<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCity extends Model
{
    use HasFactory;

    protected $table = 'user_cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'country',
        'state',
        'lat',
        'lon',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lon' => 'float',
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

    /**
     * Get the city's full location as "City Name (State, Country)".
     * Returns only the city name if state or country is unavailable.
     * @return string|null
     */
    public function getFullLocationAttribute(): string|null
    {
        $location = collect([$this->country ?? null, $this->state ?? null,])->filter()->join(', ');
        if (!empty($location)) {
            return $this->name . " ($location)";
        }
        return $this->name;
    }

    /**
     * Get User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
