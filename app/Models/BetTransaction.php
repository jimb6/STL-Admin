<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'game_category_id', 'draw_period_id', 'combination', 'amount'
    ];

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }

    public function drawPeriod()
    {
        return $this->belongsTo('App\Models\DrawPeriod');
    }

    public function gameCategory()
    {
        return $this->belongsTo('App\Models\GameCategory', 'game_category_id','id');
    }
}
