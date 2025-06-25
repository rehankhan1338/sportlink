<?php

namespace App\Models;

use App\Models\Traits\ValidatesMatches;
use App\Notifications\MatchScheduled;
use App\Notifications\MatchUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class TournamentMatch extends Model
{
    use HasFactory, ValidatesMatches;

    protected $table = 'tournament_matches';

    protected $fillable = [
        'event_id',
        'division_id',
        'division_type',
        'bracket_type',
        'round_number',
        'round_type',
        'match_number',
        'mat_number',
        'competitor_1_id',
        'competitor_2_id',
        'winner_id',
        'loser_id',
        'parent_match1_id',
        'parent_match2_id',
        'status',
        'score_details',
        'start_time',
        'created_by',
        'audit_log'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'score_details' => 'array',
        'audit_log' => 'array'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_WALKOVER = 'walkover';

    // Round type constants
    const ROUND_QUARTERFINAL = 'quarterfinal';
    const ROUND_SEMIFINAL = 'semifinal';
    const ROUND_FINAL = 'final';
    const ROUND_BRONZE = 'bronze';
    const ROUND_WINNERS = 'winners_round';
    const ROUND_LOSERS = 'losers_round';
    const ROUND_GRAND_FINAL = 'grand_final';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function division()
    {
        return $this->belongsTo(TournamentDivision::class, 'division_id');
    }

    public function competitor1()
    {
        return $this->belongsTo(Profile::class, 'competitor_1_id');
    }

    public function competitor2()
    {
        return $this->belongsTo(Profile::class, 'competitor_2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Profile::class, 'winner_id');
    }

    public function loser()
    {
        return $this->belongsTo(Profile::class, 'loser_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function parentMatch1()
    {
        return $this->belongsTo(TournamentMatch::class, 'parent_match1_id');
    }

    public function parentMatch2()
    {
        return $this->belongsTo(TournamentMatch::class, 'parent_match2_id');
    }

    public function childMatches()
    {
        return $this->hasMany(TournamentMatch::class, 'parent_match1_id')
            ->orWhere('parent_match2_id', $this->id);
    }

    public function logAuditChange($adminId, $change)
    {
        $currentLog = $this->audit_log ?? [];
        $currentLog[] = [
            'admin_id' => $adminId,
            'change' => $change,
            'timestamp' => now()->toDateTimeString()
        ];
        $this->audit_log = $currentLog;
        $this->save();
    }

    public function markWalkover($winnerId)
    {
        $this->validateMatchEditable();

        $this->status = self::STATUS_WALKOVER;
        $this->winner_id = $winnerId;
        $this->loser_id = $winnerId === $this->competitor_1_id ? $this->competitor_2_id : $this->competitor_1_id;
        $this->save();

        // Notify participants
        $this->notifyParticipants('walkover');
    }

    public function updateParticipants($competitor1Id, $competitor2Id)
    {
        $this->validateMatchEditable();
        $this->validateMatchParticipants($competitor1Id, $competitor2Id);

        $changes = [];
        if ($this->competitor_1_id !== $competitor1Id) {
            $changes['competitor_1_id'] = $competitor1Id;
        }
        if ($this->competitor_2_id !== $competitor2Id) {
            $changes['competitor_2_id'] = $competitor2Id;
        }

        if (!empty($changes)) {
            $this->update($changes);
            $this->notifyParticipants('participants_updated', $changes);
        }
    }

    public function updateSchedule($matNumber, $startTime)
    {
        $this->validateMatchEditable();

        $changes = [];
        if ($this->mat_number !== $matNumber) {
            $changes['mat_number'] = $matNumber;
        }
        if ($this->start_time->ne($startTime)) {
            $changes['start_time'] = $startTime;
        }

        if (!empty($changes)) {
            $this->update($changes);
            $this->notifyParticipants('schedule_updated', $changes);
        }
    }

    public function startMatch()
    {
        $this->validateMatchEditable();

        if (!$this->competitor_1_id || !$this->competitor_2_id) {
            throw ValidationException::withMessages([
                'participants' => ['Both competitors must be set before starting the match']
            ]);
        }

        $this->status = self::STATUS_IN_PROGRESS;
        $this->save();

        $this->notifyParticipants('match_started');
    }

    public function updateScore($competitor1Score, $competitor2Score)
    {
        $this->validateMatchEditable();

        if ($this->status !== self::STATUS_IN_PROGRESS) {
            throw ValidationException::withMessages([
                'status' => ['Can only update score for matches in progress']
            ]);
        }

        $this->score_details = [
            'competitor1_score' => $competitor1Score,
            'competitor2_score' => $competitor2Score
        ];
        $this->save();

        $this->notifyParticipants('score_updated');
    }

    public function completeMatch($winnerId)
    {
        $this->validateMatchEditable();

        if ($this->status !== self::STATUS_IN_PROGRESS) {
            throw ValidationException::withMessages([
                'status' => ['Can only complete matches that are in progress']
            ]);
        }

        if (!in_array($winnerId, [$this->competitor_1_id, $this->competitor_2_id])) {
            throw ValidationException::withMessages([
                'winner_id' => ['Winner must be one of the match competitors']
            ]);
        }

        $this->status = self::STATUS_COMPLETED;
        $this->winner_id = $winnerId;
        $this->loser_id = $winnerId === $this->competitor_1_id ? $this->competitor_2_id : $this->competitor_1_id;
        $this->save();

        $this->notifyParticipants('match_completed');
        $this->updateChildMatches();
    }

    protected function updateChildMatches()
    {
        // Update child matches based on bracket type and round
        $childMatches = $this->childMatches;
        foreach ($childMatches as $childMatch) {
            if ($childMatch->parent_match1_id === $this->id) {
                $childMatch->competitor_1_id = $this->winner_id;
            } else {
                $childMatch->competitor_2_id = $this->winner_id;
            }
            $childMatch->save();
        }
    }

    protected function notifyParticipants($type, $changes = [])
    {
        $participants = Profile::whereIn('id', [$this->competitor_1_id, $this->competitor_2_id])->get();

        foreach ($participants as $participant) {
            switch ($type) {
                case 'schedule_updated':
                case 'participants_updated':
                    $participant->notify(new MatchUpdated($this, $changes));
                    break;
                case 'match_started':
                case 'match_completed':
                case 'walkover':
                case 'score_updated':
                    $participant->notify(new MatchUpdated($this, ['status' => $this->status]));
                    break;
                default:
                    $participant->notify(new MatchScheduled($this));
            }
        }
    }

    public function isEditable()
    {
        return !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_WALKOVER]) &&
            !$this->childMatches()->whereNotNull('winner_id')->exists();
    }
} 