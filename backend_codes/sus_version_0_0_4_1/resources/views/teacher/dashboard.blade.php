<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – Smart University System</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <style>
        .app-container { display:flex; min-height:100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-collapsed);
            background: #2563eb;
            display: flex; flex-direction: column;
            position: fixed; height: 100vh; left: 0; top: 0;
            z-index: 1000; transition: var(--transition);
            overflow: hidden; color: white;
        }
        .sidebar.expanded { width: var(--sidebar-expanded); }
        .sidebar-toggle-btn {
            height: 60px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: background 0.2s;
        }
        .sidebar-toggle-btn:hover { background: rgba(255,255,255,0.15); }
        .sidebar-toggle-btn img { width:28px; height:28px; transition: transform 0.4s ease; }
        .sidebar.expanded .sidebar-toggle-btn img { transform: rotate(180deg); }
        .sidebar-icons { flex:1; display:flex; flex-direction:column; align-items:center; padding:20px 0; }
        .sidebar-icon {
            width: calc(100% - 30px); height: 48px; border-radius: 10px;
            display:flex; align-items:center; padding:0 18px; margin:4px 8px;
            cursor:pointer; transition: var(--transition); position:relative;
            color: rgba(255,255,255,0.85);
        }
        .sidebar-icon:hover, .sidebar-icon.active { background: rgba(255,255,255,0.18); color:white; }
        .sidebar-icon img { width:32px; height:32px; flex-shrink:0; margin-right:0; transition: margin-right 0.3s ease; }
        .sidebar.expanded .sidebar-icon img { margin-right:16px; }
        .sidebar-label {
            opacity:0; visibility:hidden; width:0; overflow:hidden;
            white-space:nowrap; font-size:15px; font-weight:500;
            transition: opacity 0.22s ease 0.05s, width 0.35s ease, visibility 0.35s;
        }
        .sidebar.expanded .sidebar-label { opacity:1; visibility:visible; width:auto; }
        .theme-toggle, .logout-icon { margin-top:auto; }
        .sidebar:not(.expanded) .sidebar-icon::after {
            content: attr(data-tooltip); position:fixed; left:98px;
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
        .logout-icon:hover { background: rgba(239,68,68,0.2); color:#ef4444; }

        /* ── Main ── */
        .main-content {
            margin-left: var(--sidebar-collapsed); transition: var(--transition);
            flex:1; padding:20px; max-width:2000px; position:relative; z-index:1;
        }
        .sidebar.expanded + .main-content { margin-left: var(--sidebar-expanded); }

        /* ── Top bar ── */
        .top-bar {
            background:white; padding:16px 24px; border-radius:12px;
            margin-bottom:24px; display:flex; justify-content:space-between;
            align-items:center; box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        .logo-container { height:70px; width:150px; overflow:hidden; display:flex; align-items:center; }
        .logo-container img { max-height:250%; height:auto; width:auto; object-fit:contain; margin-left:-40px; }
        .logo-light { display:block; }
        .logo-dark  { display:none; }
        body.dark-mode .logo-light { display:none; }
        body.dark-mode .logo-dark  { display:block; }
        .page-title { font-size:20px; font-weight:600; color:#1e293b; }

        /* Teacher identity badge (top-right of top bar) */
        .teacher-badge {
            display:flex; align-items:center; gap:10px;
        }
        .teacher-avatar-sm {
            width:40px; height:40px; border-radius:50%;
            background:#2563eb; color:white;
            display:flex; align-items:center; justify-content:center;
            font-size:14px; font-weight:700; flex-shrink:0;
        }
        .teacher-badge-info { text-align:right; }
        .teacher-badge-name  { font-size:14px; font-weight:600; color:#1e293b; }
        .teacher-badge-dept  { font-size:12px; color:#64748b; }

        /* ── Dashboard page title ── */
        .dash-title { font-size:20px; font-weight:700; color:#1e293b; margin-bottom:24px; text-align:center; }

        /* ── Stat cards row ── */
        .stats-row {
            display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px;
        }
        .stat-card {
            background:white; border-radius:12px; padding:20px 24px;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
            display:flex; align-items:center; gap:16px;
        }
        .stat-icon {
            width:48px; height:48px; border-radius:10px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .stat-icon img { width:28px; height:28px; }
        .stat-icon.blue   { background:#dbeafe; }
        .stat-icon.green  { background:#dcfce7; }
        .stat-icon.purple { background:#ede9fe; }
        .stat-icon.orange { background:#ffedd5; }
        .stat-number { font-size:32px; font-weight:700; line-height:1; }
        .stat-number.blue   { color:#2563eb; }
        .stat-number.green  { color:#16a34a; }
        .stat-number.purple { color:#7c3aed; }
        .stat-number.orange { color:#ea580c; }
        .stat-label  { font-size:13px; color:#64748b; margin-top:4px; }
        .stat-sub    { font-size:11px; color:#94a3b8; margin-top:2px; }

        /* ── Two-column grid ── */
        .dash-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }

        /* ── Today's classes panel ── */
        .panel {
            background:white; border-radius:12px; padding:24px;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        .panel-title {
            display:flex; align-items:center; gap:8px;
            font-size:15px; font-weight:600; margin-bottom:20px;
        }
        .panel-title img { width:20px; height:20px; }

        .class-item {
            display:flex; justify-content:space-between; align-items:center;
            padding:14px 0; border-bottom:1px solid #f1f5f9;
        }
        .class-item:last-child { border-bottom:none; padding-bottom:0; }
        .class-time  { font-size:13px; color:#64748b; margin-bottom:3px; }
        .class-name  { font-weight:600; font-size:14px; margin-bottom:4px; color:#1e293b; }
        .class-meta  { font-size:12px; color:#64748b; }
        .class-badge {
            padding:4px 10px; border-radius:4px;
            font-size:11px; font-weight:700; letter-spacing:0.3px;
            white-space:nowrap; flex-shrink:0;
        }
        .badge-lecture  { background:#dbeafe; color:#1e40af; }
        .badge-tutorial { background:#fef3c7; color:#92400e; }
        .badge-lab      { background:#dcfce7; color:#166534; }
        .badge-office   { background:#f3e8ff; color:#6b21a8; }

        /* ── Recent activity panel ── */
        .activity-item {
            display:flex; align-items:flex-start; gap:12px;
            padding:12px 0; border-bottom:1px solid #f1f5f9;
        }
        .activity-item:last-child { border-bottom:none; }
        .activity-icon {
            width:32px; height:32px; border-radius:6px;
            background:#f1f5f9; display:flex; align-items:center;
            justify-content:center; flex-shrink:0; margin-top:2px;
        }
        .activity-icon img { width:18px; height:18px; }
        .activity-icon.checked { background:#dcfce7; }
        .activity-body { flex:1; }
        .activity-student { font-weight:600; font-size:14px; color:#1e293b; }
        .activity-action  { font-size:13px; color:#64748b; margin-top:1px; }
        .activity-module  { font-size:12px; color:#94a3b8; font-style:italic; }
        .activity-time    { font-size:12px; color:#94a3b8; white-space:nowrap; flex-shrink:0; }

        /* dark mode */
        body.dark-mode .sidebar { background:#1e293b; }
        body.dark-mode .sidebar-icon::after { background:#334155; }
        body.dark-mode .sidebar-icon::before { border-right-color:#334155; }
        body.dark-mode .logout-icon { border-top-color:rgba(255,255,255,0.05); }
        body.dark-mode .top-bar,
        body.dark-mode .stat-card,
        body.dark-mode .panel { background:#1e293b; color:#e2e8f0; }
        body.dark-mode .page-title,
        body.dark-mode .dash-title,
        body.dark-mode .teacher-badge-name,
        body.dark-mode .class-name,
        body.dark-mode .panel-title,
        body.dark-mode .activity-student { color:#f1f5f9; }
        body.dark-mode .class-item,
        body.dark-mode .activity-item    { border-bottom-color:#334155; }
        body.dark-mode .activity-icon    { background:#334155; }

        @media (max-width:900px) {
            .stats-row { grid-template-columns:repeat(2,1fr); }
            .dash-grid  { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-toggle-btn">
            <img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle">
        </div>
        <div class="sidebar-icons">
            <div class="sidebar-icon active" data-page="dashboard" data-tooltip="Dashboard">
                <img src="{{ asset('images/home.png') }}" alt="Dashboard">
                <span class="sidebar-label">Dashboard</span>
            </div>
            <div class="sidebar-icon" data-page="timetable" data-tooltip="Timetable">
                <img src="{{ asset('images/calendar.png') }}" alt="Timetable">
                <span class="sidebar-label">Timetable</span>
            </div>
            <div class="sidebar-icon" data-page="modules" data-tooltip="My Modules">
                <img src="{{ asset('images/modules.png') }}" alt="Modules">
                <span class="sidebar-label">My Modules</span>
            </div>
            <div class="sidebar-icon" data-page="profile" data-tooltip="My Profile">
                <img src="{{ asset('images/person_white.png') }}" alt="Profile">
                <span class="sidebar-label">My Profile</span>
            </div>
            <div class="sidebar-icon" data-page="news" data-tooltip="News">
                <img src="{{ asset('images/news.png') }}" alt="News">
                <span class="sidebar-label">News</span>
            </div>
            <div class="sidebar-icon" data-page="career" data-tooltip="Career Centre">
                <img src="{{ asset('images/career.png') }}" alt="Career Centre">
                <span class="sidebar-label">Career Centre</span>
            </div>
            <div class="sidebar-icon" data-page="contact" data-tooltip="Contact">
                <img src="{{ asset('images/contact.png') }}" alt="Contact">
                <span class="sidebar-label">Contact</span>
            </div>
            <div class="sidebar-icon" data-page="help" data-tooltip="Help">
                <img src="{{ asset('images/help.png') }}" alt="Help">
                <span class="sidebar-label">Help</span>
            </div>
            <div class="sidebar-icon theme-toggle" data-tooltip="Toggle Theme">
                <img src="{{ asset('images/dark_mode.png') }}" alt="Dark Mode">
                <span class="sidebar-label">Dark Mode</span>
            </div>
            <div class="sidebar-icon logout-icon" data-tooltip="Logout">
                <img src="{{ asset('images/logout.png') }}" alt="Logout">
                <span class="sidebar-label">Logout</span>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        <!-- Top bar -->
        <div class="top-bar">
            <div class="logo-container">
                <img src="{{ asset('images/sus_logo.png') }}" alt="SUS" class="logo-light">
                <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SUS" class="logo-dark">
            </div>
            <h1 class="page-title">Dashboard</h1>
            <div class="teacher-badge">
                <div class="teacher-badge-info">
                    <div class="teacher-badge-name" id="badgeName">Dr. Sarah Johnson</div>
                    <div class="teacher-badge-dept" id="badgeDept">Computer Science</div>
                </div>
                <div class="teacher-avatar-sm" id="badgeAvatar">SJ</div>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <img src="{{ asset('images/calendar_today.png') }}" alt="">
                </div>
                <div>
                    <div class="stat-number blue" id="statUpcoming">--</div>
                    <div class="stat-label">Upcoming Classes</div>
                    <div class="stat-sub">Today</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <img src="{{ asset('images/doc.png') }}" alt="">
                </div>
                <div>
                    <div class="stat-number green" id="statSubmissions">--</div>
                    <div class="stat-label">New Submissions</div>
                    <div class="stat-sub">Pending review</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <img src="{{ asset('images/person.png') }}" alt="">
                </div>
                <div>
                    <div class="stat-number purple" id="statStudents">--</div>
                    <div class="stat-label">Total Students</div>
                    <div class="stat-sub">Across all courses</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <img src="{{ asset('images/modules_black.png') }}" alt="">
                </div>
                <div>
                    <div class="stat-number orange" id="statCourses">--</div>
                    <div class="stat-label">Courses Assigned</div>
                    <div class="stat-sub">This semester</div>
                </div>
            </div>
        </div>

        <!-- Two-column panels -->
        <div class="dash-grid">

            <!-- Today's Classes -->
            <div class="panel">
                <div class="panel-title">
                    <img src="{{ asset('images/calendar_today.png') }}" alt="">
                    Today's Classes
                </div>
                <div id="classesList">
                    <p style="color:#64748b;font-size:14px;">Loading...</p>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="panel">
                <div class="panel-title">
                    <img src="{{ asset('images/clock.png') }}" alt="">
                    Recent Activity
                </div>
                <div id="activityList">
                    <p style="color:#64748b;font-size:14px;">Loading...</p>
                </div>
            </div>

        </div>
    </main>
</div>

<script src="{{ asset('js/api.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('js/api.js') }}"></script> --}}
<script>
    // authGuard();

    window.ACTIVITY_ICONS = {
        graded:   "{{ asset('images/check_circle.png') }}",
        message:  "{{ asset('images/mail.png') }}",
        pending:  "{{ asset('images/doc.png') }}",     // or whatever default you use
        // you can add more statuses later
    };

    // Theme
    if (localStorage.getItem('darkMode') === 'enabled') document.body.classList.add('dark-mode');
    document.querySelector('.theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    });

    // Logout
    document.querySelector('.logout-icon').addEventListener('click', function() {
        if (confirm('Are you sure you want to logout?')) AuthAPI.logout();
    });

    // Sidebar
    var sidebar = document.querySelector('.sidebar');
    document.querySelector('.sidebar-toggle-btn').addEventListener('click', function() {
        sidebar.classList.toggle('expanded');
    });
    document.querySelector('.main-content').addEventListener('click', function(e) {
        if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target))
            sidebar.classList.remove('expanded');
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

    // Badge type colours
    var BADGE_TYPE = {
        'LECTURE': 'badge-lecture', 'TUTORIAL': 'badge-tutorial',
        'LAB': 'badge-lab', 'OFFICE': 'badge-office'
    };

    // Load dashboard
    async function loadDashboard() {
        try {
            var results = await Promise.all([
                TeacherAPI.getProfile(),
                TeacherAPI.getDashboardStats(),
                TeacherAPI.getTodayClasses(),
                TeacherAPI.getRecentActivity()
            ]);
            var profile    = results[0].data;
            var stats      = results[1].data;
            var classes    = results[2].data || [];
            var activities = results[3].data || [];

            // Badge
            var initials = (profile.firstName[0] + profile.lastName[0]).toUpperCase();
            document.getElementById('badgeAvatar').textContent = initials;
            document.getElementById('badgeName').textContent   = profile.title + ' ' + profile.firstName + ' ' + profile.lastName;
            document.getElementById('badgeDept').textContent   = profile.department;

            // Stats
            document.getElementById('statUpcoming').textContent    = stats.upcomingClasses;
            document.getElementById('statSubmissions').textContent  = stats.newSubmissions;
            document.getElementById('statStudents').textContent     = stats.totalStudents;
            document.getElementById('statCourses').textContent      = stats.coursesAssigned;

            // Today's classes
            var cl = document.getElementById('classesList');
            if (classes.length === 0) {
                cl.innerHTML = '<p style="color:#64748b;font-size:14px;">No classes today.</p>';
            } else {
                cl.innerHTML = classes.map(function(c) {
                    var typeText = c.type || 'Lecture';
                    var badgeCls = BADGE_TYPE[typeText.toUpperCase()] || 'badge-lecture';
                    var timeText = (c.startTime && c.endTime)
                        ? (c.startTime + ' – ' + c.endTime)
                        : (c.time || 'TBA');
                    return '<div class="class-item">' +
                        '<div>' +
                            '<div class="class-time">' + timeText + '</div>' +
                            '<div class="class-name">' + c.title + '</div>' +
                            '<div class="class-meta">' + c.room + ' \u00b7 ' + c.code + '</div>' +
                        '</div>' +
                        '<div class="class-badge ' + badgeCls + '">' + typeText + '</div>' +
                    '</div>';
                }).join('');
            }

            // Recent activity
            var al = document.getElementById('activityList');

            if (activities.length === 0) {
                al.innerHTML = '<p style="color:#64748b;font-size:14px;">No recent activity.</p>';
            } else {
                al.innerHTML = activities.map(function(a) {
                    // Use global object with fallback
                    var iconSrc = window.ACTIVITY_ICONS[a.status] || window.ACTIVITY_ICONS.pending || '/images/icons/doc.png';

                    var iconCls = a.checked ? 'activity-icon checked' : 'activity-icon';

                    return '<div class="activity-item">' +
                        '<div class="' + iconCls + '">' +
                            '<img src="' + iconSrc + '" alt="' + a.status + '">' +
                        '</div>' +
                        '<div class="activity-body">' +
                            '<div class="activity-student">' + a.student + '</div>' +
                            '<div class="activity-action">' + a.action + ' \u00b7 <em>' + a.module + '</em></div>' +
                        '</div>' +
                        '<div class="activity-time">' + a.time + '</div>' +
                    '</div>';
                }).join('');
            }

        } catch (err) {
            console.error('[Dashboard] Load failed:', err.message);
        }
    }

    loadDashboard();
</script>
</body>
</html>
