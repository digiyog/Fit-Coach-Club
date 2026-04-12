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

        $transactions = Transaction::select('transactions.id', 'users.name', 'user_id', 'order_id', 'title', 'total_amount' ,'transactions.due_amount', 'received_amount', 'payment_type','remark', 'transactions.created_at', 'type')
        ->where("transactions.created_by", $authUser->id);

        $transactions->Join("users", function ($join) {
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
                $query->whereRaw('(lower(users.name) LIKE \'%'.trim(strtolower($filter['name'])).'%\')');
            }

            if (!empty($filter) && !empty($filter['date_range'])) {
                $date_range = explode('/', $filter['date_range']);
                $last_30_days = [
                    'start_date' => trim($date_range[0]) . ' 00:00:00',
                    'end_date' => trim($date_range[1]) . ' 00:00:00',
                ];
                $query->whereDate('transactions.created_at', '>=', $last_30_days['start_date']);
                $query->whereDate('transactions.created_at', '<=', $last_30_days['end_date']);
            } else {
            }
        });
        
        // Table list Search conditions
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $transactions = $transactions->whereRaw('((lower(users.name) LIKE \'%'.$search.'%\') )');
        }
        
        // Table columns sort conditions
        if(!(empty($sort)) && $sort['column'] > 0)
        {
            $arr_fields = array("name", "", "title", "total_amount", "due_amount", "received_amount", "payment_type");

            for($field = 0; $field < count($arr_fields); $field++)
            {
                if($sort['column'] == $field && $arr_fields[$field] != "")
                {
                    $transactions = $transactions->orderBy($arr_fields[$field], $sort['dir']);
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

    public function order_info()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }
}

