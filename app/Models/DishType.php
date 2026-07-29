<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DishType extends Model
{
    use Orderable, Statusable, StatusToggleable;

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
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'  => true,
            ],
        ];
    }

    /**
     * to get languages details
     */
    public function languages()
    {
        return $this->hasOne('App\Models\Language');
    }

    // Get Dish Types list records
    public function scopeGetDishTypes($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $dishTypes = DishType::select('dish_types.id', 'dish_types.name', 'dish_types.order', 'dish_types.status', 'dish_types.created_at')->where('created_by', $authUser['id']);

        // Table list Search conditions
        if (!(empty($search))) {
            $search = strtolower($search);
            $dishTypes = $dishTypes->whereRaw('(lower(dish_types.name) LIKE \'%' . $search . '%\' )');
        }

        // Table columns sort conditions
        if (!(empty($sort)) && $sort['column'] > 0) {
            $arr_fields = array("", "name", 'order', "status", "");
            for ($field = 0; $field < count($arr_fields); $field++) {
                if ($sort['column'] == $field && $arr_fields[$field] != "") {
                    $dishTypes = $dishTypes->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $dishTypes = $dishTypes->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if (!empty($limit)) {
            $dishTypes = $dishTypes->skip($offset)->take($limit);
            return $dishTypes->get();
        } else {
            return $dishTypes->get()->count();
        }
    }
}
