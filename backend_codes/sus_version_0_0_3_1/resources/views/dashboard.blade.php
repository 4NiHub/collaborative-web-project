<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Smart University System</title>
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
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::before { opacity: 1; }
        

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

        .chart-legend-pie{
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .chart-legend-column{
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 100px;
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
            margin-bottom: 10px;
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

        /*body.dark-mode .main-content,*/
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

        /* ── Charts (cleaned & fixed – only one set of rules) ──────────────────────────────── */

        .chart-container {
            width: 100%;
            height: 250px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pie-chart {
            width: 220px;
            height: 220px;
        }

        /* ── FINAL BAR CHART – bars start exactly at 0%, % labels perfectly centered above bars ──────────────────────── */
        .bar-chart-dev{
            display: flex;
            justify-content: center;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 200px;
            width: 500px;
            left: 70px;
            padding: 20px 0 0 15px;
            /* position: relative; */
            border-left: 2px solid #64748b;
            border-bottom: 2px solid #64748b;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent 24.8%,
                rgba(148, 163, 184, 0.22) 25%,
                rgba(148, 163, 184, 0.22) 25.2%
            );
        }

        .bar-item {
            flex: 1;
            max-width: 65px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .bar-wrapper {
            flex: 1;
            width: 100%;
            display: flex;
            align-items: flex-end;
            position: relative;
        }

        .bar {
            width: 58px;
            border-radius: 10px 10px 0 0;
            transition: height 0.9s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            position: relative;           /* ← IMPORTANT for perfect % placement */
        }

        .bar-value {
            position: absolute;
            top: -28px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 13.5px;
            /* font-weight: 700; */
            pointer-events: none;
            z-index: 2;
            white-space: nowrap;
        }

        /* Light mode */
        .bar-value {
            color: #1e293b;
        }

        /* Dark mode – matches your screenshot */
        body.dark-mode .bar-value {
            color: #e2e8f0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.7);
        }

        .bar-label {
            font-size: 11.5px;               /* slightly larger for readability */
            font-weight: 500;                /* bit bolder than before */
            color: #313131;
            transform: rotate(-40deg);       /* -40° usually looks cleaner than -35° */
            white-space: nowrap;
            width: 140px;                    /* allow longer names without truncation */
            line-height: -2;
            position: absolute;
            left: 100%;
            bottom: -20px;                    /* fine-tune vertical position */
            transform: translateX(-50%) rotate(-40deg);
            pointer-events: none;            /* don't block hover/clicks */
        }

        body.dark-mode .bar-label{
            color: #94a3b8;
        }

        /* Y-axis labels (now perfectly aligned) */

        .y-axis-labels {
            margin: 1px 10px 0 0;
            height: calc(100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 11px;
            color: #94a3b8;
            text-align: right;
            pointer-events: none;
            font-weight: 600;
        }

        body.dark-mode .bar-label {
            color: #cbd5e1;
            text-shadow: 0 1px 2px rgba(0,0,0,0.6);
        }

        body.dark-mode .bar-chart {
            border-color: #475569;
        }

        body.dark-mode .y-axis-labels {
            color: #64748b;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Empty state polish */
        body.dark-mode #scheduleList {
            background: #1e293b;
        }

        .empty-state-svg {
            color: #64748b;           /* darker gray in light mode */
        }

        /* Add to your <style> block */
        body.dark-mode .empty-state-svg {
            color: #94a3b8;           /* lighter gray in dark mode */
        }

        .schedule-list-off{
            text-align:center; 
            padding: 80px 20px 60px; 
            color: #64748b;
        }

        .schedule-list-off .schedule-list-svg{
            display: flex;
            justify-content: center;
        }

        .schedule-list-p-1{
            font-size: 19px; 
            font-weight: 600; 
            color: #2b2c2d;             
            margin-bottom: 6px;
        }

        body.dark-mode .schedule-list-p-1{
            color: #e2e8f0;
        }

        .schedule-list-p-2{
            font-size: 15px;
            color: #364350;
        }

        body.dark-mode .schedule-list-p-2{
            color: #94a3b8;
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
                            <!-- Reset everything to zero first (this kills the ghost slices) -->
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#10b981" stroke-width="40" 
                                    stroke-dasharray="0 502" transform="rotate(-90 100 100)"/>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#3b82f6" stroke-width="40" 
                                    stroke-dasharray="0 502" stroke-dashoffset="0" transform="rotate(-90 100 100)"/>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#f59e0b" stroke-width="40" 
                                    stroke-dasharray="0 502" stroke-dashoffset="0" transform="rotate(-90 100 100)"/>
                        </svg>
                    </div>

                    <div class="chart-legend-pie">
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

                    <div class="bar-chart-dev">
                        <div id="barChartY"></div>
                        <div class="bar-chart" id="barChart"></div>
                    </div>

                    <div class="chart-legend-column">
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
        function initTheme() {
            if (localStorage.getItem('darkMode') === 'enabled') document.body.classList.add('dark-mode');
        }
        initTheme();

        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });

        document.querySelector('.logout-icon').addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) AuthAPI.logout();
        });

        // Sidebar nav (unchanged)
        var sidebar = document.querySelector('.sidebar');
        var toggleBtn = document.querySelector('.sidebar-toggle-btn');
        var mainContent = document.querySelector('.main-content');
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('expanded'));
        mainContent.addEventListener('click', (e) => {
            if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) sidebar.classList.remove('expanded');
        });

        document.querySelectorAll('.sidebar-icon[data-page]').forEach(icon => {
            icon.addEventListener('click', () => {
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
                if (pageMap[icon.dataset.page]) window.location.href = pageMap[icon.dataset.page];
            });
        });

        async function loadDashboard() {
            try {
                const [profileRes, statsRes, todayRes, weekRes, modulesRes] = await Promise.all([
                    StudentAPI.getProfile(),
                    StudentAPI.getDashboardStats(),
                    StudentAPI.getTodaySchedule(),
                    StudentAPI.getWeeklyTimetable(),
                    StudentAPI.getEnrolledModules()   // this call is important for charts
                ]);

                const p       = profileRes?.data  || {};
                const s       = statsRes?.data    || {};
                const classes = todayRes?.data    || [];     // ← declared ONLY ONCE here
                const modules = modulesRes?.data  || [];

                // Student card
                document.getElementById('studentAvatar').textContent =
                    ((p.firstName?.[0] || '') + (p.lastName?.[0] || '')).toUpperCase() || 'S';

                document.getElementById('studentName').textContent =
                    `${p.firstName || ''} ${p.lastName || ''}`.trim() || 'Student Name';

                document.getElementById('studentEmail').textContent   = p.email      || '—';
                document.getElementById('studentId').textContent      = p.studentId  || p.id || '—';
                document.getElementById('studentProgramme').textContent = p.programme || p.major || '—';
                document.getElementById('studentYear').textContent    = p.year ? `Year ${p.year}` : '—';

                document.getElementById('studentGpa').innerHTML =
                    `${Number(p.gpa || s.gpa || 0).toFixed(2)} <img src="{{ asset('images/gpa.png') }}" style="width:14px;height:14px;">`;

                // Stats cards
                document.getElementById('statModules').textContent    = s.enrolledModules ?? modules.length ?? '—';
                document.getElementById('statGpa').textContent        = Number(s.gpa ?? p.gpa ?? 0).toFixed(2);
                document.getElementById('statCredits').textContent    = s.creditsCompleted ?? '—';
                document.getElementById('statAttendance').textContent =
                    s.attendancePercentage ? `${Math.round(s.attendancePercentage)}%` : '—';

                // Today's schedule – date
                const now = new Date();
                const weekday = now.toLocaleString('en-US', { weekday: 'long' });
                const dateFormatted = now.toLocaleDateString('en-GB', {
                    day: '2-digit', month: '2-digit', year: 'numeric'
                }).replace(/\//g, '.');
                document.getElementById('scheduleDate').textContent = `${weekday}, ${dateFormatted}`;

                // Today's schedule – list
                document.getElementById('classCount').textContent = `${classes.length} Class${classes.length !== 1 ? 'es' : ''}`;

                const scheduleList = document.getElementById('scheduleList');
                scheduleList.innerHTML = '';

                if (classes.length === 0) {
                    scheduleList.innerHTML = `
                        <div class="schedule-list-off">
                            <div class="schedule-list-svg">
                                <svg class="empty-state-svg" width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 20px; opacity: 0.7;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M16 2V6M8 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="schedule-list-p-1">No classes scheduled for today</p>
                                <p class="schedule-list-p-2">Enjoy your day off! 🎉</p>
                            </div>
                        </div>`;
                } else {
                    classes.forEach(c => {
                        const start = c.starttime || c.startTime || c.time?.split('–')[0]?.trim() || 'TBA';
                        const end   = c.endtime   || c.endTime   || c.time?.split('–')[1]?.trim() || 'TBA';

                        scheduleList.innerHTML += `
                            <div class="class-item">
                                <div>
                                    <div class="class-time">${start} – ${end}</div>
                                    <div class="class-name">${c.moduleName || c.title || c.name || 'Unknown Module'}</div>
                                    <div class="class-details">
                                        <div><img src="{{ asset('images/pin.png') }}" style="width:20px;height:20px;"> ${c.room || c.location || '—'}</div>
                                        <div><img src="{{ asset('images/person.png') }}" style="width:20px;height:20px;"> ${c.instructor || c.teacher || '—'}</div>
                                    </div>
                                </div>
                                <div class="class-type type-${(c.type || 'lecture').toLowerCase().trim()}">
                                    ${(c.type || 'Lecture').toUpperCase().trim()}
                                </div>
                            </div>`;
                    });
                }

                // Grade distribution (pie chart)
                let aCount = 0, bCount = 0, cCount = 0;
                modules.forEach(m => {
                    const pct = Number(m.progress || m.percentage || m.grade || 0);
                    if      (pct >= 90) aCount++;
                    else if (pct >= 80) bCount++;
                    else if (pct >= 70) cCount++;
                });

                const total = modules.length || 1;
                document.getElementById('gradeA').textContent = aCount;
                document.getElementById('gradeB').textContent = bCount;
                document.getElementById('gradeC').textContent = cCount;

                const circumference = 502;
                const aAngle = (aCount / total) * circumference;
                const bAngle = (bCount / total) * circumference;
                const cAngle = (cCount / total) * circumference;

                const circles = document.querySelectorAll('.pie-chart circle');
                if (circles.length >= 3) {
                    circles[0].setAttribute('stroke-dasharray', `${aAngle} ${circumference - aAngle}`);
                    circles[1].setAttribute('stroke-dasharray', `${bAngle} ${circumference - bAngle}`);
                    circles[1].setAttribute('stroke-dashoffset', `-${aAngle}`);
                    circles[2].setAttribute('stroke-dasharray', `${cAngle} ${circumference - cAngle}`);
                    circles[2].setAttribute('stroke-dashoffset', `-${aAngle + bAngle}`);
                }

                // Course Performance bar chart
                const barChart   = document.getElementById('barChart');
                const barChartY  = document.getElementById('barChartY');
                barChart.innerHTML = '';

                barChartY.innerHTML = `
                    <div class="y-axis-labels">
                        <span>100%</span>
                        <span>75%</span>
                        <span>50%</span>
                        <span>25%</span>
                        <span>0%</span>
                    </div>`;

                if (modules.length === 0) {
                    barChart.innerHTML = '<p style="text-align:center; color:#94a3b8; padding:80px 0;">No performance data available yet</p>';
                } else {
                    modules.forEach((m, index) => {
                        const pct = Number(m.progress || m.percentage || 0);
                        const color = pct >= 90 ? '#10b981' : pct >= 80 ? '#3b82f6' : '#f59e0b';

                        let shortName = (m.name || m.title || m.code || '—')
                            .replace(/Structures?/gi, 'Struct')
                            .replace(/Development/gi, 'Dev')
                            .replace(/Systems/gi, 'Sys')
                            .replace(/Engineering/gi, 'Eng')
                            .replace(/Security/gi, 'Sec')
                            .replace(/Mathematics\s*III?/gi, 'Math III');

                        const barHTML = `
                            <div class="bar-item">
                                <div class="bar-wrapper">
                                    <div class="bar"
                                        style="height: ${pct}%; background: ${color};"
                                        title="${m.name || m.title || 'Module'} — ${pct}%">
                                        <div class="bar-value">${Math.round(pct)}%</div>
                                    </div>
                                </div>
                                <div class="bar-label">${shortName}</div>
                            </div>`;

                        barChart.insertAdjacentHTML('beforeend', barHTML);

                        const item = barChart.lastElementChild;
                        item.style.opacity = '0';
                        item.style.animation = `fadeInUp 0.6s ease ${index * 70}ms forwards`;
                    });
                }

            } catch (err) {
                console.error('Dashboard load failed:', err);
                // Optional: show visible error message
                document.getElementById('scheduleList').innerHTML =
                    '<p style="text-align:center; color:#ef4444; padding:40px;">Failed to load dashboard data. Please try again later.</p>';
            }
        }

        loadDashboard();
    </script>

</body>
</html>