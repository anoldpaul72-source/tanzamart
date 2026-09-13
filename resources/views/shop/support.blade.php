@extends('layouts.app')

@section('title', 'Kituo cha Msaada - TanzaMart')

@push('styles')
<style>
    .support-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 30px;
        align-items: start;
        margin-top: 15px;
    }
    .chat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 600px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    .chat-header {
        background: #2563eb;
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #ffffff !important;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .chat-status-dot {
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.35);
        flex-shrink: 0;
    }
    .chat-header-title {
        font-size: 1.15rem;
        font-weight: 800;
        margin: 0;
        color: #ffffff !important;
        letter-spacing: -0.2px;
    }
    .chat-header-status {
        color: #f0fdf4 !important;
        font-size: 0.85rem;
        font-weight: 600;
        opacity: 0.95;
    }
    .chat-body {
        flex: 1;
        padding: 22px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: #f8fafc;
    }
    .msg-bubble {
        max-width: 80%;
        padding: 13px 18px;
        border-radius: 16px;
        font-size: 0.95rem;
        line-height: 1.5;
        position: relative;
    }
    .msg-user {
        align-self: flex-end;
        background: #2563eb;
        color: #ffffff;
        border-bottom-right-radius: 4px;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }
    .msg-bot {
        align-self: flex-start;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
    .chat-footer {
        padding: 16px 20px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .chat-input {
        flex: 1;
        padding: 13px 20px;
        border-radius: 30px;
        border: 1.5px solid #cbd5e1;
        outline: none;
        font-size: 0.95rem;
        font-family: inherit;
        transition: border-color 0.2s ease;
    }
    .chat-input:focus {
        border-color: #2563eb;
    }
    .btn-send {
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 12px 24px;
        border-radius: 30px;
        font-weight: bold;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-send:hover {
        background: #1d4ed8;
    }
    .contact-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
    }
    .contact-phone-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 8px;
        text-decoration: none;
        color: #0f172a;
        font-size: 1.02rem;
        font-weight: 700;
        transition: all 0.2s ease;
        margin-bottom: 8px;
    }
    .contact-phone-btn:hover {
        background: #f0fdfa;
        border-color: #2563eb;
        color: #1d4ed8;
    }
    .whatsapp-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #25D366;
        color: #ffffff;
        text-decoration: none;
        padding: 11px 16px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.92rem;
        transition: background 0.2s ease, transform 0.15s ease;
        box-shadow: 0 3px 8px rgba(37, 211, 102, 0.3);
        margin-bottom: 8px;
    }
    .whatsapp-btn:hover {
        background: #20ba59;
        transform: translateY(-1px);
        color: #ffffff;
    }
    @media (max-width: 850px) {
        .support-layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
            💬 Kituo cha Msaada na Huduma kwa Wateja
        </h1>
        <p style="color: #64748b; font-size: 0.95rem;">
            Wasiliana na timu yetu ya wataalamu saa 24/7 kwa ajili ya msaada wa haraka na ufuatiliaji wa oda.
        </p>
    </div>

    <div class="support-layout">
        <!-- Live Chat Card -->
        <div class="chat-card">
            <!-- Header yenye maandishi meupe yaliyo wazi kabisa -->
            <div class="chat-header">
                <div class="chat-status-dot"></div>
                <div>
                    <h3 class="chat-header-title">TanzaMart Live Assistant 🤖</h3>
                    <div class="chat-header-status">Inapatikana Moja kwa Moja (Online)</div>
                </div>
            </div>

            <div class="chat-body" id="chatContainer">
                <div class="msg-bubble msg-bot">
                    Habari! Karibu kwenye Dawati la Msaada la <strong>TanzaMart Tanzania</strong>. Una swali kuhusu malipo ya Escrow, kufuatilia oda yako, au msaada wa manunuzi? Niandikie hapa chini au piga simu namba <strong>0621 530 804</strong> / <strong>0657 276 380</strong> nikusaidie mara moja! 👋
                </div>

                @foreach($messages as $msg)
                    <div class="msg-bubble {{ $msg->sender_type === 'user' ? 'msg-user' : 'msg-bot' }}">
                        {{ $msg->message }}
                    </div>
                @endforeach
            </div>

            <form id="chatForm" class="chat-footer">
                @csrf
                <input type="text" id="chatMessage" class="chat-input" placeholder="Andika ujumbe wako hapa..." required autocomplete="off">
                <button type="submit" class="btn-send">
                    Tuma 🚀
                </button>
            </form>
        </div>

        <!-- Direct Contacts Sidebar -->
        <div>
            <div class="contact-card">
                <h3 style="font-size: 1.15rem; font-weight: 800; margin-bottom: 16px; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    📞 Njia Nyingine za Mawasiliano
                </h3>

                <!-- Namba za Simu za Moja kwa Moja zilizoombwa na Mtumiaji -->
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: #2563eb; margin-bottom: 8px; letter-spacing: 0.5px;">
                        SIMU YA MOJA KWA MOJA
                    </div>
                    
                    <a href="tel:0621530804" class="contact-phone-btn" title="Piga 0621530804">
                        <span>📞</span>
                        <span>0621 530 804</span>
                    </a>
                    
                    <a href="tel:0657276380" class="contact-phone-btn" title="Piga 0657276380">
                        <span>📞</span>
                        <span>0657 276 380</span>
                    </a>

                    <small style="color: #64748b; font-size: 0.82rem; margin-top: 4px; display: block;">
                        Inapatikana Jumatatu - Jumapili (Saa 24/7)
                    </small>
                </div>

                <!-- Vitufe vya WhatsApp kwa namba zote mbili -->
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: #2563eb; margin-bottom: 8px; letter-spacing: 0.5px;">
                        WHATSAPP SUPPORT
                    </div>
                    
                    <a href="https://wa.me/255621530804?text=Habari%20TanzaMart,%20naomba%20msaada" target="_blank" class="whatsapp-btn">
                        <span>🟢</span>
                        <span>Chat WhatsApp: 0621 530 804</span>
                    </a>

                    <a href="https://wa.me/255657276380?text=Habari%20TanzaMart,%20naomba%20msaada" target="_blank" class="whatsapp-btn" style="background:#128C7E;">
                        <span>🟢</span>
                        <span>Chat WhatsApp: 0657 276 380</span>
                    </a>
                </div>

                <div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; font-weight: 800; color: #2563eb; margin-bottom: 6px; letter-spacing: 0.5px;">
                        BARUA PEPE
                    </div>
                    <a href="mailto:support@tanzamart.co.tz" style="font-weight: 700; font-size: 0.95rem; color: #0f172a; text-decoration: none;">
                        support@tanzamart.co.tz
                    </a>
                </div>
            </div>

            <!-- Escrow Guarantee Box -->
            <div class="contact-card" style="background: #f0fdf4; border-color: #bbf7d0;">
                <h4 style="color: #15803d; font-size: 0.95rem; font-weight: 800; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    🛡️ Malipo Salama (Escrow)
                </h4>
                <p style="font-size: 0.85rem; color: #166534; line-height: 1.55; margin: 0;">
                    Kama umefanya malipo na mzigo wako umechelewa au haujafika, fedha zako ziko salama na zitarudishwa moja kwa moja kwenye namba yako ya simu.
                </p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const chatContainer = document.getElementById('chatContainer');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatMessage');

    chatContainer.scrollTop = chatContainer.scrollHeight;

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        // Append user bubble
        const userDiv = document.createElement('div');
        userDiv.className = 'msg-bubble msg-user';
        userDiv.textContent = text;
        chatContainer.appendChild(userDiv);
        chatInput.value = '';
        chatContainer.scrollTop = chatContainer.scrollHeight;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('{{ route("support.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ message: text })
            });
            const data = await res.json();
            if (data.reply) {
                setTimeout(() => {
                    const botDiv = document.createElement('div');
                    botDiv.className = 'msg-bubble msg-bot';
                    botDiv.textContent = data.reply;
                    chatContainer.appendChild(botDiv);
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }, 600);
            }
        } catch (err) {
            console.error(err);
        }
    });
</script>
@endpush
