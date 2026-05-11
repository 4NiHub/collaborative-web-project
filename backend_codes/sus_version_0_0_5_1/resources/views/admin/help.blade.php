<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Help & Documentation – SUSAdmin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_sidebar.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --border: #e2e8f0;
            --bg-main: #f8fafc;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            height: 100vh;
            overflow: hidden;
        }

        .app-container { display: flex; height: 100%; width: 100%; }

        /* Sidebar - exact copy from news.html */
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
            top: 45px; right: 1px;
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

        /* Main content */
        /* .main-content {
            flex: 1;
            margin-left: 100px;
            overflow-y: auto;
            transition: margin-left 0.2s ease;
            padding: 24px 32px;
        }
        .sidebar.expanded ~ .main-content,
        .sidebar.expanded + .main-content { margin-left: 220px; }
        .main-content::-webkit-scrollbar { width: 6px; }
        .main-content::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 10px; }
        .main-content::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; } */

        /* Top bar - matching news.html */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            background: white;
            padding: 10px 20px;
            border-radius: 40px;
            border: 1px solid var(--border);
        }
        .top-bar-title {
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 0.3px;
        }
        .admin-badge {
            display: flex;
            align-items: center;
            gap: 9px;
            background: #f1f5f9;
            padding: 6px 14px 6px 8px;
            border-radius: 40px;
            cursor: pointer;
            transition: background 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
        }
        .admin-badge:hover { background: #eef2ff; border-color: var(--border); }
        .admin-avatar {
            width: 32px;
            height: 32px;
            background: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 15px;
        }
        .admin-name { font-size: 13px; font-weight: 600; color: #1e293b; }

        /* Page header */
        .page-header {
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-size: 30px;
            font-weight: 800;
        }
        .page-header p {
            font-size: 16px;
            color: #64748b;
            margin-top: 6px;
        }

        /* Help layout */
        .help-layout {
            display: flex;
            gap: 24px;
        }

        /* Left nav */
        .help-nav {
            width: 280px;
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            flex-shrink: 0;
            padding: 20px 0;
            height: fit-content;
        }
        body.dark-mode .help-nav { background: #1e293b; }
        .help-nav-section { margin-bottom: 6px; }
        .help-nav-heading {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--text-light);
            padding: 10px 20px 6px;
        }
        .help-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; cursor: pointer;
            font-size: 13.5px; font-weight: 500; color: #374151;
            border-left: 3px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
        }
        body.dark-mode .help-nav-item { color: #94a3b8; }
        .help-nav-item:hover { background: #f8fafc; color: var(--primary); }
        body.dark-mode .help-nav-item:hover { background: #0f172a; color: #60a5fa; }
        .help-nav-item.active {
            background: #eef2ff; color: var(--primary);
            border-left-color: var(--primary);
            font-weight: 600;
        }
        body.dark-mode .help-nav-item.active { background: #1e3a5f; color: #60a5fa; border-left-color: #3b82f6; }
        .help-nav-item i { width: 18px; text-align: center; font-size: 13px; opacity: 0.8; }

        /* Content area */
        .help-content {
            flex: 1;
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 32px 36px;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
        }
        body.dark-mode .help-content { background: #1e293b; }
        .help-content::-webkit-scrollbar { width: 6px; }
        .help-content::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }

        .help-section { display: none; }
        .help-section.active { display: block; }

        .section-hero {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 20px; padding: 32px 36px;
            color: white; margin-bottom: 28px;
            position: relative; overflow: hidden;
        }
        body.dark-mode .section-hero { background: linear-gradient(135deg, #1e3a5f 0%, #1e3a8a 100%); }
        .section-hero h1 { font-size: 26px; font-weight: 800; margin-bottom: 8px; }
        .section-hero p { font-size: 14px; opacity: 0.85; line-height: 1.6; max-width: 540px; }

        .help-card {
            background: white; border: 1px solid var(--border);
            border-radius: 16px; padding: 24px 28px;
            box-shadow: var(--card-shadow); margin-bottom: 20px;
        }
        body.dark-mode .help-card { background: #1e293b; border-color: #334155; }
        .help-card h2 {
            font-size: 16px; font-weight: 700; color: var(--text-dark);
            margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
        }
        body.dark-mode .help-card h2 { color: #f1f5f9; }
        .help-card h2 i { color: var(--primary); font-size: 15px; }
        .help-card p { font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 10px; }
        body.dark-mode .help-card p { color: #cbd5e1; }

        .step-list { list-style: none; counter-reset: steps; display: flex; flex-direction: column; gap: 10px; }
        .step-list li {
            counter-increment: steps;
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 14px; color: #374151; line-height: 1.6;
        }
        body.dark-mode .step-list li { color: #cbd5e1; }
        .step-list li::before {
            content: counter(steps);
            min-width: 24px; height: 24px;
            background: #eef2ff; color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
        }
        body.dark-mode .step-list li::before { background: #1e3a5f; color: #60a5fa; }

        .bullet-list { list-style: none; display: flex; flex-direction: column; gap: 8px; }
        .bullet-list li {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 14px; color: #374151; line-height: 1.6;
        }
        body.dark-mode .bullet-list li { color: #cbd5e1; }
        .bullet-list li::before {
            content: '•';
            color: var(--primary); font-weight: 700; flex-shrink: 0; margin-top: 1px;
        }

        .info-box {
            background: #eef2ff; border: 1px solid #c7d7ff;
            border-radius: 10px; padding: 12px 16px;
            display: flex; gap: 10px; align-items: flex-start;
            margin-top: 14px;
        }
        body.dark-mode .info-box { background: #1e3a5f; border-color: #1e3a8a; }
        .info-box i { color: var(--primary); margin-top: 2px; flex-shrink: 0; }
        .info-box p { font-size: 13px; color: #1e3a8a; margin: 0; line-height: 1.6; }
        body.dark-mode .info-box p { color: #93c5fd; }

        .warn-box {
            background: #fef3c7; border: 1px solid #fde68a;
            border-radius: 10px; padding: 12px 16px;
            display: flex; gap: 10px; align-items: flex-start;
            margin-top: 14px;
        }
        body.dark-mode .warn-box { background: #451a03; border-color: #78350f; }
        .warn-box i { color: #d97706; margin-top: 2px; flex-shrink: 0; }
        .warn-box p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.6; }
        body.dark-mode .warn-box p { color: #fcd34d; }

        .feature-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 14px; margin-top: 4px;
        }
        .feature-card-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: #eef2ff; color: #2563eb;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; margin-bottom: 10px;
        }
        body.dark-mode .feature-card { border-color: #334155; }
        .feature-card:hover {
            border-color: var(--primary); background: #fafbff;
            box-shadow: 0 4px 14px rgba(37,99,235,0.1);
        }
        body.dark-mode .feature-card:hover { background: #1e293b; border-color: #3b82f6; }
        .feature-card-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: #eef2ff; color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; margin-bottom: 10px;
        }
        .feature-card h3 { font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
        body.dark-mode .feature-card h3 { color: #f1f5f9; }
        .feature-card p { font-size: 12px; color: var(--text-light); line-height: 1.5; }

        .api-tag {
            display: inline-block; padding: 2px 10px;
            border-radius: 20px; font-size: 11px; font-weight: 700;
            font-family: monospace; margin-right: 6px;
        }
        .api-get { background: #dcfce7; color: #15803d; }
        .api-post { background: #dbeafe; color: #1d4ed8; }
        .api-put { background: #fef3c7; color: #b45309; }
        .api-del { background: #fee2e2; color: #b91c1c; }

        .api-row {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 0; border-bottom: 1px solid var(--border);
            font-size: 13px;
        }
        body.dark-mode .api-row { border-color: #334155; }
        .api-row:last-child { border-bottom: none; }
        .api-endpoint { font-family: monospace; color: #1e293b; }
        body.dark-mode .api-endpoint { color: #e2e8f0; }
        .api-desc { color: var(--text-light); margin-left: auto; font-size: 12px; }

        .shortcut-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 10px; margin-top: 4px;
        }
        .shortcut-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 8px 12px; background: #f8fafc;
            border-radius: 8px; border: 1px solid var(--border);
        }
        body.dark-mode .shortcut-row { background: #0f172a; border-color: #334155; }
        .shortcut-label { font-size: 13px; color: #374151; }
        body.dark-mode .shortcut-label { color: #cbd5e1; }
        .kbd {
            background: white; border: 1px solid #cbd5e1;
            border-bottom: 3px solid #cbd5e1;
            border-radius: 5px; padding: 2px 8px;
            font-size: 12px; font-family: monospace; color: #1e293b;
            font-weight: 600;
        }
        body.dark-mode .kbd { background: #1e293b; border-color: #475569; border-bottom-color: #475569; color: #e2e8f0; }
        body.dark-mode .feature-card-icon {
            background: #1e3a5f;
            color: #60a5fa;
        }

        body.dark-mode .feature-card-icon i {
            color: #60a5fa;
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

    <!-- Main content -->
    <main class="main-content" id="main">

        <!-- Top bar -->
        <div class="top-bar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1>Help & Documentation</h1>
            <p>Guides and reference for SUSAdmin</p>
        </div>

        <div class="help-layout">

            <!-- Left nav -->
            <nav class="help-nav" id="helpNav">
                <div class="help-nav-section">
                    <div class="help-nav-heading">Getting Started</div>
                    <a class="help-nav-item active" data-section="overview" onclick="showSection('overview')">
                        <i class="fas fa-home"></i> Overview
                    </a>
                    <a class="help-nav-item" data-section="login" onclick="showSection('login')">
                        <i class="fas fa-sign-in-alt"></i> Logging In
                    </a>
                    <a class="help-nav-item" data-section="navigation" onclick="showSection('navigation')">
                        <i class="fas fa-compass"></i> Navigation
                    </a>
                </div>
                <div class="help-nav-section">
                    <div class="help-nav-heading">Modules</div>
                    <a class="help-nav-item" data-section="dashboard" onclick="showSection('dashboard')">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                    <a class="help-nav-item" data-section="users" onclick="showSection('users')">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a class="help-nav-item" data-section="teachers" onclick="showSection('teachers')">
                        <i class="fas fa-chalkboard-teacher"></i> Teachers
                    </a>
                    <a class="help-nav-item" data-section="timetable" onclick="showSection('timetable')">
                        <i class="fas fa-calendar-alt"></i> Timetable
                    </a>
                    <a class="help-nav-item" data-section="grading" onclick="showSection('grading')">
                        <i class="fas fa-star-half-alt"></i> Grading
                    </a>
                    <a class="help-nav-item" data-section="attendance" onclick="showSection('attendance')">
                        <i class="fas fa-check-square"></i> Attendance
                    </a>
                    <a class="help-nav-item" data-section="content" onclick="showSection('content')">
                        <i class="fas fa-layer-group"></i> Content
                    </a>
                    <a class="help-nav-item" data-section="news" onclick="showSection('news')">
                        <i class="fas fa-newspaper"></i> News
                    </a>
                    <a class="help-nav-item" data-section="messages" onclick="showSection('messages')">
                        <i class="fas fa-comments"></i> Messages
                    </a>
                </div>
                <div class="help-nav-section">
                    <div class="help-nav-heading">Reference</div>
                    <a class="help-nav-item" data-section="api" onclick="showSection('api')">
                        <i class="fas fa-code"></i> API Reference
                    </a>
                    <a class="help-nav-item" data-section="shortcuts" onclick="showSection('shortcuts')">
                        <i class="fas fa-keyboard"></i> Shortcuts
                    </a>
                    <a class="help-nav-item" data-section="faq" onclick="showSection('faq')">
                        <i class="fas fa-question-circle"></i> FAQ
                    </a>
                </div>
            </nav>

            <!-- Content area -->
            <div class="help-content" id="helpContent">
                <div class="help-section active" id="section-overview">
                    <div class="section-hero">
                        <h1>Welcome to SUSAdmin</h1>
                        <p>This portal lets you manage every aspect of Smart University System — students, teachers, timetables, grades, attendance, and published content — all from one place.</p>
                    </div>
                    <div class="feature-grid">
                        <div class="feature-card" onclick="showSection('dashboard')">
                            <div class="feature-card-icon"><i class="fas fa-th-large"></i></div>
                            <h3>Dashboard</h3>
                            <p>Live stats, enrollment charts, and recent activity at a glance.</p>
                        </div>
                        <div class="feature-card" onclick="showSection('users')">
                            <div class="feature-card-icon"><i class="fas fa-users"></i></div>
                            <h3>Users</h3>
                            <p>View, filter, and manage all student accounts.</p>
                        </div>
                        <div class="feature-card" onclick="showSection('teachers')">
                            <div class="feature-card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            <h3>Teachers</h3>
                            <p>Add or update teacher profiles, departments, and subjects.</p>
                        </div>
                        <div class="feature-card" onclick="showSection('timetable')">
                            <div class="feature-card-icon"><i class="fas fa-calendar-alt"></i></div>
                            <h3>Timetable</h3>
                            <p>Drag-and-drop schedule builder with conflict detection.</p>
                        </div>
                        <div class="feature-card" onclick="showSection('grading')">
                            <div class="feature-card-icon"><i class="fas fa-star-half-alt"></i></div>
                            <h3>Grading</h3>
                            <p>Enter grades, set weights, and export GPA reports to CSV.</p>
                        </div>
                        <div class="feature-card" onclick="showSection('attendance')">
                            <div class="feature-card-icon"><i class="fas fa-check-square"></i></div>
                            <h3>Attendance</h3>
                            <p>Track daily presence and generate attendance reports.</p>
                        </div>
                    </div>
                </div>

                <div class="help-section" id="section-login">
                    <div class="section-hero">
                        <h1>Logging In</h1>
                        <p>Access the admin portal securely with your institutional credentials.</p>
                    </div>
                    <div class="help-card">
                        <h2><i class="fas fa-sign-in-alt"></i> How to Sign In</h2>
                        <ol class="step-list">
                            <li>Go to the portal URL provided by your IT department (the <strong>index.html</strong> login page).</li>
                            <li>Enter your admin <strong>email address</strong> (e.g. <code>admin@university.edu</code>).</li>
                            <li>Enter your <strong>password</strong> and press <strong>Sign In</strong> or hit <kbd class="kbd">Enter</kbd>.</li>
                            <li>You will be redirected to the <strong>Dashboard</strong> upon successful login.</li>
                        </ol>
                        <div class="warn-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>The portal is currently in <strong>mock/demo mode</strong> — any email and password will be accepted. The backend team must set <code>USE_MOCK = false</code> in <code>js/admin-api.js</code> and point <code>API_BASE_URL</code> to the live server before go-live.</p>
                        </div>
                    </div>
                    <div class="help-card">
                        <h2><i class="fas fa-lock"></i> Session & Security</h2>
                        <ul class="bullet-list">
                            <li>Your session token is stored in browser <code>localStorage</code> under the key <code>adminToken</code>.</li>
                            <li>All protected pages will redirect to the login page if no token is found.</li>
                            <li>Click <strong>Logout</strong> (bottom of sidebar) to end your session at any time.</li>
                        </ul>
                    </div>
                </div>

                <div class="help-section" id="section-navigation">
                    <div class="section-hero">
                        <h1>Navigation</h1>
                        <p>The sidebar is your primary navigation tool. It collapses to icons or expands to show labels.</p>
                    </div>
                    <div class="help-card">
                        <h2><i class="fas fa-bars"></i> Sidebar</h2>
                        <p>Click the <strong>toggle button</strong> (the small arrow on the right edge of the sidebar) to expand or collapse the menu. Hover over any icon in collapsed mode to see a tooltip.</p>
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>The sidebar state is not currently persisted across page loads. Each page load starts with the sidebar collapsed.</p>
                        </div>
                    </div>
                    <div class="help-card">
                        <h2><i class="fas fa-moon"></i> Dark Mode</h2>
                        <p>Click the <strong>moon / sun icon</strong> in the sidebar to toggle dark mode. Your preference is saved in <code>localStorage</code> and applied automatically on your next visit.</p>
                    </div>
                </div>

                <div class="help-section" id="section-dashboard">
                    <div class="section-hero"><h1>Dashboard</h1><p>A real-time overview of university metrics, charts, and recent activity.</p></div>
                    <div class="help-card"><h2><i class="fas fa-chart-bar"></i> Stats Cards</h2><p>The three summary cards at the top show <strong>Total Students</strong>, <strong>Total Teachers</strong>, and <strong>Active Courses</strong>. Clicking a card navigates to the corresponding module.</p></div>
                    <div class="help-card"><h2><i class="fas fa-chart-line"></i> Enrollment Chart</h2><p>The chart on the left shows enrollment trends. Switch between Students, Teachers, and Logins views using the tab buttons above the chart.</p></div>
                    <div class="help-card"><h2><i class="fas fa-bell"></i> Recent Activity</h2><p>The right panel lists recent system events. Click any item to expand it and see details or take quick actions like <strong>Review</strong> or <strong>Dismiss</strong>.</p></div>
                </div>

                <div class="help-section" id="section-users">
                    <div class="section-hero"><h1>Users</h1><p>Manage student accounts — view, search, edit, and delete.</p></div>
                    <div class="help-card"><h2><i class="fas fa-search"></i> Searching & Filtering</h2><p>Use the search bar to find students by name or ID. Filter by role and status using the dropdowns.</p></div>
                    <div class="help-card"><h2><i class="fas fa-edit"></i> Editing a User</h2><p>Click the Edit icon on the right side of the row, update fields in the modal, and click Save.</p></div>
                    <div class="help-card"><h2><i class="fas fa-trash"></i> Deleting a User</h2><p>Click the Delete icon and confirm. Deletions are permanent.</p></div>
                </div>

                <div class="help-section" id="section-teachers">
                    <div class="section-hero"><h1>Teachers</h1><p>Full CRUD management for teacher profiles.</p></div>
                    <div class="help-card"><h2><i class="fas fa-plus"></i> Adding a Teacher</h2><p>Click <strong>Add Teacher</strong>, fill in First Name, Last Name, Email, and Department (required), then click <strong>Save Teacher</strong>.</p></div>
                    <div class="help-card"><h2><i class="fas fa-id-card"></i> Teacher Profiles</h2><p>Click any teacher card to open their detailed profile view, including subjects, contact info, and office hours.</p></div>
                </div>

                <div class="help-section" id="section-timetable">
                    <div class="section-hero"><h1>Timetable Editor</h1><p>Drag-and-drop schedule builder with conflict detection.</p></div>
                    <div class="help-card"><h2><i class="fas fa-plus-square"></i> Adding a Slot</h2><p>Click an empty cell in the grid, or click the <strong>Add Slot</strong> button. Select Subject, Teacher, Group, Room, and click Save.</p></div>
                    <div class="help-card"><h2><i class="fas fa-exchange-alt"></i> Moving a Slot</h2><p>Drag any slot card to a different cell. The system runs a conflict check on drop.</p></div>
                    <div class="help-card"><h2><i class="fas fa-filter"></i> Filtering the View</h2><p>Use the <strong>Group</strong>, <strong>Teacher</strong>, and <strong>Room</strong> dropdowns to filter the timetable.</p></div>
                </div>

                <div class="help-section" id="section-grading">
                    <div class="section-hero"><h1>Grading</h1><p>Enter and manage student grades with configurable weights.</p></div>
                    <div class="help-card"><h2><i class="fas fa-sliders-h"></i> Grade Weights</h2><p>Click <strong>Weights</strong> to set percentages for Assignments, Midterm, and Final. They must add up to 100%.</p></div>
                    <div class="help-card"><h2><i class="fas fa-keyboard"></i> Entering Grades</h2><p>Click any grade cell to edit it inline. Changes are reflected in Final Score and GPA immediately.</p></div>
                    <div class="help-card"><h2><i class="fas fa-file-csv"></i> Exporting</h2><p>Click <strong>Export CSV</strong> to download a spreadsheet of all grades.</p></div>
                </div>

                <div class="help-section" id="section-attendance">
                    <div class="section-hero"><h1>Attendance</h1><p>Track and record student attendance session by session.</p></div>
                    <div class="help-card"><h2><i class="fas fa-check-circle"></i> Marking Attendance</h2><p>Select the <strong>Course</strong> and <strong>Date</strong> from the filters. Click each student row to toggle between Present, Late, and Absent.</p></div>
                    <div class="help-card"><h2><i class="fas fa-save"></i> Saving</h2><p>Click <strong>Save Session</strong> when done. The attendance records are stored permanently.</p></div>
                </div>

                <div class="help-section" id="section-content">
                    <div class="section-hero"><h1>Content</h1><p>Manage course modules, study materials, and learning resources.</p></div>
                    <div class="help-card"><h2><i class="fas fa-folder-plus"></i> Publishing Content</h2><p>Select the target <strong>Course</strong> from the left panel, then add content with title, description, and file attachments.</p></div>
                </div>

                <div class="help-section" id="section-news">
                    <div class="section-hero"><h1>News</h1><p>Create and publish university announcements and news articles.</p></div>
                    <div class="help-card"><h2><i class="fas fa-pen"></i> Writing an Article</h2><p>Click <strong>New Article</strong>, enter a title, choose a category, write the content, and click <strong>Publish</strong>.</p></div>
                </div>

                <div class="help-section" id="section-messages">
                    <div class="section-hero"><h1>Messages</h1><p>View and reply to messages sent from students and teachers.</p></div>
                    <div class="help-card"><h2><i class="fas fa-inbox"></i> Inbox</h2><p>Messages are listed on the left panel sorted by date. Click any message to open the full thread.</p></div>
                    <div class="help-card"><h2><i class="fas fa-reply"></i> Replying</h2><p>Type your reply in the text area and click <strong>Send Reply</strong>.</p></div>
                    <div class="help-card"><h2><i class="fas fa-building"></i> Departments</h2><p>The <strong>Departments</strong> section lets you manage which departments appear in the contact form.</p></div>
                </div>

                <div class="help-section" id="section-api">
                    <div class="section-hero"><h1>API Reference</h1><p>All endpoints are defined in <code>js/admin-api.js</code>.</p></div>
                    <div class="help-card"><h2><i class="fas fa-lock"></i> Auth</h2><div class="api-row"><span class="api-tag api-post">POST</span><span class="api-endpoint">/auth/login</span><span class="api-desc">Login, returns JWT token</span></div></div>
                    <div class="help-card"><h2><i class="fas fa-calendar-alt"></i> Timetable</h2><div class="api-row"><span class="api-tag api-get">GET</span><span class="api-endpoint">/timetable</span><span class="api-desc">All slots</span></div><div class="api-row"><span class="api-tag api-post">POST</span><span class="api-endpoint">/timetable</span><span class="api-desc">Add a new slot</span></div><div class="api-row"><span class="api-tag api-put">PUT</span><span class="api-endpoint">/timetable/:id</span><span class="api-desc">Update a slot</span></div><div class="api-row"><span class="api-tag api-del">DEL</span><span class="api-endpoint">/timetable/:id</span><span class="api-desc">Delete a slot</span></div></div>
                    <div class="help-card"><h2><i class="fas fa-chalkboard-teacher"></i> Teachers</h2><div class="api-row"><span class="api-tag api-get">GET</span><span class="api-endpoint">/teachers</span><span class="api-desc">List all teachers</span></div><div class="api-row"><span class="api-tag api-post">POST</span><span class="api-endpoint">/teachers</span><span class="api-desc">Create teacher</span></div></div>
                </div>

                <div class="help-section" id="section-shortcuts">
                    <div class="section-hero"><h1>Keyboard Shortcuts</h1><p>Speed up common workflows across the portal.</p></div>
                    <div class="help-card"><h2><i class="fas fa-keyboard"></i> Global</h2><div class="shortcut-grid"><div class="shortcut-row"><span class="shortcut-label">Focus search</span><kbd class="kbd">/</kbd></div><div class="shortcut-row"><span class="shortcut-label">Close modal</span><kbd class="kbd">Esc</kbd></div><div class="shortcut-row"><span class="shortcut-label">Toggle sidebar</span><kbd class="kbd">[ ]</kbd></div></div></div>
                    <div class="info-box"><i class="fas fa-info-circle"></i><p>Most modals across the portal can be dismissed with <kbd class="kbd">Esc</kbd>.</p></div>
                </div>

                <div class="help-section" id="section-faq">
                    <div class="section-hero"><h1>Frequently Asked Questions</h1><p>Common questions about the SUSAdmin portal.</p></div>
                    <div class="help-card"><h2><i class="fas fa-question"></i> Why does login accept any password?</h2><p>The portal is currently running in <strong>mock mode</strong>. Before deployment, the backend team must flip this flag and point <code>API_BASE_URL</code> to the real server.</p></div>
                    <div class="help-card"><h2><i class="fas fa-question"></i> My changes disappeared after refreshing — why?</h2><p>All data mutations in mock mode happen in-memory. A page reload resets all data back to the hardcoded defaults. Real persistence requires backend integration.</p></div>
                    <div class="help-card"><h2><i class="fas fa-question"></i> The profile page has no sidebar — is that intentional?</h2><p>The <code>profile.html</code> page is currently a basic stub. It should be fully integrated before handoff.</p></div>
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
function showSection(id) {
    document.querySelectorAll('.help-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.help-nav-item').forEach(a => a.classList.remove('active'));
    const sec = document.getElementById('section-' + id);
    if (sec) sec.classList.add('active');
    const link = document.querySelector('[data-section="' + id + '"]');
    if (link) link.classList.add('active');
    document.getElementById('helpContent').scrollTop = 0;
}

function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('expanded');
}
function showLogoutPopup() {
document.getElementById('logoutPopup').classList.add('show');
}
function hideLogoutPopup() {
    document.getElementById('logoutPopup').classList.remove('show');
}

// Apply saved theme
(function () {
    const saved = localStorage.getItem('darkMode');
    if (saved === 'enabled') {
        document.body.classList.add('dark-mode');
    }
})();
</script>
</body>
</html>