<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'city_name',
        'city_country',
        'city_state',
        'city_lat',
        'city_lon',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'city_lat' => 'float',
            'city_lon' => 'float',
        ];
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_location'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the city's full location as "City Name (State, Country)".
     *
     * Returns only the city name if state or country is unavailable.
     *
     * @return string
     */
    public function getFullLocationAttribute(): string
    {
        $location = collect([$this->city_country ?? null, $this->city_state ?? null,])->filter()->join(', ');
        if (!empty($location)) {
            return $this->city_name . " ($location)";
        }
        return $this->city_name;
    }

}
