<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Management – SUSAdmin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin_reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_sidebar.css') }}">
    <style>
        /* .sidebar {
            width: 100px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0 24px;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            transition: width 0.2s ease;
            z-index: 100;
            overflow: visible;
        }
        .sidebar.expanded { width: 220px; }

        .sidebar-toggle-btn {
            position: absolute;
            top: 20px; right: -12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #475569; z-index: 110;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .sidebar-icons {
            margin-top: 48px; width: 100%;
            display: flex; flex-direction: column; gap: 4px;
        }

        .sidebar-icon {
            display: flex; align-items: center;
            justify-content: center; gap: 12px;
            padding: 11px 14px; margin: 0 10px;
            border-radius: 12px; cursor: pointer;
            color: #475569; transition: all 0.18s;
            position: relative; white-space: nowrap;
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
        }
        .sidebar-icon:hover { background: #f1f5f9; color: #2563eb; }
        .sidebar-icon.active { background: #eef2ff; color: #2563eb; }
        .sidebar-icon svg { min-width: 20px; width: 20px; height: 20px; flex-shrink: 0; }

        .sidebar-label { font-size: 14px; font-weight: 500; display: none; }
        .sidebar.expanded .sidebar-label { display: inline-block; }
        .sidebar.expanded .sidebar-icon { justify-content: flex-start; padding: 11px 18px; }

        .logout-icon { margin-top: auto; margin-bottom: 8px; }
        .logout-icon:hover { background: #fff1f2 !important; color: #dc2626 !important; }

        .sidebar-icon[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute; left: 74px;
            background: #1e293b; color: white;
            font-size: 13px; font-weight: 500;
            padding: 5px 10px; border-radius: 7px;
            white-space: nowrap; z-index: 200;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .sidebar.expanded .sidebar-icon[data-tooltip]:hover::after { display: none; } */

        /* ── Layout ── */
        .app-container { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        /* .main-content {
            flex: 1;
            margin-left: 72px;
            overflow-y: auto;
            transition: margin-left 0.2s ease;
        }
        .sidebar.expanded ~ .main-content,
        .sidebar.expanded + .main-content { margin-left: 220px; } */

        /* Admin Teacher Management */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
            margin-bottom: 28px;
            max-width: 320px;
        }
        .stat-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.12); }
        body.dark-mode .stat-card { background: #1e293b; }
        .stat-icon {
            width: 56px;
            height: 56px;
            background: #eff6ff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .stat-number { font-size: 32px; font-weight: 800; color: #1e293b; line-height: 1.2; }
        body.dark-mode .stat-number { color: #e2e8f0; }
        .stat-label { font-size: 14px; color: #64748b; font-weight: 500; margin-top: 4px; }

        .list-controls {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 18px;
            flex: 1;
            max-width: 300px;
        }
        .search-box input {
            border: none;
            background: transparent;
            font-size: 14px;
            width: 100%;
            outline: none;
        }
        body.dark-mode .search-box { background: #1e293b; border-color: #334155; }
        body.dark-mode .search-box input { color: #e2e8f0; }

        .filter-select {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            background: white;
            cursor: pointer;
            outline: none;
        }
        body.dark-mode .filter-select { background: #1e293b; color: #e2e8f0; border-color: #334155; }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-secondary {
            background: white;
            color: #475569;
            border: 1px solid var(--border);
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        body.dark-mode .btn-secondary { background: #1e293b; color: #e2e8f0; }
        .btn-secondary:hover { background: #f1f5f9; border-color: #2563eb; color: #2563eb; }
        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-danger:hover { background: #b91c1c; }

        /* Teacher Grid */
        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .teacher-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 20px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .teacher-card:hover { border-color: #2563eb; transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        body.dark-mode .teacher-card { background: #1e293b; }
        .teacher-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
        .teacher-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
            flex-shrink: 0;
        }
        
        .teacher-info h3 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        body.dark-mode .teacher-info h3 { color: #f1f5f9; }
        .teacher-dept { font-size: 14px; color: #2563eb; font-weight: 600; margin-bottom: 4px; }
        .teacher-email { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 4px; }
        .teacher-stats { display: flex; gap: 16px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); }
        .stat-badge { font-size: 13px; color: #64748b8a; }
        .stat-badge strong { color: #1e293b; font-size: 14px; }
        body.dark-mode .stat-badge strong { color: #e2e8f0; }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            visibility: hidden;
            opacity: 0;
            transition: all 0.2s;
        }
        .modal-overlay.open { visibility: visible; opacity: 1; }
        .modal-box {
            background: white;
            border-radius: 28px;
            width: 500px;
            max-width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            padding: 28px;
        }
        body.dark-mode .modal-box { background: #1e293b; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 20px; font-weight: 700; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b; }
        .form-group { margin-bottom: 18px; }
        .form-label { font-size: 14px; font-weight: 600; margin-bottom: 6px; display: block; color: #475569; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            background: white;
        }
        body.dark-mode .form-input, body.dark-mode .form-select, body.dark-mode .form-textarea { background: #0f172a; color: #e2e8f0; border-color: #334155; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: #2563eb; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }

        /* Profile View */
        #profileView {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 24px;
        }
        body.dark-mode .back-btn { background: #1e293b; color: #e2e8f0; }
        .profile-layout { display: grid; grid-template-columns: 300px 1fr; gap: 24px; }
        @media (max-width: 700px) { .profile-layout { grid-template-columns: 1fr; } }
        .profile-card { background: white; border-radius: 20px; border: 1px solid var(--border); padding: 24px; }
        body.dark-mode .profile-card { background: #1e293b; }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.555);
            font-size: 36px;
            font-weight: 700;
            margin: 0 auto 16px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        .profile-name { font-size: 22px; font-weight: 800; text-align: center; margin-bottom: 8px; }
        .profile-badge { display: inline-block; padding: 5px 16px; background: #eff6ff; color: #2563eb; border-radius: 30px; font-size: 14px; font-weight: 600; margin-bottom: 20px; text-align: center; width: fit-content; margin-left: auto; margin-right: auto; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); }
        .info-label { color: #64748b; font-weight: 500; }
        .info-value { font-weight: 600; color: #1e293b; text-align: right; }
        body.dark-mode .info-value { color: #e2e8f0; }
        .profile-actions { display: flex; gap: 12px; margin-top: 20px; }
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1e293b;
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 14px;
            z-index: 1100;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }
        .teacher-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            font-size: 24px;
            font-weight: 700;
            flex-shrink: 0;
            background-color: #2563eb; /* fallback */
        }
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #16a34a; }
        .toast-error { background: #dc2626; }
        .teacher-email { display: flex; align-items: center; gap: 4px; }
        .teacher-stats .stat-badge { display: flex; align-items: center; gap: 4px; }
        .profile-actions button { display: inline-flex; align-items: center; gap: 6px; }
        .info-row .info-label { display: flex; align-items: center; gap: 4px; }
        body.dark-mode .profile-badge { background: #1e3a5f !important; color: #60a5fa !important; }
        body.dark-mode .back-btn { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .btn-secondary { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .modal-box { background: #1e293b !important; color: #e2e8f0 !important; }
        body.dark-mode .form-label { color: #94a3b8 !important; }

        /* ── Logout Popup Styles ── */
        .logout-popup-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(5px);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .logout-popup-overlay.show { opacity: 1; visibility: visible; }
        .logout-popup-modal {
            background: white; border-radius: 20px; padding: 32px;
            max-width: 380px; width: 90%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            transform: translateY(20px) scale(0.95); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .logout-popup-overlay.show .logout-popup-modal { transform: translateY(0) scale(1); }
        .logout-icon-large { font-size: 28px; color: #dc2626; background: #fee2e2; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .logout-popup-title { font-size: 20px; font-weight: 700; margin-bottom: 8px; color: #1e293b; }
        .logout-popup-text { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        .logout-actions { display: flex; gap: 12px; }
        .logout-btn-cancel, .logout-btn-confirm { flex: 1; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: background 0.2s; }
        .logout-btn-cancel { background: #f1f5f9; color: #475569; }
        .logout-btn-cancel:hover { background: #e2e8f0; }
        .logout-btn-confirm { background: #dc2626; color: white; }
        .logout-btn-confirm:hover { background: #b91c1c; }
        
        /* Dark Mode for Popup */
        body.dark-mode .logout-popup-modal { background: #1e293b; border: 1px solid #334155; }
        body.dark-mode .logout-popup-title { color: #f1f5f9; }
        body.dark-mode .logout-popup-text { color: #94a3b8; }
        body.dark-mode .logout-icon-large { background: #450a0a; color: #f87171; }
        body.dark-mode .logout-btn-cancel { background: #334155; color: #e2e8f0; }
        body.dark-mode .logout-btn-cancel:hover { background: #475569; }
    </style>
</head>
<body>
<div class="app-container">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <img src="{{ asset('images/admin_icons/arrow_left.png') }}" alt="Toggle" style="width:15px;height:15px;"> 
        </div>
        <nav class="sidebar-icons">
            <div class="sidebar-icon {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard" onclick="location.href='{{ route('admin.dashboard') }}'">
                <img src="{{ asset('images/admin_icons/dashboard.png') }}" alt="Dashboard">
                <span class="sidebar-label">Dashboard</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.users') ? 'active' : '' }}" data-tooltip="Users" onclick="location.href='{{ route('admin.users') }}'">
                <img src="{{ asset('images/admin_icons/users.png') }}" alt="Users">
                <span class="sidebar-label">Users</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.timetable') ? 'active' : '' }}" data-tooltip="Timetable" onclick="location.href='{{ route('admin.timetable') }}'">
                <img src="{{ asset('images/admin_icons/timetable.png') }}" alt="Timetable">
                <span class="sidebar-label">Timetable</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.content') ? 'active' : '' }}" data-tooltip="Content Manager" onclick="location.href='{{ route('admin.content') }}'">
                <img src="{{ asset('images/admin_icons/content.png') }}" alt="Content">
                <span class="sidebar-label">Content Manager</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.grading') ? 'active' : '' }}" data-tooltip="Grading" onclick="location.href='{{ route('admin.grading') }}'">
                <img src="{{ asset('images/admin_icons/grades.png') }}" alt="Grading">
                <span class="sidebar-label">Grading</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.news') ? 'active' : '' }}" data-tooltip="News CMS" onclick="location.href='{{ route('admin.news') }}'">
                <img src="{{ asset('images/admin_icons/news.png') }}" alt="News">
                <span class="sidebar-label">News CMS</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.attendance') ? 'active' : '' }}" data-tooltip="Attendance" onclick="location.href='{{ route('admin.attendance') }}'">
                <img src="{{ asset('images/admin_icons/attendance.png') }}" alt="Attendance">
                <span class="sidebar-label">Attendance</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.teachers') ? 'active' : '' }}" data-tooltip="Teachers" onclick="location.href='{{ route('admin.teachers') }}'">
                <img src="{{ asset('images/admin_icons/teachers.png') }}" alt="Teachers">
                <span class="sidebar-label">Teachers</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.contact') ? 'active' : '' }}" data-tooltip="Contact" onclick="location.href='{{ route('admin.contact') }}'">
                <img src="{{ asset('images/admin_icons/contact.png') }}" alt="Contact">
                <span class="sidebar-label">Contact</span>
            </div>
            <div class="sidebar-icon {{ request()->routeIs('admin.help') ? 'active' : '' }}" data-tooltip="Help" onclick="location.href='{{ route('admin.help') }}'">
                <img src="{{ asset('images/admin_icons/help_grey.png') }}" alt="Help">
                <span class="sidebar-label">Help</span>
            </div>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-icon theme-toggle-btn" id="themeToggle" data-tooltip="Toggle Theme" onclick="toggleTheme()">
                <img src="{{ asset('images/admin_icons/dark_mode.png') }}" alt="Dark Mode">
                <span class="sidebar-label">Toggle Theme</span>
            </div>
            <div class="sidebar-icon logout-icon" data-tooltip="Logout" onclick="showLogoutPopup()">
                <img src="{{ asset('images/admin_icons/logout.png') }}" alt="Logout">
                <span class="sidebar-label">Logout</span>
            </div>
        </div>
    </aside>

    <main class="main-content" id="main">
        <!-- Top Bar -->
                <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <!-- List View -->
        <div id="listView">
            <div class="page-header">
                <h1 style="font-size:30px;font-weight:800;">Teacher Management</h1>
                <p style="font-size:15px;color:#64748b;margin-top:6px;">Manage faculty profiles, departments, and contact information</p>
            </div>

            <!-- Stats  -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon"><img src="{{ asset('images/admin_icons/person_grey.png') }}" style="width:35px;height:35px;"></div>
                    <div><div class="stat-number" id="totalTeachers">0</div><div class="stat-label">Total Teachers</div></div>
                </div>
            </div>

            <div class="list-controls">
                <div class="search-box">
                    <img src="{{ asset('images/admin_icons/search.png') }}" style="width:16px;height:16px;">
                    <input type="text" id="searchInput" placeholder="Search teachers..." oninput="filterTeachers()">
                </div>
                <select class="filter-select" id="deptFilter" onchange="filterTeachers()">
                    <option value="">All Departments</option>
                </select>
                <button class="btn-primary" onclick="openAddTeacherModal()"><img src="{{ asset('images/admin_icons/add.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;"> Add Teacher</button>
            </div>

            <div class="teachers-grid" id="teachersGrid"></div>
        </div>

        <!-- Profile View -->
        <div id="profileView">
            <button class="back-btn" onclick="showListView()"><img src="{{ asset('images/admin_icons/arrow_right.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;">Back to Teachers</button>
            <div class="profile-layout" id="profileContent"></div>
        </div>
    </main>
</div>

<!-- Add/Edit Teacher Modal -->
<div class="modal-overlay" id="teacherModal">
    <div class="modal-box">
        <div class="modal-header">
            <span id="modalTitle">Add New Teacher</span>
            <button class="modal-close" onclick="closeTeacherModal()">✕</button>
        </div>
        <div id="modalForm">
            <div class="form-group"><label class="form-label">Title</label><input type="text" class="form-input" id="teacherTitle" placeholder="Dr./Prof./Mr./Ms."></div>
            <div class="form-group"><label class="form-label">First Name *</label><input type="text" class="form-input" id="teacherFirst" placeholder="First name"></div>
            <div class="form-group"><label class="form-label">Last Name *</label><input type="text" class="form-input" id="teacherLast" placeholder="Last name"></div>
            <div class="form-group"><label class="form-label">Email *</label><input type="email" class="form-input" id="teacherEmail" placeholder="email@university.edu"></div>
            <div class="form-group"><label class="form-label">Phone</label><input type="text" class="form-input" id="teacherPhone" placeholder="+44 123 456 789"></div>
            <div class="form-group"><label class="form-label">Department *</label><input type="text" class="form-input" id="teacherDept" placeholder="e.g., Computer Science"></div>
            <div class="form-group"><label class="form-label">Office Location</label><input type="text" class="form-input" id="teacherOffice" placeholder="Building A, Room 101"></div>
            <div class="form-group"><label class="form-label">Office Hours</label><input type="text" class="form-input" id="teacherHours" placeholder="Mon & Wed 2-4 PM"></div>
            <div class="form-group"><label class="form-label">Subjects (comma-separated)</label><input type="text" class="form-input" id="teacherSubjects" placeholder="e.g., Data Structures, Algorithms"></div>
            <div class="form-group"><label class="form-label">Bio</label><textarea class="form-textarea" id="teacherBio" rows="3" placeholder="Short biography..."></textarea></div>
        </div>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeTeacherModal()">Cancel</button>
            <button class="btn-primary" id="saveTeacherBtn" onclick="saveTeacher()">Save Teacher</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-header">Delete Teacher <button class="modal-close" onclick="closeDeleteModal()">✕</button></div>
        <p style="font-size:15px; margin:16px 0;">Are you sure you want to delete this teacher? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" id="confirmDeleteBtn">Delete Permanently</button>
        </div>
    </div>
</div>

<!-- Logout Confirmation Popup -->
<div class="logout-popup-overlay" id="logoutPopup">
    <div class="logout-popup-modal">
        <div class="logout-icon-large">
            <img src="{{ asset('images/admin_icons/logout.png') }}" alt="Logout" style="width:32px; height:32px; filter: invert(27%) sepia(91%) saturate(5421%) hue-rotate(345deg) brightness(93%) contrast(93%);">
        </div>
        <h3 class="logout-popup-title">Log Out</h3>
        <p class="logout-popup-text">Are you sure you want to log out of the Admin panel?</p>
        <div class="logout-actions">
            <button class="logout-btn-cancel" onclick="hideLogoutPopup()">Cancel</button>
            <button class="logout-btn-confirm" onclick="AdminAuthAPI.logout()">Log Out</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-api.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('js/admin-api.js') }}"></script> --}}

<script>
// Teacher Data Store
let teachers = [];

async function init() {
    try {
        const res = await AdminTeacherAPI.getTeachers();
        teachers = res.data;
        renderTeachersGrid();
        populateDeptFilter();
    } catch (err) {
        showToast('Failed to load teachers', 'error');
    }
}
init();

let deleteTargetId = null;
let editTargetId = null;
let currentFilter = '';
let currentSearch = '';

function updateStats() {
    document.getElementById('totalTeachers').innerText = teachers.length;
}

function getFilteredTeachers() {
    return teachers.filter(t => {
        const fullName = `${t.title || ''} ${t.firstName} ${t.lastName}`.toLowerCase();
        const matchesSearch = !currentSearch || fullName.includes(currentSearch) || t.email.toLowerCase().includes(currentSearch) || t.department.toLowerCase().includes(currentSearch);
        const matchesDept = !currentFilter || t.department === currentFilter;
        return matchesSearch && matchesDept;
    });
}

function renderTeachersGrid() {
    const filtered = getFilteredTeachers();
    const grid = document.getElementById('teachersGrid');
    
    if (filtered.length === 0) {
        grid.innerHTML = '<div style="text-align:center;padding:60px;color:#94a3b8;">No teachers found. Click "Add Teacher" to create a new profile.</div>';
        return;
    }
    
    grid.innerHTML = filtered.map(t => {
        const initials = (t.firstName[0] + t.lastName[0]).toUpperCase();
        const subjectsText = t.subjects ? t.subjects.slice(0, 2).join(', ') + (t.subjects.length > 2 ? '...' : '') : '—';
        return `
            <div class="teacher-card" onclick="showProfile(${t.id})">
                <div class="teacher-header">
                    <div class="teacher-avatar" data-color="${t.color}">${initials}</div>
                    <div class="teacher-info">
                        <h3>${t.title || ''} ${t.firstName} ${t.lastName}</h3>
                        <div class="teacher-dept">${t.department}</div>
                        <div class="teacher-email"><img src="{{ asset('images/admin_icons/contact.png') }}" style="width:20px;height:20px;vertical-align:middle;margin-right:4px;"> ${t.email}</div>
                    </div>
                </div>
                <div class="teacher-stats">
                    <div class="stat-badge"><img src="{{ asset('images/admin_icons/assignment.png') }}" style="width:20px;height:20px;vertical-align:middle;margin-right:4px;"> ${subjectsText}</div>
                </div>
            </div>
        `;
    }).join('');
    updateStats();
}

function filterTeachers() {
    currentSearch = document.getElementById('searchInput').value.toLowerCase();
    currentFilter = document.getElementById('deptFilter').value;
    renderTeachersGrid();
}

function populateDeptFilter() {
    const depts = [...new Set(teachers.map(t => t.department))];
    const select = document.getElementById('deptFilter');
    select.innerHTML = '<option value="">All Departments</option>' + depts.map(d => `<option value="${d}">${d}</option>`).join('');
}

function openAddTeacherModal() {
    editTargetId = null;
    document.getElementById('modalTitle').innerText = 'Add New Teacher';
    document.getElementById('teacherTitle').value = '';
    document.getElementById('teacherFirst').value = '';
    document.getElementById('teacherLast').value = '';
    document.getElementById('teacherEmail').value = '';
    document.getElementById('teacherPhone').value = '';
    document.getElementById('teacherDept').value = '';
    document.getElementById('teacherOffice').value = '';
    document.getElementById('teacherHours').value = '';
    document.getElementById('teacherSubjects').value = '';
    document.getElementById('teacherBio').value = '';
    document.getElementById('teacherModal').classList.add('open');
}

function editTeacher(id) {
    const teacher = teachers.find(t => t.id === id);
    if (!teacher) return;
    editTargetId = id;
    document.getElementById('modalTitle').innerText = 'Edit Teacher';
    document.getElementById('teacherTitle').value = teacher.title || '';
    document.getElementById('teacherFirst').value = teacher.firstName;
    document.getElementById('teacherLast').value = teacher.lastName;
    document.getElementById('teacherEmail').value = teacher.email;
    document.getElementById('teacherPhone').value = teacher.phone || '';
    document.getElementById('teacherDept').value = teacher.department;
    document.getElementById('teacherOffice').value = teacher.officeLocation || '';
    document.getElementById('teacherHours').value = teacher.officeHours || '';
    document.getElementById('teacherSubjects').value = (teacher.subjects || []).join(', ');
    document.getElementById('teacherBio').value = teacher.bio || '';
    document.getElementById('teacherModal').classList.add('open');
}

async function saveTeacher() {
    const firstName = document.getElementById('teacherFirst').value.trim();
    const lastName = document.getElementById('teacherLast').value.trim();
    const email = document.getElementById('teacherEmail').value.trim();
    const department = document.getElementById('teacherDept').value.trim();
    
    if (!firstName || !lastName || !email || !department) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    const subjectsStr = document.getElementById('teacherSubjects').value;
    const subjects = subjectsStr ? subjectsStr.split(',').map(s => s.trim()).filter(s => s) : [];
    
    const teacherData = {
        title: document.getElementById('teacherTitle').value,
        firstName: firstName,
        lastName: lastName,
        email: email,
        phone: document.getElementById('teacherPhone').value,
        department: department,
        officeLocation: document.getElementById('teacherOffice').value,
        officeHours: document.getElementById('teacherHours').value,
        subjects: subjects,
        bio: document.getElementById('teacherBio').value,
        color: ['#2563eb', '#7c3aed', '#0891b2', '#d97706', '#dc2626', '#0284c7'][Math.floor(Math.random() * 6)]
    };
    
    try {
        if (editTargetId) {
            await AdminTeacherAPI.updateTeacher(editTargetId, teacherData);
            showToast('Teacher updated successfully', 'success');
        } else {
            await AdminTeacherAPI.createTeacher(teacherData);
            showToast('Teacher added successfully', 'success');
        }

        const res = await AdminTeacherAPI.getTeachers();
        teachers = res.data;

        closeTeacherModal();
        populateDeptFilter();
        renderTeachersGrid();
    } catch (err) {
        showToast('Error: ' + err.message, 'error');
    }
}

function confirmDeleteTeacher(id) {
    deleteTargetId = id;
    document.getElementById('deleteModal').classList.add('open');
}

async function deleteTeacher() {
    if (deleteTargetId) {
        try {
            await AdminTeacherAPI.deleteTeacher(deleteTargetId);
            const res = await AdminTeacherAPI.getTeachers();
            teachers = res.data;
            showToast('Teacher deleted successfully', 'success');
            closeDeleteModal();
            populateDeptFilter();
            renderTeachersGrid();
        } catch (err) {
            showToast('Error: ' + err.message, 'error');
        }
        if (document.getElementById('profileView').style.display !== 'none') {
            showListView();
        }
    }
}

function closeTeacherModal() { document.getElementById('teacherModal').classList.remove('open'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('open'); }

function showProfile(id) {
    const teacher = teachers.find(t => t.id === id);
    if (!teacher) return;
    
    const initials = (teacher.firstName[0] + teacher.lastName[0]).toUpperCase();
    const fullName = `${teacher.title || ''} ${teacher.firstName} ${teacher.lastName}`;
    const subjectsText = (teacher.subjects || []).join(', ');
    
    document.getElementById('profileContent').innerHTML = `
        <div class="profile-card">
            <div class="profile-avatar" style="background:${teacher.color};">${initials}</div>
            <div class="profile-name">${fullName}</div>
            <div class="profile-badge">${teacher.department}</div>
            <div class="info-row"><span class="info-label">Email</span><span class="info-value">${teacher.email}</span></div>
            <div class="info-row"><span class="info-label">Phone</span><span class="info-value">${teacher.phone || '—'}</span></div>
            <div class="info-row"><span class="info-label">Office</span><span class="info-value">${teacher.officeLocation || '—'}</span></div>
            <div class="info-row"><span class="info-label">Office Hours</span><span class="info-value">${teacher.officeHours || '—'}</span></div>
            <div class="info-row"><span class="info-label">Subjects</span><span class="info-value">${subjectsText || '—'}</span></div>
            <div class="profile-actions">
                <button class="btn-secondary" onclick="editTeacher(${teacher.id}); showListView(); setTimeout(()=>document.getElementById('teacherModal').classList.add('open'),100)"><img src="{{ asset('images/admin_icons/edit_grey.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;"> Edit</button>
                <button class="btn-danger" onclick="confirmDeleteTeacher(${teacher.id})"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;"> Delete</button>
            </div>
        </div>
        <div class="profile-card">
            <h3 style="font-size:18px;font-weight:700;margin-bottom:16px;">Biography</h3>
            <p style="font-size:14px;line-height:1.6;color:#475569;">${teacher.bio || 'No biography provided.'}</p>
            <div style="margin-top:20px;">
                <h3 style="font-size:16px;font-weight:700;margin-bottom:12px;">Course Load</h3>
                <div class="info-row"><span class="info-label">Current Modules</span><span class="info-value">${(teacher.subjects || []).length} courses</span></div>
            </div>
        </div>
    `;
    
    document.getElementById('listView').style.display = 'none';
    document.getElementById('profileView').style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showListView() {
    document.getElementById('profileView').style.display = 'none';
    document.getElementById('listView').style.display = 'block';
    renderTeachersGrid();
}

function showToast(msg, type = 'success') {
    let t = document.getElementById('__toast');
    if (!t) { t = document.createElement('div'); t.id = '__toast'; t.className = 'toast-notification'; document.body.appendChild(t); }
    t.className = `toast-notification toast-${type}`;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}



document.getElementById('confirmDeleteBtn').onclick = deleteTeacher;
populateDeptFilter();
renderTeachersGrid();
</script>
<script>
    /* ── Theme toggle ── */
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        updateThemeIcon(isDark);
    }
    function updateThemeIcon(isDark) {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        const moon = btn.querySelector('.theme-icon-moon');
        const sun  = btn.querySelector('.theme-icon-sun');
        if (moon) moon.style.display = isDark ? 'none' : '';
        if (sun)  sun.style.display  = isDark ? '' : 'none';
    }
    /* Apply saved theme on load */
    (function() {
        const saved = localStorage.getItem('darkMode');
        if (saved === 'enabled') {
            document.body.classList.add('dark-mode');
            updateThemeIcon(true);
        }
    })();
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('expanded');
    }
    function showLogoutPopup() {
    document.getElementById('logoutPopup').classList.add('show');
    }
    function hideLogoutPopup() {
        document.getElementById('logoutPopup').classList.remove('show');
    }
</script>
</body>
</html>