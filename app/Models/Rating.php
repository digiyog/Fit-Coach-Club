<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = "ratings";
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function scopeGetReviews($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array()){
        $ratings = Rating::select('ratings.id', 'users.name', 'ratings.rating', 'ratings.message', 'ratings.created_at');
        
        $ratings->leftJoin('users', function($join){
            $join->on('users.id', '=', 'ratings.user_id');
        });

        // Record filter conditions
        if(!(empty($filter['user_id']))) {
            $ratings = $ratings->where("user_id", $filter['user_id']);
        }

        // Table list Search conditions
        if(!(empty($search))){
            $search = strtolower($search);
            $ratings = $ratings->whereRaw('(lower(users.name) LIKE \'%'.$search.'%\')');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "users.name", "ratings.rating", "ratings.message", "ratings.created_at", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $ratings = $ratings->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $ratings = $ratings->orderBy('ratings.id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $ratings = $ratings->skip($offset)->take($limit);
            return $ratings->get();
        }
        else
        {
            return $ratings->get()->count();
        }
    }
}
