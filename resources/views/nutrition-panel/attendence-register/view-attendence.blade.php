<div class="modal-header border-0 pb-0" style="padding: 20px 24px 10px 24px;">
    <div class="d-flex align-items-center justify-content-between w-100">
        <div>
            <h5 class="modal-title fw-bold text-dark mb-1" style="font-size: 18px; font-family: 'Outfit', sans-serif;">
                <i class="fa fa-calendar-check-o text-primary me-2"></i> Attendance Calendar
            </h5>
            <p class="text-muted mb-0" style="font-size: 13px;">
                {{ $user->name ?? 'Member' }} &bull; {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}
            </p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
    </div>
</div>

<style type="text/css">
    .fcc-cal-wrap {
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }
    .fcc-cal-summary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 16px;
    }
    .fcc-cal-stat-item {
        text-align: center;
    }
    .fcc-cal-stat-val {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.2;
    }
    .fcc-cal-stat-lbl {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
    }
    .fcc-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        text-align: center;
    }
    .fcc-cal-header-cell {
        font-weight: 700;
        font-size: 12px;
        color: #64748b;
        background: #f1f5f9;
        padding: 8px 0;
        border-radius: 8px;
        text-transform: uppercase;
    }
    .fcc-cal-day-cell {
        border-radius: 10px;
        padding: 10px 4px;
        font-size: 12px;
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .fcc-cal-day-cell:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .fcc-cal-day-cell.present {
        background-color: #dcfce7;
        color: #15803d;
        border-color: #86efac;
    }
    .fcc-cal-day-cell.absent {
        background-color: #fee2e2;
        color: #b91c1c;
        border-color: #fca5a5;
    }
    .fcc-cal-day-cell.empty {
        background: transparent;
        border: none;
        pointer-events: none;
    }
    .fcc-cal-day-num {
        font-weight: 700;
        font-size: 14px;
        line-height: 1;
        margin-bottom: 4px;
    }
    .fcc-cal-day-badge {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    @media (max-width: 576px) {
        .fcc-cal-summary {
            flex-wrap: wrap;
            gap: 10px;
        }
        .fcc-cal-stat-item {
            flex: 1 1 calc(50% - 10px);
            border-left: none !important;
            padding-left: 0 !important;
        }
        .fcc-cal-grid {
            gap: 4px;
        }
        .fcc-cal-header-cell {
            font-size: 10px;
            padding: 4px 0;
        }
        .fcc-cal-day-cell {
            min-height: 44px;
            padding: 6px 2px;
            border-radius: 6px;
        }
        .fcc-cal-day-num {
            font-size: 12px;
            margin-bottom: 2px;
        }
        .fcc-cal-day-badge {
            font-size: 8px;
        }
    }
</style>

@php
    $monthName = date('F', mktime(0, 0, 0, $month, 10));
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $presentCount = 0;
    $absentCount = 0;
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $dateKey = \Carbon\Carbon::createFromDate($year, $month, $d)->format('Y-m-d');
        $att = $attendances->get($dateKey);
        if ($att && $att->type == 2) {
            $presentCount++;
        } else {
            $absentCount++;
        }
    }
    $attendanceRate = $daysInMonth > 0 ? round(($presentCount / $daysInMonth) * 100) : 0;
@endphp

<div class="modal-body fcc-cal-wrap" style="padding: 16px 24px 24px 24px;">
    <!-- Month Summary Stats -->
    <div class="fcc-cal-summary">
        <div class="fcc-cal-stat-item">
            <div class="fcc-cal-stat-val text-dark">{{ $daysInMonth }}</div>
            <div class="fcc-cal-stat-lbl">Total Days</div>
        </div>
        <div class="fcc-cal-stat-item" style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
            <div class="fcc-cal-stat-val text-success">{{ $presentCount }}</div>
            <div class="fcc-cal-stat-lbl">Present</div>
        </div>
        <div class="fcc-cal-stat-item" style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
            <div class="fcc-cal-stat-val text-danger">{{ $absentCount }}</div>
            <div class="fcc-cal-stat-lbl">Absent</div>
        </div>
        <div class="fcc-cal-stat-item" style="border-left: 1px solid #e2e8f0; padding-left: 20px;">
            <div class="fcc-cal-stat-val text-primary">{{ $attendanceRate }}%</div>
            <div class="fcc-cal-stat-lbl">Rate</div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="fcc-cal-grid">
        @foreach ($daysOfWeek as $day)
            <div class="fcc-cal-header-cell">{{ $day }}</div>
        @endforeach

        @for ($i = 0; $i < ($firstDayOfWeek ?? 0); $i++)
            <div class="fcc-cal-day-cell empty"></div>
        @endfor

        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDate = \Carbon\Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                $attendance = $attendances->get($currentDate);
                $isToday = ($currentDate === date('Y-m-d'));
                $isPresent = ($attendance && $attendance->type == 2);
            @endphp

            <div class="fcc-cal-day-cell {{ $isPresent ? 'present' : 'absent' }}" @if($isToday) style="outline: 2px solid #3b46f1; outline-offset: 1px;" @endif title="{{ \Carbon\Carbon::createFromDate($year, $month, $day)->format('D, M d, Y') }} - {{ $isPresent ? 'Present' : 'Absent' }}">
                <span class="fcc-cal-day-num">{{ $day }}</span>
                <span class="fcc-cal-day-badge">
                    @if ($isPresent)
                        Present
                    @else
                        Absent
                    @endif
                </span>
            </div>
        @endfor
    </div>
</div>

<div class="modal-footer border-0" style="padding: 10px 24px 20px 24px;">
    <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600; font-size: 13px;">Close</button>
</div>