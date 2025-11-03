  {{-- ChatBot Floating (paste before </body> in resources/views/vendor/filament/components/layouts/app.blade.php) --}}
    <div id="filament-global-chatbot" style="position:fixed; bottom:20px; right:20px; z-index:99999;" aria-hidden="false">
        <style>
            *{
                font-family: Cairo;
            }
            /* محددات محلية لتفادي التضارب */
            #filament-global-chatbot * { box-sizing: border-box; }
            #filament-global-chatbot .fgc-btn {
                background:#2563eb; width:60px; height:60px; border-radius:50%; border:none; display:flex;
                align-items:center; justify-content:center; cursor:pointer; box-shadow:0 6px 18px rgba(0,0,0,0.25);
                transition:transform .12s ease;
            }
            #filament-global-chatbot .fgc-btn:hover{ transform:scale(1.05); }
            #filament-global-chatbot .fgc-modal {
                position:fixed; bottom:90px; right:20px; width:340px; max-height:480px; background:#fff; border-radius:12px;
                box-shadow:0 10px 30px rgba(2,6,23,0.25); padding:12px; transform:translateY(10px) scale(.96);
                opacity:0; pointer-events:none; transition:all .18s ease; display:flex; flex-direction:column; overflow:hidden;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            }
            #filament-global-chatbot .fgc-modal.open {
                transform:translateY(0) scale(1); opacity:1; pointer-events:auto;
            }
            #filament-global-chatbot .fgc-header { font-weight:600; color:#0f172a; margin-bottom:6px; }
            #filament-global-chatbot .fgc-sub { display:block; font-weight:400; font-size:12px; color:#6b7280; margin-bottom:8px; }
            #filament-global-chatbot .fgc-messages { flex:1; overflow:auto; padding-right:6px; margin-bottom:8px; }
            #filament-global-chatbot .fgc-messages .msg { background:#f3f4f6; padding:8px 10px; border-radius:8px; margin-bottom:6px; max-width:80%; font-size:13px; }
            #filament-global-chatbot .fgc-messages .msg.user { background:#2563eb; color:white; margin-left:auto; }
            #filament-global-chatbot .fgc-input { display:flex; gap:8px; align-items:center; }
            #filament-global-chatbot .fgc-input input { flex:1; padding:8px; border-radius:8px; border:1px solid #e6e9ef; font-size:13px; }
            #filament-global-chatbot .fgc-input button { padding:8px 12px; border-radius:8px; border:none; background:#2563eb; color:#fff; cursor:pointer; }
        </style>

        <button id="fgc-toggle" class="fgc-btn" aria-label="Open chat">
            {{-- استخدم أي أيقونة أو صورة موجودة لديك --}}
            <img src="{{ asset('images/ChatBot.png') }}" alt="chat" style="width:38px; height:38px; border-radius:50%;">
        </button>

        <div id="fgc-modal" class="fgc-modal" role="dialog" aria-modal="false" aria-hidden="true">
            <div class="fgc-header">CareBot <span class="fgc-sub">رفيقك الطبي الذكي — نصائح صحية سريعة</span></div>

            <div id="fgc-messages" class="fgc-messages" aria-live="polite">
                <div class="msg" style="color: #4e4e4e;">مرحباً! كيف يمكنني مساعدتك اليوم؟</div>
            </div>

            <form id="fgc-form" class="fgc-input" onsubmit="return false;">
                <input id="fgc-input" type="text" placeholder="اكتب رسالتك..." autocomplete="off" style="color: #0f172a;"/>
                <button id="fgc-send" type="button" wire:click="">إرسال</button>
            </form>
        </div>

        <script>
            (function(){
                // عناصر
                const toggle = document.getElementById('fgc-toggle');
                const modal = document.getElementById('fgc-modal');
                const form = document.getElementById('fgc-form');
                const input = document.getElementById('fgc-input');
                const messages = document.getElementById('fgc-messages');

                // ردود تجريبية
                const replies = [
                    "أكيد — سأتابع معك نصائح اليوم.",
                    "تذكّر شرب الماء كل ساعة.",
                    "نقترح تمارين تنفّس لمدة 5 دقائق.",
                    "هل تريد نصيحة غذائية؟",
                    "تأكد من النوم 7-8 ساعات."
                ];

                // فتح/إغلاق
                let open = false;
                toggle.addEventListener('click', function(e){
                    e.preventDefault();
                    open = !open;
                    if(open){
                        modal.classList.add('open');
                        modal.setAttribute('aria-hidden','false');
                        modal.setAttribute('aria-modal','true');
                        input.focus();
                    } else {
                        modal.classList.remove('open');
                        modal.setAttribute('aria-hidden','true');
                        modal.setAttribute('aria-modal','false');
                    }
                });

                // إرسال رسالة
                function appendMessage(text, isUser = false){
                    const d = document.createElement('div');
                    d.className = 'msg' + (isUser ? ' user' : '');
                    d.textContent = text;
                    d.style.color = '#4e4e4e';
                    messages.appendChild(d);
                    messages.scrollTop = messages.scrollHeight;
                }

                document.getElementById('fgc-send').addEventListener('click', function(){
                    const v = input.value.trim();
                    if(!v) return;
                    appendMessage(v, true);
                    input.value = '';
                    setTimeout(function(){
                        const r = replies[Math.floor(Math.random()*replies.length)];
                        appendMessage(r, false);
                    }, 900);
                });

                // Enter لإرسال
                input.addEventListener('keydown', function(e){
                    if(e.key === 'Enter'){
                        e.preventDefault();
                        document.getElementById('fgc-send').click();
                    }
                });

                // تأكد أن العنصر يبقى دائماً خارج Livewire (بعض صفحات Filament يعيدون جزء فقط من الصفحة)
                // لذلك نستخدم عنصر ثابت في القالب الأساسي (تم وضعه هنا).
                // تصحيح أخطاء: إن لم يعمل افتح Console في المتصفح وراجع أي أخطاء.
            })();
        </script>
    </div>

      {{-- ChatBot Floating (paste before </body> in resources/views/vendor/filament/components/layouts/app.blade.php) --}}
{{-- <div id="filament-global-chatbot" style="position:fixed; bottom:20px; right:20px; z-index:99999;" wire:ignore.self>
    <style>
        #filament-global-chatbot * { box-sizing: border-box; }
        .fgc-btn {
            background:#2563eb; width:60px; height:60px; border-radius:50%; border:none; display:flex;
            align-items:center; justify-content:center; cursor:pointer; box-shadow:0 6px 18px rgba(0,0,0,0.25);
            transition:transform .12s ease;
        }
        .fgc-btn:hover{ transform:scale(1.05); }
        .fgc-modal {
            position:fixed; bottom:90px; right:20px; width:340px; max-height:480px; background:#fff; border-radius:12px;
            box-shadow:0 10px 30px rgba(2,6,23,0.25); padding:12px; transform:translateY(10px) scale(.96);
            opacity:0; pointer-events:none; transition:all .18s ease; display:flex; flex-direction:column; overflow:hidden;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        .fgc-modal.open { transform:translateY(0) scale(1); opacity:1; pointer-events:auto; }
        .fgc-header { font-weight:600; color:#0f172a; margin-bottom:6px; }
        .fgc-sub { display:block; font-weight:400; font-size:12px; color:#6b7280; margin-bottom:8px; }
        .fgc-messages { flex:1; overflow:auto; padding-right:6px; margin-bottom:8px; }
        .fgc-messages .msg { background:#f3f4f6; padding:8px 10px; border-radius:8px; margin-bottom:6px; max-width:80%; font-size:13px; }
        .fgc-messages .msg.user { background:#2563eb; color:white; margin-left:auto; }
        .fgc-input { display:flex; gap:8px; align-items:center; }
        .fgc-input input { flex:1; padding:8px; border-radius:8px; border:1px solid #e6e9ef; font-size:13px; }
        .fgc-input button { padding:8px 12px; border-radius:8px; border:none; background:#2563eb; color:#fff; cursor:pointer; }
    </style>

    <button id="fgc-toggle" class="fgc-btn" aria-label="Open chat">
        <img src="{{ asset('images/ChatBot.png') }}" alt="chat" style="width:38px; height:38px; border-radius:50%;">
    </button>

    <div id="fgc-modal" class="fgc-modal" x-data="{ open:false }" x-bind:class="{ 'open': open }">
        <div class="fgc-header">
            CareBot <span class="fgc-sub">رفيقك الطبي الذكي — نصائح صحية سريعة</span>
        </div>

        <div class="fgc-messages" id="fgc-messages">
            <div class="msg">مرحباً! كيف يمكنني مساعدتك اليوم؟</div>
            @foreach ($messages as $msg)
                <div class="msg {{ $msg['user'] ? 'user' : '' }}">{{ $msg['text'] }}</div>
            @endforeach
        </div>

        <form wire:submit.prevent="sendMessage" class="fgc-input">
            <input type="text" wire:model="input" placeholder="اكتب رسالتك..." autocomplete="off" />
            <button type="submit">إرسال</button>
        </form>
    </div>

    <script>
        document.getElementById('fgc-toggle').addEventListener('click', function(){
            const modal = document.getElementById('fgc-modal');
            modal.classList.toggle('open');
        });
    </script>
</div> --}}

