@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- Breadcrumb -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
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
            <div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:260px;"></div>

            <!-- Delete Confirm Modal -->
            <div id="delete-modal"
                style="
                display:none;position:fixed;inset:0;z-index:10000;
                background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
                <div
                    style="
                    background:#fff;border-radius:14px;padding:32px 28px 24px;
                    width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.2);
                    text-align:center;animation:popIn .2s ease;">
                    <div
                        style="
                        width:56px;height:56px;border-radius:50%;background:#fce8e6;
                        display:flex;align-items:center;justify-content:center;
                        margin:0 auto 16px;">
                        <i class="ti ti-trash" style="font-size:26px;color:#d93025;"></i>
                    </div>
                    <h5 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#1a1a1a;">Delete?</h5>
                    <p id="delete-modal-msg" style="margin:0 0 24px;font-size:14px;color:#666;">
                        Are you sure you want to delete this item?
                    </p>
                    <div style="display:flex;gap:10px;justify-content:center;">
                        <button onclick="closeDeleteModal()"
                            style="
                            flex:1;padding:10px;border-radius:8px;border:1px solid #ddd;
                            background:#fff;font-size:14px;font-weight:500;cursor:pointer;
                            color:#333;transition:background .15s;">
                            Cancel
                        </button>
                        <button id="delete-modal-confirm"
                            style="
                            flex:1;padding:10px;border-radius:8px;border:none;
                            background:#d93025;color:#fff;font-size:14px;
                            font-weight:500;cursor:pointer;transition:background .15s;">
                            Delete
                        </button>
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
                                <input type="text" id="new-company-name" class="form-control"
                                    placeholder="New company name..." />
                                <button type="button" class="btn btn-success" onclick="addItem('company')">
                                    <i class="ti ti-plus"></i> Add
                                </button>
                            </div>

                            <!-- List -->
                            <div id="company-list" style="max-height:264px; overflow-y:auto;">
                                @forelse($companies as $company)
                                    <div class="mgmt-row" id="company-row-{{ $company->id }}">
                                        <span class="item-label">{{ $company->name }}</span>
                                        <input type="text" class="item-input form-control form-control-sm d-none"
                                            value="{{ $company->name }}" />
                                        <div class="mgmt-actions">
                                            <button type="button" class="btn-icon btn-icon-edit btn-edit"
                                                onclick="toggleEdit('company', {{ $company->id }})" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn-icon btn-icon-save btn-save d-none"
                                                onclick="saveItem('company', {{ $company->id }})" title="Save">
                                                <i class="ti ti-check"></i>
                                            </button>
                                            <button type="button" class="btn-icon btn-icon-delete"
                                                onclick="deleteItem('company', {{ $company->id }})" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-3 mb-0" id="company-empty">No companies yet.</p>
                                @endforelse
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
                                <input type="text" id="new-material-name" class="form-control"
                                    placeholder="New description name..." />
                                <button type="button" class="btn btn-success" onclick="addItem('material')">
                                    <i class="ti ti-plus"></i> Add
                                </button>
                            </div>

                            <!-- List -->
                            <div id="material-list" style="max-height:264px; overflow-y:auto;">
                                @forelse($materials as $material)
                                    <div class="mgmt-row" id="material-row-{{ $material->id }}">
                                        <span class="item-label">{{ $material->name }}</span>
                                        <input type="text" class="item-input form-control form-control-sm d-none"
                                            value="{{ $material->name }}" />
                                        <div class="mgmt-actions">
                                            <button type="button" class="btn-icon btn-icon-edit btn-edit"
                                                onclick="toggleEdit('material', {{ $material->id }})" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn-icon btn-icon-save btn-save d-none"
                                                onclick="saveItem('material', {{ $material->id }})" title="Save">
                                                <i class="ti ti-check"></i>
                                            </button>
                                            <button type="button" class="btn-icon btn-icon-delete"
                                                onclick="deleteItem('material', {{ $material->id }})" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-3 mb-0" id="material-empty">No materials yet.</p>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
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
            /* color: #333; */
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
@endsection

@push('scripts')
    <script>
        const CSRF = '{{ csrf_token() }}';

        const routes = {
            company: {
                store: '{{ route('companies.store') }}',
                update: (id) => '{{ route('companies.update', '__ID__') }}'.replace('__ID__', id),
                destroy: (id) => '{{ route('companies.destroy', '__ID__') }}'.replace('__ID__', id),
            },
            material: {
                store: '{{ route('materials.store') }}',
                update: (id) => '{{ route('materials.update', '__ID__') }}'.replace('__ID__', id),
                destroy: (id) => '{{ route('materials.destroy', '__ID__') }}'.replace('__ID__', id),
            }
        };

        /* ── Toast ── */
        function showToast(message, type = 'success') {
            const colors = {
                success: '#198754',
                danger: '#dc3545',
                warning: '#e6a817'
            };
            const toast = document.createElement('div');
            toast.style.cssText = `
                background:${colors[type]};color:#fff;padding:10px 18px;
                border-radius:8px;margin-bottom:8px;font-size:14px;
                box-shadow:0 4px 12px rgba(0,0,0,.15);opacity:0;transition:opacity .25s;
            `;
            toast.textContent = message;
            document.getElementById('toast-container').appendChild(toast);
            requestAnimationFrame(() => toast.style.opacity = '1');
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 2800);
        }

        /* ── HTTP ── */
        async function request(url, method, body) {
            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: body ? JSON.stringify(body) : undefined,
            });
            return res.json();
        }

        /* ── Build Row HTML ── */
        function buildRow(type, id, name) {
            return `
            <div class="mgmt-row" id="${type}-row-${id}">
                <span class="item-label">${escHtml(name)}</span>
                <input type="text" class="item-input form-control form-control-sm d-none" value="${escHtml(name)}" />
                <div class="mgmt-actions">
                    <button type="button" class="btn-icon btn-icon-edit btn-edit"
                        onclick="toggleEdit('${type}', ${id})" title="Edit">
                        <i class="ti ti-edit"></i>
                    </button>
                    <button type="button" class="btn-icon btn-icon-save btn-save d-none"
                        onclick="saveItem('${type}', ${id})" title="Save">
                        <i class="ti ti-check"></i>
                    </button>
                    <button type="button" class="btn-icon btn-icon-delete"
                        onclick="deleteItem('${type}', ${id})" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>`;
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        /* ── Toggle Edit ── */
        function toggleEdit(type, id) {
            const row = document.getElementById(`${type}-row-${id}`);
            row.querySelector('.item-label').classList.toggle('d-none');
            row.querySelector('.item-input').classList.toggle('d-none');
            row.querySelector('.btn-edit').classList.toggle('d-none');
            row.querySelector('.btn-save').classList.toggle('d-none');
            const input = row.querySelector('.item-input');
            if (!input.classList.contains('d-none')) {
                input.focus();
                const len = input.value.length;
                input.setSelectionRange(len, len);
            }
        }

        /* ── Add ── */
        async function addItem(type) {
            const input = document.getElementById(`new-${type}-name`);
            const name = input.value.trim();
            if (!name) {
                showToast('Name cannot be empty.', 'warning');
                return;
            }

            try {
                const data = await request(routes[type].store, 'POST', {
                    name
                });
                if (data.id) {
                    document.getElementById(`${type}-empty`)?.remove();
                    document.getElementById(`${type}-list`)
                        .insertAdjacentHTML('afterbegin', buildRow(type, data.id, data.name));
                    input.value = '';
                    showToast(`${capitalize(type)} added.`);
                } else {
                    showToast(data.message ?? 'Something went wrong.', 'danger');
                }
            } catch {
                showToast('Request failed.', 'danger');
            }
        }

        /* ── Save ── */
        async function saveItem(type, id) {
            const row = document.getElementById(`${type}-row-${id}`);
            const input = row.querySelector('.item-input');
            const name = input.value.trim();
            if (!name) {
                showToast('Name cannot be empty.', 'warning');
                return;
            }

            try {
                const data = await request(routes[type].update(id), 'PUT', {
                    name
                });
                if (data.id) {
                    row.querySelector('.item-label').textContent = data.name;
                    input.value = data.name;
                    toggleEdit(type, id);
                    showToast(`${capitalize(type)} updated.`);
                } else {
                    showToast(data.message ?? 'Something went wrong.', 'danger');
                }
            } catch {
                showToast('Request failed.', 'danger');
            }
        }

        /* ── Delete Modal ── */
        function openDeleteModal(type, id, name) {
            document.getElementById('delete-modal-msg').textContent =
                `"${name}"This record will be permanently deleted.`;
            document.getElementById('delete-modal').style.display = 'flex';
            document.getElementById('delete-modal-confirm').onclick = () => confirmDelete(type, id);
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        /* ── Delete ── */
        async function deleteItem(type, id) {
            const row = document.getElementById(`${type}-row-${id}`);
            const name = row.querySelector('.item-label').textContent.trim();
            openDeleteModal(type, id, name);
        }

        async function confirmDelete(type, id) {
            closeDeleteModal();
            try {
                const data = await request(routes[type].destroy(id), 'DELETE');
                if (data.success) {
                    const row = document.getElementById(`${type}-row-${id}`);
                    const list = document.getElementById(`${type}-list`);
                    row.remove();
                    if (!list.querySelector(`[id^="${type}-row-"]`)) {
                        list.innerHTML =
                            `<p class="text-muted text-center py-3 mb-0" id="${type}-empty">No ${type}s yet.</p>`;
                    }
                    showToast(`${capitalize(type)} deleted.`);
                } else {
                    showToast(data.message ?? 'Could not delete.', 'danger');
                }
            } catch {
                showToast('Request failed.', 'danger');
            }
        }

        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }

        ['company', 'material'].forEach(type => {
            document.getElementById(`new-${type}-name`)
                .addEventListener('keydown', e => {
                    if (e.key === 'Enter') addItem(type);
                });
        });
    </script>
@endpush
