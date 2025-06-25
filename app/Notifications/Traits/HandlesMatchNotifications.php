<?php

namespace App\Notifications\Traits;

use App\Models\Match;
use App\Models\Profile;

trait HandlesMatchNotifications
{
    protected function getMatchDetails()
    {
        return [
            'event' => $this->matchData->event,
            'division' => $this->matchData->division,
            'scheduled_time' => $this->matchData->scheduled_time,
            'mat_name' => $this->matchData->mat_name,
            'status' => $this->matchData->status,
            'player1' => $this->matchData->player1,
            'player2' => $this->matchData->player2,
        ];
    }

    protected function getOpponentFor($notifiable)
    {
        return $this->matchData->player1_id === $notifiable->id 
            ? $this->matchData->player2
            : $this->matchData->player1;
    }

    protected function getMatchUrl()
    {
        $event = $this->matchData->event;
        $division = $this->matchData->division;
        return url("/events/{$event->id}/brackets/{$division->id}");
    }

    protected function formatMatchTime($time)
    {
        return $time->format('l, F j, Y \a\t g:i A');
    }

    protected function getNotificationData()
    {
        return [
            'match_id' => $this->matchData->id,
            'event_id' => $this->matchData->event_id,
            'division_id' => $this->matchData->division_id,
            'scheduled_time' => $this->matchData->scheduled_time,
            'mat_name' => $this->matchData->mat_name,
        ];
    }
} 