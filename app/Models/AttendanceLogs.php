<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Http\Traits\HasSlug;

class AttendanceLogs extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable;
    
    protected $table = 'attendance_logs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    // Get Attendence list records
    public function scopeGetAttendences($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        // p($filter);

        $attendences = AttendanceLogs::select('id','remark', 'date', 'days', 'total_days', 'message');
        $attendences->where("user_id", $filter['user_id']);
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", 'total_days', "days", "remark", "", 'message', '');
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $attendences = $attendences->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $attendences = $attendences->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $attendences = $attendences->skip($offset)->take($limit);
            return $attendences->get();
        }
        else
        {
            return $attendences->get()->count();
        }
    }
}

