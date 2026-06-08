<template>

    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link href="/dashboard">Home</Link>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Users</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Users Management</h2>
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

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h4 class="mb-1 text-primary">{{ users.length }}</h4>
                    <p class="mb-0 text-muted f-12">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h4 class="mb-1 text-danger">{{ countRole('admin') }}</h4>
                    <p class="mb-0 text-muted f-12">Admins</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h4 class="mb-1 text-warning">{{ countRole('manager') }}</h4>
                    <p class="mb-0 text-muted f-12">Managers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card text-center">
                <div class="card-body py-3">
                    <h4 class="mb-1 text-primary">{{ countRole('user') }}</h4>
                    <p class="mb-0 text-muted f-12">Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Users</h5>
                        <button v-if="isAdmin" class="btn btn-primary d-flex" @click="openCreate">
                            <i class="ti ti-user-plus me-1"></i> Add User
                        </button>
                    </div>
                </div>

                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(u, i) in users" :key="u.id"
                                    :style="u.is_me ? 'background:rgba(70,128,255,0.05);' : ''">
                                    <td>{{ i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avtar avtar-s me-3 rounded-circle d-flex align-items-center justify-content-center"
                                                :class="u.role_badge"
                                                style="width:40px;height:40px;font-weight:700;font-size:1rem;">
                                                {{ u.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">
                                                    {{ u.name }}
                                                    <span v-if="u.is_me" class="badge bg-light-info ms-1">You</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ u.email }}</td>
                                    <td>{{ u.phone || '—' }}</td>
                                    <td>
                                        <span class="badge" :class="u.role_badge">{{ u.role_label }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end align-items-center gap-1">

                                            <template v-if="isAdmin">
                                                <button v-if="!u.is_me" class="avtar avtar-xs"
                                                    :class="u.status === 'active' ? 'btn-link-warning' : 'btn-link-success'"
                                                    :title="u.status === 'active' ? 'Deactivate' : 'Activate'"
                                                    @click="toggleStatus(u)">
                                                    <i class="ti f-18"
                                                        :class="u.status === 'active' ? 'ti-user-off' : 'ti-user-check'"></i>
                                                </button>
                                                <button class="avtar avtar-xs btn-link-secondary" title="Edit"
                                                    @click="openEdit(u)">
                                                    <i class="ti ti-edit f-18"></i>
                                                </button>
                                                <button v-if="!u.is_me" class="avtar avtar-xs btn-link-secondary"
                                                    title="Delete" @click="openDelete(u)">
                                                    <i class="ti ti-trash f-18"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!users.length">
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ti ti-users f-40 d-block mb-2"></i>
                                        No users found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== CREATE MODAL ===================== -->
    <div class="modal fade" id="createModal" tabindex="-1" ref="createModalEl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-user-plus me-2"></i>Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" v-model="createForm.name" class="form-control"
                                :class="{ 'is-invalid': createForm.errors.name }" placeholder="Enter full name">
                            <div class="invalid-feedback">{{ createForm.errors.name }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" v-model="createForm.email" class="form-control"
                                :class="{ 'is-invalid': createForm.errors.email }" placeholder="user@example.com">
                            <div class="invalid-feedback">{{ createForm.errors.email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" v-model="createForm.phone" class="form-control"
                                placeholder="0300-1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select v-model="createForm.role" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" v-model="createForm.password" class="form-control"
                                :class="{ 'is-invalid': createForm.errors.password }" placeholder="Min 8 characters">
                            <div class="invalid-feedback">{{ createForm.errors.password }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" v-model="createForm.password_confirmation" class="form-control"
                                placeholder="Repeat password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" :disabled="createForm.processing"
                        @click="submitCreate">
                        <i class="ti ti-check me-1"></i> Create User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== EDIT MODAL ===================== -->
    <div class="modal fade" id="editModal" tabindex="-1" ref="editModalEl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" v-model="editForm.name" class="form-control"
                                :class="{ 'is-invalid': editForm.errors.name }">
                            <div class="invalid-feedback">{{ editForm.errors.name }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" v-model="editForm.email" class="form-control"
                                :class="{ 'is-invalid': editForm.errors.email }">
                            <div class="invalid-feedback">{{ editForm.errors.email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" v-model="editForm.phone" class="form-control" placeholder="0300-1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select v-model="editForm.role" class="form-select"
                                :class="{ 'is-invalid': editForm.errors.role }">
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="user">User</option>
                            </select>
                            <div class="invalid-feedback">{{ editForm.errors.role }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" v-model="editForm.password" class="form-control"
                                :class="{ 'is-invalid': editForm.errors.password }"
                                placeholder="Leave blank to keep current">
                            <div class="invalid-feedback">{{ editForm.errors.password }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" v-model="editForm.password_confirmation" class="form-control"
                                placeholder="Repeat new password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" :disabled="editForm.processing" @click="submitEdit">
                        <i class="ti ti-check me-1"></i> Update User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== DELETE CONFIRM MODAL ===================== -->
    <div class="modal fade" id="deleteModal" tabindex="-1" ref="deleteModalEl">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <span
                            class="avtar avtar-l bg-light-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:60px;height:60px;">
                            <i class="ti ti-trash f-28 text-danger"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete User?</h5>
                    <p class="text-muted mb-0">Are you sure you want to delete <strong>{{ deleteTarget?.name
                    }}</strong>?
                        This
                        action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center pt-0 border-0 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" @click="confirmDelete">
                        <i class="ti ti-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    users: { type: Array, default: () => [] },
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.isAdmin === true);

function countRole(role) {
    return props.users.filter(u => u.role === role).length;
}

// ── Bootstrap modal refs ──
const createModalEl = ref(null);
const editModalEl = ref(null);
const deleteModalEl = ref(null);
let createModal, editModal, deleteModal;

onMounted(() => {
    createModal = new window.bootstrap.Modal(createModalEl.value);
    editModal = new window.bootstrap.Modal(editModalEl.value);
    deleteModal = new window.bootstrap.Modal(deleteModalEl.value);
});

// ── Create ──
const createForm = useForm({
    name: '', email: '', phone: '', role: 'user', password: '', password_confirmation: '',
});

function openCreate() {
    createForm.reset();
    createForm.clearErrors();
    createModal.show();
}

function submitCreate() {
    createForm.post('/users', {
        preserveScroll: true,
        onSuccess: () => { createModal.hide(); createForm.reset(); },
    });
}

// ── Edit ──
const editId = ref(null);
const editForm = useForm({
    name: '', email: '', phone: '', role: 'user', password: '', password_confirmation: '',
});

function openEdit(u) {
    editId.value = u.id;
    editForm.clearErrors();
    editForm.name = u.name;
    editForm.email = u.email;
    editForm.phone = u.phone || '';
    editForm.role = u.role;
    editForm.password = '';
    editForm.password_confirmation = '';
    editModal.show();
}

function submitEdit() {
    editForm.put(`/users/${editId.value}`, {
        preserveScroll: true,
        onSuccess: () => editModal.hide(),
    });
}

// ── Toggle status ──
function toggleStatus(u) {
    router.post(`/users/${u.id}/toggle`, {}, { preserveScroll: true, preserveState: true });
}

// ── Delete ──
const deleteTarget = ref(null);

function openDelete(u) {
    deleteTarget.value = u;
    deleteModal.show();
}

function confirmDelete() {
    router.delete(`/users/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onFinish: () => { deleteModal.hide(); deleteTarget.value = null; },
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

watch(() => page.props.flash, (flash) => {
    if (flash?.success) showToast(flash.success, 'success');
    if (flash?.error) showToast(flash.error, 'danger');
}, { deep: true });
</script>