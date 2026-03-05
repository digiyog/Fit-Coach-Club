<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Traits\UploadImage;

use App\Models\Transaction;
use Intervention\Image\Facades\Image;

class PaymentController extends Controller
{
    use UploadImage;

    /**
     * Create an controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * View Transaction.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function index(Request $request)
    {
        $userInfo = Auth::user();

        $transactions = Transaction::select('id', 'user_id', 'order_id', 'title', 'total_amount', 'payment_type', 'created_at')
        ->where('user_id', $userInfo->id)
        ->when($request->month, function ($q) use ($request) {
            $q->whereMonth('created_at', $request->month);
        })
        ->when($request->year, function ($q) use ($request) {
            $q->whereYear('created_at', $request->year);
        })
        ->orderBy('id','DESC')->paginate(10);

        // Set response
        if(!empty($transactions[0])) {
            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'transaction']),
                '_data'    => $transactions
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'transaction']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
