<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUS Portal - Reset Password</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #667eea;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .login-container {
            background: white; border-radius: 16px; padding: 48px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            width: 560px; max-width: 90vw; margin: 20px;
            animation: slideUp .4s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:none} }
        .logo-container { text-align: center; margin-bottom: 32px; }
        .logo-icon {
            width: 70px; height: 70px; background: #eff6ff; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;
        }
        .logo-icon svg { color: #2563eb; }
        .logo-title { font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .logo-subtitle { font-size: 14px; color: #64748b; line-height: 1.6; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: #334155; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
        .form-input {
            width: 100%; padding: 13px 16px 13px 44px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 15px; font-family: inherit;
            transition: all .2s; background: #f8fafc; color: #1e293b; outline: none;
        }
        .form-input::placeholder { color: #94a3b8; }
        .form-input:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .form-input.is-error { border-color: #ef4444; }
        .field-error { font-size: 12.5px; color: #ef4444; margin-top: 6px; }
        .btn-primary {
            width: 100%; padding: 14px; background: #0f172a; color: white;
            border: none; border-radius: 10px; font-size: 15px; font-weight: 600;
            font-family: inherit; cursor: pointer; transition: all .2s;
        }
        .btn-primary:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15,23,42,.3); }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary .spinner { display: none; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.3); border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite; margin: 0 auto; }
        @keyframes spin { to{transform:rotate(360deg)} }
        .btn-primary.loading .btn-text { display: none; }
        .btn-primary.loading .spinner  { display: block; }
        .back-link { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 20px; font-size: 13.5px; color: #64748b; text-decoration: none; transition: color .2s; }
        .back-link:hover { color: #2563eb; }

        .demo-alert {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px;
            padding: 16px; margin-bottom: 24px; text-align: center; color: #1e3a8a;
        }
        .demo-link-btn {
            display: inline-block; background: #2563eb; color: white !important;
            text-decoration: none; padding: 10px 20px; border-radius: 6px;
            font-weight: 600; margin-top: 12px; font-size: 14px;
        }
        .demo-link-btn:hover { background: #1d4ed8; }

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
    </style>
</head>
<body>
<div class="login-container">

    @if(session('demo_reset_link'))
        <div class="demo-alert">
            <strong>👨‍🏫 Evaluator Bypass Activated</strong><br>
            Bypassed email system. Click below to securely reset this demo account's password:<br>
            <a href="{{ session('demo_reset_link') }}" class="demo-link-btn">Proceed to Reset Password →</a>
        </div>
    @endif

    <div class="logo-container">
        <div class="logo-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <h1 class="logo-title">Reset Password</h1>
        <p class="logo-subtitle">Enter your university email and we'll send you a secure reset link.</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('status') }}
        </div>
    @endif

    @if(session('error') && !str_contains(session('error'), 'Suspended'))
        <div class="alert alert-danger" style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:16px; font-size:14px; text-align:center;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="resetForm">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-wrap">
                <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="student@idu.edu" autocomplete="email" autofocus
                       class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" required>
            </div>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary" id="submitBtn">
            <span class="btn-text">Send Reset Link</span>
            <span class="spinner"></span>
        </button>
    </form>

    <div class="demo-accounts">
            <div class="demo-accounts-title">Evaluator Quick Access</div>
            <div class="demo-grid">
                <div class="demo-card" onclick="fillDemoEmail('a.morgan@wlv.ac.uk')">
                    <div class="demo-icon">🎓</div>
                    <div class="demo-role">Student</div>
                </div>
                <div class="demo-card" onclick="fillDemoEmail('s.johnson@wlv.ac.uk')">
                    <div class="demo-icon">👨‍🏫</div>
                    <div class="demo-role">Teacher</div>
                </div>
                <div class="demo-card" onclick="fillDemoEmail('admin@wlv.ac.uk')">
                    <div class="demo-icon">🛡️</div>
                    <div class="demo-role">Admin</div>
                </div>
            </div>
        </div>

    <a href="{{ route('login') }}" class="back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Sign In
    </a>

    @if(session('error') && str_contains(session('error'), 'Suspended'))
        <div class="banned-overlay active" id="bannedModal">
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
    @endif
</div>
<script>
    function fillDemoEmail(email) {
        document.getElementById('email').value = email;
    }

    document.getElementById('resetForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading'); btn.disabled = true;
    });
</script>
</body>
</html>
