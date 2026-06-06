@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            {{-- BREADCRUMB --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('slips.index') }}">Slips</a></li>
                                <li class="breadcrumb-item">{{ $slip->slip_no }}</li>
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

                    {{-- ACTION BUTTONS --}}
                    <div class="action-bar">
                        <button class="btn-print" onclick="printSlip('full')">
                            🖨 Print (Full Design)
                        </button>
                        <button class="btn-print-data" onclick="printSlip('data')">
                            🖨 Print on Pre-Printed Form (Dot Matrix)
                        </button>
                        <a class="btn-pdf" href="{{ route('slips.pdf', $slip->id) }}">
                            ⬇ Download PDF
                        </a>
                        <a class="btn-back" href="{{ route('slips.index') }}">
                            ↩ Back
                        </a>
                    </div>

                    {{-- ========================================================= --}}
                    {{--  SCREEN / PDF VIEW  (poora design, jaisa pehle tha)       --}}
                    {{-- ========================================================= --}}
                    <div class="paper-wrapper screen-view">

                        {{-- LEFT HOLES --}}
                        <div class="holes-strip">
                            @for ($i = 0; $i < 9; $i++)
                                <div class="hole"></div>
                            @endfor
                        </div>

                        {{-- SLIP CONTENT --}}
                        <div class="slip">

                            {{-- HEADER --}}
                            <div class="slip-header">

                                <div class="logo-area">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo"
                                        onerror="this.style.display='none'">
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

                            {{-- DELIVERY NOTE BAR --}}
                            <div class="delivery-bar">
                                <span>سند نـسليم &nbsp;&nbsp;&nbsp; DELIVERY NOTE</span>
                            </div>

                            {{-- INFO GRID --}}
                            <div class="info-grid">

                                <div class="info-row">
                                    <div class="info-col">
                                        <span class="info-label">Serial No</span>
                                        <span class="info-colon">:</span>
                                        <span class="info-value">{{ $slip->slip_no }}</span>
                                    </div>
                                    <div class="info-col">
                                        <span class="info-label">Date</span>
                                        <span class="info-colon">:</span>
                                        <span class="info-value">{{ optional($slip->date)->format('Y-m-d') }}</span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-col">
                                        <span class="info-label">Site No</span>
                                        <span class="info-colon">:</span>
                                        <span class="info-value">{{ $slip->site_no ?? '—' }}</span>
                                    </div>
                                    <div class="info-col">
                                        <span class="info-label">Time</span>
                                        <span class="info-colon">:</span>
                                        <span class="info-value">{{ $slip->time ?? '—' }}</span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-col">
                                        <span class="info-label">LPO No</span>
                                        <span class="info-colon">:</span>
                                        <span class="info-value">{{ $slip->lpo_no ?? '—' }}</span>
                                    </div>
                                    <div class="info-col">
                                        <span class="info-label">Vehicle No</span>
                                        <span class="info-colon">:</span>
                                        <span class="info-value">{{ $slip->vehicle_no }}</span>
                                    </div>
                                </div>

                            </div>

                            {{-- COMPANY NAME --}}
                            <div class="company-row">
                                <span class="info-label">Company Name:...</span>
                                <span class="company-dotted">{{ $slip->company }}</span>
                            </div>

                            {{-- MATERIALS TABLE --}}
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
                                    @php
                                        $items = $slip->items ?? [];
                                        $maxRows = max(4, count($items));
                                    @endphp

                                    @for ($i = 0; $i < $maxRows; $i++)
                                        @php $item = $items[$i] ?? null; @endphp
                                        <tr>
                                            <td>{{ $item ? $i + 1 : '' }}</td>
                                            <td class="desc-td">{{ $item['material'] ?? '' }}</td>
                                            <td>{{ $item['m3'] ?? '' }}</td>
                                            <td>{{ $item['ton'] ?? '' }}</td>
                                            <td>{{ $item['trips'] ?? '' }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>

                            {{-- TIP & CASH TRIP --}}
                            <div class="totals-row">
                                <div>Tip : {{ $slip->tip > 0 ? number_format($slip->tip, 0) : '—' }}</div>
                                <div>Cash Trip : {{ $slip->cash_trip > 0 ? number_format($slip->cash_trip, 0) : '' }}</div>
                            </div>

                            {{-- NAMES --}}
                            <div class="sig-row">
                                <div class="sig-col">
                                    <span class="sig-label">Rec. Name</span>
                                    <span class="sig-line">{{ $slip->receiver_name }}</span>
                                </div>
                                <div class="sig-col" style="text-align:right;">
                                    <span class="sig-label">Driver's Name</span>
                                    <span class="sig-line">{{ $slip->driver }}</span>
                                </div>
                            </div>

                            {{-- SIGNATURES --}}
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

                        {{-- RIGHT HOLES --}}
                        <div class="holes-strip">
                            @for ($i = 0; $i < 9; $i++)
                                <div class="hole"></div>
                            @endfor
                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{--  PRINT-ONLY DATA OVERLAY  (sirf backend values)           --}}
                    {{--  Ye sirf "Print on Pre-Printed Form" pe chhapega.         --}}
                    {{--  Pre-printed kaghaz pe values theek baithane ke liye      --}}
                    {{--  niche CSS me .print-overlay ke variables adjust karein.  --}}
                    {{-- ========================================================= --}}
                    <div class="print-overlay">
                        <div class="po-page">

                            {{-- Row 1: Serial No (left)  |  Date (right) --}}
                            <span class="po"
                                style="top: calc(var(--row1-top) + var(--shift-y)); left: calc(var(--col-left)  + var(--shift-x));">{{ $slip->slip_no }}</span>
                            <span class="po"
                                style="top: calc(var(--row1-top) + var(--shift-y)); left: calc(var(--col-right) + var(--shift-x));">{{ optional($slip->date)->format('Y-m-d') }}</span>

                            {{-- Row 2: Site No (left)  |  Time (right) --}}
                            <span class="po"
                                style="top: calc(var(--row2-top) + var(--shift-y)); left: calc(var(--col-left)  + var(--shift-x));">{{ $slip->site_no }}</span>
                            <span class="po"
                                style="top: calc(var(--row2-top) + var(--shift-y)); left: calc(var(--col-right) + var(--shift-x));">{{ $slip->time }}</span>

                            {{-- Row 3: LPO No (left)  |  Vehicle No (right) --}}
                            <span class="po"
                                style="top: calc(var(--row3-top) + var(--shift-y)); left: calc(var(--col-left)  + var(--shift-x));">{{ $slip->lpo_no }}</span>
                            <span class="po"
                                style="top: calc(var(--row3-top) + var(--shift-y)); left: calc(var(--col-right) + var(--shift-x));">{{ $slip->vehicle_no }}</span>

                            {{-- Company Name --}}
                            <span class="po"
                                style="top: calc(var(--company-top) + var(--shift-y)); left: calc(var(--company-left) + var(--shift-x));">{{ $slip->company }}</span>

                            {{-- Materials table rows --}}
                            @php $items = $slip->items ?? []; @endphp
                            @foreach ($items as $i => $item)
                                @php $rowTop = "calc(var(--rows-top) + {$i} * var(--row-height) + var(--shift-y))"; @endphp
                                <span class="po po-row"
                                    style="top: {{ $rowTop }}; left: calc(var(--col-no)    + var(--shift-x));">{{ $i + 1 }}</span>
                                <span class="po po-row"
                                    style="top: {{ $rowTop }}; left: calc(var(--col-desc)  + var(--shift-x));">{{ $item['material'] ?? '' }}</span>
                                <span class="po po-row"
                                    style="top: {{ $rowTop }}; left: calc(var(--col-m3)    + var(--shift-x));">{{ $item['m3'] ?? '' }}</span>
                                <span class="po po-row"
                                    style="top: {{ $rowTop }}; left: calc(var(--col-ton)   + var(--shift-x));">{{ $item['ton'] ?? '' }}</span>
                                <span class="po po-row"
                                    style="top: {{ $rowTop }}; left: calc(var(--col-trips) + var(--shift-x));">{{ $item['trips'] ?? '' }}</span>
                            @endforeach

                            {{-- Tip (left)  |  Refund (right)  — labels samet, jaise PDF me hain --}}
                            <span class="po po-name"
                                style="top: calc(var(--totals-top) + var(--shift-y)); left: calc(var(--tip-left)    + var(--shift-x));">Tip
                                : {{ $slip->tip > 0 ? $slip->tip : '' }}</span>
                            <span class="po po-name"
                                style="top: calc(var(--totals-top) + var(--shift-y)); left: calc(var(--refund-left) + var(--shift-x));">Refund
                                : {{ $slip->refund ?? '' }}</span>

                            {{-- Names: Rec. Name (left)  |  Driver's Name (right) --}}
                            <span class="po po-name"
                                style="top: calc(var(--names-top) + var(--shift-y)); left: calc(var(--recname-left) + var(--shift-x));">{{ $slip->receiver_name }}</span>
                            <span class="po po-name"
                                style="top: calc(var(--names-top) + var(--shift-y)); left: calc(var(--drvname-left) + var(--shift-x));">{{ $slip->driver }}</span>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        // print mode select karta hai: 'full' (poora design) ya 'data' (sirf values)
        function printSlip(mode) {
            document.body.setAttribute('data-print-mode', mode);
            window.print();
        }
        // print ke baad attribute hata dein
        window.addEventListener('afterprint', function() {
            document.body.removeAttribute('data-print-mode');
        });
    </script>
@endsection

@push('styles')
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
            /* === FORM KA SIZE === A4 portrait width. Height jaan boojh kar
                   kam rakhi hai (content sirf upar wale hisse me hai) taake
                   print 1 page se aage NA jaaye, chahe margins "Default" hon. */
            width: 210mm;
            height: 200mm;
            overflow: hidden;
            font-family: Arial, sans-serif;
            font-weight: bold;
            color: #000;

            /* ===========================================================
                   CALIBRATION VARIABLES (mm me)
                   -----------------------------------------------------------
                   Ye coordinates aap ke "Old City Sand Washing.pdf" se
                   exact nikale gaye hain, to print hubahu PDF jaisa aayega.
                   Agar dot-matrix pe halka sa upar/neeche ya left/right ho:
                     - SAARI values ek saath: sirf --shift-x / --shift-y badlein
                     - Single field: us ka apna variable badlein
                   =========================================================== */

            /* Poori layer ko ek saath move karne ke liye: */
            --shift-x: 0mm;
            /* + = right,  - = left  */
            --shift-y: 0mm;
            /* + = neeche, - = upar  */

            /* Left aur right columns (header info) */
            --col-left: 39.6mm;
            /* serial / site / lpo */
            --col-right: 180.2mm;
            /* date / time / vehicle */

            /* Top info rows */
            --row1-top: 32.9mm;
            /* Serial No  |  Date */
            --row2-top: 39.8mm;
            /* Site No    |  Time */
            --row3-top: 46.6mm;
            /* LPO No     |  Vehicle No */

            /* Company name line */
            --company-top: 52.8mm;
            --company-left: 50.3mm;

            /* Materials table */
            --rows-top: 70.8mm;
            /* pehli row ki height */
            --row-height: 9.7mm;
            /* har row ke darmiyan gap (zaroorat ho to badlein) */
            --col-no: 17.6mm;
            --col-desc: 29.7mm;
            --col-m3: 157.1mm;
            --col-ton: 173.2mm;
            --col-trips: 191.5mm;

            /* Tip (left)  |  Refund (right) */
            --totals-top: 103.5mm;
            --tip-left: 27.9mm;
            --refund-left: 182.6mm;

            /* Names (Rec. Name  |  Driver's Name) */
            --names-top: 112.8mm;
            --recname-left: 43.5mm;
            --drvname-left: 157.8mm;
        }

        .print-overlay .po {
            position: absolute;
            font-size: 11pt;
            /* header info text */
            line-height: 1;
            white-space: nowrap;
            text-align: left;
        }

        .print-overlay .po-row {
            font-size: 10.5pt;
        }

        /* table rows */
        .print-overlay .po-name {
            font-size: 9.5pt;
        }

        /* names */

        /* ========================================================= */
        /*  PRINT RULES                                              */
        /* ========================================================= */

        /* MODE: full design (default print + "Print Full Design" button) */
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

            /* position: FIXED => page ke bilkul top-left (0,0) se anchor,
                   kisi parent (card/layout) ka offset asar nahi karega, isliye
                   values exact wahi jagah aayengi jahan PDF me hain. */
            body[data-print-mode="data"] .print-overlay {
                display: block !important;
                position: fixed;
                top: 0;
                left: 0;
                width: 210mm;
                margin: 0;
                padding: 0;
            }

            /* page + body/html sab ka margin/padding 0 */
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
@endpush
