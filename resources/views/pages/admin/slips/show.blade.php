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
                        <button class="btn-print" onclick="window.print()">
                            🖨 Print
                        </button>
                        <a class="btn-pdf" href="{{ route('slips.pdf', $slip->id) }}">
                            ⬇ Download PDF
                        </a>
                        <a class="btn-back" href="{{ route('slips.index') }}">
                            ↩ Back
                        </a>
                    </div>

                    {{-- PAPER --}}
                    <div class="paper-wrapper">

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

                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* ── ACTION BAR ── */
        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }

        .btn-print {
            background: #0d6efd;
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
        }

        .btn-pdf {
            background: #198754;
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

        .btn-back {
            background: #6c757d;
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
            /* direction: rtl; */
            color: #222;
            line-height: 1.4;
            /* display:content; */
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
            /* line-height: 1.7; */
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

        /* ── PRINT ── */
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
    </style>
@endpush
