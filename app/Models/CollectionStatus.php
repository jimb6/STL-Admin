<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\BaseScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollectionStatus extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'color_field'];

    protected $searchableFields = ['*'];

    protected $table = 'collection_statuses';


    public function collectionRecords()
    {
        return $this->hasMany(CollectionRecord::class);
    }
}
