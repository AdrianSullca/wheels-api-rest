<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->from('no-reply@wheels.com', 'Wheels')
            ->subject('Verify your email address')
            ->view('emails.verify-email', [
                'url' => $verificationUrl,
                'appLogo' => asset('https://res.cloudinary.com/dxvjedi2n/image/upload/v1737025409/juqmr8els9dsdlqkdhzs.png'),
                'appName' => 'Wheels',
            ]);
    }

    protected function verificationUrl($notifiable)
    {
        $frontendUrl = config('app.frontend_url');
        $url = parent::verificationUrl($notifiable);

        return $frontendUrl . '/auth?mode=login&url=' . urlencode($url);
    }
}
