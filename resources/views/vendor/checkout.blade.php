<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.chk_title') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f6; padding: 30px 20px; display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; }
        .checkout-container { max-width: 650px; width: 100%; margin: 20px auto; background: white; padding: 35px 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #2563eb; font-size: 14px; font-weight: bold; }
        .back-link:hover { color: #1d4ed8; }
        h2 { color: #333; margin-bottom: 20px; font-size: 22px; font-weight: bold; }
        .amount-box { background: #eff6ff; border-left: 5px solid #2563eb; padding: 15px 18px; margin-bottom: 22px; border-radius: 4px; }
        .amount-box h3 { color: #006064; margin: 0; font-size: 18px; font-weight: bold; }
        .instructions { background: #fffde7; border: 1px solid #fff59d; padding: 16px 20px; border-radius: 6px; margin-bottom: 25px; }
        .instructions h4 { margin-top: 0; margin-bottom: 8px; color: #f57f17; font-size: 15px; font-weight: bold; }
        .instructions ul { padding-left: 20px; margin: 5px 0; color: #333; font-size: 13.5px; line-height: 1.6; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #444; font-size: 13.5px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: #2563eb; }
        
        .btn-complete { background: #ff9800; color: white; border: none; width: 100%; padding: 12px; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 15px; }
        .btn-complete:hover { background: #f57c00; }
        .btn-complete:disabled { background: #ccc; cursor: not-allowed; }

        .btn-whatsapp { background: #25d366; color: white; border: none; width: 100%; padding: 13px; border-radius: 5px; font-size: 15px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.3s; margin-top: 12px; text-decoration: none; text-align: center; }
        .btn-whatsapp:hover { background: #1ebe5d; }

        .btn-sms { background: #2563eb; color: white; border: none; width: 100%; padding: 13px; border-radius: 5px; font-size: 15px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: 0.3s; margin-top: 12px; text-decoration: none; text-align: center; }
        .btn-sms:hover { background: #1d4ed8; }

        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; text-align: center; font-weight: 500; line-height: 1.5; }
        .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 12px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; display: none; }
        
        #confirmationActions { display: none; }
    </style>
</head>
<body>

<div class="checkout-container">
    <a href="{{ route('vendor.dashboard') }}" class="back-link">{{ __('messages.chk_back_dashboard') ?? '← Back to Dashboard' }}</a>
    
    <h2>{{ __('messages.chk_heading') }} {{ $plan }}</h2>
    
    <div class="amount-box">
        <h3>{{ __('messages.chk_total_amount') }} {{ number_format($amount) }}</h3>
    </div>

    <div class="instructions">
        <h4>{{ __('messages.chk_inst_title') }}</h4>
        <ul>
            <li>{{ __('messages.chk_inst_1') }}</li>
            <li>{{ __('messages.chk_inst_2') }}</li>
            <li>{{ __('messages.chk_inst_3') }}</li>
        </ul>
    </div>

    <!-- Error Message Alert -->
    <div id="errorMessage" class="alert-error"></div>

    <!-- Payment Information Form -->
    <form id="paymentForm" onsubmit="completePayment(event)">
        <input type="hidden" id="plan" value="{{ $plan }}">
        <input type="hidden" id="amount" value="{{ $amount }}">

        <div id="formInputsWrapper">
            <div class="form-group">
                <label for="fullname">{{ __('messages.chk_lbl_fullname') }}</label>
                <input type="text" id="fullname" value="{{ $vendor->name ?? '' }}" placeholder="{{ __('messages.chk_ph_fullname') }}" required>
            </div>

            <div class="form-group">
                <label for="email">{{ __('messages.chk_lbl_email') }}</label>
                <input type="email" id="email" value="{{ $vendor->email ?? '' }}" placeholder="{{ __('messages.chk_ph_email') }}" required>
            </div>

            <div class="form-group">
                <label for="store">{{ __('messages.chk_lbl_store') }}</label>
                <input type="text" id="store" value="{{ $vendor->shop_name ?? ($vendor->name . ' Store') }}" placeholder="{{ __('messages.chk_ph_store') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">{{ __('messages.chk_lbl_phone') }}</label>
                <input type="text" id="phone" value="{{ $vendor->phone ?? '' }}" placeholder="{{ __('messages.chk_ph_phone') }}" required>
            </div>

            <div class="form-group">
                <label for="payment_method">{{ __('messages.chk_lbl_method') }}</label>
                <select id="payment_method" required>
                    <option value="">{{ __('messages.chk_ph_method') }}</option>
                    <option value="M-Pesa">Vodacom M-Pesa</option>
                    <option value="Tigo Pesa">Tigo Pesa</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="Halopesa">Halopesa</option>
                    <option value="CRDB Bank">Benki ya CRDB</option>
                </select>
            </div>

            <div class="form-group">
                <label for="transaction_id">{{ __('messages.chk_lbl_txid') }}</label>
                <input type="text" id="transaction_id" placeholder="{{ __('messages.chk_ph_txid') }}" required>
            </div>

            <button type="submit" id="submitBtn" class="btn-complete">
                {{ __('messages.chk_btn_complete') }}
            </button>
        </div>
    </form>

    <!-- Confirmation Actions Section (Shown upon successful submission) -->
    <div id="confirmationActions">
        <div class="alert-success">{{ __('messages.chk_success_notice') }}</div>

        <!-- WhatsApp Button -->
        <button type="button" onclick="sendToWhatsApp(event)" class="btn-whatsapp">
            {{ __('messages.chk_btn_whatsapp') }}
        </button>

        <!-- Normal SMS Button -->
        <button type="button" onclick="sendToSMS(event)" class="btn-sms">
            {{ __('messages.chk_btn_sms') }}
        </button>
    </div>
</div>

<script>
let savedDetails = {};

async function completePayment(event) {
    event.preventDefault();

    let form = document.getElementById('paymentForm');
    let errorDiv = document.getElementById('errorMessage');
    let submitBtn = document.getElementById('submitBtn');

    errorDiv.style.display = 'none';

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    let txIdValue = document.getElementById('transaction_id').value.trim();

    savedDetails = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        plan: document.getElementById('plan').value,
        amount: document.getElementById('amount').value,
        fullname: document.getElementById('fullname').value,
        email: document.getElementById('email').value,
        store: document.getElementById('store').value,
        phone: document.getElementById('phone').value,
        method: document.getElementById('payment_method').value,
        txId: txIdValue
    };

    submitBtn.disabled = true;
    submitBtn.innerText = "{{ app()->getLocale() === 'sw' ? 'Inahakiki...' : 'Verifying...' }}";

    try {
        let response = await fetch("{{ route('vendor.process_payment') }}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': savedDetails._token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(savedDetails)
        });

        let result = await response.json();

        if (result.status === 'success') {
            document.getElementById('formInputsWrapper').style.display = 'none';
            document.getElementById('confirmationActions').style.display = 'block';
        } else {
            errorDiv.innerText = result.message;
            errorDiv.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerText = "{{ __('messages.chk_btn_complete') }}";
        }
    } catch (err) {
        errorDiv.innerText = "{{ app()->getLocale() === 'sw' ? 'Hitilafu imetokea wakati wa kuwasiliana na server. Jaribu tena.' : 'An error occurred while connecting to the server. Please try again.' }}";
        errorDiv.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.innerText = "{{ __('messages.chk_btn_complete') }}";
    }
}

function sendToWhatsApp(event) {
    event.preventDefault();
    let d = savedDetails;

    let message = `Habari Admin, nimefanya malipo ya kifurushi changu cha TanzaMart:\n\n` +
                  `📦 *Kifurushi:* ${d.plan} (TSh ${d.amount})\n` +
                  `👤 *Jina Kamili:* ${d.fullname}\n` +
                  `📧 *Barua Pepe:* ${d.email}\n` +
                  `🏪 *Jina la Biashara:* ${d.store}\n` +
                  `📞 *Namba ya Simu:* ${d.phone}\n` +
                  `💳 *Njia ya Malipo:* ${d.method}\n` +
                  `🔢 *Transaction ID:* ${d.txId}\n\n` +
                  `Naomba mhakiki muamala wangu. Asante!`;

    let whatsappURL = `https://wa.me/255621530804?text=${encodeURIComponent(message)}`;
    window.open(whatsappURL, '_blank');
}

function sendToSMS(event) {
    event.preventDefault();
    let d = savedDetails;

    let message = `Habari Admin, nimelipia TanzaMart:\n` +
                  `Kifurushi: ${d.plan} (${d.amount}/=)\n` +
                  `Jina: ${d.fullname}\n` +
                  `Duka: ${d.store}\n` +
                  `Simu: ${d.phone}\n` +
                  `Njia: ${d.method}\n` +
                  `TxID: ${d.txId}\n` +
                  `Naomba uhakiki.`;

    let smsURL = `sms:255621530804?body=${encodeURIComponent(message)}`;
    window.location.href = smsURL;
}
</script>

</body>
</html>
