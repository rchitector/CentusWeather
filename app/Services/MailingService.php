<?php

namespace App\Services;


use App\Mail\SevereWeatherExpected;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class MailingService
{
    public static function sendWeatherAlert(User $user, Collection $cities): void
    {
        Mail::to($user)->send(new SevereWeatherExpected($cities));
    }
}
