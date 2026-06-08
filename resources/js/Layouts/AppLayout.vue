<template>
  <!-- ══════════ SIDEBAR ══════════ -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="navbar-content">

        <div class="card pc-user-card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <Link href="/dashboard">
                  <img src="/assets/images/user/avatar-1.jpg" alt="user" class="user-avtar rounded-circle"
                    style="width:50px;height:50px;" />
                </Link>
              </div>
              <div class="flex-grow-1 ms-3 me-2">
                <h6 class="mb-0">{{ user.name }}</h6>
                <small class="text-muted text-capitalize">{{ user.roleLabel }}</small>
              </div>
            </div>
          </div>
        </div>

        <ul class="pc-navbar">
          <li class="pc-item pc-caption"><label>Navigation</label></li>

          <li class="pc-item">
            <Link href="/dashboard" class="pc-link">
              <span class="pc-micon"><svg class="pc-icon">
                  <use xlink:href="#custom-status-up"></use>
                </svg></span>
              <span class="pc-mtext">Dashboard</span>
            </Link>
          </li>

          <li class="pc-item">
            <Link href="/slips" class="pc-link">
              <span class="pc-micon"><i class="ti ti-file-text"></i></span>
              <span class="pc-mtext">Delivery Slips</span>
            </Link>
          </li>

          <!-- Admin Only -->
          <template v-if="user.isAdmin">
            <li class="pc-item pc-caption"><label>Administration</label></li>
            <li class="pc-item">
              <Link href="/management" class="pc-link">
                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                <span class="pc-mtext">Management</span>
              </Link>
            </li>
            <li class="pc-item">
              <Link href="/users" class="pc-link">
                <span class="pc-micon"><i class="ti ti-users"></i></span>
                <span class="pc-mtext">Users</span>
              </Link>
            </li>
          </template>

          <!-- Manager Only -->
          <template v-else-if="user.isManager">
            <li class="pc-item pc-caption"><label>Administration</label></li>
            <li class="pc-item">
              <Link href="/management" class="pc-link">
                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                <span class="pc-mtext">Management</span>
              </Link>
            </li>
          </template>

          <!-- Logout -->
          <li class="pc-item pc-caption"><label>Account</label></li>
          <li class="pc-item">
            <Link href="/logout" method="post" as="button" class="pc-link text-danger"
              style="border:0;background:none;width:100%;text-align:left;">
              <span class="pc-micon"><i class="ti ti-logout"></i></span>
              <span class="pc-mtext">Logout</span>
            </Link>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <!-- ══════════ TOPBAR ══════════ -->
  <header class="pc-header">
    <div class="header-wrapper">
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide"><i class="ti ti-menu-2"></i></a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse"><i class="ti ti-menu-2"></i></a>
          </li>
        </ul>
      </div>
      <div class="ms-auto">
        <ul class="list-unstyled">

          <!-- Dark / Light mode -->
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
              <svg class="pc-icon">
                <use xlink:href="#custom-sun-1"></use>
              </svg>
            </a>
            <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
              <a href="#!" class="dropdown-item" @click.prevent="setTheme('dark')">
                <svg class="pc-icon">
                  <use xlink:href="#custom-moon"></use>
                </svg><span>Dark</span>
              </a>
              <a href="#!" class="dropdown-item" @click.prevent="setTheme('light')">
                <svg class="pc-icon">
                  <use xlink:href="#custom-sun-1"></use>
                </svg><span>Light</span>
              </a>
              <a href="#!" class="dropdown-item" @click.prevent="setTheme('default')">
                <svg class="pc-icon">
                  <use xlink:href="#custom-setting-2"></use>
                </svg><span>Default</span>
              </a>
            </div>
          </li>

          <!-- Settings / Logout -->
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
              <svg class="pc-icon">
                <use xlink:href="#custom-setting-2"></use>
              </svg>
            </a>
            <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
              <Link href="/logout" method="post" as="button" class="dropdown-item"
                style="border:0;background:none;width:100%;text-align:left;">
                <i class="ti ti-power"></i><span>Logout</span>
              </Link>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </header>

  <!-- ══════════ PAGE CONTENT ══════════ -->
  <div class="pc-container">
    <div class="pc-content">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});

// Theme functions theme.js se global hain
const setTheme = (mode) => {
  if (mode === 'dark') { localStorage.setItem('theme', 'dark'); window.layout_change('dark'); }
  if (mode === 'light') { localStorage.setItem('theme', 'light'); window.layout_change('light'); }
  if (mode === 'default') { localStorage.removeItem('theme'); window.layout_change_default(); }
};
</script>