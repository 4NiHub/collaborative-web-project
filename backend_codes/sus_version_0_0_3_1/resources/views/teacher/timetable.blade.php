<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <title>Timetable – Smart University System</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <style>
        .app-container { display:flex; min-height:100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-collapsed); background:#2563eb;
            display:flex; flex-direction:column; position:fixed;
            height:100vh; left:0; top:0; z-index:1000;
            transition: var(--transition); overflow:hidden; color:white;
        }
        .sidebar.expanded { width: var(--sidebar-expanded); }
        .sidebar-toggle-btn {
            height:60px; display:flex; align-items:center; justify-content:center;
            cursor:pointer; border-bottom:1px solid rgba(255,255,255,0.12); transition:background 0.2s;
        }
        .sidebar-toggle-btn:hover { background:rgba(255,255,255,0.15); }
        .sidebar-toggle-btn img { width:28px; height:28px; transition:transform 0.4s ease; }
        .sidebar.expanded .sidebar-toggle-btn img { transform:rotate(180deg); }
        .sidebar-icons { flex:1; display:flex; flex-direction:column; align-items:center; padding:20px 0; }
        .sidebar-icon {
            width:calc(100% - 30px); height:48px; border-radius:10px;
            display:flex; align-items:center; padding:0 18px; margin:4px 8px;
            cursor:pointer; transition:var(--transition); position:relative; color:rgba(255,255,255,0.85);
        }
        .sidebar-icon:hover, .sidebar-icon.active { background:rgba(255,255,255,0.18); color:white; }
        .sidebar-icon img { width:32px; height:32px; flex-shrink:0; margin-right:0; transition:margin-right 0.3s ease; }
        .sidebar.expanded .sidebar-icon img { margin-right:16px; }
        .sidebar-label {
            opacity:0; visibility:hidden; width:0; overflow:hidden; white-space:nowrap;
            font-size:15px; font-weight:500; transition:opacity 0.22s ease 0.05s, width 0.35s ease, visibility 0.35s;
        }
        .sidebar.expanded .sidebar-label { opacity:1; visibility:visible; width:auto; }
        .theme-toggle, .logout-icon { margin-top:auto; }
        .sidebar:not(.expanded) .sidebar-icon::after {
            content:attr(data-tooltip); position:fixed; left:98px;
            background:#1e293b; color:white; padding:8px 14px; border-radius:6px;
            font-size:13px; font-weight:500; white-space:nowrap;
            opacity:0; pointer-events:none; transition:opacity 0.2s;
            z-index:9999; box-shadow:0 4px 12px rgba(0,0,0,0.3); line-height:1;
        }
        .sidebar:not(.expanded) .sidebar-icon::before {
            content:''; position:fixed; left:87px;
            border:7px solid transparent; border-right-color:#1e293b;
            opacity:0; transition:opacity 0.2s; z-index:9999;
        }
        .sidebar:not(.expanded) .sidebar-icon:hover::after,
        .sidebar:not(.expanded) .sidebar-icon:hover::before { opacity:1; }
        .logout-icon { margin-top:8px; border-top:1px solid rgba(255,255,255,0.1); }
        .logout-icon:hover { background:rgba(239,68,68,0.2); color:#ef4444; }

        /* Main */
        .main-content {
            margin-left:var(--sidebar-collapsed); transition:var(--transition);
            flex:1; padding:20px; max-width:2000px; position:relative; z-index:1;
        }
        .sidebar.expanded + .main-content { margin-left:var(--sidebar-expanded); }

        /* Top bar */
        .top-bar {
            background:white; padding:16px 24px; border-radius:12px;
            margin-bottom:24px; display:flex; justify-content:space-between;
            align-items:center; box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        .logo-container { height:70px; width:150px; overflow:hidden; display:flex; align-items:center; }
        .logo-container img { max-height:250%; height:auto; width:auto; object-fit:contain; margin-left:-40px; }
        .logo-light { display:block; } .logo-dark { display:none; }
        body.dark-mode .logo-light { display:none; } body.dark-mode .logo-dark { display:block; }
        .page-title { font-size:20px; font-weight:600; color:#1e293b; }

        /* Calendar controls */
        .calendar-header { margin-bottom:20px; }
        .calendar-controls { display:flex; justify-content:space-between; align-items:center; }
        .calendar-nav { display:flex; align-items:center; gap:8px; }
        .today-btn {
            padding:8px 16px; background:white; border:1.5px solid #e2e8f0;
            border-radius:8px; font-size:14px; font-weight:500; cursor:pointer; color:#1e293b;
            transition:all 0.2s;
        }
        .today-btn:hover { border-color:#2563eb; color:#2563eb; }
        .nav-btn {
            width:32px; height:32px; background:white; border:1.5px solid #e2e8f0;
            border-radius:8px; cursor:pointer; display:flex; align-items:center;
            justify-content:center; transition:all 0.2s;
        }
        .nav-btn:hover { border-color:#2563eb; }
        .current-month { font-size:18px; font-weight:600; color:#1e293b; margin:0 8px; }
        .current-view-badge {
            padding:4px 12px; background:#eff6ff; color:#2563eb;
            border-radius:20px; font-size:13px; font-weight:500;
        }
        .calendar-actions { display:flex; gap:10px; align-items:center; }

        /* My Timetable button */
        .my-timetable-btn {
            padding:8px 18px; background:white; border:1.5px solid #e2e8f0;
            border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;
            color:#1e293b; transition:all 0.2s;
        }
        .my-timetable-btn:hover, .my-timetable-btn.active {
            background:#2563eb; color:white; border-color:#2563eb;
        }

        /* Dropdown */
        .dropdown-container { position:relative; }
        .dropdown-btn {
            padding:8px 16px; background:white; border:1.5px solid #e2e8f0;
            border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;
            color:#1e293b; display:flex; align-items:center; gap:6px; transition:all 0.2s;
        }
        .dropdown-btn:hover, .dropdown-btn.open { border-color:#2563eb; color:#2563eb; }
        .dropdown-menu {
            display:none; position:absolute; top:calc(100% + 6px); right:0;
            background:white; border:1.5px solid #e2e8f0; border-radius:10px;
            box-shadow:0 8px 24px rgba(0,0,0,0.1); min-width:180px; z-index:100; overflow:hidden;
        }
        .dropdown-menu.show { display:block; }
        .dropdown-item {
            padding:10px 16px; font-size:14px; cursor:pointer; color:#1e293b;
            transition:background 0.15s;
        }
        .dropdown-item:hover { background:#f8fafc; color:#2563eb; }

        /* Calendar grid */
        .calendar-container { background:white; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .calendar-grid {
            display:grid; grid-template-columns:repeat(7,1fr);
            border-left:1px solid #e2e8f0; border-top:1px solid #e2e8f0;
        }
        .calendar-day-header {
            padding:12px; text-align:center; font-size:13px; font-weight:600;
            color:#64748b; background:#f8fafc;
            border-right:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;
        }
        .calendar-day {
            min-height:110px; padding:8px; vertical-align:top;
            border-right:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;
        }
        .calendar-day.other-month .day-number { color:#cbd5e1; }
        .calendar-day.today .day-number {
            background:#2563eb; color:white; width:28px; height:28px;
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-weight:700;
        }
        .day-number { font-size:13px; font-weight:500; color:#1e293b; margin-bottom:6px; }
        .day-events { display:flex; flex-direction:column; gap:3px; }
        .event-item {
            padding:3px 6px; border-radius:4px; cursor:pointer;
            font-size:11px; line-height:1.3; transition:opacity 0.15s;
        }
        .event-item:hover { opacity:0.8; }
        .event-item span { display:block; }
        .event-time     { font-weight:600; }
        .event-title    { font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .event-location { color:rgba(0,0,0,0.5); font-size:10px; }
        .event-lecture  { background:#dbeafe; color:#1e40af; }
        .event-tutorial { background:#fef3c7; color:#92400e; }
        .event-lab      { background:#dcfce7; color:#166534; }
        .event-office   { background:#f3e8ff; color:#6b21a8; }

        /* Event modal */
        .event-modal {
            display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4);
            z-index:2000; align-items:center; justify-content:center; padding:20px;
        }
        .event-modal.active { display:flex; }
        .event-modal .modal-content {
            background:white; border-radius:14px; padding:28px;
            max-width:380px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.2);
        }
        .event-modal .modal-header {
            display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;
        }
        .event-modal .modal-title { font-size:18px; font-weight:700; color:#1e293b; }
        .event-modal .modal-close {
            width:32px; height:32px; border:none; background:#f1f5f9;
            border-radius:8px; cursor:pointer; font-size:16px; color:#64748b;
            display:flex; align-items:center; justify-content:center;
        }
        .event-modal .modal-close:hover { background:#e2e8f0; }
        .event-detail { margin-bottom:14px; }
        .event-detail-label { font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px; }
        .event-detail-value { font-size:14px; font-weight:500; color:#1e293b; }

        /* dark mode */
        body.dark-mode .sidebar { background:#1e293b; }
        body.dark-mode .sidebar-icon::after { background:#334155; }
        body.dark-mode .sidebar-icon::before { border-right-color:#334155; }
        body.dark-mode .logout-icon { border-top-color:rgba(255,255,255,0.05); }
        body.dark-mode .top-bar, body.dark-mode .calendar-container,
        body.dark-mode .today-btn, body.dark-mode .nav-btn, body.dark-mode .my-timetable-btn,
        body.dark-mode .dropdown-btn, body.dark-mode .dropdown-menu,
        body.dark-mode .event-modal .modal-content { background:#1e293b; color:#e2e8f0; border-color:#334155; }
        body.dark-mode .calendar-grid, body.dark-mode .calendar-day,
        body.dark-mode .calendar-day-header { border-color:#334155; }
        body.dark-mode .calendar-day-header { background:#0f172a; }
        body.dark-mode .page-title, body.dark-mode .current-month,
        body.dark-mode .day-number, body.dark-mode .event-detail-value,
        body.dark-mode .event-modal .modal-title { color:#f1f5f9; }
        body.dark-mode .dropdown-item { color:#e2e8f0; }
        body.dark-mode .dropdown-item:hover { background:#334155; color:#60a5fa; }
        body.dark-mode .today-btn:hover, body.dark-mode .nav-btn:hover,
        body.dark-mode .dropdown-btn:hover, body.dark-mode .dropdown-btn.open { border-color:#3b82f6; color:#60a5fa; }
        body.dark-mode .my-timetable-btn:hover, body.dark-mode .my-timetable-btn.active { background:#2563eb; color:white; }
        body.dark-mode .event-modal .modal-close { background:#334155; color:#e2e8f0; }
    </style>
</head>
<body>
<div class="app-container">

    <aside class="sidebar">
        <div class="sidebar-toggle-btn"><img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle"></div>
        <div class="sidebar-icons">
            <div class="sidebar-icon" data-page="dashboard" data-tooltip="Dashboard"><img src="{{ asset('images/home.png') }}" alt=""><span class="sidebar-label">Dashboard</span></div>
            <div class="sidebar-icon active" data-page="timetable" data-tooltip="Timetable"><img src="{{ asset('images/calendar.png') }}" alt=""><span class="sidebar-label">Timetable</span></div>
            <div class="sidebar-icon" data-page="modules" data-tooltip="My Modules"><img src="{{ asset('images/modules.png') }}" alt=""><span class="sidebar-label">My Modules</span></div>
            <div class="sidebar-icon" data-page="profile" data-tooltip="My Profile"><img src="{{ asset('images/person_white.png') }}" alt=""><span class="sidebar-label">My Profile</span></div>
            <div class="sidebar-icon" data-page="news" data-tooltip="News"><img src="{{ asset('images/news.png') }}" alt=""><span class="sidebar-label">News</span></div>
            <div class="sidebar-icon" data-page="career" data-tooltip="Career Centre"><img src="{{ asset('images/career.png') }}" alt=""><span class="sidebar-label">Career Centre</span></div>
            <div class="sidebar-icon" data-page="contact" data-tooltip="Contact"><img src="{{ asset('images/contact.png') }}" alt=""><span class="sidebar-label">Contact</span></div>
            <div class="sidebar-icon" data-page="help" data-tooltip="Help"><img src="{{ asset('images/help.png') }}" alt=""><span class="sidebar-label">Help</span></div>
            <div class="sidebar-icon theme-toggle" data-tooltip="Toggle Theme"><img src="{{ asset('images/dark_mode.png') }}" alt=""><span class="sidebar-label">Dark Mode</span></div>
            <div class="sidebar-icon logout-icon" data-tooltip="Logout"><img src="{{ asset('images/logout.png') }}" alt=""><span class="sidebar-label">Logout</span></div>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="logo-container">
                <img src="{{ asset('images/sus_logo.png') }}" alt="SUS" class="logo-light">
                <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SUS" class="logo-dark">
            </div>
            <h1 class="page-title">Smart University System</h1>
        </div>

        <div class="calendar-header">
            <div class="calendar-controls">
                <div class="calendar-nav">
                    <button class="today-btn" onclick="goToToday()">Today</button>
                    <button class="nav-btn" onclick="previousMonth()">
                        <img src="{{ asset('images/arrow_back.png') }}" style="width:15px;height:10px;">
                    </button>
                    <button class="nav-btn" onclick="nextMonth()">
                        <img src="{{ asset('images/arrow_forward.png') }}" style="width:15px;height:10px;">
                    </button>
                    <h2 class="current-month" id="currentMonth">March 2026</h2>
                    <span class="current-view-badge" id="currentViewBadge">My Timetable</span>
                </div>
                <div class="calendar-actions">
                    <button class="my-timetable-btn active" id="myTimetableBtn" onclick="loadMyTimetable()">My Timetable</button>
                    <div class="dropdown-container">
                        <button class="dropdown-btn" id="groupsBtn" onclick="toggleDropdown('groupsDropdown')">
                            Groups
                            <img src="{{ asset('images/arrow_down.png') }}" style="width:18px;height:18px;">
                        </button>
                        <div class="dropdown-menu" id="groupsDropdown"></div>
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

<!-- Event Modal -->
<div class="event-modal" id="eventModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Event Details</h3>
            <button class="modal-close" onclick="closeModal()">&#x2715;</button>
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
            <div class="event-detail-label">Group</div>
            <div class="event-detail-value" id="modalGroup"></div>
        </div>
        <div class="event-detail">
            <div class="event-detail-label">Type</div>
            <div class="event-detail-value" id="modalType"></div>
        </div>
    </div>
</div>

<script src="{{ asset('js/api.js') }}"></script>
{{-- <script src="{{ asset('js/api.js') }}?v={{ filemtime(public_path('js/api.js')) }}"></script> --}}
<script>
    // authGuard();
    if (localStorage.getItem('darkMode') === 'enabled') document.body.classList.add('dark-mode');
    document.querySelector('.theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    });
    document.querySelector('.logout-icon').addEventListener('click', function() {
        if (confirm('Are you sure you want to logout?')) AuthAPI.logout();
    });
    var sidebar = document.querySelector('.sidebar');
    document.querySelector('.sidebar-toggle-btn').addEventListener('click', function() { sidebar.classList.toggle('expanded'); });
    document.querySelector('.main-content').addEventListener('click', function(e) {
        if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) sidebar.classList.remove('expanded');
    });
    var PAGE_MAP = {
        'dashboard':  '{{ route("dashboard") }}',
        'timetable':  '{{ route("teacher.timetable") }}',
        'modules':    '{{ route("teacher.modules") }}',
        'profile':    '{{ route("teacher.profile") }}',
        'news':       '{{ route("news") }}',
        'career':     '{{ route("career-centre") }}',
        'contact':    '{{ route("contact") }}',
        'help':       '{{ route("teacher.help") }}'
    };

    document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon) {
        icon.addEventListener('click', function() {
            var dest = PAGE_MAP[this.dataset.page];
            if (dest) {
                window.location.href = dest;
            }
        });
    });

    var currentDate   = new Date();
    var currentView   = 'my';
    var currentEntity = null;
    var timetableData = {};
    var groupsList    = [];

    function daysToDateMap(daysObj, monday) {
        var dayNames = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        var map = {};
        dayNames.forEach(function(name, idx) {
            if (daysObj[name] && daysObj[name].length > 0) {
                var d = new Date(monday); d.setDate(monday.getDate() + idx);
                map[d.getDate()] = daysObj[name];
            }
        });
        return map;
    }

    function getMonday() {
        var today = new Date(); var dow = today.getDay();
        var diff = (dow === 0) ? -6 : 1 - dow;
        var monday = new Date(today); monday.setDate(today.getDate() + diff);
        return monday;
    }

    async function loadTimetable() {
        try {
            var res  = await TimetableAPI.getMyWeeklyTimetable();
            var days = res.data.days;
            timetableData['my'] = daysToDateMap(days, getMonday());

            var gr = await TimetableAPI.getGroups();
            groupsList = gr.data || [];
            buildGroupsDropdown();

            updateMonthDisplay();
            renderCalendar();
        } catch(err) {
            console.error('[Timetable]', err.message);
            document.getElementById('calendarGrid').innerHTML =
                '<p style="padding:40px;color:#dc2626;grid-column:1/-1;text-align:center;">Could not load timetable.</p>';
        }
    }

    function buildGroupsDropdown() {
        var menu = document.getElementById('groupsDropdown');
        menu.innerHTML = '';
        groupsList.forEach(function(g) {
            var item = document.createElement('div');
            item.className = 'dropdown-item';
            item.textContent = g.name || g.id;
            item.onclick = function() { loadGroupTimetable(g.id, g.name || g.id); };
            menu.appendChild(item);
        });
    }

    function updateMonthDisplay() {
        var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        document.getElementById('currentMonth').textContent = months[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
    }

    function previousMonth() { currentDate.setMonth(currentDate.getMonth()-1); updateMonthDisplay(); renderCalendar(); }
    function nextMonth()     { currentDate.setMonth(currentDate.getMonth()+1); updateMonthDisplay(); renderCalendar(); }
    function goToToday()     { currentDate = new Date(); updateMonthDisplay(); renderCalendar(); }

    function loadMyTimetable() {
        currentView = 'my'; currentEntity = null;
        document.getElementById('currentViewBadge').textContent = 'My Timetable';
        document.getElementById('myTimetableBtn').classList.add('active');
        document.querySelectorAll('.dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
        document.querySelectorAll('.dropdown-btn').forEach(function(b) { b.classList.remove('open'); });
        renderCalendar();
    }

    async function loadGroupTimetable(groupId, groupName) {
        currentView = 'group'; currentEntity = groupId;
        document.getElementById('currentViewBadge').textContent = groupName;
        document.getElementById('myTimetableBtn').classList.remove('active');
        toggleDropdown('groupsDropdown');
        if (!timetableData['group_' + groupId]) {
            try {
                var res  = await TimetableAPI.getGroupWeeklyTimetable(groupId);
                timetableData['group_' + groupId] = daysToDateMap(res.data.days || {}, getMonday());
            } catch(err) { timetableData['group_' + groupId] = {}; }
        }
        renderCalendar();
    }

    function toggleDropdown(id) {
        var dd = document.getElementById(id);
        var allDd = document.querySelectorAll('.dropdown-menu');
        var allBt = document.querySelectorAll('.dropdown-btn');
        allDd.forEach(function(d) { if(d.id!==id) d.classList.remove('show'); });
        allBt.forEach(function(b) { b.classList.remove('open'); });
        dd.classList.toggle('show');
        if (dd.classList.contains('show')) {
            var btnId = id === 'groupsDropdown' ? 'groupsBtn' : '';
            if (btnId) document.getElementById(btnId).classList.add('open');
        }
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-container')) {
            document.querySelectorAll('.dropdown-menu').forEach(function(d) { d.classList.remove('show'); });
            document.querySelectorAll('.dropdown-btn').forEach(function(b) { b.classList.remove('open'); });
        }
    });

    function renderCalendar() {
        var eventsData = currentView === 'my' ? (timetableData['my'] || {}) : (timetableData['group_' + currentEntity] || {});
        var year = currentDate.getFullYear(); var month = currentDate.getMonth();
        var firstDay = new Date(year, month, 1); var lastDay = new Date(year, month+1, 0);
        var daysInMonth = lastDay.getDate();
        var startDow = firstDay.getDay(); startDow = (startDow===0)?6:startDow-1;
        var prevMonthDays = new Date(year, month, 0).getDate();
        var todayDate = new Date(); var isThisMonth = (todayDate.getFullYear()===year && todayDate.getMonth()===month);

        var html = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'].map(function(d) {
            return '<div class="calendar-day-header">'+d+'</div>';
        }).join('');

        for (var i=startDow-1; i>=0; i--) {
            html += '<div class="calendar-day other-month"><div class="day-number">'+(prevMonthDays-i)+'</div><div class="day-events"></div></div>';
        }
        for (var day=1; day<=daysInMonth; day++) {
            var isToday = isThisMonth && day===todayDate.getDate();
            var events  = eventsData[day] || [];
            var evHtml  = events.map(function(ev) {
                var typeText = ev.type || 'Lecture';
                var tc = 'event-'+typeText.toLowerCase();
                var locationText = ev.location || ev.room || 'TBA';
                var groupText = ev.group || (currentView === 'group' ? document.getElementById('currentViewBadge').textContent : '');
                return '<div class="event-item '+tc+'" onclick=\'showEventDetails('+
                    JSON.stringify(ev.title)+','+JSON.stringify(ev.time)+','+
                    JSON.stringify(locationText)+','+JSON.stringify(groupText)+','+JSON.stringify(typeText)+')\'>' +
                    '<span class="event-time">'+ev.time+'</span>' +
                    '<span class="event-title">'+ev.title+'</span>' +
                    '<span class="event-location">'+locationText+'</span>' +
                    '<span class="event-location">'+typeText+'</span>'+
                    '</div>';
            }).join('');
            var numHtml = isToday
                ? '<div class="day-number"><div style="background:#2563eb;color:white;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;">'+day+'</div></div>'
                : '<div class="day-number">'+day+'</div>';
            html += '<div class="calendar-day'+(isToday?' today':'')+'">'+numHtml+'<div class="day-events">'+evHtml+'</div></div>';
        }
        var total = startDow + daysInMonth; var trailing = (total%7===0)?0:7-(total%7);
        for (var t=1; t<=trailing; t++) {
            html += '<div class="calendar-day other-month"><div class="day-number">'+t+'</div><div class="day-events"></div></div>';
        }
        document.getElementById('calendarGrid').innerHTML = html;
    }

    function showEventDetails(title, time, location, group, type) {
        document.getElementById('modalTitle').textContent    = title;
        document.getElementById('modalTime').textContent     = time || 'N/A';
        document.getElementById('modalLocation').textContent = location || 'N/A';
        document.getElementById('modalGroup').textContent    = group || 'N/A';
        document.getElementById('modalType').textContent     = type  || 'N/A';
        document.getElementById('eventModal').classList.add('active');
    }
    function closeModal() { document.getElementById('eventModal').classList.remove('active'); }
    document.getElementById('eventModal').addEventListener('click', function(e) { if(e.target===this) closeModal(); });

    loadTimetable();
</script>
</body>
</html>
