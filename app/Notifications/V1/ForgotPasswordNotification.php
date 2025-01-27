<?php

namespace App\Notifications\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $code, public string $url)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)
            ->subject(__('Reset Your Password'))
            ->greeting(__('Hello, :name!', ['name' => $notifiable->name]))
            ->line(__('We received a request to reset the password for your account. If you did not make this request, you can ignore this email.'))
            ->line(__('To reset your password, please click the button below to proceed:'))
            ->action(__('Reset Password'), $this->url)
            ->line(__('If the button doesn’t work, you can manually enter the verification code below on the reset page:'))
            ->line(__('Verification Code: :code', ['code' => $this->code]))
            ->line(__('This link and code will expire in :count minutes. For your security, please complete the process before the expiration time.', ['count' => config('settings.access_codes.reset_password_expiration', 180)]))
            ->line(__('If you have any issues, please contact our support team.'))
            ->line(__('Thank you for using our service!'));



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
