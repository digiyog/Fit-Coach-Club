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
use App\Models\User;
use DB;

class Attendance extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable;
    
    protected $table = 'attendances';

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

    // Get Attendance Register list records
    public function scopeGetAttendenceRegister($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $attendenceRegister = User::select('users.id', 'users.name', 'users.status', 'users.created_at')
        ->where("users.role_type", 'user')->where("users.created_by", $authUser->id)->with('user_attendence');

        $attendenceRegister = $attendenceRegister->withCount(['user_attendence as total_absent' => function ($query) use ($filter) {
            $query->where('type', 1)->whereMonth('date', $filter['month'])->whereYear('date', $filter['year']);
        }]);

        $attendenceRegister = $attendenceRegister->withCount(['user_attendence as total_present' => function ($query) use ($filter) {
            $query->where('type', 2)->whereMonth('date', $filter['month'])->whereYear('date', $filter['year']);
        }]);
         
        // Record filter conditions
        $attendenceRegister->where(function ($query) use ($filter) {
            // Filter
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower($filter['name'])).'%\')');
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $attendenceRegister = $attendenceRegister->whereRaw('((lower(users.name) LIKE \'%'.$search.'%\') )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name", "", "", "", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $attendenceRegister = $attendenceRegister->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $attendenceRegister = $attendenceRegister->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $attendenceRegister = $attendenceRegister->skip($offset)->take($limit);
            return $attendenceRegister->get();
        }
        else
        {
            return $attendenceRegister->get()->count();
        }
    }

    // Get Shake Intakes list records
    public function scopeGetShakeIntakes($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $shakeIntakes = Attendance::select('users.id', 'users.name', 'users.coach_name','users.days', 'attendances.date');

        $shakeIntakes->leftJoin('users', function($join){
            $join->on('attendances.user_id', '=', 'users.id');
        });

        $shakeIntakes->where("users.role_type", 'user')->where('type', 2)->where("attendances.franchise_id", $authUser->id);

        // ->where("users.role_type", 'user')->where("users.created_by", $authUser->id)->with('user_attendence');

        // $shakeIntakes = $shakeIntakes->withCount(['user_attendence as total_absent' => function ($query) use ($filter) {
        //     $query->where('type', 1)->whereMonth('date', $filter['month'])->whereYear('date', $filter['year']);
        // }]);

        // $shakeIntakes = $shakeIntakes->withCount(['user_attendence as total_present' => function ($query) use ($filter) {
        //     $query->where('type', 2)->whereMonth('date', $filter['month'])->whereYear('date', $filter['year']);
        // }]);
         
        // Record filter conditions
        $shakeIntakes->where(function ($query) use ($filter) {
            // Filter
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(users.coach_name) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(users.days) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(attendances.date) LIKE \'%'.trim(strtolower($filter['name'])).'%\' )');
            }

            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                $last_30_days = [
                    'start_date' => trim($date_range[0]) . ' 00:00:00',
                    'end_date' => trim($date_range[1]) . ' 00:00:00',
                ];
                $query->whereDate('date', '>=', $last_30_days['start_date']);
                $query->whereDate('date', '<=', $last_30_days['end_date']);
            } else {
                $query->whereDate('date', '=', date('Y-m-d'));
            }

        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $shakeIntakes = $shakeIntakes->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(users.coach_name) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(users.days) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(attendances.date) LIKE \'%'.trim(strtolower($filter['name'])).'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name", "coach_name", "", "days", "date");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $shakeIntakes = $shakeIntakes->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $shakeIntakes = $shakeIntakes->orderBy('date', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $shakeIntakes = $shakeIntakes->skip($offset)->take($limit);
            return $shakeIntakes->get();
        }
        else
        {
            return $shakeIntakes->get()->count();
        }
    }

    // Get Counsellings list records
    public function scopeGetCounsellings($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        if (!$authUser) {
            return !empty($limit) ? collect([]) : 0;
        }

        $counsellings = Attendance::select(
            'users.id',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.profile_image',
            'users.coach_name',
            'users.days',
            'users.user_type',
            'users.status',
            'users.due_amount',
            'users.starting_weight',
            'users.current_weight',
            'users.created_at as user_created_at',
            'attendances.id as attendance_id',
            'attendances.weight as attendance_weight',
            'attendances.date',
            'attendances.created_at as attendance_time',
            'meal_types.id as meal_type_id',
            'meal_types.name as meal_type_name',
            DB::raw('(SELECT COUNT(a2.id) FROM attendances AS a2 WHERE a2.user_id = users.id AND a2.type = 2 AND a2.deleted_at IS NULL) as total_attendance'),
            DB::raw('(SELECT a_prev.weight FROM attendances AS a_prev WHERE a_prev.user_id = users.id AND a_prev.type = 2 AND a_prev.deleted_at IS NULL AND a_prev.weight > 0 AND (a_prev.date < attendances.date OR (a_prev.date = attendances.date AND a_prev.id < attendances.id)) ORDER BY a_prev.date DESC, a_prev.id DESC LIMIT 1) as previous_weight')
        );

        $counsellings->leftJoin('users', function($join){
            $join->on('attendances.user_id', '=', 'users.id');
        });

        $counsellings->leftJoin('meal_types', function($join){
            $join->on('users.meal_type_id', '=', 'meal_types.id');
        });

        $counsellings->where("users.role_type", 'user')
            ->where('attendances.type', 2)
            ->where("attendances.franchise_id", $authUser->id);

        // Record filter conditions
        $counsellings->where(function ($query) use ($filter) {
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $nameFilter = trim(strtolower($filter['name']));
                $query->whereRaw('(lower(users.name) LIKE \'%'.$nameFilter.'%\' || lower(users.coach_name) LIKE \'%'.$nameFilter.'%\' || lower(users.days) LIKE \'%'.$nameFilter.'%\' || lower(attendances.date) LIKE \'%'.$nameFilter.'%\' || lower(meal_types.name) LIKE \'%'.$nameFilter.'%\' )');
            }

            if (!empty($filter) && !empty($filter['coach_name'])) {
                $query->where('users.coach_name', $filter['coach_name']);
            }

            if (!empty($filter) && !empty($filter['plan_id'])) {
                $query->where('users.meal_type_id', $filter['plan_id']);
            }

            $tab = $filter['tab'] ?? 'completed';

            if ($tab == 'today') {
                $query->whereDate('attendances.date', '=', date('Y-m-d'));
            } elseif ($tab == 'pending') {
                $query->where(function($q) {
                    $q->where('users.days', '<=', 5)
                      ->orWhere('users.due_amount', '>', 0);
                });
                if (!empty($filter['date'])) {
                    $dateVal = date('Y-m-d', strtotime(str_replace('/', '-', $filter['date'])));
                    $query->whereDate('attendances.date', '<=', $dateVal);
                }
            } else { // 'completed'
                if (!empty($filter['date'])) {
                    $dateVal = date('Y-m-d', strtotime(str_replace('/', '-', $filter['date'])));
                    $query->whereDate('attendances.date', '=', $dateVal);
                } else {
                    $query->whereDate('attendances.date', '=', date('Y-m-d'));
                }
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $searchStr = trim(strtolower($search));
            $counsellings = $counsellings->whereRaw('(lower(users.name) LIKE \'%'.$searchStr.'%\' || lower(users.coach_name) LIKE \'%'.$searchStr.'%\' || lower(meal_types.name) LIKE \'%'.$searchStr.'%\' || lower(users.mobile_number) LIKE \'%'.$searchStr.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && isset($sort['column']) && $sort['column'] > 0)
        {
            $arr_fields = array("", "", "users.name", "total_attendance", "users.coach_name", "meal_types.name", "users.days", "attendances.weight", "users.due_amount", "meal_types.name", "attendances.created_at", "");
            if(isset($arr_fields[$sort['column']]) && $arr_fields[$sort['column']] != "")
            {
                $counsellings = $counsellings->orderBy($arr_fields[$sort['column']], $sort['dir'] ?? 'ASC');
            }
        }
        else
        {
            $counsellings = $counsellings->orderBy('attendances.created_at', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $counsellings = $counsellings->skip($offset)->take($limit);
            return $counsellings->get();
        }
        else
        {
            return $counsellings->count();
        }
    }

    // Get Weights list records
    public function scopeGetViewWeights($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $viewWeights = Attendance::select('attendances.id', 'users.name', 'attendances.weight','attendances.weight_image', 'attendances.date', 'attendances.created_at');

        $viewWeights->leftJoin('users', function($join){
            $join->on('attendances.user_id', '=', 'users.id');
        });

        $viewWeights->where("users.role_type", 'user')->where('type', 2)->where('user_id', $filter['user_id'])->where("attendances.franchise_id", $authUser->id);
         
        // Record filter conditions
        $viewWeights->where(function ($query) use ($filter) {
            // Filter
            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                $last_30_days = [
                    'start_date' => trim($date_range[0]) . ' 00:00:00',
                    'end_date' => trim($date_range[1]) . ' 00:00:00',
                ];
                $query->whereDate('date', '>=', $last_30_days['start_date']);
                $query->whereDate('date', '<=', $last_30_days['end_date']);
            } else {
                // $query->whereDate('date', '=', date('Y-m-d'));
            }

        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $viewWeights = $viewWeights->whereRaw('(lower(attendances.weight) LIKE \'%'.trim(strtolower($filter['name'])).'%\' || lower(attendances.date) LIKE \'%'.trim(strtolower($filter['name'])).'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "weight","", "date");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $viewWeights = $viewWeights->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $viewWeights = $viewWeights->orderBy('date', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $viewWeights = $viewWeights->skip($offset)->take($limit);
            return $viewWeights->get();
        }
        else
        {
            return $viewWeights->get()->count();
        }
    }

    // Get Attendence list records
    public function scopeGetAttendences($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        // p($filter);

        $attendences = Attendance::select('id','weight', 'attendances.date');
        $attendences->where('type', 2)->where("user_id", $filter['user_id']);
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", 'weight', "date", "", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $attendences = $attendences->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $attendences = $attendences->orderBy('date', 'DESC')->orderBy('id', 'DESC');
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

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


