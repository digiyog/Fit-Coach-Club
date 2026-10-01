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

        // Filter Conditions
        $filterQuery = function ($query) use ($filter, $search) {
            if(isset($filter['filter_date_range']) && !empty($filter['filter_date_range']) ) {
                $rangeStr = $filter['filter_date_range'];
                if (strpos($rangeStr, 'to') !== false) {
                    $dateRange = explode('to', $rangeStr);
                } elseif (strpos($rangeStr, '/') !== false) {
                    $dateRange = explode('/', $rangeStr);
                } elseif (strpos($rangeStr, ' - ') !== false) {
                    $dateRange = explode(' - ', $rangeStr);
                } else {
                    $dateRange = [];
                }

                if (count($dateRange) == 2) {
                    $query->whereDate('created_at', '>=', \Carbon\Carbon::parse(trim($dateRange[0])));
                    $query->whereDate('created_at', '<=', \Carbon\Carbon::parse(trim($dateRange[1])));
                }
            }

            if (isset($filter['status_filter']) && !(empty($filter['status_filter']))) {
                $query->where('order_status', $filter['status_filter']);
            }

            if (isset($filter['user_id']) && !(empty($filter['user_id']))) {
                $query->where('user_id', $filter['user_id']);
            }

            if (isset($filter['payment_status_filter']) && !(empty($filter['payment_status_filter']))) {
                $query->where('payment_status', $filter['payment_status_filter']);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                      ->orWhere('user_name', 'like', '%' . $search . '%')
                      ->orWhere('mobile_number', 'like', '%' . $search . '%');
                });
            }
        };

        // Get Orders
        $orders = Order::select('id', 'order_id', 'user_id', 'order_number', 'order_date', 'product_quantity', 'user_name', 'mobile_number', 'total_amount', 'discount','net_amount', 'payment_status', 'order_status', 'created_at')
            ->where('franchise_id', $authUser->id)
            ->where($filterQuery);

        // Sort Columns Conditions
        if (!empty($sort) && isset($sort['column'])) {
            $arr_fields = array("created_at", "user_name", "mobile_number", "total_amount", "discount", "net_amount", "payment_status", "order_status", "");
            $colIdx = intval($sort['column']);
            if (isset($arr_fields[$colIdx]) && !empty($arr_fields[$colIdx])) {
                $dir = $sort['dir'] ?? 'DESC';
                $orders->orderBy($arr_fields[$colIdx], $dir);
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

    /**
     * Get Orders Summary metrics for KPI card
     */
    public function scopeGetOrdersSummary($model, $search = null, $filter = array())
    {
        $authUser = auth()->user();
        $query = Order::where('franchise_id', $authUser->id);

        if(isset($filter['filter_date_range']) && !empty($filter['filter_date_range']) ) {
            $rangeStr = $filter['filter_date_range'];
            if (strpos($rangeStr, 'to') !== false) {
                $dateRange = explode('to', $rangeStr);
            } elseif (strpos($rangeStr, '/') !== false) {
                $dateRange = explode('/', $rangeStr);
            } elseif (strpos($rangeStr, ' - ') !== false) {
                $dateRange = explode(' - ', $rangeStr);
            } else {
                $dateRange = [];
            }

            if (count($dateRange) == 2) {
                $query->whereDate('created_at', '>=', \Carbon\Carbon::parse(trim($dateRange[0])));
                $query->whereDate('created_at', '<=', \Carbon\Carbon::parse(trim($dateRange[1])));
            }
        }

        if (isset($filter['status_filter']) && !(empty($filter['status_filter']))) {
            $query->where('order_status', $filter['status_filter']);
        }

        if (isset($filter['user_id']) && !(empty($filter['user_id']))) {
            $query->where('user_id', $filter['user_id']);
        }

        if (isset($filter['payment_status_filter']) && !(empty($filter['payment_status_filter']))) {
            $query->where('payment_status', $filter['payment_status_filter']);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('user_name', 'like', '%' . $search . '%')
                  ->orWhere('mobile_number', 'like', '%' . $search . '%');
            });
        }

        $totalOrders = (clone $query)->count();
        $netAmount = (float) (clone $query)->sum('net_amount');
        $successfulPayments = (clone $query)->where('payment_status', 2)->count();
        $pendingPayments = (clone $query)->where('payment_status', 1)->count();

        $deliveredOrders = (clone $query)->where('order_status', 6)->count();
        $cancelledOrders = (clone $query)->where('order_status', 7)->count();
        $orderPlaced = (clone $query)->where('order_status', 1)->count();
        $otherOrders = $totalOrders - ($deliveredOrders + $cancelledOrders + $orderPlaced);
        if ($otherOrders > 0) {
            $orderPlaced += $otherOrders;
        }

        $deliveredPercent = $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0;
        $cancelledPercent = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0;
        $orderPlacedPercent = $totalOrders > 0 ? round(($orderPlaced / $totalOrders) * 100, 1) : 0;

        return [
            'total_orders' => $totalOrders,
            'net_amount' => $netAmount,
            'successful_payments' => $successfulPayments,
            'pending_payments' => $pendingPayments,
            'delivered_orders' => $deliveredOrders,
            'cancelled_orders' => $cancelledOrders,
            'order_placed' => $orderPlaced,
            'delivered_percent' => $deliveredPercent,
            'cancelled_percent' => $cancelledPercent,
            'order_placed_percent' => $orderPlacedPercent,
            'total_orders_formatted' => number_format($totalOrders),
            'net_amount_formatted' => number_format($netAmount, 0),
            'successful_payments_formatted' => number_format($successfulPayments),
            'pending_payments_formatted' => number_format($pendingPayments)
        ];
    }
}

