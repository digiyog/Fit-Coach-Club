<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cms extends Model
{
    use Orderable, Statusable, StatusToggleable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'image',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];


    /**
     * Get CMS list records
     */
    public function scopeGetCms($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $cms = Cms::select('cms.id', 'cms.title', 'cms.image', 'cms.description', 'meta_title' , 'meta_keyword' ,'meta_description' , 'cms.status', 'cms.created_at');
        
        $cms = $cms->where(function ($query) use ($filter) {
            $query->where('cms.status', 1);
            if (!empty($filter) && !empty($filter['filter'])) {
                $query->where('cms.title', "like", "%{$filter['filter']}%");
            }
        });

        // Table List Search conditions by columns
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $cms = $cms->whereRaw('(lower(cms.title) LIKE \'%'.$search.'%\') OR (lower(cms.title) LIKE \'%'.$search.'%\')')->where('deleted_at','');
        }

        // Columns Sort Conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "cms.title", "cms.description", "cms.meta_title", "cms.meta_keyword", "meta_description",  "cms.status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                // $sfield = $field+1;
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $cms = $cms->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $cms = $cms->orderBy('id', 'ASC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $cms = $cms->skip($offset)->take($limit);
            return $cms->get();
        }
        else
        {
            return $cms->get()->count();
        }
    }
}