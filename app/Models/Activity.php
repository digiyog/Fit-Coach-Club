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

class Activity extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable, Sluggable, HasSlug;
    
    protected $table = 'activities';

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

    // Get Activity list records
    public function scopeGetActivities($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $activities = Activity::select('id', 'name', 'activity_type', 'date', 'order', 'status', 'created_at')->where('created_by', $authUser['id']);
        
        // Filter conditions
        if (isset($filter['activity_type']) && $filter['activity_type'] !== '' && $filter['activity_type'] !== null) {
            $activities = $activities->where('activity_type', $filter['activity_type']);
        }

        if (isset($filter['status']) && $filter['status'] !== '' && $filter['status'] !== null) {
            $activities = $activities->where('status', $filter['status']);
        }

        if (!empty($filter['date'])) {
            $now = \Carbon\Carbon::now();
            if ($filter['date'] === 'today') {
                $activities = $activities->whereDate('date', \Carbon\Carbon::today());
            } elseif ($filter['date'] === 'week') {
                $activities = $activities->whereBetween('date', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
            } elseif ($filter['date'] === 'month') {
                $activities = $activities->whereMonth('date', $now->month)->whereYear('date', $now->year);
            } elseif ($filter['date'] === 'upcoming') {
                $activities = $activities->whereDate('date', '>=', \Carbon\Carbon::today());
            } elseif ($filter['date'] === 'past') {
                $activities = $activities->whereDate('date', '<', \Carbon\Carbon::today());
            }
        }

        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $activities = $activities->whereRaw('(lower(activities.name) LIKE \'%'.$search.'%\' OR lower(activities.activity_type) like \'%'.$search.'%\' OR lower(activities.date) like \'%'.$search.'%\' )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("", "name", "activity_type", "date", "status", "order", "status", "");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $activities = $activities->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $activities = $activities->orderBy('order', 'ASC')->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $activities = $activities->skip($offset)->take($limit);
            return $activities->get();
        }
        else
        {
            return $activities->get()->count();
        }
    }
}

