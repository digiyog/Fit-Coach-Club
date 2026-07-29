<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'config_name',
        'config_value',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
