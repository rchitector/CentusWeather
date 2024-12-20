<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface MailingServiceInterface
{
    public function sendWeatherAlert(User $user, Collection $cities): void;
}

