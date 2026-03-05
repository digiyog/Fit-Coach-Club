<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
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
     * View roles.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Sumit
     * @created_at 27 July 2019
     */
    public function index()
    {
        return view('admin-panel.roles.index');
    }

    /**
     * Get roles list.
     *
     * @return response
     *
     * @author Rajesh
     * @created_at 22 Dec 2021
     */
    public function getRoles(Request $request)
    {
        $auth_user = auth()->user();

        // Ajax Post
        $draw = $request->get('draw');
        $start = $request->get('start');
        $limit = $request->get('length');
        $sort = $request->get('order')[0];
        $search = $request->get('search')['value'];
        
        $filter = array(
            "name" => $request->name,
            "platform" => $request->platform,
            "date_range" => $request->date_range,
            "email" => $request->email,
            "mobile_number" => $request->mobile_number,
            "learner_type" => $request->learner_type
        );
        
        // Getting Roles
        $records_count = Role::GetRoles(null, null, $search, $filter, $sort);
        $records = Role::GetRoles($limit, $start, $search, $filter, $sort);

        $arr_data = array();

        if(count($records) > 0)
        {
            foreach($records as $key => $value)
            {
                $name = $value['name'];
                $permissions = 'N/A';
                $status = '';
                $action = '';

                // Preparing Data
                if (count($value['permissions']))
                {
                    $permissions = implode(', ',$value['permissions']['name']);
                }
                
                if ( $value['status'] == 0 )
                {
                    $status .= '<label class="badge badge-warning">Inactive</label> &nbsp;';
                } 
                else 
                {
                    $status .= '<label class="badge badge-success">Active</label> &nbsp;';
                }
                
                if ($value['is_default'] == 0)
                {
                    $action .= '<a href="' . route('adminPanel.roles.edit', ['id' => ev($value['id'])]) . '" class="btn btn-sm btn-icon btn-pure btn-default on-default edit-row edit-icon" title="Edit"><i class="icon wb-pencil" aria-hidden="true"></i></a>';
                }
                
                // Array Data
                $arr_data[] = array(
                    "id" => $value['id'],
                    "name" => $name,
                    "status" => $status,
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
}
