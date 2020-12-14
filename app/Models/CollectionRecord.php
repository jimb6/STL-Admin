<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Scopes\ClusterScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollectionRecord extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'agent_id',
        'collectionAmount',
        'collectionDate',
        'remarks',
        'collection_status_id',
    ];


    protected $searchableFields = ['*'];

    protected $table = 'collection_records';

    protected $casts = [
        'collectionDate' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function collectionStatus()
    {
        return $this->belongsTo(CollectionStatus::class);
    }
}
