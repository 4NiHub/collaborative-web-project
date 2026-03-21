<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Modules - Smart University System</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
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

        .share-btn {
            padding: 8px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        /* Page header */
        .page-header {
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #64748b;
            font-size: 14px;
        }

        /* Module grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .module-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s;
        }

        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .module-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .module-icon {
            width: 40px;
            height: 40px;
            background: #2563eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .module-icon svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .module-credits {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .module-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .module-code {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .progress-section {
            margin-bottom: 20px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .progress-text {
            color: #64748b;
        }

        .progress-value {
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            border-radius: 10px;
            transition: width 0.3s;
        }

        .module-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .module-meta {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: #64748b;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .meta-item svg {
            width: 16px;
            height: 16px;
        }

        .semester-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Module detail */
        .modal {
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

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f1f5f9;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 18px;
        }

        .modal-close:hover {
            background: #e2e8f0;
        }

        .modal-body {
            padding: 24px;
        }

        .detail-section {
            margin-bottom: 32px;
        }

        .detail-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .detail-text {
            color: #475569;
            line-height: 1.6;
            font-size: 14px;
        }

        .instructor-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .instructor-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 20px;
        }

        .instructor-info h4 {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .instructor-info p {
            color: #64748b;
            font-size: 13px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-item svg {
            width: 16px;
            height: 16px;
        }

        .assignments-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .assignment-item {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .assignment-info h4 {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .assignment-info p {
            color: #64748b;
            font-size: 13px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-upcoming {
            background: #e0e7ff;
            color: #3730a3;
        }

        .tab-nav {
            display: flex;
            gap: 12px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .tab-btn {
            padding: 12px 0;
            border: none;
            background: transparent;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .schedule-item {
            display: flex;
            gap: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .schedule-week {
            font-weight: 600;
            color: #2563eb;
            min-width: 80px;
        }

        .schedule-content {
            flex: 1;
        }

        .schedule-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .schedule-materials {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .material-link {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            color: #64748b;
            text-decoration: none;
            cursor: pointer;
        }

        .material-link:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        .material-link svg {
            width: 14px;
            height: 14px;
        }

        .resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }

        .resource-card {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .resource-card:hover {
            border-color: #2563eb;
            background: #f8fafc;
        }

        .resource-icon {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: #64748b;
        }

        .resource-icon svg {
            width: 20px;
            height: 20px;
        }

        .resource-title {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .resource-type {
            color: #64748b;
            font-size: 12px;
        }


        body.dark-mode .sidebar {
            background: #1e293b;
        }

        body.dark-mode .top-bar,
        body.dark-mode .module-card,
        body.dark-mode .modal-content {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .module-title,
        body.dark-mode .page-title,
        body.dark-mode h1,
        body.dark-mode h3,
        body.dark-mode h4 {
            color: #f1f5f9;
        }

        body.dark-mode .module-card {
            border: 1px solid #334155;
        }

        body.dark-mode .progress-bar {
            background: #334155;
        }

        body.dark-mode .module-footer {
            border-top-color: #334155;
        }

        @media (max-width: 768px) {
            .modules-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 24px
            }

            .resources-grid {
                grid-template-columns: 1fr;
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
                <div class="sidebar-icon" data-page="dashboard" data-tooltip="Dashboard">
                    <img src="{{ asset('images/home.png') }}" alt="Dashboard">
                    <span class="sidebar-label">Dashboard</span>
                </div>
                <div class="sidebar-icon" data-page="timetable" data-tooltip="Timetable">
                    <img src="{{ asset('images/calendar.png') }}" alt="Timetable">
                    <span class="sidebar-label">Timetable</span>
                </div>
                <div class="sidebar-icon active" data-page="modules" data-tooltip="My Modules">
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

            <!-- Page header -->
            <div class="page-header">
                <h1>My Modules</h1>
                <p>Track your enrolled courses and progress</p>
            </div>

           
            <div class="modules-grid" id="modulesGrid"></div>
                <div class="state-msg" id="loadingMsg">Loading modules...</div>
                
            </div>
        </main>
    </div>

    <!-- Module detail -->
    <div class="modal" id="moduleModal">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 id="modalTitle" style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">Loading... </h2>
                    <p class="modal-header-meta" id="modalMeta">—</p>
                </div>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <div class="modal-body">
                <!-- Tab nav -->
                <div class="tab-nav">
                    <button class="tab-btn active" data-modal-tab="overview">Overview</button>
                    <button class="tab-btn" data-modal-tab="schedule">Schedule</button>
                    <button class="tab-btn" data-modal-tab="resources">Resources</button>
                </div>

                <!-- Overview tab -->
                <div class="tab-content active" id="overview-tab">
                    <div class="detail-section">
                        <h3>Module Description</h3>
                        <p class="detail-text" id="modalDescription">—</p>
                    </div>

                    <div class="detail-section">
                        <h3>Module Leader</h3>
                        <div class="instructor-card">
                            <div class="instructor-avatar" id="instructorAvatar">?</div>
                            <div class="instructor-info">
                                <h4 id="instructorName">-</h4>
                                <p id="instructorDept">—</p>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <img src="{{ asset('images/mail.png') }}" style="width: 20px; height: 20px;display: block; margin-left: 3px;">
                                        <span id="instructorEmail">—</span>
                                    </div>
                                    <div class="contact-item">
                                        <img src="{{ asset('images/pin.png') }}" style="width: 20px; height: 20px;display: block; margin-left: 3px;">
                                        <span id="instructorRoom">—</span>
                                    </div>
                                    <div class="contact-item">
                                        <img src="{{ asset('images/clock.png') }}" style="width: 20px; height: 20px;display: block; margin-left: 3px;">
                                        <span id="instructorHours">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Assignments</h3>
                        <div class="assignments-list" id="assignmentList">
                            <p style="color:#64748b;font-size:14px;">Loading assessments...</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule tab -->
                <div class="tab-content" id="schedule-tab">
                    <div class="detail-section">
                        <h3>Weekly Schedule</h3>
                        <p class="detail-text" style="margin-bottom: 20px;">Lectures, workshops  and materials for the semester</p>
                        
                        <div class="schedule-list" id="scheduleList">
                            <p style="color:#64748b;font-size:14px;">Schedule information will be available here.</p>
                        </div>
                    </div>
                </div>

                <!-- Resources tab -->
                <div class="tab-content" id="resources-tab">
                    <div class="detail-section">
                        <h3>Learning Resources</h3>
                        <div class="resources-grid" id="resourcesGrid">
                            <p style="color:#64748b;font-size:14px;">Loading resources...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/api.js"></script>

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

        toggleBtn.addEventListener('click', function() { sidebar.classList.toggle('expanded'); });
        mainContent.addEventListener('click', function(e) {
            if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
                sidebar.classList.remove('expanded');
            }
        });

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

        document.querySelectorAll('.tab-btn[data-modal-tab]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
                document.getElementById(this.dataset.modalTab + '-tab').classList.add('active');
            });
        });

        document.getElementById('moduleModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        function closeModal() {
            document.getElementById('moduleModal').classList.remove('active');
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
            document.querySelector('.tab-btn[data-modal-tab="overview"]').classList.add('active');
            document.getElementById('overview-tab').classList.add('active');
        }

    

               
        async function loadModules() {
            var grid = document.getElementById('modulesGrid');
            var loadingMsg = document.getElementById('loadingMsg');
            try {
                var res = await ModuleAPI.getEnrolledModules();
                var modules = res.data;

                if (loadingMsg) loadingMsg.style.display = 'none';

                if (!modules || modules.length === 0) {
                    grid.innerHTML = '<div style="color:#64748b;padding:20px;">You are not enrolled in any modules this semester.</div>';
                    return;
                }

                grid.innerHTML = '';
                modules.forEach(function(m) {
                    var card = document.createElement('div');
                    card.className = 'module-card';
                    card.setAttribute('data-id', m.id);
                    card.innerHTML =
                        '<div class="module-header">' +
                            '<div class="module-icon">' +
                                '<img src="{{ asset('images/modules.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">' +
                            '</div>' +
                            '<div class="module-credits">' + m.credits + ' Credits</div>' +
                        '</div>' +
                        '<h3 class="module-title">' + m.name + '</h3>' +
                        '<p class="module-code">' + m.code + ' • ' + m.instructor.name + '</p>' +
                        '<div class="progress-section">' +
                            '<div class="progress-label">' +
                                '<span class="progress-text">Course Progress</span>' +
                                '<span class="progress-value">' + m.progress + '%</span>' +
                            '</div>' +
                            '<div class="progress-bar">' +
                                '<div class="progress-fill" style="width:' + m.progress + '%;"></div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="module-footer">' +
                            '<div class="module-meta">' +
                                '<div class="meta-item">' +
                                    '<img src="{{ asset('images/calendar_today.png') }}" style="width:20px;height:20px;display:block;margin-left:3px;">' +
                                    'Spring 2026' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                    
                    card.addEventListener('click', function() {
                        openModuleModal(this.getAttribute('data-id'));
                    });

                    grid.appendChild(card);
                });

            } catch (err) {
                grid.innerHTML = '<div class="state-msg error">Could not load modules. Please refresh the page.</div>';
                console.error('[Modules] Load failed:', err.message);
            }
        }

        async function openModuleModal(moduleId) {
            document.getElementById('moduleModal').classList.add('active');
            document.getElementById('modalTitle').textContent       = 'Loading...';
            document.getElementById('modalMeta').textContent        = '—';
            document.getElementById('modalDescription').textContent = 'Loading module details...';
            document.getElementById('assignmentList').innerHTML    = '<p style="color:#64748b;font-size:14px;">Loading...</p>';
            document.getElementById('resourcesGrid').innerHTML      = '<p style="color:#64748b;font-size:14px;">Loading...</p>';

            try {
                var res = await ModuleAPI.getModuleDetails(moduleId);
                var m = res.data;
                //header
                document.getElementById('modalTitle').textContent = m.name;
                document.getElementById('modalMeta').textContent  = m.code + ' • ' + m.credits + ' Credits • Spring 2026';
                // description
                document.getElementById('modalDescription').textContent =
                    m.description || 'No description available.';
                // instructor
                var instrName = m.instructor ? m.instructor.name : '—';
                var initials  = instrName.split(' ').filter(function(w) {
                    return w.length > 0 && w[0] === w[0].toUpperCase();
                }).map(function(w) { return w[0]; }).join('').slice(0, 2);

                document.getElementById('instructorAvatar').textContent = initials || '?';
                document.getElementById('instructorName').textContent   = instrName;
                document.getElementById('instructorDept').textContent   = m.instructor && m.instructor.department ? m.instructor.department : 'Lecturer';
                document.getElementById('instructorEmail').textContent  = m.instructor && m.instructor.email ? m.instructor.email : '—';
                document.getElementById('instructorHours').textContent  = m.instructor && m.instructor.officeHours ? m.instructor.officeHours : '—';
                // assessments data fill
                var assignList = document.getElementById('assignmentsList');
                if (m.assessments && m.assessments.length > 0) {
                    assignList.innerHTML = '';
                    m.assessments.forEach(function(a) {
                        var statusClass = 'status-' + (a.status || 'upcoming');
                        assignList.innerHTML +=
                            '<div class="assignment-item">' +
                                '<div class="assignment-info">' +
                                    '<h4>' + a.title + '</h4>' +
                                    '<p>Due: ' + a.dueDate + ' &nbsp;•&nbsp; Weight: ' + a.weight + '%</p>' +
                                '</div>' +
                                '<div class="status-badge ' + statusClass + '">' + (a.status || 'upcoming') + '</div>' +
                            '</div>';
                    });
                } else {
                    assignList.innerHTML = '<p style="color:#64748b;font-size:14px;">No assessments listed yet.</p>';
                }
                //schedule tab data 
                var scheduleList = document.getElementById('scheduleList');
                if (m.learningOutcomes && m.learningOutcomes.length > 0) {
                    scheduleList.innerHTML = '';
                    m.learningOutcomes.forEach(function(outcome, idx) {
                        scheduleList.innerHTML +=
                            '<div class="schedule-item">' +
                                '<div class="schedule-week">Week ' + (idx + 1) + ':</div>' +
                                '<div class="schedule-content">' +
                                    '<div class="schedule-title">' + outcome + '</div>' +
                                    '<div class="schedule-materials">' +
                                        '<a class="material-link">' +
                                            '<img src="{{ asset('images/slides.png') }}" style="width:18px;height:18px;display:block;margin-left:3px;"> Lecture Slides' +
                                        '</a>' +
                                        '<a class="material-link">' +
                                            '<img src="{{ asset('images/doc.png') }}" style="width:18px;height:18px;display:block;margin-left:3px;"> Workshop Brief' +
                                        '</a>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    });
                } else {
                    scheduleList.innerHTML = '<p style="color:#64748b;font-size:14px;">Schedule not available yet.</p>';
                }

                //resources data
                var resourcesGrid = document.getElementById('resourcesGrid');
                if (m.resources && m.resources.length > 0) {
                    resourcesGrid.innerHTML = '';
                    m.resources.forEach(function(r) {
                        resourcesGrid.innerHTML +=
                            '<div class="resource-card">' +
                                '<div class="resource-icon">' +
                                    '<img src="{{ asset('images/doc.png') }}" style="width:18px;height:18px;display:block;margin-left:3px;">' +
                                '</div>' +
                                '<div class="resource-title">' + r.title + '</div>' +
                                '<div class="resource-type">' + (r.type || 'Document').toUpperCase() + '</div>' +
                            '</div>';
                    });
                } else {
                    resourcesGrid.innerHTML = '<p style="color:#64748b;font-size:14px;">No resources uploaded yet.</p>';
                }

            } catch (err) {
                document.getElementById('modalTitle').textContent       = 'Could not load module';
                document.getElementById('modalDescription').textContent = 'Please close and try again.';
                console.error('[Module Detail] Load failed:', err.message);
            }
        }

        loadModules();
    </script>
</body>
</html>