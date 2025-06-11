<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasUuids;
    use HasFactory;

    // table doesn't use timestamps
    public $timestamps = false;

    // hide pivot table from model results
    protected $hidden = ['pivot'];

    protected $fillable = [
        'name'
    ];
}
