<template>

    <!-- Header -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link href="/dashboard">Home</Link>
                        </li>
                        <li class="breadcrumb-item">Slips</li>
                        <li class="breadcrumb-item">List</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Slip Management</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div style="position:fixed;top:20px;right:20px;z-index:10001;min-width:260px;">
        <div v-for="t in toasts" :key="t.id"
            :style="`background:${toastColor(t.type)};color:#fff;padding:10px 18px;border-radius:8px;margin-bottom:8px;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);`">
            {{ t.message }}
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div v-if="deleteTarget" @click.self="deleteTarget = null"
        style="position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;">
        <div
            style="background:#fff;border-radius:14px;padding:32px 28px 24px;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.2);text-align:center;">
            <div
                style="width:56px;height:56px;border-radius:50%;background:#fce8e6;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="ti ti-trash" style="font-size:26px;color:#d93025;"></i>
            </div>
            <h5 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#1a1a1a;">Delete Slip?</h5>
            <p style="margin:0 0 24px;font-size:14px;color:#666;">
                Slip "{{ deleteTarget.slip_no }}" — This record will be permanently deleted.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;">
                <button @click="deleteTarget = null"
                    style="flex:1;padding:10px;border-radius:8px;border:1px solid #ddd;background:#fff;font-size:14px;font-weight:500;cursor:pointer;color:#333;">Cancel</button>
                <button @click="confirmDelete"
                    style="flex:1;padding:10px;border-radius:8px;border:none;background:#d93025;color:#fff;font-size:14px;font-weight:500;cursor:pointer;">Delete</button>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Slip Records</h5>
                <button class="btn btn-outline-primary btn-sm" @click="showFilters = !showFilters">
                    <i class="ti ti-filter me-1"></i>
                    <span>{{ showFilters ? 'Hide Filters' : 'Show Filters' }}</span>
                </button>
            </div>
        </div>
        <div class="card-body" v-show="showFilters">
            <div class="row align-items-end g-2 mb-3">
                <div class="col-md-4">
                    <label>Date Filter</label>
                    <input type="date" v-model="filters.filter_date" class="form-control" @change="reload">
                </div>
                <div class="col-md-4">
                    <label>Serial Number</label>
                    <input type="text" v-model="filters.filter_slip_no" class="form-control"
                        placeholder="e.g., OLD CITY-1" @keyup.enter="reload">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100 rounded-2" style="padding:0.8em;"
                        @click="reload">
                        <i class="ti ti-search me-1"></i>Apply
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary w-100 rounded-2" style="padding:0.8em;"
                        @click="clearFilters">
                        <i class="ti ti-x me-1"></i>Clear
                    </button>
                </div>
            </div>
            <hr>
            <!-- Export: plain form = file download -->
            <form method="GET" action="/slips/export">
                <div class="row align-items-end g-2 mt-2"
                    style="padding:14px;border-radius:8px;border:1px solid #b8eecb;">
                    <div class="col-12"><strong>Download Excel Report</strong></div>
                    <div class="col-md-4">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100 rounded-2" style="padding:0.8em;">
                            <i class="ti ti-download me-1"></i>Download Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Slips Table -->
    <div class="card m-0">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Slips</h5>
                <div class="d-flex gap-2 align-items-center">
                    <select v-model="filters.per_page" class="form-select form-select-sm" style="width:90px;"
                        @change="reload">
                        <option :value="10">10</option>
                        <option :value="25">25</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                    </select>
                    <input type="text" v-model="filters.search" class="form-control form-control-sm"
                        style="width:200px;" placeholder="Search...">
                    <button class="btn btn-primary d-flex" @click="$refs.formModal.openAdd()">
                        <i class="ti ti-plus"></i> Add Slip
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th role="button" @click="sortBy('id')">Slip No
                            <SortIcon col="id" :s="filters" />
                        </th>
                        <th role="button" @click="sortBy('date')">Date
                            <SortIcon col="date" :s="filters" />
                        </th>
                        <th role="button" @click="sortBy('company')">Company
                            <SortIcon col="company" :s="filters" />
                        </th>
                        <th role="button" @click="sortBy('site_no')">Site No
                            <SortIcon col="site_no" :s="filters" />
                        </th>
                        <th role="button" @click="sortBy('vehicle_no')">Vehicle
                            <SortIcon col="vehicle_no" :s="filters" />
                        </th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in slips.data" :key="row.id">
                        <td>{{ row.slip_no }}</td>
                        <td>{{ row.date }}</td>
                        <td>{{ row.company }}</td>
                        <td>{{ row.site_no }}</td>
                        <td>{{ row.vehicle_no }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-1">
                                <Link :href="`/slips/${row.id}`" class="avtar avtar-xs btn-link-secondary" title="View">
                                    <i class="ti ti-eye f-18"></i>
                                </Link>
                                <button type="button" class="avtar avtar-xs btn-link-secondary" title="Edit"
                                    @click="$refs.formModal.openEdit(row)">
                                    <i class="ti ti-edit f-18"></i>
                                </button>
                                <button type="button" class="avtar avtar-xs btn-link-secondary" title="Delete"
                                    @click="deleteTarget = row">
                                    <i class="ti ti-trash f-18"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!slips.data.length">
                        <td colspan="6" class="text-center text-muted py-4">No slips found.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Footer: count + pagination -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                <small class="text-muted">
                    Showing {{ slips.from || 0 }} to {{ slips.to || 0 }} of {{ slips.total }} entries
                </small>
                <nav v-if="slips.last_page > 1">
                    <ul class="pagination mb-0">
                        <li v-for="(link, i) in slips.links" :key="i" class="page-item"
                            :class="{ active: link.active, disabled: !link.url }">
                            <a class="page-link" href="#" @click.prevent="link.url && go(link.url)"
                                v-html="link.label"></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Add / Edit modal -->
    <SlipFormModal ref="formModal" modal-id="slipFormModal" :companies="companies" :materials="materials" />
</template>

<script setup>
import { ref, reactive, watch, h } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SlipFormModal from '@/Pages/Slips/SlipFormModal.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    slips: { type: Object, required: true },
    companies: { type: Array, default: () => [] },
    materials: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

// Small inline sort indicator
const SortIcon = (p) => {
    if (p.s.sort !== p.col) return h('i', { class: 'ti ti-arrows-sort text-muted', style: 'font-size:13px;' });
    return h('i', { class: p.s.dir === 'asc' ? 'ti ti-sort-ascending' : 'ti ti-sort-descending', style: 'font-size:13px;' });
};

const showFilters = ref(false);
const deleteTarget = ref(null);
const filters = reactive({
    filter_date: props.filters.filter_date ?? '',
    filter_slip_no: props.filters.filter_slip_no ?? '',
    search: props.filters.search ?? '',
    sort: props.filters.sort ?? 'id',
    dir: props.filters.dir ?? 'desc',
    per_page: props.filters.per_page ?? 25,
});

const page = usePage();

function reload() {
    router.get('/slips', {
        filter_date: filters.filter_date || undefined,
        filter_slip_no: filters.filter_slip_no || undefined,
        search: filters.search || undefined,
        sort: filters.sort,
        dir: filters.dir,
        per_page: filters.per_page,
    }, { preserveState: true, preserveScroll: true, replace: true });
}

// Live search — debounced so we don't hit the server on every keystroke
let searchTimer = null;
watch(() => filters.search, () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(reload, 350);
});

function clearFilters() {
    filters.filter_date = '';
    filters.filter_slip_no = '';
    filters.search = '';
    reload();
}

function sortBy(col) {
    if (filters.sort === col) {
        filters.dir = filters.dir === 'asc' ? 'desc' : 'asc';
    } else {
        filters.sort = col;
        filters.dir = 'asc';
    }
    reload();
}

function go(url) {
    router.get(url, {}, { preserveState: true, preserveScroll: true });
}

function confirmDelete() {
    const target = deleteTarget.value;
    router.delete(`/slips/${target.id}`, {
        preserveScroll: true,
        onSuccess: () => { deleteTarget.value = null; },
    });
}

// ── Toast ──
let toastId = 0;
const toasts = ref([]);
function toastColor(type) {
    return { success: '#198754', danger: '#dc3545', warning: '#e6a817' }[type] || '#198754';
}
function showToast(message, type = 'success') {
    const id = ++toastId;
    toasts.value.push({ id, message, type });
    setTimeout(() => { toasts.value = toasts.value.filter(t => t.id !== id); }, 2800);
}

// Show toast whenever a flash message arrives
watch(() => page.props.flash, (flash) => {
    if (flash?.success) showToast(flash.success, 'success');
    if (flash?.error) showToast(flash.error, 'danger');
}, { deep: true });
</script>