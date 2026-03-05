<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;

class CronJobs extends Model
{
    use Orderable, Statusable, StatusToggleable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'start_time',
        'end_time',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}