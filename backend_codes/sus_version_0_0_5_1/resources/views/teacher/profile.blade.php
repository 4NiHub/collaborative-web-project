<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile – Smart University System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <style>
        .app-container { display:flex; min-height:100vh; }

        /* Sidebar */
        .sidebar {
            width:var(--sidebar-collapsed); background:#2563eb;
            display:flex; flex-direction:column; position:fixed;
            height:100vh; left:0; top:0; z-index:1000;
            transition:var(--transition); overflow:hidden; color:white;
        }
        .sidebar.expanded { width:var(--sidebar-expanded); }
        .sidebar-toggle-btn {
            height:60px; display:flex; align-items:center; justify-content:center;
            cursor:pointer; border-bottom:1px solid rgba(255,255,255,0.12); transition:background 0.2s;
        }
        .sidebar-toggle-btn:hover { background:rgba(255,255,255,0.15); }
        .sidebar-toggle-btn img { width:28px; height:28px; transition:transform 0.4s ease; }
        .sidebar.expanded .sidebar-toggle-btn img { transform:rotate(180deg); }
        .sidebar-icons { flex:1; display:flex; flex-direction:column; align-items:center; padding:20px 0; }
        .sidebar-icon {
            width:calc(100% - 30px); height:48px; border-radius:10px;
            display:flex; align-items:center; padding:0 18px; margin:4px 8px;
            cursor:pointer; transition:var(--transition); position:relative; color:rgba(255,255,255,0.85);
        }
        .sidebar-icon:hover, .sidebar-icon.active { background:rgba(255,255,255,0.18); color:white; }
        .sidebar-icon img { width:32px; height:32px; flex-shrink:0; margin-right:0; transition:margin-right 0.3s ease; }
        .sidebar.expanded .sidebar-icon img { margin-right:16px; }
        .sidebar-label {
            opacity:0; visibility:hidden; width:0; overflow:hidden; white-space:nowrap;
            font-size:15px; font-weight:500; transition:opacity 0.22s ease 0.05s, width 0.35s ease, visibility 0.35s;
        }
        .sidebar.expanded .sidebar-label { opacity:1; visibility:visible; width:auto; }
        .theme-toggle, .logout-icon { margin-top:auto; }
        .sidebar:not(.expanded) .sidebar-icon::after {
            content:attr(data-tooltip); position:fixed; left:98px;
            background:#1e293b; color:white; padding:8px 14px; border-radius:6px;
            font-size:13px; font-weight:500; white-space:nowrap;
            opacity:0; pointer-events:none; transition:opacity 0.2s;
            z-index:9999; box-shadow:0 4px 12px rgba(0,0,0,0.3); line-height:1;
        }
        .sidebar:not(.expanded) .sidebar-icon::before {
            content:''; position:fixed; left:87px;
            border:7px solid transparent; border-right-color:#1e293b;
            opacity:0; transition:opacity 0.2s; z-index:9999;
        }
        .sidebar:not(.expanded) .sidebar-icon:hover::after,
        .sidebar:not(.expanded) .sidebar-icon:hover::before { opacity:1; }
        .logout-icon { margin-top:8px; border-top:1px solid rgba(255,255,255,0.1); }
        .logout-icon:hover { background:rgba(239,68,68,0.2); color:#ef4444; }

        /* Main */
        .main-content {
            margin-left:var(--sidebar-collapsed); transition:var(--transition);
            flex:1; position:relative; z-index:1; min-height:100vh;
            background:#f8fafc;
        }
        .sidebar.expanded + .main-content { margin-left:var(--sidebar-expanded); }

        /* ── Profile hero banner ── */
        .profile-banner {
            background:#2563eb; padding:32px 40px 28px;
            display:flex; align-items:center; gap:24px; position:relative;
        }
        .profile-avatar {
            width:72px; height:72px; border-radius:50%;
            background:rgba(255,255,255,0.25);
            display:flex; align-items:center; justify-content:center;
            font-size:26px; font-weight:700; color:white; flex-shrink:0;
        }
        .profile-banner-info { flex:1; }
        .profile-banner-name { font-size:22px; font-weight:700; color:white; margin-bottom:4px; }
        .profile-banner-dept { font-size:14px; color:rgba(255,255,255,0.8); margin-bottom:12px; }
        .subject-chips { display:flex; gap:8px; flex-wrap:wrap; }
        .subject-chip {
            padding:4px 12px; background:rgba(255,255,255,0.2); border-radius:20px;
            font-size:13px; color:white; font-weight:500;
        }
        .edit-profile-btn {
            position:absolute; top:24px; right:32px;
            padding:8px 18px; background:white; border:none; border-radius:8px;
            font-size:13px; font-weight:600; color:#2563eb; cursor:pointer; transition:all 0.2s;
        }
        .edit-profile-btn:hover { background:#eff6ff; }

        /* ── Contact strip ── */
        .contact-strip {
            background:white; padding:14px 40px;
            display:flex; align-items:center; gap:32px;
            border-bottom:1px solid #e2e8f0; flex-wrap:wrap;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .contact-item {
            display:flex; align-items:center; gap:8px;
            font-size:13px; color:#475569;
        }
        .contact-item img { width:16px; height:16px; opacity:0.6; }

        /* ── Profile body ── */
        .profile-body { display:grid; grid-template-columns:1fr 320px; gap:0; }
        .profile-left  { padding:28px 40px; }
        .profile-right { padding:28px 32px; border-left:1px solid #e2e8f0; background:white; }

        /* Sections */
        .profile-section { margin-bottom:36px; }
        .profile-section-title {
            font-size:14px; font-weight:700; color:#1e293b; margin-bottom:14px;
        }
        .profile-section p { font-size:14px; color:#475569; line-height:1.65; }
        .divider { height:1px; background:#e2e8f0; margin-bottom:28px; }

        /* Experience / Education timeline */
        .timeline-item { display:flex; gap:14px; margin-bottom:20px; }
        .timeline-dot {
            width:12px; height:12px; border-radius:50%;
            flex-shrink:0; margin-top:4px;
        }
        .dot-blue   { background:#2563eb; }
        .dot-green  { background:#10b981; }
        .timeline-content { flex:1; }
        .timeline-title { font-size:14px; font-weight:600; color:#1e293b; margin-bottom:2px; }
        .timeline-org   { font-size:13px; color:#2563eb; margin-bottom:2px; }
        .timeline-org a { color:#2563eb; text-decoration:none; }
        .timeline-org a:hover { text-decoration:underline; }
        .timeline-period{ font-size:12px; color:#94a3b8; margin-bottom:4px; }
        .timeline-desc  { font-size:13px; color:#64748b; line-height:1.5; }

        /* Right panel details */
        .right-section { margin-bottom:24px; }
        .right-section-title {
            font-size:11px; font-weight:700; color:#94a3b8;
            text-transform:uppercase; letter-spacing:0.6px; margin-bottom:12px;
        }
        .right-detail-row { display:flex; flex-direction:column; margin-bottom:12px; }
        .right-detail-label { font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:2px; }
        .right-detail-value { font-size:14px; color:#1e293b; font-weight:500; }
        .subjects-wrap { display:flex; flex-wrap:wrap; gap:8px; }
        .subject-tag {
            padding:5px 12px; border:1.5px solid #2563eb; border-radius:20px;
            font-size:13px; color:#2563eb; font-weight:500;
        }

        /* dark mode */
        body.dark-mode .sidebar { background:#1e293b; }
        body.dark-mode .sidebar-icon::after { background:#334155; }
        body.dark-mode .sidebar-icon::before { border-right-color:#334155; }
        body.dark-mode .logout-icon { border-top-color:rgba(255,255,255,0.05); }
        body.dark-mode .main-content { background:#0f172a; }
        body.dark-mode .contact-strip,
        body.dark-mode .profile-right { background:#1e293b; border-color:#334155; }
        body.dark-mode .profile-left  { background:#0f172a; }
        body.dark-mode .profile-section-title,
        body.dark-mode .timeline-title,
        body.dark-mode .right-detail-value { color:#f1f5f9; }
        body.dark-mode .contact-item,
        body.dark-mode .timeline-desc,
        body.dark-mode .profile-section p { color:#94a3b8; }
        body.dark-mode .divider { background:#334155; }
        body.dark-mode .subject-tag { border-color:#3b82f6; color:#60a5fa; }

        @media (max-width:900px) {
            .profile-body { grid-template-columns:1fr; }
            .profile-right { border-left:none; border-top:1px solid #e2e8f0; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <aside class="sidebar">
        <div class="sidebar-toggle-btn"><img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle"></div>
        <div class="sidebar-icons">
            <div class="sidebar-icon" data-page="dashboard" data-tooltip="Dashboard"><img src="{{ asset('images/home.png') }}" alt=""><span class="sidebar-label">Dashboard</span></div>
            <div class="sidebar-icon" data-page="timetable" data-tooltip="Timetable"><img src="{{ asset('images/calendar.png') }}" alt=""><span class="sidebar-label">Timetable</span></div>
            <div class="sidebar-icon" data-page="modules" data-tooltip="My Modules"><img src="{{ asset('images/modules.png') }}" alt=""><span class="sidebar-label">My Modules</span></div>
            <div class="sidebar-icon active" data-page="profile" data-tooltip="My Profile"><img src="{{ asset('images/person_white.png') }}" alt=""><span class="sidebar-label">My Profile</span></div>
            <div class="sidebar-icon" data-page="news" data-tooltip="News"><img src="{{ asset('images/news.png') }}" alt=""><span class="sidebar-label">News</span></div>
            <div class="sidebar-icon" data-page="career" data-tooltip="Career Centre"><img src="{{ asset('images/career.png') }}" alt=""><span class="sidebar-label">Career Centre</span></div>
            <div class="sidebar-icon" data-page="contact" data-tooltip="Contact"><img src="{{ asset('images/contact.png') }}" alt=""><span class="sidebar-label">Contact</span></div>
            <div class="sidebar-icon" data-page="help" data-tooltip="Help"><img src="{{ asset('images/help.png') }}" alt=""><span class="sidebar-label">Help</span></div>
            <div class="sidebar-icon theme-toggle" data-tooltip="Toggle Theme"><img src="{{ asset('images/dark_mode.png') }}" alt=""><span class="sidebar-label">Dark Mode</span></div>
            <div class="sidebar-icon logout-icon" data-tooltip="Logout"><img src="{{ asset('images/logout.png') }}" alt=""><span class="sidebar-label">Logout</span></div>
        </div>
    </aside>

    <main class="main-content">

        <!-- Profile Banner -->
        <div class="profile-banner">
            <div class="profile-avatar" id="profileAvatar"></div>
            <div class="profile-banner-info">
                <div class="profile-banner-name" id="profileName">Loading...</div>
                <div class="profile-banner-dept" id="profileDept">—</div>
                <div class="subject-chips" id="subjectChips"></div>
            </div>

            <button class="edit-profile-btn" id="editProfileBtn" onclick="toggleEditMode()">Edit Profile</button>
        </div>

        <!-- Contact strip -->
        <div class="contact-strip">
            <div class="contact-item">
                <img src="{{ asset('images/mail.png') }}" alt="">
                <span id="profileEmail">sjohnson@sus.edu</span>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/call_black.png') }}" alt="">
                <span id="profilePhone">+1 555-0101</span>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/pin.png') }}" alt="">
                <span id="profileOffice">Block A, Room 205</span>
            </div>
            <div class="contact-item">
                <img src="{{ asset('images/clock.png') }}" alt="">
                <span id="profileHours">Office hrs: Mon 14:00–16:00, Wed 10:00–12:00</span>
            </div>
        </div>

        <!-- Profile body -->
        <div class="profile-body">

            <!-- LEFT -->
            <div class="profile-left">

                <!-- About Me -->
                <div class="profile-section">
                    <div class="profile-section-title">About Me</div>
                    <p id="profileBio">Expert in data structures, algorithms and computational theory with over 12 years of teaching and research experience.</p>
                </div>
                <div class="divider"></div>

                <!-- Experience -->
                <div class="profile-section">
                    <div class="profile-section-title">Experience</div>
                    <div id="experienceList"></div>
                </div>
                <div class="divider"></div>

                <!-- Education -->
                <div class="profile-section">
                    <div class="profile-section-title">Education</div>
                    <div id="educationList"></div>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="profile-right">

                <div class="right-section">
                    <div class="right-section-title">Subjects Taught</div>
                    <div class="subjects-wrap" id="subjectTags"></div>
                </div>

                <div class="right-section">
                    <div class="right-section-title">Details</div>
                    <div class="right-detail-row">
                        <span class="right-detail-label">Department</span>
                        <span class="right-detail-value" id="detailDept">Computer Science</span>
                    </div>
                    <div class="right-detail-row">
                        <span class="right-detail-label">Office</span>
                        <span class="right-detail-value" id="detailOffice">Block A, Room 205</span>
                    </div>
                    <div class="right-detail-row">
                        <span class="right-detail-label">Nationality</span>
                        <span class="right-detail-value" id="detailNationality">British</span>
                    </div>
                    <div class="right-detail-row">
                        <span class="right-detail-label">Languages</span>
                        <span class="right-detail-value" id="detailLanguages">English, French</span>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<div class="logout-popup-overlay" id="logoutPopupOverlay">
    <div class="logout-popup-modal">
        
        <div class="logout-icon-large">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>

        <h2 class="logout-popup-title">Log Out</h2>
        <p class="logout-popup-text">Are you sure you want to log out of your account? You will need to sign in again to access your account.</p>
        
        <div class="logout-actions">
            <button class="logout-btn-cancel" onclick="closeLogoutPopup()">Cancel</button>
            <button class="logout-btn-confirm" id="confirmLogoutBtn" onclick="executeLogout()">Log Out</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/api.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('js/api.js') }}"></script> --}}
<script>
    // authGuard();
    if (localStorage.getItem('darkMode') === 'enabled') document.body.classList.add('dark-mode');
    document.querySelector('.theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    });
    
    // 1. Show the custom modal when the sidebar icon is clicked
    document.querySelector('.logout-icon').addEventListener('click', function() {
        document.getElementById('logoutPopupOverlay').classList.add('show');
    });

    // 2. Close the modal if they click cancel or click outside the box
    function closeLogoutPopup() {
        document.getElementById('logoutPopupOverlay').classList.remove('show');
    }

    document.getElementById('logoutPopupOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLogoutPopup();
        }
    });

    // 3. Execute the actual logout when they click the red button
    function executeLogout() {
        const btn = document.getElementById('confirmLogoutBtn');
        btn.disabled = true;
        btn.innerHTML = 'Logging out...';
        AuthAPI.logout();
    }

    var sidebar = document.querySelector('.sidebar');
    document.querySelector('.sidebar-toggle-btn').addEventListener('click', function() { sidebar.classList.toggle('expanded'); });
    document.querySelector('.main-content').addEventListener('click', function(e) {
        if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) sidebar.classList.remove('expanded');
    });

    // Navigation
    var PAGE_MAP = {
        'dashboard':  '{{ route("dashboard") }}',
        'timetable':  '{{ route("teacher.timetable") }}',
        'modules':    '{{ route("teacher.modules") }}',
        'profile':    '{{ route("teacher.profile") }}',
        'news':       '{{ route("news") }}',
        'career':     '{{ route("career-centre") }}',
        'contact':    '{{ route("contact") }}',
        'help':       '{{ route("teacher.help") }}'
    };

    document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon) {
        icon.addEventListener('click', function() {
            var dest = PAGE_MAP[this.dataset.page];
            if (dest) {
                window.location.href = dest;
            }
        });
    });

    // async function loadProfile() {
    //     try {
    //         var res = await TeacherAPI.getProfile();
    //         var p   = res.data;

    //         var initials = (p.firstName[0] + p.lastName[0]).toUpperCase();
    //         document.getElementById('profileAvatar').textContent = initials;
    //         document.getElementById('profileName').textContent   = p.title + ' ' + p.firstName + ' ' + p.lastName;
    //         document.getElementById('profileDept').textContent   = p.department + ' \u2013 Smart University System';

    //         // Subject chips in banner
    //         var chips = document.getElementById('subjectChips');
    //         chips.innerHTML = (p.subjects || []).map(function(s) {
    //             return '<span class="subject-chip">'+s+'</span>';
    //         }).join('');

    //         // Contact strip
    //         document.getElementById('profileEmail').textContent = p.email;
    //         document.getElementById('profilePhone').textContent = p.phone;
    //         document.getElementById('profileOffice').textContent = p.office;
    //         document.getElementById('profileHours').textContent  = 'Office hrs: ' + p.officeHours;

    //         // Bio
    //         document.getElementById('profileBio').textContent = p.bio;

    //         // Experience
    //         var expList = document.getElementById('experienceList');
    //         expList.innerHTML = (p.experience || []).map(function(e) {
    //             return '<div class="timeline-item">' +
    //                 '<div class="timeline-dot dot-blue"></div>' +
    //                 '<div class="timeline-content">' +
    //                     '<div class="timeline-title">'+e.title+'</div>' +
    //                     '<div class="timeline-org"><a href="'+e.link+'" target="_blank">'+e.org+'</a></div>' +
    //                     '<div class="timeline-period">'+e.period+'</div>' +
    //                     '<div class="timeline-desc">'+e.desc+'</div>' +
    //                 '</div>' +
    //             '</div>';
    //         }).join('');

    //         // Education
    //         var eduList = document.getElementById('educationList');
    //         eduList.innerHTML = (p.education || []).map(function(e) {
    //             return '<div class="timeline-item">' +
    //                 '<div class="timeline-dot dot-green"></div>' +
    //                 '<div class="timeline-content">' +
    //                     '<div class="timeline-title">'+e.degree+'</div>' +
    //                     '<div class="timeline-org"><a href="'+e.link+'" target="_blank">'+e.school+'</a></div>' +
    //                     '<div class="timeline-period">'+e.period+'</div>' +
    //                 '</div>' +
    //             '</div>';
    //         }).join('');

    //         // Right panel
    //         document.getElementById('subjectTags').innerHTML = (p.subjects || []).map(function(s) {
    //             return '<span class="subject-tag">'+s+'</span>';
    //         }).join('');
    //         document.getElementById('detailDept').textContent        = p.department;
    //         document.getElementById('detailOffice').textContent      = p.office;
    //         document.getElementById('detailNationality').textContent = p.nationality;
    //         document.getElementById('detailLanguages').textContent   = p.languages;

    //     } catch(err) {
    //         console.error('[Profile] Load failed:', err.message);
    //     }
    // }

    async function loadProfile() {
        try {
            var res = await TeacherAPI.getProfile();
            var p   = res.data;

            // 🚨 NEW: Helper functions to safely update the DOM without crashing
            const safeSetText = (id, text) => {
                const el = document.getElementById(id);
                if (el) el.textContent = text || '—';
            };

            const safeSetHTML = (id, html) => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = html;
            };

            // 🚨 FIX 1: Restored correct Frontend Dev IDs!
            var initials = (p.firstName && p.lastName) ? (p.firstName[0] + p.lastName[0]).toUpperCase() : 'T';
            safeSetText('profileAvatar', initials);
            safeSetText('profileName', (p.title || '') + ' ' + (p.firstName || '') + ' ' + (p.lastName || ''));
            safeSetText('profileDept', p.department + ' \u2013 Smart University System');

            // Subject chips in banner (Corrected ID!)
            var chipsHtml = (p.subjects || []).map(function(s) {
                return '<span class="subject-chip">' + s + '</span>';
            }).join('');
            safeSetHTML('subjectChips', chipsHtml); 

            // Contact strip
            safeSetText('profileEmail', p.email);
            safeSetText('profilePhone', p.phone);
            safeSetText('profileOffice', p.office);
            
            // 🚨 FIX 2: Aggressively scrub the DB string so "Office hrs:" NEVER duplicates!
            let cleanHrs = (p.officeHours || '').replace(/^(Office hrs:\s*)+/gi, '').trim();
            safeSetText('profileHours', cleanHrs ? 'Office hrs: ' + cleanHrs : '—');

            // Bio
            safeSetText('profileBio', p.bio);

            // Experience
            var expHtml = (p.experience || []).map(function(e) {
                return '<div class="timeline-item">' +
                    '<div class="timeline-dot dot-blue"></div>' +
                    '<div class="timeline-content">' +
                        '<div class="timeline-title">'+e.title+'</div>' +
                        '<div class="timeline-org"><a href="'+e.link+'" target="_blank">'+e.org+'</a></div>' +
                        '<div class="timeline-period">'+e.period+'</div>' +
                        '<div class="timeline-desc">'+e.desc+'</div>' +
                    '</div>' +
                '</div>';
            }).join('');
            safeSetHTML('experienceList', expHtml);

            // Education
            var eduHtml = (p.education || []).map(function(e) {
                return '<div class="timeline-item">' +
                    '<div class="timeline-dot dot-green"></div>' +
                    '<div class="timeline-content">' +
                        '<div class="timeline-title">'+e.degree+'</div>' +
                        '<div class="timeline-org"><a href="'+e.link+'" target="_blank">'+e.school+'</a></div>' +
                        '<div class="timeline-period">'+e.period+'</div>' +
                    '</div>' +
                '</div>';
            }).join('');
            safeSetHTML('educationList', eduHtml);

            // Right panel
            var tagsHtml = (p.subjects || []).map(function(s) {
                return '<span class="subject-tag">' + s + '</span>';
            }).join('');
            safeSetHTML('subjectTags', tagsHtml);
            
            safeSetText('detailDept', p.department);
            safeSetText('detailOffice', p.office);
            safeSetText('detailNationality', p.nationality);
            safeSetText('detailLanguages', p.languages);

        } catch(err) {
            console.error('[Profile] Load failed:', err.message);
        }
    }

    // ==========================================
    // INLINE PROFILE EDITING LOGIC (FRONTEND DEV STYLE)
    // ==========================================
    var editMode = false;

    // Helper: Turn text into a small input box
    function replaceWithInput(id, val) {
        var el = document.getElementById(id);
        if (!el) return;
        var input = document.createElement('input');
        input.id = id;
        input.type = 'text';
        input.className = 'profile-inline-input'; 
        input.style.cssText = 'padding:6px 10px;border:1.5px solid #93c5fd;border-radius:8px;' +
            'font-size:13px;color:#1e293b;background:white;width:auto;min-width:180px;';
        input.value = (val === '—' || val === 'Not specified') ? '' : val;
        el.replaceWith(input);
    }

    // Helper: Turn text into a small textarea box
    function replaceWithTextarea(id, val) {
        var el = document.getElementById(id);
        if (!el) return;
        var input = document.createElement('textarea');
        input.id = id;
        input.rows = 3;
        input.className = 'profile-inline-input';
        input.style.cssText = 'width:100%;padding:8px 12px;border:1.5px solid #e2e8f0;' +
            'border-radius:8px;font-size:14px;margin-top:2px;resize:vertical;color:#1e293b;';
        input.style.marginTop = '8px';
        input.value = (val === '—' || val === 'Not specified') ? '' : val;
        el.replaceWith(input);
    }

    // Helper: Turn inputs back into standard text
    function restoreField(id, tag) {
        var el = document.getElementById(id);
        if (!el) return;
        // 🚨 FIX 3: Safely check for value so the Cancel button never crashes!
        var val = (el.value || '').trim(); 
        var newEl = document.createElement(tag);
        newEl.id = id;
        newEl.textContent = val || 'Not specified';
        el.replaceWith(newEl);
    }

    function toggleEditMode() {
        editMode = !editMode;
        var btn = document.getElementById('editProfileBtn');

        if (editMode) {
            btn.textContent = 'Cancel';

            // 🚨 FIX 3: Strips out ALL "Office hrs:" when editing, so you save a clean string!
            var rawHours = (document.getElementById('profileHours')?.textContent || '').replace(/^(Office hrs:\s*)+/gi, '').trim();
            var vals = {
                phone:       document.getElementById('profilePhone')?.textContent  || '',
                office:      document.getElementById('profileOffice')?.textContent  || '', // Fixed ID
                officeHours: rawHours,
                bio:         document.getElementById('profileBio')?.textContent    || ''
            };

            replaceWithInput('profilePhone',  vals.phone);
            replaceWithInput('profileOffice',  vals.office); // Fixed ID
            replaceWithInput('profileHours',  vals.officeHours);
            replaceWithTextarea('profileBio', vals.bio);

            // Inject Save Changes button next to Cancel
            var saveBtn = document.createElement('button');
            saveBtn.id          = 'saveProfileBtn';
            saveBtn.textContent = 'Save Changes';
            saveBtn.className   = 'edit-profile-btn';
            
            // Placed 130px from the right so it sits perfectly next to the Cancel button
            saveBtn.style.cssText = 'position:absolute; top:24px; right:130px; background:#16a34a; color:white; border:none;'; 
            saveBtn.onclick     = saveProfile;
            
            btn.parentNode.appendChild(saveBtn);

        } else {
            // Cancel clicked: Restore fields without saving
            restoreField('profilePhone',  'span');
            restoreField('profileOffice',  'span'); // Fixed ID
            restoreField('profileHours',  'span');
            restoreField('profileBio',    'p');

            var saveBtn = document.getElementById('saveProfileBtn');
            if (saveBtn) saveBtn.remove();
            
            btn.textContent = 'Edit Profile';
            editMode = false;
            
            // Reload original data to wipe out any unsaved typing
            loadProfile(); 
        }
    }

    async function saveProfile() {
        var btn = document.getElementById('saveProfileBtn');
        var cancelBtn = document.getElementById('editProfileBtn');
        
        btn.disabled    = true;
        btn.textContent = 'Saving...';
        cancelBtn.style.display = 'none'; // Hide cancel while processing

        var profileData = {
            phone:       document.getElementById('profilePhone')?.value  || '',
            office:      document.getElementById('profileOffice')?.value  || '', // Fixed ID
            officeHours: document.getElementById('profileHours')?.value  || '',
            bio:         document.getElementById('profileBio')?.value    || ''
        };

        try {
            // Send data to our Laravel backend!
            await apiCall('/teacher/profile', { 
                method: 'PUT', 
                body: JSON.stringify(profileData) 
            });
            
            // Clean up the UI after a successful save
            restoreField('profilePhone',  'span');
            restoreField('profileOffice',  'span');
            restoreField('profileHours',  'span');
            restoreField('profileBio',    'p');
            
            editMode = false;
            cancelBtn.textContent = 'Edit Profile';
            cancelBtn.style.display = 'block';
            btn.remove();
            
            // Refresh the page data from the database
            loadProfile(); 
            
        } catch (err) {
            btn.disabled    = false;
            btn.textContent = 'Save Changes';
            cancelBtn.style.display = 'block';
            alert('Could not save profile: ' + err.message);
        }
    }

    loadProfile();
</script>
</body>
</html>
