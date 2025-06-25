<?php

namespace App\Notifications;

use App\Models\Match;
use App\Notifications\Traits\HandlesMatchNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatchScheduled extends Notification implements ShouldQueue
{
    use Queueable, HandlesMatchNotifications;

    protected $matchData;

    public function __construct($matchModel)
    {
        $this->matchData = $matchModel;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $details = $this->getMatchDetails();
        $opponent = $this->getOpponentFor($notifiable);

        return (new MailMessage)
            ->subject("Match Scheduled - {$details['event']->name}")
            ->line("Your match has been scheduled for " . $this->formatMatchTime($details['scheduled_time']))
            ->line("Event: {$details['event']->name}")
            ->line("Division: {$details['division']->name}")
            ->line("Mat: {$details['mat_name']}")
            ->line("Opponent: " . ($opponent ? $opponent->name : 'TBD'))
            ->action('View Match Details', $this->getMatchUrl());
    }

    public function toArray($notifiable)
    {
        return array_merge($this->getNotificationData(), [
            'type' => 'match_scheduled'
        ]);
    }
} 