<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Records - Smart University System</title>
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
            color: rgba(255,255,255,0.85);
        }

        .sidebar-icon:hover,
        .sidebar-icon.active {
            background: rgba(255,255,255,0.18);
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

        /* Tooltips */
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
        .sidebar:not(.expanded) .sidebar-icon:nth-child(1):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::before  { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::before { opacity: 1; }

        .logout-icon {
            margin-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 0px;
        }

        .logout-icon:hover {
            background: rgba(239,68,68,0.2);
            color: #ef4444;
        }

        .theme-toggle {
            margin-top: auto;
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

        .top-bar {
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .logo-container {
            height: 70px;
            width: 150px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 0;
            margin-left: 0;
        }

        .logo-container img {
            max-height: 250%;
            height: auto;
            width: auto;
            object-fit: contain;
            margin-left: -40px;
        }

        .logo-light { display: block; }
        .logo-dark  { display: none;  }
        body.dark-mode .logo-light { display: none;  }
        body.dark-mode .logo-dark  { display: block; }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }

        body.dark-mode .sidebar              { background: #1e293b; }
        body.dark-mode .sidebar-icon::after  { background: #334155; }
        body.dark-mode .sidebar-icon::before { border-right-color: #334155; }
        body.dark-mode .logout-icon          { border-top-color: rgba(255,255,255,0.1); }

        body.dark-mode .top-bar,
        body.dark-mode .metric-card,
        body.dark-mode .section-card,
        body.dark-mode .improve-card {
            background: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.dark-mode .page-title,
        body.dark-mode .student-name,
        body.dark-mode .module-name-cell,
        body.dark-mode .improve-module  { color: #f1f5f9; }

        body.dark-mode .combined-table thead th       { background: #334155; }
        body.dark-mode .combined-table tbody tr       { border-color: #334155; }
        body.dark-mode .combined-table tbody tr:hover { background: #334155; }
        body.dark-mode .mini-bar,
        body.dark-mode .gpa-bar                       { background: #334155; }
        body.dark-mode .improve-item                  { border-color: #334155; background: #0f172a; }
        body.dark-mode .student-badge                 { background: #1e3a8a; color: #60a5fa; }
        body.dark-mode .grade-group-header            { background: #1e3a5a; color: #93c5fd; }


        .metrics-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 20px 22px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
        }

        .metric-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .gpa-value {
            font-size: 52px;
            font-weight: 800;
            color: var(--blue);
            line-height: 1;
        }
        .gpa-sub { font-size: 13px; color: var(--muted); margin-top: 5px; }
        .gpa-bar {
            margin-top: 14px;
            height: 6px;
            background: var(--border);
            border-radius: 99px;
            overflow: hidden;
        }
         .gpa-bar-fill {
            height: 100%;
            width: 0%;
            background: var(--blue);
            border-radius: 99px;
            transition: width 0.6s ease;
        }

        .student-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 8px;
        }
        .student-avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: var(--blue);
            color: white;
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--blue-light);
        }
        .student-name  { font-size: 20px; font-weight: 700; color: #1e293b; }
        .student-meta  { font-size: 13px; color: var(--muted); }
        .student-badge {
            display: inline-block;
            background: var(--blue-light);
            color: var(--blue);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 12px;
            font-weight: 600;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        .combined-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .combined-table thead th {
            padding: 9px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            text-align: left;
            white-space: nowrap;
        }

        .combined-table thead th:not(:first-child) { text-align: center; }

        .combined-table .grade-group-header th {
            background: #eff6ff;
            color: var(--blue);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-top: 1px solid var(--border);
        }

        .combined-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        .combined-table tbody tr:last-child { border-bottom: none; }
        .combined-table tbody tr:hover      { background: #f8fafc; }

        .combined-table td {
            padding: 10px 12px;
            vertical-align: middle;
        }

        .combined-table td:not(:first-child) { text-align: center; }

        .module-name-cell { font-weight: 600; color: #1e293b; }

        .grade-num {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            min-width: 36px;
            text-align: center;
        }
        .g-a { background: var(--green-light);  color: var(--green);  }
        .g-b { background: var(--blue-light);   color: var(--blue);   }
        .g-c { background: var(--orange-light); color: var(--orange); }
        .g-d { background: var(--red-light);    color: var(--red);    }

        .mini-bar {
            height: 4px;
            background: var(--border);
            border-radius: 99px;
            overflow: hidden;
            margin-top: 5px;
            min-width: 50px;
        }
        .mini-bar-fill { height: 100%; border-radius: 99px; }

        .att-num { font-weight: 600; font-size: 13px; }

        .improve-card {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
        }

        .improve-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .improve-item {
            border: 1px solid var(--border);
            border-top-width: 3px;
            border-radius: 10px;
            padding: 16px 18px;
        }

        .improve-item.high { border-top-color: var(--red);    }
        .improve-item.med  { border-top-color: var(--orange); }
        .improve-item.low  { border-top-color: var(--green);  }

        .improve-priority {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .improve-item.high .improve-priority { color: var(--red);    }
        .improve-item.med  .improve-priority { color: var(--orange); }
        .improve-item.low  .improve-priority { color: var(--green);  }

        .improve-module {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 14px;
        }

        .improve-target { display: flex; align-items: baseline; gap: 6px; }

        .improve-target-val { font-size: 32px; font-weight: 800; }
        .improve-item.high .improve-target-val { color: var(--red);    }
        .improve-item.med  .improve-target-val { color: var(--orange); }
        .improve-item.low  .improve-target-val { color: var(--green);  }

        .improve-target-label { font-size: 12px; color: var(--muted); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .metrics-row  { animation: fadeUp 0.5s ease both; }
        .section-card { animation: fadeUp 0.5s ease 0.12s both; }
        .improve-card { animation: fadeUp 0.5s ease 0.24s both; }

        @media (max-width: 1050px) {
            .metrics-row  { grid-template-columns: 1fr 1fr; }
            .student-card { grid-column: 1 / -1; order: -1; }
            .improve-grid { grid-template-columns: 1fr 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar-icon::after,
            .sidebar-icon::before { display: none; }
            .improve-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 650px) {
            .metrics-row  { grid-template-columns: 1fr; }
            .improve-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-container">

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
                <div class="sidebar-icon" data-page="modules" data-tooltip="My Modules">
                    <img src="{{ asset('images/modules.png') }}" alt="Modules">
                    <span class="sidebar-label">My Modules</span>
                </div>
                <div class="sidebar-icon active" data-page="records" data-tooltip="Records">
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
                <div class="sidebar-icon" data-page="help" data-tooltip="Help">
                     <img src="{{ asset('images/help.png') }}" alt="Help">
                     <span class="sidebar-label">Help</span>
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

        <main class="main-content">

            <div class="top-bar">
                <div class="logo-container">
                    <img src="{{ asset('images/sus_logo.png') }}"      alt="SuS" class="logo-light">
                    <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SuS" class="logo-dark">
                </div>
                <h1 class="page-title">Smart University System</h1>
            </div>

            <div class="metrics-row">
                <div class="metric-card">
                    <div class="metric-label">Cumulative GPA</div>
                    <div class="gpa-value" id="gpaValue">-</div>
                    <div class="gpa-sub">out of 4.0</div>
                    <div class="gpa-bar">
                        <div class="gpa-bar-fill" id="gpaFill" style="width:0%"></div>
                    </div>
                </div>

                <div class="metric-card student-card">
                    <div class="student-avatar" id="recAvatar">-</div>
                    <div class="student-name"   id="recName">Loading...</div>
                    <div class="student-meta"   id="recMeta">-</div>
                    <div class="student-badge"  id="recBadge">-</div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-title">
                    <span class="dot" style="background:#2563eb"></span>
                    Module Grades &amp; Attendance
                </div>
                <table class="combined-table">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Code</th>
                            <th>Credits</th>
                            <th>Grade</th>
                            <th>Grade Points</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody id="gradesBody">
                        <tr class="state-row">
                            <td colspan="6">Loading academic records...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="improve-card">
                <div class="section-title">
                    <span class="dot" style="background:#d97706"></span>
                    Focus Areas for Next Exam
                </div>
                <div class="improve-grid" id="improveGrid"></div>
            </div>

        </main>
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

        document.querySelector('.theme-toggle').addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode',
                document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled'
            );
        });

        document.querySelector('.logout-icon').addEventListener('click', function () {
            if (confirm('Are you sure you want to logout?')) {
                AuthAPI.logout();
            }
        });

        var sidebar     = document.querySelector('.sidebar');
        var toggleBtn   = document.querySelector('.sidebar-toggle-btn');
        var mainContent = document.querySelector('.main-content');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('expanded');
            });
        }

        if (mainContent) {
            mainContent.addEventListener('click', function (e) {
                if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
                    sidebar.classList.remove('expanded');
                }
            });
        }

        document.querySelectorAll('.sidebar-icon[data-page]').forEach(function (icon) {
            icon.addEventListener('click', function () {
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

        function gradeClass(grade) {
            if (!grade) return 'g-b';
            var g = grade.toString().toUpperCase();
            if (g.indexOf('A') === 0) return 'g-a';
            if (g.indexOf('B') === 0) return 'g-b';
            if (g.indexOf('C') === 0) return 'g-c';
            return 'g-d';
        }

        function barColor(points) {
            if (points >= 3.7) return '#16a34a';
            if (points >= 3.0) return '#2563eb';
            if (points >= 2.0) return '#d97706';
            return '#dc2626';
        }

        async function loadRecords() {
            var tbody = document.getElementById('gradesBody');
            try {
                var res  = await RecordsAPI.getTranscript();
                var data = res.data;
                var gpa  = parseFloat(data.gpa) || 0;

                document.getElementById('gpaValue').textContent = gpa.toFixed(1);
                document.getElementById('gpaFill').style.width  = ((gpa / 4.0) * 100).toFixed(0) + '%';

                var initials = (data.fullName || '')
                    .split(' ')
                    .map(function (n) { return n[0] || ''; })
                    .join('')
                    .slice(0, 2)
                    .toUpperCase();
                document.getElementById('recAvatar').textContent = initials || '?';
                document.getElementById('recName').textContent   = data.fullName || '—';
                document.getElementById('recMeta').textContent   = 'ID: ' + data.studentId + '  ·  ' + data.programme;
                document.getElementById('recBadge').textContent  = 'Credits Completed: ' + data.creditsCompleted;

                var attendanceMap = {};
                try {
                    var attRes = await RecordsAPI.getAttendance();
                    (attRes.data || []).forEach(function (a) {
                        attendanceMap[a.moduleName] = a;
                    });
                } catch (e) {
                    console.warn('[Records] Attendance load failed:', e.message);
                }

                var semesters = data.semesters || {};
                var semKeys   = Object.keys(semesters);

                if (semKeys.length === 0) {
                    tbody.innerHTML = '<tr class="state-row"><td colspan="6">No academic records found.</td></tr>';
                    return;
                }

                tbody.innerHTML = '';
                var allModules  = [];

                semKeys.forEach(function (semName) {
                    var modules = semesters[semName];

                    tbody.innerHTML +=
                        '<tr class="grade-group-header">' +
                            '<th colspan="6">' + semName + '</th>' +
                        '</tr>';

                    modules.forEach(function (m) {
                        allModules.push(m);

                        var gc     = gradeClass(m.grade);
                        var color  = barColor(m.gradePoints || 0);
                        var barPct = ((m.gradePoints || 0) / 4.0 * 100).toFixed(0);

                        var att = attendanceMap[m.name] || null;
                        var attHtml;
                        if (att) {
                            var attColor = att.percentage >= 85 ? '#16a34a'
                                         : att.percentage >= 70 ? '#d97706'
                                         : '#dc2626';
                            attHtml =
                                '<span class="att-num" style="color:' + attColor + ';">' +
                                    att.attended + '/' + att.total +
                                '</span>' +
                                '<div class="mini-bar" style="margin-top:4px;">' +
                                    '<div class="mini-bar-fill" style="width:' + att.percentage + '%;background:' + attColor + ';"></div>' +
                                '</div>' +
                                '<div style="font-size:10px;color:#64748b;">' + att.percentage + '%</div>';
                        } else {
                            attHtml = '<span style="color:#94a3b8;">—</span>';
                        }

                        tbody.innerHTML +=
                            '<tr>' +
                                '<td class="module-name-cell">' + m.name + '</td>' +
                                '<td>' + (m.code    || '—') + '</td>' +
                                '<td>' + (m.credits || '—') + '</td>' +
                                '<td><span class="grade-num ' + gc + '">' + (m.grade || '—') + '</span></td>' +
                                '<td>' +
                                    '<span class="grade-num ' + gc + '">' + (m.gradePoints || '—') + '</span>' +
                                    '<div class="mini-bar">' +
                                        '<div class="mini-bar-fill" style="width:' + barPct + '%;background:' + color + ';"></div>' +
                                    '</div>' +
                                '</td>' +
                                '<td>' + attHtml + '</td>' +
                            '</tr>';
                    });
                });

                 var sorted = allModules.slice().sort(function (a, b) {
                    return (a.gradePoints || 0) - (b.gradePoints || 0);
                });

                var priorities = [
                    { cls: 'high', label: '🔴 High Priority',  suffix: 'points to improve' },
                    { cls: 'med',  label: '🟠 Medium Priority', suffix: 'points to reach A' },
                    { cls: 'low',  label: '🟢 Low Priority',    suffix: 'on track'          }
                ];

                var improveGrid = document.getElementById('improveGrid');
                improveGrid.innerHTML = '';

                var focusCount = Math.min(3, sorted.length);
                for (var i = 0; i < focusCount; i++) {
                    var mod = sorted[i];
                    var p   = priorities[i];
                    var gap = ((4.0 - (mod.gradePoints || 0)) * 10).toFixed(0);

                    improveGrid.innerHTML +=
                        '<div class="improve-item ' + p.cls + '">' +
                            '<div class="improve-priority">' + p.label + '</div>' +
                            '<div class="improve-module">' + mod.name + '</div>' +
                            '<div class="improve-target">' +
                                '<span class="improve-target-val">+' + gap + '</span>' +
                                '<span class="improve-target-label">' + p.suffix + '</span>' +
                            '</div>' +
                        '</div>';
                }

                if (focusCount < 3) {
                    improveGrid.innerHTML +=
                        '<div class="improve-item low" style="grid-column:' + (focusCount + 1) + '/-1;">' +
                            '<div class="improve-priority">' +
                                '<img src="{{ asset('images/gpa.png') }}" style="width:14px;height:14px;vertical-align:middle;margin-right:4px;"> Overall Standing' +
                            '</div>' +
                            '<div class="improve-module">Cumulative GPA</div>' +
                            '<div class="improve-target">' +
                                '<span class="improve-target-val">' + gpa.toFixed(1) + '</span>' +
                                '<span class="improve-target-label">out of 4.0</span>' +
                            '</div>' +
                        '</div>';
                }

            } catch (err) {
                tbody.innerHTML = '<tr class="state-row error"><td colspan="6">Could not load records. Please refresh the page.</td></tr>';
                console.error('[Records] Load failed:', err.message);
            }
        }

        loadRecords();
    </script>
</body>
</html>