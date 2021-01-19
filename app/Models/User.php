<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\ClusterScope;
use App\Scopes\StatusScope;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Auth\Passwords\CanResetPassword;
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
    use HasFactory, Notifiable, HasRoles, HasApiTokens, Searchable, SoftDeletes, CanResetPassword;

    protected $fillable = ['name', 'birthdate', 'gender', 'address_id', 'contact_number', 'email', 'cluster_id', 'password', 'status'];
    protected $searchableFields = ['*'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean'
    ];

    public static function booted()
    {
        if (Auth::check() && !Auth::user()->hasRole(['super-admin'])) {
            static::addGlobalScope(new ClusterScope);
            static::addGlobalScope(new StatusScope);
        }
    }

    public function setEmailAttribute($value) {
        if ( empty($value) ) { // will check for empty string
            $this->attributes['email'] = NULL;
        } else {
            $this->attributes['email'] = $value;
        }
    }

    protected function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }

    protected function getBirthdateAttribute($date)
    {
        return Carbon::parse($this->attributes['birthdate'])->format('m/d/Y');
    }

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return $this->email . ' - ' . strtoupper(implode("", $this->userRole()->toArray()));
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

    public function betTransactions()
    {
        return $this->hasMany(BetTransaction::class);
    }

}
