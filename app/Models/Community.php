<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;

class Community extends Model
{
    use Statusable, StatusToggleable;
    
    protected $table = "communities";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];


    public function community_images()
    {
        return $this->hasMany('App\Models\CommunityImage');
    }

    /**
     * to get Community user
    */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id' , 'user_id');
    }

    // Get Community Photos Records
    public function scopeGetCommunityPhotos($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $communities = Community::select('id','user_id', 'message', 'created_at')
        ->with(['user' => function ($query) {
            $query->select('id','name','email','mobile_number','profile_image')->active();
        }])
        ->with(['community_images'])
        ->whereHas('user', function ($query) use ($filter) {
            if (!empty($filter) && !empty($filter['name']) && $filter['name'] !== 'all') 
            {
                $query->where("id", $filter['name']);
            }
        })
        ->withCount(['community_images']);

        // Date filter
        if (!empty($filter['date']) && $filter['date'] !== 'all') {
            if ($filter['date'] === 'today') {
                $communities = $communities->whereDate('communities.created_at', \Carbon\Carbon::today());
            } elseif ($filter['date'] === 'this_week') {
                $communities = $communities->whereBetween('communities.created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($filter['date'] === 'this_month') {
                $communities = $communities->whereMonth('communities.created_at', \Carbon\Carbon::now()->month)
                                           ->whereYear('communities.created_at', \Carbon\Carbon::now()->year);
            }
        }
         
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $communities = $communities->where(function($q) use ($search) {
                $q->whereRaw('lower(communities.message) LIKE ?', ['%'.$search.'%'])
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->whereRaw('lower(users.name) LIKE ?', ['%'.$search.'%'])
                         ->orWhereRaw('lower(users.mobile_number) LIKE ?', ['%'.$search.'%']);
                  });
            });
        }
         
        // Sort conditions
        if (!empty($filter['sort_order'])) {
            if ($filter['sort_order'] === 'oldest') {
                $communities = $communities->orderBy('communities.id', 'ASC');
            } else {
                $communities = $communities->orderBy('communities.id', 'DESC');
            }
        } elseif (!(empty($sort)) && $sort['column'] > 0) {
            $arr_fields = array("","id", "message", "created_at");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $communities = $communities->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $communities = $communities->orderBy('id', 'DESC');
        }
 
        // Set final limit and records
        if(!empty($limit))
        {
            $communities = $communities->skip($offset)->take($limit);
            return $communities->get();
        }
        else
        {
            return $communities->get()->count();
        }
    }
}
