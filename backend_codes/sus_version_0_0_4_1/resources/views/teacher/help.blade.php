<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Help – Smart University System</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <style>
        .app-container { display:flex; min-height:100vh; }

        /* Sidebar */
        .sidebar {
            width:var(--sidebar-collapsed); background:#2563eb;
            display:flex; flex-direction:column; position:fixed;
            height:100vh; left:0; top:0; z-index:1000;
            transition:var(--transition); overflow:hidden; color:white;
        }
        .sidebar.expanded { width:var(--sidebar-expanded); }
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

        /* Help hero */
        .help-hero {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius:12px; padding:40px; margin-bottom:28px; text-align:center; color:white;
        }
        .help-hero h1 { font-size:26px; font-weight:700; margin-bottom:8px; }
        .help-hero p  { font-size:15px; opacity:0.85; margin-bottom:24px; }
        .help-search {
            display:flex; max-width:460px; margin:0 auto; gap:10px;
        }
        .help-search input {
            flex:1; padding:12px 16px; border-radius:8px; border:none;
            font-size:14px; outline:none;
        }
        .help-search button {
            padding:12px 20px; background:rgba(255,255,255,0.2); border:none;
            border-radius:8px; color:white; font-size:14px; font-weight:600; cursor:pointer;
            transition:background 0.2s;
        }
        .help-search button:hover { background:rgba(255,255,255,0.3); }

        /* Category cards */
        .categories-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
        .category-card {
            background:white; border-radius:12px; padding:24px;
            box-shadow:0 1px 3px rgba(0,0,0,0.05); cursor:pointer; transition:all 0.2s;
            text-align:center;
        }
        .category-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,0.08); }
        .category-icon {
            width:52px; height:52px; border-radius:14px; margin:0 auto 14px;
            display:flex; align-items:center; justify-content:center;
        }
        .category-icon img { width:28px; height:28px; }
        .category-icon.blue   { background:#dbeafe; }
        .category-icon.green  { background:#dcfce7; }
        .category-icon.purple { background:#ede9fe; }
        .category-icon.orange { background:#ffedd5; }
        .category-icon.pink   { background:#fce7f3; }
        .category-icon.teal   { background:#ccfbf1; }
        .category-title { font-size:14px; font-weight:700; color:#1e293b; margin-bottom:4px; }
        .category-desc  { font-size:12px; color:#64748b; line-height:1.4; }

        /* FAQ section */
        .faq-section { background:white; border-radius:12px; padding:28px; box-shadow:0 1px 3px rgba(0,0,0,0.05); margin-bottom:24px; }
        .faq-section h2 { font-size:17px; font-weight:700; margin-bottom:20px; color:#1e293b; }
        .faq-item { border-bottom:1px solid #f1f5f9; }
        .faq-item:last-child { border-bottom:none; }
        .faq-question {
            display:flex; justify-content:space-between; align-items:center;
            padding:16px 0; cursor:pointer; font-size:14px; font-weight:600; color:#1e293b;
            transition:color 0.2s;
        }
        .faq-question:hover { color:#2563eb; }
        .faq-toggle { font-size:18px; color:#64748b; transition:transform 0.2s; font-weight:400; }
        .faq-item.open .faq-toggle { transform:rotate(45deg); color:#2563eb; }
        .faq-answer {
            display:none; padding:0 0 16px; font-size:14px; color:#475569; line-height:1.6;
        }
        .faq-item.open .faq-answer { display:block; }

        /* Contact support */
        .support-card {
            background:white; border-radius:12px; padding:28px;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
            display:grid; grid-template-columns:1fr 1fr; gap:16px;
        }
        .support-option {
            border:1.5px solid #e2e8f0; border-radius:10px; padding:20px;
            text-align:center; cursor:pointer; transition:all 0.2s;
        }
        .support-option:hover { border-color:#2563eb; background:#f8faff; }
        .support-icon { font-size:28px; margin-bottom:10px; }
        .support-title { font-size:14px; font-weight:700; color:#1e293b; margin-bottom:4px; }
        .support-desc  { font-size:12px; color:#64748b; }

        /* dark mode */
        body.dark-mode .sidebar { background:#1e293b; }
        body.dark-mode .sidebar-icon::after { background:#334155; }
        body.dark-mode .sidebar-icon::before { border-right-color:#334155; }
        body.dark-mode .logout-icon { border-top-color:rgba(255,255,255,0.05); }
        body.dark-mode .top-bar, body.dark-mode .category-card, body.dark-mode .faq-section,
        body.dark-mode .support-card, body.dark-mode .support-option { background:#1e293b; border-color:#334155; }
        body.dark-mode .page-title, body.dark-mode .faq-section h2,
        body.dark-mode .category-title, body.dark-mode .faq-question,
        body.dark-mode .support-title { color:#f1f5f9; }
        body.dark-mode .faq-item { border-bottom-color:#334155; }
        body.dark-mode .faq-answer, body.dark-mode .category-desc,
        body.dark-mode .support-desc { color:#94a3b8; }
        body.dark-mode .support-option:hover { border-color:#3b82f6; background:#1e3a5f; }

        @media (max-width:768px) {
            .categories-grid { grid-template-columns:repeat(2,1fr); }
            .support-card { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <aside class="sidebar">
        <div class="sidebar-toggle-btn"><img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle"></div>
        <div class="sidebar-icons">
            <div class="sidebar-icon" data-page="dashboard" data-tooltip="Dashboard"><img src="{{ asset('images/home.png') }}" alt=""><span class="sidebar-label">Dashboard</span></div>
            <div class="sidebar-icon" data-page="timetable" data-tooltip="Timetable"><img src="{{ asset('images/calendar.png') }}" alt=""><span class="sidebar-label">Timetable</span></div>
            <div class="sidebar-icon" data-page="modules" data-tooltip="My Modules"><img src="{{ asset('images/modules.png') }}" alt=""><span class="sidebar-label">My Modules</span></div>
            <div class="sidebar-icon" data-page="profile" data-tooltip="My Profile"><img src="{{ asset('images/person_white.png') }}" alt=""><span class="sidebar-label">My Profile</span></div>
            <div class="sidebar-icon" data-page="news" data-tooltip="News"><img src="{{ asset('images/news.png') }}" alt=""><span class="sidebar-label">News</span></div>
            <div class="sidebar-icon" data-page="career" data-tooltip="Career Centre"><img src="{{ asset('images/career.png') }}" alt=""><span class="sidebar-label">Career Centre</span></div>
            <div class="sidebar-icon" data-page="contact" data-tooltip="Contact"><img src="{{ asset('images/contact.png') }}" alt=""><span class="sidebar-label">Contact</span></div>
            <div class="sidebar-icon active" data-page="help" data-tooltip="Help"><img src="{{ asset('images/help.png') }}" alt=""><span class="sidebar-label">Help</span></div>
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

        <!-- Hero -->
        <div class="help-hero">
            <h1>How can we help you?</h1>
            <p>Search our knowledge base or browse topics below</p>
            <div class="help-search">
                <input type="text" placeholder="Search for answers..." id="helpSearchInput">
                <button onclick="alert('Search feature is still under construction 🚧')">Search</button>
            </div>
        </div>

        <!-- Categories -->
        <div class="categories-grid">
            <div class="category-card">
                <div class="category-icon blue"><img src="{{ asset('images/modules_black.png') }}" alt=""></div>
                <div class="category-title">Managing Modules</div>
                <div class="category-desc">How to view and manage your assigned courses and student groups</div>
            </div>
            <div class="category-card">
                <div class="category-icon green"><img src="{{ asset('images/records_black.png') }}" alt=""></div>
                <div class="category-title">Grades & Submissions</div>
                <div class="category-desc">Reviewing student work, entering grades and downloading reports</div>
            </div>
            <div class="category-card">
                <div class="category-icon purple"><img src="{{ asset('images/calendar_black.png') }}" alt=""></div>
                <div class="category-title">Timetable</div>
                <div class="category-desc">Understanding your timetable, group schedules and office hours</div>
            </div>
            <div class="category-card">
                <div class="category-icon orange"><img src="{{ asset('images/person.png') }}" alt=""></div>
                <div class="category-title">My Profile</div>
                <div class="category-desc">Updating your contact details, bio, office hours and subjects</div>
            </div>
            <div class="category-card">
                <div class="category-icon pink"><img src="{{ asset('images/clock.png') }}" alt=""></div>
                <div class="category-title">Attendance</div>
                <div class="category-desc">Recording and reviewing student attendance per session</div>
            </div>
            <div class="category-card">
                <div class="category-icon teal"><img src="{{ asset('images/technical.png') }}" alt=""></div>
                <div class="category-title">Technical Support</div>
                <div class="category-desc">Login issues, system errors and IT helpdesk contact</div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I view student performance for a module?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Go to <strong>My Modules</strong> in the sidebar, then click on the module card. Inside the module, select the <strong>Performance</strong> tab to see each student's scores for quizzes, homework, labs and exams. You can filter by group or search by student name.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I review and grade a student submission?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Open the module from <strong>My Modules</strong>, then click the <strong>Submissions</strong> tab. Each submission shows the student's name, group, assignment title and file. Use the action buttons to view or grade the submission.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I record attendance for a session?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Open the module, go to the <strong>Attendance</strong> tab. Select the student group and the session from the dropdowns, then click <strong>+ Add Attendance</strong> to record student attendance for that session.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How does the timetable work for teachers?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    The <strong>Timetable</strong> page defaults to your own schedule via the <strong>My Timetable</strong> button. You can also view any group's timetable using the <strong>Groups</strong> dropdown. Events are colour-coded: blue for lectures, yellow for tutorials, green for labs, purple for office hours.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I update my office hours or contact details?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Visit your <strong>My Profile</strong> page and click the <strong>Edit Profile</strong> button in the top-right of your profile banner. You can update your email, phone, office location and office hours there.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I switch between light and dark mode?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    Click the <strong>Dark Mode</strong> icon at the bottom of the sidebar (the moon/sun icon). The system saves your preference so it will persist across all pages.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    I cannot log in – what should I do?
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    First try clearing your browser cache and cookies, then reload the page. If the issue persists, contact the <strong>IT Helpdesk</strong> at <a href="mailto:it@sus.edu" style="color:#2563eb;">it@sus.edu</a> or visit Block C, Ground Floor. You can also raise a ticket via the <strong>Contact</strong> page.
                </div>
            </div>
        </div>

        <!-- Contact support options -->
        <div class="support-card">
            <div class="support-option" onclick="window.location.href='{{ route('contact') }}'">
                <div class="support-icon">&#9993;</div>
                <div class="support-title">Submit a Ticket</div>
                <div class="support-desc">Raise a support request via the Contact page and we'll respond within 24 hours</div>
            </div>
            <div class="support-option" onclick="window.location.href='{{ route('contact') }}'">
                <div class="support-icon">&#9743;</div>
                <div class="support-title">Call IT Helpdesk</div>
                <div class="support-desc">Speak directly with IT support at +1 555-1002, Mon–Fri 08:00–18:00</div>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/api.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('js/api.js') }}"></script> --}}
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

    // Navigation
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

    function toggleFaq(el) {
        var item = el.parentElement;
        item.classList.toggle('open');
    }
</script>
</body>
</html>
