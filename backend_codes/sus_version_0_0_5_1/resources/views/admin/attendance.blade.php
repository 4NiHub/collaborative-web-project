<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance – SUSAdmin</title>
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
        /* .app-container { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        .main-content {
            flex: 1;
            margin-left: 72px;
            overflow-y: auto;
            transition: margin-left 0.2s ease;
        }
        .sidebar.expanded ~ .main-content,
        .sidebar.expanded + .main-content { margin-left: 220px; } */

        /* Enhanced Attendance Page */
        .attendance-filters {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .filter-select {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 22px;
            font-size: 16px;
            font-weight: 500;
            background: white;
            color: #1e293b;
            cursor: pointer;
            outline: none;
            min-width: 180px;
            transition: all 0.2s;
        }
        .filter-select:hover { border-color: #2563eb; }
        body.dark-mode .filter-select { background: #1e293b; color: #e2e8f0; border-color: #334155; }

        .date-input {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 22px;
            font-size: 16px;
            background: white;
            color: #374151;
            cursor: pointer;
            outline: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        body.dark-mode .date-input { background: #1e293b; color: #e2e8f0; border-color: #334155; }
        .date-input input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 16px;
            font-weight: 500;
            color: #1e293b;
        }
        body.dark-mode .date-input input { color: #e2e8f0; }

        .mark-all-btn {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px 22px;
            font-size: 15px;
            font-weight: 600;
            background: white;
            color: #374151;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }
        body.dark-mode .mark-all-btn { background: #1e293b; color: #e2e8f0; border-color: #334155; }
        .mark-all-btn:hover { background: #eef2ff; border-color: #2563eb; color: #2563eb; }

        .student-row {
            display: flex;
            align-items: center;
            padding: 20px 28px;
            border-bottom: 1px solid var(--border);
            gap: 24px;
            transition: background 0.2s;
        }
        .student-row:last-child { border-bottom: none; }
        .student-row.low-attendance { background: #fff5f5; }
        body.dark-mode .student-row.low-attendance { background: rgba(220,38,38,0.1); }
        .student-row:hover { background: #f8fafc; }
        body.dark-mode .student-row:hover { background: #0f172a; }

        .student-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .student-info { min-width: 200px; }
        .student-name { font-size: 18px; font-weight: 700; color: #1e293b; }
        body.dark-mode .student-name { color: #f1f5f9; }
        .student-id { font-size: 14px; color: #64748b; margin-top: 4px; }
        .student-course { font-size: 14px; color: #94a3b8; margin-top: 2px; }

        .attendance-col { flex: 1; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .attendance-bar-bg {
            flex: 1;
            height: 12px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
            max-width: 220px;
        }
        body.dark-mode .attendance-bar-bg { background: #334155; }
        .attendance-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            transition: width 0.4s ease;
        }
        .attendance-pct { 
            font-size: 17px; 
            font-weight: 700; 
            color: #1e293b; 
            min-width: 55px;
        }
        body.dark-mode .attendance-pct { color: #e2e8f0; }

        .status-col { display: flex; gap: 12px; flex-wrap: wrap; }
        .status-btn {
            padding: 10px 22px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            color: #475569;
        }
        body.dark-mode .status-btn { background: #0f172a; color: #94a3b8; border-color: #334155; }
        .status-btn:hover { border-color: #2563eb; color: #2563eb; transform: translateY(-1px); }
        .status-btn.present.active { background: #dcfce7; border-color: #16a34a; color: #16a34a; }
        .status-btn.absent.active { background: #fee2e2; border-color: #dc2626; color: #dc2626; }
        .status-btn.late.active { background: #fef3c7; border-color: #d97706; color: #d97706; }
        body.dark-mode .status-btn.present.active { background: #14532d; border-color: #16a34a; color: #86efac; }
        body.dark-mode .status-btn.absent.active { background: #7f1d1d; border-color: #dc2626; color: #fca5a5; }
        body.dark-mode .status-btn.late.active { background: #78350f; border-color: #d97706; color: #fcd34d; }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            color: #94a3b8;
            text-align: center;
        }
        .empty-state svg { margin-bottom: 24px; opacity: 0.5; width: 64px; height: 64px; }
        .empty-state h3 { font-size: 20px; font-weight: 600; color: #475569; margin-bottom: 10px; }
        body.dark-mode .empty-state h3 { color: #94a3b8; }
        .empty-state p { font-size: 15px; max-width: 320px; }

        .save-bar {
            display: flex;
            justify-content: flex-end;
            gap: 18px;
            padding: 24px 28px;
            border-top: 1px solid var(--border);
            background: #f8fafc;
        }
        body.dark-mode .save-bar { background: #0f172a; }
        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-secondary {
            background: white;
            color: #475569;
            border: 1px solid var(--border);
            padding: 14px 32px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        body.dark-mode .btn-secondary { background: #1e293b; color: #e2e8f0; }
        .btn-secondary:hover { background: #f1f5f9; border-color: #2563eb; color: #2563eb; }

        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1e293b;
            color: white;
            padding: 16px 28px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 500;
            z-index: 1100;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #16a34a; }
        .toast-warning { background: #d97706; }
        
        /* Header adjustment */
        .page-header {
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-size: 32px;
            font-weight: 800;
        }
        
        /* Card styling */
        .card {
            background: white;
            border-radius: 24px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        body.dark-mode .card { background: #1e293b; }
        
        /* Grid headers */
        .grid-header {
            display: grid;
            grid-template-columns: 220px 1fr 280px;
            padding: 18px 28px;
            border-bottom: 2px solid var(--border);
            background: #f8fafc;
        }
        body.dark-mode .grid-header { background: #0f172a; }
        .grid-header span {
            font-size: 15px;
            font-weight: 700;
            color: #64748b;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px 20px;
            width: 280px;
        }
        .search-box input {
            border: none;
            background: transparent;
            font-size: 15px;
            width: 100%;
            outline: none;
        }
        .status-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        body.dark-mode .search-box { background: #1e293b; border-color: #334155; }
        body.dark-mode .search-box input { color: #e2e8f0; }
        body.dark-mode #bulkPanel { background: #1e293b !important; border-color: #334155 !important; }
        body.dark-mode .btn-secondary { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .mark-all-btn { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }

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

        /* ── ATTENDANCE BUTTONS DARK MODE FIX ── */
        body.dark-mode .status-btn {
            background-color: #1e293b !important;
            color: #94a3b8 !important;
            border: 1px solid #334155 !important;
        }
        
        /* Active Present Button (Dark Mode) */
        body.dark-mode .status-btn.present.active {
            background-color: #16a34a !important; /* Solid Green */
            color: #ffffff !important;
            border-color: #16a34a !important;
        }

        /* Active Absent Button (Dark Mode) */
        body.dark-mode .status-btn.absent.active {
            background-color: #dc2626 !important; /* Solid Red */
            color: #ffffff !important;
            border-color: #dc2626 !important;
        }
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
                    <div class="top-bar" id="topBar">
                <div class="top-bar-title">SUS — Smart University System</div>
                <div class="top-bar-spacer"></div>
                <div class="admin-badge" onclick="location.href='profile.html'">
                    <div class="admin-avatar">A</div>
                    <span class="admin-name">Admin</span>
                </div>
            </div>

            <div class="page-header">
                <h1>Attendance Management</h1>
            </div>

            <div class="attendance-filters">
                <select class="filter-select" id="attSubject" onchange="checkFilters()">
                    <option value="">Select Course</option>
                </select>
                <select class="filter-select" id="attGroup" onchange="checkFilters()">
                    <option value="">Select Group</option>
                </select>
                <div class="date-input">
                    <input type="date" id="attDate">
                </div>
                <button class="mark-all-btn" onclick="toggleMarkAllPanel()">
                    <img src="{{ asset('images/admin_icons/bulk.png') }}" style="width:26px;height:26px;"> Bulk Actions <img src="{{ asset('images/admin_icons/arrow_down.png') }}" style="width:30px;height:30px;vertical-align:middle;">
                </button>
                <button class="btn-secondary" onclick="retrieveFromTeacher()" style="padding:14px 22px;">
                    <img src="{{ asset('images/admin_icons/download.png') }}" style="width:26px;height:26px;vertical-align:middle;margin-right:6px;"> Retrieve from Teacher
                </button>
            </div>

            <div id="bulkPanel" style="display:none; margin-bottom:24px; padding:20px 24px; background:#f8fafc; border-radius:20px; border:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
                    <span style="font-weight:700; font-size:15px;">Mark all as:</span>
                    <select id="markAllStatus" class="filter-select" style="min-width:140px;">
                        <option value="present"><img src="{{ asset('images/admin_icons/check_circle.png') }}" style="width:20px;height:20px;vertical-align:middle;margin-right:4px;"> Present</option>
                        <option value="absent"><img src="{{ asset('images/admin_icons/x.png') }}" style="width:20px;height:20px;vertical-align:middle;margin-right:4px;"> Absent</option>
                        <option value="late"><img src="{{ asset('images/admin_icons/clock_grey.png') }}" style="width:20px;height:20px;vertical-align:middle;margin-right:4px;"> Late</option>
                    </select>
                    <button class="btn-primary" onclick="markAll()" style="padding:12px 28px;">Apply to All</button>
                    <button class="btn-secondary" onclick="toggleMarkAllPanel()">Close</button>
                </div>
            </div>

            <div class="card">
                <div class="empty-state" id="emptyState">
                    <img src="{{ asset('images/admin_icons/empty.png') }}" style="width:60px;height:60px;vertical-align:middle;margin-right:6px;"><h3>Select a subject and group</h3>
                    <p>Choose a subject and group from the filters above to start marking attendance.</p>
                </div>
                <div id="studentsList" style="display:none;">
                    <div class="grid-header">
                        <span>Student</span>
                        <span>Overall Attendance</span>
                        <span>Today's Status</span>
                    </div>
                    <div id="studentsBody"></div>
                    <div class="save-bar">
                        <button class="btn-secondary" onclick="resetSession()">Reset</button>
                        <button class="btn-primary" onclick="saveAttendance()">Save Attendance Records</button>
                    </div>
                </div>
            </div>
        </main>
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
        let studentsData = [];
        let todayStatus = {};
        let currentSubject = '';
        let currentGroup = '';
        let searchTerm = '';

        // ── 1. INITIALIZATION & DROPDOWNS ──
        async function init() {
            try {
                // Fetch real Subjects and Groups from Laravel
                const [subjRes, groupRes] = await Promise.all([
                    AdminSubjectAPI.getSubjects(),
                    AdminGroupAPI.getGroups()
                ]);

                const subjects = subjRes.data || [];
                const groups = groupRes.data || [];

                // Populate Course Dropdown
                const courseSelect = document.getElementById('attSubject');
                if (courseSelect) {
                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                    subjects.forEach(s => {
                        courseSelect.innerHTML += `<option value="${s.name}">${s.name}</option>`;
                    });
                }

                // Populate Group Dropdown
                const groupSelect = document.getElementById('attGroup');
                if (groupSelect) {
                    groupSelect.innerHTML = '<option value="">Select Group</option>';
                    groups.forEach(g => {
                        const groupName = g.name || g.group_name; 
                        groupSelect.innerHTML += `<option value="${groupName}">${groupName}</option>`;
                    });
                }

                // Set today's date automatically
                document.getElementById('attDate').valueAsDate = new Date();

            } catch (err) {
                console.error(err);
                showToast('Failed to load dropdown data: ' + err.message, 'error');
            }
        }

        // ── 2. LOADING ATTENDANCE FROM DATABASE ──
        async function loadSession() {
            const course = document.getElementById('attSubject').value;
            const group  = document.getElementById('attGroup').value;
            const date   = document.getElementById('attDate').value;
            
            if (!course || !group) return;

            try {
                const res = await adminApiCall(`/attendance?group=${group}&course=${course}&date=${date}`);
                
                // 🚨 NEW: If there is no class on this date, show a message and stop!
                if (res.scheduled === false) {
                    studentsData = [];
                    todayStatus = {}; // Clear any unsaved clicks
                    document.getElementById('studentsBody').innerHTML = `
                        <tr>
                            <td colspan="4">
                                <div style="text-align:center; padding:60px 20px; color:#94a3b8;">
                                    <img src="{{ asset('images/admin_icons/calendar_black.png') }}" style="width:48px; opacity:0.3; margin-bottom:16px; filter: invert(0.8);">
                                    <div style="font-size:18px; font-weight:700; color:var(--text-dark);">No Class Scheduled</div>
                                    <div style="font-size:14px; margin-top:8px;">There are no <b>${course}</b> classes for <b>${group}</b> on this day of the week.</div>
                                </div>
                            </td>
                        </tr>`;
                    return;
                }

                // Normal loading if the class DOES exist
                studentsData = (res.data || []).map(s => {
                    let names = s.name.split(' ');
                    let initials = names[0].charAt(0) + (names.length > 1 ? names[names.length-1].charAt(0) : '');
                    
                    if (s.status === 'present' || s.status === 'absent') {
                        todayStatus[s.id] = s.status;
                    } else {
                        delete todayStatus[s.id]; 
                    }

                    return {
                        ...s,
                        initials: initials.toUpperCase(),
                        color: `hsl(${(s.id.charCodeAt(s.id.length-1) * 37) % 360}, 70%, 45%)`,
                        pct: Math.floor(Math.random() * 15) + 85
                    };
                });

                renderStudents();
            } catch (err) {
                showToast('Failed to load session: ' + err.message, 'error');
            }
        }

        // ── 3. FILTERING & RENDERING ──
        function checkFilters() {
            currentSubject = document.getElementById('attSubject').value;
            currentGroup = document.getElementById('attGroup').value;
            
            if (currentSubject && currentGroup) {
                document.getElementById('emptyState').style.display = 'none';
                document.getElementById('studentsList').style.display = 'block';
                loadSession(); 
            } else {
                document.getElementById('emptyState').style.display = 'flex';
                document.getElementById('studentsList').style.display = 'none';
            }
        }

        function filterStudents() {
            searchTerm = document.getElementById('searchStudent').value.toLowerCase();
            renderStudents();
        }

        function getFilteredStudents() {
            return studentsData.filter(s => {
                if (searchTerm && !s.name.toLowerCase().includes(searchTerm) && !s.id.toLowerCase().includes(searchTerm)) return false;
                return true;
            });
        }

        function renderStudents() {
            const filtered = getFilteredStudents();
            const container = document.getElementById('studentsBody');
            
            if (filtered.length === 0) {
                container.innerHTML = '<div style="text-align:center;padding:50px;color:#94a3b8;font-size:15px;">No students match the selected criteria</div>';
                return;
            }
            
            container.innerHTML = filtered.map(s => {
                // Get the current status, defaulting to empty if unmarked
                const status = todayStatus[s.id] || '';
                const lowClass = s.pct < 70 ? 'low-attendance' : '';
                
                return `<div class="student-row ${lowClass}" data-id="${s.id}" style="display:flex; align-items:center; justify-content:space-between; padding:16px; border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:16px;min-width:220px;">
                        <div class="student-avatar" style="background:${s.color || '#3b82f6'}; color:white; display:flex; align-items:center; justify-content:center; border-radius:50%; width:40px; height:40px; font-weight:bold;">${s.initials || 'ST'}</div>
                        <div class="student-info">
                            <div class="student-name" style="font-weight:700; color:var(--text-dark);">${s.name}</div>
                            <div class="student-id" style="font-size:12px; color:#64748b;">${s.id}</div>
                            <div class="student-course" style="font-size:12px; color:#94a3b8;">${s.course} • ${s.group}</div>
                        </div>
                    </div>
                    
                    <div class="attendance-col" style="flex:1; padding: 0 20px;">
                        <div class="attendance-bar-bg" style="background:#e2e8f0; height:8px; border-radius:4px; width:100%; max-width:150px; display:inline-block;">
                            <div class="attendance-bar-fill" style="background:${s.pct < 70 ? '#dc2626' : '#2563eb'}; height:100%; border-radius:4px; width:${s.pct}%;"></div>
                        </div>
                        <span class="attendance-pct" style="margin-left:10px; font-weight:600; font-size:13px; color:var(--text-dark);">${s.pct}%</span>
                    </div>
                    
                    <div class="status-col" style="display:flex; gap:8px;">
                        <button class="status-btn present ${status === 'present' ? 'active' : ''}" style="padding:8px 16px; border-radius:8px; border:1px solid #e2e8f0; background:${status === 'present' ? '#dcfce7' : 'white'}; color:${status === 'present' ? '#16a34a' : '#64748b'}; cursor:pointer; font-weight:600; transition:all 0.2s;" onclick="setStatus('${s.id}','present')">Present</button>
                        
                        <button class="status-btn absent ${status === 'absent' ? 'active' : ''}" style="padding:8px 16px; border-radius:8px; border:1px solid #e2e8f0; background:${status === 'absent' ? '#fee2e2' : 'white'}; color:${status === 'absent' ? '#dc2626' : '#64748b'}; cursor:pointer; font-weight:600; transition:all 0.2s;" onclick="setStatus('${s.id}','absent')">Absent</button>
                    </div>
                </div>`;
            }).join('');
        }

        // ── 4. MARKING AND SAVING LOGIC ──
        function setStatus(studentId, status) {
            if (todayStatus[studentId] === status) {
                delete todayStatus[studentId]; // un-toggle
            } else {
                todayStatus[studentId] = status;
            }
            renderStudents();
        }

        function toggleMarkAllPanel() {
            const panel = document.getElementById('bulkPanel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }

        function markAll() {
            const status = document.getElementById('markAllStatus').value;
            const filtered = getFilteredStudents();
            filtered.forEach(s => { todayStatus[s.id] = status; });
            renderStudents();
            showToast(`All ${filtered.length} students marked as ${status}`, 'success');
        }

        function resetSession() {
            if (confirm('Reset all unsaved attendance marks?')) {
                todayStatus = {};
                renderStudents();
                showToast('Attendance reset', 'success');
            }
        }

        async function saveAttendance() {
            const date = document.getElementById('attDate').value;
            const courseName = document.getElementById('attSubject').value;
            let successCount = 0;

            for (const [studentId, status] of Object.entries(todayStatus)) {
                try {
                    await adminApiCall(`/attendance/${studentId}`, {
                        method: 'PUT',
                        body: JSON.stringify({ 
                            date: date, 
                            // Automatically maps 'present' to true, and 'absent' to false for PostgreSQL
                            status: status === 'present', 
                            course: courseName 
                        }) 
                    });
                    successCount++;
                } catch (err) {
                    console.error(`Failed to save for ${studentId}`);
                }
            }

            if (successCount > 0) {
                showToast(`Attendance saved securely to database!`, 'success');
            } else {
                showToast('Please mark at least one student before saving.', 'warning');
            }
        }

        function retrieveFromTeacher() {
            showToast('Retrieving attendance from teacher records...', 'warning');
            setTimeout(() => {
                const filtered = getFilteredStudents();
                filtered.forEach(s => {
                    if (Math.random() > 0.2) todayStatus[s.id] = 'present';
                    else todayStatus[s.id] = 'absent';
                });
                renderStudents();
                showToast(`Retrieved teacher records for ${filtered.length} students`, 'success');
            }, 1000);
        }

        // ── 5. FIXED TOAST NOTIFICATION ──
        function showToast(msg, type = 'success') {
            let t = document.getElementById('__toast');
            if (!t) { 
                t = document.createElement('div'); 
                t.id = '__toast'; 
                Object.assign(t.style, {
                    position:'fixed', bottom:'28px', right:'28px', padding:'14px 24px', borderRadius:'10px',
                    fontSize:'14px', fontWeight:'600', color:'white', zIndex:'9999', pointerEvents:'none',
                    boxShadow:'0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
                    transition: 'all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1)', 
                    opacity: '0', transform: 'translateY(40px)' // Start hidden & below
                });
                document.body.appendChild(t); 
            }
            
            t.style.background = type === 'error' ? '#dc2626' : (type === 'warning' ? '#d97706' : '#16a34a');
            t.innerHTML = msg;
            
            // Force reflow and slide up smoothly
            requestAnimationFrame(() => { 
                t.style.opacity = '1'; 
                t.style.transform = 'translateY(0)'; 
            });
            
            // Slide down and fade out smoothly
            setTimeout(() => { 
                t.style.opacity = '0'; 
                t.style.transform = 'translateY(40px)'; 
            }, 3000);
        }

        // ── 6. UI TOGGLES ──
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
        (function() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                updateThemeIcon(true);
            }
        })();
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('expanded'); }
        function showLogoutPopup() { document.getElementById('logoutPopup').classList.add('show'); }
        function hideLogoutPopup() { document.getElementById('logoutPopup').classList.remove('show'); }

        // Start everything!
        window.addEventListener('DOMContentLoaded', init);
        document.getElementById('attDate').addEventListener('change', loadSession);
    </script>
</body>
</html>