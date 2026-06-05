<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use App\Models\Company;
use App\Models\Material;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SlipController extends Controller
{
    /**
     * Columns exposed to the DataTable, in the same order as the
     * frontend table headers. NOTE: column 0 ("Slip No") maps to `id`
     * on purpose — slip_no is a string like "OLD CITY-100", so sorting
     * it as text gives the wrong order ("OLD CITY-9" > "OLD CITY-100").
     * `id` increments with creation, so it matches slip_no numerically.
     */
    private const ORDER_COLUMNS = ['id', 'date', 'company', 'site_no', 'vehicle_no'];

    /**
     * Show all slips with optional filters.
     */
    public function index(Request $request)
    {
        $companies = Company::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        return view('pages.admin.slips.index', compact('companies', 'materials'));
    }

    public function datatable(Request $request)
    {
        $query = Slip::query();

        // Custom filters
        if ($request->filled('filter_date')) {
            $query->whereDate('date', $request->filter_date);
        }

        if ($request->filled('filter_slip_no')) {
            // Anchored LIKE so the index on slip_no can be used.
            $query->where('slip_no', 'like', $request->filter_slip_no . '%');
        }

        $totalRecords = Slip::count();

        // DataTables search box
        $search = $request->input('search.value', '');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('slip_no',     'like', "%{$search}%")
                    ->orWhere('company',    'like', "%{$search}%")
                    ->orWhere('site_no',    'like', "%{$search}%")
                    ->orWhere('vehicle_no', 'like', "%{$search}%");
            });
        }

        $filteredRecords = $query->count();

        // Ordering — column 0 (Slip No) resolves to `id` (see ORDER_COLUMNS).
        $orderCol = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderBy  = self::ORDER_COLUMNS[$orderCol] ?? 'id';
        $query->orderBy($orderBy, $orderDir);

        // Pagination
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);
        $slips  = $query->skip($start)->take($length)->get();

        $rows = $slips->map(function ($slip) {
            return [
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
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $rows,
        ]);
    }

    /**
     * Show single slip (delivery note print view).
     */
    public function show(Slip $slip)
    {
        return view('pages.admin.slips.show', compact('slip'));
    }

    /**
     * Store a new slip.
     */
    public function store(Request $request)
    {
        $validated = $this->validateSlip($request);

        $validated['company'] = $this->resolveCompany($request);

        if (empty($validated['company'])) {
            return response()->json([
                'errors' => ['company' => ['Company name is required.']]
            ], 422);
        }

        // Build the items payload, then strip helper keys that are not
        // real columns so mass assignment never tries to insert them.
        $validated['items'] = $this->buildItems($request);
        $this->stripNonColumns($validated);

        $validated['created_by'] = auth()->user()->name ?? 'Admin';

        $slip = \DB::transaction(function () use ($validated) {
            $lastSlip   = Slip::lockForUpdate()->latest('id')->first();
            $lastNumber = $lastSlip ? (int) preg_replace('/\D/', '', $lastSlip->slip_no) : 0;

            $validated['slip_no'] = 'OLD CITY-' . ($lastNumber + 1);

            return Slip::create($validated);
        });

        return $this->slipResponse($slip->fresh());
    }

    /**
     * Update an existing slip.
     */
    public function update(Request $request, Slip $slip)
    {
        $validated = $this->validateSlip($request);

        $validated['company'] = $this->resolveCompany($request);

        if (empty($validated['company'])) {
            return response()->json([
                'errors' => ['company' => ['Company name is required.']]
            ], 422);
        }

        // Same item-building logic as store() — keeps custom descriptions
        // and the two code paths consistent.
        $validated['items'] = $this->buildItems($request);
        $this->stripNonColumns($validated);

        $slip->update($validated);

        return $this->slipResponse($slip->fresh());
    }

    /**
     * Delete a slip.
     */
    public function destroy(Slip $slip)
    {
        $slip->delete();

        return response()->json(['success' => true]);
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

            // UTF-8 BOM so Excel reads Arabic/special chars correctly.
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

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
     * Shared validation rules for store() and update().
     */
    private function validateSlip(Request $request): array
    {
        return $request->validate([
            'date'           => 'required|date',
            'site_no'        => 'nullable|string|max:255',
            'time'           => 'nullable|date_format:H:i',
            'lpo_no'         => 'nullable|string|max:255',
            'vehicle_no'     => 'required|string|max:255',
            'company'        => 'nullable|string|max:255',
            'company_custom' => 'nullable|string|max:255',
            'tip'            => 'nullable|numeric|min:0',
            'cash_trip'      => 'nullable|numeric|min:0',
            'refund'         => 'nullable|string|max:255',
            'receiver_name'  => 'nullable|string|max:255',
            'driver'         => 'nullable|string|max:255',
            'remarks'        => 'nullable|string',
            'items'          => 'nullable|array',
            'items.*'        => 'nullable|string|max:255',
            'items_custom'   => 'nullable|array',
            'items_custom.*' => 'nullable|string|max:255',
            'm3'             => 'nullable|array',
            'm3.*'           => 'nullable|string|max:255',
            'ton'            => 'nullable|array',
            'ton.*'          => 'nullable|string|max:255',
            'trips'          => 'nullable|array',
            'trips.*'        => 'nullable|string|max:255',
        ]);
    }

    /**
     * Remove keys that are submitted by the form but are NOT real
     * columns, so Slip::create()/update() never breaks if these ever
     * end up in $fillable.
     */
    private function stripNonColumns(array &$data): void
    {
        unset(
            $data['company_custom'],
            $data['items_custom'],
            $data['m3'],
            $data['ton'],
            $data['trips']
        );
    }

    /**
     * Standard JSON response for a slip.
     */
    private function slipResponse(Slip $slip)
    {
        return response()->json([
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
        ]);
    }

    private function resolveCompany(Request $request): string
    {
        return trim(
            $request->filled('company_custom')
                ? $request->company_custom
                : ($request->company ?? '')
        );
    }

    private function buildItems(Request $request): array
    {
        $items       = [];
        $itemNames   = $request->input('items', []);
        $customNames = $request->input('items_custom', []);
        $m3s         = $request->input('m3', []);
        $tons        = $request->input('ton', []);
        $tripsList   = $request->input('trips', []);

        foreach ($itemNames as $index => $name) {
            // Prefer the custom description if it was filled for this row.
            $finalName = !empty($customNames[$index])
                ? trim($customNames[$index])
                : trim((string) $name);

            if (!empty($finalName)) {
                $items[] = [
                    'material' => $finalName,
                    'm3'       => $m3s[$index]       ?? null,
                    'ton'      => $tons[$index]      ?? null,
                    'trips'    => $tripsList[$index] ?? null,
                ];
            }
        }

        return $items;
    }
}
