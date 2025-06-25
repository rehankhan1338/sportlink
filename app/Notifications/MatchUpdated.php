<?php

namespace App\Notifications;

use App\Models\Match;
use App\Models\Profile;
use App\Notifications\Traits\HandlesMatchNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatchUpdated extends Notification implements ShouldQueue
{
    use Queueable, HandlesMatchNotifications;

    protected $matchData;
    protected $changes;

    public function __construct($matchModel, array $changes)
    {
        $this->matchData = $matchModel;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $details = $this->getMatchDetails();

        $message = (new MailMessage)
            ->subject("Match Updated - {$details['event']->name}")
            ->line("Your match details have been updated:");

        foreach ($this->changes as $field => $value) {
            switch ($field) {
                case 'scheduled_time':
                    $message->line("New Time: " . $this->formatMatchTime($value));
                    break;
                case 'mat_name':
                    $message->line("New Mat: {$value}");
                    break;
                case 'status':
                    $message->line("Status: " . strtoupper($value));
                    break;
                case 'opponent_id':
                    $opponent = $value ? Profile::find($value) : null;
                    $message->line("New Opponent: " . ($opponent ? $opponent->name : 'TBD'));
                    break;
            }
        }

        return $message->action('View Match Details', $this->getMatchUrl());
    }

    public function toArray($notifiable)
    {
        return array_merge($this->getNotificationData(), [
            'changes' => $this->changes,
            'type' => 'match_updated'
        ]);
    }
} 