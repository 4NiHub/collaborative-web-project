<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – SUSAdmin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin_reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_sidebar.css') }}">
    {{-- <style>
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden;
        }

        .app-container { display: flex; height: 100%; width: 100%; }

   
        .sidebar {
            width: 72px;
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0 24px;
            position: relative;
            transition: width 0.2s ease;
            z-index: 20;
            overflow: visible;
        }
        .sidebar.expanded { width: 220px; }

        .sidebar-toggle-btn {
            position: absolute;
            top: 20px; right: -12px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #475569; z-index: 25;
            box-shadow: var(--card-shadow);
        }

        .sidebar-icons {
            margin-top: 48px; width: 100%;
            display: flex; flex-direction: column; gap: 6px;
        }

        .sidebar-icon {
            display: flex; align-items: center;
            justify-content: center; gap: 12px;
            padding: 11px 14px; margin: 0 10px;
            border-radius: 12px; cursor: pointer;
            color: #475569; transition: all 0.18s;
            position: relative; white-space: nowrap;
        }
        .sidebar-icon:hover { background: #f1f5f9; color: var(--primary); }
        .sidebar-icon.active { background: #eef2ff; color: var(--primary); }

        .sidebar-icon svg { min-width: 20px; width: 20px; height: 20px; }

        .sidebar-label { font-size: 14px; font-weight: 500; display: none; }
        .sidebar.expanded .sidebar-label { display: inline-block; }
        .sidebar.expanded .sidebar-icon { justify-content: flex-start; padding: 11px 18px; }

        .logout-icon { margin-top: auto; margin-bottom: 20px; }
        .logout-icon:hover { background: #fff1f2 !important; color: #dc2626 !important; }


        .sidebar-icon[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute; left: 74px;
            background: #1e293b; color: white;
            font-size: 13px; font-weight: 500;
            padding: 5px 10px; border-radius: 7px;
            white-space: nowrap; z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .sidebar.expanded .sidebar-icon[data-tooltip]:hover::after { display: none; }

        .main-content { flex: 1; overflow-y: auto; padding: 24px 32px; }
        .main-content::-webkit-scrollbar { width: 6px; }
        .main-content::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 10px; }
        .main-content::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }

        /* merged into sidebar.css */
        .admin-badge:hover { background: #eef2ff; border-color: var(--border); }

        .stats-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 20px; margin-bottom: 28px;
        }
        .stat-card {
            background: white; border-radius: 20px; border: 1px solid var(--border);
            padding: 20px 24px; display: flex; align-items: center; gap: 18px;
            box-shadow: var(--card-shadow); cursor: pointer; transition: all 0.2s;
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(37,99,235,0.12); transform: translateY(-2px); }
        .stat-icon { width: 50px; height: 50px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; background: #f1f5f9; }
        .stat-info h3 { font-size: 14px; color: #64748b; font-weight: 500; letter-spacing: 0.3px; }
        .stat-value { font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 4px; }
        .stat-change { font-size: 13px; font-weight: 600; margin-top: 4px; }
        .stat-change.up { color: #16a34a; }

        
        .dashboard-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 24px; align-items: start; }

        .card { background: white; border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--card-shadow); }

        .chart-card { padding: 20px 20px 24px; }
        .chart-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; color: #1e293b; }

        .chart-tabs { display: flex; gap: 6px; margin-bottom: 16px; }
        .chart-tab {
            padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid var(--border); background: #f8fafc;
            color: #64748b; transition: all 0.18s;
        }
        .chart-tab.active { background: var(--primary); color: white; border-color: var(--primary); }
        .chart-tab:hover:not(.active) { background: #eef2ff; color: var(--primary); border-color: var(--primary); }

        .chart-container { position: relative; width: 100%; height: 300px; }

       
        .recent-card { padding: 20px; }
        .recent-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .recent-title { font-size: 16px; font-weight: 700; color: #1e293b; }

        .activity-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 10px; border-radius: 12px; cursor: pointer;
            transition: background 0.15s; border-bottom: 1px solid var(--border);
            position: relative;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #f8fafc; }
        .activity-item.expanded-item { background: #f8fafc; }

        .activity-icon {
            width: 50px; height: 50px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 17px; background: #f1f5f9;
        }
        .activity-icon img { width: 35px; height: 35px; }
        .activity-body { flex: 1; min-width: 0; }
        .activity-text strong { font-size: 14px; font-weight: 700; color: #0f172a; }
        .activity-text span { font-size: 14px; color: #475569; }
        .activity-time { font-size: 13px; color: #94a3b8; margin-top: 3px; font-weight: 500; }

        .activity-chevron {
            color: #cbd5e1; align-self: center; flex-shrink: 0;
            transition: transform 0.2s; font-size: 13px;
        }
        .activity-item.expanded-item .activity-chevron { transform: rotate(90deg); color: var(--primary); }

        .activity-detail {
            display: none; overflow: hidden;
            background: #f1f5f9; border-radius: 12px;
            margin: 0 4px 4px 48px;
            border: 1px solid var(--border);
            animation: slideDown 0.18s ease;
        }
        .activity-detail.open { display: block; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        .detail-inner { padding: 12px 14px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .detail-row:last-of-type { border-bottom: none; }
        .detail-label { color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; }
        .detail-val { color: #1e293b; font-weight: 500; }
        .detail-actions { display: flex; gap: 8px; padding: 10px 14px; border-top: 1px solid var(--border); }
        .det-btn {
            padding: 5px 14px; border-radius: 7px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid var(--border); background: white; color: #374151;
            transition: all 0.15s;
        }
        .det-btn:hover { background: #eef2ff; color: var(--primary); border-color: var(--primary); }
        .det-btn.pri { background: var(--primary); color: white; border-color: var(--primary); }
        .det-btn.pri:hover { background: var(--primary-hover); }
        .det-btn.dismiss:hover { background: #fff1f2; color: #dc2626; border-color: #dc2626; }

        @media (max-width: 1000px) {
            .main-content { padding: 20px; }
            .dashboard-grid { grid-template-columns: 1fr; }
            .stats-grid { gap: 12px; }
        }
        body.dark-mode .stat-card, body.dark-mode .card { background: #1e293b !important; border-color: #334155 !important; }
        body.dark-mode .stat-value { color: #f1f5f9; } 
    </style> --}}

    <style>
        /* We removed all the sidebar, body, and top-bar CSS because admin_sidebar.css handles it! */
        
        /* Dashboard specific styles */
        .stats-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 20px; margin-bottom: 28px;
        }
        .stat-card {
            background: white; border-radius: 20px; border: 1px solid var(--border);
            padding: 20px 24px; display: flex; align-items: center; gap: 18px;
            box-shadow: var(--card-shadow); cursor: pointer; transition: all 0.2s;
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(37,99,235,0.12); transform: translateY(-2px); }
        .stat-icon { width: 50px; height: 50px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; background: #f1f5f9; }
        .stat-info h3 { font-size: 14px; color: #64748b; font-weight: 500; letter-spacing: 0.3px; }
        .stat-value { font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 4px; }
        .stat-change { font-size: 13px; font-weight: 600; margin-top: 4px; }
        .stat-change.up { color: #16a34a; }

        .dashboard-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 24px; align-items: start; }
        .card { background: white; border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--card-shadow); }

        .chart-card { padding: 20px 20px 24px; }
        .chart-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; color: #1e293b; }
        .chart-tabs { display: flex; gap: 6px; margin-bottom: 16px; }
        .chart-tab {
            padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid var(--border); background: #f8fafc;
            color: #64748b; transition: all 0.18s;
        }
        .chart-tab.active { background: var(--primary); color: white; border-color: var(--primary); }
        .chart-tab:hover:not(.active) { background: #eef2ff; color: var(--primary); border-color: var(--primary); }
        .chart-container { position: relative; width: 100%; height: 300px; }

        .recent-card { padding: 20px; }
        .recent-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .recent-title { font-size: 16px; font-weight: 700; color: #1e293b; }

        .activity-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 10px; border-radius: 12px; cursor: pointer;
            transition: background 0.15s; border-bottom: 1px solid var(--border);
            position: relative;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #f8fafc; }
        .activity-item.expanded-item { background: #f8fafc; }

        .activity-icon {
            width: 50px; height: 50px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 17px; background: #f1f5f9;
        }
        .activity-icon img { width: 35px; height: 35px; }
        .activity-body { flex: 1; min-width: 0; }
        .activity-text strong { font-size: 14px; font-weight: 700; color: #0f172a; }
        .activity-text span { font-size: 14px; color: #475569; }
        .activity-time { font-size: 13px; color: #94a3b8; margin-top: 3px; font-weight: 500; }

        .activity-chevron {
            color: #cbd5e1; align-self: center; flex-shrink: 0;
            transition: transform 0.2s; font-size: 13px;
        }
        .activity-item.expanded-item .activity-chevron { transform: rotate(90deg); color: var(--primary); }

        .activity-detail {
            display: none; overflow: hidden;
            background: #f1f5f9; border-radius: 12px;
            margin: 7px 4px 7px 48px;
            border: 1px solid var(--border);
            animation: slideDown 0.18s ease;
        }
        .activity-detail.open { display: block; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        .detail-inner { padding: 12px 14px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .detail-row:last-of-type { border-bottom: none; }
        .detail-label { color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; }
        .detail-val { color: #1e293b; font-weight: 500; }
        .detail-actions { display: flex; gap: 8px; padding: 10px 14px; border-top: 1px solid var(--border); }
        .det-btn {
            padding: 5px 14px; border-radius: 7px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid var(--border); background: white; color: #374151;
            transition: all 0.15s;
        }
        .det-btn:hover { background: #eef2ff; color: var(--primary); border-color: var(--primary); }
        .det-btn.pri { background: var(--primary); color: white; border-color: var(--primary); }
        .det-btn.pri:hover { background: var(--primary-hover); }
        .det-btn.dismiss:hover { background: #fff1f2; color: #dc2626; border-color: #dc2626; }

        @media (max-width: 1000px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .stats-grid { gap: 12px; }
        }
        body.dark-mode .stat-card, body.dark-mode .card { background: #1e293b !important; border-color: #334155 !important; }
        body.dark-mode .stat-value { color: #f1f5f9; }

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

        body.dark-mode .det-btn{color: #374151 !important;}
        body.dark-mode .det-btn.dismiss:hover { background: #fff1f2; color: #dc2626 !important; border-color: #dc2626; }
        
        /* Dark Mode for Popup */
        body.dark-mode .logout-popup-modal { background: #1e293b; border: 1px solid #334155; }
        body.dark-mode .logout-popup-title { color: #f1f5f9; }
        body.dark-mode .logout-popup-text { color: #94a3b8; }
        body.dark-mode .logout-icon-large { background: #450a0a; color: #f87171; }
        body.dark-mode .logout-btn-cancel { background: #334155; color: #e2e8f0; }
        body.dark-mode .logout-btn-cancel:hover { background: #475569; }

        /* ── RECENT ACTIONS DARK MODE FIX ── */
        body.dark-mode .activity-item {
            background-color: #1e293b;
            border-color: #334155;
        }
        body.dark-mode .activity-item:hover {
            background-color: #334155;
        }
        body.dark-mode .activity-text strong { color: #f8fafc; }
        body.dark-mode .activity-text span { color: #94a3b8; }
        body.dark-mode .activity-text span:last-child { color: #3b82f6 !important; } /* Blue detail text */
        body.dark-mode .activity-time { color: #64748b; }
        
        body.dark-mode .activity-detail {
            background-color: #0f172a;
            border-color: #334155;
            border-top: none;
        }
        body.dark-mode .detail-label { color: #64748b; }
        body.dark-mode .detail-val { color: #e2e8f0; }
        
        /* Invert the black icons so they turn white in dark mode! */
        /* body.dark-mode .activity-icon img {
            filter: brightness(0) invert(1);
        } */
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

        <!-- TOP BAR -->
        <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='{{ route('admin.profile') }}'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <!-- Page header -->
        <div style="margin-bottom:24px;">
            <h1 style="font-size:26px;font-weight:700;">Dashboard</h1>
            <p style="font-size:14px;color:#64748b;margin-top:4px;">Welcome back, Admin. Here's what's happening today</p>
        </div>

        <!-- Stats  -->
        <div class="stats-grid">
            <div class="stat-card" onclick="location.href='{{ route('admin.users') }}'">
                <div class="activity-icon">
                  <img src="{{ asset('images/admin_icons/users_black.png') }}" alt="Users">
                </div>
                <div class="stat-info">
                    <h3>Total students</h3>
                    <div class="stat-value" id="statStudents">2,847</div>
                    <div class="stat-change up">↑ +12%</div>
                </div>
            </div>
            <div class="stat-card" onclick="location.href='{{ route('admin.users') }}'">
                <div class="activity-icon">
                  <img src="{{ asset('images/admin_icons/person.png') }}" alt="Users">
                </div>
                <div class="stat-info">
                    <h3>Total teachers</h3>
                    <div class="stat-value" id="statTeachers">184</div>
                    <div class="stat-change up">↑ +3%</div>
                </div>
            </div>
            <div class="stat-card" onclick="location.href='{{ route('admin.content') }}'">
                <div class="activity-icon">
                  <img src="{{ asset('images/admin_icons/records_black.png') }}" alt="Users">
                </div>
                <div class="stat-info">
                    <h3>Total courses</h3>
                    <div class="stat-value" id="statCourses">96</div>
                    <div class="stat-change up">↑ +8%</div>
                </div>
            </div>
        </div>

        
        <div class="dashboard-grid">

            
            <div class="card chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-line" style="color:var(--primary);"></i>
                    Weekly User Activity
                </div>
                <div class="chart-tabs">
                    <button class="chart-tab active" onclick="switchDataset(0, this)">Students</button>
                    <button class="chart-tab" onclick="switchDataset(1, this)">Teachers</button>
                    <button class="chart-tab" onclick="switchDataset(2, this)">Logins</button>
                </div>
                <div class="chart-container">
                    <canvas id="lineChartCanvas"></canvas>
                </div>
                <div style="font-size:11px;color:#94a3b8;text-align:center;margin-top:10px;">
                    Hover data points for details · Click a point for drill-down
                </div>
            </div>

            <!-- Recent Activity (original) -->
            <div class="card recent-card">
                <div id="activityFeed"></div>
            </div>
        </div>
    </main>
</div>

<!-- Logout Confirmation Popup -->
<div class="logout-popup-overlay" id="logoutPopup">
    <div class="logout-popup-modal">
        <div class="logout-icon-large">
            <i class="fas fa-sign-out-alt"></i>
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
    // 1. Setup global variables
    let ACTIVITIES = []; 
    let openId = null;

    const DATASETS = [
        { label: 'Active Students', data: [0,0,0,0,0,0,0], borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.08)' },
        { label: 'Active Teachers', data: [0,0,0,0,0,0,0], borderColor: '#7c3aed', backgroundColor: 'rgba(124,58,237,0.08)' },
        { label: 'Total Logins',    data: [0,0,0,0,0,0,0], borderColor: '#0891b2', backgroundColor: 'rgba(8,145,178,0.08)' },
    ];

    const ctx = document.getElementById('lineChartCanvas').getContext('2d');
    let activeDatasetIndex = 0;

    function buildDataset(idx) {
        const ds = DATASETS[idx];
        return {
            label: ds.label, data: ds.data, borderColor: ds.borderColor, backgroundColor: ds.backgroundColor,
            borderWidth: 3, pointRadius: 5, pointHoverRadius: 9, pointBackgroundColor: ds.borderColor,
            pointBorderColor: '#ffffff', pointBorderWidth: 2.5, tension: 0.3, fill: true,
        };
    }

    const chart = new Chart(ctx, {
        type: 'line',
        data: { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], datasets: [buildDataset(0)] },
        options: {
            responsive: true, maintainAspectRatio: false, animation: { duration: 500, easing: 'easeInOutQuart' },
            plugins: {
                tooltip: { backgroundColor: '#0f172a', titleColor: '#f1f5f9', bodyColor: '#cbd5e1', padding: 10, cornerRadius: 8, callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.raw.toLocaleString()}` } },
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 } }, title: { display: true, text: 'Count', color: '#94a3b8', font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { color: '#475569', font: { size: 12 } } }
            }
        }
    });

    // 2. Main Initialization
    async function init() {
        try {
            const res = await AdminDashboardAPI.getStats();
            const s = res.data;
            
            // Populate Top Cards
            if(document.getElementById('statStudents')) document.getElementById('statStudents').textContent = s.totalStudents.toLocaleString();
            if(document.getElementById('statTeachers')) document.getElementById('statTeachers').textContent = s.totalTeachers.toLocaleString();
            if(document.getElementById('statCourses')) document.getElementById('statCourses').textContent  = s.activeCourses.toLocaleString();
            
            // Populate Charts
            if (s.weeklyStudents) { DATASETS[0].data = s.weeklyStudents; }
            if (s.weeklyTeachers) { DATASETS[1].data = s.weeklyTeachers; }
            if (s.weeklyLogins)   { DATASETS[2].data = s.weeklyLogins;   }
            chart.data.datasets = [buildDataset(activeDatasetIndex)];
            chart.update();

            // Populate Recent Actions from the Database!
            ACTIVITIES = s.recentActions || [];
            
            // Auto-expand the very first item if we have data
            // if(ACTIVITIES.length > 0) openId = ACTIVITIES[0].id;
            
            renderActivity();
        } catch (err) {
            console.error('Dashboard load failed:', err.message);
        }
    }

    function switchDataset(idx, btn) {
        activeDatasetIndex = idx;
        chart.data.datasets = [buildDataset(idx)];
        chart.update();
        document.querySelectorAll('.chart-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    // 3. Render Dynamic Activities
    function renderActivity() {
        const feed = document.getElementById('activityFeed');
        if (!feed) return;
        
        if (!ACTIVITIES.length) {
            feed.innerHTML = '<div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px;">No recent activity</div>';
            return;
        }
        
        feed.innerHTML = ACTIVITIES.map(a => `
            <div>
                <div class="activity-item ${openId === a.id ? 'expanded-item' : ''}"
                     id="row-${a.id}"
                     onclick="toggleDetail('${a.id}')"
                     role="button" tabindex="0">
                    <div class="activity-icon"><img src="{{ asset('') }}${a.icon}" style="width:24px;height:24px;"></div>
                    <div class="activity-body">
                        <div class="activity-text">
                            <strong>${a.actor}</strong>
                            <span>${a.action} —</span>
                            <span style="color:#2563eb;font-weight:600;">${a.detail}</span>
                        </div>
                        <div class="activity-time">${a.time}</div>
                    </div>
                    <i class="fas fa-chevron-right activity-chevron ${openId === a.id ? 'fa-rotate-90' : ''}"></i>
                </div>
                <div class="activity-detail ${openId === a.id ? 'open' : ''}" id="detail-${a.id}">
                    <div class="detail-inner">
                        <div class="detail-row"><span class="detail-label">Type</span><span class="detail-val">${a.type}</span></div>
                        <div class="detail-row"><span class="detail-label">Module</span><span class="detail-val">${a.module}</span></div>
                        <div class="detail-row"><span class="detail-label">Record ID</span><span class="detail-val">${a.recordId}</span></div>
                        <div class="detail-row"><span class="detail-label">Status</span><span class="detail-val">${a.status}</span></div>
                    </div>
                    <div class="detail-actions">
                        <button class="det-btn dismiss" onclick="event.stopPropagation();dismissActivity('${a.id}')">Dismiss</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function toggleDetail(id) {
        openId = (openId === id) ? null : id;
        renderActivity();
    }

    function dismissActivity(id) {
        const idx = ACTIVITIES.findIndex(a => a.id === id);
        if (idx !== -1) ACTIVITIES.splice(idx, 1);
        if (openId === id) openId = null;
        renderActivity();
        showToast('Activity dismissed');
    }

    let toastTimer;
    function showToast(msg) {
        let t = document.getElementById('__toast');
        if (!t) {
            t = document.createElement('div'); t.id = '__toast';
            Object.assign(t.style, {
                position:'fixed', bottom:'28px', right:'28px', background:'#1e293b', color:'white',
                padding:'11px 18px', borderRadius:'10px', fontSize:'13px', fontWeight:'500',
                boxShadow:'0 6px 24px rgba(0,0,0,0.2)', zIndex:'9999', transition:'all 0.3s ease',
                opacity:'0', transform:'translateY(20px)',
            });
            document.body.appendChild(t);
        }
        t.textContent = msg;
        requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translateY(0)'; });
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => { t.style.opacity='0'; t.style.transform='translateY(20px)'; }, 3200);
    }

    // 4. UI Helpers
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        updateThemeIcon(isDark);
        // Force chart grid line color update for dark mode
        chart.options.scales.y.grid.color = isDark ? '#334155' : '#f1f5f9';
        chart.update();
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
        const saved = localStorage.getItem('darkMode');
        if (saved === 'enabled') {
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