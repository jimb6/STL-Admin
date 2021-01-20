<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'field_set',
        'digit_per_field_set',
        'has_repetition',
        'is_rumbled',
        'max_sum_bet',
        'multiplier',
        'transaction_limit',
        'min_per_bet',
        'max_per_bet',
        'days_availability',
        ];

    protected $casts = [
        'days_availability' => 'array',
        'has_repetition' => 'boolean',
        'is_rumbled' => 'boolean',
        'in_exact_order' => 'boolean'
    ];

    protected $table = 'game_configurations';

    public function getDaysAvailabilityAttribute($days){
        return json_decode($this->attributes['days_availability']);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
