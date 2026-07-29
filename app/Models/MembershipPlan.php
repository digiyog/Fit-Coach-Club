<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPlan extends Model
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

    // Get Membership Plans list records
    public function scopeGetMembershipPlans($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $membershipPlans = MembershipPlan::select('membership_plans.id', 'membership_plans.name', 'membership_plans.price', 'membership_plans.days', 'membership_plans.order', 'membership_plans.status', 'membership_plans.created_at');

        // Table list Search conditions
        if (!(empty($search))) {
            $search = strtolower($search);
            $membershipPlans = $membershipPlans->whereRaw('(lower(membership_plans.name) LIKE \'%' . $search . '%\' || lower(membership_plans.price) LIKE \'%' . $search . '%\' || lower(membership_plans.days) LIKE \'%' . $search . '%\' )');
        }

        // Table columns sort conditions
        if (!(empty($sort)) && $sort['column'] > 0) {
            $arr_fields = array("", "name", "price", "days", 'order', "status", "");
            for ($field = 0; $field < count($arr_fields); $field++) {
                if ($sort['column'] == $field && $arr_fields[$field] != "") {
                    $membershipPlans = $membershipPlans->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        } else {
            $membershipPlans = $membershipPlans->orderBy('id', 'DESC');
        }

        // Set final limit and records
        if (!empty($limit)) {
            $membershipPlans = $membershipPlans->skip($offset)->take($limit);
            return $membershipPlans->get();
        } else {
            return $membershipPlans->get()->count();
        }
    }
}
