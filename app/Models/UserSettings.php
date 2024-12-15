<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'city_name',
        'city_country',
        'city_state',
        'city_lat',
        'city_lon',
    ];


    protected $casts = [
        'city_lat' => 'float',
        'city_lon' => 'float',
    ];

    protected $appends = [
        'full_location'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullLocationAttribute()
    {
        $location = collect([$this->city_country ?? null, $this->city_state ?? null,])->filter()->join(', ');
        if (!empty($location)) {
            return $this->city_name . " ($location)";
        }
        return $this->city_name;
    }

}
