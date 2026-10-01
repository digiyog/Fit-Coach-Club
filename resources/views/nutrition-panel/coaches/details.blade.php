@extends('nutrition-panel.layouts.main-layout')

@section('page-title', $coach->name . ' - Coach Details | ' . __('language.page_main_title'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .coach-details-wrap {
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

    /* Profile Header Card */
    .coach-profile-hero {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 26px 30px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .hero-left-flex {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .coach-hero-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #eff6ff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }

    .coach-hero-initials {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 800;
        border: 3px solid #eff6ff;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }

    .coach-hero-name {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .coach-hero-spec {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .coach-hero-spec-tag {
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 99px;
    }

    .coach-hero-contacts {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        font-size: 13px;
        color: #475569;
        flex-wrap: wrap;
    }

    .hero-contact-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hero-contact-item i {
        color: #94a3b8;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-edit-coach {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: #334155;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }

    .btn-edit-coach:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    /* 5 Metric Summary Grid */
    .details-stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    @media (max-width: 991px) {
        .details-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .details-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .stat-box-lbl {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stat-box-val {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
    }

    /* Members Table Card */
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

    .members-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13.5px;
    }

    .members-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }

    .members-table tbody td {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .members-table tbody tr:hover td {
        background: #f8fafc;
    }

    .member-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar-mini {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
    }

    .member-name {
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
    }

    .member-name:hover {
        color: #2563eb;
    }

    .btn-view-member {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }

    .btn-view-member:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .badge-pill-plan {
        background: #f1f5f9;
        color: #475569;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="layout-px-spacing">
    <div class="coach-details-wrap">
        <!-- Breadcrumbs -->
        <div class="fcc-breadcrumb-nav">
            <a href="{{ route('nutritionPanel.dashboard') }}">Member & Club</a>
            <span class="fcc-breadcrumb-sep">/</span>
            <a href="{{ route('nutritionPanel.coaches.index') }}">Coach Management</a>
            <span class="fcc-breadcrumb-sep">/</span>
            <span class="fcc-breadcrumb-active">{{ $coach->name }}</span>
        </div>

        @php
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

        <!-- Hero Profile Card -->
        <div class="coach-profile-hero">
            <div class="hero-left-flex">
                @if(!empty($imagePath))
                    <img src="{{ $imagePath }}" class="coach-hero-avatar" alt="{{ $coach->name }}">
                @else
                    <div class="coach-hero-initials">{{ $initials }}</div>
                @endif
                <div>
                    <h1 class="coach-hero-name">{{ $coach->name }}</h1>
                    <div class="coach-hero-spec">
                        <span class="coach-hero-spec-tag">{{ $coach->specialization ?? 'Fitness Coach' }}</span>
                        @if(!empty($coach->experience_years))
                            <span>• {{ $coach->experience_years }} Experience</span>
                        @endif
                        <span class="badge {{ $coach->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $coach->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="coach-hero-contacts">
                        @if(!empty($coach->mobile_number))
                            <div class="hero-contact-item">
                                <i class="fa fa-phone"></i>
                                <span>{{ $coach->mobile_number }}</span>
                            </div>
                        @endif
                        @if(!empty($coach->email))
                            <div class="hero-contact-item">
                                <i class="fa fa-envelope-o"></i>
                                <span>{{ $coach->email }}</span>
                            </div>
                        @endif
                    </div>
                    @if(!empty($coach->bio))
                        <div class="mt-2 text-muted" style="font-size: 13px; max-width: 600px;">
                            {{ $coach->bio }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="hero-actions">
                <a href="{{ route('nutritionPanel.coaches.edit', ['id' => ev($coach->id)]) }}" class="btn-edit-coach">
                    <i class="fa fa-pencil"></i> Edit Profile
                </a>
                <a href="{{ route('nutritionPanel.users.index', ['coach_name' => $coach->name]) }}" class="btn btn-primary" style="border-radius: 10px; font-weight: 600; font-size: 13.5px; padding: 9px 18px;">
                    <i class="fa fa-external-link me-1"></i> Open in User Management
                </a>
            </div>
        </div>

        <!-- 5 Metric Summary Grid -->
        <div class="details-stats-grid">
            <div class="stat-box">
                <div class="stat-box-lbl">Total Members</div>
                <div class="stat-box-val text-primary">{{ $totalMembers }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-lbl">Active Members</div>
                <div class="stat-box-val text-success">{{ $activeMembers }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-lbl">Online Members</div>
                <div class="stat-box-val text-info">{{ $onlineMembers }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-lbl">Offline Members</div>
                <div class="stat-box-val text-secondary">{{ $offlineMembers }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-lbl">Due Amount</div>
                <div class="stat-box-val {{ $totalDueAmount > 0 ? 'text-danger' : 'text-dark' }}">
                    ₹{{ number_format($totalDueAmount) }}
                </div>
            </div>
        </div>

        <!-- Assigned Members Table -->
        <div class="table-container-card">
            <div class="table-card-header">
                <div>
                    <h3 class="table-card-heading">Assigned Members ({{ $members->total() }})</h3>
                    <p class="text-muted mb-0" style="font-size: 13px;">All members currently coached by {{ $coach->name }}</p>
                </div>
                <a href="{{ route('nutritionPanel.users.create', ['type' => 'ums', 'user_type' => 'Regular User']) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-user-plus me-1"></i> Add New Member
                </a>
            </div>

            @if($members->count() > 0)
                <div class="table-responsive">
                    <table class="members-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Plan / Type</th>
                                <th>State</th>
                                <th class="text-center">Days Left</th>
                                <th class="text-end">Due Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>
                                        <div class="member-cell">
                                            <div class="member-avatar-mini">
                                                {{ strtoupper(substr($member->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('nutritionPanel.users.details', ['id' => ev($member->id)]) }}" class="member-name">
                                                    {{ $member->name }}
                                                </a>
                                                <div class="text-muted" style="font-size: 12px;">{{ $member->mobile_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-pill-plan">{{ $member->user_type ?? 'Regular' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $member->user_state == 'Online' ? 'bg-primary' : 'bg-secondary' }}" style="font-size: 11px;">
                                            {{ $member->user_state ?? 'Offline' }}
                                        </span>
                                    </td>
                                    <td class="text-center font-monospace fw-bold">
                                        {{ $member->days }} days
                                    </td>
                                    <td class="text-end font-monospace {{ $member->due_amount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                        ₹{{ number_format($member->due_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ ($member->status == 1 && $member->days > 0) ? 'bg-success' : 'bg-danger' }}">
                                            {{ ($member->status == 1 && $member->days > 0) ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('nutritionPanel.users.details', ['id' => ev($member->id)]) }}" class="btn-view-member">
                                            <i class="fa fa-eye"></i> View Profile
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    {{ $members->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-users text-muted mb-2" style="font-size: 32px;"></i>
                    <h5 class="fw-bold text-dark">No members assigned yet</h5>
                    <p class="text-muted" style="font-size: 13px;">When you add members under this franchise and select "{{ $coach->name }}" as their coach, they will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
