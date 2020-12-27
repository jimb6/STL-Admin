<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameConfiguration extends Model
{
    use HasFactory;

    protected $fillable = ['field_set', 'days_availability', 'max_per_bet', 'transaction_limit', 'multiplier', 'max_sum_bet', 'is_rumbled', 'has_repetition',
    'digit_per_field_set', 'field_set'];

    protected $casts = [
        'days_availability' => 'array',
        'has_repetition' => 'boolean',
        'is_rumbled' => 'boolean'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
