<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Career Center - Smart University System</title>
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

        /* tooltips*/
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

        /* top bar */
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

        /* page header */
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

        /* two column layout */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        /* job listings */
        .jobs-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .section-header svg {
            width: 24px;
            height: 24px;
        }

        .section-header h2 {
            font-size: 20px;
            font-weight: 700;
        }

        .job-card {
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .job-card:hover {
            border-color: #2563eb;
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
        }

        .job-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .job-company {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: 14px;
        }

        .job-company svg {
            width: 16px;
            height: 16px;
        }

        .job-type {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .type-internship {
            background: #dcfce7;
            color: #166534;
        }

        .type-fulltime {
            background: #dbeafe;
            color: #1e40af;
        }

        .job-description {
            color: #475569;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .job-footer {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: #64748b;
            margin: 3px 0 3px 0;
        }

        .deadline-badge{
            margin-top: 10px;
        }

        .job-meta {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .job-meta svg {
            width: 16px;
            height: 16px;
        }

        /* events section */
        .events-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .event-card {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .event-card:hover {
            border-color: #2563eb;
            background: #f8fafc;
        }

        .event-date {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 8px;
        }

        .event-title {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .event-location {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: 13px;
            margin: 3px 0 3px 0;
        }

        .event-location svg {
            width: 16px;
            height: 16px;
        }

        .event-description {
            color: #64748b;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .event-time{
            display: flex;
            align-items: center;
            margin: 3px 0 3px 0;
        }

        .event-spots{
            margin: 3px 0 15px 0;
        }

        .register-btn {
            width: 100%;
            padding: 10px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .register-btn:hover {
            background: #1d4ed8;
        }

        /* job detail*/
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
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
            max-width: 700px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2001;
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
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
            flex-shrink: 0;
        }

        .modal-close:hover {
            background: #e2e8f0;
        }

        .modal-body {
            padding: 24px;
        }

        .detail-section {
            margin-bottom: 24px;
        }

        .detail-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .detail-text {
            color: #475569;
            line-height: 1.6;
            font-size: 14px;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 8px;
            color: #475569;
            font-size: 14px;
        }

        .requirements-list li svg {
            width: 20px;
            height: 20px;
            color: #10b981;
            flex-shrink: 0;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .skill-tag {
            padding: 6px 12px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #475569;
        }

        .apply-button {
            width: 100%;
            padding: 14px;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .apply-button:hover {
            background: #1e293b;
        }

        .deadline-notice {
            background: #fef3c7;
            color: #92400e;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        body.dark-mode .top-bar,
        body.dark-mode .jobs-section,
        body.dark-mode .events-section,
        body.dark-mode .modal-content {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .job-card,
        body.dark-mode .event-card {
            border-color: #334155;
            background: #1e293b;
        }

        body.dark-mode .job-card:hover,
        body.dark-mode .event-card:hover {
            background: #334155;
        }

        body.dark-mode .job-title,
        body.dark-mode .event-title,
        body.dark-mode .page-title,
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3 {
            color: #f1f5f9;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Disabled button for Staff */
        .register-btn.staff-view {
            background: #64748b;
            cursor: help; /* Shows a question mark or help cursor */
            opacity: 0.8;
        }

        .register-btn.staff-view:hover {
            background: #475569;
        }

        /* Custom Success/Info Popup */
        .custom-popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #10b981; /* Emerald Green */
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateY(100px);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 9999;
        }

        .custom-popup.active {
            transform: translateY(0);
        }

        .custom-popup-icon {
            width: 24px;
            height: 24px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Dark Mode adjustment for Popup */
        body.dark-mode .custom-popup {
            background: #059669; /* Slightly deeper green for dark mode */
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- sidebar -->

        @auth
            @if (auth()->user()->role_id === 2)
                @include('partials.sidebar-teacher')
            @else
                @include('partials.sidebar-student')
            @endif
        @else
            <!-- Guest / not logged in – optional redirect or minimal sidebar -->
            <p style="text-align:center; padding:40px; color:#64748b;">
                Please <a href="{{ route('login') }}">log in</a> to view this page.
            </p>
        @endauth

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
                <h1>Career Center</h1>
                <p>Explore job opportunities and career events</p>
            </div>

            
            <div class="content-grid">
                <div class="jobs-section">
                    <div class="section-header">
                        <img src="{{ asset('images/career_black.png') }}" style="width: 25px; height: 25px; display: block; margin-left: 1px;">
                        <h2>Job Listings</h2>
                    </div>
                    <div id="jobsList">
                        <div class="state-msg">Loading jobs...</div>
                    </div>
                </div>

                <!-- events_section -->
                <div class="events-section">
                    <div class="section-header">
                        <img src="{{ asset('images/calendar_today.png') }}" style="width: 20px; height: 20px; display: block; margin-left: 1px;">
                        <h2>Upcoming Events</h2>
                    </div>
                    <div id="eventsList">
                        <div class="state-msg">Loading events...</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- job_detail -->
    <div class="modal" id="jobModal">
        <div class="modal-content">
            <div class="modal-header">
                <div style="flex:1;">
                    <div class="job-type" id="modalJobType" style="display:inline-block;margin-bottom:12px;">—</div>
                    <h2 style="font-size:22px;font-weight:700;margin-bottom:8px;" id="modalJobTitle">Loading...</h2>
                    <div class="job-company">
                        <img src="{{ asset('images/corporate.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">
                        <span id="modalJobCompany">—</span>
                    </div>
                </div>
                <button class="modal-close" onclick="closeJobModal()">✕</button>
            </div>
            <div class="modal-body">
                <div class="deadline-notice" id="modalDeadline">
                    <img src="{{ asset('images/warning.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">
                    <span id="modalDeadlineText">—</span>
                </div>

                <div class="detail-section">
                    <h3>About the Role</h3>
                    <p class="detail-text" id="modalDescription">—</p>
                </div>
                <div class="detail-section">
                    <h3>Requirements</h3>
                    <ul class="requirements-list" id="modalRequirements"></ul>
                </div>

                <div class="detail-section">
                    <h3>Job Details</h3>
                    <div class="job-footer" style="flex-direction:column;gap:10px;">
                        <div class="job-meta">
                            <img src="{{ asset('images/pin.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">
                            <span id="modalLocation">—</span>
                        </div>
                        <div class="job-meta">
                            <img src="{{ asset('images/circle.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">
                            <span id="modalSalary">—</span>
                        </div>
                    </div>
                </div>

                <button class="apply-button" id="applyBtn" >Apply Now</button>
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
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('expanded');
            });
        }

        if (mainContent) {
            mainContent.addEventListener('click', (e) => {
                if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('expanded');
                }
            });
        }

        // Page nav
        const isTeacher = {{ auth()->check() && auth()->user()->role_id === 2 ? 'true' : 'false' }};

        const PAGE_MAP = {
            'dashboard': '{{ route('dashboard') }}',
            'timetable': isTeacher ? '{{ route('teacher.timetable') }}' : '{{ route('timetable') }}',
            'modules':   isTeacher ? '{{ route('teacher.modules') }}' : '{{ route('modules') }}',
            'profile':   isTeacher ? '{{ route('teacher.profile') }}' : '/profile', // adjust if student has profile route
            'news':      '{{ route('news') }}',
            'career':    '{{ route('career-centre') }}',
            'contact':   '{{ route('contact') }}',
            'help':      isTeacher ? '{{ route('teacher.help') }}' : '{{ route('help') }}',
            'records':   '{{ route('records') ?? '/records' }}', // only for students
            'teachers':  '{{ route('teachers') ?? '/teachers' }}', // only for students
        };

        document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon) {
            icon.addEventListener('click', function() {
                const page = this.dataset.page;
                const dest = PAGE_MAP[page];
                if (dest) {
                    window.location.href = dest;
                }
            });
        });

        //modal close
        document.getElementById('jobModal').addEventListener('click', function(e) {
            if (e.target === this) closeJobModal();
        });

        //state
        var currentJobId = null;
        function typeClass(type) {
            if (!type) return 'type-internship';
            var t = type.toLowerCase();
            if (t.indexOf('intern') !== -1) return 'type-internship';
            if (t.indexOf('full')   !== -1) return 'type-fulltime';
            return 'type-parttime';
        }

        function formatDeadline(str) {
            if (!str) return 'Rolling basis';
            var d = new Date(str);
            if (isNaN(d)) return str;
            return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }

        //load jobs
        async function loadJobs() {
            var container = document.getElementById('jobsList');
            try {
                var res  = await CareerAPI.getJobs();
                var jobs = res.data || [];

                if (jobs.length === 0) {
                    container.innerHTML = '<div class="state-msg">No job listings available at the moment.</div>';
                    return;
                }

                container.innerHTML = '';
                jobs.forEach(function(j) {
                    var tc = typeClass(j.type);
                    var card = document.createElement('div');
                    card.className = 'job-card';
                    card.setAttribute('data-id', j.id);
                    card.innerHTML =
                        '<div class="job-header">' +
                            '<div>' +
                                '<h3 class="job-title">' + j.title + '</h3>' +
                                '<div class="job-company">' +
                                    '<img src="{{ asset('images/corporate.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">' +
                                    j.company +
                                '</div>' +
                            '</div>' +
                            '<div class="job-type ' + tc + '">' + (j.type || 'Position') + '</div>' +
                        '</div>' +
                        '<p class="job-description">' + (j.description || '') + '</p>' +
                        '<div class="job-footer">' +
                            '<div class="job-meta">' +
                                '<img src="{{ asset('images/pin.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">' +
                                (j.location || '—') +
                            '</div>' +
                            '<div class="job-meta">' +
                                '<img src="{{ asset('images/circle.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">' +
                                (j.salary || '—') +
                            '</div>' +
                        '</div>' +
                        '<div class="deadline-badge">Deadline: ' + formatDeadline(j.deadline) + '</div>';

                    card.addEventListener('click', function() {
                        openJobModal(parseInt(this.getAttribute('data-id')));
                    });
                    container.appendChild(card);
                });

            } catch (err) {
                container.innerHTML = '<div class="state-msg error">Could not load jobs. Please refresh.</div>';
                console.error('[Career] Jobs load failed:', err.message);
            }
        }

        // load events
        async function loadEvents() {
            var container = document.getElementById('eventsList');
            try {
                var res = await CareerAPI.getEvents();
                var events = res.data || [];

                if (events.length === 0) {
                    container.innerHTML = '<div class="state-msg">No upcoming events.</div>';
                    return;
                }

                container.innerHTML = '';
                
                events.forEach(function(ev) {
                    var card = document.createElement('div');
                    card.className = 'event-card';
                    
                    // Logic to determine button HTML based on user role
                    let btnHtml = '';
                    if (isTeacher) {
                        // Amazing green pop style trigger for Staff
                        btnHtml = `<button class="register-btn staff-view" onclick="showStaffNotice()">Staff View Only</button>`;
                    } else {
                        btnHtml = ev.registered
                            ? `<button class="register-btn registered" id="evtBtn_${ev.id}">Registered ✓</button>
                            <button class="cancel-reg-btn" onclick="cancelEventRegistration(${ev.id})" style="margin-top:6px;width:100%;padding:6px;background:none;border:1px solid #dc2626;color:#dc2626;border-radius:6px;font-size:12px;cursor:pointer;">Cancel Registration</button>`
                            : `<button class="register-btn" onclick="registerForEvent(${ev.id}, this)">Register Now</button>`;
                    }

                    card.innerHTML = `
                        <div class="event-date">${ev.date}</div>
                        <h3 class="event-title">${ev.title}</h3>
                        <div class="event-time">
                            <img src="/images/clock.png" style="width:20px;height:20px;display:block;margin:0 5px 0 0;">
                            ${ev.time || '—'}
                        </div>
                        <div class="event-location">
                            <img src="/images/pin.png" style="width:20px;height:20px;display:block;margin:0 5px 0 0;">
                            ${ev.location || '—'}
                        </div>
                        <div class="event-spots">Spots available: <span>${ev.spots || '—'}</span></div>
                        ${btnHtml}
                    `;
                    container.appendChild(card);
                });

            } catch (err) {
                container.innerHTML = '<div class="state-msg error">Could not load events. Please refresh.</div>';
                console.error('[Career] Events load failed:', err.message);
            }
        }

                // events.forEach(function(ev) {
                //     var card = document.createElement('div');
                //     card.className = 'event-card';
                //     card.setAttribute('data-id', ev.id);
                //     card.setAttribute('data-registered', ev.registered ? '1' : '0');

                //     card.innerHTML =
                //         '<div class="event-date">' + ev.date + '</div>' +
                //         '<h3 class="event-title">' + ev.title + '</h3>' +
                //         '<div class="event-time">' +
                //             '<img src="{{ asset('images/clock.png') }}" style="width:20px;height:20px;display:block;margin:0 5px 0 0;">' + 
                //             (ev.time || '—') + '</div>' +
                //         '<div class="event-location">' +
                //             '<img src="{{ asset('images/pin.png') }}" style="width:20px;height:20px;display:block;margin:0 5px 0 0;">' +
                //             (ev.location || '—') +
                //         '</div>' +
                //         '<div class="event-spots">Spots available: <span>' + (ev.spots || '—') + '</span></div>' +
                //         (ev.registered
                //             ? '<button class="register-btn registered" id="evtBtn_' + ev.id + '">Registered ✓</button>' +
                //             '<button class="cancel-reg-btn" id="evtCancel_' + ev.id + '" style="margin-top:6px;width:100%;padding:6px;background:none;border:1px solid #dc2626;color:#dc2626;border-radius:6px;font-size:12px;cursor:pointer;">Cancel Registration</button>'
                //             : '<button class="register-btn" id="evtBtn_' + ev.id + '">Register Now</button>'
                //         );

                    // var btn = card.querySelector('#evtBtn_' + ev.id);

                    // // FIXED: removed the non-existent "Btn" parameter
                    // if (!ev.registered) {
                    //     btn.addEventListener('click', function() {
                    //         registerForEvent(ev.id, btn);
                    //     });
                    // }

                    // var cancelBtn = card.querySelector('#evtCancel_' + ev.id);
                    // if (cancelBtn) {
                    //     cancelBtn.addEventListener('click', function() {
                    //         cancelEventRegistration(ev.id, btn, cancelBtn);
                    //     });
                    // }

                    var btn = card.querySelector('#evtBtn_' + ev.id);

                    if (!ev.registered) {
                        // Not registered
                        btn.addEventListener('click', function() {
                            registerForEvent(ev.id, btn);
                        });
                    } else {
                        // Already registered → only cancel button works
                        var cancelBtn = card.querySelector('#evtCancel_' + ev.id);
                        if (cancelBtn) {
                            cancelBtn.addEventListener('click', function() {
                                cancelEventRegistration(ev.id);
                            });
                        }
                    }

                    container.appendChild(card);
                });

            } catch (err) {
                container.innerHTML = '<div class="state-msg error">Could not load events. Please refresh.</div>';
                console.error('[Career] Events load failed:', err.message);
            }
        }
        // //register event
        // async function registerForEvent(eventId, btn) {
        //     btn.disabled = true;
        //     btn.textContent = 'Registering…';
        //     try {
        //         await CareerAPI.registerForEvent(eventId);
        //         btn.textContent = 'Registered ✓';
        //         btn.className = 'register-btn registered';
        //     } catch (err) {
        //         btn.disabled = false;
        //         btn.textContent = 'Register Now';
        //         alert('Could not register: ' + err.message);
        //     }
        // }
        // async function cancelEventRegistration(eventId, regBtn, cancelBtn) {
        //     cancelBtn.disabled = true;
        //     cancelBtn.textContent = 'Cancelling…';
        //     try {
        //         await CareerAPI.cancelEventRegistration(eventId);
        //         regBtn.textContent = 'Register Now';
        //         regBtn.className = 'register-btn';
        //         regBtn.disabled = false;
        //         regBtn.addEventListener('click', function() {
        //             registerForEvent(eventId, regBtn);
        //         });
        //         cancelBtn.remove();
        //     } catch (err) {
        //         cancelBtn.disabled = false;
        //         cancelBtn.textContent = 'Cancel Registration';
        //         alert('Could not cancel registration: ' + err.message);
        //     }
        // }

        // REGISTER – clean & reliable
        async function registerForEvent(eventId, btn) {
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Registering…';

            try {
                await CareerAPI.registerForEvent(eventId);
                loadEvents();           // ← full refresh = correct UI appears
            } catch (err) {
                btn.disabled = false;
                btn.textContent = originalText;
                alert('Could not register: ' + (err.message || 'Unknown error'));
            }
        }

        // CANCEL – simplified (only needs eventId)
        async function cancelEventRegistration(eventId) {
            if (!confirm('Are you sure you want to cancel your registration?')) return;

            try {
                await CareerAPI.cancelEventRegistration(eventId);
                loadEvents();           // ← full refresh
            } catch (err) {
                alert('Could not cancel registration: ' + (err.message || 'Unknown error'));
            }
        }

        async function openJobModal(jobId) {
            currentJobId = jobId;
            const modal = document.getElementById('jobModal');
            modal.classList.add('active');

            // Reset all fields — use safe defaults (NO j here!)
            document.getElementById('modalJobType').textContent = '—';
            document.getElementById('modalJobTitle').textContent = 'Loading...';
            document.getElementById('modalJobCompany').textContent = '—';
            document.getElementById('modalDescription').textContent = 'Loading description...';
            document.getElementById('modalLocation').textContent = '—';
            document.getElementById('modalSalary').textContent = '—';
            document.getElementById('modalDeadlineText').textContent = 'Application deadline: —';
            document.getElementById('modalRequirements').innerHTML = '';

            const applyBtn = document.getElementById('applyBtn');
            applyBtn.textContent = 'Apply Now';
            applyBtn.disabled = false;
            applyBtn.style.background = ''; // reset color if previously green

            try {
                const res = await CareerAPI.getJobDetails(jobId);
                const j = res.data || {};  // safe empty object fallback

                // Now safely fill with real data
                document.getElementById('modalJobType').textContent = j.type || '—';
                document.getElementById('modalJobTitle').textContent = j.title || '—';
                document.getElementById('modalJobCompany').textContent = j.company || '—';
                document.getElementById('modalDescription').innerHTML = 
                    (j.description || 'No description available.').replace(/\n/g, '<br>');
                document.getElementById('modalLocation').textContent = j.location || '—';
                document.getElementById('modalSalary').textContent = j.salary || '—';
                document.getElementById('modalDeadlineText').textContent = 
                    'Application deadline: ' + (j.deadline || 'N/A');

                // Requirements list
                const reqList = document.getElementById('modalRequirements');
                reqList.innerHTML = '';
                const reqs = j.requirements || [];
                if (reqs.length === 0) {
                    reqList.innerHTML = '<li>No specific requirements listed</li>';
                } else {
                    reqs.forEach(r => {
                        const li = document.createElement('li');
                        li.innerHTML = `<img src="{{ asset('images/check.png') }}" style="width:20px;height:20px;"> ${r}`;
                        reqList.appendChild(li);
                    });
                }

                // Apply button state
                if (j.alreadyApplied) {
                    applyBtn.textContent = '✓ Already Applied';
                    applyBtn.style.background = '#16a34a';
                    applyBtn.disabled = true;
                } else {
                    applyBtn.onclick = applyForJob;
                }

            } catch (err) {
                console.error('Failed to load job details:', err);
                document.getElementById('modalJobTitle').textContent = 'Could not load details';
                document.getElementById('modalDescription').textContent = 'Please try again later.';
            }
        }

        async function applyForJob() {
            if (!currentJobId) return;
            var btn = document.getElementById('applyBtn');
            btn.disabled = true;
            btn.textContent = 'Submitting...';

            try {
                await CareerAPI.applyForJob(currentJobId);
                
                // SUCCESS: change style permanently
                btn.textContent = '✓ Application Submitted';
                btn.style.background = '#16a34a';
                btn.style.cursor = 'default';
                
                // Optional: show toast
                alert('Application submitted successfully!');
            } catch (err) {
                btn.disabled = false;
                btn.textContent = 'Apply Now';
                alert('Could not submit: ' + (err.message || 'Unknown error'));
            }
        }

        function closeJobModal() {
            document.getElementById('jobModal').classList.remove('active');
            currentJobId = null;
        }

        // Function to show the amazing green popup
        function showStaffNotice() {
            // Create popup element if it doesn't exist
            let popup = document.getElementById('staffPopup');
            if (!popup) {
                popup = document.createElement('div');
                popup.id = 'staffPopup';
                popup.className = 'custom-popup';
                popup.innerHTML = `
                    <div class="custom-popup-icon">!</div>
                    <div>
                        <strong style="display:block;">Staff Access</strong>
                        <span style="font-size:13px; opacity:0.9;">You have full viewing rights for this event. No registration required!</span>
                    </div>
                `;
                document.body.appendChild(popup);
            }

            // Trigger animation
            popup.classList.add('active');

            // Auto-hide after 3 seconds
            setTimeout(() => {
                popup.classList.remove('active');
            }, 3500);
        }

        loadJobs();
        loadEvents();
    </script>
</body>
</html>