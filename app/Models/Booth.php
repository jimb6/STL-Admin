<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\ClusterScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booth extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['address_id', 'cluster_id', 'active_user_id'];

    protected $searchableFields = ['*'];

    protected static function booted()
    {
        static::addGlobalScope(new ClusterScope);
    }

    public function base()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function  address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'active_user_id', 'id');
    }

}
