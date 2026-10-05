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

class Transaction extends Model
{
    use HasFactory, SoftDeletes , Orderable, Statusable, StatusToggleable;
    
    protected $table = 'transactions';

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

    // Get Transactions list records
    public function scopeGetTransactions($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get Transaction
        $authUser = auth()->user();
        //----------

        $transactions = Transaction::select('transactions.id', 'users.name', 'users.email', 'users.profile_image', 'user_id', 'order_id', 'title', 'total_amount', 'transactions.due_amount', 'received_amount', 'payment_type', 'remark', 'transactions.created_at', 'type')
        ->where("transactions.created_by", $authUser->id);

        $transactions->leftJoin("users", function ($join) {
            $join->on("transactions.user_id", "=", "users.id");
        });

        $transactions = $transactions->with('order_info', function($qry) use($search, $filter, $sort){
            $qry->select('id', 'order_number');
        });
         
        // Record filter conditions
        $transactions->where(function ($query) use ($filter) {
            // Filter
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower(addslashes($filter['name']))).'%\')');
            }

            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                if (count($date_range) >= 2) {
                    $last_30_days = [
                        'start_date' => trim($date_range[0]) . ' 00:00:00',
                        'end_date' => trim($date_range[1]) . ' 23:59:59',
                    ];
                    $query->whereDate('transactions.created_at', '>=', $last_30_days['start_date']);
                    $query->whereDate('transactions.created_at', '<=', $last_30_days['end_date']);
                }
            }

            if (!empty($filter) && !empty($filter['payment_type'])) {
                if ($filter['payment_type'] == 'product') {
                    $query->where(function ($q) {
                        $q->where('transactions.title', 'Order Placed')
                          ->orWhere('transactions.type', 1);
                    });
                } elseif ($filter['payment_type'] == 'subscription') {
                    $query->where('transactions.title', '!=', 'Order Placed')
                          ->where(function ($q) {
                              $q->whereNull('transactions.type')
                                ->orWhere('transactions.type', '!=', 1);
                          });
                }
            }

            if (!empty($filter) && !empty($filter['collection_state'])) {
                if ($filter['collection_state'] == 'paid') {
                    $query->where(function ($q) {
                        $q->whereNull('transactions.due_amount')
                          ->orWhere('transactions.due_amount', '<=', 0);
                    });
                } elseif ($filter['collection_state'] == 'due') {
                    $query->where('transactions.due_amount', '>', 0);
                }
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower(addslashes($search));
            $transactions = $transactions->where(function($q) use ($search) {
                $q->whereRaw('(lower(users.name) LIKE \'%'.$search.'%\')')
                  ->orWhereRaw('(lower(transactions.title) LIKE \'%'.$search.'%\')');
            });
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && isset($sort['column']) && $sort['column'] >= 0)
        {
            $arr_fields = array("users.name", "", "transactions.title", "transactions.total_amount", "transactions.due_amount", "transactions.received_amount", "transactions.payment_type", "", "transactions.created_at", "");

            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $sortDir = (isset($sort['dir']) && in_array(strtolower($sort['dir']), ['asc', 'desc'])) ? $sort['dir'] : 'desc';
                    $transactions = $transactions->orderBy($arr_fields[$field], $sortDir);
                }
            }
        }
        else
        {
            $transactions = $transactions->orderBy('transactions.id', 'DESC');
        }

        // Set final limit and records
        if(!empty($limit))
        {
            $transactions = $transactions->skip($offset)->take($limit);
            return $transactions->get();
        }
        else
        {
            return $transactions->get()->count();
        }
    }

    // Get Transactions summary metrics (total, received, due, collection rate)
    public function scopeGetTransactionsSummary($model, $search = null, $filter = array())
    {
        $authUser = auth()->user();
        $query = Transaction::where("transactions.created_by", $authUser->id)
            ->leftJoin("users", "transactions.user_id", "=", "users.id");

        // Record filter conditions
        $query->where(function ($q) use ($filter) {
            if (!empty($filter) && !empty($filter['name'])) {
                $q->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower(addslashes($filter['name']))).'%\')');
            }

            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                if (count($date_range) >= 2) {
                    $last_30_days = [
                        'start_date' => trim($date_range[0]) . ' 00:00:00',
                        'end_date' => trim($date_range[1]) . ' 23:59:59',
                    ];
                    $q->whereDate('transactions.created_at', '>=', $last_30_days['start_date']);
                    $q->whereDate('transactions.created_at', '<=', $last_30_days['end_date']);
                }
            }

            if (!empty($filter) && !empty($filter['payment_type'])) {
                if ($filter['payment_type'] == 'product') {
                    $q->where(function ($sub) {
                        $sub->where('transactions.title', 'Order Placed')
                            ->orWhere('transactions.type', 1);
                    });
                } elseif ($filter['payment_type'] == 'subscription') {
                    $q->where('transactions.title', '!=', 'Order Placed')
                        ->where(function ($sub) {
                            $sub->whereNull('transactions.type')
                                ->orWhere('transactions.type', '!=', 1);
                        });
                }
            }

            if (!empty($filter) && !empty($filter['collection_state'])) {
                if ($filter['collection_state'] == 'paid') {
                    $q->where(function ($sub) {
                        $sub->whereNull('transactions.due_amount')
                            ->orWhere('transactions.due_amount', '<=', 0);
                    });
                } elseif ($filter['collection_state'] == 'due') {
                    $q->where('transactions.due_amount', '>', 0);
                }
            }
        });

        if (!empty($search)) {
            $search = strtolower(addslashes($search));
            $query->where(function($q) use ($search) {
                $q->whereRaw('(lower(users.name) LIKE \'%'.$search.'%\')')
                  ->orWhereRaw('(lower(transactions.title) LIKE \'%'.$search.'%\')');
            });
        }

        $totalAmount = (float) (clone $query)->sum('transactions.total_amount');
        $receivedAmount = (float) (clone $query)->sum('transactions.received_amount');
        $dueAmount = (float) (clone $query)->sum('transactions.due_amount');
        $collectionRate = $totalAmount > 0 ? round(($receivedAmount / $totalAmount) * 100, 1) : 0;

        return [
            'total_amount' => $totalAmount,
            'received_amount' => $receivedAmount,
            'due_amount' => $dueAmount,
            'collection_rate' => $collectionRate,
            'total_amount_formatted' => number_format($totalAmount, 0),
            'received_amount_formatted' => number_format($receivedAmount, 0),
            'due_amount_formatted' => number_format($dueAmount, 0),
            'collection_rate_formatted' => $collectionRate . '%'
        ];
    }

    public function order_info()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}

