<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    use Searchable;
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'base_id', 'api_token'];

    protected $searchableFields = ['*'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return Auth::user()->email.' - '.strtoupper(implode("", $this->userRole()->toArray()));
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }

    public function userRole()
    {
        return Auth::user()->getRoleNames();
    }

    public function base()
    {
        return $this->belongsTo('App\Models\Base');
    }
}
