<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChangeVerification extends Notification
{
    use Queueable;

    public function __construct(
        public string $token,
        public string $newEmail
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('email-change.verify', [
            'token' => $this->token,
            'email' => $this->newEmail,
        ]);

        return (new MailMessage)
            ->subject('Verify Your New Email Address')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have requested to change your email address to: ' . $this->newEmail)
            ->line('Please click the button below to verify your new email address.')
            ->action('Verify Email Address', $url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request this change, please ignore this email and your email address will remain unchanged.');
    }
}