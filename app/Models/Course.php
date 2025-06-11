<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'is_premium',
    ];


    // don't display deleted_at timestamp
    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];
    
    // always add tags relationship to model
    protected $with = ['tags'];

    public function tags(): BelongsToMany 
    {
        return $this->belongsToMany(Tag::class);
    }
}
