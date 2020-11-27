<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'transaction_code', 'transaction_date', 'agent_id', 'booth_id'
    ];

    public function bets()
    {
        return $this->hasMany('App\Models\BetTransaction');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Agent');
    }

    public function booth()
    {
        return $this->belongsTo('App\Models\Booth');
    }


}
