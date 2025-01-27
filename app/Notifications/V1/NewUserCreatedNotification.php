<?php

namespace App\Notifications\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $password
    ) {
        //
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
        return (new MailMessage)
            ->subject(__('Welcome to Our Application'))
            ->greeting(__('Hello, :name!', ['name' => $notifiable->name]))
            ->line(__('We’re excited to let you know that your account has been created successfully.'))
            ->line(__('You can log in using either your email or phone number, along with the password provided below:'))
            ->line(__('**Email:** :email', ['email' => $notifiable->email]))
            ->line(__('**Phone:** :phone', ['phone' => $notifiable->phone]))
            ->line(__('**Password:** :password', ['password' => $this->password]))
            ->line(__('Click the button below to log in to your account:'))
            ->action(__('Log in to Your Account'), url('/login'))
            ->line(__('For security purposes, we recommend changing your password after logging in.'))
            ->line(__('If you have any questions or need assistance, feel free to contact our support team.'))
            ->line(__('Thank you for joining us!'));

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
