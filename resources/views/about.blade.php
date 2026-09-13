@extends('layouts.app')

@section('title', app()->getLocale() == 'sw' ? 'Kuhusu Sisi - TanzaMart' : 'About Us - TanzaMart')

@push('styles')
<style>
    /* HERO BANNER */
    .about-hero {
        background: linear-gradient(135deg, #0b1329 0%, #0f172a 50%, #00363a 100%);
        color: white;
        padding: 70px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 3px solid #00bcd4;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0, 188, 212, 0.25) 0%, transparent 70%);
        border-radius: 50%;
    }

    .about-hero h1 {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 15px;
        letter-spacing: -0.5px;
    }

    .about-hero h1 span {
        color: #00bcd4;
    }

    .about-hero p {
        font-size: 19px;
        color: #cbd5e1;
        max-width: 750px;
        margin: 0 auto 25px;
        line-height: 1.6;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 50px 20px;
    }

    /* STATS STRIP */
    .stats-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: -85px;
        position: relative;
        z-index: 10;
        margin-bottom: 50px;
    }

    .stat-card {
        background: white;
        padding: 25px 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #00bcd4;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-num {
        font-size: 32px;
        font-weight: 800;
        color: #0b1329;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* SECTION TITLES */
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .section-header p {
        font-size: 16px;
        color: #64748b;
        max-width: 650px;
        margin: 0 auto;
    }

    /* THREE PILLARS (HOW IT WORKS) */
    .pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .pillar-card {
        background: white;
        border-radius: 14px;
        padding: 35px 28px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .pillar-card:hover {
        border-color: #00bcd4;
        box-shadow: 0 12px 30px rgba(0, 188, 212, 0.12);
        transform: translateY(-4px);
    }

    .pillar-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: rgba(0, 188, 212, 0.12);
        color: #00bcd4;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .pillar-card h3 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .pillar-card p {
        font-size: 15px;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .pillar-features {
        list-style: none;
        margin-top: auto;
    }

    .pillar-features li {
        font-size: 14px;
        color: #334155;
        padding: 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pillar-features li span {
        color: #00bcd4;
        font-weight: bold;
    }

    /* ESCROW BANNER */
    .escrow-banner {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 60px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        border: 1px solid rgba(0, 188, 212, 0.3);
    }

    .escrow-text {
        flex: 1;
        min-width: 300px;
    }

    .escrow-text h3 {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .escrow-text h3 span {
        color: #00bcd4;
    }

    .escrow-text p {
        font-size: 15px;
        color: #cbd5e1;
        line-height: 1.6;
    }

    .escrow-badge-box {
        background: rgba(0, 188, 212, 0.15);
        border: 2px dashed #00bcd4;
        border-radius: 12px;
        padding: 20px 25px;
        text-align: center;
    }

    .escrow-badge-box .icon {
        font-size: 36px;
        margin-bottom: 6px;
    }

    .escrow-badge-box .title {
        font-size: 16px;
        font-weight: 800;
        color: #00bcd4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* PAYMENT CHANNELS */
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 60px;
    }

    .payment-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        transition: 0.3s;
    }

    .payment-card:hover {
        border-color: #00bcd4;
        transform: translateY(-4px);
    }

    .payment-card .pay-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .payment-card h4 {
        font-size: 18px;
        color: #0f172a;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .payment-card p {
        font-size: 13px;
        color: #64748b;
    }

    /* CTA STRIP */
    .cta-banner {
        background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
        color: white;
        border-radius: 16px;
        padding: 45px 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 188, 212, 0.3);
    }

    .cta-banner h2 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .cta-banner p {
        font-size: 17px;
        max-width: 600px;
        margin: 0 auto 25px;
        opacity: 0.95;
    }

    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .cta-btn-white {
        background: #ffffff;
        color: #0097a7;
        padding: 14px 28px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        transition: 0.3s;
    }

    .cta-btn-white:hover {
        background: #0f172a;
        color: #ffffff;
    }

    .cta-btn-dark {
        background: #0f172a;
        color: #ffffff;
        padding: 14px 28px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        transition: 0.3s;
    }

    .cta-btn-dark:hover {
        background: #1e293b;
    }

    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 30px;
        }
        .about-hero p {
            font-size: 16px;
        }
        .stats-strip {
            margin-top: -40px;
        }
    }
</style>
@endpush

@section('content')

    <!-- HERO SECTION -->
    <div class="about-hero">
        <h1>{{ app()->getLocale() == 'sw' ? 'Kuhusu' : 'About' }} <span>TanzaMart</span></h1>
        <p>
            {{ app()->getLocale() == 'sw' 
                ? 'Soko kuu la kidijitali lililojengwa kwa ajili ya Watanzania: Kuunganisha wauzaji wa ndani, bidhaa za ubora, malipo salama ya kielektroniki, na usafirishaji wa haraka nchi nzima.' 
                : 'Tanzania’s premier online marketplace designed for modern commerce: Empowering local merchants, verified high-quality products, secure mobile payments, and fast countrywide delivery.' }}
        </p>
    </div>

    <div class="about-container">

        <!-- STATS STRIP -->
        <div class="stats-strip">
            <div class="stat-card">
                <div class="stat-num">100%</div>
                <div class="stat-label">{{ app()->getLocale() == 'sw' ? 'Malipo Salama (Escrow)' : 'Secure Escrow Payments' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">31</div>
                <div class="stat-label">{{ app()->getLocale() == 'sw' ? 'Mikoa Inayofikiwa TZ' : 'Tanzania Regions Covered' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">24/7</div>
                <div class="stat-label">{{ app()->getLocale() == 'sw' ? 'Msaada kwa Wateja' : 'Customer Support' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ $productsCount ?? '100+' }}+</div>
                <div class="stat-label">{{ app()->getLocale() == 'sw' ? 'Bidhaa Zilizopo Stoki' : 'Active Products in Stock' }}</div>
            </div>
        </div>

        <!-- HOW TANZAMART WORKS (THE THREE PILLARS) -->
        <div class="section-header">
            <h2>{{ app()->getLocale() == 'sw' ? 'Jinsi Mfumo wa TanzaMart Unavyofanya Kazi' : 'How the TanzaMart Ecosystem Works' }}</h2>
            <p>
                {{ app()->getLocale() == 'sw' 
                    ? 'Tumejenga mfumo imara unaowalinda wateja, kuwainua wafanyabiashara, na kurahisisha ununuzi wa mtandaoni.' 
                    : 'We built a secure, transparent platform engineered to protect buyers, empower entrepreneurs, and simplify digital shopping.' }}
            </p>
        </div>

        <div class="pillars-grid">
            
            <!-- Pillar 1: Wanunuzi / Customers -->
            <div class="pillar-card">
                <div>
                    <div class="pillar-icon">🛍️</div>
                    <h3>{{ app()->getLocale() == 'sw' ? '1. Kwa Wanunuzi (Wateja)' : '1. For Online Buyers' }}</h3>
                    <p>
                        {{ app()->getLocale() == 'sw'
                            ? 'Mteja anaweza kuvinjari bidhaa kwa kategoria, kuongeza kwenye kikapu, na kufanya malipo salama. Kila oda inapewa namba ya ufuatiliaji (Tracking ID) ili kujua eneo mzigo ulipo muda wowote.'
                            : 'Customers easily search, compare and add products to cart. Every purchase includes real-time order tracking and downloadable official receipts.' }}
                    </p>
                </div>
                <ul class="pillar-features">
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Ufuatiliaji wa Oda kwa Wakati Halisi (Live Tracking)' : 'Live Real-time Order Tracking' }}</li>
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Stakabadhi Rasmi ya Kielektroniki (E-Receipt)' : 'Digital Invoices & Printable Receipts' }}</li>
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Dhamana ya Kurudishiwa Pesa iwapo mzigo una kasoro' : 'Money-back guarantee via Dispute Portal' }}</li>
                </ul>
            </div>

            <!-- Pillar 2: Wauzaji / Vendors -->
            <div class="pillar-card">
                <div>
                    <div class="pillar-icon">🏪</div>
                    <h3>{{ app()->getLocale() == 'sw' ? '2. Kwa Wauzaji (Maduka)' : '2. For Vendors & Stores' }}</h3>
                    <p>
                        {{ app()->getLocale() == 'sw'
                            ? 'Mfanyabiashara yeyote anaweza kufungua akaunti ya duka (Vendor), kupakia bidhaa zake zenye picha, bei na idadi ya stoki, na kuanza kuuza kwa wateja kote Tanzania.'
                            : 'Merchants register custom stores, list products with photos and inventory controls, and fulfill orders directly across the country.' }}
                    </p>
                </div>
                <ul class="pillar-features">
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Dashibodi Maalum ya Muuzaji (Vendor Portal)' : 'Dedicated Vendor Dashboard & Analytics' }}</li>
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Usimamizi wa Mauzo na Oda za Wateja' : 'Direct Order Management & Notifications' }}</li>
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Kutoa Fedha za Mauzo moja kwa moja kwenda M-Pesa / Benki' : 'Direct Payouts to M-Pesa, TigoPesa & Banks' }}</li>
                </ul>
            </div>

            <!-- Pillar 3: Usimamizi / Admin -->
            <div class="pillar-card">
                <div>
                    <div class="pillar-icon">⚡</div>
                    <h3>{{ app()->getLocale() == 'sw' ? '3. Usimamizi Mkuu (Admin)' : '3. Admin & Escrow Control' }}</h3>
                    <p>
                        {{ app()->getLocale() == 'sw'
                            ? 'Wasimamizi wa mfumo wanakagua usalama wa bidhaa, wanathibitisha wauzaji halisi, na kusimamia amana za malipo (Escrow) ili kuhakikisha haki inatendeka kwa pande zote mbili.'
                            : 'Platform administrators oversee store compliance, verify vendor accounts, manage escrow deposits, and resolve consumer disputes swiftly.' }}
                    </p>
                </div>
                <ul class="pillar-features">
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Ukaguzi wa Ubora na Usalama wa Bidhaa' : 'Strict quality control & vendor verification' }}</li>
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Mfumo wa Usuluhishi wa Migogoro (Dispute Resolution)' : 'Neutral mediation & dispute settlement' }}</li>
                    <li><span>✔</span> {{ app()->getLocale() == 'sw' ? 'Takwimu za Uuzaji na Ufuatiliaji wa Mfumo' : 'Financial reporting & system health metrics' }}</li>
                </ul>
            </div>

        </div>

        <!-- ESCROW BUYER PROTECTION BANNER -->
        <div class="escrow-banner">
            <div class="escrow-text">
                <h3>🛡️ <span>{{ app()->getLocale() == 'sw' ? 'Mfumo wa Escrow (Ulinzi wa 100% wa Fedha Zako)' : '100% Escrow Buyer Protection' }}</span></h3>
                <p>
                    {{ app()->getLocale() == 'sw'
                        ? 'Unapolipa bidhaa kwenye TanzaMart, fedha zako HAZIENDI moja kwa moja kwa muuzaji. TanzaMart inazishikilia salama kwenye mfumo maalum (Escrow). Muuzaji analipwa tu baada ya wewe mteja kupokea mzigo wako, kuukagua, na kubonyeza "Thibitisha Kupokea Mzigo". Ukikuta tatizo, unafungua mgogoro na kurudishiwa fedha zako mara moja.'
                        : 'When you purchase on TanzaMart, your money does NOT go directly to the vendor. Funds are safely held in TanzaMart Escrow. The merchant is only released the funds AFTER you safely receive and verify your delivery. If an item is damaged or missing, open a dispute for an instant refund.' }}
                </p>
            </div>
            <div class="escrow-badge-box">
                <div class="icon">🔒</div>
                <div class="title">{{ app()->getLocale() == 'sw' ? 'Dhamana Salama' : 'Verified Secure' }}</div>
            </div>
        </div>

        <!-- PAYMENT CHANNELS -->
        <div class="section-header">
            <h2>{{ app()->getLocale() == 'sw' ? 'Njia za Malipo Zinazokubaliwa' : 'Supported Payment Channels' }}</h2>
            <p>{{ app()->getLocale() == 'sw' ? 'Lipa kwa urahisi ukitumia mitandao yote ya simu au benki maarufu nchini Tanzania:' : 'Pay smoothly using all top Tanzanian mobile wallets or local banks:' }}</p>
        </div>

        <div class="payment-grid">
            <div class="payment-card">
                <div class="pay-icon">📱</div>
                <h4>M-Pesa / TigoPesa / Airtel</h4>
                <p>{{ app()->getLocale() == 'sw' ? 'Lipa Namba ya Mtandao wowote wa Simu (Vodacom, Tigo, Airtel, Halotel)' : 'Mobile Money Lipa Namba across all mobile providers' }}</p>
            </div>
            <div class="payment-card">
                <div class="pay-icon">🏦</div>
                <h4>CRDB Bank & NMB</h4>
                <p>{{ app()->getLocale() == 'sw' ? 'Malipo ya Benki moja kwa moja kupitia akaunti zetu zilizothibitishwa' : 'Direct bank transfers to official verified company accounts' }}</p>
            </div>
            <div class="payment-card">
                <div class="pay-icon">🧾</div>
                <h4>{{ app()->getLocale() == 'sw' ? 'Risiti ya Kielektroniki' : 'Instant E-Receipt' }}</h4>
                <p>{{ app()->getLocale() == 'sw' ? 'Uthibitisho wa papo hapo wenye Transaction ID kwa kila ununuzi' : 'Instant order confirmation and unique Transaction ID' }}</p>
            </div>
            <div class="payment-card">
                <div class="pay-icon">🚚</div>
                <h4>{{ app()->getLocale() == 'sw' ? 'Uwasilishaji Nchi Nzima' : 'Countrywide Shipping' }}</h4>
                <p>{{ app()->getLocale() == 'sw' ? 'Usafirishaji wa kuaminika mikoa yote ya Tanzania Bara na Visiwani' : 'Reliable delivery across all 31 regions of Tanzania' }}</p>
            </div>
        </div>

        <!-- CALL TO ACTION -->
        <div class="cta-banner">
            <h2>{{ app()->getLocale() == 'sw' ? 'Uko Tayari Kuanza na TanzaMart?' : 'Ready to Experience TanzaMart?' }}</h2>
            <p>
                {{ app()->getLocale() == 'sw'
                    ? 'Jiunge na maelfu ya wanunuzi na wauzaji wanaoamini TanzaMart kwa ununuzi wa uhakika na salama.'
                    : 'Join thousands of Tanzanian buyers and merchants doing smart, secure, and modern digital business.' }}
            </p>
            <div class="cta-buttons">
                <a href="{{ route('shop.products') }}" class="cta-btn-white">🛍️ {{ app()->getLocale() == 'sw' ? 'Vinjari Bidhaa Zote' : 'Browse All Products' }}</a>
                @guest
                    <a href="{{ route('register') }}" class="cta-btn-dark">📝 {{ app()->getLocale() == 'sw' ? 'Fungua Akaunti Bure' : 'Register Free Account' }}</a>
                @endguest
                <a href="{{ route('support.index') }}" class="cta-btn-dark">📞 {{ app()->getLocale() == 'sw' ? 'Mawasiliano & Msaada' : 'Contact Support' }}</a>
            </div>
        </div>

    </div>

@endsection
