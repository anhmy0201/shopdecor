{{--
    ShopDecor Chatbot Widget
    Nhúng vào layouts/app.blade.php trước thẻ </body>:
        @include('chatbot.widget')
--}}

<style>
/* ── Floating Button ── */
#chatbot-toggle {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #1a5276;
    color: #fff;
    border: none;
    box-shadow: 0 4px 16px rgba(26,82,118,.45);
    cursor: pointer;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: background .2s, transform .2s;
}
#chatbot-toggle:hover { background: #154360; transform: scale(1.08); }
#chatbot-toggle .badge-dot {
    position: absolute;
    top: 6px; right: 6px;
    width: 11px; height: 11px;
    background: #e74c3c;
    border-radius: 50%;
    border: 2px solid #fff;
    display: none;
}

/* ── Chat Box ── */
#chatbot-box {
    position: fixed;
    bottom: 96px;
    right: 28px;
    width: 360px;
    max-height: 520px;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    background: #fff;
    display: none;
    flex-direction: column;
    z-index: 9998;
    overflow: hidden;
    font-family: Arial, sans-serif;
    font-size: 0.88rem;
}
#chatbot-box.open { display: flex; }

/* Header */
#chatbot-header {
    background: #1a5276;
    color: #fff;
    padding: 13px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
#chatbot-header .avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #e74c3c;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
#chatbot-header .info .name { font-weight: 700; font-size: 0.92rem; }
#chatbot-header .info .status { font-size: 0.75rem; color: #afd6f5; }
#chatbot-close {
    margin-left: auto;
    background: none; border: none; color: #fff;
    font-size: 1.2rem; cursor: pointer; padding: 0 4px;
    line-height: 1;
}

/* Messages */
#chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #f5f7fa;
}
.cb-msg {
    max-width: 82%;
    padding: 9px 13px;
    border-radius: 14px;
    line-height: 1.5;
    word-break: break-word;
    white-space: pre-wrap;
}
.cb-msg.bot {
    background: #fff;
    border: 1px solid #e0e0e0;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
}
.cb-msg.user {
    background: #1a5276;
    color: #fff;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}
.cb-typing {
    align-self: flex-start;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 14px;
    border-bottom-left-radius: 4px;
    padding: 10px 14px;
    display: none;
}
.cb-typing span {
    display: inline-block;
    width: 7px; height: 7px;
    background: #1a5276;
    border-radius: 50%;
    margin: 0 2px;
    animation: bounce 1.2s infinite;
}
.cb-typing span:nth-child(2) { animation-delay: .2s; }
.cb-typing span:nth-child(3) { animation-delay: .4s; }
@keyframes bounce {
    0%, 80%, 100% { transform: translateY(0); }
    40%           { transform: translateY(-6px); }
}

/* Input */
#chatbot-footer {
    padding: 10px 12px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 8px;
    background: #fff;
    flex-shrink: 0;
}
#chatbot-input {
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.88rem;
    outline: none;
    resize: none;
    max-height: 80px;
    font-family: Arial, sans-serif;
}
#chatbot-input:focus { border-color: #1a5276; }
#chatbot-send {
    background: #1a5276;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0 14px;
    cursor: pointer;
    font-size: 1rem;
    transition: background .2s;
    flex-shrink: 0;
}
#chatbot-send:hover { background: #154360; }
#chatbot-send:disabled { background: #aaa; cursor: not-allowed; }

/* Quick replies */
.cb-quick-replies {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 0 12px 10px;
    background: #fff;
}
.cb-quick-btn {
    border: 1px solid #1a5276;
    color: #1a5276;
    background: #fff;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.78rem;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}
.cb-quick-btn:hover { background: #1a5276; color: #fff; }

@media (max-width: 420px) {
    #chatbot-box { width: calc(100vw - 24px); right: 12px; }
    #chatbot-toggle { right: 16px; bottom: 20px; }
}
</style>

{{-- ── Nút mở chatbot ── --}}
<button id="chatbot-toggle" title="Chat với ShopDecor AI">
    <i class="fas fa-comment-dots"></i>
    <span class="badge-dot" id="chatbot-badge"></span>
</button>

{{-- ── Hộp chat ── --}}
<div id="chatbot-box">
    <div id="chatbot-header">
        <div class="avatar"><i class="fas fa-robot"></i></div>
        <div class="info">
            <div class="name">ShopDecor AI</div>
            <div class="status">● Trực tuyến — hỏi gì cũng được!</div>
        </div>
        <button id="chatbot-close" title="Đóng"><i class="fas fa-times"></i></button>
    </div>

    <div id="chatbot-messages">
        {{-- Tin nhắn chào --}}
        <div class="cb-msg bot">
            👋 Xin chào! Tôi là <strong>ShopDecor AI</strong>, sẵn sàng tư vấn sản phẩm, đặt hàng và tra cứu đơn hàng cho bạn.<br>Bạn cần hỗ trợ gì hôm nay?
        </div>
        {{-- Typing indicator --}}
        <div class="cb-typing" id="cb-typing">
            <span></span><span></span><span></span>
        </div>
    </div>

    {{-- Quick replies --}}
    <div class="cb-quick-replies" id="cb-quick-replies">
        <button class="cb-quick-btn" data-msg="Cho tôi xem sản phẩm nổi bật">🔥 Sản phẩm nổi bật</button>
        <button class="cb-quick-btn" data-msg="Chính sách giao hàng như thế nào?">🚚 Giao hàng</button>
        <button class="cb-quick-btn" data-msg="Chính sách đổi trả ra sao?">🔄 Đổi trả</button>
        <button class="cb-quick-btn" data-msg="Tôi muốn tra cứu đơn hàng">📦 Tra cứu đơn</button>
    </div>

    <div id="chatbot-footer">
        <textarea id="chatbot-input" placeholder="Nhập tin nhắn..." rows="1"></textarea>
        <button id="chatbot-send"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
(function () {
    const toggle   = document.getElementById('chatbot-toggle');
    const box      = document.getElementById('chatbot-box');
    const closeBtn = document.getElementById('chatbot-close');
    const input    = document.getElementById('chatbot-input');
    const sendBtn  = document.getElementById('chatbot-send');
    const messages = document.getElementById('chatbot-messages');
    const typing   = document.getElementById('cb-typing');
    const badge    = document.getElementById('chatbot-badge');
    const quickWrap= document.getElementById('cb-quick-replies');

    let history = [];   // [{role, text}]
    let isOpen  = false;
    let hasNewMsg = false;

    // ── Mở / đóng ──────────────────────────────────────────────────────────
    function openBox() {
        isOpen = true;
        box.classList.add('open');
        badge.style.display = 'none';
        hasNewMsg = false;
        setTimeout(() => input.focus(), 100);
    }
    function closeBox() {
        isOpen = false;
        box.classList.remove('open');
    }

    toggle.addEventListener('click', () => isOpen ? closeBox() : openBox());
    closeBtn.addEventListener('click', closeBox);

    // Hiện badge sau 3s nếu chưa mở
    setTimeout(() => {
        if (!isOpen) {
            badge.style.display = 'block';
            hasNewMsg = true;
        }
    }, 3000);

    // ── Append message ──────────────────────────────────────────────────────
    function appendMsg(text, role) {
        const div = document.createElement('div');
        div.className = 'cb-msg ' + (role === 'user' ? 'user' : 'bot');
        div.textContent = text;
        messages.insertBefore(div, typing);
        scrollBottom();
    }

    function scrollBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    // ── Gửi tin nhắn ────────────────────────────────────────────────────────
    async function sendMessage(text) {
        text = text.trim();
        if (!text) return;

        // Ẩn quick replies sau lần gửi đầu tiên
        quickWrap.style.display = 'none';

        appendMsg(text, 'user');
        history.push({ role: 'user', text });

        input.value = '';
        input.style.height = 'auto';
        sendBtn.disabled = true;
        typing.style.display = 'flex';
        scrollBottom();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            const res = await fetch('{{ route("chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: text,
                    history: history.slice(-10),   // gửi tối đa 10 lượt gần nhất
                }),
            });

            const data = await res.json();
            typing.style.display = 'none';

            const reply = data.message || 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại.';
            appendMsg(reply, 'bot');
            history.push({ role: 'model', text: reply });

        } catch (err) {
            typing.style.display = 'none';
            appendMsg('Không thể kết nối. Vui lòng kiểm tra mạng và thử lại.', 'bot');
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    }

    // ── Event listeners ──────────────────────────────────────────────────────
    sendBtn.addEventListener('click', () => sendMessage(input.value));

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage(input.value);
        }
    });

    // Auto-resize textarea
    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 80) + 'px';
    });

    // Quick reply buttons
    document.querySelectorAll('.cb-quick-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            openBox();
            sendMessage(btn.dataset.msg);
        });
    });
})();
</script>
