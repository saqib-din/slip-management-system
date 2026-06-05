@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
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

            {{-- Toast --}}
            <div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:10001;min-width:260px;"></div>

            {{-- Delete Confirm Modal --}}
            <div id="delete-modal"
                style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
                <div
                    style="background:#fff;border-radius:14px;padding:32px 28px 24px;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.2);text-align:center;animation:popIn .2s ease;">
                    <div
                        style="width:56px;height:56px;border-radius:50%;background:#fce8e6;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="ti ti-trash" style="font-size:26px;color:#d93025;"></i>
                    </div>
                    <h5 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#1a1a1a;">Delete Slip?</h5>
                    <p id="delete-modal-msg" style="margin:0 0 24px;font-size:14px;color:#666;"></p>
                    <div style="display:flex;gap:10px;justify-content:center;">
                        <button onclick="closeDeleteModal()"
                            style="flex:1;padding:10px;border-radius:8px;border:1px solid #ddd;background:#fff;font-size:14px;font-weight:500;cursor:pointer;color:#333;">Cancel</button>
                        <button id="delete-modal-confirm"
                            style="flex:1;padding:10px;border-radius:8px;border:none;background:#d93025;color:#fff;font-size:14px;font-weight:500;cursor:pointer;">Delete</button>
                    </div>
                </div>
            </div>
            <style>
                @keyframes popIn {
                    from {
                        transform: scale(.9);
                        opacity: 0;
                    }

                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }
            </style>

            {{-- FILTER CARD --}}
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Slip Records</h5>
                        <button class="btn btn-outline-primary btn-sm" id="toggleFiltersBtn">
                            <i class="ti ti-filter me-1"></i>
                            <span id="filterBtnLabel">Show Filters</span>
                        </button>
                    </div>
                </div>
                <div class="card-body" id="filterBody" style="display:none;">
                    <div class="row align-items-end g-2 mb-3">
                        <div class="col-md-4">
                            <label>Date Filter</label>
                            <input type="date" id="filter_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Serial Number</label>
                            <input type="text" id="filter_slip_no" class="form-control" placeholder="e.g., OLD CITY-1">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100 rounded-2" style="padding:0.8em;"
                                id="applyFilterBtn">
                                <i class="ti ti-search me-1"></i>Apply
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary w-100 rounded-2" style="padding:0.8em;"
                                id="clearFiltersBtn">
                                <i class="ti ti-x me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                    <hr>
                    <form method="GET" action="{{ route('slips.export') }}">
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

            {{-- SLIPS TABLE --}}
            <div class="card m-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>All Slips</h5>
                        <button class="btn btn-primary d-flex" data-bs-toggle="modal" data-bs-target="#addSlipModal">
                            <i class="ti ti-plus"></i> Add Slip
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="slips-table">
                        <thead>
                            <tr>
                                <th>Slip No</th>
                                <th>Date</th>
                                <th>Company</th>
                                <th>Site No</th>
                                <th>Vehicle</th>
                                <th class="text-end no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ADD SLIP MODAL --}}
    <div class="modal fade" id="addSlipModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <form id="addSlipForm" action="{{ route('slips.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Slip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Site No</label>
                                <input type="text" name="site_no" class="form-control" placeholder="e.g., JLT-BO1">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Time</label>
                                <input type="time" name="time" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>LPO No</label>
                                <input type="text" name="lpo_no" class="form-control" placeholder="e.g., LPO-12345">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Vehicle No</label>
                                <input type="text" name="vehicle_no" class="form-control" required
                                    placeholder="e.g., D 54321">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Tip</label>
                                <input type="number" name="tip" step="0.01" class="form-control"
                                    placeholder="Enter tip">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Cash Trip</label>
                                <input type="number" name="cash_trip" step="0.01" class="form-control"
                                    placeholder="Enter cash trip">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Refund</label>
                                <input type="text" name="refund" class="form-control" placeholder="Enter credits">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="customCompanyCheck">
                                    <label class="form-check-label">Use custom company</label>
                                </div>
                                <select name="company" id="companySelect" class="form-control">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->name }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="company_custom" id="companyCustom"
                                    class="form-control mt-2 d-none" placeholder="Enter custom company">
                            </div>

                            {{-- ADD ITEMS --}}
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
                                <div id="itemsWrapper"></div>
                                <button type="button" class="btn btn-light-danger mt-2" id="addItem">+ Add
                                    Item</button>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Receiver's Name</label>
                                <input type="text" name="receiver_name" class="form-control"
                                    placeholder="Receiver full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Driver's Name</label>
                                <input type="text" name="driver" class="form-control"
                                    placeholder="Driver full name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="addSlipSubmitBtn">Generate Slip</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT SLIP MODAL --}}
    <div class="modal fade" id="editSlipModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Slip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Date</label>
                                <input type="date" name="date" id="edit_date" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Site No</label>
                                <input type="text" name="site_no" id="edit_site_no" class="form-control"
                                    placeholder="e.g., JLT-BO1">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Time</label>
                                <input type="time" name="time" id="edit_time" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>LPO No</label>
                                <input type="text" name="lpo_no" id="edit_lpo_no" class="form-control"
                                    placeholder="e.g., LPO-12345">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Vehicle No</label>
                                <input type="text" name="vehicle_no" id="edit_vehicle_no" class="form-control"
                                    placeholder="e.g., D 54321">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Tip</label>
                                <input type="number" step="0.01" name="tip" id="edit_tip" class="form-control"
                                    placeholder="Enter tip">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Cash Trip</label>
                                <input type="number" step="0.01" name="cash_trip" id="edit_cash_trip"
                                    class="form-control" placeholder="Enter cash trip">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Refund</label>
                                <input type="text" name="refund" id="edit_refund" class="form-control"
                                    placeholder="Enter credits">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="editCustomCompanyCheck">
                                    <label class="form-check-label">Use custom company</label>
                                </div>
                                <select name="company" id="edit_companySelect" class="form-control">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->name }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="company_custom" id="editCompanyCustom"
                                    class="form-control mt-2 d-none" placeholder="Enter custom company">
                            </div>

                            {{-- EDIT ITEMS --}}
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
                                <div id="editItemsWrapper"></div>
                                <button type="button" class="btn btn-light-danger mt-2" id="addEditItem">+ Add
                                    Item</button>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Receiver's Name</label>
                                <input type="text" name="receiver_name" id="edit_receiver_name" class="form-control"
                                    placeholder="Receiver full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Driver's Name</label>
                                <input type="text" name="driver" id="edit_driver" class="form-control"
                                    placeholder="Driver full name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="editSlipSubmitBtn">Update Slip</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const SLIP_CSRF = '{{ csrf_token() }}';
        const knownMaterials = @json($materials->pluck('name'));

        // ─── Material options ─────────────────────────────────────────────
        function materialOptions(selectedVal) {
            selectedVal = selectedVal || '';
            var html = '<option value="">Select a material...</option>';
            knownMaterials.forEach(function(name) {
                var sel = (name === selectedVal) ? ' selected' : '';
                html += '<option value="' + escAttr(name) + '"' + sel + '>' + escAttr(name) + '</option>';
            });
            return html;
        }

        // ─── Helpers ──────────────────────────────────────────────────────
        function escAttr(str) {
            return String(str || '')
                .replace(/&/g, '&amp;').replace(/"/g, '&quot;')
                .replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // ─── Toast ────────────────────────────────────────────────────────
        function showToast(message, type) {
            type = type || 'success';
            var colors = {
                success: '#198754',
                danger: '#dc3545',
                warning: '#e6a817'
            };
            var toast = document.createElement('div');
            toast.style.cssText = 'background:' + colors[type] +
                ';color:#fff;padding:10px 18px;border-radius:8px;margin-bottom:8px;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);opacity:0;transition:opacity .25s;';
            toast.textContent = message;
            document.getElementById('toast-container').appendChild(toast);
            requestAnimationFrame(function() {
                toast.style.opacity = '1';
            });
            setTimeout(function() {
                toast.style.opacity = '0';
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 2800);
        }

        // ─── Delete Modal ─────────────────────────────────────────────────
        function openDeleteModal(id, slipNo) {
            document.getElementById('delete-modal-msg').textContent = 'Slip "' + slipNo + '" This record will be permanently deleted.';
            document.getElementById('delete-modal').style.display = 'flex';
            document.getElementById('delete-modal-confirm').onclick = function() {
                confirmSlipDelete(id);
            };
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }
        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
        async function confirmSlipDelete(id) {
            closeDeleteModal();
            try {
                var res = await fetch('/slips/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': SLIP_CSRF,
                        'Accept': 'application/json'
                    }
                });
                var data = await res.json();
                if (data.success) {
                    dt.ajax.reload(null, false);
                    showToast('Slip deleted.');
                } else showToast(data.message || 'Could not delete.', 'danger');
            } catch (e) {
                showToast('Request failed.', 'danger');
            }
        }

        // ─── DataTable ────────────────────────────────────────────────────
        var dt = $('#slips-table').DataTable({
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            pageLength: 25,
            ajax: {
                url: '{{ route('slips.datatable') }}',
                data: function(d) {
                    d.filter_date = $('#filter_date').val();
                    d.filter_slip_no = $('#filter_slip_no').val();
                }
            },
            columns: [{
                    data: 'slip_no'
                },
                {
                    data: 'date'
                },
                {
                    data: 'company'
                },
                {
                    data: 'site_no'
                },
                {
                    data: 'vehicle_no'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function(data) {
                        return '<a href="/slips/' + data.id +
                            '" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-18"></i></a> ' +
                            '<button type="button" class="avtar avtar-xs btn-link-secondary editBtn"' +
                            ' data-id="' + data.id + '"' +
                            ' data-date="' + escAttr(data.date_raw) + '"' +
                            ' data-site_no="' + escAttr(data.site_no) + '"' +
                            ' data-time="' + escAttr(data.time) + '"' +
                            ' data-lpo_no="' + escAttr(data.lpo_no) + '"' +
                            ' data-vehicle_no="' + escAttr(data.vehicle_no) + '"' +
                            ' data-company="' + escAttr(data.company) + '"' +
                            ' data-tip="' + escAttr(data.tip) + '"' +
                            ' data-cash_trip="' + escAttr(data.cash_trip) + '"' +
                            ' data-refund="' + escAttr(data.refund) + '"' +
                            ' data-receiver_name="' + escAttr(data.receiver_name) + '"' +
                            ' data-driver="' + escAttr(data.driver) + '"' +
                            " data-items='" + JSON.stringify(data.items || []) + "'" +
                            ' data-bs-toggle="modal" data-bs-target="#editSlipModal" title="Edit">' +
                            '<i class="ti ti-edit f-18"></i></button> ' +
                            '<button type="button" class="avtar avtar-xs btn-link-secondary slip-delete-btn"' +
                            ' data-id="' + data.id + '"' +
                            ' data-slip-no="' + escAttr(data.slip_no) + '" title="Delete">' +
                            '<i class="ti ti-trash f-18"></i></button>';
                    }
                }
            ]
        });

        $(document).on('click', '.slip-delete-btn', function() {
            openDeleteModal($(this).data('id'), $(this).data('slip-no'));
        });

        // ─── Filters ──────────────────────────────────────────────────────
        document.getElementById('toggleFiltersBtn').addEventListener('click', function() {
            var fb = document.getElementById('filterBody');
            var visible = fb.style.display !== 'none';
            fb.style.display = visible ? 'none' : 'block';
            document.getElementById('filterBtnLabel').textContent = visible ? 'Show Filters' : 'Hide Filters';
        });
        document.getElementById('applyFilterBtn').addEventListener('click', function() {
            dt.ajax.reload();
        });
        document.getElementById('clearFiltersBtn').addEventListener('click', function() {
            document.getElementById('filter_date').value = '';
            document.getElementById('filter_slip_no').value = '';
            dt.ajax.reload();
        });
        document.getElementById('filter_slip_no').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') dt.ajax.reload();
        });
        document.getElementById('filter_date').addEventListener('change', function() {
            dt.ajax.reload();
        });

        // ─── Dubai Date/Time ──────────────────────────────────────────────
        (function() {
            var dubaiNow = new Date(new Date().toLocaleString('en-US', {
                timeZone: 'Asia/Dubai'
            }));
            var yyyy = dubaiNow.getFullYear();
            var mm = String(dubaiNow.getMonth() + 1).padStart(2, '0');
            var dd = String(dubaiNow.getDate()).padStart(2, '0');
            var hh = String(dubaiNow.getHours()).padStart(2, '0');
            var min = String(dubaiNow.getMinutes()).padStart(2, '0');
            document.getElementById('addSlipModal').addEventListener('show.bs.modal', function() {
                var dateInput = document.querySelector('#addSlipModal input[name="date"]');
                var timeInput = document.querySelector('#addSlipModal input[name="time"]');
                if (dateInput && !dateInput.value) dateInput.value = yyyy + '-' + mm + '-' + dd;
                if (timeInput && !timeInput.value) timeInput.value = hh + ':' + min;
            });
        })();

        // ─── Build Item Row ───────────────────────────────────────────────
        function buildItemRow(material, m3, ton, trips, isEdit) {
            material = material || '';
            m3 = m3 || '';
            ton = ton || '';
            trips = trips || '';
            isEdit = isEdit || false;

            var isCustom = material !== '' && knownMaterials.indexOf(material) === -1;
            var removeClass = isEdit ? 'removeEditItem' : 'removeAddItem';

            var div = document.createElement('div');
            div.className = 'item-row mb-3';
            div.innerHTML =
                '<div class="form-check mb-1">' +
                '<input class="form-check-input item-custom-check" type="checkbox"' + (isCustom ? ' checked' : '') + '>' +
                '<label class="form-check-label" style="font-size:13px;">Use custom description</label>' +
                '</div>' +
                '<div class="row g-2 align-items-center">' +
                '<div class="col-md-5">' +
                '<select name="items[]" class="form-control item-mat-select' + (isCustom ? ' d-none' : '') + '"' + (
                    isCustom ? ' disabled' : '') + '>' +
                materialOptions(isCustom ? '' : material) +
                '</select>' +
                '<input type="text" name="items_custom[]" class="form-control item-mat-custom' + (isCustom ? '' :
                    ' d-none') + '" placeholder="Enter custom description" value="' + escAttr(isCustom ? material : '') +
                '">' +
                '</div>' +
                '<div class="col-md-2"><input type="text" name="m3[]"    class="form-control" placeholder="0" value="' +
                escAttr(m3) + '"></div>' +
                '<div class="col-md-2"><input type="text" name="ton[]"   class="form-control" placeholder="0" value="' +
                escAttr(ton) + '"></div>' +
                '<div class="col-md-2"><input type="text" name="trips[]" class="form-control" placeholder="0" value="' +
                escAttr(trips) + '"></div>' +
                '<div class="col-md-1 d-flex align-items-center justify-content-center">' +
                '<button type="button" class="btn btn-sm ' + removeClass +
                '" style="color:#dc3545;background:none;border:none;padding:4px 8px;">' +
                '<i class="ti ti-trash" style="font-size:20px;"></i>' +
                '</button>' +
                '</div>' +
                '</div>';
            return div;
        }

        // ─── ✅ Page load — 
        document.getElementById('itemsWrapper').appendChild(buildItemRow('', '', '', '', false));
        document.getElementById('editItemsWrapper').appendChild(buildItemRow('', '', '', '', true));

        // ─── Custom check toggle ──────────────────────────────────────────
        document.addEventListener('change', function(e) {
            if (!e.target.classList.contains('item-custom-check')) return;
            var row = e.target.closest('.item-row');
            var select = row.querySelector('.item-mat-select');
            var input = row.querySelector('.item-mat-custom');
            if (e.target.checked) {
                select.classList.add('d-none');
                select.disabled = true;
                select.value = '';
                input.classList.remove('d-none');
                input.focus();
            } else {
                select.classList.remove('d-none');
                select.disabled = false;
                input.classList.add('d-none');
                input.value = '';
            }
        });

        // ─── Remove ADD item ──────────────────────────────────────────────
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.removeAddItem')) return;
            var wrapper = document.getElementById('itemsWrapper');
            if (wrapper.querySelectorAll('.item-row').length > 1)
                e.target.closest('.item-row').remove();
            else showToast('At least one item is required.', 'warning');
        });

        // ─── Remove EDIT item ─────────────────────────────────────────────
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.removeEditItem')) return;
            var wrapper = document.getElementById('editItemsWrapper');
            if (wrapper.querySelectorAll('.item-row').length > 1)
                e.target.closest('.item-row').remove();
            else showToast('At least one item is required.', 'warning');
        });

        // ─── Add Item buttons ─────────────────────────────────────────────
        document.getElementById('addItem').addEventListener('click', function() {
            document.getElementById('itemsWrapper').appendChild(buildItemRow('', '', '', '', false));
        });
        document.getElementById('addEditItem').addEventListener('click', function() {
            document.getElementById('editItemsWrapper').appendChild(buildItemRow('', '', '', '', true));
        });

        // ─── Company toggle ADD ───────────────────────────────────────────
        document.getElementById('customCompanyCheck').addEventListener('change', function() {
            var select = document.getElementById('companySelect');
            var input = document.getElementById('companyCustom');
            if (this.checked) {
                select.classList.add('d-none');
                select.value = '';
                input.classList.remove('d-none');
            } else {
                select.classList.remove('d-none');
                input.classList.add('d-none');
                input.value = '';
            }
        });

        // ─── Company toggle EDIT ──────────────────────────────────────────
        document.getElementById('editCustomCompanyCheck').addEventListener('change', function() {
            var select = document.getElementById('edit_companySelect');
            var input = document.getElementById('editCompanyCustom');
            if (this.checked) {
                select.classList.add('d-none');
                select.value = '';
                input.classList.remove('d-none');
            } else {
                select.classList.remove('d-none');
                input.classList.add('d-none');
                input.value = '';
            }
        });

        // ─── syncCustomItems ──────────────────────────────────────────────
        function syncCustomItems(wrapperId) {
            document.querySelectorAll('#' + wrapperId + ' .item-row').forEach(function(row) {
                var check = row.querySelector('.item-custom-check');
                var select = row.querySelector('.item-mat-select');
                var input = row.querySelector('.item-mat-custom');
                if (check && check.checked && input && select) {
                    var opt = document.createElement('option');
                    opt.value = input.value;
                    opt.selected = true;
                    select.appendChild(opt);
                    select.disabled = false;
                }
            });
        }

        // ─── ADD MODAL — Submit ───────────────────────────────────────────
        document.getElementById('addSlipForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            var btn = document.getElementById('addSlipSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Saving...';
            syncCustomItems('itemsWrapper');
            try {
                var res = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': SLIP_CSRF,
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                });
                var data = await res.json();
                if (res.ok && data.id) {
                    dt.ajax.reload(null, false);
                    bootstrap.Modal.getInstance(document.getElementById('addSlipModal')).hide();
                    showToast('Slip added successfully.');
                } else {
                    var errors = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ||
                        'Something went wrong.');
                    showToast(errors, 'danger');
                }
            } catch (err) {
                showToast('Request failed.', 'danger');
            }
            btn.disabled = false;
            btn.textContent = 'Generate Slip';
        });

        // ─── ADD MODAL — Reset on close ───────────────────────────────────
        document.getElementById('addSlipModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('addSlipForm').reset();
            var wrapper = document.getElementById('itemsWrapper');
            wrapper.innerHTML = '';
            wrapper.appendChild(buildItemRow('', '', '', '', false)); // first row permanent
            document.getElementById('companySelect').classList.remove('d-none');
            document.getElementById('companyCustom').classList.add('d-none');
            document.getElementById('customCompanyCheck').checked = false;
        });

        // ─── EDIT MODAL — Populate ────────────────────────────────────────
        $(document).on('click', '.editBtn', function() {
            var btn = this;
            document.getElementById('editForm').action = '/slips/' + btn.dataset.id;
            document.getElementById('edit_date').value = btn.dataset.date || '';
            document.getElementById('edit_site_no').value = btn.dataset.site_no || '';
            document.getElementById('edit_time').value = btn.dataset.time || '';
            document.getElementById('edit_lpo_no').value = btn.dataset.lpo_no || '';
            document.getElementById('edit_vehicle_no').value = btn.dataset.vehicle_no || '';
            document.getElementById('edit_tip').value = btn.dataset.tip || '';
            document.getElementById('edit_cash_trip').value = btn.dataset.cash_trip || '';
            document.getElementById('edit_refund').value = btn.dataset.refund || '';
            document.getElementById('edit_receiver_name').value = btn.dataset.receiver_name || '';
            document.getElementById('edit_driver').value = btn.dataset.driver || '';

            // Company
            var companyVal = btn.dataset.company || '';
            var editSelect = document.getElementById('edit_companySelect');
            var editInput = document.getElementById('editCompanyCustom');
            var editCheck = document.getElementById('editCustomCompanyCheck');
            editCheck.checked = false;
            editSelect.classList.remove('d-none');
            editInput.classList.add('d-none');
            editInput.value = '';
            editSelect.value = companyVal;
            if (companyVal && editSelect.value !== companyVal) {
                editCheck.checked = true;
                editSelect.classList.add('d-none');
                editInput.classList.remove('d-none');
                editInput.value = companyVal;
            }

            // Items
            var wrapper = document.getElementById('editItemsWrapper');
            wrapper.innerHTML = '';
            var items = [];
            try {
                items = JSON.parse(btn.dataset.items || '[]');
            } catch (e) {
                items = [];
            }
            if (items.length) {
                items.forEach(function(i) {
                    wrapper.appendChild(buildItemRow(i.material, i.m3, i.ton, i.trips, true));
                });
            } else {
                wrapper.appendChild(buildItemRow('', '', '', '', true)); // first row permanent
            }
        });

        // ─── EDIT MODAL — Submit ──────────────────────────────────────────
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            var btn = document.getElementById('editSlipSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Updating...';
            syncCustomItems('editItemsWrapper');
            var formData = new FormData(this);
            formData.append('_method', 'PUT');
            try {
                var res = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': SLIP_CSRF,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                var data = await res.json();
                if (res.ok && data.id) {
                    dt.ajax.reload(null, false);
                    bootstrap.Modal.getInstance(document.getElementById('editSlipModal')).hide();
                    showToast('Slip updated successfully.');
                } else {
                    var errors = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ||
                        'Something went wrong.');
                    showToast(errors, 'danger');
                }
            } catch (err) {
                showToast('Request failed.', 'danger');
            }
            btn.disabled = false;
            btn.textContent = 'Update Slip';
        });

        // ─── EDIT MODAL — Reset on close ─────────────────────────────────
        document.getElementById('editSlipModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('editCustomCompanyCheck').checked = false;
            document.getElementById('edit_companySelect').classList.remove('d-none');
            document.getElementById('editCompanyCustom').classList.add('d-none');
            document.getElementById('editCompanyCustom').value = '';
            var wrapper = document.getElementById('editItemsWrapper');
            wrapper.innerHTML = '';
            wrapper.appendChild(buildItemRow('', '', '', '', true)); // first row permanent
        });
    </script>
@endpush
