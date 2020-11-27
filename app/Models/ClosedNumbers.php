<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosedNumbers extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_value', 'closed_by'
    ];
}
