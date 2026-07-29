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

class Product extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable, Sluggable, HasSlug;
    
    protected $table = 'products';

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

    public function product_type()
    {
        return $this->hasOne('App\Models\ProductType', 'id', 'product_type_id');
    }

    // Get Products list records
    public function scopeGetProducts($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $products = Product::select('id', 'product_type_id', 'name', 'price', 'short_description', 'description', 'order', 'status', 'created_at')->with('product_type');

        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $products = $products->whereRaw('(lower(name) LIKE \'%'.$search.'%\' OR lower(price) like \'%'.$search.'%\' OR lower(short_description) like \'%'.$search.'%\'  )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name", 'price', "short_description", 'order', "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $products = $products->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $products = $products->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $products = $products->skip($offset)->take($limit);
            return $products->get();
        }
        else
        {
            return $products->get()->count();
        }
    }

    public function product_images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function franchise()
    {
        return $this->hasMany('App\Models\FranchiseProduct');
    }
}

