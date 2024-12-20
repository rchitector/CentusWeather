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
        'rain_enabled',
        'snow_enabled',
        'uvi_enabled',
        'rain_value',
        'snow_value',
        'uvi_value',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rain_enabled' => 'boolean',
            'snow_enabled' => 'boolean',
            'uvi_enabled' => 'boolean',
            'rain_value' => 'float',
            'snow_value' => 'float',
            'uvi_value' => 'float',
        ];
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

    public function scopeEnabledSettings($query)
    {
        return $query->whereHas('settings', function ($query) {
            $query->where('rain_enabled', true)
                ->orWhere('snow_enabled', true)
                ->orWhere('uvi_enabled', true);
        });
    }


}
