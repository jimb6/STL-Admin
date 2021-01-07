<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['key', 'value'];

    protected $searchableFields = ['*'];


}
