<template>
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

    <!-- ══ WELCOME BANNER ══ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-banner">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-12 d-flex align-items-center">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="user-upload wid-75">
                                        <img src="/assets/images/old.jpeg" alt="Logo" class="img-fluid"
                                            style="max-width:140px;" />
                                    </div>
                                </div>
                                <div class="content-stack">
                                    <h2 class="text-white mb-1">Slip Management App</h2>
                                    <div class="quick-stat mt-2">
                                        <i class="ti ti-calendar"></i>
                                        <span>{{ today }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="classical-image-container">
                                <div class="image-wrapper">
                                    <div class="glass-frame">
                                        <img src="/assets/images/widget/welcome-banner.png" alt="Welcome Banner"
                                            class="banner-image" />
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

    <!-- ══ KPI STAT CARDS ══ -->
    <div class="row g-3 mb-4">

        <!-- Total Users -->
        <div class="col-xl-3 col-sm-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Users</p>
                            <h3 class="bm-stat-value text-success">{{ totalUsers }}</h3>
                        </div>
                        <div class="avtar avtar-m bg-light-success rounded"><i class="ti ti-user f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="user.isAdmin" href="/users" class="text-success f-12">
                            <i class="ti ti-eye me-1"></i> View all users
                        </Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin can view
                            users</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Slips -->
        <div class="col-xl-3 col-sm-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Slips</p>
                            <h3 class="bm-stat-value text-primary">{{ totalSlips }}</h3>
                        </div>
                        <div class="avtar avtar-m bg-light-primary rounded"><i class="ti ti-truck-delivery f-24"></i>
                        </div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link href="/slips" class="text-primary f-12"><i class="ti ti-eye me-1"></i> View all slips
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Companies -->
        <div class="col-xl-3 col-sm-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Companies</p>
                            <h3 class="bm-stat-value text-warning">{{ totalCompany }}</h3>
                        </div>
                        <div class="avtar avtar-m bg-light-warning rounded"><i class="ti ti-building f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="user.isAdmin" href="/management" class="text-warning f-12">
                            <i class="ti ti-eye me-1"></i> View all companies
                        </Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin can
                            manage</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Materials -->
        <div class="col-xl-3 col-sm-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Materials</p>
                            <h3 class="bm-stat-value text-info">{{ totalMaterial }}</h3>
                        </div>
                        <div class="avtar avtar-m bg-light-info rounded"><i class="ti ti-box f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="user.isAdmin" href="/management" class="text-info f-12">
                            <i class="ti ti-eye me-1"></i> View all materials
                        </Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin can
                            manage</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

defineProps({
    totalUsers: Number,
    totalSlips: Number,
    totalCompany: Number,
    totalMaterial: Number,
});

const user = computed(() => usePage().props.auth.user);

const today = new Date().toLocaleDateString('en-US', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
});
</script>

<style>
/* Dashboard ke saare styles — Blade waale jaise hi */
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
    filter: drop-shadow(0 18px 45px rgba(0, 0, 0, .35));
}

.float-icon {
    position: absolute;
    width: 52px;
    height: 52px;
    background: rgba(255, 255, 255, .25);
    backdrop-filter: blur(12px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
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

.avtar {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    transition: all .3s ease;
}

.quick-stat {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: rgba(255, 255, 255, .15);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    white-space: nowrap;
}

.quick-stat i {
    font-size: 20px;
}

.bm-stat-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
}

.bm-stat-card .card-body {
    padding: 20px;
}

.bm-stat-label {
    font-size: .75rem;
    color: #8a9ab5;
    letter-spacing: .5px;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.bm-stat-value {
    font-size: 1.7rem;
    font-weight: 700;
    margin-bottom: 4px;
    line-height: 1;
}

.bm-stat-footer {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 0, 0, .06);
    font-size: .78rem;
}
</style>