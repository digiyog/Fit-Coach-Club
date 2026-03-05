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

class Tip extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable, Sluggable, HasSlug;
    
    protected $table = 'tips';

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
                'source' => 'name',
                'separator'  => '_',
                'on_update'  => true,
            ],
        ];
    }

    // Get Tip list records
    public function scopeGetTips($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $tips = Tip::select('id', 'name', 'coach_name', 'link', 'order', 'status', 'created_at')->where('created_by', $authUser['id']);
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $tips = $tips->whereRaw('(lower(tips.name) LIKE \'%'.$search.'%\' OR lower(tips.coach_name) like \'%'.$search.'%\' OR lower(tips.link) like \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("","name", "coach_name" , "link", 'order', "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $tips = $tips->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $tips = $tips->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $tips = $tips->skip($offset)->take($limit);
            return $tips->get();
        }
        else
        {
            return $tips->get()->count();
        }
    }
}

