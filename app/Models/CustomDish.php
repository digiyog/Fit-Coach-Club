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

class CustomDish extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable, Sluggable, HasSlug;
    
    protected $table = 'custom_dishes';

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

    public function dish_type()
    {
        return $this->hasOne('App\Models\DishType', 'id', 'dish_type_id');
    }

    // Get Custom dishes list records
    public function scopeGetCustomDishes($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $customDishes = CustomDish::select('custom_dishes.id', 'custom_dishes.dish_type_id', 'custom_dishes.name', 'custom_dishes.description', 'custom_dishes.order', 'custom_dishes.status', 'custom_dishes.created_at')->where('created_by', $authUser['id']);
        
        $customDishes = $customDishes->with('dish_type', function($qry) use($search, $filter, $sort){
            $qry->select('id', 'name');
        });

        // Table list Search conditions
        if(!(empty($search)))
        {
            $customDishes->leftJoin('dish_types', function($join){
                $join->on('dish_types.id', '=', 'custom_dishes.dish_type_id');
            });

            $search = strtolower($search);
            $customDishes = $customDishes->whereRaw('(lower(custom_dishes.name) LIKE \'%'.$search.'%\' OR lower(dish_types.name) like \'%'.$search.'%\'  )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "", "name", "description", 'order', "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $customDishes = $customDishes->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $customDishes = $customDishes->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $customDishes = $customDishes->skip($offset)->take($limit);
            return $customDishes->get();
        }
        else
        {
            return $customDishes->get()->count();
        }
    }
}

