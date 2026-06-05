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
                                <li class="breadcrumb-item" aria-current="page">Users</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Users Management</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary" id="stat-total">{{ $users->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger" id="stat-admin">{{ $users->where('role', 'admin')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Admins</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-warning" id="stat-manager">{{ $users->where('role', 'manager')->count() }}
                            </h4>
                            <p class="mb-0 text-muted f-12">Managers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary" id="stat-user">{{ $users->where('role', 'user')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Users</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">All Users</h5>
                                @if (auth()->user()->isAdmin())
                                    <button class="btn btn-primary d-flex" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        <i class="ti ti-user-plus me-1"></i> Add User
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="users-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            {{-- <th>Status</th> --}}
                                            {{-- <th>Joined</th> --}}
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="users-tbody">
                                        @forelse($users as $user)
                                            <tr id="user-row-{{ $user->id }}"
                                                @if ($user->id === auth()->id()) style="background:rgba(70,128,255,0.05);" @endif>
                                                <td class="row-num">{{ $loop->iteration }}</td>

                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avtar avtar-s me-3 {{ $user->getRoleBadgeClass() }} rounded-circle d-flex align-items-center justify-content-center user-avatar"
                                                            style="width:40px;height:40px;font-weight:700;font-size:1rem;">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 user-name">
                                                                {{ $user->name }}
                                                                @if ($user->id === auth()->id())
                                                                    <span class="badge bg-light-info ms-1">You</span>
                                                                @endif
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="user-email">{{ $user->email }}</td>
                                                <td class="user-phone">{{ $user->phone ?? '—' }}</td>

                                                <td>
                                                    <span class="badge {{ $user->getRoleBadgeClass() }} user-role-badge">
                                                        {{ $user->getRoleLabel() }}
                                                    </span>
                                                </td>

                                                {{-- <td>
                                                    <span
                                                        class="badge {{ $user->getStatusBadgeClass() }} user-status-badge">
                                                        {{ ucfirst($user->status) }}
                                                    </span>
                                                </td> --}}

                                                {{-- <td class="user-joined">{{ $user->created_at->format('d M Y') }}</td> --}}

                                                <td class="text-end">
                                                    @if (auth()->user()->isAdmin())
                                                        {{-- Toggle Status --}}
                                                        @if ($user->id !== auth()->id())
                                                            <button
                                                                class="avtar avtar-xs {{ $user->status === 'active' ? 'btn-link-warning' : 'btn-link-success' }} btn-toggle-status"
                                                                data-id="{{ $user->id }}"
                                                                data-status="{{ $user->status }}"
                                                                title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                                <i
                                                                    class="ti ti-{{ $user->status === 'active' ? 'user-off' : 'user-check' }} f-18"></i>
                                                            </button>
                                                        @endif

                                                        {{-- Edit --}}
                                                        <button class="avtar avtar-xs btn-link-secondary btn-edit-user"
                                                            data-id="{{ $user->id }}" title="Edit">
                                                            <i class="ti ti-edit f-18"></i>
                                                        </button>

                                                        {{-- Delete --}}
                                                        @if ($user->id !== auth()->id())
                                                            <button
                                                                class="avtar avtar-xs btn-link-secondary btn-delete-user"
                                                                data-id="{{ $user->id }}"
                                                                data-name="{{ $user->name }}" title="Delete">
                                                                <i class="ti ti-trash f-18"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="empty-row">
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <i class="ti ti-users f-40 d-block mb-2"></i>
                                                    No users found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===================== CREATE MODAL ===================== --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-user-plus me-2"></i>Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="createName" class="form-control" placeholder="Enter full name">
                            <div class="invalid-feedback" id="err-create-name"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="createEmail" class="form-control" placeholder="user@example.com">
                            <div class="invalid-feedback" id="err-create-email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" id="createPhone" class="form-control" placeholder="0300-1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="createRole" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="user" selected>User</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" id="createPassword" class="form-control"
                                placeholder="Min 8 characters">
                            <div class="invalid-feedback" id="err-create-password"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" id="createPasswordConfirm" class="form-control"
                                placeholder="Repeat password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnCreateUser">
                        <i class="ti ti-check me-1"></i> Create User
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== DELETE CONFIRM MODAL ===================== --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <span
                            class="avtar avtar-l bg-light-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:60px;height:60px;">
                            <i class="ti ti-trash f-28 text-danger"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete User?</h5>
                    <p class="text-muted mb-0">Are you sure you want to delete <strong id="deleteUserName"></strong>? This
                        action cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center pt-0 border-0 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="btnConfirmDelete">
                        <i class="ti ti-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== EDIT MODAL ===================== --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editUserId">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="editName" class="form-control">
                            <div class="invalid-feedback" id="err-edit-name"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="editEmail" class="form-control">
                            <div class="invalid-feedback" id="err-edit-email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" id="editPhone" class="form-control" placeholder="0300-1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="editRole" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" id="editPassword" class="form-control"
                                placeholder="Leave blank to keep current">
                            <div class="invalid-feedback" id="err-edit-password"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" id="editPasswordConfirm" class="form-control"
                                placeholder="Repeat new password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="btnUpdateUser">
                        <i class="ti ti-check me-1"></i> Update User
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const CSRF = '{{ csrf_token() }}';
        const AUTH_ID = {{ auth()->id() }};

        function initDT() {}

        function destroyDT() {}

        /* ═══════════════════════════════════════════════════════════
           TOAST
        ═══════════════════════════════════════════════════════════ */
        function showToast(message, type = 'success') {
            const bg = type === 'success' ? 'bg-success' : 'bg-danger';
            const el = document.createElement('div');
            el.className = `toast align-items-center text-white ${bg} border-0 position-fixed top-0 end-0 m-3`;
            el.style.zIndex = 9999;
            el.setAttribute('role', 'alert');
            el.innerHTML = `<div class="d-flex">
        <div class="toast-body fw-semibold">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>`;
            document.body.appendChild(el);
            const t = new bootstrap.Toast(el, {
                delay: 3000
            });
            t.show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }

        /* ═══════════════════════════════════════════════════════════
           VALIDATION HELPERS
        ═══════════════════════════════════════════════════════════ */
        function clearErrors(prefix) {
            document.querySelectorAll(`[id^="err-${prefix}-"]`).forEach(e => e.textContent = '');
            document.querySelectorAll(`#${prefix}Modal .form-control, #${prefix}Modal .form-select`)
                .forEach(el => el.classList.remove('is-invalid'));
        }

        function showErrors(prefix, errors) {
            Object.entries(errors).forEach(([field, msgs]) => {
                const key = field.replace('_confirmation', '');
                const errEl = document.getElementById(`err-${prefix}-${key}`);
                const inputId = prefix + key.charAt(0).toUpperCase() + key.slice(1);
                const inputEl = document.getElementById(inputId);
                if (errEl) errEl.textContent = msgs[0];
                if (inputEl) inputEl.classList.add('is-invalid');
            });
        }

        /* ═══════════════════════════════════════════════════════════
           STATS + ROW NUMBERS
        ═══════════════════════════════════════════════════════════ */
        function updateStats() {
            const rows = document.querySelectorAll('#users-tbody tr[id^="user-row-"]');
            let total = 0,
                admins = 0,
                managers = 0,
                users = 0;
            rows.forEach(row => {
                total++;
                const role = (row.querySelector('.user-role-badge') || {}).textContent || '';
                if (role.trim().toLowerCase() === 'admin') admins++;
                else if (role.trim().toLowerCase() === 'manager') managers++;
                else users++;
            });
            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-admin').textContent = admins;
            document.getElementById('stat-manager').textContent = managers;
            document.getElementById('stat-user').textContent = users;
        }

        function renumberRows() {
            document.querySelectorAll('#users-tbody tr[id^="user-row-"] .row-num')
                .forEach((td, i) => td.textContent = i + 1);
        }

        /* ═══════════════════════════════════════════════════════════
           BUILD ROW HTML
        ═══════════════════════════════════════════════════════════ */
        function buildRow(u) {
            const isMe = u.id === AUTH_ID || u.is_me === true;
            const avatar = u.name.charAt(0).toUpperCase();
            const cap = s => s.charAt(0).toUpperCase() + s.slice(1);
            const phone = u.phone || '—';

            const toggleBtn = isMe ? '' : `
        <button class="avtar avtar-xs ${u.status === 'active' ? 'btn-link-warning' : 'btn-link-success'} btn-toggle-status"
            data-id="${u.id}" data-status="${u.status}"
            title="${u.status === 'active' ? 'Deactivate' : 'Activate'}">
            <i class="ti ti-${u.status === 'active' ? 'user-off' : 'user-check'} f-18"></i>
        </button>`;

            const deleteBtn = isMe ? '' : `
        <button class="avtar avtar-xs btn-link-secondary btn-delete-user"
            data-id="${u.id}" data-name="${u.name}" title="Delete">
            <i class="ti ti-trash f-18"></i>
        </button>`;

            const youBadge = isMe ? '<span class="badge bg-light-info ms-1">You</span>' : '';

            return `
        <tr id="user-row-${u.id}" ${isMe ? 'style="background:rgba(70,128,255,0.05);"' : ''}>
            <td class="row-num"></td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avtar avtar-s me-3 ${u.role_badge} rounded-circle d-flex align-items-center justify-content-center user-avatar"
                        style="width:40px;height:40px;font-weight:700;font-size:1rem;">${avatar}</div>
                    <div><h6 class="mb-0 user-name">${u.name}${youBadge}</h6></div>
                </div>
            </td>
            <td class="user-email">${u.email}</td>
            <td class="user-phone">${phone}</td>
            <td><span class="badge ${u.role_badge} user-role-badge">${u.role_label}</span></td>
            {{-- <td><span class="badge ${u.status_badge} user-status-badge">${cap(u.status)}</span></td> --}}
            {{-- <td class="user-joined">${u.created_at}</td> --}}
            <td class="text-end">
                ${toggleBtn}
                <button class="avtar avtar-xs btn-link-secondary btn-edit-user" data-id="${u.id}" title="Edit">
                    <i class="ti ti-edit f-18"></i>
                </button>
                ${deleteBtn}
            </td>
        </tr>`;
        }

        /* ═══════════════════════════════════════════════════════════
           CREATE
        ═══════════════════════════════════════════════════════════ */
        document.getElementById('btnCreateUser').addEventListener('click', function() {
            clearErrors('create');
            const btn = this;
            const body = {
                name: document.getElementById('createName').value.trim(),
                email: document.getElementById('createEmail').value.trim(),
                phone: document.getElementById('createPhone').value.trim(),
                role: document.getElementById('createRole').value,
                password: document.getElementById('createPassword').value,
                password_confirmation: document.getElementById('createPasswordConfirm').value,
            };

            btn.disabled = true;

            fetch('{{ route('users.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body),
                })
                .then(r => r.json().then(d => ({
                    ok: r.ok,
                    data: d
                })))
                .then(({
                    ok,
                    data
                }) => {
                    if (!ok) {
                        if (data.errors) showErrors('create', data.errors);
                        else showToast(data.message || 'Error occurred.', 'error');
                        return;
                    }

                    // ── KEY FIX: destroy DT → mutate DOM → reinit DT ──
                    destroyDT();

                    const emptyRow = document.getElementById('empty-row');
                    if (emptyRow) emptyRow.remove();

                    document.getElementById('users-tbody').insertAdjacentHTML('beforeend', buildRow(data.user));
                    renumberRows();
                    updateStats();
                    initDT();
                    // ───────────────────────────────────────────────────

                    ['createName', 'createEmail', 'createPhone', 'createPassword', 'createPasswordConfirm']
                    .forEach(id => document.getElementById(id).value = '');
                    document.getElementById('createRole').value = 'user';

                    bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
                    showToast(data.message);
                })
                .catch(err => {
                    console.error(err);
                    showToast('Request failed.', 'error');
                })
                .finally(() => btn.disabled = false);
        });

        /* ═══════════════════════════════════════════════════════════
           EDIT — open modal
           (buttons are inside DT-rendered table, so listen on document)
        ═══════════════════════════════════════════════════════════ */
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit-user');
            if (!editBtn) return;

            clearErrors('edit');
            const id = editBtn.dataset.id;

            fetch(`/users/${id}/edit-data`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('editUserId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editPhone').value = data.phone || '';
                    document.getElementById('editRole').value = data.role;
                    document.getElementById('editPassword').value = '';
                    document.getElementById('editPasswordConfirm').value = '';
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                })
                .catch(() => showToast('Failed to load user data.', 'error'));
        });

        /* ═══════════════════════════════════════════════════════════
           UPDATE
        ═══════════════════════════════════════════════════════════ */
        document.getElementById('btnUpdateUser').addEventListener('click', function() {
            clearErrors('edit');
            const btn = this;
            const id = document.getElementById('editUserId').value;
            const body = {
                name: document.getElementById('editName').value.trim(),
                email: document.getElementById('editEmail').value.trim(),
                phone: document.getElementById('editPhone').value.trim(),
                role: document.getElementById('editRole').value,
                password: document.getElementById('editPassword').value,
                password_confirmation: document.getElementById('editPasswordConfirm').value,
            };

            btn.disabled = true;

            fetch(`/users/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body),
                })
                .then(r => r.json().then(d => ({
                    ok: r.ok,
                    data: d
                })))
                .then(({
                    ok,
                    data
                }) => {
                    if (!ok) {
                        if (data.errors) showErrors('edit', data.errors);
                        else showToast(data.message || 'Error occurred.', 'error');
                        return;
                    }

                    const u = data.user;
                    const cap = s => s.charAt(0).toUpperCase() + s.slice(1);

                    // ── destroy DT → update original DOM row → reinit DT ──
                    destroyDT();

                    const row = document.getElementById(`user-row-${u.id}`);
                    if (row) {
                        // Update name (keep "You" badge if present)
                        const nameEl = row.querySelector('.user-name');
                        const youBadge = nameEl.querySelector('.badge');
                        nameEl.textContent = u.name + ' ';
                        if (youBadge) nameEl.appendChild(youBadge);

                        row.querySelector('.user-email').textContent = u.email;
                        row.querySelector('.user-phone').textContent = u.phone || '—';

                        const roleBadge = row.querySelector('.user-role-badge');
                        roleBadge.textContent = u.role_label;
                        roleBadge.className = `badge ${u.role_badge} user-role-badge`;

                        // const statusBadge = row.querySelector('.user-status-badge');
                        // statusBadge.textContent = cap(u.status);
                        // statusBadge.className = `badge ${u.status_badge} user-status-badge`;

                        const avatar = row.querySelector('.user-avatar');
                        avatar.textContent = u.name.charAt(0).toUpperCase();
                        avatar.className =
                            `avtar avtar-s me-3 ${u.role_badge} rounded-circle d-flex align-items-center justify-content-center user-avatar`;
                    }

                    updateStats();
                    initDT();
                    // ───────────────────────────────────────────────────

                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    showToast(data.message);
                })
                .catch(err => {
                    console.error(err);
                    showToast('Request failed.', 'error');
                })
                .finally(() => btn.disabled = false);
        });

        /* ═══════════════════════════════════════════════════════════
           TOGGLE STATUS
        ═══════════════════════════════════════════════════════════ */
        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.btn-toggle-status');
            if (!toggleBtn) return;

            const id = toggleBtn.dataset.id;
            toggleBtn.disabled = true;

            fetch('{{ route('admin.users.toggle', ':id') }}'.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                })
                .then(r => r.json().then(d => ({
                    ok: r.ok,
                    data: d
                })))
                .then(({
                    ok,
                    data
                }) => {
                    if (!ok) {
                        showToast(data.message || 'Error occurred.', 'error');
                        return;
                    }

                    const cap = s => s.charAt(0).toUpperCase() + s.slice(1);
                    const isActive = data.status === 'active';

                    // Update original DOM row (DT reads from it)
                    const row = document.getElementById(`user-row-${id}`);
                    // if (row) {
                    //     const statusBadge = row.querySelector('.user-status-badge');
                    //     statusBadge.textContent = cap(data.status);
                    //     statusBadge.className = `badge ${data.status_badge} user-status-badge`;
                    // }

                    // Update the visible button (it's in DT-rendered markup)
                    toggleBtn.className =
                        `avtar avtar-xs ${isActive ? 'btn-link-warning' : 'btn-link-success'} btn-toggle-status`;
                    toggleBtn.title = isActive ? 'Deactivate' : 'Activate';
                    toggleBtn.dataset.status = data.status;
                    toggleBtn.innerHTML = `<i class="ti ti-${isActive ? 'user-off' : 'user-check'} f-18"></i>`;

                    showToast(data.message);
                })
                .catch(() => showToast('Request failed.', 'error'))
                .finally(() => toggleBtn.disabled = false);
        });

        /* ═══════════════════════════════════════════════════════════
           DELETE
        ═══════════════════════════════════════════════════════════ */
        let pendingDeleteId = null;
        let pendingDeleteBtn = null;

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete-user');
            if (!deleteBtn) return;

            pendingDeleteId = deleteBtn.dataset.id;
            pendingDeleteBtn = deleteBtn;

            document.getElementById('deleteUserName').textContent = deleteBtn.dataset.name;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });

        document.getElementById('btnConfirmDelete').addEventListener('click', function() {
            if (!pendingDeleteId) return;

            const id = pendingDeleteId;
            const btn = this;
            btn.disabled = true;
            if (pendingDeleteBtn) pendingDeleteBtn.disabled = true;

            fetch(`/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                })
                .then(r => r.json().then(d => ({
                    ok: r.ok,
                    data: d
                })))
                .then(({
                    ok,
                    data
                }) => {
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();

                    if (!ok) {
                        showToast(data.message || 'Error occurred.', 'error');
                        return;
                    }

                    // ── destroy DT → remove row from original DOM → reinit ──
                    destroyDT();

                    const row = document.getElementById(`user-row-${id}`);
                    if (row) row.remove();

                    renumberRows();
                    updateStats();

                    if (!document.querySelector('#users-tbody tr[id^="user-row-"]')) {
                        document.getElementById('users-tbody').innerHTML = `
                <tr id="empty-row">
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="ti ti-users f-40 d-block mb-2"></i>
                        No users found.
                    </td>
                </tr>`;
                    }

                    initDT();
                    // ────────────────────────────────────────────────────────

                    showToast(data.message);
                })
                .catch(() => showToast('Request failed.', 'error'))
                .finally(() => {
                    btn.disabled = false;
                    pendingDeleteId = null;
                    pendingDeleteBtn = null;
                });
        });
    </script>
@endpush
