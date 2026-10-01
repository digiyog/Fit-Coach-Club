<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Coach;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Transaction;
use App\Http\Traits\UploadImage;
use Storage;

class CoachController extends Controller
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
     * Backfill coaches from existing users if table is empty or missing coach
     */
    private function syncExistingCoaches($franchiseId)
    {
        try {
            Coach::ensureTableExists();

            $existingCoachNames = User::where('created_by', $franchiseId)
                ->where('role_type', 'user')
                ->whereNotNull('coach_name')
                ->where('coach_name', '!=', '')
                ->groupBy('coach_name')
                ->pluck('coach_name')
                ->toArray();

            foreach ($existingCoachNames as $coachName) {
                $trimmed = trim($coachName);
                if (empty($trimmed)) continue;

                $coach = Coach::where('franchise_id', $franchiseId)
                    ->where('name', $trimmed)
                    ->first();

                if (!$coach) {
                    Coach::create([
                        'franchise_id'     => $franchiseId,
                        'name'             => $trimmed,
                        'specialization'   => 'Fitness & Nutrition Coach',
                        'experience_years' => '2+',
                        'status'           => 1,
                        'created_by'       => $franchiseId,
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Sync existing coaches error: ' . $e->getMessage());
        }
    }

    /**
     * Coach Management Index
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $franchiseId = $authUser->id;

        // Auto backfill existing coaches
        $this->syncExistingCoaches($franchiseId);

        // Fetch all coaches for this franchise
        $coaches = Coach::where('franchise_id', $franchiseId)
            ->orderBy('id', 'DESC')
            ->get();

        // Calculate metrics for each coach
        $totalAssignedMembers = 0;
        $totalActiveMembers = 0;

        foreach ($coaches as $coach) {
            $membersCount = User::where('created_by', $franchiseId)
                ->where('role_type', 'user')
                ->where('coach_name', $coach->name)
                ->count();

            $activeMembersCount = User::where('created_by', $franchiseId)
                ->where('role_type', 'user')
                ->where('coach_name', $coach->name)
                ->where('status', 1)
                ->where('days', '>', 0)
                ->count();

            $coach->total_members = $membersCount;
            $coach->active_members = $activeMembersCount;

            // Monthly attendance for coach's members
            $monthlyAttendance = Attendance::join('users', 'attendances.user_id', '=', 'users.id')
                ->where('attendances.franchise_id', $franchiseId)
                ->where('attendances.type', 2)
                ->whereMonth('attendances.date', now()->month)
                ->whereYear('attendances.date', now()->year)
                ->where('users.coach_name', $coach->name)
                ->count();

            // Monthly revenue for coach's members
            $monthlyRevenue = Transaction::join('users', 'transactions.user_id', '=', 'users.id')
                ->where('transactions.created_by', $franchiseId)
                ->whereMonth('transactions.created_at', now()->month)
                ->whereYear('transactions.created_at', now()->year)
                ->where('users.coach_name', $coach->name)
                ->sum('transactions.received_amount');

            $coach->monthly_attendance = $monthlyAttendance;
            $coach->monthly_revenue = $monthlyRevenue;

            $totalAssignedMembers += $membersCount;
            $totalActiveMembers += $activeMembersCount;
        }

        $totalCoaches = $coaches->count();
        $activeCoaches = $coaches->where('status', 1)->count();
        $avgMembersPerCoach = $totalCoaches > 0 ? round($totalAssignedMembers / $totalCoaches) : 0;

        // Breadcrumb
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Coach Management' => '',
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['coaches'] = $coaches;
        $this->viewData['totalCoaches'] = $totalCoaches;
        $this->viewData['activeCoaches'] = $activeCoaches;
        $this->viewData['totalAssignedMembers'] = $totalAssignedMembers;
        $this->viewData['totalActiveMembers'] = $totalActiveMembers;
        $this->viewData['avgMembersPerCoach'] = $avgMembersPerCoach;

        return view('nutrition-panel.coaches.index')->with($this->viewData);
    }

    /**
     * Create Coach View
     */
    public function create(Request $request)
    {
        $authUser = auth()->user();

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Coach Management' => route('nutritionPanel.coaches.index'),
            'Add Coach' => '',
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;

        return view('nutrition-panel.coaches.create')->with($this->viewData);
    }

    /**
     * Store New Coach
     */
    public function store(Request $request)
    {
        $authUser = auth()->user();

        $request->validate([
            'name'             => 'required|string|max:191',
            'email'            => 'nullable|email|max:191',
            'mobile_number'    => 'nullable|string|max:20',
            'specialization'   => 'nullable|string|max:191',
            'experience_years' => 'nullable|string|max:50',
            'bio'              => 'nullable|string|max:1000',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        Coach::ensureTableExists();

        $data = [
            'franchise_id'     => $authUser->id,
            'name'             => trim($request->name),
            'email'            => $request->email ? trim($request->email) : null,
            'mobile_number'    => $request->mobile_number ? trim($request->mobile_number) : null,
            'specialization'   => $request->specialization ? trim($request->specialization) : 'Fitness Coach',
            'experience_years' => $request->experience_years ? trim($request->experience_years) : null,
            'bio'              => $request->bio ? trim($request->bio) : null,
            'status'           => $request->has('status') ? (int)$request->status : 1,
            'created_by'       => $authUser->id,
        ];

        // Profile Image Upload
        if ($request->hasFile('profile_image')) {
            $imageName = $this->uploadImage($request->file('profile_image'), config('constants.users.image_path'));
            $data['profile_image'] = $imageName;
        }

        Coach::create($data);

        $notification = [
            '_status'  => true,
            '_message' => 'Coach created successfully.',
            '_type'    => 'success',
        ];

        return redirect()->route('nutritionPanel.coaches.index')->with(['notification' => $notification]);
    }

    /**
     * Coach Details & Assigned Members View
     */
    public function details($id)
    {
        $authUser = auth()->user();
        $coachId = dv($id);
        $coach = Coach::where('franchise_id', $authUser->id)->findOrFail($coachId);

        // Fetch assigned members with pagination
        $members = User::where('created_by', $authUser->id)
            ->where('role_type', 'user')
            ->where('coach_name', $coach->name)
            ->orderBy('id', 'DESC')
            ->paginate(15);

        // Coach metrics
        $totalMembers = User::where('created_by', $authUser->id)->where('coach_name', $coach->name)->count();
        $activeMembers = User::where('created_by', $authUser->id)->where('coach_name', $coach->name)->where('status', 1)->where('days', '>', 0)->count();
        $onlineMembers = User::where('created_by', $authUser->id)->where('coach_name', $coach->name)->where('user_state', 'Online')->count();
        $offlineMembers = User::where('created_by', $authUser->id)->where('coach_name', $coach->name)->where('user_state', 'Offline')->count();
        $totalDueAmount = User::where('created_by', $authUser->id)->where('coach_name', $coach->name)->sum('due_amount');

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Coach Management' => route('nutritionPanel.coaches.index'),
            $coach->name => '',
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['coach'] = $coach;
        $this->viewData['members'] = $members;
        $this->viewData['totalMembers'] = $totalMembers;
        $this->viewData['activeMembers'] = $activeMembers;
        $this->viewData['onlineMembers'] = $onlineMembers;
        $this->viewData['offlineMembers'] = $offlineMembers;
        $this->viewData['totalDueAmount'] = $totalDueAmount;

        return view('nutrition-panel.coaches.details')->with($this->viewData);
    }

    /**
     * Edit Coach View
     */
    public function edit($id)
    {
        $authUser = auth()->user();
        $coachId = dv($id);
        $coach = Coach::where('franchise_id', $authUser->id)->findOrFail($coachId);

        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Coach Management' => route('nutritionPanel.coaches.index'),
            'Edit ' . $coach->name => '',
        ];

        $this->viewData['breadcrumb'] = $breadcrumb;
        $this->viewData['authUser'] = $authUser;
        $this->viewData['coach'] = $coach;

        return view('nutrition-panel.coaches.edit')->with($this->viewData);
    }

    /**
     * Update Coach
     */
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $coachId = dv($id);
        $coach = Coach::where('franchise_id', $authUser->id)->findOrFail($coachId);

        $request->validate([
            'name'             => 'required|string|max:191',
            'email'            => 'nullable|email|max:191',
            'mobile_number'    => 'nullable|string|max:20',
            'specialization'   => 'nullable|string|max:191',
            'experience_years' => 'nullable|string|max:50',
            'bio'              => 'nullable|string|max:1000',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $oldName = $coach->name;
        $newName = trim($request->name);

        $data = [
            'name'             => $newName,
            'email'            => $request->email ? trim($request->email) : null,
            'mobile_number'    => $request->mobile_number ? trim($request->mobile_number) : null,
            'specialization'   => $request->specialization ? trim($request->specialization) : 'Fitness Coach',
            'experience_years' => $request->experience_years ? trim($request->experience_years) : null,
            'bio'              => $request->bio ? trim($request->bio) : null,
            'status'           => $request->has('status') ? (int)$request->status : 1,
        ];

        // Profile Image Upload
        if ($request->hasFile('profile_image')) {
            $imageName = $this->uploadImage($request->file('profile_image'), config('constants.users.image_path'));
            $data['profile_image'] = $imageName;
        }

        $coach->update($data);

        // Sync updated coach name with assigned members
        if ($oldName !== $newName) {
            User::where('created_by', $authUser->id)
                ->where('role_type', 'user')
                ->where('coach_name', $oldName)
                ->update(['coach_name' => $newName]);
        }

        $notification = [
            '_status'  => true,
            '_message' => 'Coach updated successfully.',
            '_type'    => 'success',
        ];

        return redirect()->route('nutritionPanel.coaches.index')->with(['notification' => $notification]);
    }

    /**
     * Soft Delete Coach
     */
    public function destroy(Request $request)
    {
        $authUser = auth()->user();
        $coachId = dv($request->id);

        $coach = Coach::where('franchise_id', $authUser->id)->find($coachId);
        if ($coach) {
            $coach->delete();
            return response()->json(['status' => true, 'message' => 'Coach removed successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'Coach not found.'], 404);
    }

    /**
     * Toggle Coach Active Status
     */
    public function changeStatus(Request $request)
    {
        $authUser = auth()->user();
        $coachId = dv($request->id);

        $coach = Coach::where('franchise_id', $authUser->id)->find($coachId);
        if ($coach) {
            $coach->status = (int)$request->status;
            $coach->save();
            return response()->json(['status' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'Coach not found.'], 404);
    }
}
