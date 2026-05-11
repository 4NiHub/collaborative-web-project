<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Management – SUSAdmin</title>
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

        /* Admin Contact Management - Messages from Students & Teachers */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
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

        /* Messages Table */
        .messages-container {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        body.dark-mode .messages-container { background: #1e293b; }
        
        .message-row {
            display: flex;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
            cursor: pointer;
        }
        .message-row:hover { background: #f8fafc; }
        body.dark-mode .message-row:hover { background: #0f172a; }
        .message-row.unread { background: #eff6ff; }
        body.dark-mode .message-row.unread { background: #1e3a5f; }
        
        .message-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
            margin-right: 16px;
        }
        .message-content { flex: 1; min-width: 0; }
        .message-sender { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        body.dark-mode .message-sender { color: #f1f5f9; }
        .message-subject { font-size: 14px; font-weight: 600; color: #2563eb; margin-bottom: 4px; }
        .message-preview { font-size: 14px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        body.dark-mode .message-preview { color: #94a3b8; }
        .message-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 13px;
            color: #94a3b8;
            margin-top: 6px;
        }
        .message-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .badge-student { background: #dbeafe; color: #1e40af; }
        .badge-teacher { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-replied { background: #d1fae5; color: #065f46; }
        
        .message-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            color: #64748b;
        }
        body.dark-mode .icon-btn { background: #0f172a; color: #94a3b8; }
        .icon-btn:hover { background: #eef2ff; color: #2563eb; border-color: #2563eb; }
        .icon-btn.del:hover { background: #fee2e2; color: #dc2626; border-color: #dc2626; }
        .message-meta span { display: inline-flex; align-items: center; gap: 4px; }
        .dept-contact { display: flex; align-items: center; gap: 6px; }
        /* Message Detail Modal */
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
            width: 550px;
            max-width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            padding: 28px;
        }
        body.dark-mode .modal-box { background: #1e293b; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 20px; font-weight: 700; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b; }
        .detail-row { display: flex; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
        .detail-label { width: 100px; font-weight: 600; color: #64748b; }
        .detail-value { flex: 1; color: #1e293b; word-break: break-word; }
        body.dark-mode .detail-value { color: #e2e8f0; }
        .message-full { background: #f8fafc; padding: 16px; border-radius: 12px; margin: 16px 0; line-height: 1.6; }
        body.dark-mode .message-full { background: #0f172a; }
        .reply-box { margin-top: 20px; }
        .reply-textarea { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-family: inherit; resize: vertical; min-height: 100px; }
        body.dark-mode .reply-textarea { background: #0f172a; color: #e2e8f0; border-color: #334155; }
        body.dark-mode .badge { background: #334155 !important; color: #e2e8f0 !important; }
        body.dark-mode .btn-secondary { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .modal-box { background: #1e293b !important; color: #e2e8f0 !important; }
        body.dark-mode .message-meta { color: #94a3b8 !important; }
        body.dark-mode .message-badge { color: #1e293b !important; }
        body.dark-mode .badge-student { background: #1e3a5f !important; color: #93c5fd !important; }
        body.dark-mode .badge-teacher { background: #14532d !important; color: #86efac !important; }
        body.dark-mode .badge-pending { background: #713f12 !important; color: #fcd34d !important; }
        body.dark-mode .badge-replied { background: #14532d !important; color: #86efac !important; }
        body.dark-mode .dept-card { background: #0f172a !important; border-color: #334155 !important; }
         /* Department management */
        .dept-section {
            margin-top: 28px;
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 24px;
        }
        body.dark-mode .dept-section { background: #1e293b; }
        .dept-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .dept-header h3 { font-size: 18px; font-weight: 700; }
        .dept-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px; }
        .dept-card {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: all 0.2s;
        }
        .dept-card:hover { border-color: #2563eb; background: #f8fafc; }
        body.dark-mode .dept-card:hover { background: #0f172a; }
        .dept-name { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .dept-contact { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 6px; margin-top: 6px; }
        
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
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #16a34a; }
        .toast-error { background: #dc2626; }
        .toast-warning { background: #d97706; }
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 32px; font-weight: 800; }
        .page-header p { font-size: 15px; color: #64748b; margin-top: 6px; }
        
        .empty-state {
            text-align: center;
            padding: 60px;
            color: #94a3b8;
        }

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
                <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <div class="page-header">
            <h1>Contact Messages</h1>
            <p>View and respond to inquiries from students and teachers</p>
        </div>

        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card"><div class="stat-icon"> <img src="{{ asset('images/admin_icons/contact.png') }}" style="width:28px;height:28px;"></div><div><div class="stat-number" id="totalMessages">0</div><div class="stat-label">Total Messages</div></div></div>
            <div class="stat-card"><div class="stat-icon"> <img src="{{ asset('images/admin_icons/news.png') }}" style="width:28px;height:28px;"></div><div><div class="stat-number" id="unreadCount">0</div><div class="stat-label">Unread</div></div></div>
            <div class="stat-card"><div class="stat-icon"> <img src="{{ asset('images/admin_icons/users.png') }}" style="width:28px;height:28px;"></div><div><div class="stat-number" id="studentCount">0</div><div class="stat-label">From Students</div></div></div>
            <div class="stat-card"><div class="stat-icon"> <img src="{{ asset('images/admin_icons/teachers.png') }}" style="width:28px;height:28px;"></div><div><div class="stat-number" id="teacherCount">0</div><div class="stat-label">From Teachers</div></div></div>
        </div>

        <div class="list-controls">
            <div class="search-box">
                <img src="{{ asset('images/admin_icons/search.png') }}" style="width:16px;height:16px;">
                <input type="text" id="searchInput" placeholder="Search by name, email, subject..." oninput="filterMessages()">
            </div>
            <select class="filter-select" id="roleFilter" onchange="filterMessages()">
                <option value="">All Senders</option>
                <option value="Student">Students</option>
                <option value="Teacher">Teachers</option>
            </select>
            <select class="filter-select" id="statusFilter" onchange="filterMessages()">
                <option value="">All Status</option>
                <option value="unread">Unread</option>
                <option value="replied">Replied</option>
            </select>
        </div>

        <!-- Messages List -->
        <div class="messages-container" id="messagesContainer"></div>

        <!-- Department management section -->
        <div class="dept-section">
            <div class="dept-header">
                <h3> Department Contacts</h3>
                <button class="btn-primary" onclick="openDeptModal()">+ Add Department</button>
            </div>
            <div class="dept-grid" id="deptGrid"></div>
        </div>
    </main>
</div>

<!-- Message Detail Modal -->
<div class="modal-overlay" id="messageModal">
    <div class="modal-box">
        <div class="modal-header">
            <span>Message Details</span>
            <button class="modal-close" onclick="closeMessageModal()">✕</button>
        </div>
        <div id="messageDetail"></div>
        <div class="reply-box">
            <label class="form-label">Reply Message</label>
            <textarea id="replyText" class="reply-textarea" placeholder="Type your reply here..."></textarea>
            <div class="modal-actions" style="margin-top: 16px;">
                <button class="btn-secondary" onclick="closeMessageModal()">Cancel</button>
                <button class="btn-primary" onclick="sendReply()">Send Reply</button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit department Modal -->
<div class="modal-overlay" id="deptModal">
    <div class="modal-box" style="max-width:450px;">
        <div class="modal-header">
            <span id="deptModalTitle">Add Department</span>
            <button class="modal-close" onclick="closeDeptModal()">✕</button>
        </div>
        <div class="form-group"><label class="form-label">Department Name</label><input type="text" class="form-input" id="deptName" placeholder="e.g., Computer Science"></div>
        <div class="form-group"><label class="form-label">Phone</label><input type="text" class="form-input" id="deptPhone" placeholder="+44 20 1234 5678"></div>
        <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" id="deptEmail" placeholder="dept@university.edu"></div>
        <div class="form-group"><label class="form-label">Location</label><input type="text" class="form-input" id="deptLocation" placeholder="Building A, Floor 2"></div>
        <div class="modal-actions"><button class="btn-secondary" onclick="closeDeptModal()">Cancel</button><button class="btn-primary" onclick="saveDepartment()">Save</button></div>
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
    
// Message Data Store
let messages = [];

let departments = [];

let currentMessageId = null;
let editDeptId = null;

function updateStats() {
    document.getElementById('totalMessages').innerText = messages.length;
    document.getElementById('unreadCount').innerText = messages.filter(m => m.status === 'unread').length;
    document.getElementById('studentCount').innerText = messages.filter(m => m.role === 'Student').length;
    document.getElementById('teacherCount').innerText = messages.filter(m => m.role === 'Teacher').length;
}

function getFilteredMessages() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    return messages.filter(m => {
        const matchSearch = m.name.toLowerCase().includes(search) || m.email.toLowerCase().includes(search) || m.subject.toLowerCase().includes(search);
        const matchRole = !role || m.role === role;
        const matchStatus = !status || m.status === status;
        return matchSearch && matchRole && matchStatus;
    });
}

async function loadMessages() {
    try {
        const res = await AdminContactAPI.getMessages();
        messages = res.data || [];
        renderMessages();
    } catch (err) {
        showToast('Failed to load messages: ' + err.message, 'error');
    }
}

function renderMessages() {
    const filtered = getFilteredMessages();
    const container = document.getElementById('messagesList');
    if (!container) return;
    if (filtered.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:40px;color:#94a3b8;">No messages found.</div>';
        updateStats();
        return;
    }
    container.innerHTML = filtered.map(m => `
        <div class="message-row ${m.status === 'unread' ? 'unread' : ''}" onclick="openMessageModal(${m.id})">
            <div class="message-avatar" style="background:${m.avatarColor};">${m.initials}</div>
            <div class="message-content">
                <div class="message-sender">${m.name} <span class="message-badge badge-${m.role === 'Student' ? 'student' : 'teacher'}">${m.role}</span></div>
                <div class="message-subject">${escapeHtml(m.subject)}</div>
                <div class="message-preview">${escapeHtml(m.message.substring(0, 80))}${m.message.length > 80 ? '...' : ''}</div>
                <div class="message-meta">
                    <span><img src="{{ asset('images/admin_icons/timetable.png') }}" style="width:14px;height:14px;vertical-align:middle;"> ${m.date}</span>
                    <span><img src="{{ asset('images/admin_icons/content.png') }}" style="width:14px;height:14px;vertical-align:middle;">${m.department}</span>
                    <span class="message-badge badge-${m.status === 'unread' ? 'pending' : 'replied'}">${m.status === 'unread' ? 'Pending' : 'Replied'}</span>
                </div>
            </div>
            <div class="message-actions" onclick="event.stopPropagation()">
                <button class="icon-btn" onclick="deleteMessage(${m.id})" title="Delete"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:16px;height:16px;"></button>
            </div>
        </div>
    `).join('');
    updateStats();
}

function openMessageModal(id) {
    const message = messages.find(m => m.id === id);
    if (!message) return;
    currentMessageId = id;
    
    if (message.status === 'unread') {
        message.status = 'replied';
        renderMessages();
    }
    
    document.getElementById('messageDetail').innerHTML = `
        <div class="detail-row"><span class="detail-label">From:</span><span class="detail-value">${escapeHtml(message.name)} (${message.role})</span></div>
        <div class="detail-row"><span class="detail-label">Email:</span><span class="detail-value">${escapeHtml(message.email)}</span></div>
        <div class="detail-row"><span class="detail-label">Department:</span><span class="detail-value">${escapeHtml(message.department)}</span></div>
        <div class="detail-row"><span class="detail-label">Subject:</span><span class="detail-value">${escapeHtml(message.subject)}</span></div>
        <div class="detail-row"><span class="detail-label">Date:</span><span class="detail-value">${message.date}</span></div>
        <div class="message-full"><strong>Message:</strong><br>${escapeHtml(message.message)}</div>
    `;
    document.getElementById('replyText').value = '';
    document.getElementById('messageModal').classList.add('open');
}

function closeMessageModal() { document.getElementById('messageModal').classList.remove('open'); currentMessageId = null; }

async function sendReplyToMessage(messageId, replyText) {
    try {
        await AdminContactAPI.sendReply({ messageId, reply: replyText });
        showToast('Reply sent successfully', 'success');
        loadMessages();
    } catch (err) {
        showToast('Failed to send reply: ' + err.message, 'error');
    }
}

async function sendReply() {
    const text = document.getElementById('replyText').value.trim();
    if (!text) { showToast('Please write a reply', 'error'); return; }
    closeMessageModal();
    await sendReplyToMessage(currentMessageId, text);
}

async function deleteMessageAPI(messageId) {
    try {
        await AdminContactAPI.deleteMessage(messageId);
        showToast('Message deleted', 'success');
        loadMessages();
    } catch (err) {
        showToast('Failed to delete: ' + err.message, 'error');
    }
}

async function deleteMessage(id) {
    await deleteMessageAPI(id);
}

async function deleteDepartment(id) {
    await deleteDepartmentAPI(id);
}

function filterMessages() { renderMessages(); }

// Department Management
async function loadDepartments() {
    try {
        const res = await AdminContactAPI.getDepartments();
        departments = res.data || [];
        renderDepartments();
    } catch (err) {
        showToast('Failed to load departments: ' + err.message, 'error');
    }
}

function renderDepartments() {
    const container = document.getElementById('departmentsList');
    if (!container) return;
    container.innerHTML = departments.map(d => `
        <div class="dept-card">
            <div class="dept-name">${escapeHtml(d.name)}</div>
            <div class="dept-contact">${d.phone || '—'}</div>
            <div class="dept-contact"><img src="{{ asset('images/admin_icons/contact.png') }}" style="width:20px;height:20px;vertical-align:middle;"> ${d.email || '—'}</div>
            <div class="dept-contact"><img src="{{ asset('images/admin_icons/pin_grey.png') }}" style="width:20px;height:20px;vertical-align:middle;"> ${d.location || '—'}</div>
            <div style="display:flex; gap:8px; margin-top:12px;">
                <button class="btn-secondary" style="padding:6px 12px; font-size:12px;" onclick="editDepartment(${d.id})">Edit</button>
                <button class="btn-danger" style="padding:6px 12px; font-size:12px;" onclick="deleteDepartment(${d.id})">Delete</button>
            </div>
        </div>
    `).join('');
}

function openDeptModal(editId = null) {
    editDeptId = editId;
    if (editId) {
        const dept = departments.find(d => d.id === editId);
        if (dept) {
            document.getElementById('deptModalTitle').innerText = 'Edit Department';
            document.getElementById('deptName').value = dept.name;
            document.getElementById('deptPhone').value = dept.phone || '';
            document.getElementById('deptEmail').value = dept.email || '';
            document.getElementById('deptLocation').value = dept.location || '';
        }
    } else {
        document.getElementById('deptModalTitle').innerText = 'Add Department';
        document.getElementById('deptName').value = '';
        document.getElementById('deptPhone').value = '';
        document.getElementById('deptEmail').value = '';
        document.getElementById('deptLocation').value = '';
    }
    document.getElementById('deptModal').classList.add('open');
}

function closeDeptModal() { document.getElementById('deptModal').classList.remove('open'); editDeptId = null; }

async function saveDepartmentAPI(deptData, isEdit, deptId) {
    try {
        if (isEdit) {
            await AdminContactAPI.updateDepartment(deptId, deptData);
            showToast('Department updated', 'success');
        } else {
            await AdminContactAPI.addDepartment(deptData);
            showToast('Department added', 'success');
        }
        loadDepartments();
    } catch (err) {
        showToast('Failed to save department: ' + err.message, 'error');
    }
}

function saveDepartment() {
    const deptData = {
        name:     document.getElementById('deptName').value.trim(),
        phone:    document.getElementById('deptPhone').value.trim(),
        email:    document.getElementById('deptEmail').value.trim(),
        location: document.getElementById('deptLocation').value.trim()
    };
    if (!deptData.name) { showToast('Department name is required', 'error'); return; }
    closeDeptModal();
    saveDepartmentAPI(deptData, !!editDeptId, editDeptId);
}

function editDepartment(id) { openDeptModal(id); }

async function deleteDepartmentAPI(deptId) {
    try {
        await AdminContactAPI.deleteDepartment(deptId);
        showToast('Department deleted', 'success');
        loadDepartments();
    } catch (err) {
        showToast('Failed to delete department: ' + err.message, 'error');
    }
}

function showToast(msg, type = 'success') {
    let t = document.getElementById('__toast');
    if (!t) { t = document.createElement('div'); t.id = '__toast'; t.className = 'toast-notification'; document.body.appendChild(t); }
    t.className = `toast-notification toast-${type}`;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

function escapeHtml(str) { return String(str).replace(/[&<>]/g, function(m) { if (m === '&') return '&amp;'; if (m === '<') return '&lt;'; if (m === '>') return '&gt;'; return m; }); }


loadMessages();
loadDepartments();
</script>
<script>
    /*  Theme toggle  */
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