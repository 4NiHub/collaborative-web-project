<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SUS Portal - Register</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #667eea;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .register-container {
            background: white;
            border-radius: 16px;
            padding: 30px 48px 30px 48px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 560px;
            max-width: 90vw;
        }
        .logo-container { text-align: center; margin-bottom: 32px; }
        .logo-title { font-size: 28px; font-weight: 700; color: #1e293b; }
        .logo-subtitle { font-size: 14px; color: #64748b; }

        .role-toggle {
            display: flex;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 6px;
            margin-bottom: 30px;
            gap: 8px;
        }

        .role-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
        }

        .role-btn.active {
            background: #2563eb;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px 13px 48px;
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

        .input-wrap { position: relative; }

        /* Input icons */
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #94a3b8;
            pointer-events: none;
        }

        /* Password toggle */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 14px; 
            top: 50%;
            transform: translateY(-50%);
            background: none; 
            border: none;
            cursor: pointer; 
            color: #94a3b8; padding: 4px;
            transition: color 0.2s;
        }
        .password-toggle:hover { color: #2563eb; }
        
        .btn-register {
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

        .btn-register:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
        }

       .btn-register:active { transform: translateY(0); }
       .btn-register:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

       .btn-register .spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-register.loading .btn-text { display: none; }
        .btn-register.loading .spinner  { display: block; }

        .back-link { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 20px; font-size: 13.5px; color: #64748b; text-decoration: none; transition: color .2s; }
        .back-link:hover { color: #2563eb; }

    </style>
</head>
<body>

<div class="register-container">
    <div class="logo-container">
        <h1 class="logo-title">SUS Portal</h1>
        <p class="logo-subtitle">Smart University System</p>
    </div>

    <div class="role-toggle">
        <button type="button" class="role-btn active" id="studentBtn">Student</button>
        <button type="button" class="role-btn" id="teacherBtn">Teacher</button>
    </div>

    <form id="registerForm" method="POST" action="{{ route('register.submit') }}">
        @csrf
        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:8px; margin:15px 0;">
                <strong>Registration failed:</strong>
                <ul style="margin-top:10px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="hidden" name="role" id="roleInput" value="student">

        <div class="form-group">
            <label class="form-label">Full Name</label>
            <div class="input-wrap">
                <div class="input-icon">
                    <!-- User icon (from Feather Icons) -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <input type="text" name="name" class="form-input" placeholder="Naruto" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Surname</label>
            <div class="input-wrap">
                <div class="input-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <input type="text" name="surname" class="form-input" placeholder="Uzumaki" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
                <div class="input-icon">
                    <!-- Mail icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <input type="email" name="email" class="form-input" placeholder="example@email.com" required>
            </div>
        </div>

        <div class="form-group password-wrapper">
            <label class="form-label">Password</label>
            <div class="input-wrap">
                <div class="input-icon">
                    <!-- Lock icon -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                <button type="button" class="password-toggle" id="togglePassword">
                    <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-register" id="submitBtn">
            <span class="btn-text">Create Account</span>
            <span class="spinner"></span>
        </button>

    <a href="{{ route('login') }}" class="back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Sign In
    </a>

    </form>
</div>

<script>
    // Role toggle
    const studentBtn = document.getElementById('studentBtn');
    const teacherBtn = document.getElementById('teacherBtn');
    const roleInput = document.getElementById('roleInput');

    studentBtn.addEventListener('click', () => {
        studentBtn.classList.add('active');
        teacherBtn.classList.remove('active');
        roleInput.value = 'student';
    });

    teacherBtn.addEventListener('click', () => {
        teacherBtn.classList.add('active');
        studentBtn.classList.remove('active');
        roleInput.value = 'teacher';
    });

    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    const eyeOpen = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        eyeIcon.innerHTML = type === 'text' ? eyeClosed : eyeOpen;
    });
</script>

</body>
</html>
