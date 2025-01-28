<?php

namespace App\Notifications\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AutoUserPasswordResetNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $password)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your Password Has Been Reset'))
            ->greeting(__('Hello, :name!', ['name' => $notifiable->name]))
            ->line(__('This is to inform you that your password has been successfully reset by the administrator.'))
            ->line(__('Here are your new login credentials:'))
            ->line(__('**Password:** :password', ['password' => $this->password]))
            ->line(__('We recommend that you log in immediately and update your password to something secure and memorable.'))
            ->action(__('Log in to Your Account'), url('/'))
            ->line(__('If you did not request this password reset or believe this was a mistake, please contact our support team immediately.'))
            ->line(__('Thank you for using our application!'));

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Password Reset Notification'),
            'body' => __(
                'Your account password has been reset by an administrator. Please check your email for the new password and instructions on how to securely log in. If you did not request this change, please contact support immediately.'
            ),
        ];

    }
}
