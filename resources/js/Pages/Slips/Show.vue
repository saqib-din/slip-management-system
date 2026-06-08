<template>

    <!-- BREADCRUMB -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link href="/dashboard">Home</Link>
                        </li>
                        <li class="breadcrumb-item">
                            <Link href="/slips">Slips</Link>
                        </li>
                        <li class="breadcrumb-item">{{ slip.slip_no }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Delivery Note</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <!-- ACTION BUTTONS -->
            <div class="action-bar">
                <button class="btn-print" @click="printSlip('full')">
                    🖨 Print (Full Design)
                </button>
                <button class="btn-print-data" @click="printSlip('data')">
                    🖨 Print on Pre-Printed Form (Dot Matrix)
                </button>
                <a class="btn-pdf" :href="`/slips/${slip.id}/pdf`">
                    ⬇ Download PDF
                </a>
                <Link class="btn-back" href="/slips">
                    ↩ Back
                </Link>
            </div>

            <!-- ========================================================= -->
            <!--  SCREEN / PDF VIEW  (poora design)                        -->
            <!-- ========================================================= -->
            <div class="paper-wrapper screen-view">

                <!-- LEFT HOLES -->
                <div class="holes-strip">
                    <div v-for="n in 9" :key="`l${n}`" class="hole"></div>
                </div>

                <!-- SLIP CONTENT -->
                <div class="slip">

                    <!-- HEADER -->
                    <div class="slip-header">
                        <div class="logo-area">
                            <img src="/assets/images/logo.png" alt="Logo" onerror="this.style.display='none'">
                            <div>
                                <div class="company-name-ar">اولد سيتي لغسل الرمال وتهيئتها ش.ذ.م.م</div>
                                <div class="company-name-en">OLD CITY SAND WASHING L.L.C</div>
                            </div>
                        </div>
                        <div class="contact-info">
                            P.O. Box : 73100<br>
                            Dubai – U.A.E<br>
                            Tel.: 04 283 2732<br>
                            sales@oldcity.ae<br>
                            www.oldcity.ae
                        </div>
                    </div>

                    <!-- DELIVERY NOTE BAR -->
                    <div class="delivery-bar">
                        <span>سند نـسليم &nbsp;&nbsp;&nbsp; DELIVERY NOTE</span>
                    </div>

                    <!-- INFO GRID -->
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-col">
                                <span class="info-label">Serial No</span>
                                <span class="info-colon">:</span>
                                <span class="info-value">{{ slip.slip_no }}</span>
                            </div>
                            <div class="info-col">
                                <span class="info-label">Date</span>
                                <span class="info-colon">:</span>
                                <span class="info-value">{{ slip.date }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-col">
                                <span class="info-label">Site No</span>
                                <span class="info-colon">:</span>
                                <span class="info-value">{{ slip.site_no || '—' }}</span>
                            </div>
                            <div class="info-col">
                                <span class="info-label">Time</span>
                                <span class="info-colon">:</span>
                                <span class="info-value">{{ slip.time || '—' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-col">
                                <span class="info-label">LPO No</span>
                                <span class="info-colon">:</span>
                                <span class="info-value">{{ slip.lpo_no || '—' }}</span>
                            </div>
                            <div class="info-col">
                                <span class="info-label">Vehicle No</span>
                                <span class="info-colon">:</span>
                                <span class="info-value">{{ slip.vehicle_no }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- COMPANY NAME -->
                    <div class="company-row">
                        <span class="info-label">Company Name:...</span>
                        <span class="company-dotted">{{ slip.company }}</span>
                    </div>

                    <!-- MATERIALS TABLE -->
                    <table class="materials-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th class="desc-th">Description</th>
                                <th style="width:70px;">M3</th>
                                <th style="width:80px;">TON</th>
                                <th style="width:80px;">TRIPS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, i) in displayRows" :key="i">
                                <td>{{ item ? i + 1 : '' }}</td>
                                <td class="desc-td">{{ item ? item.material : '' }}</td>
                                <td>{{ item ? item.m3 : '' }}</td>
                                <td>{{ item ? item.ton : '' }}</td>
                                <td>{{ item ? item.trips : '' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- TIP & CASH TRIP -->
                    <div class="totals-row">
                        <div>Tip : {{ slip.tip > 0 ? Math.round(slip.tip) : '—' }}</div>
                        <div>Cash Trip : {{ slip.cash_trip > 0 ? Math.round(slip.cash_trip) : '' }}</div>
                    </div>

                    <!-- NAMES -->
                    <div class="sig-row">
                        <div class="sig-col">
                            <span class="sig-label">Rec. Name</span>
                            <span class="sig-line">{{ slip.receiver_name }}</span>
                        </div>
                        <div class="sig-col" style="text-align:right;">
                            <span class="sig-label">Driver's Name</span>
                            <span class="sig-line">{{ slip.driver }}</span>
                        </div>
                    </div>

                    <!-- SIGNATURES -->
                    <div class="sig-row">
                        <div class="sig-col">
                            <span class="sig-label">Rec. Signature</span>
                            <span class="sig-line-blank">&nbsp;</span>
                        </div>
                        <div class="sig-col" style="text-align:right;">
                            <span class="sig-label">Driver's Signature</span>
                            <span class="sig-line-blank">&nbsp;</span>
                        </div>
                    </div>

                </div>

                <!-- RIGHT HOLES -->
                <div class="holes-strip">
                    <div v-for="n in 9" :key="`r${n}`" class="hole"></div>
                </div>

            </div>

            <!-- ========================================================= -->
            <!--  PRINT-ONLY DATA OVERLAY  (sirf backend values)           -->
            <!--  Ye sirf "Print on Pre-Printed Form" pe chhapega.         -->
            <!--  Pre-printed kaghaz pe values theek baithane ke liye      -->
            <!--  niche CSS me .print-overlay ke variables adjust karein.  -->
            <!-- ========================================================= -->
            <div class="print-overlay">
                <div class="po-page">

                    <!-- Row 1: Serial No (left)  |  Date (right) -->
                    <span class="po"
                        style="top: calc(var(--row1-top) + var(--shift-y)); left: calc(var(--col-left)  + var(--shift-x));">{{
                        slip.slip_no }}</span>
                    <span class="po"
                        style="top: calc(var(--row1-top) + var(--shift-y)); left: calc(var(--col-right) + var(--shift-x));">{{
                        slip.date }}</span>

                    <!-- Row 2: Site No (left)  |  Time (right) -->
                    <span class="po"
                        style="top: calc(var(--row2-top) + var(--shift-y)); left: calc(var(--col-left)  + var(--shift-x));">{{
                        slip.site_no }}</span>
                    <span class="po"
                        style="top: calc(var(--row2-top) + var(--shift-y)); left: calc(var(--col-right) + var(--shift-x));">{{
                        slip.time }}</span>

                    <!-- Row 3: LPO No (left)  |  Vehicle No (right) -->
                    <span class="po"
                        style="top: calc(var(--row3-top) + var(--shift-y)); left: calc(var(--col-left)  + var(--shift-x));">{{
                        slip.lpo_no }}</span>
                    <span class="po"
                        style="top: calc(var(--row3-top) + var(--shift-y)); left: calc(var(--col-right) + var(--shift-x));">{{
                        slip.vehicle_no }}</span>

                    <!-- Company Name -->
                    <span class="po"
                        style="top: calc(var(--company-top) + var(--shift-y)); left: calc(var(--company-left) + var(--shift-x));">{{
                        slip.company }}</span>

                    <!-- Materials table rows -->
                    <template v-for="(item, i) in items" :key="`po${i}`">
                        <span class="po po-row"
                            :style="`top: ${rowTop(i)}; left: calc(var(--col-no)    + var(--shift-x));`">{{ i + 1
                            }}</span>
                        <span class="po po-row"
                            :style="`top: ${rowTop(i)}; left: calc(var(--col-desc)  + var(--shift-x));`">{{
                            item.material }}</span>
                        <span class="po po-row"
                            :style="`top: ${rowTop(i)}; left: calc(var(--col-m3)    + var(--shift-x));`">{{ item.m3
                            }}</span>
                        <span class="po po-row"
                            :style="`top: ${rowTop(i)}; left: calc(var(--col-ton)   + var(--shift-x));`">{{ item.ton
                            }}</span>
                        <span class="po po-row"
                            :style="`top: ${rowTop(i)}; left: calc(var(--col-trips) + var(--shift-x));`">{{ item.trips
                            }}</span>
                    </template>

                    <!-- Tip (left)  |  Refund (right) -->
                    <span class="po po-name"
                        style="top: calc(var(--totals-top) + var(--shift-y)); left: calc(var(--tip-left)    + var(--shift-x));">Tip
                        : {{
                            slip.tip > 0 ? slip.tip : '' }}</span>
                    <span class="po po-name"
                        style="top: calc(var(--totals-top) + var(--shift-y)); left: calc(var(--refund-left) + var(--shift-x));">Refund
                        : {{
                        slip.refund || '' }}</span>

                    <!-- Names: Rec. Name (left)  |  Driver's Name (right) -->
                    <span class="po po-name"
                        style="top: calc(var(--names-top) + var(--shift-y)); left: calc(var(--recname-left) + var(--shift-x));">{{
                        slip.receiver_name }}</span>
                    <span class="po po-name"
                        style="top: calc(var(--names-top) + var(--shift-y)); left: calc(var(--drvname-left) + var(--shift-x));">{{
                        slip.driver }}</span>

                </div>
            </div>

        </div>
    </div>

</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    slip: { type: Object, required: true },
});

// Screen materials table — kam se kam 4 rows (jaise Blade me max(4, count)).
const items = computed(() => Array.isArray(props.slip.items) ? props.slip.items : []);
const displayRows = computed(() => {
    const rows = [...items.value];
    while (rows.length < 4) rows.push(null);
    return rows;
});

// Print-overlay me har row ka top calc.
function rowTop(i) {
    return `calc(var(--rows-top) + ${i} * var(--row-height) + var(--shift-y))`;
}

// Print mode select karta hai: 'full' (poora design) ya 'data' (sirf values)
function printSlip(mode) {
    document.body.setAttribute('data-print-mode', mode);
    window.print();
}
function clearPrintMode() {
    document.body.removeAttribute('data-print-mode');
}
onMounted(() => window.addEventListener('afterprint', clearPrintMode));
onUnmounted(() => {
    window.removeEventListener('afterprint', clearPrintMode);
    document.body.removeAttribute('data-print-mode');
});
</script>

<!-- NOTE: ye style block jaan boojh kar "scoped" NAHI hai, kyunki print rules
     AppLayout ke .pc-sidebar / .pc-header / body[data-print-mode] ko target karte hain. -->
<style>
/* ── ACTION BAR ── */
.action-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.btn-print,
.btn-print-data,
.btn-pdf,
.btn-back {
    color: #fff;
    border: none;
    padding: 9px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.btn-print {
    background: #0d6efd;
}

.btn-print-data {
    background: #fd7e14;
}

.btn-pdf {
    background: #198754;
}

.btn-back {
    background: #6c757d;
}

/* ── PAPER WRAPPER ── */
.paper-wrapper {
    display: flex;
    align-items: stretch;
    max-width: 860px;
    margin: 0 auto;
    background: #d0d0d0;
    padding: 10px;
    border-radius: 4px;
}

/* ── HOLES ── */
.holes-strip {
    width: 38px;
    background: #e8e8e8;
    border: 1px solid #bbb;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-around;
    padding: 10px 0;
    flex-shrink: 0;
}

.hole {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #e075a0;
    background: #d0d0d0;
}

/* ── SLIP ── */
.slip {
    flex: 1;
    background: #fff;
    border: 1px solid #bbb;
    padding: 18px 22px;
    font-family: Arial, sans-serif;
    font-size: 13px;
    color: #111;
}

.slip-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.logo-area {
    display: flex;
    align-items: center;
    gap: 45px;
}

.logo-area img {
    height: 80px;
    width: auto;
}

.company-name-ar {
    font-size: 27px;
    font-weight: bold;
    color: #222;
    line-height: 1.4;
}

.company-name-en {
    font-size: 23px;
    font-weight: 700;
    color: #e8a020;
    line-height: 1.4;
}

.contact-info {
    font-weight: bold;
    text-align: right;
    font-size: 12.5px;
    color: #333;
}

.delivery-bar {
    background: #111;
    color: #fff;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    padding: 7px 0;
    letter-spacing: 1px;
    margin-bottom: 14px;
}

.info-grid {
    margin-bottom: 12px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.info-col {
    display: flex;
    align-items: baseline;
    gap: 4px;
    flex: 1;
}

.info-col:last-child {
    justify-content: flex-end;
}

.info-label {
    font-weight: bold;
    white-space: nowrap;
    font-size: 13px;
}

.info-colon {
    font-weight: bold;
}

.info-value {
    font-size: 13px;
}

.company-row {
    display: flex;
    align-items: baseline;
    gap: 4px;
    margin-bottom: 12px;
}

.company-row .info-label {
    white-space: nowrap;
}

.company-dotted {
    flex: 1;
    border-bottom: 2px dotted #444;
    font-weight: bold;
    font-size: 15px;
    padding: 0 6px 2px 6px;
    min-width: 0;
}

.materials-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
}

.materials-table th {
    background: #fff;
    border: 1px solid #555;
    text-align: center;
    padding: 6px 8px;
    font-size: 13px;
    font-weight: bold;
}

.materials-table td {
    border: 1px solid #555;
    padding: 7px 8px;
    font-size: 13px;
    text-align: center;
    height: 32px;
}

.materials-table td.desc-td {
    text-align: left;
    font-weight: bold;
}

.totals-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
    font-size: 14px;
    font-weight: bold;
}

.sig-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
}

.sig-col {
    flex: 1;
}

.sig-col:last-child {
    text-align: right;
}

.sig-label {
    font-size: 13px;
    font-weight: bold;
}

.sig-line {
    display: inline-block;
    border-bottom: 1px solid #444;
    width: 180px;
    margin-left: 6px;
    font-weight: bold;
    vertical-align: bottom;
    padding-bottom: 1px;
    font-size: 13px;
}

.sig-line-blank {
    display: inline-block;
    border-bottom: 1px solid #444;
    width: 180px;
    margin-left: 6px;
}

/* ========================================================= */
/*  PRINT-ONLY DATA OVERLAY                                   */
/*  Screen pe hidden. Sirf "data" print mode me dikhega.     */
/* ========================================================= */
.print-overlay {
    display: none;
}

.print-overlay .po-page {
    position: relative;
    width: 210mm;
    height: 200mm;
    overflow: hidden;
    font-family: Arial, sans-serif;
    font-weight: bold;
    color: #000;

    /* === CALIBRATION VARIABLES (mm) === */
    --shift-x: 0mm;
    /* + = right,  - = left  */
    --shift-y: 0mm;
    /* + = neeche, - = upar  */

    --col-left: 39.6mm;
    /* serial / site / lpo */
    --col-right: 180.2mm;
    /* date / time / vehicle */

    --row1-top: 32.9mm;
    --row2-top: 39.8mm;
    --row3-top: 46.6mm;

    --company-top: 52.8mm;
    --company-left: 50.3mm;

    --rows-top: 70.8mm;
    --row-height: 9.7mm;
    --col-no: 17.6mm;
    --col-desc: 29.7mm;
    --col-m3: 157.1mm;
    --col-ton: 173.2mm;
    --col-trips: 191.5mm;

    --totals-top: 103.5mm;
    --tip-left: 27.9mm;
    --refund-left: 182.6mm;

    --names-top: 112.8mm;
    --recname-left: 43.5mm;
    --drvname-left: 157.8mm;
}

.print-overlay .po {
    position: absolute;
    font-size: 11pt;
    line-height: 1;
    white-space: nowrap;
    text-align: left;
}

.print-overlay .po-row {
    font-size: 10.5pt;
}

.print-overlay .po-name {
    font-size: 9.5pt;
}

/* ========================================================= */
/*  PRINT RULES                                              */
/* ========================================================= */

/* MODE: full design */
@media print {

    .pc-sidebar,
    .pc-header,
    .page-header,
    .action-bar {
        display: none !important;
    }

    .paper-wrapper {
        background: #fff !important;
        padding: 0 !important;
        max-width: 100%;
    }

    .holes-strip {
        display: none;
    }

    .slip {
        border: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 0 !important;
    }

    .pc-container,
    .pc-content {
        padding: 0 !important;
        margin: 0 !important;
    }
}

/* MODE: data-only (pre-printed form / dot matrix) */
@media print {
    body[data-print-mode="data"] .screen-view {
        display: none !important;
    }

    body[data-print-mode="data"] .print-overlay {
        display: block !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 210mm;
        margin: 0;
        padding: 0;
    }

    body[data-print-mode="data"],
    html:has(body[data-print-mode="data"]) {
        margin: 0 !important;
        padding: 0 !important;
    }
}

@media print {
    @page {
        size: A4 portrait;
        margin: 0;
    }
}
</style>