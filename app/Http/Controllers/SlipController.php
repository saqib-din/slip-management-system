<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use App\Models\Company;
use App\Models\Material;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class SlipController extends Controller
{
    /**
     * Columns the table is allowed to sort by.
     * "Slip No" sorts by `id` on purpose — slip_no is a string like
     * "OLD CITY-100", so text sorting gives the wrong order.
     */
    private const SORTABLE = ['id', 'date', 'company', 'site_no', 'vehicle_no'];

    /**
     * Slips list — Inertia page with server-side pagination, search & filters.
     */
    public function index(Request $request)
    {
        $query = Slip::query();

        // Custom filters
        if ($request->filled('filter_date')) {
            $query->whereDate('date', $request->filter_date);
        }
        if ($request->filled('filter_slip_no')) {
            $query->where('slip_no', 'like', $request->filter_slip_no . '%');
        }

        // Global search
        // Global search — prefix match (index-friendly)
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('slip_no',    'like', "{$s}%")   // % aage se hata diya
                    ->orWhere('company',    'like', "{$s}%")
                    ->orWhere('site_no',    'like', "{$s}%")
                    ->orWhere('vehicle_no', 'like', "{$s}%");
            });
        }

        // Ordering
        $sort = in_array($request->sort, self::SORTABLE, true) ? $request->sort : 'id';
        $dir  = $request->dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        // Pagination (keeps current query string on page links)
        $perPage = (int) $request->input('per_page', 25);
        $slips = $query->paginate($perPage)->withQueryString()->through(fn($slip) => [
            'id'            => $slip->id,
            'slip_no'       => $slip->slip_no,
            'date'          => optional($slip->date)->format('d M Y'),
            'date_raw'      => optional($slip->date)->format('Y-m-d'),
            'company'       => $slip->company,
            'site_no'       => $slip->site_no,
            'vehicle_no'    => $slip->vehicle_no,
            'time'          => $slip->time,
            'lpo_no'        => $slip->lpo_no,
            'tip'           => $slip->tip,
            'cash_trip'     => $slip->cash_trip,
            'refund'        => $slip->refund,
            'receiver_name' => $slip->receiver_name,
            'driver'        => $slip->driver,
            'items'         => $slip->items ?? [],
        ]);

        return Inertia::render('Slips/Index', [
            'slips'     => $slips,
            'companies' => Company::orderBy('name')->get(['id', 'name']),
            'materials' => Material::orderBy('name')->pluck('name'),
            'filters'   => [
                'filter_date'    => $request->filter_date,
                'filter_slip_no' => $request->filter_slip_no,
                'search'         => $request->search,
                'sort'           => $sort,
                'dir'            => $dir,
                'per_page'       => $perPage,
            ],
        ]);
    }

    /**
     * Show single slip (delivery note view) — Inertia page, no reload.
     * Note: the DomPDF template (pages.admin.slips.pdf) stays Blade — used by pdf() below.
     */
    public function show(Slip $slip)
    {
        return Inertia::render('Slips/Show', [
            'slip' => [
                'id'            => $slip->id,
                'slip_no'       => $slip->slip_no,
                'date'          => optional($slip->date)->format('Y-m-d'),
                'site_no'       => $slip->site_no,
                'time'          => $slip->time,
                'lpo_no'        => $slip->lpo_no,
                'vehicle_no'    => $slip->vehicle_no,
                'company'       => $slip->company,
                'tip'           => $slip->tip,
                'cash_trip'     => $slip->cash_trip,
                'refund'        => $slip->refund,
                'receiver_name' => $slip->receiver_name,
                'driver'        => $slip->driver,
                'items'         => $slip->items ?? [],
            ],
        ]);
    }

    /**
     * Store a new slip.
     */
    public function store(Request $request)
    {
        $validated = $this->validateSlip($request);

        $validated['items']      = $this->buildItems($request);
        $validated['created_by'] = auth()->user()->name ?? 'Admin';

        \DB::transaction(function () use (&$validated) {
            $lastSlip   = Slip::lockForUpdate()->latest('id')->first();
            $lastNumber = $lastSlip ? (int) preg_replace('/\D/', '', $lastSlip->slip_no) : 0;
            $validated['slip_no'] = 'OLD CITY-' . ($lastNumber + 1);

            Slip::create($validated);
        });

        return back()->with('success', 'Slip added successfully.');
    }

    /**
     * Update an existing slip.
     */
    public function update(Request $request, Slip $slip)
    {
        $validated = $this->validateSlip($request);
        $validated['items'] = $this->buildItems($request);

        $slip->update($validated);

        return back()->with('success', 'Slip updated successfully.');
    }

    /**
     * Delete a slip.
     */
    public function destroy(Slip $slip)
    {
        $slip->delete();

        return back()->with('success', 'Slip deleted.');
    }

    /**
     * Download slip as PDF.
     */
    public function pdf(Slip $slip)
    {
        $pdf = Pdf::loadView('pages.admin.slips.pdf', compact('slip'))
            ->setPaper('a5', 'landscape');

        return $pdf->download($slip->slip_no . '.pdf');
    }

    /**
     * Export slips to CSV.
     */
    public function export(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date|after_or_equal:from_date',
        ]);

        $query = Slip::query();

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $filename = 'slips_' . now()->format('Y_m_d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'Slip No',
                'Date',
                'Site No',
                'Time',
                'LPO No',
                'Vehicle',
                'Company',
                'Material',
                'M3',
                'TON',
                'Trips',
                'Tip',
                'Cash Trip',
                'Refund',
                'Receiver',
                'Driver',
            ]);

            $query->latest('id')->cursor()->each(function ($slip) use ($file) {
                $items = $slip->items ?? [];

                if (empty($items)) {
                    fputcsv($file, [
                        $slip->slip_no,
                        optional($slip->date)->format('Y-m-d'),
                        $slip->site_no,
                        $slip->time,
                        $slip->lpo_no,
                        $slip->vehicle_no,
                        $slip->company,
                        '',
                        '',
                        '',
                        '',
                        $slip->tip,
                        $slip->cash_trip,
                        $slip->refund,
                        $slip->receiver_name,
                        $slip->driver,
                    ]);
                } else {
                    foreach ($items as $i => $item) {
                        fputcsv($file, [
                            $i === 0 ? $slip->slip_no                         : '',
                            $i === 0 ? optional($slip->date)->format('Y-m-d') : '',
                            $i === 0 ? $slip->site_no                         : '',
                            $i === 0 ? $slip->time                            : '',
                            $i === 0 ? $slip->lpo_no                          : '',
                            $i === 0 ? $slip->vehicle_no                      : '',
                            $i === 0 ? $slip->company                         : '',
                            $item['material'] ?? '',
                            $item['m3']       ?? '',
                            $item['ton']      ?? '',
                            $item['trips']    ?? '',
                            $i === 0 ? $slip->tip           : '',
                            $i === 0 ? $slip->cash_trip     : '',
                            $i === 0 ? $slip->refund        : '',
                            $i === 0 ? $slip->receiver_name : '',
                            $i === 0 ? $slip->driver        : '',
                        ]);
                    }
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Private Helpers ────────────────────────────

    /**
     * Validation rules for store() and update().
     * Vue sends `company` already resolved and `items` as an array of objects.
     */
    private function validateSlip(Request $request): array
    {
        return $request->validate([
            'date'             => 'required|date',
            'site_no'          => 'nullable|string|max:255',
            'time'             => 'nullable',
            'lpo_no'           => 'nullable|string|max:255',
            'vehicle_no'       => 'required|string|max:255',
            'company'          => 'required|string|max:255',
            'tip'              => 'nullable|numeric|min:0',
            'cash_trip'        => 'nullable|numeric|min:0',
            'refund'           => 'nullable|string|max:255',
            'receiver_name'    => 'nullable|string|max:255',
            'driver'           => 'nullable|string|max:255',
            'items'            => 'nullable|array',
            'items.*.material' => 'nullable|string|max:255',
            'items.*.m3'       => 'nullable|string|max:255',
            'items.*.ton'      => 'nullable|string|max:255',
            'items.*.trips'    => 'nullable|string|max:255',
        ]);
    }

    /**
     * Keep only rows that have a material; store clean keys only.
     */
    private function buildItems(Request $request): array
    {
        return collect($request->input('items', []))
            ->filter(fn($i) => !empty($i['material']))
            ->map(fn($i) => [
                'material' => trim($i['material']),
                'm3'       => $i['m3']    ?? null,
                'ton'      => $i['ton']   ?? null,
                'trips'    => $i['trips'] ?? null,
            ])
            ->values()
            ->all();
    }
}
