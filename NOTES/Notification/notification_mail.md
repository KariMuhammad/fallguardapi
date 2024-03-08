# Notification Mails

In this section, we will learn how to send the notifications to the users by email.

## 1. Create a Notification

```bash
php artisan make:notification InvoicePaid
```

```php
class InvoicePaid extends Notification
{
    use Queueable;

    /**
     * The notifiable is the thing which implements Notifiable interface.
     *
     * it may `User` model, `Store`, `Vendor`, etc.
     *
     * $notifiable is dependency injection, so you can use it with any class you want implements Notifable.
     *
     * (Code to interface, not to Concretion)
     */
    public function via($notifiable)
    {
        return ['mail'];
        // here you can return ['mail', 'database', 'broadcast', 'nexmo', 'slack', 'pusher', 'twilio', 'firebase', 'web-push', 'signal', etc.]

        // any number of channels you want to send the notification to

        // each channel you what return, you should make configuration for it
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('The subject of the mail')
                    ->greeting('Hello!')
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
                    // ->view('emails.invoice.paid'); // you can use this method to use custom view instead of previous default one
    }
}
```

### Why we accept #notifiable as parameter in `via` and `toMail` methods?

because in platforms, it gives you the ability to allow to custom what you want to notify for it and what not, like you want to be notified by **email**, but not by **SMS** and **real-time**, etc.

$notifiable has all information about the notifiable thing, so you can based on it to make the decision.
