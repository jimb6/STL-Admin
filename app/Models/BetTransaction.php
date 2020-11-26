<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code', 'game_category', 'draw_period', 'combination', 'amount'
    ];

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }
}
