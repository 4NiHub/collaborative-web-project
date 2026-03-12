<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us - Smart University System</title>
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
        .contact-grid {
            display: grid;
            grid-template-columns: 4fr 2fr;
            gap: 24px;
        }

        /* contact form */
        .contact-form-section {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-header {
            margin-bottom: 24px;
        }

        .form-header h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
            background: white;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .submit-btn {
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

        .submit-btn:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* contact info */
        .contact-info-section {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .info-header {
            margin-bottom: 24px;
        }

        .info-header h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .contact-card {
            padding: 20px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .contact-card:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            width: 48px;
            height: 48px;
            background: #2563eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: white;
        }

        .contact-icon svg {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .contact-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .contact-detail {
            color: #475569;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 4px;
        }

        .contact-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        /* departments sect */
        .departments-section {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            grid-column: 1 / -1;
            margin-top: 24px;
        }
        .departments-header {
            margin-bottom: 24px;
        }

        .departments-header h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .departments-header p {
            color: #64748b;
            font-size: 14px;
        }

        .departments-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .department-card {
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .department-card:hover {
            border-color: #2563eb;
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .department-name {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .department-contact {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .department-contact svg {
            width: 16px;
            height: 16px;
        }

        /* success message */
        .success-message {
            display: none;
            background: #dcfce7;
            color: #166534;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            align-items: center;
            gap: 12px;
        }

        .success-message.show {
            display: flex;
        }

        .success-message svg {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        body.dark-mode .sidebar {
            background: #1e293b;
        }

        body.dark-mode .top-bar,
        body.dark-mode .contact-form-section,
        body.dark-mode .contact-info-section,
        body.dark-mode .departments-section {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .form-input,
        body.dark-mode .form-select,
        body.dark-mode .form-textarea {
            background: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        body.dark-mode .contact-card,
        body.dark-mode .department-card {
            background: #0f172a;
            border-color: #334155;
        }

        body.dark-mode .department-card:hover {
            background: #1e293b;
        }

        body.dark-mode .page-title,
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3 {
            color: #f1f5f9;
        }

        @media (max-width: 1024px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .departments-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .departments-grid {
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
                <div class="sidebar-icon active" data-page="contact" data-tooltip="Contact">
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


            <!-- Page header -->
            <div class="page-header">
                <h1>Contact Us</h1>
                <p>Get in touch with university departments</p>
            </div>

           
            <div class="contact-grid">
                <div class="contact-form-section">
                    <div class="form-header">
                        <h2>Send us a message</h2>
                        <p>Fill out the form below and we will get back to you as soon as possible</p>
                    </div>

                    <div class="success-message" id="successMessage">
                        <img src="{{ asset('images/check_circle.png') }}" style="width: 30px; height: 30px; display: block; margin-left: 1px;">
                        <div>
                            <strong>Message sent successfully!</strong><br>
                            We'll get back to you shortly.
                        </div>
                    </div>

                    <form id="contactForm" novalidate>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="fullName">Full Name</label>
                                <input 
                                    type="text" 
                                    id="fullName" 
                                    name="name"
                                    class="form-input" 
                                    placeholder="Your full name"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email"
                                    class="form-input" 
                                    placeholder="your@email.com"
                                    required
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="department">Department</label>
                            <select id="department" name="department" class="form-select" required>
                                <option value="">Select a department</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subject">Subject</label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject"
                                class="form-input" 
                                placeholder="Enter subject"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">Message</label>
                            <textarea 
                                id="message" 
                                class="form-textarea" 
                                name="message"
                                placeholder="Type your message here..."
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>

                <!-- General info -->
                <div class="contact-info-section">
                    <div class="info-header">
                        <h2>General Information</h2>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <img src="{{ asset('images/distance.png') }}" style="width: 30px; height: 30px; display: block; margin-left: 1px;">
                        </div>
                        <h3>Address</h3>
                        <p class="contact-detail">123 University Avenue</p>
                        <p class="contact-detail">College Town, St 12345</p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                           <img src="{{ asset('images/call_white.png') }}" style="width: 30px; height: 30px; display: block; margin-left: 1px;">
                        </div>
                        <h3>Phone</h3>
                        <p class="contact-detail">
                            <a href="tel:+15551234500" class="contact-link">+1 (555) 123-4500</a>
                        </p>
                        <p class="contact-detail" style="color: #64748b; font-size: 13px;">Main Office</p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <img src="{{ asset('images/contact.png') }}" style="width: 30px; height: 30px; display: block; margin-left: 1px;">
                        </div>
                        <h3>Email</h3>
                        <p class="contact-detail">
                            <a href="mailto:info@university.edu" class="contact-link">info@sus.edu</a>
                        </p>
                        <p class="contact-detail" style="color: #64748b; font-size: 13px;">General Inquiries</p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <img src="{{ asset('images/schedule.png') }}" style="width: 30px; height: 30px; display: block; margin-left: 1px;">
                        </div>
                        <h3>Office Hours</h3>
                        <p class="contact-detail">Mon - Fri: 8:00 AM - 5:00 PM</p>
                        <p class="contact-detail" style="color: #64748b; font-size: 13px;">Closed on weekends and holidays</p>
                    </div>
                </div>
            </div>

            <div class="departments-section">
                <div class="departments-header">
                    <h2>Departments</h2>
                    <p>Direct contact information</p>
                </div>

                <div class="departments-grid" id="departmentsGrid">
                    <p class="dept-loading">Loading departments...</p>
                </div>
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

       
        document.querySelector('.theme-toggle').addEventListener('click', function() {
          document.body.classList.toggle('dark-mode');
          if (document.body.classList.contains('dark-mode')) {
              localStorage.setItem('darkMode', 'enabled');
          } else {
              localStorage.setItem('darkMode', 'disabled');
          }
        });
        //logout
        document.querySelector('.logout-icon').addEventListener('click', function() {
           if (confirm('Are you sure you want to logout?')) {
             AuthAPI.logout();
            }
        });

        // Sidebar nav
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
        document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon) {
            icon.addEventListener('click', function() {
                var pageMap = {
                    'dashboard': '/dashboard',
                    'timetable': '/timetable',
                    'modules':   '/modules',
                    'records' :  '/records',
                    'news':      '/news',
                    'teachers':  '/teachers',
                    'career':    '/career-centre',
                    'contact':   '/contact',
                    'help':      '/help' 
                };

                var dest = pageMap[this.dataset.page];
                if (dest) window.location.href = dest;
            });
        });

        //load departments
        async function loadDepartments() {
            try {
                var res         = await ContactAPI.getDepartments();
                var departments = res.data || [];

                var select = document.getElementById('department');
                var placeholder = select.querySelector('option[value=""]');
                select.innerHTML = '';
                if (placeholder) select.appendChild(placeholder);

                departments.forEach(function(d) {
                    var opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.name;
                    select.appendChild(opt);
                });

                var grid = document.getElementById('departmentsGrid');

                if (departments.length === 0) {
                    grid.innerHTML = '<p class="dept-loading">No department information available.</p>';
                    return;
                }

                grid.innerHTML = '';
                departments.forEach(function(d) {
                    var card = document.createElement('div');
                    card.className = 'department-card';
                    card.innerHTML =
                        '<div class="department-name">' + d.name + '</div>' +
                        (d.phone ? '<div class="department-contact">' +
                            '<img src="{{ asset('images/call.png') }}" style="width:18px;height:18px;display:block;margin-left:1px;">' +
                            '<a href="tel:' + d.phone + '" class="contact-link">' + d.phone + '</a>' +
                        '</div>' : '') +
                        (d.email ? '<div class="department-contact">' +
                            '<img src="{{ asset('images/mail_blue.png') }}" style="width:18px;height:18px;display:block;margin-left:1px;">' +
                            '<a href="mailto:' + d.email + '" class="contact-link">' + d.email + '</a>' +
                        '</div>' : '') +
                        (d.location ? '<div class="department-contact"><img src="{{ asset('images/pin.png') }}" style="width:18px;height:18px;display:block;margin-left:1px;"> ' + d.location + '</div>' : '');
                    grid.appendChild(card);
                });

            } catch (err) {
                document.getElementById('departmentsGrid').innerHTML =
                    '<p class="dept-loading" style="color:#dc2626;">Could not load departments.</p>';
                console.error('[Contact] Departments load failed:', err.message);
            }
        }
        // Form submit
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            var btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Sending…';

            var formData = {
                name:       document.getElementById('fullName').value.trim(),
                email:      document.getElementById('email').value.trim(),
                department: document.getElementById('department').value,
                subject:    document.getElementById('subject').value.trim(),
                message:    document.getElementById('message').value.trim()
            };

            try {
                var res = await ContactAPI.submitForm(formData);

                // show success 
                var banner = document.getElementById('successMessage');
                banner.classList.add('show');
                setTimeout(function() { banner.classList.remove('show'); }, 5000);



                this.reset();
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } catch (err) {
                alert('Failed to send message: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Send Message';
            }
        });
        loadDepartments();
    </script>
</body>
</html>