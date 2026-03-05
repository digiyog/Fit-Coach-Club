<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Team;
use App\Models\SportType;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class TeamController extends Controller
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
     * View Teams list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function index($id)
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('auctionPanel.dashboard'),
            'Teams' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('auctionPanel.teams.create', ['id' => $id ]),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['id'] = $id;
        
        return view('auction-panel.teams.index')->with($this->viewData);
    }

    /**
     * Get Teams list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getTeams(Request $request)
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
           'auction_id' => dv($request['auction_id'])
        );

        // Getting Teams Records
        $records_count  = Team::getTeams(null, null, $search, $filter, $sort);
        $records        = Team::getTeams($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name           = 'N/A';
                $short_name     = 'N/A';
                $short_key      = 'N/A';
                $order          = 'N/A';
                $status         = '';
                $action         = '';

                // Preparing Data
                if(!empty($value->name)){
                    $name = $value->name;
                }

                if(!empty($value->short_name)){
                    $short_name = $value->short_name;
                }

                if(!empty($value->short_key)){
                    $short_key = $value->short_key;
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="teams_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('auctionPanel.teams.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "name"              => $name,
                    "short_name"        => $short_name,
                    "short_key"         => $short_key,
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
        * View create Teams.
        *
        * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
        *
        * @author Sandeep
        * @created 20 Jan 2023
    */
    public function create($id)
    {
        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('auctionPanel.dashboard'),
            'Teams' => route('auctionPanel.teams.index', ['id' => $id ] ),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['id'] = $id;

        return view('auction-panel.teams.create')->with($this->viewData);
    }

    /**
     * Store Teams.
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
 
        $team           = null;
        $errorMessage   = null;
        
        // Begin Transaction
        DB::beginTransaction();
        
        // Create Team
        try {

            // Set data
            $data = [
                'auction_id'        => dv($request['auction_id']),
                'name'              => $request['name'],
                'short_name'        => $request['short_name'],
                'short_key'         => $request['short_key'],
                'order'             => $request['order'],
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString()
            ];

            // Upload Team image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.teams.image_path'), null, 'teams-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $team = Team::create($data);
            DB::commit();

        } catch (\Exception $e) {
            $team        = null;
            $errorMessage   = $e->getMessage();
            DB::rollback();
        }
        //------------
        if (!is_null($team)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Team']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('auctionPanel.teams.index', ['id' => $request['auction_id'] ])->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Team']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('auctionPanel.teams.create', ['id' => $request['auction_id'] ])->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Teams.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $team = Team::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('auctionPanel.dashboard'),
            'Teams' => route('auctionPanel.teams.index', ['id' => ev($team['auction_id']) ]),
            __('language.edit') => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['teams']      = $team;
        $this->viewData['sportTypes'] = $sportTypes;
        
        return view('auction-panel.teams.edit')->with($this->viewData);
    }

    /**
     * Update Team.
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
        
        $teamUpdate     = false;
        $errorMessage   = null;
        
        // Update Team
        DB::beginTransaction();

        try {

            // Update Team
            $team = Team::where('id', dv($id))->first();

            $data = [
                'name'              => $request['name'],
                'short_name'        => $request['short_name'],
                'short_key'         => $request['short_key'],
                'order'             => $request['order'],
                'updated_at'        => Carbon::now()->toDateTimeString()
            ];

            // Upload Team image
            if ($request->hasFile('image'))
            {   
                // Remove old image
                if (!is_null($team->image)) {
                    delete_image(config('constants.teams.image_path'), $team->image);
                }
                //-----------------
                $image = $this->uploadImage($request->file('image'), config('constants.teams.image_path'), null, 'teams-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $teamUpdate = Team::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $teamUpdate = null;
            $errorMessage = $e->getMessage();
            DB::rollback();
        }
        //------------

        if (!is_null($teamUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Team']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('auctionPanel.teams.index', ['id' => ev($team['auction_id']) ])->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Team']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('auctionPanel.teams.edit', ['id' => $id ])->withInput()->with(['notification' => $notification]);
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
        $language = Team::toggleStatus($request['ids']);
        
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
        $team = Team::whereIn('id', $ids)->delete();
        
        // Set response
        if ($team == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Team']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Team']),
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

            Team::find($value[0])->update($data);
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

}
