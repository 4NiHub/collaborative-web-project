<aside class="sidebar" id="sidebar">
    <div class="sidebar-toggle-btn" onclick="toggleSidebar()">
        <img src="{{ asset('images/admin_icons/arrow_left.png') }}" alt="Toggle" style="width:15px;height:15px;"> 
    </div>
    <nav class="sidebar-icons">
        <div class="sidebar-icon {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard" onclick="location.href='{{ route('admin.dashboard') }}'">
            <img src="{{ asset('images/admin_icons/dashboard.png') }}" alt="Dashboard">
            <span class="sidebar-label">Dashboard</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.users') ? 'active' : '' }}" data-tooltip="Users" onclick="location.href='{{ route('admin.users') }}'">
            <img src="{{ asset('images/admin_icons/users.png') }}" alt="Users">
            <span class="sidebar-label">Users</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.timetable') ? 'active' : '' }}" data-tooltip="Timetable" onclick="location.href='{{ route('admin.timetable') }}'">
            <img src="{{ asset('images/admin_icons/timetable.png') }}" alt="Timetable">
            <span class="sidebar-label">Timetable</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.content') ? 'active' : '' }}" data-tooltip="Content Manager" onclick="location.href='{{ route('admin.content') }}'">
            <img src="{{ asset('images/admin_icons/content.png') }}" alt="Content">
            <span class="sidebar-label">Content Manager</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.grading') ? 'active' : '' }}" data-tooltip="Grading" onclick="location.href='{{ route('admin.grading') }}'">
            <img src="{{ asset('images/admin_icons/grades.png') }}" alt="Grading">
            <span class="sidebar-label">Grading</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.news') ? 'active' : '' }}" data-tooltip="News CMS" onclick="location.href='{{ route('admin.news') }}'">
            <img src="{{ asset('images/admin_icons/news.png') }}" alt="News">
            <span class="sidebar-label">News CMS</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.attendance') ? 'active' : '' }}" data-tooltip="Attendance" onclick="location.href='{{ route('admin.attendance') }}'">
            <img src="{{ asset('images/admin_icons/attendance.png') }}" alt="Attendance">
            <span class="sidebar-label">Attendance</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.teachers') ? 'active' : '' }}" data-tooltip="Teachers" onclick="location.href='{{ route('admin.teachers') }}'">
            <img src="{{ asset('images/admin_icons/teachers.png') }}" alt="Teachers">
            <span class="sidebar-label">Teachers</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.contact') ? 'active' : '' }}" data-tooltip="Contact" onclick="location.href='{{ route('admin.contact') }}'">
            <img src="{{ asset('images/admin_icons/contact.png') }}" alt="Contact">
            <span class="sidebar-label">Contact</span>
        </div>
        <div class="sidebar-icon {{ request()->routeIs('admin.help') ? 'active' : '' }}" data-tooltip="Help" onclick="location.href='{{ route('admin.help') }}'">
            <img src="{{ asset('images/admin_icons/help_grey.png') }}" alt="Help">
            <span class="sidebar-label">Help</span>
        </div>
    </nav>
    <div class="sidebar-bottom">
        <div class="sidebar-icon theme-toggle-btn" id="themeToggle" data-tooltip="Toggle Theme" onclick="toggleTheme()">
            <img src="{{ asset('images/admin_icons/dark_mode.png') }}" alt="Dark Mode">
            <span class="sidebar-label">Toggle Theme</span>
        </div>
        <div class="sidebar-icon logout-icon" data-tooltip="Logout" onclick="AdminAuthAPI.logout()">
            <img src="{{ asset('images/admin_icons/logout.png') }}" alt="Logout">
            <span class="sidebar-label">Logout</span>
        </div>
    </div>
</aside>