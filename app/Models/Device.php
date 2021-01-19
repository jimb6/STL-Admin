<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\DeviceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class Device extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['serial_number',  'device_code', 'user_id', 'cluster_id'];

    protected $searchableFields = ['*'];

    public static function booted()
    {
        if (Auth::check() && !Auth::user()->hasRole(['super-admin']))
            static::addGlobalScope(new DeviceScope);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $cluster = Cluster::where('id', $model->cluster_id)->first();
            $shortName = strtoupper(str_replace(' ', '', $cluster->name));
            $generatedKey = strlen($shortName) < 5 ? $shortName
                : substr($shortName, 0,strlen($cluster->name) / 2);
            $devicesCount = Device::where('cluster_id', $model->cluster_id)
                ->orWhere('device_code', 'like', "%{$generatedKey}%")
                ->orderBy('device_code', 'DESC')
                ->count();
            $generatedKey = $devicesCount!=null
                ?$generatedKey.'-'.str_pad($devicesCount++,5,"0",STR_PAD_LEFT)
                :$generatedKey.'-'.str_pad(1,5,"0",STR_PAD_LEFT);

            $model->setAttribute('device_code', $generatedKey);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

}
