<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\ClusterScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, Searchable, SoftDeletes;

    protected $fillable = ['name', 'birthdate', 'gender', 'address_id', 'contact_number', 'email', 'cluster_id', 'password', 'api_token'];
    protected $searchableFields = ['*'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function booted()
    {
        static::addGlobalScope(new ClusterScope);
    }


    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return Auth::user()->email . ' - ' . strtoupper(implode("", $this->userRole()->toArray()));
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }

    public function userRole()
    {
        return Auth::user()->getRoleNames();
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function device()
    {
        return $this->hasOne(Device::class);
    }
}
