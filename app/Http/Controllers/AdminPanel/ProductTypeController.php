<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\ProductType;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class ProductTypeController extends Controller
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
     * View Product Types list.
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
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Product Types' => '',
        ];

        // Breadcrumb Button
        $breadcrumbButton = [];
        $breadcrumbButton[] = [
            'btn_class' => 'btn btn-primary mt-2 rounded-circle',
            'btn_link' => route('adminPanel.product-types.create'),
            'btn_icon' => 'plus',
            'btn_text' => __('language.add_button'),
            'attributes' => []
        ];

        // KPI Counts
        $totalTypes = ProductType::count();
        $activeTypes = ProductType::where('status', 1)->count();
        $inactiveTypes = ProductType::where('status', 0)->count();
        $thisMonthAdded = ProductType::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['breadcrumbButton'] = $breadcrumbButton;
        $this->viewData['authUser']         = $authUser;
        $this->viewData['totalTypes']       = $totalTypes;
        $this->viewData['activeTypes']      = $activeTypes;
        $this->viewData['inactiveTypes']    = $inactiveTypes;
        $this->viewData['thisMonthAdded']   = $thisMonthAdded;
        
        return view('admin-panel.product-types.index')->with($this->viewData);
    }

    /**
     * Get Product Types list.
     *
     * @return response
     *
     * @author Sandeep
     * @created_at 20 Jan 2023
    */
    public function getProductTypes(Request $request)
    {
        $authUser = auth()->user();

        // Ajax Post Parameters
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $limit  = $request->get('length');
        $order  = $request->get('order');
        $sort   = (!empty($order) && isset($order[0])) ? $order[0] : null;
        $search = isset($request->get('search')['value']) ? $request->get('search')['value'] : null;
        
        // Filter Parameters
        $filter = array();

        // Getting Product Types Records
        $records_count  = ProductType::getProductTypes(null, null, $search, $filter, $sort);
        $records        = ProductType::getProductTypes($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        $colorStyles = [
            ['bg' => '#eff6ff', 'color' => '#2563eb', 'border' => '#dbeafe'], // Blue
            ['bg' => '#fffbeb', 'color' => '#d97706', 'border' => '#fde68a'], // Gold/Amber
            ['bg' => '#faf5ff', 'color' => '#9333ea', 'border' => '#f3e8ff'], // Purple/Silver
            ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#bbf7d0'], // Emerald/Bronze
        ];

        if(count($records) > 0)
        {
            $rowIndex = $start + 1;
            foreach($records as $key => $value)
            {
                $color = $colorStyles[($rowIndex - 1) % count($colorStyles)];

                // Row Number
                $numberCell = '<span class="fcc-row-number">' . $rowIndex . '</span>';

                // Type Name Cell
                $typeNameCell = '<div class="fcc-type-name-cell">' .
                    '<div class="fcc-type-icon-box" style="background: ' . $color['bg'] . '; color: ' . $color['color'] . '; border: 1px solid ' . $color['border'] . ';">' .
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' .
                            '<polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>' .
                            '<polyline points="2 17 12 22 22 17"></polyline>' .
                            '<polyline points="2 12 12 17 22 12"></polyline>' .
                        '</svg>' .
                    '</div>' .
                    '<span class="fcc-type-title">' . e($value->name ?? 'N/A') . '</span>' .
                '</div>';

                // Status Badge
                if ($value->status == 1) {
                    $status = '<span class="fcc-status-badge fcc-status-active"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Active</span>';
                } else {
                    $status = '<span class="fcc-status-badge fcc-status-inactive"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Inactive</span>';
                }

                // Created At
                $createdAt = !empty($value->created_at) ? Carbon::parse($value->created_at)->format('d M Y, h:i A') : 'N/A';
                $createdCell = '<span class="fcc-date-cell">' .
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> ' .
                    $createdAt .
                '</span>';

                // Action buttons
                $action = '<div class="fcc-action-btns">' .
                    '<a href="' . route('adminPanel.product-types.edit', ['id' => ev($value->id)]) . '" class="fcc-btn-action-edit" title="Edit Product Type">' .
                        '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>' .
                    '</a>' .
                    '<button type="button" class="fcc-btn-action-delete single-delete-record" data-id="' . $value->id . '" title="Delete Product Type">' .
                        '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>' .
                    '</button>' .
                '</div>';

                // Array Data
                $arr_data[] = array(
                    "id"                => $value->id,
                    "row_number"        => $numberCell,
                    "name"              => $typeNameCell,
                    "status"            => $status,
                    "created_at"        => $createdCell,
                    "action"            => $action,
                );

                $rowIndex++;
            }
        }

        $totalRecords = $records_count;

        $response = array(
            "draw"                  => intval($draw),
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data
        );

        return response()->json($response);
    }

    /**
        * View create Product Types.
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
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Product Types' => route('adminPanel.product-types.index'),
            __('language.create') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;

        return view('admin-panel.product-types.create')->with($this->viewData);
    }

    /**
     * Store Product Types.
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
 
        $productType       = null;
        $errorMessage   = null;

        // Begin Transaction
        DB::beginTransaction();
        
        // Create Product Type
        try {

            // Set data
            $data = [
                'name'                  => $request['name'],
                'created_by'            => $authUser->id,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ];
            
            $productType = ProductType::create($data);

            DB::commit();

        } catch (\Exception $e) {
            $productType       = null;
            $errorMessage   = $e->getMessage();
            \Log::error('ProductType create Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($productType)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.record_created', ['record' => 'Product Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.record_creation_failed', ['record' => 'Product Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.create')->withInput()->with(['notification' => $notification]);
        }
    }

    /**
     * Edit Product Types.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Divyansh
     * @created 24 Jan 2023
     */
    public function edit(Request $request, $id)
    {
        $productType = ProductType::where('id', dv($id))->first();

        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'Product Types' => route('adminPanel.product-types.index'),
            __('language.edit') => '',
        ];

        // View Data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['productType'] = $productType;

        return view('admin-panel.product-types.edit')->with($this->viewData);
    }

    /**
     * Update Product Type.
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
        
        $productTypeUpdate  = false;
        $errorMessage       = null;
        
        // Update Product Type
        DB::beginTransaction();

        try {

            // Update Product Type
            $productType = ProductType::where('id', dv($id))->first();

            $data = [
                'name'       => $request['name'],
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
            
            $productTypeUpdate = ProductType::where('id', dv($id))->update($data);

            DB::commit();
        } catch (\Exception $e) {
            $productTypeUpdate = null;
            $errorMessage = $e->getMessage();
            \Log::error('ProductType update Error: ' . $e->getMessage());
            DB::rollback();
        }
        //------------

        if (!is_null($productTypeUpdate)) 
        {
            // Set notification
            $notification = [
                '_status' => true,
                '_message' => __('messages.records_updated', ['record' => 'Product Type']),
                '_type' => 'success',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.index')->with(['notification' => $notification]);
        } 
        else 
        {
            // Set notification
            $notification = [
                '_status' => false,
                '_message' => __('messages.records_updation_failed', ['record' => 'Product Type']),
                '_type' => 'error',
            ];
            //-----------------

            return redirect()->route('adminPanel.product-types.edit')->withInput()->with(['notification' => $notification]);
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
        $language = ProductType::toggleStatus($request['ids']);
        
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
        $productType = ProductType::whereIn('id', $ids)->delete();
        
        // Set response
        if ($productType == true) 
        {
            $response = [
                '_status' => true,
                '_message' => __('messages.record_deleted', ['record' => 'Product Type']),
                '_type' => 'success',
            ];
        } 
        else 
        {
            $response = [
                '_status' => false,
                '_message' => __('messages.record_failed', ['record' => 'Product Type']),
                '_type' => 'error',
            ];
        }
        //-------------
        
        return response()->json($response, 200);
    }

}
