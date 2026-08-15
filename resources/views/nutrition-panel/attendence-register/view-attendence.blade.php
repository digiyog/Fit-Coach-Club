<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">View Attendance</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <style type="text/css">
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            text-align: center;
        }

        .calendar-day-header {
            font-weight: bold;
            background: #f2f2f2;
            padding: 8px 0;
            border-radius: 8px;
        }

        .calendar-day {
            border-radius: 12px;
            padding: 15px 5px;
            font-size: 14px;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: transform 0.2s ease;
        }

        .calendar-day:hover {
            transform: scale(1.05);
        }

        .calendar-day.present {
            background-color: #c8f7c5; /* Light green */
            color: #2e7d32;
            border: 1px solid #81c784;
        }

        .calendar-day.absent {
            background-color: #ffcdd2; /* Light red */
            color: #c62828;
            border: 1px solid #ef5350;
        }

        .calendar-day.not-marked {
            background-color: #eeeeee;
            color: #757575;
            border: 1px solid #ccc;
        }

        .calendar-day.empty {
            background: transparent;
            border: none;
        }

        .day-number {
            font-weight: bold;
            font-size: 16px;
        }
    </style>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                @php
                    $monthName = date('F', mktime(0, 0, 0, $month, 10));
                    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                @endphp

                <div class="card">
                    <div class="card-header text-center">
                        <h5>{{ $monthName }} {{ $year }} - Attendance Calendar</h5>
                    </div>

                    <div class="card-body">
                        <div class="calendar-grid">
                            {{-- Days of week --}}
                            @foreach ($daysOfWeek as $day)
                                <div class="calendar-day-header">{{ $day }}</div>
                            @endforeach

                            {{-- Empty slots before the 1st day --}}
                            @for ($i = 0; $i < $firstDayOfWeek; $i++)
                                <div class="calendar-day empty"></div>
                            @endfor

                            {{-- Days of the month --}}
                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $currentDate = \Carbon\Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                                    $attendance = $attendances->get($currentDate);
                                    $status = $attendance ? $attendance->type : null;
                                @endphp

                                <div class="calendar-day
                                    @if ($status == 2) present
                                    @elseif ($status == 1) absent
                                    @else absent
                                    @endif">
                                    <span class="day-number">{{ $day }}</span>
                                    <small>
                                        @if ($status == 2)
                                            Present
                                        @elseif ($status == 1)
                                            Absent
                                        @else
                                            Absent
                                        @endif
                                    </small>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
    </div>
</div>