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
        $attendences = AttendanceLogs::select('id', 'remark', 'date', 'days', 'total_days', 'message');
        if (!empty($filter['user_id'])) {
            $attendences->where("user_id", $filter['user_id']);
        }
        
        if (!empty($search)) {
            $searchStr = trim(strtolower($search));
            $attendences->where(function($q) use ($searchStr) {
                $q->whereRaw('lower(remark) LIKE ?', ["%{$searchStr}%"])
                  ->orWhereRaw('lower(message) LIKE ?', ["%{$searchStr}%"])
                  ->orWhereRaw('lower(date) LIKE ?', ["%{$searchStr}%"]);
            });
        }

        if (!empty($filter['activity'])) {
            $attendences->where('remark', $filter['activity']);
        }

        if (!empty($filter['source'])) {
            if ($filter['source'] === 'App Side') {
                $attendences->where('remark', 'QR Attendance Add');
            } elseif ($filter['source'] === 'Admin Panel') {
                $attendences->where('remark', '!=', 'QR Attendance Add');
            }
        }

        // Table columns sort conditions
        if(!(empty($sort)) && isset($sort['column']) && $sort['column'] > 0)
        {
            $arr_fields = array("", "id", "date", 'total_days', "days", "remark", "message");
            if (isset($arr_fields[$sort['column']]) && $arr_fields[$sort['column']] != "") {
                $attendences = $attendences->orderBy($arr_fields[$sort['column']], $sort['dir'] ?? 'DESC');
            }
        } else {
            $attendences = $attendences->orderBy('date', 'DESC')->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            return $attendences->skip($offset)->take($limit)->get();
        }
        else
        {
            return $attendences->count();
        }
    }
}

