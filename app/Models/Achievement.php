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

class Achievement extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable, Sluggable, HasSlug;
    
    protected $table = 'achievements';

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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'separator'  => '_',
                'on_update'  => true,
            ],
        ];
    }

    // Get Achievement list records
    public function scopeGetAchievements($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $achievements = Achievement::select('id', 'title', 'type', 'in_app_show', 'show_achievement', 'order', 'status', 'created_at')->where('created_by', $authUser['id']);
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $achievements = $achievements->whereRaw('(lower(achievements.title) LIKE \'%'.$search.'%\' OR lower(achievements.in_app_show) like \'%'.$search.'%\' OR lower(achievements.type) like \'%'.$search.'%\' OR lower(achievements.show_achievement) like \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("","title", 'type', "in_app_show" , "show_achievement", 'order', "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $achievements = $achievements->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $achievements = $achievements->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $achievements = $achievements->skip($offset)->take($limit);
            return $achievements->get();
        }
        else
        {
            return $achievements->get()->count();
        }
    }
}

