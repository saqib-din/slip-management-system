<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $slip->slip_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
            background: #fff;
            padding: 16px 20px;
        }

        /* ── HEADER ── */
        .slip-header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 11px;
            line-height: 1.7;
            color: #333;
        }

        .company-name-ar {
            font-size: 14px;
            font-weight: bold;
            color: #111;
            margin-bottom: 2px;
        }

        .company-name-en {
            font-size: 20px;
            font-weight: 900;
            color: #d4820a;
            letter-spacing: 0.3px;
        }

        /* ── DELIVERY BAR ── */
        .delivery-bar {
            background: #111;
            color: #fff;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 6px 0;
            margin-bottom: 12px;
        }

        /* ── INFO GRID ── */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .info-table td {
            font-size: 12px;
            padding: 2px 0;
            width: 50%;
        }

        .lbl {
            font-weight: bold;
        }

        /* ── COMPANY ROW ── */
        .company-row {
            margin-bottom: 10px;
            font-size: 12px;
        }

        .company-row .lbl {
            font-weight: bold;
        }

        .company-value {
            display: inline-block;
            border-bottom: 1.5px dotted #444;
            font-weight: bold;
            font-size: 13px;
            padding: 0 4px 1px;
            min-width: 300px;
        }

        /* ── MATERIALS TABLE ── */
        .mat-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .mat-table th {
            border: 1px solid #555;
            text-align: center;
            padding: 5px 6px;
            font-weight: bold;
            background: #fff;
        }

        .mat-table td {
            border: 1px solid #555;
            padding: 5px 6px;
            text-align: center;
            height: 28px;
        }

        .mat-table td.desc {
            text-align: left;
            font-weight: bold;
        }

        /* ── TOTALS ── */
        .totals {
            width: 100%;
            margin-bottom: 14px;
        }

        .totals td {
            font-size: 13px;
            font-weight: bold;
            padding: 0;
        }

        /* ── SIGNATURES ── */
        .sig-table {
            width: 100%;
            margin-bottom: 12px;
        }

        .sig-table td {
            font-size: 12px;
            padding: 0;
            width: 50%;
        }

        .sig-label {
            font-weight: bold;
        }

        .sig-line {
            display: inline-block;
            border-bottom: 1px solid #333;
            min-width: 150px;
            font-weight: bold;
            font-size: 12px;
            vertical-align: bottom;
            padding: 0 3px 1px;
        }

        .sig-line-blank {
            display: inline-block;
            border-bottom: 1px solid #333;
            min-width: 150px;
            vertical-align: bottom;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="slip-header">
        <div class="header-left">
            <div class="company-name-ar">اولد سيتي لغسل الرمال وتهيئتها ش.ذ.م.م</div>
            <div class="company-name-en">OLD CITY SAND WASHING L.L.C</div>
        </div>
        <div class="header-right">
            P.O. Box : 73100<br>
            Dubai – U.A.E<br>
            Tel.: 04 283 2732<br>
            sales@oldcity.ae &nbsp; www.oldcity.ae
        </div>
    </div>

    {{-- DELIVERY BAR --}}
    <div class="delivery-bar">
        سند نـسليم &nbsp;&nbsp;&nbsp;&nbsp; DELIVERY NOTE
    </div>

    {{-- INFO --}}
    <table class="info-table">
        <tr>
            <td><span class="lbl">Serial No :</span> {{ $slip->slip_no }}</td>
            <td><span class="lbl">Date :</span> {{ optional($slip->date)->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td><span class="lbl">Site No :</span> {{ $slip->site_no }}</td>
            <td><span class="lbl">Time :</span> {{ $slip->time }}</td>
        </tr>
        <tr>
            <td><span class="lbl">LPO No :</span> {{ $slip->lpo_no }}</td>
            <td><span class="lbl">Vehicle No :</span> {{ $slip->vehicle_no }}</td>
        </tr>
    </table>

    {{-- COMPANY NAME --}}
    <div class="company-row">
        <span class="lbl">Company Name:...</span>
        <span class="company-value">{{ $slip->company }}</span>
    </div>

    {{-- MATERIALS TABLE --}}
    @php
        $items = $slip->items ?? [];
        $maxRows = max(4, count($items));
    @endphp

    <table class="mat-table">
        <thead>
            <tr>
                <th style="width:35px;">No</th>
                <th>Description</th>
                <th style="width:60px;">M3</th>
                <th style="width:70px;">TON</th>
                <th style="width:70px;">TRIPS</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < $maxRows; $i++)
                @php $item = $items[$i] ?? null; @endphp
                <tr>
                    <td>{{ $item ? $i + 1 : '' }}</td>
                    <td class="desc">{{ $item['material'] ?? '' }}</td>
                    <td>{{ $item['m3'] ?? '' }}</td>
                    <td>{{ $item['ton'] ?? '' }}</td>
                    <td>{{ $item['trips'] ?? '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    {{-- TOTALS --}}
    <table class="totals">
        <tr>
            <td>Tip : {{ $slip->tip > 0 ? number_format($slip->tip, 0) : '' }}</td>
            <td style="text-align:right;">Cash Trip :
                {{ $slip->cash_trip > 0 ? number_format($slip->cash_trip, 0) : '' }}</td>
        </tr>
    </table>

    {{-- NAMES --}}
    <table class="sig-table" style="margin-bottom:10px;">
        <tr>
            <td>
                <span class="sig-label">Rec. Name</span>
                <span class="sig-line">{{ $slip->receiver_name }}</span>
            </td>
            <td style="text-align:right;">
                <span class="sig-label">Driver's Name</span>
                <span class="sig-line">{{ $slip->driver }}</span>
            </td>
        </tr>
    </table>

    {{-- SIGNATURES --}}
    <table class="sig-table">
        <tr>
            <td>
                <span class="sig-label">Rec. Signature</span>
                <span
                    class="sig-line-blank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </td>
            <td style="text-align:right;">
                <span class="sig-label">Driver's Signature</span>
                <span
                    class="sig-line-blank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </td>
        </tr>
    </table>

</body>

</html>
