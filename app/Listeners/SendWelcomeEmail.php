<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        try {
            Mail::to($event->user->email)->send(new WelcomeMail($event->user));
        } catch (\Exception $e) {
            Log::warning('Welcome email failed: '.$e->getMessage());
        }
    }
}
