<?php

namespace App\Http\Controllers\NutritionPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use App\Models\Attendance;
use App\Models\User;
use App\Models\MealType;
use App\Models\AttendanceLogs;
use App\Http\Traits\UploadImage;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CounsellingController extends Controller
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
     * View Counsellings list.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    */
    public function index()
    {
        $authUser = auth()->user();

        // Adding breadcrumb array
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Counsellings' => '',
        ];

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->groupBy('coach_name')
            ->pluck('coach_name');

        // Meal types
        $mealTypes = MealType::where('status', 1)->orderBy('name')->get();
        $mealPlansCount = $mealTypes->count();

        // Completed count today
        $todayCompletedCount = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where('date', date('Y-m-d'))
            ->count();

        // Total Sessions this month
        $totalMonthlySessions = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where(function($q) {
                $q->where(function($s1) {
                    $s1->whereNotNull('date')->whereMonth('date', date('m'))->whereYear('date', date('Y'));
                })->orWhere(function($s2) {
                    $s2->whereNull('date')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                });
            })
            ->count();

        // Pending follow-ups
        $pendingFollowUpsCount = Attendance::join('users', 'attendances.user_id', '=', 'users.id')
            ->where('attendances.franchise_id', $authUser->id)
            ->where('attendances.type', 2)
            ->where('users.role_type', 'user')
            ->where(function($q) {
                $q->where('users.days', '<=', 5)
                  ->orWhere('users.due_amount', '>', 0);
            })
            ->distinct('attendances.user_id')
            ->count('attendances.user_id');

        // Total dues flagged for franchise members
        $duesFlagged = User::where('created_by', $authUser->id)
            ->where('due_amount', '>', 0)
            ->sum('due_amount');

        // Last session timestamp today
        $lastAttendance = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where('date', date('Y-m-d'))
            ->latest('created_at')
            ->first();

        $lastSessionTime = $lastAttendance && !empty($lastAttendance->created_at) 
            ? date('h:i A', strtotime($lastAttendance->created_at)) 
            : '09:41 AM';

        // View Data
        $this->viewData['breadcrumbFilter'] = $breadcrumb;
        $this->viewData['todayCompletedCount'] = $todayCompletedCount;
        $this->viewData['totalMonthlySessions'] = $totalMonthlySessions;
        $this->viewData['pendingFollowUpsCount'] = $pendingFollowUpsCount;
        $this->viewData['mealPlansCount'] = $mealPlansCount;
        $this->viewData['duesFlagged'] = $duesFlagged;
        $this->viewData['lastSessionTime'] = $lastSessionTime;
        $this->viewData['coachesList'] = $coachesList;
        $this->viewData['mealTypes'] = $mealTypes;
        $this->viewData['authUser'] = $authUser;
        
        return view('nutrition-panel.counsellings.index')->with($this->viewData);
    }

    /**
     * Get Counsellings list.
     *
     * @return response
    */
    public function getCounsellings(Request $request)
    {
        $draw   = $request->get('draw');
        $start  = $request->get('start') ? intval($request->get('start')) : 0;
        $limit  = $request->get('length') ? intval($request->get('length')) : 25;
        $sort   = $request->get('order')[0] ?? null;
        $search = $request->get('search')['value'] ?? null;
        
        // Filter Parameters
        $filter = array(
            "name" => $request->name,
            "coach_name" => $request->coach_name,
            "plan_id" => $request->plan_id,
            "date" => $request->date,
            "tab" => $request->tab,
        );

        $arr_data = array();
        $totalRecords = 0;

        try {
            // Getting Counsellings Records
            $records_count  = Attendance::getCounsellings(null, null, $search, $filter, $sort);
            $records        = Attendance::getCounsellings($limit, $start, $search, $filter, $sort);
            $totalRecords   = intval($records_count);

            if(!empty($records) && count($records) > 0)
            {
                $colors = [
                    ['bg' => '#e0e7ff', 'color' => '#3b46f1'], // Blue
                    ['bg' => '#f3e8ff', 'color' => '#9333ea'], // Purple
                    ['bg' => '#fce7f3', 'color' => '#db2777'], // Pink
                    ['bg' => '#dcfce7', 'color' => '#16a34a'], // Green
                    ['bg' => '#fef3c7', 'color' => '#d97706'], // Amber / Yellow
                    ['bg' => '#ffedd5', 'color' => '#ea580c'], // Orange
                ];

                foreach($records as $key => $value)
                {
                    $userId         = $value->id ?? 0;
                    $name           = !empty($value->name) ? $value->name : 'N/A';
                    $coach_name     = !empty($value->coach_name) ? $value->coach_name : 'N/A';
                    $attendanceCount= $value->total_attendance ?? 1;
                    $pendingDays    = $value->days ?? 0;
                    $current_meals  = !empty($value->meal_type_name) ? $value->meal_type_name : '';
                    $due_amount     = (float)($value->due_amount ?? 0);
                    $joinedDate     = !empty($value->user_created_at) ? date('d M Y', strtotime($value->user_created_at)) : '12 Sep 2026';
                    $completedAt    = !empty($value->attendance_time) ? date('H:i:s', strtotime($value->attendance_time)) : '-';

                    // 1. Initials and Avatar
                    $initials = '';
                    $nameWords = explode(' ', trim($name));
                    foreach ($nameWords as $w) {
                        if (!empty($w)) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    }
                    $initials = substr($initials, 0, 1);
                    if (empty($initials)) $initials = 'M';

                    $cIdx = abs(crc32($name)) % count($colors);
                    $avatarCol = $colors[$cIdx];

                    $profileImageUrl = null;
                    if (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                    } elseif (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $value->profile_image);
                    }

                    if ($profileImageUrl) {
                        $avatarInner = '<img src="'.$profileImageUrl.'" class="rounded-circle" style="width: 34px; height: 34px; min-width: 34px; object-fit: cover; border: 1.5px solid #e2e8f0;" alt="'.e($name).'" />';
                    } else {
                        $avatarInner = '<div class="fcc-avatar-circle" style="width: 34px; height: 34px; min-width: 34px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>';
                    }

                    // Member HTML with Initial/Image and Joined Date
                    $memberHtml = '<div class="d-flex align-items-center gap-2">
                        '.$avatarInner.'
                        <div>
                            <div class="fcc-member-name fw-bold" style="color: #0f172a; font-size: 13px; line-height: 1.2;">'.e($name).'</div>
                            <div class="fcc-member-joined text-muted" style="font-size: 11px; margin-top: 2px;">Joined: '.$joinedDate.'</div>
                        </div>
                    </div>';

                    // 2. Attendance Count (Att.)
                    $attHtml = '<div class="fcc-att-pill">'.$attendanceCount.'</div>';

                    // 3. Coach Name
                    $coachHtml = '<span class="fcc-coach-name">'.e($coach_name).'</span>';

                    // 4. Plan
                    $planHtml = '<span class="fcc-plan-name">'.$this->formatDoubleLinePlan(!empty($current_meals) ? $current_meals : '21 Days Challenge (Loss)').'</span>';

                    // 5. Pending Days
                    $pendingHtml = '<div class="fcc-pending-pill">'.$pendingDays.'</div>';

                    // 6. Progress (Weight, Recent Diff between previous day and current day, Total Diff from start)
                    $curWeight = (float)($value->attendance_weight ?: ($value->current_weight ?: 0));
                    $startWeight = (float)($value->starting_weight ?: 0);
                    $prevWeight = !empty($value->previous_weight) ? (float)$value->previous_weight : 0;

                    if ($curWeight > 0) {
                        $weightDisplay = number_format($curWeight, ($curWeight == (int)$curWeight ? 1 : 2)) . ' kg';
                    } else {
                        $weightDisplay = '-';
                    }

                    // Total diff against starting weight
                    if ($curWeight > 0 && $startWeight > 0) {
                        $totalDiff = round($curWeight - $startWeight, 2);
                        if ($totalDiff < 0) {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss">-'.abs($totalDiff).' kg</span>';
                        } elseif ($totalDiff > 0) {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain">+'.abs($totalDiff).' kg</span>';
                        } else {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                        }
                    } else {
                        $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                    }

                    // Recent diff: difference between current day and previous day/session weight
                    if ($curWeight > 0 && $prevWeight > 0) {
                        $diffKg = round($curWeight - $prevWeight, 3);
                        if ($diffKg < 0) {
                            $absDiff = abs($diffKg);
                            if ($absDiff < 1.0) {
                                $grams = intval(round($absDiff * 1000));
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss">-'.$grams.' g</span>';
                            } else {
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss">-'.$absDiff.' kg</span>';
                            }
                        } elseif ($diffKg > 0) {
                            if ($diffKg < 1.0) {
                                $grams = intval(round($diffKg * 1000));
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain">+'.$grams.' g</span>';
                            } else {
                                $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain">+'.$diffKg.' kg</span>';
                            }
                        } else {
                            $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 g</span>';
                        }
                    } else {
                        $recentDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 g</span>';
                    }

                    $progressHtml = '<div class="d-flex align-items-center gap-1 flex-nowrap text-nowrap" style="white-space: nowrap;">
                        <span class="fcc-weight-val">'.$weightDisplay.'</span>
                        '.$recentDiffHtml.'
                        '.$totalDiffHtml.'
                    </div>';

                    // 7. Dues
                    if ($due_amount > 0) {
                        $duesHtml = '<span class="fcc-dues-flagged text-nowrap">₹'.number_format($due_amount, 0).'</span>';
                    } else {
                        $duesHtml = '<span class="text-muted text-nowrap" style="font-size: 13px;">₹0</span>';
                    }

                    // Encrypted ID for URLs
                    $encryptedId = $userId ? ev($userId) : '';

                    // 8. Meal Button
                    if (!empty($current_meals) && $userId) {
                        $mealHtml = '<a href="'.route('nutritionPanel.users.details', ['id' => $encryptedId]).'" class="btn fcc-btn-view-meal text-nowrap" style="white-space: nowrap;">View meal</a>';
                    } else {
                        $mealHtml = '<span class="text-muted text-nowrap" style="font-size: 13px; white-space: nowrap;">No meal</span>';
                    }

                    // 9. Completed At
                    $completedAtHtml = '<span style="font-size: 12.5px; color: #334155; font-weight: 500;">'.$completedAt.'</span>';

                    // 10. Actions Dropdown matching modern 3-dots
                    $action = '<div class="dropdown custom-dropdown d-inline-block">
                        <a class="dropdown-toggle fcc-action-dots-btn" href="#" role="button" id="dropdownMenuLink_'.$userId.'" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_'.$userId.'" style="border-radius: 12px; min-width: 230px; padding: 6px; height: auto !important; max-height: none !important; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif; white-space: nowrap;">
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.users.viewWeights', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-balance-scale me-2 text-muted"></i> View Weight</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.users.viewAttendance', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-calendar-check-o me-2 text-muted"></i> View Attendance</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $encryptedId]) : route('nutritionPanel.manual-attendances.manual-attendance')).'"><i class="fa fa-clock-o me-2 text-muted"></i> Manual Attendance</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.track-shake.index', ['id' => $encryptedId]) : route('nutritionPanel.track-shake.index')).'"><i class="fa fa-coffee me-2 text-muted"></i> Track Shake</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.orders.index').'"><i class="fa fa-shopping-cart me-2 text-muted"></i> Purchase Products</a>
                            <div class="dropdown-divider my-1"></div>
                            <a class="dropdown-item py-2 px-3 rounded-2 text-primary fw-bold" href="'.($userId ? route('nutritionPanel.users.details', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-id-card-o me-2 text-primary"></i> View Details</a>
                        </div>
                    </div>';

                    // Row Checkbox
                    $checkboxHtml = '<label class="fcc-custom-checkbox m-0"><input type="checkbox" class="fcc-checkbox-input child-chk" value="'.$userId.'"><span class="fcc-checkbox-control"><svg viewBox="0 0 12 10" class="fcc-check-icon"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span></label>';

                    // Array Data
                    $arr_data[] = array(
                        "DT_RowClass"       => ($due_amount > 0 ? 'fcc-row-dues-flagged' : ''),
                        "id"                => $userId,
                        "checkbox"          => $checkboxHtml,
                        "name"              => $memberHtml,
                        "att"               => $attHtml,
                        "attendance"        => $attHtml,
                        "coach_name"        => $coachHtml,
                        "plan"              => $planHtml,
                        "days"              => $pendingHtml,
                        "progress"          => $progressHtml,
                        "dues"              => $duesHtml,
                        "meal"              => $mealHtml,
                        "current_meals"     => $mealHtml,
                        "completed_at"      => $completedAtHtml,
                        "date"              => $completedAtHtml,
                        "action"            => $action
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('Counselling getCounsellings error: ' . $e->getMessage());
        }

        $response = array(
            "draw"                  => intval($draw),
            "recordsTotal"          => $totalRecords,
            "recordsFiltered"       => $totalRecords,
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data
        );

        return response()->json($response);
    }

    /**
     * View Previous Month Counselling list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function previousMonth(Request $request)
    {
        $authUser = auth()->user();

        // Default to previous month
        $prevMonthCarbon = Carbon::now()->subMonth();
        $selectedMonth = str_pad($request->get('month', $prevMonthCarbon->format('m')), 2, '0', STR_PAD_LEFT);
        $selectedYear  = $request->get('year', $prevMonthCarbon->format('Y'));

        $selectedCarbon = Carbon::createFromDate(intval($selectedYear), intval($selectedMonth), 1);
        $monthName      = $selectedCarbon->format('F');
        $monthYearLabel = $selectedCarbon->format('F Y');

        $prevNav = $selectedCarbon->copy()->subMonth();
        $nextNav = $selectedCarbon->copy()->addMonth();

        // Build list of last 12 months for selector
        $monthOptions = [];
        $cursor = Carbon::now();
        for ($i = 0; $i < 12; $i++) {
            $mVal = $cursor->format('m');
            $yVal = $cursor->format('Y');
            $isPrev = ($mVal == $prevMonthCarbon->format('m') && $yVal == $prevMonthCarbon->format('Y'));
            $isCurrent = ($mVal == Carbon::now()->format('m') && $yVal == Carbon::now()->format('Y'));

            $label = $cursor->format('F Y');
            if ($isPrev) {
                $label .= ' (Previous Month)';
            } elseif ($isCurrent) {
                $label .= ' (Current Month)';
            }

            $monthOptions[] = [
                'month'       => $mVal,
                'year'        => $yVal,
                'label'       => $label,
                'is_selected' => ($mVal == $selectedMonth && $yVal == $selectedYear),
                'is_previous' => $isPrev
            ];
            $cursor->subMonth();
        }

        // Breadcrumb
        $breadcrumb = [
            __('language.dashboard') => route('nutritionPanel.dashboard'),
            'Counsellings'           => route('nutritionPanel.counsellings.index'),
            'Previous Month (' . $monthYearLabel . ')' => '',
        ];

        // Coaches list
        $coachesList = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->groupBy('coach_name')
            ->pluck('coach_name');

        // Meal types
        $mealTypes = MealType::where('status', 1)->orderBy('name')->get();
        $mealPlansCount = $mealTypes->count();

        // Total Sessions in selected month
        $totalMonthlySessions = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where(function($q) use ($selectedMonth, $selectedYear) {
                $q->where(function($s1) use ($selectedMonth, $selectedYear) {
                    $s1->whereNotNull('date')->whereMonth('date', $selectedMonth)->whereYear('date', $selectedYear);
                })->orWhere(function($s2) use ($selectedMonth, $selectedYear) {
                    $s2->whereNull('date')->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                });
            })
            ->count();

        // Unique Members counselled in selected month
        $uniqueMembersCount = Attendance::where('franchise_id', $authUser->id)
            ->where('type', 2)
            ->where(function($q) use ($selectedMonth, $selectedYear) {
                $q->where(function($s1) use ($selectedMonth, $selectedYear) {
                    $s1->whereNotNull('date')->whereMonth('date', $selectedMonth)->whereYear('date', $selectedYear);
                })->orWhere(function($s2) use ($selectedMonth, $selectedYear) {
                    $s2->whereNull('date')->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                });
            })
            ->distinct('user_id')
            ->count('user_id');

        // Active coaches in selected month
        $activeCoachesCount = User::where('created_by', $authUser->id)
            ->whereNotNull('coach_name')
            ->where('coach_name', '!=', '')
            ->whereIn('id', function($sub) use ($authUser, $selectedMonth, $selectedYear) {
                $sub->select('user_id')->from('attendances')
                    ->where('franchise_id', $authUser->id)
                    ->where('type', 2)
                    ->whereNull('deleted_at')
                    ->where(function($q) use ($selectedMonth, $selectedYear) {
                        $q->where(function($s1) use ($selectedMonth, $selectedYear) {
                            $s1->whereNotNull('date')->whereMonth('date', $selectedMonth)->whereYear('date', $selectedYear);
                        })->orWhere(function($s2) use ($selectedMonth, $selectedYear) {
                            $s2->whereNull('date')->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                        });
                    });
            })
            ->distinct('coach_name')
            ->count('coach_name');

        // Dues flagged for counselled members
        $duesFlagged = User::where('created_by', $authUser->id)
            ->where('due_amount', '>', 0)
            ->whereIn('id', function($sub) use ($authUser, $selectedMonth, $selectedYear) {
                $sub->select('user_id')->from('attendances')
                    ->where('franchise_id', $authUser->id)
                    ->where('type', 2)
                    ->whereNull('deleted_at')
                    ->where(function($q) use ($selectedMonth, $selectedYear) {
                        $q->where(function($s1) use ($selectedMonth, $selectedYear) {
                            $s1->whereNotNull('date')->whereMonth('date', $selectedMonth)->whereYear('date', $selectedYear);
                        })->orWhere(function($s2) use ($selectedMonth, $selectedYear) {
                            $s2->whereNull('date')->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                        });
                    });
            })
            ->sum('due_amount');

        // Weight loss calculations
        $weightLossCount = 0;
        $totalWeightLost = 0;

        try {
            $memberIds = Attendance::where('franchise_id', $authUser->id)
                ->where('type', 2)
                ->whereNull('deleted_at')
                ->where(function($q) use ($selectedMonth, $selectedYear) {
                    $q->where(function($s1) use ($selectedMonth, $selectedYear) {
                        $s1->whereNotNull('date')->whereMonth('date', $selectedMonth)->whereYear('date', $selectedYear);
                    })->orWhere(function($s2) use ($selectedMonth, $selectedYear) {
                        $s2->whereNull('date')->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                    });
                })
                ->distinct()
                ->pluck('user_id');

            if ($memberIds->isNotEmpty()) {
                $mIdList = $memberIds->toArray();
                $weights = Attendance::where('franchise_id', $authUser->id)
                    ->where('type', 2)
                    ->whereNull('deleted_at')
                    ->whereIn('user_id', $mIdList)
                    ->where('weight', '>', 0)
                    ->where(function($q) use ($selectedMonth, $selectedYear) {
                        $q->where(function($s1) use ($selectedMonth, $selectedYear) {
                            $s1->whereNotNull('date')->whereMonth('date', $selectedMonth)->whereYear('date', $selectedYear);
                        })->orWhere(function($s2) use ($selectedMonth, $selectedYear) {
                            $s2->whereNull('date')->whereMonth('created_at', $selectedMonth)->whereYear('created_at', $selectedYear);
                        });
                    })
                    ->orderBy('date', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->get()
                    ->groupBy('user_id');

                foreach ($weights as $uId => $attendances) {
                    if ($attendances->count() >= 1) {
                        $firstWeight = (float)$attendances->first()->weight;
                        $lastWeight  = (float)$attendances->last()->weight;
                        if ($firstWeight > 0 && $lastWeight > 0 && $lastWeight < $firstWeight) {
                            $weightLossCount++;
                            $totalWeightLost += ($firstWeight - $lastWeight);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Weight loss calculation error: ' . $e->getMessage());
        }

        // View Data
        $this->viewData['breadcrumbFilter']    = $breadcrumb;
        $this->viewData['authUser']            = $authUser;
        $this->viewData['selectedMonth']       = $selectedMonth;
        $this->viewData['selectedYear']        = $selectedYear;
        $this->viewData['monthName']           = $monthName;
        $this->viewData['monthYearLabel']      = $monthYearLabel;
        $this->viewData['prevNav']             = $prevNav;
        $this->viewData['nextNav']             = $nextNav;
        $this->viewData['monthOptions']        = $monthOptions;
        $this->viewData['totalMonthlySessions']= $totalMonthlySessions;
        $this->viewData['uniqueMembersCount']  = $uniqueMembersCount;
        $this->viewData['weightLossCount']     = $weightLossCount;
        $this->viewData['totalWeightLost']     = round($totalWeightLost, 1);
        $this->viewData['activeCoachesCount']  = $activeCoachesCount;
        $this->viewData['duesFlagged']         = $duesFlagged;
        $this->viewData['mealPlansCount']      = $mealPlansCount;
        $this->viewData['coachesList']         = $coachesList;
        $this->viewData['mealTypes']           = $mealTypes;

        return view('nutrition-panel.counsellings.previous-month')->with($this->viewData);
    }

    /**
     * Get Previous Month Counsellings list for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreviousMonthCounsellings(Request $request)
    {
        $draw   = $request->get('draw');
        $start  = $request->get('start') ? intval($request->get('start')) : 0;
        $limit  = $request->get('length') ? intval($request->get('length')) : 25;
        $sort   = $request->get('order')[0] ?? null;
        $search = $request->get('search')['value'] ?? null;

        $prevMonthCarbon = Carbon::now()->subMonth();
        $month = $request->get('month', $prevMonthCarbon->format('m'));
        $year  = $request->get('year', $prevMonthCarbon->format('Y'));
        $tab   = $request->get('tab', 'all_sessions');

        $filter = array(
            "name"       => $request->name,
            "coach_name" => $request->coach_name,
            "plan_id"    => $request->plan_id,
            "month"      => $month,
            "year"       => $year,
            "tab"        => $tab,
        );

        $arr_data = array();
        $totalRecords = 0;

        try {
            $records_count = Attendance::getMonthlyCounsellings(null, null, $search, $filter, $sort);
            $records       = Attendance::getMonthlyCounsellings($limit, $start, $search, $filter, $sort);
            $totalRecords  = intval($records_count);

            if (!empty($records) && count($records) > 0) {
                $colors = [
                    ['bg' => '#e0e7ff', 'color' => '#3b46f1'],
                    ['bg' => '#f3e8ff', 'color' => '#9333ea'],
                    ['bg' => '#fce7f3', 'color' => '#db2777'],
                    ['bg' => '#dcfce7', 'color' => '#16a34a'],
                    ['bg' => '#fef3c7', 'color' => '#d97706'],
                    ['bg' => '#ffedd5', 'color' => '#ea580c'],
                ];

                foreach ($records as $key => $value) {
                    $userId          = $value->id ?? 0;
                    $name            = !empty($value->name) ? $value->name : 'N/A';
                    $coach_name      = !empty($value->coach_name) ? $value->coach_name : 'N/A';
                    $monthAttCount   = $value->month_attendance_count ?? 1;
                    $totalAttCount   = $value->total_attendance ?? 1;
                    $pendingDays     = $value->days ?? 0;
                    $current_meals   = !empty($value->meal_type_name) ? $value->meal_type_name : '';
                    $due_amount      = (float)($value->due_amount ?? 0);
                    $joinedDate      = !empty($value->user_created_at) ? date('d M Y', strtotime($value->user_created_at)) : '-';
                    $sessionDate     = !empty($value->date) ? date('d M Y', strtotime($value->date)) : (!empty($value->attendance_time) ? date('d M Y', strtotime($value->attendance_time)) : '-');
                    $sessionTime     = !empty($value->attendance_time) ? date('h:i A', strtotime($value->attendance_time)) : '';

                    // Initials and Avatar
                    $initials = '';
                    $nameWords = explode(' ', trim($name));
                    foreach ($nameWords as $w) {
                        if (!empty($w)) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    }
                    $initials = substr($initials, 0, 1) ?: 'M';

                    $cIdx = abs(crc32($name)) % count($colors);
                    $avatarCol = $colors[$cIdx];

                    $profileImageUrl = null;
                    if (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path'), $value->profile_image);
                    } elseif (!empty($value->profile_image) && \Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$value->profile_image)) {
                        $profileImageUrl = get_image_url(config('constants.users.image_path_thumb'), $value->profile_image);
                    }

                    if ($profileImageUrl) {
                        $avatarInner = '<img src="'.$profileImageUrl.'" class="rounded-circle" style="width: 34px; height: 34px; min-width: 34px; object-fit: cover; border: 1.5px solid #e2e8f0;" alt="'.e($name).'" />';
                    } else {
                        $avatarInner = '<div class="fcc-avatar-circle" style="width: 34px; height: 34px; min-width: 34px; border-radius: 50%; background: '.$avatarCol['bg'].'; color: '.$avatarCol['color'].'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; font-family: \'Outfit\', sans-serif;">'.$initials.'</div>';
                    }

                    // 1. Member HTML
                    $memberHtml = '<div class="d-flex align-items-center gap-2">
                        '.$avatarInner.'
                        <div>
                            <div class="fcc-member-name fw-bold" style="color: #0f172a; font-size: 13px; line-height: 1.2;">'.e($name).'</div>
                            <div class="fcc-member-joined text-muted" style="font-size: 11px; margin-top: 2px;">Joined: '.$joinedDate.'</div>
                        </div>
                    </div>';

                    // 2. Attendance Counts
                    $attHtml = '<div class="d-flex align-items-center gap-1">
                        <span class="fcc-att-pill" title="Sessions in selected month">'.$monthAttCount.'</span>
                        <span class="text-muted" style="font-size: 11.5px;" title="Total lifetime attendance">('.$totalAttCount.')</span>
                    </div>';

                    // 3. Coach Name
                    $coachHtml = '<span class="fcc-coach-name">'.e($coach_name).'</span>';

                    // 4. Plan
                    $planHtml = '<span class="fcc-plan-name">'.$this->formatDoubleLinePlan(!empty($current_meals) ? $current_meals : 'Standard Plan').'</span>';

                    // 5. Pending Days
                    $pendingHtml = '<div class="fcc-pending-pill">'.$pendingDays.'</div>';

                    // 6. Weight & Progress
                    $curWeight    = (float)($value->attendance_weight ?: ($value->current_weight ?: 0));
                    $startWeight  = (float)($value->starting_weight ?: 0);
                    $mFirstWeight = (float)($value->month_first_weight ?: 0);
                    $mLastWeight  = (float)($value->month_last_weight ?: $curWeight);

                    $weightDisplay = ($curWeight > 0) ? number_format($curWeight, 1) . ' kg' : '-';

                    // Monthly Net Weight Difference
                    if ($mFirstWeight > 0 && $mLastWeight > 0) {
                        $diffMonth = round($mLastWeight - $mFirstWeight, 2);
                        if ($diffMonth < 0) {
                            $monthDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss" title="Monthly Net Loss: -'.abs($diffMonth).' kg">-'.abs($diffMonth).' kg</span>';
                        } elseif ($diffMonth > 0) {
                            $monthDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain" title="Monthly Net Gain: +'.abs($diffMonth).' kg">+'.abs($diffMonth).' kg</span>';
                        } else {
                            $monthDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                        }
                    } else {
                        $monthDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">-</span>';
                    }

                    // Total diff against starting weight
                    if ($curWeight > 0 && $startWeight > 0) {
                        $totalDiff = round($curWeight - $startWeight, 2);
                        if ($totalDiff < 0) {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-loss" title="Lifetime: -'.abs($totalDiff).' kg">-'.abs($totalDiff).' kg</span>';
                        } elseif ($totalDiff > 0) {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-gain" title="Lifetime: +'.abs($totalDiff).' kg">+'.abs($totalDiff).' kg</span>';
                        } else {
                            $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                        }
                    } else {
                        $totalDiffHtml = '<span class="fcc-progress-pill fcc-pill-neutral">0 kg</span>';
                    }

                    $progressHtml = '<div class="d-flex align-items-center gap-1 flex-nowrap text-nowrap" style="white-space: nowrap;">
                        <span class="fcc-weight-val">'.$weightDisplay.'</span>
                        '.$monthDiffHtml.'
                        '.$totalDiffHtml.'
                    </div>';

                    // 7. Dues
                    if ($due_amount > 0) {
                        $duesHtml = '<span class="fcc-dues-flagged text-nowrap">₹'.number_format($due_amount, 0).'</span>';
                    } else {
                        $duesHtml = '<span class="text-muted text-nowrap" style="font-size: 13px;">₹0</span>';
                    }

                    // Encrypted ID
                    $encryptedId = $userId ? ev($userId) : '';

                    // 8. Meal Button
                    if (!empty($current_meals) && $userId) {
                        $mealHtml = '<a href="'.route('nutritionPanel.users.details', ['id' => $encryptedId]).'" class="btn fcc-btn-view-meal text-nowrap" style="white-space: nowrap;">View meal</a>';
                    } else {
                        $mealHtml = '<span class="text-muted text-nowrap" style="font-size: 13px; white-space: nowrap;">No meal</span>';
                    }

                    // 9. Session Date & Time
                    $sessionDateHtml = '<div class="text-nowrap">
                        <div style="font-size: 12.5px; color: #0f172a; font-weight: 600;">'.$sessionDate.'</div>
                        '.(!empty($sessionTime) ? '<div class="text-muted" style="font-size: 11px;">'.$sessionTime.'</div>' : '').'
                    </div>';

                    // 10. Actions Dropdown
                    $action = '<div class="dropdown custom-dropdown d-inline-block">
                        <a class="dropdown-toggle fcc-action-dots-btn" href="#" role="button" id="dropdownMenuLink_prev_'.$userId.'_'.$key.'" data-bs-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="dropdownMenuLink_prev_'.$userId.'_'.$key.'" style="border-radius: 12px; min-width: 230px; padding: 6px; height: auto !important; max-height: none !important; border: 1px solid #edf2f7 !important; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1) !important; font-family: \'Outfit\', sans-serif; white-space: nowrap;">
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.users.viewWeights', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-balance-scale me-2 text-muted"></i> View Weight</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.users.viewAttendance', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-calendar-check-o me-2 text-muted"></i> View Attendance</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.manual-attendances.manual-attendance', ['id' => $encryptedId]) : route('nutritionPanel.manual-attendances.manual-attendance')).'"><i class="fa fa-clock-o me-2 text-muted"></i> Manual Attendance</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.($userId ? route('nutritionPanel.track-shake.index', ['id' => $encryptedId]) : route('nutritionPanel.track-shake.index')).'"><i class="fa fa-coffee me-2 text-muted"></i> Track Shake</a>
                            <a class="dropdown-item py-2 px-3 rounded-2" href="'.route('nutritionPanel.orders.index').'"><i class="fa fa-shopping-cart me-2 text-muted"></i> Purchase Products</a>
                            <div class="dropdown-divider my-1"></div>
                            <a class="dropdown-item py-2 px-3 rounded-2 text-primary fw-bold" href="'.($userId ? route('nutritionPanel.users.details', ['id' => $encryptedId]) : 'javascript:;').'"><i class="fa fa-id-card-o me-2 text-primary"></i> View Details</a>
                        </div>
                    </div>';

                    // Row Checkbox
                    $checkboxHtml = '<label class="fcc-custom-checkbox m-0"><input type="checkbox" class="fcc-checkbox-input child-chk" value="'.$userId.'"><span class="fcc-checkbox-control"><svg viewBox="0 0 12 10" class="fcc-check-icon"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span></label>';

                    $arr_data[] = array(
                        "DT_RowClass"       => ($due_amount > 0 ? 'fcc-row-dues-flagged' : ''),
                        "id"                => $userId,
                        "checkbox"          => $checkboxHtml,
                        "name"              => $memberHtml,
                        "att"               => $attHtml,
                        "attendance"        => $attHtml,
                        "coach_name"        => $coachHtml,
                        "plan"              => $planHtml,
                        "days"              => $pendingHtml,
                        "progress"          => $progressHtml,
                        "dues"              => $duesHtml,
                        "meal"              => $mealHtml,
                        "current_meals"     => $mealHtml,
                        "completed_at"      => $sessionDateHtml,
                        "date"              => $sessionDateHtml,
                        "action"            => $action
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('Previous Month Counselling getPreviousMonthCounsellings error: ' . $e->getMessage());
        }

        $response = array(
            "draw"                  => intval($draw),
            "recordsTotal"          => $totalRecords,
            "recordsFiltered"       => $totalRecords,
            "iTotalRecords"         => $totalRecords,
            "iTotalDisplayRecords"  => $totalRecords,
            "aaData"                => $arr_data
        );

        return response()->json($response);
    }

    /**
     * Format plan name into double line
     *
     * @param string|null $planName
     * @return string
     */
    protected function formatDoubleLinePlan($planName)
    {
        if (empty($planName)) {
            return '21 Days<br>Challenge (Loss)';
        }

        $planName = trim($planName);

        // If already contains <br>, return as is
        if (strpos($planName, '<br>') !== false || strpos($planName, '<br/>') !== false || strpos($planName, '<br />') !== false) {
            return $planName;
        }

        $words = preg_split('/\s+/', $planName);
        if (count($words) <= 1) {
            return e($planName);
        }

        if (count($words) === 2) {
            return e($words[0]) . '<br>' . e($words[1]);
        }

        // If starts with number + Days (e.g. "21 Days Weight loss Program", "30 Days Challenge")
        if (count($words) >= 3 && is_numeric($words[0]) && strtolower($words[1]) === 'days') {
            $line1 = $words[0] . ' ' . $words[1];
            $line2 = implode(' ', array_slice($words, 2));
            return e($line1) . '<br>' . e($line2);
        }

        // Otherwise split into two balanced lines
        $half = (int)ceil(count($words) / 2);
        $line1 = implode(' ', array_slice($words, 0, $half));
        $line2 = implode(' ', array_slice($words, $half));

        return e($line1) . '<br>' . e($line2);
    }
}

