@extends('nutrition-panel.layouts.main-layout')

@section('page-title', 'Coach Management | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
<link href="{{ asset('admin-assets/css/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">

<style>
    .coaches-page-wrap {
        padding: 6px 10px 40px 10px;
        color: #1e293b;
        font-family: 'Plus Jakarta Sans', 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Breadcrumbs */
    .fcc-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .fcc-breadcrumb-nav a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .fcc-breadcrumb-nav a:hover {
        color: #2563eb;
    }

    .fcc-breadcrumb-sep {
        color: #94a3b8;
    }

    .fcc-breadcrumb-active {
        color: #0f172a;
        font-weight: 600;
    }

    /* Header Bar */
    .fcc-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .fcc-page-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .fcc-page-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 0;
        font-weight: 400;
    }

    .btn-add-coach {
        background: #2563eb;
        color: #ffffff !important;
        border: 1px solid #2563eb;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.25);
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-add-coach:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    /* Top KPI Cards */
    .kpi-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 991px) {
        .kpi-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .kpi-cards-grid {
            grid-template-columns: 1fr;
        }
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3.5px;
    }

    .kpi-card.blue::before { background: #2563eb; }
    .kpi-card.green::before { background: #10b981; }
    .kpi-card.indigo::before { background: #6366f1; }
    .kpi-card.amber::before { background: #f59e0b; }

    .kpi-icon-circle {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .kpi-card.blue .kpi-icon-circle { background: #eff6ff; color: #2563eb; }
    .kpi-card.green .kpi-icon-circle { background: #ecfdf5; color: #10b981; }
    .kpi-card.indigo .kpi-icon-circle { background: #eef2ff; color: #6366f1; }
    .kpi-card.amber .kpi-icon-circle { background: #fffbeb; color: #f59e0b; }

    .kpi-content {
        flex: 1;
    }

    .kpi-title {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 2px;
    }

    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        font-family: 'Outfit', sans-serif;
    }

    /* Main Table Card */
    .table-container-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .table-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-card-heading {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .table-card-subheading {
        font-size: 13px;
        color: #64748b;
        margin: 2px 0 0 0;
    }

    .coach-search-input {
        height: 38px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 6px 14px 6px 38px !important;
        font-size: 13px;
        width: 250px;
        outline: none;
        transition: all 0.2s ease;
    }

    .coach-search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon-left {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }

    /* Table Design */
    .coaches-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13.5px;
    }

    .coaches-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 13px 16px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }

    .coaches-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .coaches-table tbody tr:hover td {
        background: #f8fafc;
    }

    /* Coach Info Cell */
    .coach-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .coach-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        flex-shrink: 0;
    }

    .coach-avatar-initials {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .coach-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        text-decoration: none;
    }

    .coach-name:hover {
        color: #2563eb;
    }

    .coach-specialization {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    /* Contact Details */
    .coach-contact-item {
        font-size: 13px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 3px;
    }

    .coach-contact-item i {
        color: #94a3b8;
        font-size: 12px;
    }

    /* Badges */
    .badge-members {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-active-sub {
        font-size: 11px;
        color: #059669;
        font-weight: 600;
        margin-top: 3px;
        display: block;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: opacity 0.15s ease;
    }

    .status-badge:hover {
        opacity: 0.85;
    }

    .status-badge.active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .status-badge.inactive {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-badge.active .status-dot { background: #059669; }
    .status-badge.inactive .status-dot { background: #dc2626; }

    /* Action Buttons */
    .btn-action-view {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }

    .btn-action-view:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
        text-decoration: none !important;
        cursor: pointer;
    }

    .btn-action-icon:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .btn-action-icon.delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .actions-flex {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Empty state */
    .empty-coaches-box {
        text-align: center;
        padding: 48px 16px;
    }

    .empty-coaches-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 14px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="coaches-page-wrap">
        <!-- Breadcrumbs -->
        <div class="fcc-breadcrumb-nav">
            <a href="{{ route('nutritionPanel.dashboard') }}">Member & Club</a>
            <span class="fcc-breadcrumb-sep">/</span>
            <span class="fcc-breadcrumb-active">Coach Management</span>
        </div>

        <!-- Header Bar -->
        <div class="fcc-header-bar">
            <div>
                <h1 class="fcc-page-title">Coach Management</h1>
                <p class="fcc-page-subtitle">Manage, track, and assign coaches registered under your franchise.</p>
            </div>
            <div>
                <a href="{{ route('nutritionPanel.coaches.create') }}" class="btn-add-coach">
                    <i class="fa fa-plus"></i> Add Coach
                </a>
            </div>
        </div>

        <!-- Validation Flash Message -->
        @component('nutrition-panel.validation.errors') @endcomponent

        <!-- 4 Top KPI Cards -->
        <div class="kpi-cards-grid">
            <div class="kpi-card blue">
                <div class="kpi-icon-circle">
                    <i class="fa fa-user-circle"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-title">Total Coaches</div>
                    <div class="kpi-value">{{ $totalCoaches }}</div>
                </div>
            </div>

            <div class="kpi-card green">
                <div class="kpi-icon-circle">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-title">Active Coaches</div>
                    <div class="kpi-value">{{ $activeCoaches }}</div>
                </div>
            </div>

            <div class="kpi-card indigo">
                <div class="kpi-icon-circle">
                    <i class="fa fa-users"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-title">Assigned Members</div>
                    <div class="kpi-value">{{ number_format($totalAssignedMembers) }}</div>
                </div>
            </div>

            <div class="kpi-card amber">
                <div class="kpi-icon-circle">
                    <i class="fa fa-tachometer"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-title">Avg Members / Coach</div>
                    <div class="kpi-value">{{ $avgMembersPerCoach }}</div>
                </div>
            </div>
        </div>

        <!-- Main Coaches Table Card -->
        <div class="table-container-card">
            <div class="table-card-header">
                <div>
                    <h3 class="table-card-heading">All Registered Coaches</h3>
                    <p class="table-card-subheading">List of all coaches under this franchise and their member portfolio.</p>
                </div>
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon-left"></i>
                    <input type="text" id="coachSearchBox" class="coach-search-input" placeholder="Search coach name, phone..." style="padding-left: 38px !important;">
                </div>
            </div>

            @if($coaches->count() > 0)
                <div class="table-responsive">
                    <table class="coaches-table" id="coachesListTable">
                        <thead>
                            <tr>
                                <th>Coach</th>
                                <th>Contact</th>
                                <th class="text-center">Assigned Members</th>
                                <th class="text-center">Month Attendance</th>
                                <th class="text-end">Month Revenue</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coaches as $coach)
                                @php
                                    $coachEncryptedId = ev($coach->id);
                                    $initials = strtoupper(substr($coach->name, 0, 2));
                                    $imagePath = '';
                                    if(!empty($coach->profile_image)){
                                        if (Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path').$coach->profile_image)) {
                                            $imagePath = get_image_url(config('constants.users.image_path'), $coach->profile_image);
                                        } elseif (Storage::disk(config('filesystems.default'))->exists(config('constants.users.image_path_thumb').$coach->profile_image)) {
                                            $imagePath = get_image_url(config('constants.users.image_path_thumb'), $coach->profile_image);
                                        }
                                    }
                                @endphp
                                <tr class="coach-row">
                                    <td>
                                        <div class="coach-info-cell">
                                            @if(!empty($imagePath))
                                                <img src="{{ $imagePath }}" class="coach-avatar" alt="{{ $coach->name }}">
                                            @else
                                                <div class="coach-avatar-initials">{{ $initials }}</div>
                                            @endif
                                            <div>
                                                <a href="{{ route('nutritionPanel.coaches.details', ['id' => $coachEncryptedId]) }}" class="coach-name">
                                                    {{ $coach->name }}
                                                </a>
                                                <div class="coach-specialization">
                                                    {{ $coach->specialization ?? 'Fitness Coach' }}
                                                    @if(!empty($coach->experience_years))
                                                        · {{ $coach->experience_years }} exp
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if(!empty($coach->mobile_number))
                                            <div class="coach-contact-item">
                                                <i class="fa fa-phone"></i>
                                                <span>{{ $coach->mobile_number }}</span>
                                            </div>
                                        @endif
                                        @if(!empty($coach->email))
                                            <div class="coach-contact-item">
                                                <i class="fa fa-envelope-o"></i>
                                                <span>{{ $coach->email }}</span>
                                            </div>
                                        @endif
                                        @if(empty($coach->mobile_number) && empty($coach->email))
                                            <span class="text-muted" style="font-size: 12.5px;">No contact set</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-members">
                                            <i class="fa fa-users"></i> {{ $coach->total_members ?? 0 }} members
                                        </span>
                                        <span class="badge-active-sub">
                                            {{ $coach->active_members ?? 0 }} active
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-bold" style="font-size: 12.5px;">
                                            {{ number_format($coach->monthly_attendance ?? 0) }} shakes
                                        </span>
                                    </td>
                                    <td class="text-end font-monospace fw-bold text-dark">
                                        ₹{{ number_format($coach->monthly_revenue ?? 0, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge {{ $coach->status == 1 ? 'active' : 'inactive' }} toggle-coach-status" 
                                              data-id="{{ $coachEncryptedId }}" 
                                              data-status="{{ $coach->status == 1 ? 0 : 1 }}"
                                              title="Click to toggle status">
                                            <span class="status-dot"></span>
                                            <span>{{ $coach->status == 1 ? 'Active' : 'Inactive' }}</span>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="actions-flex justify-content-end">
                                            <a href="{{ route('nutritionPanel.coaches.details', ['id' => $coachEncryptedId]) }}" class="btn-action-view" title="View Assigned Members">
                                                <i class="fa fa-users"></i> Members
                                            </a>
                                            <a href="{{ route('nutritionPanel.coaches.edit', ['id' => $coachEncryptedId]) }}" class="btn-action-icon" title="Edit Coach">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button type="button" class="btn-action-icon delete btn-delete-coach" data-id="{{ $coachEncryptedId }}" title="Delete Coach">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-coaches-box">
                    <div class="empty-coaches-icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <h4 class="fw-bold mb-1">No coaches found</h4>
                    <p class="text-muted mb-3" style="max-width: 400px; margin: 0 auto 16px auto;">
                        You have not registered any coaches yet. Add your first coach to assign members and track performance.
                    </p>
                    <a href="{{ route('nutritionPanel.coaches.create') }}" class="btn-add-coach">
                        <i class="fa fa-plus"></i> Add First Coach
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quick search filter
    $('#coachSearchBox').on('keyup', function() {
        var query = $(this).val().toLowerCase().trim();
        $('.coach-row').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Toggle Coach Status
    $(document).on('click', '.toggle-coach-status', function(e) {
        e.preventDefault();
        var $badge = $(this);
        var coachId = $badge.data('id');
        var newStatus = $badge.data('status');

        $.ajax({
            url: "{{ route('nutritionPanel.coaches.changeStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: coachId,
                status: newStatus
            },
            success: function(res) {
                if (res.status) {
                    if (newStatus == 1) {
                        $badge.removeClass('inactive').addClass('active');
                        $badge.find('span:last').text('Active');
                        $badge.data('status', 0);
                    } else {
                        $badge.removeClass('active').addClass('inactive');
                        $badge.find('span:last').text('Inactive');
                        $badge.data('status', 1);
                    }
                    if (typeof App !== 'undefined' && App.alert) {
                        App.alert(res.message, 'success');
                    }
                }
            },
            error: function() {
                alert('Failed to update status. Please try again.');
            }
        });
    });

    // Delete Coach
    $(document).on('click', '.btn-delete-coach', function(e) {
        e.preventDefault();
        var coachId = $(this).data('id');
        var $row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this coach? Assigned members will remain intact.')) {
            $.ajax({
                url: "{{ route('nutritionPanel.coaches.destroy') }}",
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: coachId
                },
                success: function(res) {
                    if (res.status) {
                        $row.fadeOut(300, function() { $(this).remove(); });
                    }
                },
                error: function() {
                    alert('Error deleting coach. Please try again.');
                }
            });
        }
    });
});
</script>
@endpush
