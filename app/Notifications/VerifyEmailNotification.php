<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Verify Your Email Address - Cloth Bank')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Thank you for registering with Cloth Bank. Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Regards, Cloth Bank Team');
    }
}
