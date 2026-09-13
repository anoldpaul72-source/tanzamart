@extends('layouts.admin')

@section('title', __('messages.manage_vendors') . ' - TanzaMart Admin')

@section('styles')
<style>
    .vendor-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .vendor-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .vendor-table th {
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
    .vendor-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .vendor-table tr:last-child td {
        border-bottom: none;
    }
    .vendor-table tr:hover {
        background: #f8fafc;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }
    .badge-warning { background: #fef3c7; color: #b45309; }
    .badge-info { background: #e0f2fe; color: #0369a1; }
    
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
    .btn-verify { background: #10b981; color: white; }
    .btn-verify:hover { background: #059669; }
    .btn-unverify { background: #f59e0b; color: white; }
    .btn-unverify:hover { background: #d97706; }
    .btn-pay { background: #0284c7; color: white; }
    .btn-pay:hover { background: #0369a1; }
    .btn-unpay { background: #64748b; color: white; }
    .btn-unpay:hover { background: #475569; }
    .btn-pw { background: #6366f1; color: white; }
    .btn-pw:hover { background: #4f46e5; }
    .btn-del { background: #ef4444; color: white; }
    .btn-del:hover { background: #dc2626; }

    /* Modal Styles */
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
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
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
        transition: color 0.15s;
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
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
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
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
        👥 {{ __('messages.manage_vendors') }}
    </h1>
    <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
        {{ app()->getLocale() === 'sw' 
            ? 'Hakiki wauzaji, dhibiti manenosiri, thibitisha usajili na kufuta akaunti ikibidi.' 
            : 'Verify vendors, manage credentials, approve subscription payments and manage accounts.' }}
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

<div class="vendor-table-card">
    <div style="overflow-x: auto;">
        <table class="vendor-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">{{ __('messages.shop_name') }}</th>
                    <th style="width: 20%;">{{ __('messages.email') }}</th>
                    <th style="width: 12%;">{{ app()->getLocale() === 'sw' ? 'Kifurushi (Plan)' : 'Plan Type' }}</th>
                    <th style="width: 15%;">{{ app()->getLocale() === 'sw' ? 'Mwisho wa Muda' : 'Expires At' }}</th>
                    <th style="width: 10%;">{{ __('messages.status') }}</th>
                    <th style="width: 18%;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $v)
                    @php
                        $is_active = ($v->is_paid == 1 && !empty($v->subscription_end_date) && strtotime($v->subscription_end_date) > time());
                        $displayName = $v->shop_name ?? ($v->name ?? 'Muuzaji #' . $v->id);
                    @endphp
                    <tr>
                        <td style="font-weight: 600; color: #64748b;">#{{ $v->id }}</td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;">{{ $displayName }}</div>
                            @if($v->is_verified)
                                <span style="display: inline-flex; align-items: center; gap: 3px; font-size: 0.7rem; color: #10b981; font-weight: 600;">
                                    ✓ {{ app()->getLocale() === 'sw' ? 'Imehakikiwa' : 'Verified' }}
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 3px; font-size: 0.7rem; color: #94a3b8;">
                                    ⏳ {{ app()->getLocale() === 'sw' ? 'Bado Uhakiki' : 'Unverified' }}
                                </span>
                            @endif
                        </td>
                        <td style="color: #475569;">{{ $v->email }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ !empty($v->plan_type) ? strtoupper($v->plan_type) : 'MONTHLY' }}
                            </span>
                        </td>
                        <td>
                            @if(!empty($v->subscription_end_date))
                                @php
                                    $is_expired = strtotime($v->subscription_end_date) < time();
                                    $dateStr = date('Y-m-d H:i', strtotime($v->subscription_end_date));
                                @endphp
                                @if($is_expired)
                                    <span style="color: #ef4444; font-weight: 600; font-size: 0.8125rem;">{{ $dateStr }} ({{ app()->getLocale() === 'sw' ? 'Imeisha' : 'Expired' }})</span>
                                @else
                                    <span style="color: #10b981; font-weight: 600; font-size: 0.8125rem;">{{ $dateStr }}</span>
                                @endif
                            @else
                                <span style="color: #94a3b8; font-size: 0.8125rem;">{{ app()->getLocale() === 'sw' ? 'Haijalipia' : 'Unpaid' }}</span>
                            @endif
                        </td>
                        <td>
                            @if($is_active)
                                <span class="badge badge-success">● {{ __('messages.active') }}</span>
                            @else
                                <span class="badge badge-danger">● {{ __('messages.inactive') }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @if($v->is_verified)
                                    <a href="{{ route('admin.vendors', ['action' => 'unverify', 'id' => $v->id]) }}" class="btn-action btn-unverify" title="{{ app()->getLocale() === 'sw' ? 'Ondoa Uhakiki' : 'Revoke Verification' }}">
                                        {{ app()->getLocale() === 'sw' ? 'Ondoa Uhakiki' : 'Revoke' }}
                                    </a>
                                @else
                                    <a href="{{ route('admin.vendors', ['action' => 'verify', 'id' => $v->id]) }}" class="btn-action btn-verify" title="{{ app()->getLocale() === 'sw' ? 'Hakiki Muuzaji' : 'Verify Vendor' }}">
                                        ✓ {{ app()->getLocale() === 'sw' ? 'Hakiki' : 'Verify' }}
                                    </a>
                                @endif

                                @if($is_active)
                                    <a href="{{ route('admin.vendors', ['action' => 'unpay', 'id' => $v->id]) }}" class="btn-action btn-unpay" title="{{ app()->getLocale() === 'sw' ? 'Futa Malipo' : 'Revoke Payment' }}">
                                        {{ app()->getLocale() === 'sw' ? 'Futa Malipo' : 'Unpay' }}
                                    </a>
                                @else
                                    <a href="{{ route('admin.vendors', ['action' => 'pay', 'id' => $v->id]) }}" class="btn-action btn-pay" title="{{ app()->getLocale() === 'sw' ? 'Thibitisha Malipo' : 'Confirm Payment' }}">
                                        💳 {{ app()->getLocale() === 'sw' ? 'Thibitisha' : 'Activate' }}
                                    </a>
                                @endif

                                <button type="button" class="btn-action btn-pw" onclick="openPasswordModal({{ $v->id }}, '{{ addslashes($displayName) }}', '{{ $v->email }}')" title="{{ app()->getLocale() === 'sw' ? 'Badilisha Nenosiri' : 'Change Password' }}">
                                    🔑
                                </button>

                                <button type="button" class="btn-action btn-del" onclick="openDeleteModal({{ $v->id }}, '{{ addslashes($displayName) }}', '{{ $v->email }}')" title="{{ app()->getLocale() === 'sw' ? 'Futa Akaunti' : 'Delete Account' }}">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b; padding: 36px;">
                            {{ app()->getLocale() === 'sw' ? 'Hakuna wauzaji wowote waliosajiliwa kwa sasa.' : 'No vendors registered yet.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal ya Kubadili Password -->
<div id="passwordModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>🔑 {{ app()->getLocale() === 'sw' ? 'Badilisha Nenosiri la Muuzaji' : 'Change Vendor Password' }}</h3>
            <button type="button" class="modal-close" onclick="closePasswordModal()">&times;</button>
        </div>
        <form id="passwordForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="user-pill">
                    <div><strong>{{ __('messages.vendor') }}:</strong> <span id="pwVendorName"></span></div>
                    <div style="color: #64748b; font-size: 0.75rem; margin-top: 3px;"><strong>{{ __('messages.email') }}:</strong> <span id="pwVendorEmail"></span></div>
                </div>

                <div class="form-group">
                    <label for="new_password">{{ app()->getLocale() === 'sw' ? 'Nenosiri Jipya:' : 'New Password:' }}</label>
                    <input type="password" name="password" id="new_password" class="form-control" placeholder="{{ app()->getLocale() === 'sw' ? 'Herufi angalau 6' : 'At least 6 characters' }}" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">{{ app()->getLocale() === 'sw' ? 'Rudia Nenosiri Jipya:' : 'Confirm New Password:' }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ app()->getLocale() === 'sw' ? 'Rudia nenosiri lile lile' : 'Confirm password' }}" required minlength="6">
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
            <h3>⚠️ {{ app()->getLocale() === 'sw' ? 'Thibitisha Kufuta Akaunti ya Muuzaji' : 'Confirm Vendor Account Deletion' }}</h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <form id="deleteForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="user-pill" style="background: #fef2f2; border-color: #fee2e2;">
                    <div><strong>{{ __('messages.vendor') }}:</strong> <span id="delVendorName" style="color: #991b1b; font-weight: bold;"></span></div>
                    <div style="color: #64748b; font-size: 0.75rem; margin-top: 3px;"><strong>{{ __('messages.email') }}:</strong> <span id="delVendorEmail"></span></div>
                </div>
                <p style="font-size: 0.875rem; color: #334155; line-height: 1.5; margin: 0 0 10px 0;">
                    {{ app()->getLocale() === 'sw' ? 'Una uhakika unataka kufuta akaunti hii ya muuzaji?' : 'Are you sure you want to delete this vendor account?' }}
                </p>
                <div style="font-size: 0.8125rem; color: #b91c1c; background: #fff1f2; padding: 10px 12px; border-radius: 8px; border-left: 3px solid #dc2626;">
                    <strong>{{ app()->getLocale() === 'sw' ? 'Tahadhari:' : 'Warning:' }}</strong> 
                    {{ app()->getLocale() === 'sw' 
                        ? 'Kufuta akaunti hii kutaondoa pia bidhaa zote za muuzaji na rekodi zake sokoni.' 
                        : 'Deleting this account will remove all products and records of this vendor.' }}
                </div>
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
    function openPasswordModal(id, name, email) {
        document.getElementById('pwVendorName').innerText = name;
        document.getElementById('pwVendorEmail').innerText = email;
        document.getElementById('passwordForm').action = "{{ url('/admin/users') }}/" + id + "/password";
        document.getElementById('new_password').value = '';
        document.getElementById('password_confirmation').value = '';
        document.getElementById('passwordModal').classList.add('active');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('active');
    }

    function openDeleteModal(id, name, email) {
        document.getElementById('delVendorName').innerText = name;
        document.getElementById('delVendorEmail').innerText = email;
        document.getElementById('deleteForm').action = "{{ url('/admin/users') }}/" + id + "/delete";
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
