<template>
    <div class="modal fade" :id="modalId" tabindex="-1" ref="modalEl">
        <div class="modal-dialog modal-xl">
            <form @submit.prevent="submit">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ isEdit ? 'Edit Slip' : 'Add New Slip' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Date</label>
                                <input type="date" v-model="form.date" class="form-control" required>
                                <div v-if="form.errors.date" class="text-danger small mt-1">{{ form.errors.date }}</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Site No</label>
                                <input type="text" v-model="form.site_no" class="form-control"
                                    placeholder="e.g., JLT-BO1">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Time</label>
                                <input type="time" v-model="form.time" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>LPO No</label>
                                <input type="text" v-model="form.lpo_no" class="form-control"
                                    placeholder="e.g., LPO-12345">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Vehicle No</label>
                                <input type="text" v-model="form.vehicle_no" class="form-control" required
                                    placeholder="e.g., D 54321">
                                <div v-if="form.errors.vehicle_no" class="text-danger small mt-1">{{
                                    form.errors.vehicle_no }}</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Tip</label>
                                <input type="number" step="0.01" v-model="form.tip" class="form-control"
                                    placeholder="Enter tip">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Cash Trip</label>
                                <input type="number" step="0.01" v-model="form.cash_trip" class="form-control"
                                    placeholder="Enter cash trip">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Refund</label>
                                <input type="text" v-model="form.refund" class="form-control"
                                    placeholder="Enter credits">
                            </div>

                            <!-- Company -->
                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="companyCustom"
                                        @change="form.company = ''">
                                    <label class="form-check-label">Use custom company</label>
                                </div>
                                <select v-if="!companyCustom" v-model="form.company" class="form-control">
                                    <option value="">Select Company</option>
                                    <option v-for="c in companies" :key="c.id" :value="c.name">{{ c.name }}</option>
                                </select>
                                <input v-else type="text" v-model="form.company" class="form-control"
                                    placeholder="Enter custom company">
                                <div v-if="form.errors.company" class="text-danger small mt-1">{{ form.errors.company }}
                                </div>
                            </div>

                            <!-- Items -->
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold">Description</label>
                                <hr class="mt-1 mb-2">
                                <div class="row fw-semibold mb-1" style="font-size:13px;padding:0 6px;">
                                    <div class="col-md-5"></div>
                                    <div class="col-md-2">M3</div>
                                    <div class="col-md-2">TON</div>
                                    <div class="col-md-2">TRIPS</div>
                                    <div class="col-md-1"></div>
                                </div>

                                <div v-for="(item, idx) in form.items" :key="idx" class="item-row mb-3">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" v-model="item.custom"
                                            @change="item.material = ''">
                                        <label class="form-check-label" style="font-size:13px;">Use custom
                                            description</label>
                                    </div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-5">
                                            <select v-if="!item.custom" v-model="item.material" class="form-control">
                                                <option value="">Select a material...</option>
                                                <option v-for="m in materials" :key="m" :value="m">{{ m }}</option>
                                            </select>
                                            <input v-else type="text" v-model="item.material" class="form-control"
                                                placeholder="Enter custom description">
                                        </div>
                                        <div class="col-md-2"><input type="text" v-model="item.m3" class="form-control"
                                                placeholder="0"></div>
                                        <div class="col-md-2"><input type="text" v-model="item.ton" class="form-control"
                                                placeholder="0"></div>
                                        <div class="col-md-2"><input type="text" v-model="item.trips"
                                                class="form-control" placeholder="0"></div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <button type="button" class="btn btn-sm"
                                                style="color:#dc3545;background:none;border:none;padding:4px 8px;"
                                                @click="removeItem(idx)">
                                                <i class="ti ti-trash" style="font-size:20px;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-light-danger mt-2" @click="addItem">+ Add
                                    Item</button>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Receiver's Name</label>
                                <input type="text" v-model="form.receiver_name" class="form-control"
                                    placeholder="Receiver full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Driver's Name</label>
                                <input type="text" v-model="form.driver" class="form-control"
                                    placeholder="Driver full name">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" :disabled="form.processing">
                            {{ form.processing ? (isEdit ? 'Updating...' : 'Saving...') : (isEdit ? 'Update Slip' :
                            'Generate Slip') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    modalId: { type: String, required: true },
    companies: { type: Array, default: () => [] },
    materials: { type: Array, default: () => [] },
});

const isEdit = ref(false);
const editId = ref(null);
const companyCustom = ref(false);
const modalEl = ref(null);
let modal = null;

const form = useForm({
    date: '', site_no: '', time: '', lpo_no: '', vehicle_no: '',
    tip: '', cash_trip: '', refund: '', company: '',
    receiver_name: '', driver: '',
    items: [blankItem()],
});

function blankItem() {
    return { material: '', m3: '', ton: '', trips: '', custom: false };
}
function addItem() {
    form.items.push(blankItem());
}
function removeItem(idx) {
    if (form.items.length > 1) form.items.splice(idx, 1);
}

// Dubai date/time defaults for new slips
function dubaiNow() {
    const d = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Dubai' }));
    const p = (n) => String(n).padStart(2, '0');
    return {
        date: `${d.getFullYear()}-${p(d.getMonth() + 1)}-${p(d.getDate())}`,
        time: `${p(d.getHours())}:${p(d.getMinutes())}`,
    };
}

function openAdd() {
    isEdit.value = false;
    editId.value = null;
    form.reset();
    form.clearErrors();
    const now = dubaiNow();
    form.date = now.date;
    form.time = now.time;
    form.items = [blankItem()];
    companyCustom.value = false;
    modal.show();
}

function openEdit(slip) {
    isEdit.value = true;
    editId.value = slip.id;
    form.clearErrors();

    form.date = slip.date_raw || '';
    form.site_no = slip.site_no || '';
    form.time = slip.time || '';
    form.lpo_no = slip.lpo_no || '';
    form.vehicle_no = slip.vehicle_no || '';
    form.tip = slip.tip || '';
    form.cash_trip = slip.cash_trip || '';
    form.refund = slip.refund || '';
    form.company = slip.company || '';
    form.receiver_name = slip.receiver_name || '';
    form.driver = slip.driver || '';

    companyCustom.value = !!slip.company && !props.companies.some(c => c.name === slip.company);

    const items = Array.isArray(slip.items) ? slip.items : [];
    form.items = items.length
        ? items.map(i => ({
            material: i.material || '',
            m3: i.m3 || '',
            ton: i.ton || '',
            trips: i.trips || '',
            custom: !!i.material && !props.materials.includes(i.material),
        }))
        : [blankItem()];

    modal.show();
}

function submit() {
    const opts = {
        preserveScroll: true,
        onSuccess: () => modal.hide(),
    };
    if (isEdit.value) {
        form.put(`/slips/${editId.value}`, opts);
    } else {
        form.post('/slips', opts);
    }
}

onMounted(() => {
    modal = new window.bootstrap.Modal(modalEl.value);
});

defineExpose({ openAdd, openEdit });
</script>