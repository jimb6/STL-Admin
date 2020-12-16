<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\DeviceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Device extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['serial_number', 'user_id'];

    protected $searchableFields = ['*'];

    public static function booted()
    {
        static::addGlobalScope(new DeviceScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
