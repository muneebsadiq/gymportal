<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'gym_name', 'gym_email', 'gym_phone', 'gym_address',
        'currency', 'currency_symbol', 'timezone',
        'opening_time', 'closing_time', 'working_days',
        'logo', 'about'
    ];

    protected $casts = [
        'working_days' => 'array',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];

    /**
     * Get the singleton settings instance
     */
    public static function get()
    {
        return static::first() ?? static::create([
            'gym_name' => 'Fitness Gym',
            'currency' => 'PKR',
            'currency_symbol' => 'Rs',
        ]);
    }
}
