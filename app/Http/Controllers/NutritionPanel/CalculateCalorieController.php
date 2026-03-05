<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\Models\User;
use App\Http\Traits\UploadImage;
use App\Http\Requests\AdminPanel\ChangePassword;
use Illuminate\Support\Facades\Hash;
use App\Models\Document;
use Illuminate\Support\Facades\Mail;
use App\Http\Traits\SendNotification;
use Storage;

class CalculateCalorieController extends Controller
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
     * Calculate Calories
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     *
     * @author Rajesh
     * @created 4 Feb 2022
     */
    public function index(Request $request)
    {
        // Get logged in nutrition user
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Calculate Calories' => '',
        ];

        // Send view data
        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;

        return view('nutrition-panel.calculate-calories.index')->with($this->viewData);
    }
}
