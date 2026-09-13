<!-- TANZAMART MULTI-LANGUAGE & DATABASE CHATBOT -->
<style>
#tm-bot-button {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 55px;
    height: 55px;
    background: #2563eb;
    color: #fff;
    border-radius: 50%;
    border: none;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    z-index: 99999;
    transition: transform 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

#tm-bot-button:hover {
    transform: scale(1.08);
}

#tm-bot-window {
    position: fixed;
    bottom: 90px;
    right: 25px;
    width: 340px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    display: none;
    flex-direction: column;
    z-index: 99999;
    overflow: hidden;
    border: 1px solid #ddd;
    font-family: Arial, sans-serif;
}

.tm-bot-header {
    background: #2563eb;
    color: white;
    padding: 12px 15px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tm-bot-messages {
    height: 270px;
    overflow-y: auto;
    padding: 12px;
    background: #f9f9f9;
    display: flex;
    flex-direction: column;
}

.tm-msg {
    margin-bottom: 10px;
    max-width: 80%;
    padding: 8px 12px;
    font-size: 13px;
    line-height: 1.4;
    border-radius: 10px;
    word-wrap: break-word;
}

.tm-incoming {
    background: #e4e6eb;
    color: #000;
    align-self: flex-start;
    border-bottom-left-radius: 2px;
}

.tm-outgoing {
    background: #2563eb;
    color: #fff;
    margin-left: auto;
    border-bottom-right-radius: 2px;
}

.tm-bot-input {
    display: flex;
    padding: 8px;
    background: #fff;
    border-top: 1px solid #eee;
}

.tm-bot-input input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
    font-size: 13px;
}

.tm-bot-input button {
    margin-left: 5px;
    background: #2563eb;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.tm-lang-opt {
    margin-top: 6px;
    display: flex;
    gap: 5px;
}

.tm-lang-btn {
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.tm-lang-btn:hover {
    background: #008ba3;
}
</style>

<button id="tm-bot-button" onclick="toggleTanzaBot()" title="Chat with TanzaMart">💬</button>

<div id="tm-bot-window">
    <div class="tm-bot-header">
        <span>🤖 TanzaMart Assistant</span>
        <span style="cursor:pointer;" onclick="toggleTanzaBot()">✖</span>
    </div>
    
    <div class="tm-bot-messages" id="tmBotLogs">
        <!-- Welcoming Message -->
        <div class="tm-msg tm-incoming" id="tmWelcomeMsg">
            <span id="tmWelcomeText">{{ app()->getLocale() === 'en' ? 'Hello! 👋 Welcome to TanzaMart. How can I help you today?' : 'Habari! 👋 Karibu TanzaMart. Nikusaidie nini leo?' }}</span>
            <div class="tm-lang-opt">
                <button class="tm-lang-btn" onclick="setTanzaBotLanguage('en')">🇬🇧 English</button>
                <button class="tm-lang-btn" onclick="setTanzaBotLanguage('sw')">🇹🇿 Swahili</button>
            </div>
        </div>
    </div>

    <div class="tm-bot-input">
        <input type="text" id="tmUserInput" placeholder="{{ app()->getLocale() === 'en' ? 'Type in any language...' : 'Andika kwa lugha yoyote...' }}" onkeypress="if(event.key==='Enter') sendTanzaMsg()">
        <button onclick="sendTanzaMsg()">{{ app()->getLocale() === 'en' ? 'Send' : 'Tuma' }}</button>
    </div>
</div>

<script>
let currentBotLanguage = '{{ app()->getLocale() }}' || 'sw';

function toggleTanzaBot() {
    var win = document.getElementById('tm-bot-window');
    win.style.display = (win.style.display === 'flex') ? 'none' : 'flex';
}

function setTanzaBotLanguage(lang) {
    currentBotLanguage = lang;
    let welcomeElem = document.getElementById('tmWelcomeText');
    let inputElem = document.getElementById('tmUserInput');

    if (lang === 'en') {
        welcomeElem.innerText = "Hello! 👋 Welcome to TanzaMart. How can I help you today?";
        inputElem.placeholder = "Type in any language...";
    } else {
        welcomeElem.innerText = "Habari! 👋 Karibu TanzaMart. Nikusaidie nini leo?";
        inputElem.placeholder = "Andika kwa lugha yoyote...";
    }
}

async function sendTanzaMsg() {
    var input = document.getElementById('tmUserInput');
    var txt = input.value.trim();
    if(!txt) return;

    addTanzaBubble(txt, 'tm-outgoing');
    input.value = '';

    var loadingId = 'loading-' + Date.now();
    addTanzaBubble('✍️ ...', 'tm-incoming', loadingId);

    try {
        let cleanText = txt.toLowerCase().trim();

        let directThanks = ['ok', 'okay', 'asante', 'thanks', 'thank you', 'bye', 'kwaheri', 'see you'];
        let isDirectMatch = directThanks.includes(cleanText);

        let translatedQuestion = txt;
        let detectedLang = currentBotLanguage;

        if (!isDirectMatch) {
            try {
                let translateUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=sw&dt=t&q=${encodeURIComponent(txt)}`;
                let response = await fetch(translateUrl);
                if (response.ok) {
                    let data = await response.json();
                    translatedQuestion = data[0][0][0];
                    detectedLang = data[2] || currentBotLanguage;
                }
            } catch (err) {
                console.warn("Translation API bypassed:", err);
            }
        }

        let dbData = { found: false };
        
        if (!isDirectMatch && txt.trim().length >= 3) {
            try {
                let dbResponse = await fetch(`{{ url('/get_product.php') }}?query=${encodeURIComponent(txt)}`);
                if (dbResponse.ok) {
                    dbData = await dbResponse.json();
                }
            } catch (err) {
                console.warn("get_product fetch failed:", err);
            }
        }

        let swahiliReply = "";
        let isDefaultReply = false;
        let isThanks = false;

        if (dbData && dbData.found) {
            swahiliReply = `Bidhaa ya ${dbData.name} inauzwa Tsh ${dbData.price}.`;
        } else {
            let replyObj = getTanzaReply(translatedQuestion, txt);
            swahiliReply = replyObj.text;
            isDefaultReply = replyObj.isDefault;
            isThanks = replyObj.isThanks || false;
        }

        let targetLang = (currentBotLanguage === 'en' && detectedLang === 'sw') ? 'en' : detectedLang;
        let finalReply = swahiliReply;
        
        if (isThanks) {
            finalReply = (targetLang === 'en') ? "Thank you for contacting us!" : "Asante kwa kuwasiliana nasi!";
        } else if (isDefaultReply) {
            if (targetLang === 'en') {
                finalReply = "Thank you for contacting us! For quicker assistance, please call: 0621 530 804 / 0657 276 380 or email: support@tanzamart.co.tz.";
            } else if (targetLang !== 'sw') {
                try {
                    let replyUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=sw&tl=${targetLang}&dt=t&q=${encodeURIComponent(swahiliReply)}`;
                    let replyResponse = await fetch(replyUrl);
                    if (replyResponse.ok) {
                        let replyData = await replyResponse.json();
                        finalReply = replyData[0][0][0];
                    }
                } catch (e) {}
            }
        } else {
            if (targetLang !== 'sw') {
                try {
                    let replyUrl = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=sw&tl=${targetLang}&dt=t&q=${encodeURIComponent(swahiliReply)}`;
                    let replyResponse = await fetch(replyUrl);
                    if (replyResponse.ok) {
                        let replyData = await replyResponse.json();
                        finalReply = replyData[0][0][0];
                    }
                } catch (e) {}
            }
        }

        removeBubble(loadingId);
        addTanzaBubble(finalReply, 'tm-incoming');

    } catch (error) {
        removeBubble(loadingId);
        let fallback = (currentBotLanguage === 'en') 
            ? "Thank you for contacting us! For quicker assistance, please call: 0621 530 804 / 0657 276 380 or email: support@tanzamart.co.tz."
            : "Asante kwa kuwasiliana nasi! Kwa msaada wa haraka zaidi, piga simu: 0621 530 804 au 0657 276 380 au email: support@tanzamart.co.tz.";
        addTanzaBubble(fallback, 'tm-incoming');
    }
}

function addTanzaBubble(msg, type, elementId = null) {
    var logs = document.getElementById('tmBotLogs');
    var div = document.createElement('div');
    div.className = 'tm-msg ' + type;
    if(elementId) div.id = elementId;
    div.innerText = msg;
    logs.appendChild(div);
    logs.scrollTop = logs.scrollHeight;
}

function removeBubble(elementId) {
    var elem = document.getElementById(elementId);
    if(elem) elem.remove();
}

function getTanzaReply(text, rawText = '') {
    var t = text.toLowerCase().trim();
    var raw = rawText.toLowerCase().trim();
    
    if (raw === 'ok' || raw === 'okay' || t === 'ok' || t === 'okay' || 
        t.includes('asante') || t.includes('shukrani') || t.includes('thank') || 
        t.includes('thanks') || t.includes('bye') || t.includes('kwaheri') || t.includes('see you')) {
        return { 
            text: "Asante kwa kuwasiliana nasi!", 
            isThanks: true, 
            isDefault: false 
        };
    }

    if (t.includes('orodha') || t.includes('list') || t.includes('bidhaa zote') || t.includes('all products') || t.includes('phones available') || t.includes('ngapi zipo') || t.includes('catalog')) {
        return { text: "Unaweza kuona orodha kamili ya bidhaa zote zilizopo stoki pamoja na bei zao kupitia ukurasa wetu wa 'Products' au 'Shop' kwenye menu kuu.", isDefault: false };
    }

    if (t.includes('ingia') || t.includes('login') || t.includes('akaunti') || t.includes('account')) {
        return { text: "Ili kuingia kwenye akaunti yako, bofya kitufe cha 'Login' kilicho juu kulia mwa tovuti, kisha weka Email na Password yako.", isDefault: false };
    }

    if (t.includes('toka') || t.includes('logout') || t.includes('log out') || t.includes('ondoka')) {
        return { text: "Ili kutoka kwenye akaunti yako, bofya 'Logout' iliyopo juu kulia kwenye menu.", isDefault: false };
    }

    if (t.includes('sajili') || t.includes('register') || t.includes('fungua akaunti') || t.includes('signup') || t.includes('jiandikisha') || t.includes('jiandikishe')) {
        return { text: "Ili kufungua akaunti mpya, bofya 'Register' au 'Sajili' juu kulia, kisha ujaze taarifa zako (Jina, Email, na Password).", isDefault: false };
    }

    if (t.includes('cart') || t.includes('kikapu') || t.includes('ongeza') || t.includes('add to cart') || t.includes('nunua')) {
        return { text: "Ili kuweka bidhaa kwenye kikapu, chagua bidhaa unayoipenda kisha ubofye kitufe cha 'Add to Cart'. Ukimaliza unaweza kwenda kwenye Cart ili kuendelea na malipo.", isDefault: false };
    }

    if (t.includes('malipo') || t.includes('lipa') || t.includes('mpesa') || t.includes('tigo') || t.includes('pesa') || t.includes('payment') || t.includes('pay') || t.includes('crdb') || t.includes('bank')) {
        return { text: "Tunapokea malipo kwa Lipa Namba (Merchant Code): 5521400 (TanzaMart Ltd - Vodacom, Tigo, Airtel, Halotel) au Benki ya CRDB (Akaunti: 0152839120300). Ukishalipa, weka Transaction ID kwenye ukurasa wa Checkout kisha thibitisha oda.", isDefault: false };
    }

    if (t.includes('oda') || t.includes('fuatilia') || t.includes('mzigo') || t.includes('order') || t.includes('track') || t.includes('my orders')) {
        return { text: "Unaweza kufuatilia hali ya oda yako au kuona oda zako za nyuma kupitia sehemu ya 'My Orders' au menu ya 'Track Order' pindi unapokuwa umeingia kwenye akaunti yako.", isDefault: false };
    }

    if (t.includes('delivery') || t.includes('safirisha') || t.includes('usafiri') || t.includes('ship')) {
        return { text: "Tunasafirisha bidhaa mikoa yote ya Tanzania na nje ya nchi. Gharama na muda wa usafiri unategemea na eneo ulipo.", isDefault: false };
    }

    if (t.includes('wapi') || t.includes('mahali') || t.includes('ofisi') || t.includes('anwani') || t.includes('location')) {
        return { text: "Ofisi zetu kuu zipo Dar es Salaam, Tanzania. Pia tunafanya delivery ya bidhaa nchi nzima!", isDefault: false };
    }

    if (t.includes('habari') || t.includes('mambo') || t.includes('salamu') || t.includes('hello') || t.includes('hi')) {
        return { text: "Salama kabisa! 👋 Karibu TanzaMart. Nikusaidie nini leo?", isDefault: false };
    }

    return { 
        text: "Asante kwa kuwasiliana nasi! Kwa msaada wa haraka zaidi, piga simu: 0621 530 804 au 0657 276 380 au email: support@tanzamart.co.tz.", 
        isDefault: true 
    };
}
</script>
