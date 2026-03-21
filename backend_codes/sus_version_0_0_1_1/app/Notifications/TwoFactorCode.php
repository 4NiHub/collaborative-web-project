<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCode extends Notification
{
    use Queueable;

    protected string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your SUS Portal Verification Code')
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('Use the code below to complete your sign-in to the Smart University System.')
            ->line('')
            ->line("**{$this->code}**")
            ->line('')
            ->line('This code expires in **10 minutes** and can only be used once.')
            ->line('If you did not request this, please change your password immediately and contact IT support.')
            ->salutation('— SUS Portal Security Team');
    }
}
