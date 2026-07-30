<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\Models\Cms;
use App\Http\Requests\AdminPanel\ValidateCms;
use App\Http\Traits\UploadImage;
use Storage;

class CmsController extends Controller
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
     * View cms list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created_at 19 Jan 2023
     */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.cms-pages.index'),
            __('language.cms') => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        // Add Button

        $breadcrumbButton[] = [
            // 'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            // 'btn_link' => route('adminPanel.cms.create'),
            // 'btn_icon' => 'plus',
            // 'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser'] = $authUser;

        return view('admin-panel.cms-pages.index')->with($this->viewData);
    }

    /**
     * Get cms list.
     *
     * @return response
     *
     * @author Divyansh 
     * @created_at 19 Jan 2023
     */
    public function getCms(Request $request)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters from table
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];

        // Filters data
        $filter = array(
            "filter" => $request->filter,
        );

        // Get cms List
        $records_count = Cms::GetCms(null, null, $search, $filter, $sort);
        $records = Cms::GetCms($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if (count($records) > 0) {
            foreach ($records as $key => $value) {
                $pageTitle  = 'N/A';
                $pageType   = 'N/A';
                $action     = '';

                // Preparing Data
                if (!empty($value->title)) {
                    $pageTitle = $value->title;
                }

                $action = '<a href="' . route('adminPanel.cms-pages.edit', ['id' => ev($value->id)]) . '" class="" title="Edit"><div class="badge badge-primary">Edit</div></a>';

                // Array Data
                $arr_data[] = array(
                    "id" => $value->id,
                    'language_name' => isset($value->language_name) ? $value->language_name : 'N/A',
                    "title" => $pageTitle,
                    "page_type" => $pageType,
                    "action" => $action
                );
            }
        }
        $totalRecords = $records_count;
        $totalDisplayRecord = $arr_data;

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $arr_data
        );
        return json_encode($response);
    }

    /**
     * Edit Cms.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 20 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            __('language.cms') => route('adminPanel.cms-pages.index'),
            __('language.edit') => '',
        ];

        // Get CMS info to update
        $cms = Cms::where('id', dv($id))->first();

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['cms'] = $cms;

        return view('admin-panel.cms-pages.edit')->with($this->viewData);
    }

    /**
     * Update Cms.
     *
     * @return mixed
     *
     * @author Divyansh
     * @created 20 Jan 2023
     */
    public function update(Request $request, $id)
    {
        // Get user
        $authUser = auth()->user();
        //----------

        $cmsUpdate = false;
        $errorMessage = null;

        // Get Cms
        $cms = Cms::where('id', dv($id))->first();
        //----------

        // Update Cms
        DB::beginTransaction();
        try {

            $data = [
                // 'title'                       => $request['title'],
                'description'                 => $request['description'],
                'meta_title'                  => $request['meta_title'],
                'meta_keyword'                => $request['meta_keyword'],
                'meta_description'            => $request['meta_desc'],
                'updated_by' => $authUser->id,
                'updated_at' => Carbon::now()->toDateTimeString()
            ];

            // Upload Cms image
            if ($request->hasFile('image')) {
                // Remove old image
                if (!is_null($cms->image)) {
                    delete_image(config('constants.cms.image_path'), $cms->image);
                }
                //-----------------

                $image = $this->uploadImage($request->file('image'), config('constants.cms.image_path'), null, 'cms-');
                if ($image['_status']) {
                    $imageName = $image['_data'];
                    $data['image'] = $imageName;
                }
            }
            //-------------------

            $cmsUpdate = Cms::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $cmsUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('CMS update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------
        if (!is_null($cmsUpdate)) {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Cms']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.cms-pages.index')->with(['notification' => $notification]);
        } else {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Cms']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.cms-pages.edit', ['id' => ev($cms->id)])->withInput()->with(['notification' => $notification]);
        }
    }
}
