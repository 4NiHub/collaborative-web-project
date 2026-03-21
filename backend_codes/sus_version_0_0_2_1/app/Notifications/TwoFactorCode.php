<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TwoFactorCode extends Notification
{
    use Queueable;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Load name from the related profile (student / staff / mentor)
        $name = 'User'; // fallback

        if ($student = $notifiable->student) {
            $name = trim($student->name . ' ' . $student->surname);
        } elseif ($staff = $notifiable->staff) {
            $name = trim($staff->name . ' ' . $staff->surname);
        } 
        // elseif ($mentor = $notifiable->mentor) {
        //     $name = trim($mentor->name . ' ' . $mentor->surname);
        // }

        return (new MailMessage)
            ->subject('Your SUS Portal Verification Code')
            ->greeting("Hello {$name}!")
            ->line("Use this 6-digit code to complete your sign-in:")
            ->line("**{$this->code}**")
            ->line('This code expires in **3 minutes** for security reasons.')
            ->line('If you did not request this code, please contact support immediately.')
            ->salutation('Best regards, SUS Portal Team');
    }
}