@extends('layouts.admin')

@section('title', __('messages.manage_users') . ' - TanzaMart Admin')

@section('styles')
<style>
    /* Filter and Search Bar */
    .controls-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        background: white;
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
    .filter-tab {
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        color: #64748b;
        background: #f1f5f9;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .filter-tab:hover { background: #e2e8f0; color: #1e293b; }
    .filter-tab.active { background: #06b6d4; color: white; }
    .tab-badge {
        background: rgba(0,0,0,0.12);
        padding: 2px 7px;
        border-radius: 9999px;
        font-size: 0.7rem;
    }
    .filter-tab.active .tab-badge { background: rgba(255,255,255,0.25); color: white; }
    
    .search-form { display: flex; gap: 8px; align-items: center; }
    .search-input {
        padding: 8px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
        width: 240px;
        transition: 0.2s;
    }
    .search-input:focus { border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15); }
    .btn-search {
        background: #0f172a;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-search:hover { background: #1e293b; }
    .btn-reset {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
    }
    .btn-reset:hover { background: #e2e8f0; }

    /* Table */
    .user-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .user-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .user-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .user-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .user-table tr:last-child td { border-bottom: none; }
    .user-table tr:hover { background-color: #f8fafc; }

    .role-badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .role-admin { background: #fee2e2; color: #991b1b; }
    .role-vendor { background: #d1fae5; color: #065f46; }
    .role-customer { background: #e0f2fe; color: #0369a1; }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-pw { background: #6366f1; color: white; }
    .btn-pw:hover { background: #4f46e5; }
    .btn-del { background: #ef4444; color: white; }
    .btn-del:hover { background: #dc2626; }

    /* Modals */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 95%;
        max-width: 480px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        animation: modalIn 0.2s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.96) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header {
        padding: 18px 22px;
        background: #0f172a;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { font-size: 1rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin: 0; }
    .modal-close {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 22px;
        cursor: pointer;
        line-height: 1;
    }
    .modal-close:hover { color: #fff; }
    .modal-body { padding: 22px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 6px; }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
        transition: 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: #06b6d4; box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15); }
    .modal-footer {
        padding: 14px 22px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .user-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.8125rem;
        margin-bottom: 16px;
    }
    .pagination-wrap {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
        👥 {{ __('messages.manage_users') }}
    </h1>
    <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
        {{ app()->getLocale() === 'sw' 
            ? 'Simamia wateja, wauzaji na wasimamizi wa mfumo. Unaweza kubadilisha nenosiri au kufuta akaunti zao.' 
            : 'Manage customers, vendors and system administrators. Reset passwords or manage account access.' }}
    </p>
</div>

@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
        ⚠️ {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Filter tabs & Search -->
<div class="controls-bar">
    <div class="filter-tabs">
        <a href="{{ route('admin.users', ['search' => request('search')]) }}" class="filter-tab {{ empty($role) ? 'active' : '' }}">
            {{ __('messages.all') }} <span class="tab-badge">{{ $counts['all'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.users', ['role' => 'user', 'search' => request('search')]) }}" class="filter-tab {{ ($role ?? '') === 'user' ? 'active' : '' }}">
            {{ app()->getLocale() === 'sw' ? 'Wateja (Customers)' : 'Customers' }} <span class="tab-badge">{{ $counts['customers'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.users', ['role' => 'vendor', 'search' => request('search')]) }}" class="filter-tab {{ ($role ?? '') === 'vendor' ? 'active' : '' }}">
            {{ app()->getLocale() === 'sw' ? 'Wauzaji (Vendors)' : 'Vendors' }} <span class="tab-badge">{{ $counts['vendors'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.users', ['role' => 'admin', 'search' => request('search')]) }}" class="filter-tab {{ ($role ?? '') === 'admin' ? 'active' : '' }}">
            {{ app()->getLocale() === 'sw' ? 'Wasimamizi (Admins)' : 'Admins' }} <span class="tab-badge">{{ $counts['admins'] ?? 0 }}</span>
        </a>
    </div>

    <form action="{{ route('admin.users') }}" method="GET" class="search-form">
        @if(!empty($role))
            <input type="hidden" name="role" value="{{ $role }}">
        @endif
        <input type="text" name="search" class="search-input" placeholder="{{ app()->getLocale() === 'sw' ? 'Tafuta jina, barua pepe au simu...' : 'Search name, email, phone...' }}" value="{{ $search ?? '' }}">
        <button type="submit" class="btn-search">{{ __('messages.search') }}</button>
        @if(!empty($search) || !empty($role))
            <a href="{{ route('admin.users') }}" class="btn-reset">{{ __('messages.reset') }}</a>
        @endif
    </form>
</div>

<div class="user-table-card">
    <div style="overflow-x: auto;">
        <table class="user-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 22%;">{{ __('messages.name') }} / {{ __('messages.shop_name') }}</th>
                    <th style="width: 20%;">{{ __('messages.email') }}</th>
                    <th style="width: 12%;">{{ __('messages.role') }}</th>
                    <th style="width: 13%;">{{ __('messages.phone') }}</th>
                    <th style="width: 12%;">{{ app()->getLocale() === 'sw' ? 'Tarehe ya Usajili' : 'Registered At' }}</th>
                    <th style="width: 16%;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    @php
                        $displayName = $u->shop_name ? $u->shop_name . ' (' . $u->name . ')' : $u->name;
                        $isCurrentAdmin = (auth()->id() === $u->id);
                    @endphp
                    <tr>
                        <td style="font-weight: 600; color: #64748b;">#{{ $u->id }}</td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;">
                                {{ $displayName }}
                                @if($isCurrentAdmin)
                                    <span style="font-size: 0.65rem; background: #e0e7ff; color: #4338ca; padding: 2px 6px; border-radius: 4px; margin-left: 4px; font-weight: 700;">
                                        {{ app()->getLocale() === 'sw' ? 'Wewe' : 'You' }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="color: #475569;">{{ $u->email }}</td>
                        <td>
                            @if($u->isAdmin())
                                <span class="role-badge role-admin">ADMIN</span>
                            @elseif($u->isVendor())
                                <span class="role-badge role-vendor">VENDOR</span>
                            @else
                                <span class="role-badge role-customer">CUSTOMER</span>
                            @endif
                        </td>
                        <td style="color: #475569;">{{ $u->phone ?? '-' }}</td>
                        <td style="color: #64748b; font-size: 0.8125rem;">{{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                <button type="button" class="btn-action btn-pw" onclick="openPasswordModal({{ $u->id }}, '{{ addslashes($displayName) }}', '{{ $u->email }}', '{{ $u->role }}')" title="{{ app()->getLocale() === 'sw' ? 'Badilisha Nenosiri' : 'Change Password' }}">
                                    🔑 {{ app()->getLocale() === 'sw' ? 'Password' : 'Password' }}
                                </button>

                                @if(!$u->isAdmin())
                                    <button type="button" class="btn-action btn-del" onclick="openDeleteModal({{ $u->id }}, '{{ addslashes($displayName) }}', '{{ $u->email }}', '{{ $u->role }}')" title="{{ app()->getLocale() === 'sw' ? 'Futa Akaunti' : 'Delete Account' }}">
                                        🗑️
                                    </button>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.75rem; padding: 4px; font-style: italic;">
                                        {{ app()->getLocale() === 'sw' ? 'Msimamizi' : 'Admin' }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b; padding: 36px;">
                            {{ app()->getLocale() === 'sw' ? 'Hakuna mtumiaji aliyepatikana kwa vigezo hivi.' : 'No users found matching this criteria.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-wrap">
    {{ $users->links() }}
</div>

<!-- Modal ya Kubadili Password -->
<div id="passwordModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>🔑 {{ app()->getLocale() === 'sw' ? 'Badilisha Nenosiri (Password)' : 'Change User Password' }}</h3>
            <button type="button" class="modal-close" onclick="closePasswordModal()">&times;</button>
        </div>
        <form id="passwordForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="user-pill">
                    <div><strong>{{ __('messages.user') }}:</strong> <span id="pwUserName"></span> (<span id="pwUserRole" style="text-transform: capitalize;"></span>)</div>
                    <div style="color: #64748b; font-size: 0.75rem; margin-top: 3px;"><strong>{{ __('messages.email') }}:</strong> <span id="pwUserEmail"></span></div>
                </div>

                <div class="form-group">
                    <label for="new_password">{{ app()->getLocale() === 'sw' ? 'Nenosiri Jipya:' : 'New Password:' }}</label>
                    <input type="password" name="password" id="new_password" class="form-control" placeholder="{{ app()->getLocale() === 'sw' ? 'Weka nenosiri jipya (angalau herufi 6)' : 'At least 6 characters' }}" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">{{ app()->getLocale() === 'sw' ? 'Rudia Nenosiri Jipya:' : 'Confirm Password:' }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ app()->getLocale() === 'sw' ? 'Rudia nenosiri jipya' : 'Confirm password' }}" required minlength="6">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <label style="font-size: 0.75rem; color: #475569; display: flex; align-items: center; gap: 5px; cursor: pointer;">
                        <input type="checkbox" onchange="togglePasswordVisibility(this)"> {{ app()->getLocale() === 'sw' ? 'Onyesha nenosiri' : 'Show password' }}
                    </label>
                    <button type="button" onclick="generateRandomPassword()" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; cursor: pointer; font-weight: 600;">
                        🎲 {{ app()->getLocale() === 'sw' ? 'Tengeneza Nenosiri' : 'Generate Strong' }}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action" style="background: #e2e8f0; color: #475569;" onclick="closePasswordModal()">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn-action btn-pw">{{ app()->getLocale() === 'sw' ? 'Hifadhi Nenosiri' : 'Save Password' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal ya Kufuta Akaunti -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header" style="background: #991b1b;">
            <h3>⚠️ {{ app()->getLocale() === 'sw' ? 'Thibitisha Kufuta Akaunti' : 'Confirm Account Deletion' }}</h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <form id="deleteForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="user-pill" style="background: #fef2f2; border-color: #fee2e2;">
                    <div><strong>{{ __('messages.user') }}:</strong> <span id="delUserName" style="color: #991b1b; font-weight: bold;"></span> (<span id="delUserRole" style="text-transform: capitalize;"></span>)</div>
                    <div style="color: #64748b; font-size: 0.75rem; margin-top: 3px;"><strong>{{ __('messages.email') }}:</strong> <span id="delUserEmail"></span></div>
                </div>
                <p style="font-size: 0.875rem; color: #334155; line-height: 1.5; margin: 0 0 10px 0;">
                    {{ app()->getLocale() === 'sw' ? 'Una uhakika unataka kufuta kabisa akaunti ya mtumiaji huyu?' : 'Are you sure you want to permanently delete this user account?' }}
                </p>
                <p id="delVendorWarning" style="display:none; font-size: 0.8125rem; color: #b91c1c; margin-top: 8px; background: #fff1f2; padding: 10px 12px; border-radius: 8px; border-left: 3px solid #dc2626;">
                    <strong>{{ app()->getLocale() === 'sw' ? 'Tahadhari:' : 'Warning:' }}</strong> 
                    {{ app()->getLocale() === 'sw' 
                        ? 'Mtumiaji huyu ni muuzaji (Vendor). Kufuta akaunti hii kutaondoa pia bidhaa zake zote sokoni na kumbukumbu zake.' 
                        : 'This user is a vendor. Deleting this account will also remove all their products and associated store data.' }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action" style="background: #e2e8f0; color: #475569;" onclick="closeDeleteModal()">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn-action btn-del">{{ app()->getLocale() === 'sw' ? 'Ndio, Futa Akaunti' : 'Yes, Delete Account' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openPasswordModal(id, name, email, role) {
        document.getElementById('pwUserName').innerText = name;
        document.getElementById('pwUserEmail').innerText = email;
        document.getElementById('pwUserRole').innerText = role;
        document.getElementById('passwordForm').action = "{{ url('/admin/users') }}/" + id + "/password";
        document.getElementById('new_password').value = '';
        document.getElementById('password_confirmation').value = '';
        document.getElementById('passwordModal').classList.add('active');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('active');
    }

    function openDeleteModal(id, name, email, role) {
        document.getElementById('delUserName').innerText = name;
        document.getElementById('delUserEmail').innerText = email;
        document.getElementById('delUserRole').innerText = role;
        document.getElementById('deleteForm').action = "{{ url('/admin/users') }}/" + id + "/delete";

        const warning = document.getElementById('delVendorWarning');
        if (role === 'vendor') {
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }

        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    function togglePasswordVisibility(checkbox) {
        const pw1 = document.getElementById('new_password');
        const pw2 = document.getElementById('password_confirmation');
        const type = checkbox.checked ? 'text' : 'password';
        pw1.type = type;
        pw2.type = type;
    }

    function generateRandomPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
        let result = '';
        for (let i = 0; i < 10; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('new_password').value = result;
        document.getElementById('password_confirmation').value = result;
        document.getElementById('new_password').type = 'text';
        document.getElementById('password_confirmation').type = 'text';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePasswordModal();
            closeDeleteModal();
        }
    });
</script>
@endsection
