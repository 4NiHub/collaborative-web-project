<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUS Portal - Create New Password</title>
    <style>
        /* Exact same styles as forgot-password.blade.php for consistency */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #667eea; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .login-container { background: white; border-radius: 16px; padding: 48px; box-shadow: 0 20px 60px rgba(0,0,0,.3); width: 560px; max-width: 90vw; margin: 20px; animation: slideUp .4s cubic-bezier(.22,1,.36,1) both; }
        @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:none} }
        .logo-container { text-align: center; margin-bottom: 32px; }
        .logo-icon { width: 70px; height: 70px; background: #eff6ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .logo-icon svg { color: #2563eb; }
        .logo-title { font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .logo-subtitle { font-size: 14px; color: #64748b; line-height: 1.6; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .form-group { margin-bottom: 24px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: #334155; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
        .form-input { width: 100%; padding: 13px 16px 13px 44px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 15px; font-family: inherit; transition: all .2s; background: #f8fafc; color: #1e293b; outline: none; }
        .form-input::placeholder { color: #94a3b8; }
        .form-input:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .form-input.is-error { border-color: #ef4444; }
        .field-error { font-size: 12.5px; color: #ef4444; margin-top: 6px; }
        .btn-primary { width: 100%; padding: 14px; background: #0f172a; color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all .2s; }
        .btn-primary:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15,23,42,.3); }
        .btn-primary .spinner { display: none; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.3); border-top-color: white; border-radius: 50%; animation: spin .7s linear infinite; margin: 0 auto; }
        @keyframes spin { to{transform:rotate(360deg)} }
        .btn-primary.loading .btn-text { display: none; }
        .btn-primary.loading .spinner { display: block; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo-container">
        <div class="logo-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <h1 class="logo-title">Create New Password</h1>
        <p class="logo-subtitle">Please secure your account with a strong password.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" id="newPasswordForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-wrap">
                <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}"
                       class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" readonly required>
            </div>
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
            <div class="input-wrap">
                <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" id="password" name="password" placeholder="••••••••"
                       class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" autofocus required>
            </div>
            @error('password') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <div class="input-wrap">
                <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••"
                       class="form-input" required>
            </div>
        </div>

        <button type="submit" class="btn-primary" id="submitBtn">
            <span class="btn-text">Reset Password</span>
            <span class="spinner"></span>
        </button>
    </form>
</div>
<script>
    document.getElementById('newPasswordForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading'); btn.disabled = true;
    });
</script>
</body>
</html>