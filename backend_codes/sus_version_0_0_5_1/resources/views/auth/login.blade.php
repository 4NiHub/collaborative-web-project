<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUS Portal - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
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

        /* ── Logo ── */
        .logo-container {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .logo-icon img {
            width: 200px;
            height: 200px;
            display: block;
            margin-left: 1px;
        }

        .logo-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .logo-subtitle {
            font-size: 14px;
            color: #64748b;
        }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        /* ── Form ── */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s;
            background: #f8fafc;
            color: #1e293b;
            outline: none;
        }

        .form-input::placeholder { color: #94a3b8; }

        .form-input:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input.is-error { border-color: #ef4444; }

        /* password toggle */
        .toggle-pw {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #94a3b8; padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #2563eb; }

        .field-error {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: #ef4444;
            margin-top: 6px;
        }

        /* forgot link */
        .forgot-wrap {
            display: flex;
            justify-content: flex-end;
            margin-top: -10px;
            margin-bottom: 20px;
        }
        .forgot-wrap a {
            font-size: 13.5px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-wrap a:hover { text-decoration: underline; }

        /* ── Button ── */
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .btn-primary:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
        }

        .btn-primary:active { transform: translateY(0); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-primary .spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-primary.loading .btn-text { display: none; }
        .btn-primary.loading .spinner  { display: block; }

        .register{
            text-align:center;
            margin-top:20px;
        }

        .register p{
            font-size: 13.5px;
        }

        .register a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .register a:hover { text-decoration: underline; }

        /* ── Divider ── */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0 0;
            font-size: 11px; font-weight: 600;
            letter-spacing: 1px; color: #cbd5e1;
        }

        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #e2e8f0;
        }

        /* ── Demo Accounts UI ── */
        .demo-accounts {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px dashed #e2e8f0;
        }
        .demo-accounts-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-align: center;
            margin-bottom: 16px;
        }
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .demo-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .demo-card:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            transform: translateY(-2px);
        }
        .demo-icon { font-size: 20px; margin-bottom: 4px; }
        .demo-role { font-size: 13px; font-weight: 600; color: #1e293b; }

        /* ── Banned Account Modal ── */
        .banned-overlay {
            position: fixed; inset: 0; 
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(6px); 
            z-index: 9999; display: none; align-items: center; justify-content: center;
        }
        .banned-overlay.active { 
            display: flex; animation: fadeInOverlay 0.3s ease; 
        }
        .banned-modal { 
            background: white; width: 100%; max-width: 420px; border-radius: 20px; 
            padding: 32px 24px; text-align: center; box-shadow: 0 25px 50px -12px rgba(220, 38, 38, 0.25); 
            animation: bounceIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #fecaca;
        }
        .banned-icon-wrap { 
            width: 72px; height: 72px; background: #fef2f2; border: 8px solid #fee2e2; 
            color: #dc2626; border-radius: 50%; display: flex; align-items: center; 
            justify-content: center; margin: 0 auto 20px;
        }
        .banned-title { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 12px; }
        .banned-text { font-size: 15px; color: #64748b; line-height: 1.6; margin-bottom: 28px; }
        .banned-btn { 
            background: #dc2626; color: white; border: none; padding: 12px 24px; 
            border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; 
            transition: all 0.2s; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .banned-btn:hover { background: #b91c1c; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); }
        
        @keyframes fadeInOverlay { from { opacity: 0; } to { opacity: 1; } }
        @keyframes bounceIn { 
            0% { opacity: 0; transform: scale(0.8) translateY(20px); } 
            100% { opacity: 1; transform: scale(1) translateY(0); } 
        }

        /* security footer */
        /* .security-badge {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin-top: 16px; font-size: 12px; color: #94a3b8;
        }
        .security-badge svg { color: #22c55e; } */
    </style>
</head>
<body>

<div class="login-container">

    <div class="logo-container">
        <div class="logo-icon">
            {{-- If you have the logo image, place it in public/images/sus_logo.png --}}
            @if(file_exists(public_path('images/sus_logo.png')))
                <img src="{{ asset('images/sus_logo.png') }}" alt="SUS Portal">
            @else
                {{-- Fallback SVG cap icon --}}
                <svg width="48" height="48" viewBox="0 0 24 24" fill="#2563eb">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
            @endif
        </div>
        <h1 class="logo-title">SUS Portal</h1>
        <p class="logo-subtitle">Smart University System</p>
    </div>

    {{-- Error / success messages --}}
    @if(session('error') && !str_contains(session('error'), 'Suspended'))
        <div class="alert alert-danger" style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:16px; font-size:14px; text-align:center;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" id="loginForm" novalidate>
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-wrap">
                <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="student@idu.edu"
                    autocomplete="email"
                    autofocus
                    required
                >
            </div>
            @error('email')
                <div class="field-error">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16" stroke="white" stroke-width="2"/></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrap">
                <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required>
                <button type="button" class="toggle-pw" id="togglePw" aria-label="Show password">
                    <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password')
                <div class="field-error">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Forgot password --}}
        <div class="forgot-wrap">
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary" id="submitBtn">
            <span class="btn-text">Sign In</span>
            <span class="spinner"></span>
        </button>

        <div class="register">
            <p>
                Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
            </p>
        </div>
    </form>

    <div class="demo-accounts">
            <div class="demo-accounts-title">Default credentials</div>
            <div class="demo-grid">
                <div class="demo-card" onclick="fillDemo('a.morgan@wlv.ac.uk', 'password')">
                    <div class="demo-icon">🎓</div>
                    <div class="demo-role">Student</div>
                </div>
                <div class="demo-card" onclick="fillDemo('s.johnson@wlv.ac.uk', 'password')">
                    <div class="demo-icon">👨‍🏫</div>
                    <div class="demo-role">Teacher</div>
                </div>
                <div class="demo-card" onclick="fillDemo('admin@wlv.ac.uk', 'password')">
                    <div class="demo-icon">🛡️</div>
                    <div class="demo-role">Admin</div>
                </div>
            </div>
        </div>

    <div class="divider"><p>SECURED PORTAL ACCESS</p></div>

    <div class="banned-overlay {{ (session('error') && str_contains(session('error'), 'Suspended')) ? 'active' : '' }}" id="bannedModal">
        <div class="banned-modal">
            <div class="banned-icon-wrap">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h2 class="banned-title">Account Suspended</h2>
            <p class="banned-text">You have been restricted from accessing the Smart University System by the administrator. Please contact your department for support.</p>
            <button class="banned-btn" onclick="document.getElementById('bannedModal').classList.remove('active')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Return to Homepage
            </button>
        </div>
    </div>

<script src="{{ asset('js/api.js') }}?v={{ time() }}"></script>

<script>
    function fillDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
        // Optional: Automatically click submit!
        // document.getElementById('loginForm').submit(); 
    }

    // Password toggle
    const togglePw = document.getElementById('togglePw');
    const pwInput  = document.getElementById('password');
    const eyeIcon  = document.getElementById('eyeIcon');
    const eyeOff = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
    const eyeOn  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;

    togglePw.addEventListener('click', () => {
        const shown = pwInput.type === 'text';
        pwInput.type = shown ? 'password' : 'text';
        eyeIcon.innerHTML = shown ? eyeOn : eyeOff;
    });

    // Handle Login using api.js
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault(); 
        
        const btn = document.getElementById('submitBtn');
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // UI Loading State
        btn.classList.add('loading');
        btn.disabled = true;

        try {
            // This calls the logic we just perfected in api.js!
            await window.AuthAPI.login(email, password);
            
            // Note: If 2FA triggers, api.js freezes the thread so this line won't run.
            // If direct login triggers (2FA off), api.js returns data and we redirect.
            if (window.location.pathname === '/login') {
                 window.location.href = '/dashboard';
            }

        } catch (error) {
            console.error('Login Error:', error);
            btn.classList.remove('loading');
            btn.disabled = false;
            
            // 🚨 FIX: Trigger the beautiful modal if the error contains "Suspended"
            if (error.message && error.message.includes('Suspended')) {
                document.getElementById('bannedModal').classList.add('active');
            } else {
                alert(error.message || 'Login failed. Please check credentials.');
            }
        }
    });
</script>

{{-- <script>
    // Password toggle
    const togglePw = document.getElementById('togglePw');
    const pwInput  = document.getElementById('password');
    const eyeIcon  = document.getElementById('eyeIcon');
    const eyeOff = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
    const eyeOn  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;

    togglePw.addEventListener('click', () => {
        const shown = pwInput.type === 'text';
        pwInput.type = shown ? 'password' : 'text';
        eyeIcon.innerHTML = shown ? eyeOn : eyeOff;
    });

    // Handle AJAX Login
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent standard page reload
        
        const btn = document.getElementById('submitBtn');
        const form = this;
        const formData = new FormData(form);

        // UI Loading State
        btn.classList.add('loading');
        btn.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // 1. Store token for any future API calls
                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('user_role', data.role);

                // 2. Direct Redirect to Dashboard
                window.location.href = data.redirect;
            } else {
                // Handle Validation Errors or Login Failures
                btn.classList.remove('loading');
                btn.disabled = false;
                
                // If there's a specific error message (like "Too many attempts")
                alert(data.message || data.errors?.email?.[0] || 'Login failed. Please check credentials.');
            }
        } catch (error) {
            console.error('Error:', error);
            btn.classList.remove('loading');
            btn.disabled = false;
            alert('A connection error occurred. Please try again.');
        }
    });
</script> --}}

{{-- <script>
    // Password toggle
    const togglePw = document.getElementById('togglePw');
    const pwInput  = document.getElementById('password');
    const eyeIcon  = document.getElementById('eyeIcon');
    const eyeOff = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
    const eyeOn  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;

    togglePw.addEventListener('click', () => {
        const shown = pwInput.type === 'text';
        pwInput.type = shown ? 'password' : 'text';
        eyeIcon.innerHTML = shown ? eyeOn : eyeOff;
    });

    // Submit loader
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading');
        btn.disabled = true;
    });
</script> --}}
</body>
</html>
