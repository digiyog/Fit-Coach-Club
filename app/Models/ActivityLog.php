<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use Orderable, Statusable, StatusToggleable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'activity_type',
        'activity_module',
        'message',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getCreatedAtAttribute($date)
    {
        return (!empty($date) ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return (!empty($date) ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date);
    }

    /**
     * Get user by activity log
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * Get user by activity log
     */
    public function user_activity_by()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }
}