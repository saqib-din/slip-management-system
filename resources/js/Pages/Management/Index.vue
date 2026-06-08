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
                        <li class="breadcrumb-item" aria-current="page">Management</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Management</h2>
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
            <h5 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#1a1a1a;">Delete?</h5>
            <p style="margin:0 0 24px;font-size:14px;color:#666;">
                "{{ deleteTarget.name }}" — This record will be permanently deleted.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;">
                <button @click="deleteTarget = null"
                    style="flex:1;padding:10px;border-radius:8px;border:1px solid #ddd;background:#fff;font-size:14px;font-weight:500;cursor:pointer;color:#333;">Cancel</button>
                <button @click="confirmDelete"
                    style="flex:1;padding:10px;border-radius:8px;border:none;background:#d93025;color:#fff;font-size:14px;font-weight:500;cursor:pointer;">Delete</button>
            </div>
        </div>
    </div>

    <div class="row g-3">

        <!-- Companies -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Manage Companies</h5>
                </div>
                <div class="card-body d-flex flex-column gap-3">

                    <!-- Add -->
                    <div class="input-group">
                        <input type="text" v-model="newCompany" class="form-control" placeholder="New company name..."
                            @keydown.enter="addItem('company')" />
                        <button type="button" class="btn btn-success" @click="addItem('company')">
                            <i class="ti ti-plus"></i> Add
                        </button>
                    </div>

                    <!-- List -->
                    <div style="max-height:264px; overflow-y:auto;">
                        <div v-for="c in companies" :key="c.id" class="mgmt-row">
                            <span v-if="editId.company !== c.id" class="item-label">{{ c.name }}</span>
                            <input v-else type="text" v-model="editValue"
                                class="item-input form-control form-control-sm"
                                @keydown.enter="saveItem('company', c.id)" />
                            <div class="mgmt-actions">
                                <button v-if="editId.company !== c.id" type="button" class="btn-icon btn-icon-edit"
                                    @click="startEdit('company', c)" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button v-else type="button" class="btn-icon btn-icon-save"
                                    @click="saveItem('company', c.id)" title="Save">
                                    <i class="ti ti-check"></i>
                                </button>
                                <button type="button" class="btn-icon btn-icon-delete"
                                    @click="deleteTarget = { type: 'company', id: c.id, name: c.name }" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p v-if="!companies.length" class="text-muted text-center py-3 mb-0">No companies yet.</p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Materials -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Manage Materials / Descriptions</h5>
                </div>
                <div class="card-body d-flex flex-column gap-3">

                    <!-- Add -->
                    <div class="input-group">
                        <input type="text" v-model="newMaterial" class="form-control"
                            placeholder="New description name..." @keydown.enter="addItem('material')" />
                        <button type="button" class="btn btn-success" @click="addItem('material')">
                            <i class="ti ti-plus"></i> Add
                        </button>
                    </div>

                    <!-- List -->
                    <div style="max-height:264px; overflow-y:auto;">
                        <div v-for="m in materials" :key="m.id" class="mgmt-row">
                            <span v-if="editId.material !== m.id" class="item-label">{{ m.name }}</span>
                            <input v-else type="text" v-model="editValue"
                                class="item-input form-control form-control-sm"
                                @keydown.enter="saveItem('material', m.id)" />
                            <div class="mgmt-actions">
                                <button v-if="editId.material !== m.id" type="button" class="btn-icon btn-icon-edit"
                                    @click="startEdit('material', m)" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button v-else type="button" class="btn-icon btn-icon-save"
                                    @click="saveItem('material', m.id)" title="Save">
                                    <i class="ti ti-check"></i>
                                </button>
                                <button type="button" class="btn-icon btn-icon-delete"
                                    @click="deleteTarget = { type: 'material', id: m.id, name: m.name }" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p v-if="!materials.length" class="text-muted text-center py-3 mb-0">No materials yet.</p>
                    </div>

                </div>
            </div>
        </div>

    </div>

</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    companies: { type: Array, default: () => [] },
    materials: { type: Array, default: () => [] },
    totalUsers: { type: Number, default: 0 },
});

const newCompany = ref('');
const newMaterial = ref('');
const editId = reactive({ company: null, material: null });
const editValue = ref('');
const deleteTarget = ref(null);

const page = usePage();

const urls = {
    company: { store: '/management/companies', item: (id) => `/management/companies/${id}` },
    material: { store: '/management/materials', item: (id) => `/management/materials/${id}` },
};

// ── Add ──
function addItem(type) {
    const model = type === 'company' ? newCompany : newMaterial;
    const name = model.value.trim();
    if (!name) { showToast('Name cannot be empty.', 'warning'); return; }

    router.post(urls[type].store, { name }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => { model.value = ''; },
        onError: (e) => showToast(e.name || 'Could not add.', 'danger'),
    });
}

// ── Edit ──
function startEdit(type, item) {
    editId.company = null;
    editId.material = null;
    editId[type] = item.id;
    editValue.value = item.name;
}

function saveItem(type, id) {
    const name = editValue.value.trim();
    if (!name) { showToast('Name cannot be empty.', 'warning'); return; }

    router.put(urls[type].item(id), { name }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => { editId[type] = null; },
        onError: (e) => showToast(e.name || 'Could not update.', 'danger'),
    });
}

// ── Delete ──
function confirmDelete() {
    const t = deleteTarget.value;
    router.delete(urls[t.type].item(t.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => { deleteTarget.value = null; },
        onError: () => { deleteTarget.value = null; showToast('Could not delete.', 'danger'); },
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

<style scoped>
.mgmt-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 4px;
    border-bottom: 1px solid #f0f0f0;
}

.mgmt-row:last-child {
    border-bottom: none;
}

.mgmt-row:hover {
    background: #fafafa;
    border-radius: 6px;
    color: #333;
}

.item-label {
    flex: 1;
    font-size: 14px;
}

.item-input {
    flex: 1;
}

.mgmt-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    transition: background .15s, transform .1s;
}

.btn-icon:hover {
    transform: scale(1.1);
}

.btn-icon-edit {
    background: #e8f0fe;
    color: #1a73e8;
}

.btn-icon-edit:hover {
    background: #d2e3fc;
}

.btn-icon-save {
    background: #e6f4ea;
    color: #1e8e3e;
}

.btn-icon-save:hover {
    background: #ceead6;
}

.btn-icon-delete {
    background: #fce8e6;
    color: #d93025;
}

.btn-icon-delete:hover {
    background: #f5c6c3;
}
</style>