@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title d-flex justify-content-between">
                                <h2 class="mb-0">Dashboard</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .classical-image-container {
                    position: relative;
                    height: 100%;
                    min-height: 100px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .image-wrapper {
                    position: relative;
                    width: 100%;
                    max-width: 420px;
                }

                .glass-frame {
                    padding: 18px;
                    animation: float-gentle 6s ease-in-out infinite;
                }

                .banner-image {
                    width: 100%;
                    height: auto;
                    max-height: 200px;
                    object-fit: contain;
                    border-radius: 16px;
                    filter: drop-shadow(0 18px 45px rgba(0, 0, 0, 0.35));
                }

                .float-icon {
                    position: absolute;
                    width: 52px;
                    height: 52px;
                    background: rgba(255, 255, 255, 0.25);
                    backdrop-filter: blur(12px);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
                }

                .float-icon i {
                    font-size: 24px;
                    color: #fff;
                    animation: sparkle-rotate 2.5s ease-in-out infinite;
                }

                .icon-1 {
                    top: 0;
                    right: -10px;
                    animation: float-up-down 3s ease-in-out infinite;
                }

                .icon-2 {
                    bottom: 10px;
                    left: -10px;
                    animation: float-up-down 4s ease-in-out infinite .8s;
                }

                .icon-3 {
                    top: 65%;
                    right: -15px;
                    animation: float-up-down 3.5s ease-in-out infinite 1.2s;
                }

                @keyframes float-gentle {

                    0%,
                    100% {
                        transform: translateY(0)
                    }

                    50% {
                        transform: translateY(-20px)
                    }
                }

                @keyframes float-up-down {

                    0%,
                    100% {
                        transform: translateY(0)
                    }

                    50% {
                        transform: translateY(-15px)
                    }
                }

                @keyframes sparkle-rotate {

                    0%,
                    100% {
                        transform: rotate(0deg);
                        opacity: 1
                    }

                    50% {
                        transform: rotate(180deg);
                        opacity: .7
                    }
                }

                @keyframes shimmer {
                    0% {
                        transform: translateX(-100%)
                    }

                    100% {
                        transform: translateX(100%)
                    }
                }

                @keyframes countUp {
                    from {
                        opacity: 0;
                        transform: translateY(10px)
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0)
                    }
                }

                :root {
                    --card-bg: #ffffff;
                    --card-border: rgba(0, 0, 0, 0.08);
                    --text-primary: #2c3e50;
                    --text-muted: #6c757d;
                    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
                    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
                }

                [data-pc-theme="dark"] {
                    --card-bg: #1e293b;
                    --card-border: rgba(255, 255, 255, 0.1);
                    --text-primary: #f1f5f9;
                    --text-muted: #cbd5e1;
                    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
                    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.5);
                }

                .avtar {
                    width: 56px;
                    height: 56px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 14px;
                    transition: all 0.3s ease;
                }

                .avtar-primary {
                    background: linear-gradient(135deg, rgba(13, 110, 253, .15), rgba(13, 110, 253, .05));
                    color: #0d6efd;
                }

                .avtar-success {
                    background: linear-gradient(135deg, rgba(25, 135, 84, .15), rgba(25, 135, 84, .05));
                    color: #198754;
                }

                .avtar-info {
                    background: linear-gradient(135deg, rgba(13, 202, 240, .15), rgba(13, 202, 240, .05));
                    color: #0dcaf0;
                }

                .avtar-warning {
                    background: linear-gradient(135deg, rgba(255, 193, 7, .15), rgba(255, 193, 7, .05));
                    color: #ffc107;
                }

                .avtar-danger {
                    background: linear-gradient(135deg, rgba(220, 53, 69, .15), rgba(220, 53, 69, .05));
                    color: #dc3545;
                }

                .f-26 {
                    font-size: 26px;
                }

                .quick-stat {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 14px;
                    background: rgba(255, 255, 255, 0.15);
                    backdrop-filter: blur(10px);
                    border-radius: 10px;
                    color: #fff;
                    font-size: 14px;
                    white-space: nowrap;
                }

                .quick-stat i {
                    font-size: 20px;
                }

                .progress {
                    height: 6px;
                    border-radius: 10px;
                    background: rgba(0, 0, 0, 0.05);
                    overflow: hidden;
                }

                [data-pc-theme="dark"] .progress {
                    background: rgba(255, 255, 255, 0.05);
                }

                .progress-bar {
                    border-radius: 10px;
                    transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
                    position: relative;
                    overflow: hidden;
                }

                .progress-bar::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .3), transparent);
                    animation: shimmer 2s infinite;
                }

                .bg-primary {
                    background: linear-gradient(90deg, #0d6efd, #0a58ca);
                }

                .bg-success {
                    background: linear-gradient(90deg, #198754, #146c43);
                }

                .bg-info {
                    background: linear-gradient(90deg, #0dcaf0, #087990);
                }

                .bg-warning {
                    background: linear-gradient(90deg, #ffc107, #cc9a06);
                }

                .bg-danger {
                    background: linear-gradient(90deg, #dc3545, #b02a37);
                }

                [data-pc-theme="light"] .stat-card {
                    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                }

                [data-pc-theme="dark"] .stat-card {
                    background: linear-gradient(135deg, var(--card-bg) 0%, rgba(30, 41, 59, .8) 100%);
                }

                @media (max-width:768px) {
                    .avtar {
                        width: 48px;
                        height: 48px;
                    }

                    .f-26 {
                        font-size: 22px;
                    }
                }
            </style>

            {{-- ══ WELCOME BANNER ══ --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card welcome-banner">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-12 d-flex align-items-center">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="user-upload wid-75">
                                                <img src="{{ asset('assets/images/bella.png') }}" alt="Logo"
                                                    class="img-fluid" style="max-width:140px;" />
                                            </div>
                                        </div>
                                        <div class="content-stack">
                                            <h2 class="text-white mb-1">Slip Management App</h2>
                                            <div class="quick-stat mt-2">
                                                <i class="ti ti-calendar"></i>
                                                <span>{{ date('l, F j, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 d-none d-lg-block">
                                    <div class="classical-image-container">
                                        <div class="image-wrapper">
                                            <div class="glass-frame">
                                                <img src="{{ asset('assets/images/widget/welcome-banner.png') }}"
                                                    alt="Welcome Banner" class="banner-image" />
                                            </div>
                                            <div class="float-icon icon-1"><i class="ti ti-sparkles"></i></div>
                                            <div class="float-icon icon-2"><i class="ti ti-star-filled"></i></div>
                                            <div class="float-icon icon-3"><i class="ti ti-circle-check-filled"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ ROW 1 — KPI Stat Cards ══ --}}
            <div class="row g-3 mb-4">


                <div class="col-xl-3 col-sm-6">
                    <div class="card bm-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="bm-stat-label">Total Users</p>
                                    <h3 class="bm-stat-value text-success">{{ $totalUsers }}</h3>
                                </div>
                                <div class="avtar avtar-m bg-light-success rounded">
                                    <i class="ti ti-user f-24"></i>
                                </div>
                            </div>
                            <div class="bm-stat-footer">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('users.index') }}" class="text-success f-12">
                                        <i class="ti ti-eye me-1"></i> View all users
                                    </a>
                                @else
                                    <span class="text-muted f-12">
                                        <i class="ti ti-lock me-1"></i> Only Admin can view users
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card bm-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="bm-stat-label">Total Slips</p>
                                    <h3 class="bm-stat-value text-primary">{{ $totalSlips }}</h3>
                                </div>
                                <div class="avtar avtar-m bg-light-primary rounded">
                                    <i class="ti ti-truck-delivery f-24"></i>
                                </div>
                            </div>
                            <div class="bm-stat-footer">
                                <a href="{{ route('slips.index') }}" class="text-primary f-12">
                                    <i class="ti ti-eye me-1"></i> View all slips
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card bm-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="bm-stat-label">Total Companies</p>
                                    <h3 class="bm-stat-value text-warning">{{ $totalCompany }}</h3>
                                </div>
                                <div class="avtar avtar-m bg-light-warning rounded">
                                    <i class="ti ti-building f-24"></i>
                                </div>
                            </div>
                            <div class="bm-stat-footer">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('management.index') }}" class="text-warning f-12">
                                        <i class="ti ti-eye me-1"></i> View all companies
                                    </a>
                                @else
                                    <span class="text-muted f-12">
                                        <i class="ti ti-lock me-1"></i> Only Admin can manage
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card bm-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="bm-stat-label">Total Materials</p>
                                    <h3 class="bm-stat-value text-info">{{ $totalMaterial }}</h3>
                                </div>
                                <div class="avtar avtar-m bg-light-info rounded">
                                    <i class="ti ti-box f-24"></i>
                                </div>
                            </div>
                            <div class="bm-stat-footer">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('management.index') }}" class="text-info f-12">
                                        <i class="ti ti-eye me-1"></i> View all materials
                                    </a>
                                @else
                                    <span class="text-muted f-12">
                                        <i class="ti ti-lock me-1"></i> Only Admin can manage
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .bm-stat-card {
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .bm-stat-card .card-body {
            padding: 20px;
        }

        .bm-stat-label {
            font-size: 0.75rem;
            color: #8a9ab5;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .bm-stat-value {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 4px;
            line-height: 1;
        }

        .bm-stat-sub {
            font-size: 0.78rem;
            margin-bottom: 0;
            color: #8a9ab5;
        }

        .bm-stat-footer {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            font-size: 0.78rem;
        }
    </style>
@endsection
