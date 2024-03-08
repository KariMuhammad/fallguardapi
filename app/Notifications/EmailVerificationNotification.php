<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    use Queueable;
    private $otp;
    
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
        $this->otp = new Otp();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email, "numeric", 6, 10);
        return (new MailMessage)
                    // ->subject("Verify Email Address")
                    // ->greeting("Hi {{$notifiable->name}}")
                    // ->line("Please use the following code to verify your email address: " . $otp->token)
                    // // ->action('Verify Email Address', url('/verify-email', ['token' => $otp->token, 'email' => $notifiable->email]))
                    // ->line('Thank you for using our application!');
                    ->view('vendor.notifications.email', [
                        'otp' => $otp->token, 
                        'user' => $notifiable, 
                        // "introLines" => ["Please use the following code to verify your email address: "],
                        // "actionText" => "Verify Email Address",
                        // "actionUrl" => url('/verify-email', ['token' => $otp->token, 'email' => $notifiable->email]),
                        // "outroLines" => ["Thank you for using our application!"],
                        "logo" => "https://res.cloudinary.com/dpr9selqa/image/upload/v1709916877/ivf95hxdxfiov8ykmvqt.png",
                    ]);

        // response()->json(['message' => 'Email sent successfully'], 200);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
