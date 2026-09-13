<!-- TANZAMART CHANGE PASSWORD MODAL -->
<style>
    .pwd-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(5px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: pwdFadeIn 0.2s ease-out;
    }

    .pwd-modal-backdrop.show {
        display: flex;
    }

    .pwd-modal-card {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        animation: pwdSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .pwd-modal-header {
        background: #0f172a;
        color: #ffffff;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #2563eb;
    }

    .pwd-modal-title {
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pwd-modal-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .pwd-modal-close:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .pwd-modal-body {
        padding: 24px;
        color: #1e293b;
    }

    .pwd-group {
        margin-bottom: 18px;
    }

    .pwd-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .pwd-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .pwd-input {
        width: 100%;
        padding: 11px 42px 11px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #f8fafc;
        color: #0f172a;
    }

    .pwd-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        background: #ffffff;
    }

    .pwd-toggle-eye {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
    }

    .pwd-toggle-eye:hover {
        color: #2563eb;
    }

    .pwd-error-msg {
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
        display: block;
        font-weight: 600;
    }

    .pwd-modal-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .pwd-btn-cancel {
        padding: 10px 18px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }

    .pwd-btn-cancel:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .pwd-btn-submit {
        padding: 10px 22px;
        border: none;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .pwd-btn-submit:hover {
        background: #1e40af;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    @keyframes pwdFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pwdSlideUp {
        from { transform: translateY(20px); opacity: 0.8; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<div id="changePasswordModal" class="pwd-modal-backdrop {{ $errors->has('current_password') || $errors->has('password') || session('password_modal_error') ? 'show' : '' }}">
    <div class="pwd-modal-card">
        <!-- Header -->
        <div class="pwd-modal-header">
            <div class="pwd-modal-title">
                <span>🔐</span> {{ app()->getLocale() == 'sw' ? 'Badili Nenosiri (Change Password)' : 'Change Your Password' }}
            </div>
            <button type="button" class="pwd-modal-close" onclick="closeChangePasswordModal()">✕</button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            <div class="pwd-modal-body">
                
                <!-- Current Password -->
                <div class="pwd-group">
                    <label class="pwd-label">{{ app()->getLocale() == 'sw' ? 'Nenosiri la Sasa (Current Password)' : 'Current Password' }} *</label>
                    <div class="pwd-input-wrapper">
                        <input type="password" name="current_password" id="input_current_password" class="pwd-input" placeholder="••••••••" required>
                        <button type="button" class="pwd-toggle-eye" onclick="togglePasswordVisibility('input_current_password', this)">👁️</button>
                    </div>
                    @error('current_password')
                        <span class="pwd-error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="pwd-group">
                    <label class="pwd-label">{{ app()->getLocale() == 'sw' ? 'Nenosiri Jipya (New Password)' : 'New Password' }} *</label>
                    <div class="pwd-input-wrapper">
                        <input type="password" name="password" id="input_new_password" class="pwd-input" placeholder="{{ app()->getLocale() == 'sw' ? 'Angalau herufi 6' : 'At least 6 characters' }}" minlength="6" required>
                        <button type="button" class="pwd-toggle-eye" onclick="togglePasswordVisibility('input_new_password', this)">👁️</button>
                    </div>
                    @error('password')
                        <span class="pwd-error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div class="pwd-group" style="margin-bottom: 5px;">
                    <label class="pwd-label">{{ app()->getLocale() == 'sw' ? 'Thibitisha Nenosiri Jipya' : 'Confirm New Password' }} *</label>
                    <div class="pwd-input-wrapper">
                        <input type="password" name="password_confirmation" id="input_confirm_password" class="pwd-input" placeholder="••••••••" minlength="6" required>
                        <button type="button" class="pwd-toggle-eye" onclick="togglePasswordVisibility('input_confirm_password', this)">👁️</button>
                    </div>
                </div>

            </div>

            <!-- Footer Buttons -->
            <div class="pwd-modal-footer">
                <button type="button" class="pwd-btn-cancel" onclick="closeChangePasswordModal()">
                    {{ app()->getLocale() == 'sw' ? 'Ghairi (Cancel)' : 'Cancel' }}
                </button>
                <button type="submit" class="pwd-btn-submit">
                    {{ app()->getLocale() == 'sw' ? 'Hifadhi Nenosiri 🔒' : 'Update Password 🔒' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openChangePasswordModal() {
        const modal = document.getElementById('changePasswordModal');
        if (modal) {
            modal.classList.add('show');
            const firstInput = document.getElementById('input_current_password');
            if (firstInput) setTimeout(() => firstInput.focus(), 150);
        }
    }

    function closeChangePasswordModal() {
        const modal = document.getElementById('changePasswordModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input) {
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerText = '🙈';
            } else {
                input.type = 'password';
                btn.innerText = '👁️';
            }
        }
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeChangePasswordModal();
        }
    });

    // Close on click outside modal card
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('changePasswordModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeChangePasswordModal();
                }
            });
        }
    });
</script>
