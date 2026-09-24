<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Tip;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class TipController extends Controller
{
    use UploadImage;

    /**
     * @var array
    */
    public $viewData = [];

    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * View Tips list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Tips' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('nutritionPanel.tips.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.tips.index')->with($this->viewData);
    }

    /**
     * Get Tips list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getTips(Request $request)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $limit  = $request->get('length');
        $sort   = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        // Filter Parameters
        $filter = array(
        );

        // Getting Tips Records
        $records_count  = Tip::getTips(null, null, $search, $filter, $sort);
        $records        = Tip::getTips($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name               = 'N/A';
                $coach_name         = 'N/A';
                $link               = 'N/A';
                $order              = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->coach_name)){
                    $coach_name = $value->coach_name;
                }

                if(!empty($value->link)){
                    $link = '<a herf="#" data-url="' . route('nutritionPanel.tips.viewVideo', ['id' => ev($value->id)]) . '" class="view-video cursor-pointer" title="View Video"><div class="badge badge-primary"><i class="fa fa-eye"></i> View Video</div></a>';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="tip_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('nutritionPanel.tips.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "coach_name"        => $coach_name,
                    "link"              => $link,
                    "order"             => $order,
                    "status"            => $status,
                    "action"            => $action,
                );
            }
        }

        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data
        );

        return json_encode($response);
    }

    /**
        * View create Tips.
        *
        * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
        *
        * @author Sandeep
        * @created 20 Jan 2023
    */
    public function create()
    {
        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Tips' => route('nutritionPanel.tips.index'),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;

        return view('nutrition-panel.tips.create')->with($this->viewData);
    }

    /**
     * Store Tips.
     *
     * @return mixed
     *
     * @author Sandeep
     * @created 24 Jan 2023
     */
    public function store(Request $request)
    {
        // Get user
        $authUser = auth()->user();
        //----------
 
        $tip       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Tip
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'coach_name'            => $request['coach_name'],
                'link'                  => $request['link'],
                'order'                 => $request['order'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Tip image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.tips.image_path'), null, 'tips-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $tip = Tip::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $tip       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('Tip create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($tip)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Tip']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.tips.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Tip']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.tips.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Tips.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $tip = Tip::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Tips' => route('nutritionPanel.tips.index'),
            __('language.edit') => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['tip'] = $tip;
        
        return view('nutrition-panel.tips.edit')->with($this->viewData);
    }

    /**
     * Update Tip.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function update(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------
        
        $tipUpdate  = false;
        $errorMessage       = null;
        
        // Update Tip
        DB::beginTransaction();

        try {

            // Update Tip
            $tip = Tip::where('id', dv($id))->first();

            $data = [
                'name'                  => $request['name'],
                'coach_name'            => $request['coach_name'],
                'link'                  => $request['link'],
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Tip image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($tip->image)) {
                    delete_image(config('constants.tips.image_path'), $tip->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.tips.image_path'), null, 'tips-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $tipUpdate = Tip::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $tipUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('Tip update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($tipUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Tip']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.tips.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Tip']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.tips.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Change status.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created 24 Jan 2023
    */
    public function changeStatus(Request $request)
    {
        $language = Tip::toggleStatus($request['ids']);
        
        // Set response
        if (!is_null($language))
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.status_changed'),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.status_change_failed'),
                '_type' => 'error',
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    /**
     * Destroy.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created_at 19 Jan 2023
     */
    public function destroy(Request $request)
    {
        $ids = $request['ids'];
        $tip = Tip::whereIn('id', $ids)->delete();
        
        // Set response
        if ($tip == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Tip']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Tip']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

    /**
     * Update Order.
     *
     * @return boolean
     *
     * @author Divyansh
     * @created 13 Feb 2023
     */
    public function updateOrder(Request $request)
    {
        foreach ($request['ids'] as $key => $value) {

            // Set data
            $data = [
                'order' => $value[1],
            ];
            //---------

            Tip::find($value[0])->update($data);
        }

        // Set response
        $response = [
            '_status' => true,
            '_message' => 'Order changed successfully.',
            '_type' => 'success',
        ];
        //-------------

        return response()->json($response, 200);
    }

    /**
     * View Video.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 23 Dec 2021
     */
    public function viewVideo($id)
    {
        $auth_user = auth()->user();

        // Get Video
        $video = Tip::where('id', dv($id))->first();

        // Send view data
        $this->viewData['video'] = $video;

        return view('nutrition-panel.tips.view-video')->with($this->viewData);
    }

}
