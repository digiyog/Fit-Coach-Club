<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;

class Language extends Model
{
    use Orderable, Statusable, StatusToggleable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'code', 'is_default', 'status', 'created_at', 'updated_at'
    ];

    /**
     * Get Languages list records
     */
    public function scopeGetLanguages($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $languages = Language::select('id', 'name', 'code', 'is_default', 'status', 'created_at');

        $languages = $languages->where(function ($query) use ($filter) {
            if (!empty($filter) && !empty($filter['name'])) {
                $query->whereRaw('lower(name) LIKE ? ', [trim(strtolower($filter['name'])) . '%']);
            }
            if (!empty($filter) && !empty($filter['code'])) {
                $query->where('code', "like", "%{$filter['code']}%");
            }
        });
        
        // Table List Search conditions by columns
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $languages = $languages->whereRaw('(lower(languages.name) LIKE \'%'.$search.'%\' OR code like \'%'.$search.'%\')');
        }

        // Columns Sort Conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("name", "code", "is_default", "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                $sfield = $field+1;
                if($sort['column'] == $sfield && $arr_fields[$field] != "")
                {
                    $languages = $languages->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $languages = $languages->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $languages = $languages->skip($offset)->take($limit);
            return $languages->get();
        }
        else
        {
            return $languages->get()->count();
        }
    }
}