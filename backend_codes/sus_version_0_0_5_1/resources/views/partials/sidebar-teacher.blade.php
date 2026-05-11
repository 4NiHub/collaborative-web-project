<aside class="sidebar" id="sidebar">
    <div class="sidebar-toggle-btn">
        <img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle">
    </div>

    <div class="sidebar-icons">
        <div class="sidebar-icon {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
             data-page="dashboard" data-tooltip="Dashboard">
            <img src="{{ asset('images/home.png') }}" alt="Dashboard">
            <span class="sidebar-label">Dashboard</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('teacher.timetable') ? 'active' : '' }}" 
             data-page="timetable" data-tooltip="Timetable">
            <img src="{{ asset('images/calendar.png') }}" alt="Timetable">
            <span class="sidebar-label">Timetable</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('teacher.modules') ? 'active' : '' }}" 
             data-page="modules" data-tooltip="My Modules">
            <img src="{{ asset('images/modules.png') }}" alt="Modules">
            <span class="sidebar-label">My Modules</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('teacher.profile') ? 'active' : '' }}" 
             data-page="profile" data-tooltip="My Profile">
            <img src="{{ asset('images/person_white.png') }}" alt="Profile">
            <span class="sidebar-label">My Profile</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('news') ? 'active' : '' }}" 
             data-page="news" data-tooltip="News">
            <img src="{{ asset('images/news.png') }}" alt="News">
            <span class="sidebar-label">News</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('career-centre') ? 'active' : '' }}" 
             data-page="career" data-tooltip="Career Centre">
            <img src="{{ asset('images/career.png') }}" alt="Career Centre">
            <span class="sidebar-label">Career Centre</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('contact') ? 'active' : '' }}" 
             data-page="contact" data-tooltip="Contact">
            <img src="{{ asset('images/contact.png') }}" alt="Contact">
            <span class="sidebar-label">Contact</span>
        </div>

        <div class="sidebar-icon {{ request()->routeIs('teacher.help') ? 'active' : '' }}" 
             data-page="help" data-tooltip="Help">
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