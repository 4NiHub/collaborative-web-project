<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>News CMS – SUSAdmin</title>
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

        /* Admin News CMS  */
        .news-grid {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 24px;
        }
        
        /* Editor Card */
        .editor-card { padding: 28px; }
        .editor-card h3 { font-size: 22px; font-weight: 700; margin-bottom: 24px; color: #1e293b; }
        body.dark-mode .editor-card h3 { color: #f1f5f9; }
        
        .form-group { margin-bottom: 24px; }
        .form-label { font-size: 15px; font-weight: 600; margin-bottom: 8px; display: block; color: #475569; }
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        body.dark-mode .form-input { background: #0f172a; color: #e2e8f0; border-color: #334155; }

        .form-select {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            cursor: pointer;
        }
        body.dark-mode .form-select { background: #0f172a; color: #e2e8f0; border-color: #334155; }

        /* Rich Text Toolbar */
        .rich-toolbar {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        .tool-btn {
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            background: white;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #374151;
            transition: all 0.2s;
        }
        body.dark-mode .tool-btn { background: #0f172a; color: #e2e8f0; border-color: #334155; }
        .tool-btn:hover { background: #eff6ff; border-color: #2563eb; color: #2563eb; }

        .content-editor {
            width: 100%;
            min-height: 200px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            font-size: 15px;
            line-height: 1.7;
            color: #1e293b;
            background: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            resize: vertical;
            outline: none;
            overflow-y: auto;
        }
        body.dark-mode .content-editor { background: #0f172a; color: #e2e8f0; border-color: #334155; }
        .content-editor:focus { border-color: #2563eb; }
        .content-editor:empty:before {
            content: attr(data-placeholder);
            color: #94a3b8;
        }

        /* Media Upload */
        .media-upload {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            font-size: 14px;
            color: #64748b;
            margin-top: 16px;
            transition: all 0.2s;
        }
        .media-upload:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
        body.dark-mode .media-upload:hover { background: #1e3a5f; }

        /* Action Buttons */
        .action-row { display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
        .btn-publish {
            background: #16a34a;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-publish:hover { background: #15803d; }
        .btn-draft {
            background: white;
            color: #475569;
            border: 1px solid var(--border);
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        body.dark-mode .btn-draft { background: #1e293b; color: #94a3b8; }
        .btn-draft:hover { background: #f1f5f9; }
        .btn-clear {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid var(--border);
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-clear:hover { background: #fee2e2; color: #dc2626; border-color: #dc2626; }

        /* Articles List  */
        .articles-list { padding: 24px; }
        .articles-list h3 { font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #1e293b; }
        body.dark-mode .articles-list h3 { color: #f1f5f9; }

        .article-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s;
            background: white;
            cursor: pointer;
        }
        body.dark-mode .article-card { background: #1e293b; }
        .article-card:hover { border-color: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        
        .article-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .article-title { font-size: 17px; font-weight: 700; color: #1e293b; margin-bottom: 8px; line-height: 1.4; }
        body.dark-mode .article-title { color: #f1f5f9; }
        .article-preview { font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 12px; }
        body.dark-mode .article-preview { color: #cbd5e1; }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 12px;
            font-size: 14px;
            color: #94a3b8;
            flex-wrap: wrap;
        }
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-draft { background: #fef3c7; color: #92400e; }
        
        .article-actions { display: flex; gap: 8px; flex-shrink: 0; }
        .icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 16px;
        }
        body.dark-mode .icon-btn { background: #0f172a; color: #94a3b8; }
        .icon-btn:hover { background: #eef2ff; color: #2563eb; border-color: #2563eb; }
        .icon-btn.del:hover { background: #fee2e2; color: #dc2626; border-color: #dc2626; }

        /* Stats Cards  */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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
            cursor: pointer;
            transition: all 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.12); border-color: #2563eb; }
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

        /* Toast Notification */
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
            width: 400px;
            max-width: 90%;
            padding: 28px;
            text-align: center;
        }
        body.dark-mode .modal-box { background: #1e293b; }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 700;
        }
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; }
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #64748b;
        }
        body.dark-mode .btn-secondary { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .btn-clear { background: #334155 !important; color: #e2e8f0 !important; border-color: #475569 !important; }
        body.dark-mode .modal-box { background: #1e293b !important; color: #e2e8f0 !important; }
        body.dark-mode .modal-close { color: #e2e8f0 !important; }
        .editor-card h3 img,
        .articles-list h3 img,
        .article-meta img,
        .action-row button img,
        .media-upload img {
            display: inline;
            vertical-align: middle;
            margin-right: 6px;
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
        <!-- Top Bar -->
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
                <h1 style="font-size:30px;font-weight:800;">News Management</h1>
                <p style="font-size:16px;color:#64748b;margin-top:6px;">Create, edit, and publish university announcements</p>
            </div>
        </div>

        <!-- Stats Cards  -->
        <div class="stats-row">
            <div class="stat-card" onclick="filterArticles('all')">
                <div class="stat-icon"><img src="{{ asset('images/admin_icons/news_black.png') }}" style="width:28px;height:28px;"></div>
                <div><div class="stat-number" id="totalArticles">0</div><div class="stat-label">Total Articles</div></div>
            </div>
            <div class="stat-card" onclick="filterArticles('published')">
                <div class="stat-icon"><img src="{{ asset('images/admin_icons/check_circle_black.png') }}" style="width:28px;height:28px;"></div>
                <div><div class="stat-number" id="publishedCount">0</div><div class="stat-label">Published</div></div>
            </div>
            <div class="stat-card" onclick="filterArticles('draft')">
                <div class="stat-icon"><img src="{{ asset('images/admin_icons/edit.png') }}" style="width:28px;height:28px;"></div>
                <div><div class="stat-number" id="draftCount">0</div><div class="stat-label">Drafts</div></div>
            </div>
        </div>

        <div class="news-grid">
            <!-- Editor Panel -->
            <div class="card editor-card">
                <h3 id="editorTitle"><img src="{{ asset('images/admin_icons/assignment.png') }}" style="width:35px;height:35px;vertical-align:middle;margin-right:8px;">Create New Article</h3>
                
                <div class="form-group">
                    <label class="form-label">Article Title</label>
                    <input type="text" class="form-input" id="articleTitle" placeholder="Enter catchy title...">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="articleCategory">
                        <option value="Academic"> Academic</option>
                        <option value="Campus"> Campus</option>
                        <option value="Events"> Events</option>
                        <option value="Important"> Important</option>
                        <option value="General"> General</option>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="display:block; font-size:13px; color:#94a3b8; font-weight:600; margin-bottom:6px;">Cover Image</label>
                    <input type="file" id="newsCoverImage" class="form-input" accept="image/*" style="width:100%; background:#0f172a; border:1px solid #334155; padding:10px 12px; border-radius:8px; color:white;">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Author</label>
                    <input type="text" class="form-input" id="articleAuthor" placeholder="Author name" value="Admin">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Content</label>
                    <div class="rich-toolbar">
                        <button class="tool-btn" onclick="formatDoc('bold')"><b>B</b></button>
                        <button class="tool-btn" onclick="formatDoc('italic')"><i>I</i></button>
                        <button class="tool-btn" onclick="formatDoc('underline')"><u>U</u></button>
                        <button class="tool-btn" onclick="formatDoc('insertUnorderedList')">•</button>
                        <button class="tool-btn" onclick="formatDoc('insertOrderedList')">1.</button>
                        <button class="tool-btn" onclick="formatDoc('justifyLeft')">◀</button>
                        <button class="tool-btn" onclick="formatDoc('justifyCenter')">≡</button>
                        <button class="tool-btn" onclick="formatDoc('justifyRight')">▶</button>
                    </div>
                    <div class="content-editor" id="articleContent" contenteditable="true" data-placeholder="Write your article content here..."></div>
                </div>
                
                <div class="media-upload" onclick="document.getElementById('mediaInput').click()">
                    <img src="{{ asset('images/admin_icons/image.png') }}" style="width:28px;height:28px;"> Click to upload featured image
                    <input type="file" id="mediaInput" style="display:none" accept="image/*" onchange="handleImageUpload(this)">
                </div>
                
                <div class="action-row">
                    <button class="btn-publish" onclick="publishArticle()"><img src="{{ asset('images/admin_icons/publish.png') }}" style="width:28px;height:28px;"> Publish Now</button>
                    <button class="btn-draft" onclick="saveAsDraft()"><img src="{{ asset('images/admin_icons/content.png') }}" style="width:28px;height:28px;"> Save as Draft</button>
                    <button class="btn-clear" onclick="clearEditor()"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:28px;height:28px;"> Clear</button>
                </div>
            </div>

            <!-- Articles List Panel -->
            <div class="card articles-list">
                <h3><img src="{{ asset('images/admin_icons/tasks.png') }}" style="width:28px;height:28px;"> All Articles <span id="filterBadge" style="font-size:14px; font-weight:normal;"></span></h3>
                <div id="articlesList"></div>
            </div>
        </div>
    </main>
</div>

<!-- Delete confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-header">Delete Article <button class="modal-close" onclick="closeDeleteModal()">✕</button></div>
        <p style="font-size:16px; margin:16px 0;">Are you sure you want to delete this article? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-primary" id="confirmDeleteBtn" style="background:#dc2626;">Delete</button>
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

let articles = [];

let editingId = null;
let deleteTargetId = null;
let currentFilter = 'all';

async function init() {
    try {
        const res = await AdminNewsAPI.getAll();
        articles = res.data;
        renderArticles();
    } catch (err) {
        showToast('Failed to load articles: ' + err.message, 'error');
    }
}
init();


function showToast(msg, type = 'success') {
    let t = document.getElementById('__toast');
    if (!t) { t = document.createElement('div'); t.id = '__toast'; t.className = 'toast-notification'; document.body.appendChild(t); }
    t.className = `toast-notification toast-${type}`;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}


function formatDoc(cmd) { document.execCommand(cmd, false, null); }


function handleImageUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.margin = '10px 0';
            img.style.borderRadius = '8px';
            document.getElementById('articleContent').appendChild(img);
            showToast('Image added to content', 'success');
        };
        reader.readAsDataURL(input.files[0]);
    }
    input.value = '';
}


function updateStats() {
    document.getElementById('totalArticles').innerText = articles.length;
    document.getElementById('publishedCount').innerText = articles.filter(a => a.status === 'Active').length;
    document.getElementById('draftCount').innerText = articles.filter(a => a.status === 'Draft').length;
}


function filterArticles(type) {
    currentFilter = type;
    const badge = document.getElementById('filterBadge');
    if (type === 'all') badge.innerHTML = '';
    else if (type === 'published') badge.innerHTML = ' (Showing Published only)';
    else badge.innerHTML = ' (Showing Drafts only)';
    renderArticles();
}

function getFilteredArticles() {
    if (currentFilter === 'published') return articles.filter(a => a.status === 'Active');
    if (currentFilter === 'draft') return articles.filter(a => a.status === 'Draft');
    return articles;
}


function renderArticles() {
    const filtered = getFilteredArticles();
    const container = document.getElementById('articlesList');
    
    if (filtered.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:40px;color:#94a3b8;font-size:15px;">No articles found. Create your first article above.</div>';
        updateStats();
        return;
    }
    
    container.innerHTML = filtered.map(a => {
        // 🚨 NEW: If the article has an image, build the HTML for it!
        const coverImageHtml = a.image 
            ? `<img src="${a.image}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 16px;">` 
            : '';

        return `
        <div class="article-card" data-id="${a.id}">
            ${coverImageHtml} <div class="article-top">
                <div style="flex:1;" onclick="editArticle(${a.id})">
                    <div class="article-title">${escapeHtml(a.title)}</div>
                    <div class="article-preview">${escapeHtml(a.content.replace(/<[^>]*>/g, '').substring(0, 120))}${a.content.length > 120 ? '...' : ''}</div>
                    <div class="article-meta">
                        <span class="badge badge-${a.status === 'Active' ? 'active' : 'draft'}">${a.status}</span>
                        <span><img src="{{ asset('images/admin_icons/clock_grey.png') }}" style="width:28px;height:28px;"> ${a.date}</span>
                        <span><img src="{{ asset('images/admin_icons/person_grey.png') }}" style="width:28px;height:28px;"> ${escapeHtml(a.author)}</span>
                        <span> ${a.category}</span>
                    </div>
                </div>
                <div class="article-actions">
                    <button class="icon-btn" onclick="event.stopPropagation(); editArticle(${a.id})" title="Edit"><img src="{{ asset('images/admin_icons/edit_grey.png') }}" style="width:28px;height:28px;"></button>
                    <button class="icon-btn del" onclick="event.stopPropagation(); confirmDelete(${a.id})" title="Delete"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:28px;height:28px;"></button>
                </div>
            </div>
        </div>
    `}).join('');
    
    updateStats();
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

async function publishArticle() {
    const title    = document.getElementById('articleTitle').value.trim();
    const content  = document.getElementById('articleContent').innerHTML.trim();
    const category = document.getElementById('articleCategory').value;
    const author   = document.getElementById('articleAuthor').value.trim();
    
    if (!title) { showToast('Please enter an article title', 'error'); return; }
    if (!content || content === '<br>') { showToast('Please enter article content', 'error'); return; }

    const formData = new FormData();
    formData.append('title', title);
    formData.append('content', content);
    formData.append('category', category);
    formData.append('author', author);
    formData.append('status', 'Active');

    const imageInput = document.getElementById('newsCoverImage');
    if (imageInput && imageInput.files.length > 0) {
        formData.append('cover_image', imageInput.files[0]);
    }

    try {
        if (editingId) {
            formData.append('_method', 'PUT'); 
            // Changed apiCall to adminApiCall and fixed the route
            await adminApiCall(`/news/${editingId}`, { method: 'POST', body: formData });
            showToast('Article updated and published!', 'success');
        } else {
            // Changed apiCall to adminApiCall and fixed the route
            await adminApiCall('/news', { method: 'POST', body: formData });
            showToast('Article published successfully!', 'success');
        }
        
        editingId = null;
        document.getElementById('editorTitle').innerHTML = '<img src="{{ asset('images/admin_icons/assignment.png') }}" style="width:35px;height:35px;vertical-align:middle;margin-right:8px;"> Create New Article';
        clearEditor();
        
        const res = await AdminNewsAPI.getAll();
        articles = res.data;
        renderArticles();
    } catch (err) {
        showToast('Failed to publish: ' + err.message, 'error');
    }
}

async function saveAsDraft() {
    const title    = document.getElementById('articleTitle').value.trim();
    const content  = document.getElementById('articleContent').innerHTML.trim();
    const category = document.getElementById('articleCategory').value;
    const author   = document.getElementById('articleAuthor').value.trim();
    
    if (!title) { showToast('Please enter an article title', 'error'); return; }

    const formData = new FormData();
    formData.append('title', title);
    formData.append('content', content);
    formData.append('category', category);
    formData.append('author', author);
    formData.append('status', 'Draft');

    const imageInput = document.getElementById('newsCoverImage');
    if (imageInput && imageInput.files.length > 0) {
        formData.append('cover_image', imageInput.files[0]);
    }

    try {
        if (editingId) {
            formData.append('_method', 'PUT');
            // Changed apiCall to adminApiCall and fixed the route
            await adminApiCall(`/news/${editingId}`, { method: 'POST', body: formData });
            showToast('Draft saved!', 'success');
        } else {
            // Changed apiCall to adminApiCall and fixed the route
            await adminApiCall('/news', { method: 'POST', body: formData });
            showToast('Draft saved successfully!', 'success');
        }
        
        editingId = null;
        document.getElementById('editorTitle').innerHTML = '<img src="{{ asset('images/admin_icons/assignment.png') }}" style="width:35px;height:35px;"> Create New Article';
        clearEditor();
        
        const res = await AdminNewsAPI.getAll();
        articles = res.data;
        renderArticles();
    } catch (err) {
        showToast('Failed to save draft: ' + err.message, 'error');
    }
}

function editArticle(id) {
    const article = articles.find(a => a.id === id);
    if (!article) return;
    
    editingId = id;
    document.getElementById('editorTitle').innerHTML = '<img src="{{ asset('images/admin_icons/edit.png') }}" style="width:28px;height:28px;"> Edit Article';
    document.getElementById('articleTitle').value = article.title;
    document.getElementById('articleCategory').value = article.category;
    document.getElementById('articleAuthor').value = article.author;
    document.getElementById('articleContent').innerHTML = article.content;
    showToast(`Editing: ${article.title}`, 'success');
}

function confirmDelete(id) {
    deleteTargetId = id;
    document.getElementById('deleteModal').classList.add('open');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
    deleteTargetId = null;
}

async function deleteArticle() {
    if (deleteTargetId) {
        try {
            await AdminNewsAPI.delete(deleteTargetId);
            if (editingId === deleteTargetId) clearEditor();
            const res = await AdminNewsAPI.getAll();
            articles = res.data;
            renderArticles();
            showToast('Article deleted successfully', 'success');
            closeDeleteModal();
        } catch (err) {
            showToast('Failed to delete: ' + err.message, 'error');
        }
    }
}

function clearEditor() {
    document.getElementById('articleTitle').value = '';
    document.getElementById('articleCategory').value = 'Academic';
    document.getElementById('articleAuthor').value = 'Admin';
    document.getElementById('articleContent').innerHTML = '';
    editingId = null;
    document.getElementById('editorTitle').innerHTML = '<img src="{{ asset('images/admin_icons/assignment.png') }}" style="width:35px;height:35px;"> Create New Article';
}


document.getElementById('confirmDeleteBtn').onclick = deleteArticle;
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