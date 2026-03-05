<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseProduct extends Model
{
    protected $table = "franchise_products";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
