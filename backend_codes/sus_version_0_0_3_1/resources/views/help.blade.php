<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Help - Smart University System</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <style>
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar (identical to all other pages) ── */
        .sidebar {
            width: var(--sidebar-collapsed);
            background: #2563eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            z-index: 1000;
            transition: var(--transition);
            overflow: hidden;
            color: white;
        }
        .sidebar.expanded { width: var(--sidebar-expanded); }

        .sidebar-toggle-btn {
            height: 60px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: background 0.2s;
        }
        .sidebar-toggle-btn:hover { background: rgba(255,255,255,0.15); }
        .sidebar-toggle-btn img { width: 28px; height: 28px; transition: transform 0.4s ease; }
        .sidebar.expanded .sidebar-toggle-btn img { transform: rotate(180deg); }

        .sidebar-icons {
            flex: 1;
            display: flex; flex-direction: column; align-items: center;
            padding: 20px 0;
        }

        .sidebar-icon {
            width: calc(100% - 30px);
            height: 48px;
            border-radius: 10px;
            display: flex; align-items: center;
            padding: 0 18px;
            margin: 4px 8px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            color: rgba(255,255,255,0.85);
        }
        .sidebar-icon:hover, .sidebar-icon.active { background: rgba(255,255,255,0.18); color: white; }
        .sidebar-icon img { width: 32px; height: 32px; flex-shrink: 0; margin-right: 0; transition: margin-right 0.3s ease; }
        .sidebar.expanded .sidebar-icon img { margin-right: 16px; }

        .sidebar-label {
            opacity: 0; visibility: hidden; width: 0; overflow: hidden;
            white-space: nowrap; font-size: 15px; font-weight: 500;
            transition: opacity 0.22s ease 0.05s, width 0.35s ease, visibility 0.35s;
        }
        .sidebar.expanded .sidebar-label { opacity: 1; visibility: visible; width: auto; }

        .theme-toggle, .logout-icon { margin-top: auto; }

        .sidebar:not(.expanded) .sidebar-icon::after {
            content: attr(data-tooltip);
            position: fixed; left: 98px; top: auto;
            background: #1e293b; color: white;
            padding: 8px 14px; border-radius: 6px;
            font-size: 13px; font-weight: 500; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: opacity 0.2s;
            z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.3); line-height: 1;
        }
        .sidebar:not(.expanded) .sidebar-icon::before {
            content: ''; position: fixed; left: 87px; top: auto;
            border: 7px solid transparent; border-right-color: #1e293b;
            opacity: 0; transition: opacity 0.2s; z-index: 9999;
        }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(1):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(1):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::before,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(10):hover::before { opacity: 1; }

        .logout-icon { margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0; }
        .logout-icon:hover { background: rgba(239,68,68,0.2); color: #ef4444; }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-collapsed);
            transition: var(--transition);
            flex: 1; padding: 20px;
            max-width: 2000px; position: relative; z-index: 1;
        }
        .sidebar.expanded + .main-content { margin-left: var(--sidebar-expanded); }

        /* ── Top bar ── */
        .top-bar {
            background: white; padding: 16px 24px; border-radius: 12px;
            margin-bottom: 24px; display: flex; justify-content: space-between;
            align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .logo-container {
            height: 70px; width: 150px; overflow: hidden;
            display: flex; align-items: center; justify-content: flex-start; padding-left: 0; margin-left: 0;
        }
        .logo-container img { max-height: 250%; height: auto; width: auto; object-fit: contain; margin-left: -40px; }
        .logo-light { display: block; }
        .logo-dark  { display: none; }
        .page-title { font-size: 18px; font-weight: 600; color: #1e293b; }

        /* ── Help page header ── */
        .page-header { margin-bottom: 32px; }
        .page-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .page-header p  { color: #64748b; font-size: 14px; }

        /* ── Search bar ── */
        .help-search {
            background: white; border-radius: 12px; padding: 20px 24px;
            margin-bottom: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex; align-items: center; gap: 14px;
        }
        .help-search-icon { width: 22px; height: 22px; flex-shrink: 0; opacity: 0.45; }
        .help-search input {
            flex: 1; border: none; outline: none; font-size: 15px;
            background: transparent; color: inherit; font-family: var(--font);
        }
        .help-search input::placeholder { color: #94a3b8; }

        /* ── Category cards row ── */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        .category-card {
            background: white; border-radius: 12px; padding: 20px;
            text-align: center; cursor: pointer;
            border: 2px solid transparent;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .category-card:hover, .category-card.active {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .category-card.active .cat-label { color: #2563eb; font-weight: 700; }
        .cat-icon {
            width: 44px; height: 44px; border-radius: 10px;
            background: #eff6ff; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 12px;
        }
        .cat-icon img { width: 24px; height: 24px; }
        .cat-label { font-size: 13px; font-weight: 600; color: #1e293b; }
        .cat-count { font-size: 12px; color: #64748b; margin-top: 4px; }

        /* ── FAQ sections ── */
        .faq-section { display: none; }
        .faq-section.visible { display: block; }

        .faq-section-title {
            font-size: 16px; font-weight: 700; margin-bottom: 16px;
            color: #1e293b;
        }

        .faq-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 32px; }

        .faq-item {
            background: white; border-radius: 10px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            transition: border-color 0.2s;
        }
        .faq-item:hover { border-color: #93c5fd; }
        .faq-item.open { border-color: #2563eb; }

        .faq-question {
            display: flex; justify-content: space-between; align-items: center;
            padding: 16px 20px; cursor: pointer;
            font-weight: 600; font-size: 14px;
            user-select: none;
        }
        .faq-arrow {
            width: 20px; height: 20px; flex-shrink: 0;
            transition: transform 0.25s ease;
            opacity: 0.5;
        }
        .faq-item.open .faq-arrow { transform: rotate(180deg); opacity: 1; }

        .faq-answer {
            max-height: 0; overflow: hidden;
            transition: max-height 0.3s ease, padding 0.2s;
            padding: 0 20px;
            font-size: 14px; color: #475569; line-height: 1.65;
        }
        .faq-item.open .faq-answer {
            max-height: 400px;
            padding: 0 20px 18px;
        }

        /* ── Contact card ── */
        .contact-section {
            background: white; border-radius: 12px; padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px;
        }
        .contact-section h3 { font-size: 16px; font-weight: 700; margin-bottom: 16px; }
        .contact-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .contact-option {
            border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px;
            text-align: center; cursor: pointer; transition: all 0.2s;
        }
        .contact-option:hover { border-color: #2563eb; background: #eff6ff; }
        .contact-option img { width: 28px; height: 28px; margin: 0 auto 10px; display: block; }
        .contact-option-title { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
        .contact-option-desc  { font-size: 12px; color: #64748b; }

        /* ── No results ── */
        .no-results { text-align: center; padding: 60px 20px; color: #64748b; font-size: 14px; display: none; }
        .no-results img { width: 48px; height: 48px; margin: 0 auto 16px; display: block; opacity: 0.4; }

        /* ── Dark mode ── */
        body.dark-mode .sidebar                { background: #1e293b; }
        body.dark-mode .sidebar-icon::after    { background: #334155; }
        body.dark-mode .sidebar-icon::before   { border-right-color: #334155; }
        body.dark-mode .logout-icon            { border-top-color: rgba(255,255,255,0.05); }
        body.dark-mode .logo-light             { display: none; }
        body.dark-mode .logo-dark              { display: block; }

        body.dark-mode .top-bar,
        body.dark-mode .help-search,
        body.dark-mode .category-card,
        body.dark-mode .faq-item,
        body.dark-mode .contact-section,
        body.dark-mode .contact-option         { background: #1e293b; }

        body.dark-mode .category-card:hover,
        body.dark-mode .category-card.active   { background: #1e3a5f; border-color: #3b82f6; }
        body.dark-mode .faq-item               { border-color: #334155; }
        body.dark-mode .faq-item:hover         { border-color: #3b82f6; }
        body.dark-mode .faq-item.open          { border-color: #3b82f6; }
        body.dark-mode .contact-option         { border-color: #334155; }
        body.dark-mode .contact-option:hover   { border-color: #3b82f6; background: #1e3a5f; }

        body.dark-mode .page-title,
        body.dark-mode h1, body.dark-mode h3,
        body.dark-mode .faq-section-title,
        body.dark-mode .faq-question,
        body.dark-mode .cat-label,
        body.dark-mode .contact-option-title   { color: #f1f5f9; }

        body.dark-mode .faq-answer             { color: #94a3b8; }
        body.dark-mode .help-search input      { color: #e2e8f0; }

        /* ── responsive ── */
        @media (max-width: 900px) {
            .category-grid    { grid-template-columns: repeat(2, 1fr); }
            .contact-options  { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-toggle-btn">
            <img src="{{asset('images/arrow_menu_open.png')}}" alt="Toggle Sidebar">
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
            <div class="sidebar-icon active" data-page="help" data-tooltip="Help">
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

        <div class="page-header">
            <h1>Help Centre</h1>
            <p>Find answers to common questions, or contact support</p>
        </div>

        <!-- Search -->
        <div class="help-search">
            <img class="help-search-icon" src="{{ asset('images/search.png') }}" alt="Search">
            <input type="text" id="helpSearch" placeholder="Search for help…" autocomplete="off">
        </div>

        <!-- Category tabs -->
        <div class="category-grid" id="categoryGrid">
            <div class="category-card active" data-cat="all">
                <div class="cat-icon"><img src="{{ asset('images/home_black.png') }}" alt="All"></div>
                <div class="cat-label">All Topics</div>
                <div class="cat-count">14 articles</div>
            </div>
            <div class="category-card" data-cat="account">
                <div class="cat-icon"><img src="{{ asset('images/person.png') }}" alt="Account"></div>
                <div class="cat-label">Account</div>
                <div class="cat-count">3 articles</div>
            </div>
            <div class="category-card" data-cat="modules">
                <div class="cat-icon"><img src="{{ asset('images/modules_black.png') }}" alt="Modules"></div>
                <div class="cat-label">Modules</div>
                <div class="cat-count">4 articles</div>
            </div>
            <div class="category-card" data-cat="grades">
                <div class="cat-icon"><img src="{{ asset('images/records_black.png') }}" alt="Grades"></div>
                <div class="cat-label">Grades &amp; Records</div>
                <div class="cat-count">4 articles</div>
            </div>
            <div class="category-card" data-cat="timetable">
                <div class="cat-icon"><img src="{{ asset('images/calendar_black.png') }}" alt="Timetable"></div>
                <div class="cat-label">Timetable</div>
                <div class="cat-count">2 articles</div>
            </div>
            <div class="category-card" data-cat="news">
                <div class="cat-icon"><img src="{{ asset('images/news_black.png') }}" alt="News"></div>
                <div class="cat-label">News</div>
                <div class="cat-count">1 article</div>
            </div>
            <div class="category-card" data-cat="technical">
                <div class="cat-icon"><img src="{{ asset('images/technical.png') }}" alt="Technical"></div>
                <div class="cat-label">Technical Issues</div>
                <div class="cat-count">2 articles</div>
            </div>
            <div class="category-card" data-cat="career">
                <div class="cat-icon"><img src="{{ asset('images/career_black.png') }}" alt="Career"></div>
                <div class="cat-label">Career Centre</div>
                <div class="cat-count">2 articles</div>
            </div>
        </div>

        <!-- No results -->
        <div class="no-results" id="noResults">
            <img src="{{ asset('images/help.png') }}" alt="No results">
            <p>No articles matched your search. Try different keywords.</p>
        </div>

        <!-- FAQ sections -->
        <div id="faqContainer">

            <!-- Account -->
            <div class="faq-section visible" data-section="account">
                <div class="faq-section-title">Account</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="login password reset forgot">
                        <div class="faq-question">
                            How do I reset my password?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Go to the login page and click <strong>"Forgot Password"</strong>. Enter your university email address and you will receive a reset link within a few minutes. If you don't receive it, check your spam folder or contact the IT helpdesk.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="dark mode theme toggle light">
                        <div class="faq-question">
                            How do I switch between light and dark mode?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Click the <strong>Dark Mode</strong> icon in the left sidebar (moon icon). Your preference is saved automatically and will persist the next time you log in.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="update profile information email">
                        <div class="faq-question">
                            How do I update my personal information?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Personal details such as your name and student ID are managed by the university registry. To request changes, please contact the Student Services team via the <strong>Contact</strong> page.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modules -->
            <div class="faq-section visible" data-section="modules">
                <div class="faq-section-title">Modules</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="enrol module enroll register course">
                        <div class="faq-question">
                            Why can't I see a module I enrolled in?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Module enrolments can take up to 24 hours to appear after registration. If a module is still missing after that time, contact your programme administrator to confirm the enrolment was processed correctly.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="assignment submit retake resubmit">
                        <div class="faq-question">
                            How do I submit or retake an assignment?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Open a module, click <strong>Overview</strong>, then find the assignment in the Assignments section. If a submission is due, a <strong>Submit</strong> button will appear. If you are eligible for a retake, a <strong>Retake</strong> button will appear instead. Follow the on-screen steps to complete the submission.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="resources download lecture slides material">
                        <div class="faq-question">
                            Where can I find lecture slides and materials?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Open the module card and switch to the <strong>Resources</strong> tab. All uploaded lecture slides, worksheets, and reading materials are listed there. Materials are added by your module leader throughout the semester.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="progress percentage grade module">
                        <div class="faq-question">
                            What does the module progress percentage mean?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            The percentage reflects your current cumulative score across all assessed components for that module. It is updated automatically after each grade is released by your module leader.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades -->
            <div class="faq-section visible" data-section="grades">
                <div class="faq-section-title">Grades &amp; Records</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="gpa grade point average calculate">
                        <div class="faq-question">
                            How is my GPA calculated?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Your GPA is a weighted average of your module grades, taking into account the credit value of each module. 
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="transcript official record download">
                        <div class="faq-question">
                            How do I get an official transcript?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Official transcripts are issued by the Student Registry. Submit a request via the <strong>Contact</strong> page or visit the registry in person. Allow up to 5 working days for processing.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="grade wrong incorrect appeal">
                        <div class="faq-question">
                            I think my grade is incorrect. What should I do?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            First, check the marking breakdown with your module leader. If you believe there is a factual error, you can submit a <strong>Grade Appeal</strong> through the Contact page within 14 days of the grade being published.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="credits completed total degree">
                        <div class="faq-question">
                            What are credits and how many do I need?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Credits represent the learning effort required for each module. A typical year is worth 120 credits and a full bachelor's degree requires 360 credits. Your running total is shown on the Dashboard and Records page.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timetable -->
            <div class="faq-section visible" data-section="timetable">
                <div class="faq-section-title">Timetable</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="timetable schedule class room">
                        <div class="faq-question">
                            How do I view my full weekly timetable?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Click <strong>Timetable</strong> in the sidebar. You can switch between monthly, weekly, and daily views using the dropdown at the top of the calendar. Your enrolled modules and their sessions are displayed automatically.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="class missing timetable not showing cancelled">
                        <div class="faq-question">
                            A class is not appearing on my timetable. What should I do?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Timetable data is pulled from the university scheduling system. If a session is missing, it may not have been entered by your department yet. Contact your module leader or departmental administrator to confirm the session details.
                        </div>
                    </div>
                </div>
            </div>

            <!-- News -->
            <div class="faq-section visible" data-section="news">
                <div class="faq-section-title">News</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="bookmark news save article">
                        <div class="faq-question">
                            How do I bookmark a news article?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Click the bookmark icon on any news card. The icon will turn dark to show it is saved. To remove a bookmark, click the icon again. Your bookmarks are stored for your session.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical -->
            <div class="faq-section visible" data-section="technical">
                <div class="faq-section-title">Technical Issues</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="page not loading error refresh browser">
                        <div class="faq-question">
                            A page is not loading correctly. What should I do?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Try refreshing the page (Ctrl+R / Cmd+R). If the issue persists, clear your browser cache and cookies, then try again. Supported browsers are Chrome, Firefox, Edge, and Safari (latest versions). If the problem continues, contact the IT helpdesk.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="logout session expired login again">
                        <div class="faq-question">
                            I was logged out automatically. Why?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            For security, your session expires after a period of inactivity. Simply log in again using your university credentials. If you are being logged out too frequently, check that cookies are enabled in your browser settings.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Career -->
            <div class="faq-section visible" data-section="career">
                <div class="faq-section-title">Career Centre</div>
                <div class="faq-list">
                    <div class="faq-item" data-keywords="career job internship opportunity apply">
                        <div class="faq-question">
                            How do I apply for a job or internship listed in the Career Centre?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Each listing in the Career Centre includes an application link or contact email. Click <strong>Apply</strong> on the listing to be directed to the employer's application process. Some positions require a CV and cover letter — the Career Centre team can assist with preparation.
                        </div>
                    </div>
                    <div class="faq-item" data-keywords="cv resume career advice appointment booking">
                        <div class="faq-question">
                            Can I book a career advice appointment?
                            <img class="faq-arrow" src="{{ asset('images/arrow_down.png') }}" alt="Toggle">
                        </div>
                        <div class="faq-answer">
                            Yes. Use the <strong>Contact</strong> page to reach the Career Centre team and request an appointment. Appointments are typically available Monday to Friday, 9am–4pm.
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end faqContainer -->

        <!-- Contact support -->
        <div class="contact-section">
            <h3>Still need help?</h3>
            <div class="contact-options">
                <div class="contact-option" onclick="window.location.href='{{ route('contact') }}'">
                    <img src="{{ asset('images/mail.png') }}" alt="Email">
                    <div class="contact-option-title">Email Support</div>
                    <div class="contact-option-desc">info@sus.edu</div>
                </div>
                <div class="contact-option" onclick="window.location.href='{{ route('contact') }}'">
                    <img src="{{ asset('images/call_black.png') }}" alt="Phone">
                    <div class="contact-option-title">Call Us</div>
                    <div class="contact-option-desc">Mon–Fri, 9am–5pm</div>
                </div>
                <div class="contact-option" onclick="window.location.href='{{ route('contact') }}'">
                    <img src="{{ asset('images/person.png') }}" alt="Visit">
                    <div class="contact-option-title">Visit In Person</div>
                    <div class="contact-option-desc">Student Services, Building A</div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="{{ asset('js/api.js') }}"></script>

<script>
    // authGuard();

    // Theme
    (function() {
        if (localStorage.getItem('darkMode') === 'enabled') document.body.classList.add('dark-mode');
    })();

    document.querySelector('.theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
    });

    // Logout
    document.querySelector('.logout-icon').addEventListener('click', function() {
        if (confirm('Are you sure you want to logout?')) {
            AuthAPI.logout();
        }
    });

    // Sidebar toggle & nav
    var sidebar     = document.querySelector('.sidebar');
    var toggleBtn   = document.querySelector('.sidebar-toggle-btn');
    var mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', function() { sidebar.classList.toggle('expanded'); });
    mainContent.addEventListener('click', function(e) {
        if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) sidebar.classList.remove('expanded');
    });

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

    // Category filter 
    var allSections = document.querySelectorAll('.faq-section');

    document.querySelectorAll('.category-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.category-card').forEach(function(c) { c.classList.remove('active'); });
            this.classList.add('active');
            var cat = this.dataset.cat;
            allSections.forEach(function(sec) {
                if (cat === 'all' || sec.dataset.section === cat) {
                    sec.classList.add('visible');
                } else {
                    sec.classList.remove('visible');
                }
            });
            document.getElementById('helpSearch').value = '';
            document.getElementById('noResults').style.display = 'none';
        });
    });

    // FAQ accordion
    document.querySelectorAll('.faq-question').forEach(function(q) {
        q.addEventListener('click', function() {
            var item = this.closest('.faq-item');
            var wasOpen = item.classList.contains('open');
            // close all
            document.querySelectorAll('.faq-item').forEach(function(i) { i.classList.remove('open'); });
            if (!wasOpen) item.classList.add('open');
        });
    });

    //  Search 
    document.getElementById('helpSearch').addEventListener('input', function() {
        var query = this.value.trim().toLowerCase();
        if (!query) {
            // reset to active category
            var activeCat = document.querySelector('.category-card.active').dataset.cat;
            allSections.forEach(function(sec) {
                sec.classList.toggle('visible', activeCat === 'all' || sec.dataset.section === activeCat);
            });
            document.querySelectorAll('.faq-item').forEach(function(i) { i.style.display = ''; });
            document.getElementById('noResults').style.display = 'none';
            return;
        }

        // show all sections and filter items
        var anyVisible = false;
        allSections.forEach(function(sec) { sec.classList.add('visible'); });
        document.querySelectorAll('.faq-item').forEach(function(item) {
            var q   = item.querySelector('.faq-question').textContent.toLowerCase();
            var a   = item.querySelector('.faq-answer').textContent.toLowerCase();
            var kw  = (item.dataset.keywords || '').toLowerCase();
            var hit = q.includes(query) || a.includes(query) || kw.includes(query);
            item.style.display = hit ? '' : 'none';
            if (hit) anyVisible = true;
        });

        document.getElementById('noResults').style.display = anyVisible ? 'none' : 'block';
        // hide sections with all items hidden
        allSections.forEach(function(sec) {
            var visible = Array.from(sec.querySelectorAll('.faq-item')).some(function(i) { return i.style.display !== 'none'; });
            sec.classList.toggle('visible', visible);
        });
    });
</script>
</body>
</html>