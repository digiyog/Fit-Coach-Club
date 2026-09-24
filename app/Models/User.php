<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use App\Notifications\AdminPanel\RestPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Statusable, StatusToggleable, SoftDeletes;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_type',
        'user_state',
        'name',
        'email',
        'email_verified_at',
        'country_code',
        'mobile_number',
        'mobile_number_verified_at',
        'date_of_birth',
        'password',
        'profile_image',
        'qr_code',
        'coach_name',
        'meal_type_id',
        'product_type_id',
        'days',
        'role_id',
        'role_type',
        'current_weight',
        'starting_weight',
        'age',
        'height',
        'gender',
        'discount',
        'weight_goal',
        'due_amount',
        'last_login_at',
        'last_login_ip',
        'uuid',
        'fcm_token',
        'device_id',
        'device_type',
        'device_os',
        'device_os_version',
        'device_manufacturer',
        'device_model',
        'app_version',
        'status',
        'created_by',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Get Franchises list records
    public function scopeGetFranchises($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $franchises = User::select('id', 'name', 'email' ,'mobile_number', 'status', 'end_date','start_date', 'created_at')
        ->where("users.role_type", 'franchise');
         
        // Record filter conditions
        $franchises->where(function ($query) use ($filter) {
            // Filter
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower($filter['name'])).'%\')');
            } 

            if (!empty($filter) && !empty($filter['email'])) 
            {
                $query->whereRaw('(lower(users.email) LIKE \'%'.trim(strtolower($filter['email'])).'%\')');
            }

            if (!empty($filter) && !empty($filter['mobile_number'])) 
            {
                $query->whereRaw('(lower(users.mobile_number) LIKE \'%'.trim(strtolower($filter['mobile_number'])).'%\')');
            }

            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                $last_30_days = [
                    'start_date' => trim($date_range[0]) . ' 00:00:00',
                    'end_date' => trim($date_range[1]) . ' 00:00:00',
                ];
                $query->whereDate('created_at', '>=', $last_30_days['start_date']);
                $query->whereDate('created_at', '<=', $last_30_days['end_date']);
            } else {
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $franchises = $franchises->whereRaw('((lower(users.name) LIKE \'%'.$search.'%\')  OR lower(users.email) LIKE \'%'.$search.'%\'  OR lower(users.mobile_number) LIKE \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name" , "email" , "mobile_number" , "status");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $franchises = $franchises->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $franchises = $franchises->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $franchises = $franchises->skip($offset)->take($limit);
            return $franchises->get();
        }
        else
        {
            return $franchises->get()->count();
        }
    }

    // Get Users list records
    public function scopeGetUsers($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $users = User::select('users.id', 'users.profile_image', 'users.user_type', 'users.user_state', 'users.name', 'users.email' ,'users.mobile_number', 'users.coach_name', 'users.meal_type_id', 'users.product_type_id', 'users.days', 'users.due_amount', 'users.status', 'users.created_at')
        ->where("users.role_type", 'user')->where("users.created_by", $authUser->id);

        $users = $users->with('meal_type', function($qry) use($search, $filter, $sort){
            $qry->select('id', 'name');
        });

        $users = $users->with('product_type', function($qry) use($search, $filter, $sort){
            $qry->select('id', 'name');
        });
         
        // Record filter conditions
        $users->where(function ($query) use ($filter) {
            // Search Input (name, email, mobile, coach)
            if (!empty($filter['name'])) 
            {
                $nameSearch = strtolower(trim($filter['name']));
                $query->where(function($subQ) use ($nameSearch) {
                    $subQ->whereRaw('(lower(users.name) LIKE \'%'.$nameSearch.'%\')')
                         ->orWhereRaw('(lower(users.email) LIKE \'%'.$nameSearch.'%\')')
                         ->orWhereRaw('(lower(users.mobile_number) LIKE \'%'.$nameSearch.'%\')')
                         ->orWhereRaw('(lower(users.coach_name) LIKE \'%'.$nameSearch.'%\')');
                });
            }

            if (!empty($filter['user_type'])) {
                if ($filter['user_type'] == 'demo') {
                    $query->where(function($q) {
                        $q->where('users.user_type', 'Demo User')->orWhere('users.user_type', '3 Days Trial');
                    });
                } elseif ($filter['user_type'] == 'offline') {
                    $query->where('users.user_type', 'Regular User')->where('users.user_state', 'Offline');
                } elseif ($filter['user_type'] == 'online') {
                    $query->where('users.user_type', 'Regular User')->where('users.user_state', 'Online');
                }
            }

            if (!empty($filter['email'])) 
            {
                $query->whereRaw('(lower(users.email) LIKE \'%'.trim(strtolower($filter['email'])).'%\')');
            }

            if (!empty($filter['mobile_number'])) 
            {
                $query->whereRaw('(lower(users.mobile_number) LIKE \'%'.trim(strtolower($filter['mobile_number'])).'%\')');
            }

            if (!empty($filter['coach_name'])) {
                $query->where('users.coach_name', $filter['coach_name']);
            }

            if (!empty($filter['plan_id'])) {
                $pId = $filter['plan_id'];
                $query->where(function($q) use ($pId) {
                    $q->where('users.meal_type_id', $pId)->orWhere('users.product_type_id', $pId);
                });
            }

            if (!empty($filter['payment_status'])) {
                if ($filter['payment_status'] == 'paid') {
                    $query->where(function($q) {
                        $q->whereNull('users.due_amount')->orWhere('users.due_amount', '<=', 0);
                    });
                } elseif ($filter['payment_status'] == 'pending') {
                    $query->where('users.due_amount', '>', 0);
                }
            }

            if (!empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                if (count($date_range) >= 2) {
                    $startDate = trim($date_range[0]) . ' 00:00:00';
                    $endDate = trim($date_range[1]) . ' 23:59:59';
                    $query->whereBetween('users.created_at', [$startDate, $endDate]);
                }
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $users = $users->whereRaw('((lower(users.name) LIKE \'%'.$search.'%\')  OR lower(users.email) LIKE \'%'.$search.'%\'  OR lower(users.mobile_number) LIKE \'%'.$search.'%\'  OR lower(users.coach_name) LIKE \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name", "user_type", "mobile_number", "coach_name", "meal_type_id", "days", "due_amount", "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $users = $users->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $users = $users->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $users = $users->skip($offset)->take($limit);
            return $users->get();
        }
        else
        {
            return $users->get()->count();
        }
    }

    // Get users list records
    public function scopeGetDeletedUsers($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $users = User::select('id', 'name', 'email' ,'country_code', 'role_type' ,'mobile_number','platform','status', 'created_at','email_verified_at')
        ->where("users.role_type", 'users')->onlyTrashed();
         
        // Record filter conditions
        $users->where(function ($query) use ($filter) {
            // Filter
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower($filter['name'])).'%\')');
            } 

            if (!empty($filter) && !empty($filter['email'])) 
            {
                $query->whereRaw('(lower(users.email) LIKE \'%'.trim(strtolower($filter['email'])).'%\')');
            }

            if(isset($filter['filter_platform']) && !(empty($filter['filter_platform'])))
            {
                $query->where('users.platform', $filter['filter_platform']);
            }

            if (!empty($filter) && !empty($filter['m_no'])) 
            {
                $query->whereRaw('(lower(users.mobile_number) LIKE \'%'.trim(strtolower($filter['m_no'])).'%\')');
            }
            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                $last_30_days = [
                    'start_date' => trim($date_range[0]) . ' 00:00:00',
                    'end_date' => trim($date_range[1]) . ' 00:00:00',
                ];
                $query->whereDate('created_at', '>=', $last_30_days['start_date']);
                $query->whereDate('created_at', '<=', $last_30_days['end_date']);
            } else {
                // $last_30_days = [
                //     'start_date' => date("Y-m-d", strtotime("-6 month")) . ' 00:00:00',
                //     'end_date' => date("Y-m-d") . ' 00:00:00',
                // ];
                // $query->whereDate('created_at', '>=', $last_30_days['start_date']);
                // $query->whereDate('created_at', '<=', $last_30_days['end_date']);
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $users = $users->whereRaw('((lower(users.name) LIKE \'%'.$search.'%\')  OR lower(users.email) LIKE \'%'.$search.'%\'  OR lower(users.role_type) LIKE \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name" , "email" , "role_type" , "status");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $users = $users->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $users = $users->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $users = $users->skip($offset)->take($limit);
            return $users->get();
        }
        else
        {
            return $users->get()->count();
        }
    }

    public function user_documents()
    {
        return $this->hasMany('App\Models\UserDocument');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function meal_type()
    {
        return $this->hasOne('App\Models\MealType', 'id', 'meal_type_id');
    }

    public function product_type()
    {
        return $this->hasOne('App\Models\ProductType', 'id', 'product_type_id');
    }

    public function user_attendence()
    {
        return $this->hasMany('App\Models\Attendance');
    }

    public function attendance_logs()
    {
        return $this->hasMany('App\Models\AttendanceLogs', 'user_id', 'id');
    }

    public function franchise_memberships()
    {
        return $this->hasMany('App\Models\FranchiseMembershipPlan', 'franchise_id', 'id');
    }

    /**
     * Recalculate and synchronize user pending days based on actual attendance dates and allocated packages.
     *
     * @return int
     */
    public function recalculatePendingDays()
    {
        $uniqueAttended = Attendance::where('user_id', $this->id)
            ->where('type', 2)
            ->whereNull('deleted_at')
            ->distinct('date')
            ->count('date');

        // Check if the user had an initial allocation from their first log before Add User Days was logged
        $firstLog = AttendanceLogs::where('user_id', $this->id)->orderBy('id', 'asc')->first();
        $initialFromFirstLog = 0;
        if ($firstLog && !preg_match('/add.*days/i', $firstLog->remark)) {
            $initialFromFirstLog = max(0, (int)($firstLog->total_days + $firstLog->days));
        }

        $added = (int) AttendanceLogs::where('user_id', $this->id)
            ->where('remark', 'LIKE', '%Add User Days%')
            ->sum('days');

        $subtracted = (int) AttendanceLogs::where('user_id', $this->id)
            ->where(function ($q) {
                $q->where('remark', 'LIKE', '%Subtract%')
                  ->orWhere('remark', 'LIKE', '%Substarct%');
            })
            ->sum('days');

        $totalAllocated = $initialFromFirstLog + $added;

        // If user has no logged additions yet, initialize baseline so subsequent attendances deduct cleanly
        if ($totalAllocated == 0 && (int)$this->days > 0) {
            $initialAllocated = max(0, (int)$this->days) + $uniqueAttended;
            AttendanceLogs::create([
                'user_id'    => $this->id,
                'date'       => $this->start_date ?: date('Y-m-d'),
                'remark'     => 'Add User Days',
                'message'    => 'Initial plan allocation baseline',
                'days'       => $initialAllocated,
                'total_days' => $initialAllocated,
                'created_by' => $this->created_by ?: 0,
            ]);
            $totalAllocated = $initialAllocated;
        }

        if ($totalAllocated > 0) {
            $netAllocated = max(0, $totalAllocated - $subtracted);
            $this->days = max(0, $netAllocated - $uniqueAttended);
        } else {
            $this->days = max(0, (int)$this->days);
        }

        $this->save();

        return (int)$this->days;
    }
}

