<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    protected $table = 'orders';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }

    /**
     * Get Order List
     */
    public function scopeGetOrders($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        $authUser = auth()->user();

        // Get Orders by default language
        $orders = Order::select('id', 'order_id', 'user_id', 'order_number', 'order_date', 'product_quantity', 'user_name', 'mobile_number', 'total_amount', 'discount','net_amount', 'payment_status', 'order_status', 'created_at')
            ->where('franchise_id', $authUser->id)
            ->where(function ($query) use ($filter, $search, $userId, $userType, $sort) {

                if(isset($sort['filter_date_range']) && !empty($sort['filter_date_range']) ) {
                    $dateRange = explode('to', $sort['filter_date_range']);
                    $query->whereDate('created_at', '>=', Carbon::parse(trim($dateRange[0])));
                    $query->whereDate('created_at', '<=', Carbon::parse(trim($dateRange[1])));
                }

                if (isset($sort['status_filter']) && !(empty($sort['status_filter']))) {
                    $query->where('order_status', $sort['status_filter']);
                }

                if (isset($sort['user_id']) && !(empty($sort['user_id']))) {
                    $query->where('user_id', $sort['user_id']);
                }

                if (isset($sort['payment_status_filter']) && !(empty($sort['payment_status_filter']))) {
                    $query->where('payment_status', $sort['payment_status_filter']);
                }
            })
            ->with([
                'orderDetails' => function ($query) use ($defaultLanguage, $search, $filter, $sort) {
                }
            ]);
           

        // Sort Columns Conditions
        if (!(empty($sort)) || !(empty($search)) || !(empty($filter))) {
            $arr_fields = array("", "user_name", "mobile_number", "", "", "order", "status", "");

            if ($sort['column'] > 0) {

            } else {
                $orders->orderBy('id', 'DESC');
            }
        } else {
            $orders->orderBy('id', 'DESC');
        }

        $orders->groupBy('id');

        // Set final limit and records
        if (!empty($limit)) {
            $orders = $orders->skip($offset)->take($limit);
            return $orders->get();
        } else {
            return $orders->get()->count();
        }
    }
}
