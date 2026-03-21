<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUS Portal - Verify Identity</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #667eea;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 16px;
            padding: 48px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 560px;
            max-width: 90vw;
            margin: 20px;
            animation: slideUp .4s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-2fa{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon {
            width: 70px; height: 70px;
            background: #eff6ff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            margin-right: 20px;
        }

        .logo-icon svg { color: #2563eb; }

        .logo-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .logo-subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        .logo-subtitle strong { color: #1e293b; font-weight: 600; }

        /* 2FA badge */
        .badge-2fa {
            display: inline-flex; align-items: center; gap: 6px;
            background: #eff6ff; color: #2563eb;
            border: 1px solid #bfdbfe;
            border-radius: 100px; padding: 5px 14px;
            font-size: 12px; font-weight: 600; letter-spacing: .5px;
            margin-bottom: 20px;
        }

        /* Alerts */
        .alert {
            padding: 12px 16px; border-radius: 8px; font-size: 13.5px;
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
        }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        /* OTP boxes */
        .otp-wrap {
            display: flex; gap: 10px; justify-content: center;
            margin-bottom: 24px;
        }

        .otp-wrap input {
            width: 54px; height: 62px;
            text-align: center;
            font-size: 24px; font-weight: 700;
            color: #1e293b;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .otp-wrap input:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            transform: translateY(-2px);
        }

        .otp-wrap input.filled { border-color: #2563eb; }
        .otp-wrap input.is-error { border-color: #ef4444; }

        @keyframes shake {
            0%,100%{ transform:translateX(0); }
            20%    { transform:translateX(-6px); }
            40%    { transform:translateX(6px); }
            60%    { transform:translateX(-4px); }
            80%    { transform:translateX(4px); }
        }
        .otp-wrap.shake { animation: shake .4s ease; }

        /* Timer */
        .timer-wrap {
            text-align: center; margin-bottom: 24px;
            font-size: 13.5px; color: #64748b;
        }
        #countdown { font-weight: 600; color: #1e293b; font-variant-numeric: tabular-nums; }
        #countdown.expired { color: #ef4444; }

        #resendBtn {
            background: none; border: none;
            font-family: inherit; font-size: 13.5px;
            color: #2563eb; cursor: pointer; font-weight: 600;
            text-decoration: underline; padding: 0;
            display: none;
        }
        #resendBtn.visible { display: inline; }
        #resendBtn:disabled { opacity: .5; cursor: not-allowed; }

        /* Button */
        .btn-primary {
            width: 100%; padding: 14px;
            background: #0f172a; color: white;
            border: none; border-radius: 10px;
            font-size: 15px; font-weight: 600; font-family: inherit;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,23,42,.3);
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .btn-primary .spinner {
            display: none; width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,.3);
            border-top-color: white;
            border-radius: 50%; animation: spin .7s linear infinite; margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-primary.loading .btn-text { display: none; }
        .btn-primary.loading .spinner  { display: block; }

        .back-link {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin-top: 20px; font-size: 13.5px; color: #64748b; text-decoration: none;
            transition: color .2s;
        }
        .back-link:hover { color: #2563eb; }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0 0;
            font-size: 11px; font-weight: 600; letter-spacing: 1px; color: #cbd5e1;
        }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

        .security-badge {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin-top: 16px; font-size: 12px; color: #94a3b8;
        }
        .security-badge svg { color: #22c55e; }
    </style>
</head>
<body>

<div class="login-container">

    <div class="logo-container">
        <div class="logo-2fa">
            <div class="logo-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>

            <div class="badge-2fa">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                TWO-FACTOR AUTHENTICATION
            </div>
        </div>

        <h1 class="logo-title">Verify Your Identity</h1>
        <p class="logo-subtitle">
            A 6-digit code was sent to<br>
            <strong>{{ Str::mask(session('2fa_email', ''), '*', 3, strlen(session('2fa_email', '')) - 7) }}</strong>
        </p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if(session('resent'))
        <div class="alert alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            A new code has been sent to your email.
        </div>
    @endif

    <form id="otpForm" novalidate>
        @csrf
        <input type="hidden" name="code" id="codeHidden">

        <div class="otp-wrap" id="otpWrap">
            @for($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    inputmode="numeric"
                    maxlength="1"
                    class="otp-digit {{ $errors->has('code') ? 'is-error' : '' }}"
                    autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                    aria-label="Digit {{ $i + 1 }}"
                >
            @endfor
        </div>

        <div class="timer-wrap">
            Code expires in <span id="countdown">3:00</span><br>
            <button type="button" id="resendBtn" onclick="resendCode()">Resend code</button>
        </div>

        <button type="submit" class="btn-primary" id="verifyBtn" disabled>
            <span class="btn-text">Verify &amp; Continue</span>
            <span class="spinner"></span>
        </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Sign In
    </a>

    <div class="divider">SINGLE USE · 3 MINUTES</div>

    <div class="security-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Code deleted after use · Rate-limited attempts
    </div>
</div>

<script>
    // OTP digit boxes
    const digits    = document.querySelectorAll('.otp-digit');
    const hidden    = document.getElementById('codeHidden');
    const verifyBtn = document.getElementById('verifyBtn');
    const otpWrap   = document.getElementById('otpWrap');

    function assembleCode() {
        return [...digits].map(d => d.value).join('');
    }

    function updateSubmit() {
        const code = assembleCode();
        hidden.value = code;
        verifyBtn.disabled = code.length < 6;
    }

    digits.forEach((input, idx) => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && idx > 0) {
                digits[idx - 1].value = '';
                digits[idx - 1].classList.remove('filled');
                digits[idx - 1].focus();
                updateSubmit();
            }
        });

        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(-1);
            if (input.value) {
                input.classList.add('filled');
                if (idx < digits.length - 1) digits[idx + 1].focus();
            } else {
                input.classList.remove('filled');
            }
            updateSubmit();
        });

        input.addEventListener('paste', e => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            text.split('').slice(0, 6).forEach((ch, i) => {
                if (digits[idx + i]) {
                    digits[idx + i].value = ch;
                    digits[idx + i].classList.add('filled');
                }
            });
            const next = Math.min(idx + text.length, digits.length - 1);
            digits[next].focus();
            updateSubmit();
        });
    });

    digits[0].focus();

    // document.getElementById('otpForm').addEventListener('submit', function() {
    //     if (assembleCode().length < 6) return;
    //     verifyBtn.classList.add('loading');
    //     verifyBtn.disabled = true;
    // });

    document.getElementById('otpForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const code = [...document.querySelectorAll('.otp-digit')].map(d => d.value).join('');
        if (code.length < 6) return;

        const btn = document.getElementById('verifyBtn');
        btn.classList.add('loading');
        btn.disabled = true;

        try {
            const response = await fetch('{{ route("2fa.verify.submit") }}', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'Accept': 'application/json', // Force Laravel to return JSON errors
                    'X-Requested-With': 'XMLHttpRequest', // Tells Laravel this is an AJAX call
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value // Explicit token
                }
            });

            // 1. Handle the 419 (CSRF/Session Expired) error gracefully
            if (response.status === 419) {
                alert("Your security session expired. The page will now refresh.");
                window.location.reload();
                return;
            }

            // 2. Handle 500 (Server Error) so you don't try to parse broken JSON
            if (!response.ok) {
                const errorText = await response.text();
                console.error("Server Error:", errorText);
                alert("Something went wrong on the server. Please try again.");
                return;
            }

            // 3. Parse JSON
            const data = await response.json();

            // Check 'data' (not 'result')
            if (data.success && data.token) {
                // Save the token so api.js can find it
                localStorage.setItem('userToken', data.token);
                
                // Optional: Save role if your dashboard needs it
                localStorage.setItem('userRole', data.role);

                // Now redirect
                window.location.href = data.redirect || '/dashboard';
            } else {
                // Handle validation errors (like wrong code)
                alert(data.message || 'Verification failed');
            }
        } catch (err) {
            console.error('2FA error:', err);
            alert('Wrong 2FA Code');
        } finally {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
    });

    @if($errors->has('code') || session('error'))
        otpWrap.classList.add('shake');
        digits.forEach(d => { d.value = ''; d.classList.remove('filled'); });
        digits[0].focus();
        setTimeout(() => otpWrap.classList.remove('shake'), 500);
    @endif

    // Countdown
    const expiresAt = {{ session('2fa_expires_at', now()->addMinutes(3)->timestamp) }};
    const countdown = document.getElementById('countdown');
    const resendBtn = document.getElementById('resendBtn');

    function tick() {
        const remaining = expiresAt - Math.floor(Date.now() / 1000);
        if (remaining <= 0) {
            countdown.textContent = 'Expired';
            countdown.classList.add('expired');
            verifyBtn.disabled = true;
            resendBtn.classList.add('visible');
            return;
        }
        const m = Math.floor(remaining / 60).toString().padStart(2, '0');
        const s = (remaining % 60).toString().padStart(2, '0');
        countdown.textContent = `${m}:${s}`;
        setTimeout(tick, 1000);
    }
    tick();

    function resendCode() {
        resendBtn.disabled = true;
        resendBtn.textContent = 'Sending…';

        fetch('{{ route("2fa.resend") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json', // REQUIRED: Prevents HTML error pages
                'X-Requested-With': 'XMLHttpRequest', // Tells Laravel this is an AJAX call
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
        })
        .then(response => {
            if (response.status === 419 || response.status === 401) {
                // Session expired, must go back to login
                window.location.href = '/login';
                return;
            }
            if (response.ok) {
                window.location.reload();
            } else {
                throw new Error('Server error');
            }
        })
        .catch(() => {
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend code';
            alert('Failed to resend. Please check your connection.');
        });
    }
</script>
</body>
</html>
