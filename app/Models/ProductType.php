<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
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

    // Get Product Types list records
    public function scopeGetProductTypes($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $productTypes = ProductType::select('product_types.id', 'product_types.name', 'product_types.status', 'product_types.created_at');

        // Table list Search conditions
        if (!(empty($search))) {
            $search = strtolower($search);
            $productTypes = $productTypes->whereRaw('(lower(product_types.name) LIKE \'%' . $search . '%\' )');
        }

        // Table columns sort conditions
        if (!(empty($sort)) && $sort['column'] > 0) {
            $arr_fields = array("", "name", "status", "");
            for ($field = 0; $field < count($arr_fields); $field++) {
                if ($sort['column'] == $field && $arr_fields[$field] != "") {
                    $productTypes = $productTypes->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $productTypes = $productTypes->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if (!empty($limit)) {
            $productTypes = $productTypes->skip($offset)->take($limit);
            return $productTypes->get();
        } else {
            return $productTypes->get()->count();
        }
    }
}
