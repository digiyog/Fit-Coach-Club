<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Carbon\Carbon;

class Notification extends Model
{
    use Orderable, Statusable, StatusToggleable; 

    protected $table = 'notifications';

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

    /**
     * Get Notification sent to user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get Notification sent to user
     */
    public function user_info()
    {
        return $this->belongsTo('App\Models\User','sender_id');
    }

    /**
     * Get Notifications List Sent by Admin
     */
    public function scopeGetNotifications($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $authUser = auth()->user();

        // Get notification messages by default language
        $notificationMessage = Notification::select('notifications.id', 'notifications.user_id', 'notifications.title', 'notifications.message', 'notifications.notification_type', 'notifications.send_to', 'notifications.send_at', 'notifications.read_status', 'notifications.created_at')
        ->where('notifications.send_by', config('constants.notifications.send_by.ADMIN.value'));
        
        $notificationMessage->with([
            'user' => function($query) use($search, $filter, $sort){
                $query->select('users.id', 'users.name');
            }
        ]);

        if($authUser->role_name == config('constants.users.roles.SUPER_ADMIN.type') && $authUser->sub_admin_parent_id > 0){
            $notificationMessage->whereHas('user', function($query) use($filter, $search, $authUser){

                $query->whereHas('user_address', function($query) use($filter, $search, $authUser){
                    $query->where('user_address.is_default', 1);
                    $query->where('user_address.country_id', $authUser->sub_admin_country_id);
                });
            });
        }

        // Filter Conditions
        if(isset($filter['filter_date_range']) && !empty($filter['filter_date_range']) ) {
            $dateRange = explode('to', $filter['filter_date_range']);
            $notificationMessage->whereDate('notifications.created_at', '>=', Carbon::parse(trim($dateRange[0])));
            $notificationMessage->whereDate('notifications.created_at', '<=', Carbon::parse(trim($dateRange[1] ?? $dateRange[0])));
        }
        //----------
        
        // Sort Columns Conditions
        if(!(empty($sort)) || !(empty($search)) || !(empty($filter)))
        {
            $arr_fields = array("notifications.title", "notifications.message", "notifications.send_to", "notifications.created_at");

            $notificationMessage->leftJoin('users', function($join){
                $join->on('users.id', '=', 'notifications.user_id');
            });

            // Conditions
            if(!(empty($search))){
                $search = strtolower($search);

                $notificationMessage->whereRaw('(lower(users.name) LIKE \'%'.$search.'%\' OR lower(notifications.title) LIKE \'%'.$search.'%\' OR (lower(notifications.message) LIKE \'%'.$search.'%\' ) OR (lower(notifications.message) LIKE \'%'.$search.'%\' ) )');
            }
            
            if(isset($sort['column'])){
                for($field = 0; $field < count($arr_fields); $field++)
                {
                    if($sort['column'] == $field && $arr_fields[$field] != "")
                    {
                        $notificationMessage->orderBy($arr_fields[$field], $sort['dir']);
                    }
                }
            }
            else{
                $notificationMessage->orderBy('notifications.id', 'DESC');
            }
        }
        else
        {
            $notificationMessage->orderBy('notifications.id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $notificationMessage = $notificationMessage->skip($offset)->take($limit);
            return $notificationMessage->get();
        }
        else
        {
            return $notificationMessage->get()->count();
        }
    }
}
