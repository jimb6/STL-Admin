<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WinningBet extends Model implements Auditable
{
    use HasFactory;
    use Searchable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['transaction_code', 'winning_combination_id', 'status'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'status' => 'boolean'
    ];


    public function betTransactions()
    {
        return $this->hasMany(BetTransaction::class, 'transaction_code', 'qr_code');
    }

    public function winningCombination()
    {
        return $this->belongsTo(WinningCombination::class);
    }
}
