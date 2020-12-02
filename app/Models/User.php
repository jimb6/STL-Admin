<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
//use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    use HasRoles;
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'base_id'];

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
        return Auth::user()->email;
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
