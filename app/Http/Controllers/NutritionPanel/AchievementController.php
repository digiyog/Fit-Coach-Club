<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Achievement;
use App\Models\Notification;
use App\Models\User;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class AchievementController extends Controller
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
     * View Achievements list.
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
            'Achievements' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button
      
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('nutritionPanel.achievements.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['id'] = $id;
        
        return view('nutrition-panel.achievements.index')->with($this->viewData);
    }

    /**
     * Get Achievements list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getAchievements(Request $request)
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

        // Getting Achievements Records
        $records_count  = Achievement::getAchievements(null, null, $search, $filter, $sort);
        $records        = Achievement::getAchievements($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $title              = 'N/A';
                $in_app_show        = 'No';
                $show_achievement   = 'N/A';
                $order              = 'N/A';
                $type               = 'N/A';
                $status             = '';
                $action             = '';

                // Preparing Data
                if(!empty($value->title)){
                    $title = $value->title;
                }

                if(!empty($value->type)){
                    $type = $value->type;
                }

                if($value->in_app_show == 1){
                    $in_app_show = 'Yes';
                }

                if($value->show_achievement == 1){
                    $show_achievement = 'All User';
                } else if($value->show_achievement == 2){
                    $show_achievement = 'Only Online User';
                } else {
                    $show_achievement = 'Only Offline User';
                }

                if(!empty($value->order) || $value->order == 0) {
                    $order = '<input type="text" class="form-control numeric pr-1" id="achievement_order_'.$value->id.'" name="order" value="'.$value->order.'" autocomplete="off" />';
                }

                if ( $value->status == 0 ){
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } else {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }

                $action = '<a href="' . route('nutritionPanel.achievements.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary"><i class="fa fa-pencil"></i> Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "title"             => $title,
                    "type"              => $type,
                    "in_app_show"       => $in_app_show,
                    "show_achievement"  => $show_achievement,
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
        * View create Achievements.
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
            'Achievements' => route('nutritionPanel.achievements.index'),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;

        return view('nutrition-panel.achievements.create')->with($this->viewData);
    }

    /**
     * Store Achievements.
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
 
        $achievement    = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Achievement
        try {

            // Set data
            $data = [
                'title'                 => $request['title'],
                'type'                  => $request['type'],
                'in_app_show'           => $request['in_app_show'],
                'show_achievement'      => $request['show_achievement'],
                'order'                 => $request['order'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Achievement image
            if ($request->hasFile('image'))
            {
                $image = $this->uploadImage($request->file('image'), config('constants.achievements.image_path'), null, 'achievements-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $achievement = Achievement::create($data);

            //Notification Send
            $users = User::where('role_type', 'user')->where('status', 1)->get();

            foreach ($users as $user) {
                // Send Notification
                $senderData   = User::find(0);
                $receiverData = User::find($user['id']);

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                // Notification content
                if($request['type'] == 'Achievement'){
                    $title = 'A new win in your group';
                    $notiMessage = 'Tap to see who made progress';
                    $message = 'Tap to see who made progress';
                    $notificationType = 8;
                } else {
                    $title = 'A new announcement is live';
                    $notiMessage = 'Your coach shared an update. Check it now.';
                    $message = 'Your coach shared an update. Check it now.';
                    $notificationType = 9;
                }

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData->id,
                    'data_id'             => '',
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderData['name'],
                    'receiver_name'       => $receiverData['name'],
                    'notification_type'   => $notificationType,
                    'sent_status'         => 0
                ]);
                //---------
            }

            DB::commit();

        } catch (\Exception $e) {
            $achievement       = null;
            $errorMessage   = $e->getMessage();
            DB::rollback();
        }
        //------------

        if (!is_null($achievement)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Achievement']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.achievements.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Achievement']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.achievements.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Achievements.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $achievement = Achievement::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Achievements' => route('nutritionPanel.achievements.index'),
            __('language.edit') => '',
        ];
        
        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['achievement'] = $achievement;
        
        return view('nutrition-panel.achievements.edit')->with($this->viewData);
    }

    /**
     * Update Achievement.
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
        
        $achievementUpdate  = false;
        $errorMessage       = null;
        
        // Update Achievement
        DB::beginTransaction();

        try {

            // Update Achievement
            $achievement = Achievement::where('id', dv($id))->first();

            $data = [
                'title'                 => $request['title'],
                'type'                  => $request['type'],
                'in_app_show'           => $request['in_app_show'],
                'show_achievement'      => $request['show_achievement'],
                'order'                 => $request['order'],
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];

            // Upload Achievement image
            if ($request->hasFile('image')){
                // Remove old image
                if (!is_null($achievement->image)) {
                    delete_image(config('constants.achievements.image_path'), $achievement->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.achievements.image_path'), null, 'achievements-');
                if ($image['_status']) 
                {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------
            
            $achievementUpdate = Achievement::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $achievementUpdate = null;
            $errorMessage = $e->getMessage();
            DB::rollback();
        }
        //------------

        if (!is_null($achievementUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Achievement']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.achievements.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Achievement']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('nutritionPanel.achievements.edit', ['id' => ev($id)])->withInput()->with(['notification' => $notification]);
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
        $language = Achievement::toggleStatus($request['ids']);
        
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
        $achievement = Achievement::whereIn('id', $ids)->delete();
        
        // Set response
        if ($achievement == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Achievement']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Achievement']),
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

            Achievement::find($value[0])->update($data);
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
