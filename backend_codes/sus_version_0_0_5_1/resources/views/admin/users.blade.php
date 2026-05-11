<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users – SUSAdmin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/png" href="{{ asset('images/sus_logo_dark.png') }}" media="(prefers-color-scheme: dark)">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin_reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_sidebar.css') }}">
    <style>
        /* .sidebar {
            width: 100px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0 24px;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            transition: width 0.2s ease;
            z-index: 100;
            overflow: visible;
        }
        .sidebar.expanded { width: 220px; }

        .sidebar-toggle-btn {
            position: absolute;
            top: 20px; right: -12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #475569; z-index: 110;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .sidebar-icons {
            margin-top: 48px; width: 100%;
            display: flex; flex-direction: column; gap: 4px;
        }

        .sidebar-icon {
            display: flex; align-items: center;
            justify-content: center; gap: 12px;
            padding: 11px 14px; margin: 0 10px;
            border-radius: 12px; cursor: pointer;
            color: #475569; transition: all 0.18s;
            position: relative; white-space: nowrap;
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif;
        }
        .sidebar-icon:hover { background: #f1f5f9; color: #2563eb; }
        .sidebar-icon.active { background: #eef2ff; color: #2563eb; }
        .sidebar-icon svg { min-width: 20px; width: 20px; height: 20px; flex-shrink: 0; }

        .sidebar-label { font-size: 14px; font-weight: 500; display: none; }
        .sidebar.expanded .sidebar-label { display: inline-block; }
        .sidebar.expanded .sidebar-icon { justify-content: flex-start; padding: 11px 18px; }

        .logout-icon { margin-top: auto; margin-bottom: 8px; }
        .logout-icon:hover { background: #fff1f2 !important; color: #dc2626 !important; }

        .sidebar-icon[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute; left: 74px;
            background: #1e293b; color: white;
            font-size: 13px; font-weight: 500;
            padding: 5px 10px; border-radius: 7px;
            white-space: nowrap; z-index: 200;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .sidebar.expanded .sidebar-icon[data-tooltip]:hover::after { display: none; } */

        /*  Layout */
        /* .app-container { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        .main-content {
            flex: 1;
            margin-left: 72px;
            overflow-y: auto;
            transition: margin-left 0.2s ease;
        }
        .sidebar.expanded ~ .main-content,
        .sidebar.expanded + .main-content { margin-left: 220px; } */

        .table-wrapper { overflow-x: auto; }
        .action-btn {
            width: 32px; height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.2s;
        }
        body.dark-mode .action-btn { background: #0f172a; }
        .action-btn:hover { background: #f1f5f9; color: #2563eb; }
        .action-btn.del:hover { background: #fee2e2; color: #dc2626; }
        .role-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            background: #f1f5f9;
            color: #475569;
        }
        .filter-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
        }
        .filter-select {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 14px;
            background: white;
            color: #374151;
            cursor: pointer;
            outline: none;
        }
        body.dark-mode .filter-select { background: #1e293b; color: #e2e8f0; border-color: #334155; }

        /* ── Logout Popup Styles ── */
        .logout-popup-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(5px);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .logout-popup-overlay.show { opacity: 1; visibility: visible; }
        .logout-popup-modal {
            background: white; border-radius: 20px; padding: 32px;
            max-width: 380px; width: 90%; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            transform: translateY(20px) scale(0.95); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .logout-popup-overlay.show .logout-popup-modal { transform: translateY(0) scale(1); }
        .logout-icon-large { font-size: 28px; color: #dc2626; background: #fee2e2; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .logout-popup-title { font-size: 20px; font-weight: 700; margin-bottom: 8px; color: #1e293b; }
        .logout-popup-text { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        .logout-actions { display: flex; gap: 12px; }
        .logout-btn-cancel, .logout-btn-confirm { flex: 1; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: background 0.2s; }
        .logout-btn-cancel { background: #f1f5f9; color: #475569; }
        .logout-btn-cancel:hover { background: #e2e8f0; }
        .logout-btn-confirm { background: #dc2626; color: white; }
        .logout-btn-confirm:hover { background: #b91c1c; }
        
        /* Dark Mode for Popup */
        body.dark-mode .logout-popup-modal { background: #1e293b; border: 1px solid #334155; }
        body.dark-mode .logout-popup-title { color: #f1f5f9; }
        body.dark-mode .logout-popup-text { color: #94a3b8; }
        body.dark-mode .logout-icon-large { background: #450a0a; color: #f87171; }
        body.dark-mode .logout-btn-cancel { background: #334155; color: #e2e8f0; }
        body.dark-mode .logout-btn-cancel:hover { background: #475569; }
    </style>
</head>
<body>
<div class="app-container">
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
            <div class="sidebar-icon logout-icon" data-tooltip="Logout" onclick="showLogoutPopup()">
                <img src="{{ asset('images/admin_icons/logout.png') }}" alt="Logout">
                <span class="sidebar-label">Logout</span>
            </div>
        </div>
    </aside>

    <main class="main-content" id="main">
                <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <div class="page-header">
            <div class="page-header-left">
                <h1>Users</h1>
                <p>Manage students and teachers</p>
            </div>
            <button class="btn-primary" onclick="openAddModal()">
                <img src="{{ asset('images/admin_icons/add.png') }}" alt="Add" style="width:20px;height:20px;">
                Add User
            </button>
        </div>

        <div class="filter-bar">
            <select class="filter-select" onchange="filterUsers()" id="roleFilter">
                <option value="">All Roles</option>
                <option value="Student">Student</option>
                <option value="Teacher">Teacher</option>
            </select>
            <select class="filter-select" onchange="filterUsers()" id="statusFilter">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Banned">Banned</option>
            </select>
        </div>

        <div class="card table-wrapper">
            <table class="data-table" id="usersTable">
                <thead>
                    <tr>
                        <th>Full name</th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="usersBody">
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add User Modal -->
{{-- <div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Add New User</div>
            <button class="modal-close" onclick="closeAddModal()">✕</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="form-label">Full Name</label>
                <input type="text" class="form-input" id="newName" placeholder="John Doe">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div>
                    <label class="form-label">ID</label>
                    <input type="text" class="form-input" id="newId" placeholder="STU001">
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <select class="form-select" id="newRole">
                        <option>Student</option>
                        <option>Teacher</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Email</label>
                <input type="email" class="form-input" id="newEmail" placeholder="user@university.edu">
            </div>
            <div>
                <label class="form-label">Status</label>
                <select class="form-select" id="newStatus">
                    <option>Active</option>
                    <option>Banned</option>
                </select>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeAddModal()">Cancel</button>
            <button class="btn-primary" onclick="addUser()">Add User</button>
        </div>
    </div>
</div> --}}

<div id="addUserModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Add New User</div>
            <button class="modal-close" onclick="closeAddModal()">✕</button>
        </div>
        
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div>
                <label class="form-label">Full Name</label>
                <input type="text" id="addFullName" class="form-input" placeholder="e.g. Naruto Uzumaki">
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <label class="form-label">Role</label>
                    <select id="addRole" class="form-select">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" id="addPassword" class="form-input" placeholder="Minimum 6 chars">
                </div>
            </div>

            <div>
                <label class="form-label">Email</label>
                <input type="email" id="addEmail" class="form-input" placeholder="N.Uzumaki@wlv.ac.uk">
            </div>

            <div class="modal-actions">
                <button onclick="closeAddModal()" class="btn-secondary" style="background:#334155; color:white;">Cancel</button>
                <button onclick="saveNewUser()" class="btn-primary">Add User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit User</div>
            <button class="modal-close" onclick="closeEditModal()">✕</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div><label class="form-label">Full Name</label><input type="text" class="form-input" id="editName"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div><label class="form-label">ID</label><input type="text" class="form-input" id="editId"></div>
                <div><label class="form-label">Role</label><select class="form-select" id="editRole"><option>Student</option><option>Teacher</option></select></div>
            </div>
            <div><label class="form-label">Email</label><input type="email" class="form-input" id="editEmail"></div>
            <div>
                <label class="form-label">Status</label>
                <select class="form-select" id="editStatus">
                    <option value="Active">Active</option>
                    <option value="Banned">Banned</option>
                </select>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-secondary" style="background:#334155;" onclick="closeEditModal()">Cancel</button>
            <button class="btn-primary" onclick="saveEdit()">Save Changes</button>
        </div>
    </div>
</div>
    
<!-- Logout Confirmation Popup -->
<div class="logout-popup-overlay" id="logoutPopup">
    <div class="logout-popup-modal">
        <div class="logout-icon-large">
            <img src="{{ asset('images/admin_icons/logout.png') }}" alt="Logout" style="width:32px; height:32px; filter: invert(27%) sepia(91%) saturate(5421%) hue-rotate(345deg) brightness(93%) contrast(93%);">
        </div>
        <h3 class="logout-popup-title">Log Out</h3>
        <p class="logout-popup-text">Are you sure you want to log out of the Admin panel?</p>
        <div class="logout-actions">
            <button class="logout-btn-cancel" onclick="hideLogoutPopup()">Cancel</button>
            <button class="logout-btn-confirm" onclick="AdminAuthAPI.logout()">Log Out</button>
        </div>
    </div>
</div>

<div class="logout-popup-overlay" id="deletePopup">
    <div class="logout-popup-modal">
        <div class="logout-icon-large">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
        </div>
        <h3 class="logout-popup-title">Delete User</h3>
        <p class="logout-popup-text">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="logout-actions">
            <button class="logout-btn-cancel" onclick="hideDeletePopup()">Cancel</button>
            <button class="logout-btn-confirm" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-api.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('js/admin-api.js') }}"></script> --}}

<script>
    let allUsers = [];
    let editTargetId = null;

    // 1. Initialize and Fetch Data
    async function init() {
        try {
            const res = await AdminUserAPI.getUsers();
            allUsers = res.data || [];
            renderTable(allUsers);
        } catch (err) {
            console.error(err);
            alert('Failed to load users: ' + err.message);
        }
    }

    // 2. Render the Table
    function renderTable(list) {
        const body = document.getElementById('usersBody');
        if (!list || list.length === 0) {
            body.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:30px;color:#64748b;">No users match your criteria.</td></tr>';
            return;
        }

        body.innerHTML = list.map(u => {
            const statusLabel = u.status || 'Active';
            // Make Active green, and Banned red!
            const badgeStyle = statusLabel === 'Banned' 
                ? 'color: #dc2626; border: 1px solid #f87171;' 
                : 'color: #16a34a; border: 1px solid #86efac;';

            return `
            <tr>
                <td style="font-weight:600; color: var(--text-dark);">${u.name}</td>
                <td style="color:#64748b;">${u.id}</td>
                <td style="color:#64748b;">${u.email}</td>
                <td><span class="role-badge">${u.role}</span></td>
                <td><span style="padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; ${badgeStyle}">${statusLabel}</span></td>
                <td style="display:flex;gap:6px;justify-content:flex-end;">
                    <button class="action-btn" onclick="openEditModal('${u.id}')" title="Edit User">
                        <img src="{{ asset('images/admin_icons/view.png') }}" style="width:14px;height:14px;">
                    </button>
                    <button class="action-btn del" onclick="promptDelete('${u.id}')" title="Delete User">
                        <img src="{{ asset('images/admin_icons/delete.png') }}" style="width:14px;height:14px; filter: invert(27%) sepia(91%) saturate(5421%) hue-rotate(345deg) brightness(93%) contrast(93%);">
                    </button>
                </td>
            </tr>
            `;
        }).join('');
    }

    // 3. Search and Filter
    function filterUsers() {
        const searchEl = document.getElementById('searchInput');
        const roleEl = document.getElementById('roleFilter');
        const statusEl = document.getElementById('statusFilter');

        const q = searchEl ? searchEl.value.toLowerCase() : '';
        const role = roleEl ? roleEl.value : '';
        const status = statusEl ? statusEl.value : '';

        const filtered = allUsers.filter(u =>
            (u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || u.id.toLowerCase().includes(q)) &&
            (!role || u.role === role) &&
            (!status || (u.status || 'Active') === status)
        );
        renderTable(filtered);
    }

    // 4. Add Modal Logic
    function openAddModal() { 
        // Use style.display = 'flex' to match the new modal structure
        document.getElementById('addUserModal').style.display = 'flex'; 
    }
    
    function closeAddModal() { 
        document.getElementById('addUserModal').style.display = 'none'; 
        // Clear inputs when closing
        document.getElementById('addFullName').value = '';
        document.getElementById('addEmail').value = '';
        document.getElementById('addPassword').value = '';
    }

    // Renamed to match the onclick="saveNewUser()" in the HTML
    async function saveNewUser() {
        const fullName = document.getElementById('addFullName').value.trim();
        const role     = document.getElementById('addRole').value;
        const password = document.getElementById('addPassword').value;
        const email    = document.getElementById('addEmail').value.trim();
        
        if (!fullName || !email || !password) return alert('Please fill in all fields (Name, Email, Password).');
        if (password.length < 6) return alert('Password must be at least 6 characters.');
        
        try {
            await AdminUserAPI.createUser({ 
                fullName: fullName, email: email, password: password, role: role 
            });
            
            const res = await AdminUserAPI.getUsers();
            allUsers = res.data || [];
            
            // CRITICAL FIX: Run the filters instead of blindly rendering everyone!
            filterUsers(); 
            
            // Inside async function saveNewUser()
            
            closeAddModal();
            
            // 🚨 Use the new UI upgrade!
            showToast('User successfully created!'); 
            
        } catch (err) {
            // You can even use it for errors!
            showToast('Failed to add user: ' + err.message, 'error');
        }
    }

    // 5. Edit Modal Logic
    function openEditModal(id) {
        editTargetId = id;
        const u = allUsers.find(user => user.id === id);
        if (!u) return;
        
        document.getElementById('editName').value = u.name;
        document.getElementById('editId').value = u.id;
        document.getElementById('editEmail').value = u.email;
        document.getElementById('editRole').value = u.role;
        document.getElementById('editStatus').value = u.status || 'Active';
        
        document.getElementById('editModal').classList.add('open');
    }
    
    function closeEditModal() { 
        document.getElementById('editModal').classList.remove('open'); 
    }
    
    async function saveEdit() {
        const editData = {
            name:   document.getElementById('editName').value.trim(),
            email:  document.getElementById('editEmail').value.trim(),
            role:   document.getElementById('editRole').value,
            status: document.getElementById('editStatus').value
        };
        try {
            await AdminUserAPI.updateUser(editTargetId, editData);
            
            // Refresh data from server
            const res = await AdminUserAPI.getUsers();
            allUsers = res.data || [];
            
            // CRITICAL FIX: Run the filters instead of blindly rendering everyone!
            filterUsers(); 
            
            closeEditModal();
        } catch (err) {
            alert('Update failed: ' + err.message);
        }
    }

    // 6. Delete Logic (New Custom UI)
    let userToDelete = null;

    function promptDelete(id) {
        userToDelete = id;
        document.getElementById('deletePopup').classList.add('show');
    }

    function hideDeletePopup() {
        userToDelete = null;
        document.getElementById('deletePopup').classList.remove('show');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
        if (!userToDelete) return;
        try {
            await AdminUserAPI.deleteUser(userToDelete);
            allUsers = allUsers.filter(u => u.id !== userToDelete);
            filterUsers();
            
            hideDeletePopup();
            showToast('User deleted successfully!'); // Reuses your awesome toast notification!
        } catch (err) {
            alert('Delete failed: ' + err.message);
        }
    });

    // 7. Event Listeners & UI Toggles
    window.addEventListener('DOMContentLoaded', init);
    
    // Add event listeners for filters if they exist
    if(document.getElementById('searchInput')) document.getElementById('searchInput').addEventListener('keyup', filterUsers);
    if(document.getElementById('roleFilter')) document.getElementById('roleFilter').addEventListener('change', filterUsers);
    if(document.getElementById('statusFilter')) document.getElementById('statusFilter').addEventListener('change', filterUsers);

    /* ── Theme toggle ── */
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        updateThemeIcon(isDark);
    }
    function updateThemeIcon(isDark) {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        const moon = btn.querySelector('.theme-icon-moon');
        const sun  = btn.querySelector('.theme-icon-sun');
        if (moon) moon.style.display = isDark ? 'none' : '';
        if (sun)  sun.style.display  = isDark ? '' : 'none';
    }
    
    (function() {
        const saved = localStorage.getItem('darkMode');
        if (saved === 'enabled') {
            document.body.classList.add('dark-mode');
            updateThemeIcon(true);
        }
    })();
    
    /* ── Sidebar and Logout ── */
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('expanded');
    }
    function showLogoutPopup() {
        document.getElementById('logoutPopup').classList.add('show');
    }
    function hideLogoutPopup() {
        document.getElementById('logoutPopup').classList.remove('show');
    }

    // ==========================================
    // AMAZING UI UPGRADE: Toast Notification
    // ==========================================
    function showToast(message, type = 'success') {
        // 1. Create a container if it doesn't exist
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;';
            document.body.appendChild(container);
        }

        // 2. Detect Light/Dark Mode for styling
        const isDark = document.body.classList.contains('dark-mode');
        const bg = isDark ? '#1e293b' : '#ffffff';
        const text = isDark ? '#f8fafc' : '#1e293b';
        const border = isDark ? '#334155' : '#e2e8f0';
        const iconColor = type === 'success' ? '#10b981' : '#ef4444';
        
        // Animated SVG Icon
        const icon = type === 'success' 
            ? `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="${iconColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`
            : `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="${iconColor}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;

        // 3. Build the beautiful Toast card
        const toast = document.createElement('div');
        toast.style.cssText = `
            background: ${bg};
            color: ${text};
            border: 1px solid ${border};
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        `;

        toast.innerHTML = `${icon} <span>${message}</span>`;
        container.appendChild(toast);

        // 4. Trigger the Slide-In Animation
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 10);

        // 5. Trigger the Slide-Out Animation and delete after 3.5 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 3500);
    }
</script>
</body>
</html>
