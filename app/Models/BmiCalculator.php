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

class BmiCalculator extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable;
    
    protected $table = 'bmi_calculator';

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

    // Get Bmi Calculator list records
    public function scopeGetBmiCalculator($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $bmiCalculator = BmiCalculator::select('id', 'name', 'mobile_number', 'age', 'weight', 'height', 'gender', 'bmi', 'body_fat', 'visceral_fat', 'muscle_mass', 'metabolic_rate', 'biologic_age', 'body_age', 'created_by')->where('created_by', $authUser['id']);

        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $bmiCalculator = $bmiCalculator->whereRaw('(lower(name) LIKE \'%'.$search.'%\' OR lower(mobile_number) LIKE \'%'.$search.'%\' OR lower(age) LIKE \'%'.$search.'%\' OR lower(weight) LIKE \'%'.$search.'%\' OR lower(height) LIKE \'%'.$search.'%\' OR lower(gender) LIKE \'%'.$search.'%\' OR lower(bmi) LIKE \'%'.$search.'%\' OR lower(body_fat) LIKE \'%'.$search.'%\' OR lower(visceral_fat) LIKE \'%'.$search.'%\' OR lower(muscle_mass) LIKE \'%'.$search.'%\' OR lower(metabolic_rate) LIKE \'%'.$search.'%\' OR lower(biologic_age) LIKE \'%'.$search.'%\' OR lower(body_age) LIKE \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name", "mobile_number", "age", 'weight', "height", "gender", "bmi", "body_fat", "visceral_fat", "muscle_mass", "metabolic_rate", "biologic_age","");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $bmiCalculator = $bmiCalculator->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $bmiCalculator = $bmiCalculator->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $bmiCalculator = $bmiCalculator->skip($offset)->take($limit);
            return $bmiCalculator->get();
        }
        else
        {
            return $bmiCalculator->get()->count();
        }
    }
}

