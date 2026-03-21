<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Smart University System</title>

    <!-- Your CSS files -->
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

        /* tooltips */
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

        /* Top bar */
        .top-bar {
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-height: 10px;
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
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .share-btn {
            padding: 8px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .share-btn:hover {
            background: #1d4ed8;
        }

        /* Student card */
        .student-card {
            background: white;
            padding: 32px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .student-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 600;
        }

        .student-info h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .student-email {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .student-details {
            display: flex;
            gap: 32px;
            font-size: 13px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            color: #64748b;
            margin-bottom: 2px;
        }

        .detail-value {
            font-weight: 600;
            color: #1e293b;
        }

        .gpa-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .gpa-badge svg {
            width: 14px;
            height: 14px;
            fill: #10b981;
        }

        /* Schedule section */
        .schedule-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .schedule-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .schedule-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .schedule-title svg {
            width: 20px;
            height: 20px;
        }

        .schedule-date {
            color: #64748b;
            font-size: 14px;
        }

        .class-count {
            background: #2563eb;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .class-item {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .class-item:hover {
            border-color: #2563eb;
            background: #f8fafc;
        }

        .class-time {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .class-name {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .class-details {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 13px;
            color: #64748b;
        }

        .class-detail {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .class-detail svg {
            width: 14px;
            height: 14px;
        }

        .class-type {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-lecture {
            background: #dbeafe;
            color: #1e40af;
        }

        .type-lab {
            background: #dcfce7;
            color: #166534;
        }

        /* Dashboard grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .dashboard-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .card-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* Charts */
        .chart-container {
            width: 100%;
            height: 250px;
            position: relative;
        }

        .pie-chart {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .grade-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 200px;
            gap: 12px;
        }
        .bar-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .bar {
            width: 100%;
            border-radius: 4px 4px 0 0;
            transition: all 0.3s;
        }

        .bar:hover {
            opacity: 0.8;
        }

        .bar-label {
            font-size: 11px;
            color: #64748b;
            text-align: center;
            transform: rotate(-45deg);
            white-space: nowrap;
        }

        /* Stts grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .stat-card .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #64748b;
        }

        body.dark-mode .sidebar {
            background: #1e293b;
        }

        /* body.dark-mode .main-content, */
        body.dark-mode .student-card,
        body.dark-mode .schedule-section,
        body.dark-mode .dashboard-card,
        body.dark-mode .stat-card,
        body.dark-mode .top-bar {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .class-item {
            border-color: #334155;
            background: #1e293b;
        }

        body.dark-mode .class-item:hover {
            background: #334155;
        }

        body.dark-mode .detail-value,
        body.dark-mode .class-name,
        body.dark-mode .card-title,
        body.dark-mode .page-title {
            color: #f1f5f9;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .student-details { flex-wrap: wrap; gap: 16px; }
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
            <div class="top-bar">
              <div class="logo-container">
                <img src="{{ asset('images/sus_logo.png') }}" alt="SuS" class="logo-light">
                <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SuS" class="logo-dark">
              </div>  
              <h1 class="page-title">Smart University System</h1>
            </div>
            
            <div class="student-card">
                <div class="student-header">
                    <div class="student-avatar" id="studentAvatar">S</div>
                    <div class="student-info">
                        <h2 id="studentName">Loading...</h2>
                        <p class="student-email" id="studentEmail">-</p>
                        <div class="student-details">
                            <div class="detail-item">
                                <span class="detail-label">Student ID</span>
                                <span class="detail-value" id="studentId">-</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Program</span>
                                <span class="detail-value" id="studentProgramme">-</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Year</span>
                                <span class="detail-value" id="studentYear">—</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">GPA</span>
                                <span class="detail-value gpa-badge" id="studentGpa">
                                    —
                                    <img src="{{ asset('images/gpa.png') }}" style="width:14px;height:14px;">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's schedule -->
            <div class="schedule-section">
                <div class="schedule-header">
                    <div>
                        <div class="schedule-title">
                            <img src="{{ asset('images/calendar_today.png') }}" style="width: 20px; height: 20px;display: block; margin-left: 3px;">
                            Today's Schedule
                        </div>
                        <div class="schedule-date" id="scheduleDate">-</div>
                    </div>
                    <div class="class-count" id="classCount">- Classes</div>
                </div>
                <div id="scheduleList">
                    <p class="no-classes">Loading schedule...</p>
                </div>
                
                

             
            </div>

            <!-- Dashboard grid -->
            <div class="dashboard-grid">
                <!-- Grade distribution -->
                <div class="dashboard-card">
                    <h3 class="card-title">Grade Distribution</h3>
                    <p class="card-subtitle">Current semester performance by grade</p>
                    
                    <div class="chart-container">
                        <svg class="pie-chart" viewBox="0 0 200 200">
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#10b981" stroke-width="40" 
                                    stroke-dasharray="167 335" transform="rotate(-90 100 100)"/>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#3b82f6" stroke-width="40" 
                                    stroke-dasharray="251 335" stroke-dashoffset="-167" transform="rotate(-90 100 100)"/>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#f59e0b" stroke-width="40" 
                                    stroke-dasharray="85 335" stroke-dashoffset="-418" transform="rotate(-90 100 100)"/>
                        </svg>
                    </div>

                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #10b981;"></div>
                            <span>A (90-100%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #3b82f6;"></div>
                            <span>B (80-89%)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f59e0b;"></div>
                            <span>C (70-79%)</span>
                        </div>
                    </div>

                    <div class="grade-stats">
                        <div class="stat-item">
                            <div class="stat-number" id="gradeA" style="color: #10b981;">-</div>
                            <div class="stat-label">A Grades</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="gradeB" style="color: #3b82f6;">-</div>
                            <div class="stat-label">B Grades</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="gradeC" style="color: #f59e0b;">-</div>
                            <div class="stat-label">C Grades</div>
                        </div>
                    </div>
                </div>

                <!-- Course perfor -->
                <div class="dashboard-card">
                    <h3 class="card-title">Course Performance</h3>
                    <p class="card-subtitle">Current scores by course</p>
                    
                    <div class="bar-chart" id="barChart">

                    </div>

                    <div class="chart-legend" style="margin-top: 24px;">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #10b981;"></div>
                            <span>90-100% (A)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #3b82f6;"></div>
                            <span>80-89% (B)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f59e0b;"></div>
                            <span>70-79% (C)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" id="statModules" style="color: #2563eb;">-</div>
                    <div class="stat-label">Enrolled Courses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="statGpa" style="color: #10b981;">-</div>
                    <div class="stat-label">Current GPA</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="statCredits" style="color: #f59e0b;">-</div>
                    <div class="stat-label">Total Credits</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="statAttendance" style="color: #8b5cf6;">-</div>
                    <div class="stat-label">Attendance Rate</div>
                </div>
            </div>
        </main>
    </div>


    <script src="{{ asset('js/api.js') }}"></script>

    <script>
    //    authGuard();

       
       function initTheme() {
          if (localStorage.getItem('darkMode') === 'enabled') {
              document.body.classList.add('dark-mode');
            }
        }

        
        initTheme();

        
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
    
            // Save preference
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // logout func
        document.querySelector('.logout-icon').addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                AuthAPI.logout();
            }
        });

        // Sidebar toggle & nav
        var sidebar = document.querySelector('.sidebar');
        var toggleBtn = document.querySelector('.sidebar-toggle-btn');
        var mainContent = document.querySelector('.main-content');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('expanded');
            });
        }

        if (mainContent) {
            mainContent.addEventListener('click', function(e) {
                if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('expanded');
                }
            });
        }

        // Page nav
        document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon) {
            icon.addEventListener('click', function() {
                var pageMap = {
                    'dashboard': '/dashboard',
                    'timetable': '/timetable',
                    'modules':   '/modules',
                    'records':   '/records',
                    'news':      '/news',
                    'teachers':  '/teachers',
                    'career':    '/career-centre',
                    'contact':   '/contact'
                };

                var dest = pageMap[this.dataset.page];
                if (dest) window.location.href = dest;
            });
        });

        // api integration
        async function loadDashboard() {
            try {

                var results = await Promise.all([
                    StudentAPI.getProfile(),
                    StudentAPI.getDashboardStats(),
                    TimetableAPI.getTodaySchedule(),
                    ModuleAPI.getEnrolledModules()
                ]);
                var profile  = results[0].data;
                var stats    = results[1].data;
                var classes  = results[2].data || [];
                var modules  = results[3].data || [];

                var initials = profile.firstName[0] + profile.lastName[0];


                // fill student card
                document.getElementById('studentAvatar').textContent    = profile.firstName[0] + profile.lastName[0];
                document.getElementById('studentName').textContent      = profile.firstName + ' ' + profile.lastName;
                document.getElementById('studentEmail').textContent     = profile.email;
                document.getElementById('studentId').textContent        = profile.studentId;
                document.getElementById('studentProgramme').textContent = profile.programme;
                document.getElementById('studentYear').textContent      = 'Year ' + profile.year;
                document.getElementById('studentGpa').innerHTML         = profile.gpa + ' <img src="{{ asset('images/gpa.png') }}">';

                // fill stat cards
                document.getElementById('statModules').textContent    = stats.enrolledModules;
                document.getElementById('statGpa').textContent        = stats.gpa;
                document.getElementById('statCredits').textContent    = stats.creditsCompleted;
                document.getElementById('statAttendance').textContent = stats.attendancePercentage + '%';

                // fill today's schedule
                document.getElementById('classCount').textContent = classes.length + ' Classes';

                var today = new Date();
                var dayName = today.toLocaleDateString('en-GB', { weekday: 'long' });
                var dateStr = today.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
                document.getElementById('scheduleDate').textContent  = dayName + ', ' + dateStr;
                document.getElementById('classCount').textContent    = classes.length + ' Classes';

                var scheduleList = document.getElementById('scheduleList');
                if (classes.length === 0) {
                    scheduleList.innerHTML = '<p style="color:#64748b;padding:20px 0;">No classes today.</p>';
                } else {
                    scheduleList.innerHTML = '';
                    classes.forEach(function(c) {
                        scheduleList.innerHTML += '<div class="class-item">' +
                            '<div>' +
                                '<div class="class-time">' + c.startTime + ' - ' + c.endTime + '</div>' +
                                '<div class="class-name">' + c.moduleName + '</div>' +
                                '<div class="class-details">' +
                                    '<div class="class-detail"><img src="{{ asset('images/pin.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;"> ' + c.room + '</div>' +
                                    '<div class="class-detail"><img src="{{ asset('images/person.png') }}" style="width:20px;height:20px;display:block;margin-left:3px;"> ' + c.instructor + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="class-type type-' + c.type.toLowerCase() + '">' + c.type + '</div>' +
                        '</div>';
                    });
                }

                var barChart = document.getElementById('barChart');
                if (modules.length > 0) {
                    barChart.innerHTML = '';
                    modules.forEach(function(m) {
                        var pct   = m.progress || 0;
                        var color = pct >= 90 ? '#10b981' : pct >= 80 ? '#3b82f6' : '#f59e0b';
                        var label = m.name.split(' ').slice(0, 2).join(' ');
                        barChart.innerHTML +=
                            '<div class="bar-item">' +
                                '<div class="bar" style="height:' + pct + '%;background:' + color + ';"></div>' +
                                '<div class="bar-label">' + label + '</div>' +
                            '</div>';
                    });
                }

                var aCount = 0, bCount = 0, cCount = 0;
                modules.forEach(function(m) {
                    var p = m.progress || 0;
                    if (p >= 90)      aCount++;
                    else if (p >= 80) bCount++;
                    else              cCount++;
                });
                document.getElementById('gradeA').textContent = aCount;
                document.getElementById('gradeB').textContent = bCount;
                document.getElementById('gradeC').textContent = cCount;

            }  catch (err) {
                console.error('Dashboard load failed:', err.message);
                document.getElementById('studentName').textContent = 'Could not load profile';
                document.getElementById('scheduleList').innerHTML =
                    '<p class="no-classes" style="color:#dc2626;">Could not load schedule. Please refresh.</p>';
            }
        }

        loadDashboard();
    </script>
</body>
</html>