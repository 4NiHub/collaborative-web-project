<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Grading – SUSAdmin</title>
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

        /* Layout */
        /* .app-container { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        .main-content {
            flex: 1;
            margin-left: 72px;
            overflow-y: auto;
            transition: margin-left 0.2s ease;
        }
        .sidebar.expanded ~ .main-content,
        .sidebar.expanded + .main-content { margin-left: 220px; } */


        .grade-filters {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .filter-select {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            background: white;
            color: #1e293b;
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
        }
        .filter-select:hover { border-color: #2563eb; }
        body.dark-mode .filter-select { background: #1e293b; color: #e2e8f0; border-color: #334155; }

        
        .grade-input {
            width: 80px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
            background: #f8fafc;
            color: #1e293b;
            outline: none;
            transition: all 0.2s;
        }
        body.dark-mode .grade-input { background: #0f172a; color: #e2e8f0; border-color: #334155; }
        .grade-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

        /* Larger table text */
        .data-table th {
            font-size: 14px;
            font-weight: 700;
            padding: 16px 12px;
            background: #f8fafc;
            color: #1e293b;
        }
        .data-table td {
            font-size: 14px;
            padding: 14px 12px;
            vertical-align: middle;
        }
        body.dark-mode .data-table th { background: #0f172a; color: #e2e8f0; }

        .final-grade {
            font-size: 16px;
            font-weight: 800;
            padding: 6px 0;
            display: inline-block;
            min-width: 55px;
            text-align: center;
        }
        .grade-A { color: #16a34a; background: #dcfce7; padding: 4px 12px; border-radius: 20px; display: inline-block; }
        .grade-B { color: #3b82f6; background: #dbeafe; padding: 4px 12px; border-radius: 20px; display: inline-block; }
        .grade-C { color: #eab308; background: #fef9c3; padding: 4px 12px; border-radius: 20px; display: inline-block; }
        .grade-D { color: #f97316; background: #ffedd5; padding: 4px 12px; border-radius: 20px; display: inline-block; }
        .grade-F { color: #dc2626; background: #fee2e2; padding: 4px 12px; border-radius: 20px; display: inline-block; }
        
        .gpa-val {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }
        body.dark-mode .gpa-val { background: #0f172a; color: #e2e8f0; }

        /* Modal enhancements */
        .modal-box { border-radius: 28px; padding: 28px; }
        .form-input, .form-select {
            padding: 12px 14px;
            font-size: 14px;
            border-radius: 12px;
        }
        
        /* Weight row styling */
        .weight-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }
        .weight-row:last-child { border-bottom: none; }
        .weight-input {
            width: 100px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid var(--border);
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }
        
        /* Badge for status */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }
        
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1e293b;
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            z-index: 1100;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #16a34a; }
        .toast-error { background: #dc2626; }
        body.dark-mode .status-badge { background: #334155 !important; color: #e2e8f0 !important; }
        body.dark-mode .grade-A { background: #14532d !important; color: #86efac !important; }
        body.dark-mode .grade-B { background: #1e3a5f !important; color: #93c5fd !important; }
        body.dark-mode .grade-C { background: #713f12 !important; color: #fcd34d !important; }
        body.dark-mode .grade-D { background: #7c2d12 !important; color: #fdba74 !important; }
        body.dark-mode .grade-F { background: #7f1d1d !important; color: #fca5a5 !important; }
        body.dark-mode .gpa-val { background: #1e293b !important; color: #e2e8f0 !important; }
        body.dark-mode .grade-input { background: #0f172a !important; color: #e2e8f0 !important; }
        body.dark-mode .btn-secondary { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .weight-input { background: #0f172a !important; color: #e2e8f0 !important; border-color: #334155 !important; }
        body.dark-mode .modal-box { background: #1e293b !important; color: #e2e8f0 !important; }
        body.dark-mode .modal-header { background: #1e293b !important; border-color: #334155 !important; }
        body.dark-mode .modal-close { background: #334155 !important; color: #e2e8f0 !important; }
        body.dark-mode .weight-row { border-color: #334155 !important; }

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
        <!-- Top bar -->
                <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <div class="page-header">
            <div class="page-header-left">
                <h1 style="font-size:28px;font-weight:800;">Grade Management</h1>
                <p style="font-size:15px;color:#64748b;margin-top:6px;">Manage grades across courses, groups, and subjects</p>
            </div>
            <div class="export-btns" style="display:flex;gap:12px;">
                <button class="btn-secondary" onclick="openWeightModal()" style="font-size:13px;font-weight:600;">
                    <img src="{{ asset('images/admin_icons/weights.png') }}" style="width:25px;height:25px;">
                    Weights
                </button>
                <button class="btn-primary" onclick="exportGradesCSV()" style="font-size:13px;font-weight:600;">
                    <img src="{{ asset('images/admin_icons/download_white.png') }}" style="width:25px;height:25px;">
                    Export CSV
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="grade-filters">
            <select class="filter-select" id="courseFilter" onchange="filterGrades()">
                <option value="">All Courses</option>
            </select>
            <select class="filter-select" id="groupFilter" onchange="filterGrades()">
                <option value="">All Groups</option>
            </select>
            <button class="btn-secondary" onclick="openBulkModal()" style="font-size:13px;">
                <img src="{{ asset('images/admin_icons/edit.png') }}" style="width:20px;height:20px;">
                Bulk Edit
            </button>
            <span class="status-badge" id="recordCount">0 students</span>
        </div>

        <!-- Grade table -->
        <div class="card" style="overflow-x: auto; border-radius: 20px;">
            <table class="data-table" id="gradeTable" style="width:100%; min-width:900px;">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Group</th>
                        <th>Assignment<br><span style="font-size:11px; font-weight:normal;" id="assignPercent">(30%)</span></th>
                        <th>Midterm<br><span style="font-size:11px; font-weight:normal;" id="midPercent">(30%)</span></th>
                        <th>Final<br><span style="font-size:11px; font-weight:normal;" id="finalPercent">(40%)</span></th>
                        <th>Final Grade</th>
                        <th>GPA</th>
                    </tr>
                </thead>
                <tbody id="gradeBody"></tbody>
            </table>
        </div>
    </main>
</div>

<!-- Weight settings modal -->
<div class="modal-overlay" id="weightModal">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-header"><div class="modal-title">Grade Weight Configuration</div><button class="modal-close" onclick="closeWeightModal()">✕</button></div>
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="weight-row">
                <span style="font-weight:600;display:flex;align-items:center;gap:6px;">
                    <img src="{{ asset('images/admin_icons/content.png') }}" style="width:16px;height:16px;">Assignments
                </span>
                <input type="number" class="weight-input" id="assignWeight" value="30" min="0" max="100" step="5">
            </div>

            <div class="weight-row">
                <span style="font-weight:600;display:flex;align-items:center;gap:6px;">
                    <img src="{{ asset('images/admin_icons/content.png') }}" style="width:16px;height:16px;">Midterm Exam
                </span>
                <input type="number" class="weight-input" id="midWeight" value="30" min="0" max="100" step="5">
            </div>

            <div class="weight-row">
                <span style="font-weight:600;display:flex;align-items:center;gap:6px;">
                    <img src="{{ asset('images/admin_icons/assignment.png') }}" style="width:16px;height:16px;">Final Exam
                </span>
                <input type="number" class="weight-input" id="finalWeight" value="40" min="0" max="100" step="5">
            </div><p style="font-size:12px;color:#64748b;margin-top:8px;text-align:center;">Total must equal 100%</p>
        </div>
        <div class="modal-actions" style="margin-top:24px;"><button class="btn-secondary" onclick="closeWeightModal()">Cancel</button><button class="btn-primary" onclick="applyWeights()">Apply</button></div>
    </div>
</div>

<!-- Bulk edit modal -->
<div class="modal-overlay" id="bulkModal">
    <div class="modal-box" style="max-width:480px;">
        <div class="modal-header"><div class="modal-title">Bulk Grade Adjustment</div><button class="modal-close" onclick="closeBulkModal()">✕</button></div>
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div><label class="form-label">Component</label><select class="form-select" id="bulkComponent"><option value="assignments">Assignments</option><option value="midterm">Midterm</option><option value="final">Final</option></select></div>
            <div><label class="form-label">Operation</label><select class="form-select" id="bulkOp"><option value="add">Add points (+)</option><option value="multiply">Multiply by (%)</option><option value="set">Set to value</option></select></div>
            <div><label class="form-label">Value</label><input type="number" class="form-input" id="bulkValue" placeholder="e.g., 5 or 1.1" step="any"></div>
            <div><label class="form-label" style="display:flex;align-items:center;gap:10px;"><input type="checkbox" id="bulkFiltered" checked style="width:18px;height:18px;"> Apply only to currently filtered students</label></div>
        </div>
        <div class="modal-actions"><button class="btn-secondary" onclick="closeBulkModal()">Cancel</button><button class="btn-primary" onclick="applyBulkEdit()">Apply Changes</button></div>
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
    let allGrades = [];
    let weightA = 0.30, weightM = 0.30, weightF = 0.40;

    // ── 1. INITIALIZATION ──
    async function init() {
        try {
            // Fetch real Subjects and Groups from PostgreSQL
            const [subjRes, groupRes] = await Promise.all([
                AdminSubjectAPI.getSubjects(),
                AdminGroupAPI.getGroups()
            ]);

            const subjects = subjRes.data || [];
            const groups = groupRes.data || [];

            // Populate Course Dropdown
            const courseSelect = document.getElementById('courseFilter');
            if (courseSelect) {
                courseSelect.innerHTML = '<option value="">All Courses</option>';
                subjects.forEach(s => {
                    courseSelect.innerHTML += `<option value="${s.name}">${s.name}</option>`;
                });
            }

            // Populate Group Dropdown
            const groupSelect = document.getElementById('groupFilter');
            if (groupSelect) {
                groupSelect.innerHTML = '<option value="">All Groups</option>';
                groups.forEach(g => {
                    const groupName = g.name || g.group_name; 
                    groupSelect.innerHTML += `<option value="${groupName}">${groupName}</option>`;
                });
            }

            updateWeightDisplay();
            await loadGrades();

        } catch (err) {
            console.error(err);
            showToast('Failed to load dropdown data: ' + err.message, 'error');
        }
    }

    // ── 2. DATA LOADING & RENDERING ──
    async function loadGrades() {
        try {
            const course = document.getElementById('courseFilter')?.value || '';
            const group = document.getElementById('groupFilter')?.value || '';

            // Fetch grades straight from Laravel API
            const res = await AdminGradeAPI.getGrades(group, course);
            allGrades = res.data || [];
            
            // Update the count UI
            const countEl = document.getElementById('recordCount');
            if(countEl) countEl.innerHTML = `${allGrades.length} student${allGrades.length !== 1 ? 's' : ''}`;
            
            renderGrades();
        } catch (err) {
            showToast('Failed to load grades: ' + err.message, 'error');
        }
    }

    function filterGrades() { loadGrades(); }

    function renderGrades() {
        const tbody = document.getElementById('gradeBody');
        if (!tbody) return;
        
        if (allGrades.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:60px;color:#94a3b8;">No students found matching the filters.</td></tr>';
            return;
        }
        
        tbody.innerHTML = allGrades.map(s => {
            const finalScore = calculateFinalGrade(s);
            const gpa = getGPA(finalScore);
            const gradeClass = getGradeClass(finalScore);
            return `
                <tr>
                    <td style="font-weight:700;">${s.name}</td>
                    <td style="color:#64748b;">${s.id}</td>
                    <td style="color:#475569; font-weight:500;">${s.course}</td>
                    <td><span class="badge badge-draft" style="background:#e2e8f0; color:#475569;">${s.group}</span></td>
                    <td><input class="form-input grade-input" style="width:70px; padding:6px;" type="number" value="${s.assignments}" min="0" max="100" step="0.5" onchange="updateGrade('${s.id}','assignments',this.value)"></td>
                    <td><input class="form-input grade-input" style="width:70px; padding:6px;" type="number" value="${s.midterm}" min="0" max="100" step="0.5" onchange="updateGrade('${s.id}','midterm',this.value)"></td>
                    <td><input class="form-input grade-input" style="width:70px; padding:6px;" type="number" value="${s.final}" min="0" max="100" step="0.5" onchange="updateGrade('${s.id}','final',this.value)"></td>
                    <td><span class="badge ${gradeClass}">${finalScore.toFixed(1)}</span></td>
                    <td><span style="font-weight:700; color:#0f172a;">${gpa.toFixed(2)}</span></td>
                </tr>
            `;
        }).join('');
    }

    // ── 3. UPDATE LOGIC ──
    async function updateGrade(studentId, field, val) {
        const idx = allGrades.findIndex(s => s.id === studentId);
        if (idx !== -1) {
            allGrades[idx][field] = Math.min(100, Math.max(0, parseFloat(val) || 0));
            renderGrades(); // Re-render instantly so average updates on screen
            
            try {
                // Send all 3 values to Laravel so it calculates the new average
                await AdminGradeAPI.updateGrade(studentId, {
                    course: allGrades[idx].course,
                    assignments: allGrades[idx].assignments,
                    midterm: allGrades[idx].midterm,
                    final: allGrades[idx].final
                });
                showToast(`Saved ${allGrades[idx].name}'s score`, 'success');
            } catch (err) {
                showToast('Save failed: ' + err.message, 'error');
            }
        }
    }

    // ── 4. MATH HELPERS ──
    function calculateFinalGrade(s) { return (s.assignments * weightA) + (s.midterm * weightM) + (s.final * weightF); }
    function getGPA(score) {
        if (score >= 90) return 4.0; if (score >= 85) return 3.7; if (score >= 80) return 3.3;
        if (score >= 75) return 3.0; if (score >= 70) return 2.7; if (score >= 65) return 2.3;
        if (score >= 60) return 2.0; if (score >= 55) return 1.7; if (score >= 50) return 1.3;
        return 1.0;
    }
    function getGradeClass(score) {
        if (score >= 90) return 'badge-active';  // Green
        if (score >= 80) return 'badge-draft';   // Yellow
        if (score >= 70) return 'badge-draft';
        if (score >= 60) return 'badge-banned';  // Red
        return 'badge-banned';
    }

    function updateWeightDisplay() {
        const aEl = document.getElementById('assignPercent');
        const mEl = document.getElementById('midPercent');
        const fEl = document.getElementById('finalPercent');
        if(aEl) aEl.innerHTML = `(${Math.round(weightA*100)}%)`;
        if(mEl) mEl.innerHTML = `(${Math.round(weightM*100)}%)`;
        if(fEl) fEl.innerHTML = `(${Math.round(weightF*100)}%)`;
    }

    // ── 5. MODALS & BULK ACTIONS ──
    function openWeightModal() { document.getElementById('weightModal').classList.add('open'); }
    function closeWeightModal() { document.getElementById('weightModal').classList.remove('open'); }
    
    function applyWeights() {
        const a = parseInt(document.getElementById('assignWeight').value);
        const m = parseInt(document.getElementById('midWeight').value);
        const f = parseInt(document.getElementById('finalWeight').value);
        if (a + m + f !== 100) { showToast('Weights must sum to 100%', 'error'); return; }
        weightA = a / 100; weightM = m / 100; weightF = f / 100;
        updateWeightDisplay(); renderGrades(); closeWeightModal();
        showToast('Grade weights updated', 'success');
    }

    function openBulkModal() { document.getElementById('bulkModal').classList.add('open'); }
    function closeBulkModal() { document.getElementById('bulkModal').classList.remove('open'); }
    
    function applyBulkEdit() {
        const component = document.getElementById('bulkComponent').value;
        const op = document.getElementById('bulkOp').value;
        const value = parseFloat(document.getElementById('bulkValue').value);
        if (isNaN(value)) { showToast('Enter a valid value', 'error'); return; }
        
        // Apply directly to allGrades (since backend handles filtering)
        allGrades.forEach(s => {
            let original = s[component];
            let newVal = original;
            if (op === 'add') newVal = original + value;
            else if (op === 'multiply') newVal = original * value;
            else if (op === 'set') newVal = value;
            
            s[component] = Math.min(100, Math.max(0, newVal));
            // Trigger API update for each student
            updateGrade(s.id, component, s[component]); 
        });
        
        closeBulkModal();
        showToast(`Bulk update applied to ${allGrades.length} student(s)`, 'success');
    }

    function exportGradesCSV() {
        let csv = "Name,ID,Course,Group,Assignments,Midterm,Final,Final Score,GPA\n";
        allGrades.forEach(s => {
            const finalScore = calculateFinalGrade(s);
            const gpa = getGPA(finalScore);
            csv += `"${s.name}",${s.id},"${s.course}",${s.group},${s.assignments},${s.midterm},${s.final},${finalScore.toFixed(1)},${gpa.toFixed(2)}\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `grades_${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        URL.revokeObjectURL(a.href);
        showToast('Grades exported to CSV', 'success');
    }

    // ── 6. UI & UTILITIES ──
    function showToast(msg, type = 'success') {
        let t = document.getElementById('__toast');
        if (!t) { 
            t = document.createElement('div'); t.id = '__toast'; 
            Object.assign(t.style, {
                position:'fixed', bottom:'28px', right:'28px', padding:'12px 20px', borderRadius:'10px',
                fontSize:'14px', fontWeight:'600', color:'white', transition:'all 0.3s', zIndex:'9999',
                opacity:'0', transform:'translateY(20px)', boxShadow:'0 4px 12px rgba(0,0,0,0.15)'
            });
            document.body.appendChild(t); 
        }
        t.style.background = type === 'error' ? '#dc2626' : '#16a34a';
        t.textContent = msg;
        requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translateY(0)'; });
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(20px)'; }, 3000);
    }

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
</script>
</body>
</html>