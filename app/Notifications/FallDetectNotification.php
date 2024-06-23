<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FallDetectNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;
    public User $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $patient)
    {
        $this->patient = $patient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Fall Detected',
            'patient' => $this->patient,
            // I want this message to navigate to the patient's profile in flutter app, how to do that?
            'url' => env("APP_URL")."/api/v1/patients/falls/{$this->patient->id}",
            'icon' => 'https://via.placeholder.com/150',
            'message' => "{$this->patient->name} has fall down!"
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Fall Detected',
            'patient' => $this->patient,
            'message' => "{$this->patient->name} has fall down!"
        ];
    }

    public function broadcastOn() {
        return ['fall-channel'];
    }

    public function broadcastAs() {
        return 'fall-event';
    }
}
