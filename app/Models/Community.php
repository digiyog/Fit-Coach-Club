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
        ->with(['user' => function ($query) use ($request,$filter) {
            $query->select('id','name','email','mobile_number')->active();
        }])
        ->whereHas('user', function ($query) use ($filter) {
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->where("id", $filter['name']);
            }
        })
        ->withCount(['community_images' => function ($query) use ($request) {
        }]);
         
         // Table list Search conditions
         if(!(empty($search)))
         {
             $search = strtolower($search);
             $communities = $communities->whereRaw('(lower(communities.message) LIKE \'%'.$search.'%\' )');
         }
         
         // Table columns sort conditions
         if(!(empty($sort)) && $sort['column'] > 0)
         {
             $arr_fields = array("","", "message", "created_at");
             for($field = 0; $field < count($arr_fields); $field++)
             {
                 if($sort['column'] == $field && $arr_fields[$field] != "")
                 {
                     $communities = $communities->orderBy($arr_fields[$field], $sort['dir']);
                 }
             }
         }
         else
         {
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
