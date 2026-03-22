<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Timetable & Booking - Smart University System</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <style>
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-collapsed);
            background: #2563eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000; 
            transition: var(--transition);
            overflow: hidden;
            color: white;
        }

        .sidebar.expanded {
            width: var(--sidebar-expanded);
        }

        .sidebar-toggle-btn {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: background 0.2s;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(255,255,255,0.15);
        }

        .sidebar-toggle-btn img {
            width: 28px;
            height: 28px;
            transition: transform 0.4s ease;
        }

        .sidebar.expanded .sidebar-toggle-btn img {
            transform: rotate(180deg);
        }

        .sidebar-icons {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }

        .sidebar-icon {
            width: calc(100% - 30px);
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 0 18px;
            margin: 4px 8px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            color: rgba(255, 255, 255, 0.85);
        }

        .sidebar-icon:hover,
        .sidebar-icon.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
        }

        .sidebar-icon img {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            margin-right: 0;
            transition: margin-right 0.3s ease;
        }

        .sidebar.expanded .sidebar-icon img {
            margin-right: 16px;
        }

        .sidebar-label {
            opacity: 0;
            visibility: hidden;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            font-size: 15px;
            font-weight: 500;
            transition: opacity 0.22s ease 0.05s, width 0.35s ease, visibility 0.35s;
        }

        .sidebar.expanded .sidebar-label {
            opacity: 1;
            visibility: visible;
            width: auto;
        }

        .theme-toggle,
        .logout-icon {
            margin-top: auto;
        }

        
        .sidebar:not(.expanded) .sidebar-icon {
            position: relative;
        }
        
        .sidebar:not(.expanded) .sidebar-icon::after {
            content: attr(data-tooltip);
            position: fixed;
            left: 98px; 
            top: auto;
            background: #1e293b;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 9999; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            line-height: 1;
        }

        .sidebar:not(.expanded) .sidebar-icon::before {
            content: '';
            position: fixed; 
            left: 87px; 
            top: auto;
            border: 7px solid transparent;
            border-right-color: #1e293b;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 9999;
        }

        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(1):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(1):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::before {
            opacity: 1;
        }
        
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::before {
            opacity: 1;
        }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::before {
            opacity: 1;
        }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::before {
            opacity: 1;
        }
        

        .main-content {
            margin-left: var(--sidebar-collapsed);
            transition: var(--transition);
            flex: 1;
            padding: 20px;
            max-width: 2000px;
            position: relative;
            z-index: 1; 
        }

        .sidebar.expanded + .main-content {
            margin-left: var(--sidebar-expanded);
        }

        body.dark-mode .sidebar {
            background: #1e293b;
        }

        body.dark-mode .sidebar-icon::after {
            background: #334155;
        }

        body.dark-mode .sidebar-icon::before {
            border-right-color: #334155;
        }

        .logout-icon {
            margin-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 0px;
        }

        .logout-icon:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        body.dark-mode .logout-icon {
            border-top-color: rgba(255, 255, 255, 0.05);
        }


        .top-bar {
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .logo-container {
            height: 70px;            
            width: 150px;             
            overflow: hidden;         
            display: flex;
            align-items: center;      
            justify-content: flex-start;
            padding-left: 0; 
            margin-left: 0 ;  
            }

        .logo-container img {
            max-height: 250%; 
            height: auto;            
            width: auto;
            object-fit: contain;
            margin-left: -40px;  
        }

        .logo-light {
            display: block;
        }

        .logo-dark {
          display: none;
        }

        body.dark-mode .logo-light {
            display: none;
        }

        body.dark-mode .logo-dark {
            display: block;
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Calendar header */
        .calendar-header {
            background: white;
            padding: 20px 24px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .nav-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .today-btn {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .today-btn:hover {
            background: #f8fafc;
        }
        .my-timetable-btn {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .my-timetable-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        body.dark-mode .my-timetable-btn {
            background: #334155;
            border-color: #475569;
            color: #e2e8f0;
        }

        body.dark-mode .my-timetable-btn:hover {
            background: #475569;
        }

        .current-month {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }

        .calendar-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* Dropdown styles */
        .dropdown-container {
            position: relative;
        }

        .dropdown-btn {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .dropdown-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .dropdown-btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
        }

        .dropdown-btn.open svg {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 100;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 10px 16px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: #f8fafc;
        }

        .dropdown-item.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .current-view-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Calendar grid */
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e2e8f0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .calendar-day-header {
            background: #f8fafc;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
        }

        .calendar-day {
            background: white;
            min-height: 120px;
            padding: 8px;
            position: relative;
            cursor: pointer;
            transition: background 0.2s;
        }

        .calendar-day:hover {
            background: #f8fafc;
        }

        .calendar-day.other-month {
            background: #fafafa;
            opacity: 0.5;
        }

        .day-number {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .day-events {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .event-item {
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .event-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .event-time {
            font-size: 10px;
            opacity: 0.8;
        }

        .event-title {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-location {
            font-size: 10px;
            opacity: 0.7;
        }

        /* Event type */
        .event-lecture {
            background: #dbeafe;
            color: #1e40af;
        }

        .event-lab {
            background: #dcfce7;
            color: #166534;
        }

        .event-tutorial {
            background: #fce7f3;
            color: #9f1239;
        }

        .event-club {
            background: #fef3c7;
            color: #92400e;
        }

        body.dark-mode .sidebar {
            background: #1e293b;
        }

        body.dark-mode .top-bar,
        body.dark-mode .calendar-header,
        body.dark-mode .calendar-container {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .calendar-grid {
            background: #334155;
            border-color: #334155;
        }

        body.dark-mode .calendar-day-header {
            background: #334155;
            color: #94a3b8;
        }

        body.dark-mode .calendar-day {
            background: #1e293b;
            border-color: #334155;
        }

        body.dark-mode .calendar-day:hover {
            background: #334155;
        }

        body.dark-mode .calendar-day.other-month {
            background: #0f172a;
        }

        body.dark-mode .day-number {
            color: #cbd5e1;
        }

        body.dark-mode .nav-btn,
        body.dark-mode .today-btn,
        body.dark-mode .dropdown-btn {
            background: #334155;
            border-color: #475569;
            color: #e2e8f0;
        }

        body.dark-mode .nav-btn:hover,
        body.dark-mode .today-btn:hover,
        body.dark-mode .dropdown-btn:hover {
            background: #475569;
        }

        body.dark-mode .dropdown-menu {
            background: #334155;
            border-color: #475569;
        }

        body.dark-mode .dropdown-item {
            border-bottom-color: #475569;
        }

        body.dark-mode .dropdown-item:hover {
            background: #475569;
        }

        body.dark-mode .dropdown-item.active {
            background: #1e3a8a;
            color: #60a5fa;
        }

        body.dark-mode .current-month,
        body.dark-mode .page-title {
            color: #f1f5f9;
        }

        body.dark-mode .current-view-badge {
            background: #1e3a8a;
            color: #60a5fa;
        }

        /* Modal styles */
        .event-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .event-modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .modal-content {
            background: #1e293b;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f1f5f9;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            color: #64748b;
        }

        body.dark-mode .modal-close {
            background: #334155;
            color: #e2e8f0;
        }

        .event-detail {
            margin-bottom: 16px;
        }

        .event-detail-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .event-detail-value {
            font-size: 14px;
            font-weight: 500;
        }

        body.dark-mode .event-detail-label {
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .calendar-grid {
                display: flex;
                flex-direction: column;
            }

            .calendar-day {
                min-height: 80px;
            }

            .calendar-actions {
                flex-wrap: wrap;
            }

            
            .sidebar-icon::after,
            .sidebar-icon::before {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- sidebar -->
        <aside class="sidebar">
            <div class="sidebar-toggle-btn">
                <img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle Sidebar">
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
                <div class="sidebar-icon" data-page="records" data-tooltip="Records">
                    <img src="{{ asset('images/records.png') }}" alt="Records">
                <span class="sidebar-label">Records</span>
                </div>
                <div class="sidebar-icon" data-page="news" data-tooltip="News">
                    <img src="{{ asset('images/news.png') }}" alt="News">
                    <span class="sidebar-label">News</span>
                </div>
                <div class="sidebar-icon" data-page="teachers" data-tooltip="Teachers">
                    <img src="{{ asset('images/teacher.png') }}" alt="Teachers">
                    <span class="sidebar-label">Teachers</span>
                </div>
                <div class="sidebar-icon" data-page="career" data-tooltip="Career Center">
                    <img src="{{ asset('images/career.png') }}" alt="Career Center">
                    <span class="sidebar-label">Career Center</span>
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

        <main class="main-content">
            <div class="top-bar">
              <div class="logo-container">
                <img src="{{ asset('images/sus_logo.png') }}" alt="SuS" class="logo-light">
                <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SuS" class="logo-dark">
              </div>  
              <h1 class="page-title">Smart University System</h1>
            </div>

            <div class="calendar-header">
                <div class="calendar-controls">
                    <div class="calendar-nav">
                        <button class="today-btn" onclick="goToToday()">Today</button>
                        <button class="nav-btn" onclick="previousMonth()">
                            <img src="{{ asset('images/arrow_back.png')}}" style="width: 15px; height: 10px;display: block; margin-left: 5px;">
                        </button>
                        <button class="nav-btn" onclick="nextMonth()">
                            <img src="{{ asset('images/arrow_forward.png') }}" style="width: 15px; height: 10px;display: block; margin-left: 5px;">
                        </button>
                        <h2 class="current-month" id="currentMonth">February 2026</h2>
                        <span class="current-view-badge" id="currentViewBadge">My Timetable</span>
                    </div>
                    <div class="calendar-actions">
                        <button class="my-timetable-btn" onclick="loadMyTimetable()">My Timetable</button>
                        <div class="dropdown-container">
    
                            <button class="dropdown-btn" id="groupsBtn" onclick="toggleDropdown('groupsDropdown')">
                                Groups
                                <img src="{{ asset('images/arrow_down.png') }}" style="width: 20px; height: 20px;display: block; margin-left: 5px;">
                            </button>
                            <div class="dropdown-menu" id="groupsDropdown"></div>
                        </div>

                        <div class="dropdown-container">
                            <button class="dropdown-btn" id="teachersBtn" onclick="toggleDropdown('teachersDropdown')">
                                Teachers
                                <img src="{{ asset('images/arrow_down.png') }}" style="width: 20px; height: 20px;display: block; margin-left: 5px;">
                            </button>
                            <div class="dropdown-menu" id="teachersDropdown"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="calendar-container">
                <div class="calendar-grid" id="calendarGrid">
                    <p style="padding:40px;color:#64748b;grid-column:1/-1;text-align:center;">Loading timetable...</p>
                </div>
            </div>
        </main>
    </div>

    <div class="event-modal" id="eventModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Event Details</h3>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Time</div>
                <div class="event-detail-value" id="modalTime"></div>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Location</div>
                <div class="event-detail-value" id="modalLocation"></div>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Instructor</div>
                <div class="event-detail-value" id="modalInstructor"></div>
            </div>
            <div class="event-detail" id="modalGroupContainer">
                <div class="event-detail-label">Group</div>
                <div class="event-detail-value" id="modalGroup"></div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/api.js') }}"></script>

    <script>
        // authGuard();

        function initTheme() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        }
        initTheme();

        
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode',
                document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });

        // Logout func
       document.querySelector('.logout-icon').addEventListener('click', function() {
           if (confirm('Are you sure you want to logout?')) {
             AuthAPI.logout();
            }
        });

        // Sidebar 
        var sidebar = document.querySelector('.sidebar');
        var toggleBtn = document.querySelector('.sidebar-toggle-btn');
        var mainContent = document.querySelector('.main-content');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('expanded');
            });
        }

        if (mainContent) {
            mainContent.addEventListener('click', function(e)  {
                if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('expanded');
                }
            });
        }

            // Page nav
        document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon) {
            icon.addEventListener('click', function() {
                const pageMap = {
                    'dashboard': '{{ route('dashboard') }}', 
                    'timetable': '{{ route('timetable') }}', 
                    'modules': '{{ route('modules') }}',
                    'records': '{{ route('records') }}', 
                    'news': '{{ route('news') }}', 
                    'teachers': '{{ route('teachers') }}',
                    'career': '{{ route('career-centre') }}', 
                    'contact': '{{ route('contact') }}', 
                    'help': '{{ route('help') }}'
                };

                var dest = pageMap[this.dataset.page];
                if (dest) window.location.href = dest;
            });
        });

        var currentDate   = new Date();   
        var currentView   = 'my';        
        var currentEntity = null;

        var timetableData = { my: {} };  // groups/teachers
        var groupsList   = [];   //  id, name  from API
        var teachersList = [];   //  id, firstName, lastName, title from API


        function getMonday() {
            const today = new Date();
            const dayOfWeek = today.getDay();
            const diff = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
            const monday = new Date(today);
            monday.setDate(today.getDate() + diff);
            return monday;
        }

        function daysToDateMap(daysObj, monday) {
            var dayNames = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            var map = {};
            dayNames.forEach(function(name, idx) {
                if (daysObj[name] && daysObj[name].length > 0) {
                    var d = new Date(monday);
                    d.setDate(monday.getDate() + idx);
                    map[d.getDate()] = daysObj[name];
                }
            });
            return map;
        }

        async function loadTimetable() {
            try {
                // console.log("[Timetable] Starting load...");

                // Step 1: Weekly timetable (your own schedule)
                // console.log("[Timetable] Fetching /timetable/week...");
                const res = await TimetableAPI.getWeeklyTimetable();
                // console.log("[Timetable] Weekly res:", res);

                const days = res?.data?.days || {};
                if (Object.keys(days).length === 0) {
                    // console.warn("[Timetable] No days found in response");
                }

                const monday = getMonday();
                timetableData.my = daysToDateMap(days, monday);

                // Step 2: Groups
                // console.log("[Timetable] Fetching groups...");
                const groupsRes = await TimetableAPI.getGroups();
                // console.log("[Timetable] Groups res:", groupsRes);
                groupsList = groupsRes?.data || [];

                // Step 3: Teachers
                // console.log("[Timetable] Fetching teachers...");
                const teachersRes = await TeacherAPI.getTeachers();
                // console.log("[Timetable] Teachers res:", teachersRes);
                teachersList = teachersRes?.data || [];

                buildDropdowns();
                updateMonthDisplay();
                renderCalendar();

                // console.log("[Timetable] Load complete!");

            } catch (err) {
                console.error("[Timetable] Critical failure:", err.message, err.stack);

                let friendlyMsg = "Could not load timetable.";
                if (err.message.includes("404")) friendlyMsg += " (Endpoint not found)";
                if (err.message.includes("500")) friendlyMsg += " (Server error)";
                if (err.message.includes("Unauthorized")) friendlyMsg += " (Please log in again)";

                document.getElementById('calendarGrid').innerHTML = `
                    <p style="padding:40px; color:#dc2626; grid-column:1/-1; text-align:center; font-weight:500;">
                        ${friendlyMsg}<br>
                        <small style="color:#64748b;">Check browser console (F12) for details</small>
                    </p>`;
            }
        }

        function buildDropdowns() {
            var groupsMenu   = document.getElementById('groupsDropdown');
            var teachersMenu = document.getElementById('teachersDropdown');

            groupsMenu.innerHTML   = '';
            teachersMenu.innerHTML = '';

            groupsList.forEach(g => {
                const item = document.createElement('div');
                item.textContent = g.name || g.id;
                item.className = 'dropdown-item';
                item.onclick = () => {
                    loadGroupTimetable(g.id, g.name || g.id);
                    toggleDropdown('groupsDropdown');
                };
                groupsMenu.appendChild(item);
            });

            teachersList.forEach(t => {
                const fullName = (t.title ? t.title + ' ' : '') + t.firstName + ' ' + t.lastName;
                const item = document.createElement('div');
                item.textContent = fullName.trim();
                item.className = 'dropdown-item';
                item.onclick = () => {
                    loadTeacherTimetable(t.id, fullName.trim());
                    toggleDropdown('teachersDropdown');
                };
                teachersMenu.appendChild(item);
            });
        }        

        async function loadGroupTimetable(groupId, groupName) {
            // console.log(`[Group] Selected: ${groupId} (${groupName})`);

            currentView   = 'group';
            currentEntity = groupId;
            document.getElementById('currentViewBadge').textContent = groupName || groupId;

            const key = 'group_' + groupId;

            if (!timetableData[key]) {
                try {
                    // console.log('[Group] Fetching...');
                    const res = await TimetableAPI.getGroupWeeklyTimetable(groupId);
                    // console.log('[Group] Response:', res);

                    const monday = getMonday();
                    timetableData[key] = daysToDateMap(res.data.days, monday);
                    // console.log('[Group] Stored:', timetableData[key]);
                } catch (err) {
                    // console.error('[Group] Failed:', err);
                }
            }

            toggleDropdown('groupsDropdown');
            renderCalendar();
        }

        async function loadTeacherTimetable(teacherId, teacherName) {
            // console.log(`[Teacher] Selected: ${teacherId} (${teacherName})`);

            currentView   = 'teacher';
            currentEntity = teacherId;
            document.getElementById('currentViewBadge').textContent = teacherName || teacherId;

            const key = 'teacher_' + teacherId;

            if (!timetableData[key]) {
                try {
                    // console.log('[Teacher] Fetching...');
                    const res = await TimetableAPI.getTeacherWeeklyTimetable(teacherId);
                    // console.log('[Teacher] Response:', res);

                    const monday = getMonday();
                    timetableData[key] = daysToDateMap(res.data.days, monday);
                    // console.log('[Teacher] Stored:', timetableData[key]);
                } catch (err) {
                    // console.error('[Teacher] Failed:', err);
                }
            }

            toggleDropdown('teachersDropdown');
            renderCalendar();
        }       

        function updateMonthDisplay() {
            var months = ['January','February','March','April','May','June',
                          'July','August','September','October','November','December'];
            document.getElementById('currentMonth').textContent =
                months[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateMonthDisplay();
            renderCalendar();
        }
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateMonthDisplay();
            renderCalendar();
        }

        function goToToday() {
            currentDate = new Date();
            updateMonthDisplay();
            renderCalendar();
        }
      
        function loadMyTimetable() {
            currentView   = 'my';
            currentEntity = null;
            document.getElementById('currentViewBadge').textContent = 'My Timetable';
            renderCalendar();
        }

        function toggleDropdown(dropdownId) {
            var dropdown    = document.getElementById(dropdownId);
            var allDropdowns = document.querySelectorAll('.dropdown-menu');
            var allButtons   = document.querySelectorAll('.dropdown-btn');

            allDropdowns.forEach(function(d) {
                if (d.id !== dropdownId) d.classList.remove('show');
            });
            allButtons.forEach(function(b) { b.classList.remove('open'); });

            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) {
                var btnId = dropdownId === 'groupsDropdown' ? 'groupsBtn' : 'teachersBtn';
                document.getElementById(btnId).classList.add('open');
            }
        }
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
                document.querySelectorAll('.dropdown-btn').forEach(function(b) { b.classList.remove('open'); });
            }
        });

        function renderCalendar() {
            var grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            
            var eventsData;
            if (currentView === 'my') {
                eventsData = timetableData.my;
            } else if (currentView === 'group') {
                eventsData = timetableData['group_' + currentEntity] || {};
            } else {
                eventsData = timetableData['teacher_' + currentEntity] || {};
            }

            
            var year  = currentDate.getFullYear();
            var month = currentDate.getMonth();

            var firstDay      = new Date(year, month, 1);
            var lastDay       = new Date(year, month + 1, 0);
            var daysInMonth   = lastDay.getDate();

            var startDow = firstDay.getDay();
            startDow = (startDow === 0) ? 6 : startDow - 1;

            
            var prevMonthDays = new Date(year, month, 0).getDate();

            var todayDate  = new Date();
            var isThisMonth = (todayDate.getFullYear() === year && todayDate.getMonth() === month);

            
            var html = '<div class="calendar-day-header">Mon</div>' +
                       '<div class="calendar-day-header">Tue</div>' +
                       '<div class="calendar-day-header">Wed</div>' +
                       '<div class="calendar-day-header">Thu</div>' +
                       '<div class="calendar-day-header">Fri</div>' +
                       '<div class="calendar-day-header">Sat</div>' +
                       '<div class="calendar-day-header">Sun</div>';
            for (var i = startDow - 1; i >= 0; i--) {
                var d = prevMonthDays - i;
                html += '<div class="calendar-day other-month">' +
                        '<div class="day-number">' + d + '</div>' +
                        '<div class="day-events"></div></div>';
            }

            
            for (var day = 1; day <= daysInMonth; day++) {
                var isToday   = isThisMonth && day === todayDate.getDate();
                var dayClass  = 'calendar-day' + (isToday ? ' today' : '');
                var events    = eventsData[day] || [];

                var eventsHtml = '';
                // events.forEach(function(event) {
                //     var instructor = event.instructor || 
                //                     (currentView === 'teacher' ? currentEntity : 'TBD');
                    
                //     var group = event.group || 
                //                 (currentView === 'group' ? currentEntity : 'N/A');
                    
                //     // Better type handling
                //     var eventType = (event.type || 'lecture').toLowerCase();
                //     var typeClass = 'event-' + eventType;
                    
                //     // Optional: capitalize first letter for display
                //     var displayType = eventType.charAt(0).toUpperCase() + eventType.slice(1);

                //     eventsHtml +=
                //         '<div class="event-item ' + typeClass + '" ' +
                //             'onclick=\'showEventDetails(' +
                //                 JSON.stringify(event.title)      + ',' +
                //                 JSON.stringify(event.time)       + ',' +
                //                 JSON.stringify(event.location || event.room || 'TBA') + ',' +
                //                 JSON.stringify(instructor)       + ',' +
                //                 JSON.stringify(group)            +
                //             ')\'>' +
                //             '<span class="event-time">'     + event.time     + '</span>' +
                //             '<span class="event-title">'    + event.title    + '</span>' +
                //             '<span class="event-location">' + (event.location || event.room || 'TBA') + '</span>' +
                //             (displayType !== 'Lecture' ? '<small style="opacity:0.7;">' + displayType + '</small>' : '') +
                //         '</div>';
                // });
                events.forEach(function(event) {
                    var instructor = (currentView === 'teacher') ? '' : (event.instructor || 'TBD');
                    var group      = (currentView === 'group')   ? currentEntity : (event.group || 'N/A');

                    // ALWAYS show type (Lecture, Lab, Tutorial)
                    var rawType    = event.type || 'Lecture';
                    var displayType = rawType.charAt(0).toUpperCase() + rawType.slice(1).toLowerCase();

                    var typeClass  = 'event-' + rawType.toLowerCase();

                    eventsHtml +=
                        '<div class="event-item ' + typeClass + '" ' +
                            'onclick=\'showEventDetails(' +
                                JSON.stringify(event.title) + ',' +
                                JSON.stringify(event.time) + ',' +
                                JSON.stringify(event.location || event.room || 'TBA') + ',' +
                                JSON.stringify(instructor) + ',' +
                                JSON.stringify(group) +
                            ')\'>' +
                            '<span class="event-time">'     + event.time     + '</span>' +
                            '<span class="event-title">'    + event.title    + '</span>' +
                            '<span class="event-location">' + (event.location || event.room || 'TBA') + '</span>' +
                            '<small style="display:block; opacity:0.8; margin-top:4px; font-size:10px; font-weight:500;">' +
                                displayType +
                            '</small>' +
                        '</div>';
                });

                html += '<div class="' + dayClass + '">' +
                        '<div class="day-number">' + day + '</div>' +
                        '<div class="day-events">' + eventsHtml + '</div>' +
                        '</div>';
            }
            var totalCells  = startDow + daysInMonth;
            var trailingDays = (totalCells % 7 === 0) ? 0 : 7 - (totalCells % 7);
            for (var t = 1; t <= trailingDays; t++) {
                html += '<div class="calendar-day other-month">' +
                        '<div class="day-number">' + t + '</div>' +
                        '<div class="day-events"></div></div>';
            }

            grid.innerHTML = html;
        }
        
        function showEventDetails(title, time, location, instructor, group) {
            document.getElementById('modalTitle').textContent    = title;
            document.getElementById('modalTime').textContent     = time;
            document.getElementById('modalLocation').textContent = location;

            if (currentView === 'teacher') {
                document.getElementById('modalInstructor').textContent = currentEntity;
                document.getElementById('modalGroupContainer').style.display = 'block';
                document.getElementById('modalGroup').textContent      = group || 'N/A';
            } else if (currentView === 'group') {
                document.getElementById('modalInstructor').textContent = instructor || 'N/A';
                document.getElementById('modalGroupContainer').style.display = 'block';
                document.getElementById('modalGroup').textContent      = currentEntity;
            } else {
                document.getElementById('modalInstructor').textContent = instructor || 'N/A';
                document.getElementById('modalGroupContainer').style.display = 'block';
                document.getElementById('modalGroup').textContent      = group || 'N/A';
            }
            document.getElementById('eventModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('eventModal').classList.remove('active');
        }

        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

     
        loadTimetable();
    </script>
</body>
</html>