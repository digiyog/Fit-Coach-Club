<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityImage extends Model
{
    protected $table = "community_images";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
