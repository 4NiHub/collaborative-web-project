<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teachers - Smart University System</title>
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

        /* tooltips */
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

        /* Top bar*/
        .top-bar {
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-height: 10px;
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

        body.dark-mode .top-bar {
            background: #1e293b;
            color: #e2e8f0;
        }

        body.dark-mode .page-title {
            color: #f1f5f9;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .page-header p {
            font-size: 13px;
            color: #64748b;
        }

        body.dark-mode .page-header h2 {
            color: #f1f5f9;
        }

        .list-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 14px;
            width: 210px;
            transition: border-color 0.2s;
        }

        .search-box:focus-within {
            border-color: #2563eb;
        }

        .search-box svg {
            width: 16px;
            height: 16px;
            fill: #94a3b8;
            flex-shrink: 0;
        }

        .search-box input {
            border: none;
            background: transparent;
            font-size: 13px;
            color: #1e293b;
            font-family: inherit;
            width: 100%;
            outline: none;
        }

        .search-box input::placeholder {
            color: #94a3b8;
        }

        body.dark-mode .search-box {
            background: #1e293b;
            border-color: #334155;
        }

        body.dark-mode .search-box input {
            color: #e2e8f0;
        }

        .sort-select {
            padding: 9px 14px 9px 32px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: white url('images/arrow_down.png') no-repeat 10px center;
            background-size: 12px 8px;
            color: #1e293b;
            font-size: 13px;
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }

        .sort-select:focus {
            border-color: #2563eb;
        }

        body.dark-mode .sort-select {
            background: #1e293b url('images/arrow_down.png') no-repeat 10px center;
            background-size: 12px 8px;
            border-color: #334155;
            color: #e2e8f0;
        }

        /* Teacher grid */
        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .teacher-card {
            background: white;
            border-radius: 12px;
            padding: 18px 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1.5px solid transparent;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
        }

        .teacher-card:hover {
            border-color: #2563eb;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.12);
            transform: translateY(-2px);
        }

        body.dark-mode .teacher-card {
            background: #1e293b;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .teacher-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 700;
            position: relative;
        }

        .teacher-info {
            flex: 1;
            min-width: 0;
        }

        .teacher-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        body.dark-mode .teacher-name {
            color: #f1f5f9;
        }

        .teacher-subject {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .view-profile-btn {
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            transition: gap 0.2s;
        }

        .view-profile-btn:hover {
            gap: 7px;
        }

        .view-profile-btn svg {
            width: 12px;
            height: 12px;
            fill: currentColor;
        }

        

        /* Pagi*/
        .pagination-bar {
            background: white;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        body.dark-mode .pagination-bar {
            background: #1e293b;
        }

        .pagination-info {
            font-size: 13px;
            color: #64748b;
        }

        .pagination-info strong {
            color: #1e293b;
        }

        body.dark-mode .pagination-info strong {
            color: #f1f5f9;
        }

        .pagination-btns {
            display: flex;
            gap: 6px;
        }

        .page-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            background: transparent;
            color: #1e293b;
            font-size: 13px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .page-btn:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        .page-btn.active {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .page-btn svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        body.dark-mode .page-btn {
            border-color: #334155;
            color: #e2e8f0;
        }

        #profileView {
            width: 100%;
            display: none;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        .back-btn svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        body.dark-mode .back-btn {
            background: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }

        .profile-layout {
            display: grid !important;
            grid-template-columns: 260px 1fr !important;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 600px) {
            .profile-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Card*/
        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 16px;
        }

        body.dark-mode .card {
            background: #1e293b;
            color: #e2e8f0;
        }

        /* Identity card */
        .identity-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .p-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 14px;
            position: relative;
            border: 4px solid #dbeafe;
        }
        .p-name {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        body.dark-mode .p-name {
            color: #f1f5f9;
        }

        .p-badge {
            display: inline-block;
            padding: 4px 14px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        body.dark-mode .p-badge {
            background: #1e3a5f;
            color: #60a5fa;
        }

        .p-divider {
            width: 100%;
            height: 1px;
            background: #f1f5f9;
            margin-bottom: 12px;
        }

        body.dark-mode .p-divider {
            background: #334155;
        }

        .p-contact-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            color: #64748b;
            width: 100%;
            text-align: left;
        }

        body.dark-mode .p-contact-row {
            border-bottom-color: #334155;
        }

        .p-contact-row:last-child {
            border-bottom: none;
        }

        .p-contact-row svg {
            width: 15px;
            height: 15px;
            fill: #2563eb;
            flex-shrink: 0;
        }

        /* General info */
        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
        }

        body.dark-mode .card-title {
            color: #f1f5f9;
        }

        .info-row {
            flex-wrap: wrap; 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            gap: 12px;
        }

        body.dark-mode .info-row {
            border-bottom-color: #334155;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #94a3b8;
            flex-shrink: 0;
        }

        .info-value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
            max-width: 60%; 
        }

        body.dark-mode .info-value {
            color: #e2e8f0;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 22px;
        }

        body.dark-mode .tabs {
            border-bottom-color: #334155;
        }

        .tab-btn {
            padding: 10px 18px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            color: #64748b;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color 0.2s, border-color 0.2s;
            white-space: nowrap;
        }

        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-btn:hover {
            color: #2563eb;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }


        

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 28px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 6px;
            bottom: 6px;
            width: 2px;
            background: #e2e8f0;
            border-radius: 2px;
        }

        body.dark-mode .timeline::before {
            background: #334155;
        }

        .tl-item {
            position: relative;
            margin-bottom: 24px;
        }

        .tl-item:last-child {
            margin-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -24px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #2563eb;
            border: 2.5px solid white;
            flex-shrink: 0;
        }

        body.dark-mode .tl-dot {
            border-color: #1e293b;
        }

        .tl-dot.green { background: #22c55e; }
        .tl-dot.amber { background: #f59e0b; }
        .tl-dot.purple { background: #8b5cf6; }

        .tl-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .tl-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        body.dark-mode .tl-title {
            color: #f1f5f9;
        }

        .tl-org {
            font-size: 13px;
            color: #2563eb;
            font-weight: 600;
        }

        .tl-date {
            font-size: 11px;
            font-weight: 600;
            color: white;
            background: #2563eb;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tl-date.green { background: #22c55e; }
        .tl-date.amber { background: #f59e0b; }
        .tl-date.purple { background: #8b5cf6; }

        .tl-desc {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            margin-top: 4px;
        }

        /* Education items */
        .study-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: border-color 0.2s;
        }

        .study-item:hover {
            border-color: #2563eb;
        }

        body.dark-mode .study-item {
            border-color: #334155;
            background: #0f172a;
        }

        .study-logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 800;
        }

        .study-degree {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        body.dark-mode .study-degree {
            color: #f1f5f9;
        }

        .study-school {
            font-size: 12px;
            color: #2563eb;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .study-period {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Roles & projects items */
        .role-item {
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: border-color 0.2s;
        }

        .role-item:hover {
            border-color: #2563eb;
        }

        body.dark-mode .role-item {
            border-color: #334155;
            background: #0f172a;
        }

        .role-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 6px;
        }

        .role-title {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        body.dark-mode .role-title {
            color: #f1f5f9;
        }

        .role-org {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .role-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .rb-blue   { background: #dbeafe; color: #1d4ed8; }
        .rb-green  { background: #dcfce7; color: #166534; }
        .rb-amber  { background: #fef9c3; color: #854d0e; }
        .rb-purple { background: #ede9fe; color: #6d28d9; }

        .role-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
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
                <div class="sidebar-icon active" data-page="teachers" data-tooltip="Teachers">
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


            
            <div id="listView">
                <div class="page-header">
                    <h2>Teachers</h2>
                    <p>Browse all faculty members and their departments</p>
                </div>

                <div class="list-controls">
                    <div class="search-box">
                        <img src="{{ asset('images/search.png') }}" style="width: 15px; height: 15px;display: block; margin-left: 8 px;">
                        <input type="text" placeholder="Search here..." id="searchInput">
                    </div>
                    <select class="sort-select" id="deptFilter">
                        <option value="">All Departments</option>
                    </select>
                </div>

                <div class="teachers-grid" id="teachersGrid">
                    <div class="grid-state">Loading teachers...</div>
                </div>
                
        

                <div class="pagination-bar">
                    <div class="pagination-info">
                        Showing <strong id="showRange">-</strong> from <strong id="totalCount">-</strong> 
                    </div>
                    <div class="pagination-btns" id="pageBtns">
                        <button class="page-btn" id="btnPrev" onclick="changePage(-1)">
                           <img src="{{ asset('images/arrow_back_blue.png') }}" style="width: 15px; height: 10px;display: block; margin-left: 5px;">
                        </button>
                        <button class="page-btn" id="btnNext" onclick="changePage(1)">
                            <img src="{{ asset('images/arrow_forward_blue.png') }}" style="width:15px;height:10px;display:block;margin-left:5px;">
                        </button>
                    </div>
                </div>

            </div>

            
            <div id="profileView">

                <button class="back-btn" onclick="showList()">
                    <img src="{{ asset('images/arrow_back_blue.png') }}" style="width: 15px; height: 10px;display: block; margin-left: 5px;">
                    Back to Teachers
                </button>

                <div class="profile-layout">

                    
                    <div>
                        <div class="card identity-card">
                            <div class="p-avatar" id="pAvatar">-</div>
                            <div class="p-name" id="pName">—</div>
                            <div class="p-badge" id="pBadge">—</div>
                            <div class="p-divider"></div>
                            <div id="pContacts" style="width: 100%;"></div>
                        </div>

                        <div class="card">
                            <h3 class="card-title">General Information</h3>
                            
                            <div class="info-row">
                                <span class="info-label">Department</span>
                                <span class="info-value" id="pDept">—</span>
                            </div>                            
                            <div class="info-row">
                                <span class="info-label">Office</span>
                                <span class="info-value" id="pOffice">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Office Hours</span>
                                <span class="info-value" id="pHours">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Subjects</span>
                                <span class="info-value" id="pSubjects">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nationality</span>
                                <span class="info-value" id="pNat">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Languages</span>
                                <span class="info-value" id="pLang">—</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="card">
                            <div class="tabs">
                                <button class="tab-btn active" onclick="switchTab('experience')">Experience</button>
                                <button class="tab-btn"        onclick="switchTab('education')">Education</button>
                                <button class="tab-btn"        onclick="switchTab('roles')">Roles &amp; Projects</button>
                            </div>
                            <div class="tab-panel active" id="tab-experience">
                                <div class="timeline" id="expTimeline"></div>
                            </div>
                            <div class="tab-panel" id="tab-education">
                                <div id="eduList"></div>
                            </div>
                            <div class="tab-panel" id="tab-roles">
                                <div id="rolesList"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>

    <script src="{{ asset('js/api.js') }}?v={{ time() }}"></script>
    {{-- <script src="{{ asset('js/api.js') }}"></script> --}}

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

            // Save pref
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
        document.querySelectorAll('.sidebar-icon[data-page]').forEach(function(icon){
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

        var COLORS = ['#2563eb','#7c3aed','#0891b2','#059669','#db2777','#d97706','#dc2626','#0284c7'];
        var allTeachers = [];
        var filtered = [];
        var currentPage = 1;
        var perPage = 10;

        async function loadTeachers() {
            const grid = document.getElementById('teachersGrid');
            try {
                const res = await TeacherAPI.getTeachers();
                allTeachers = res.data || [];
                filtered = [...allTeachers];
                buildDeptFilter();
                
                document.getElementById('searchInput').addEventListener('input', applyFilter);
                document.getElementById('deptFilter').addEventListener('change', applyFilter);
                
                renderList();

                // --- NEW: Check the URL for an ID and open the profile automatically ---
                const urlParams = new URLSearchParams(window.location.search);
                const targetTeacherId = urlParams.get('id');
                
                if (targetTeacherId) {
                    showProfile(parseInt(targetTeacherId)); 
                }

            } catch (err) {
                console.error('[Teachers] Load failed:', err);
                grid.innerHTML = `<div class="grid-state error">Could not load teachers.<br>Please refresh.</div>`;
            }
        }
        
        function buildDeptFilter() {
            var seen = {};
            allTeachers.forEach(function(t) {
                if (t.department && !seen[t.department]) {
                    seen[t.department] = true;
                }
            });
            var sel = document.getElementById('deptFilter');
            Object.keys(seen).sort().forEach(function(dept) {
                var opt = document.createElement('option');
                opt.value = dept;
                opt.textContent = dept;
                sel.appendChild(opt);
            });
        }
        
        function applyFilter() {
            var q    = document.getElementById('searchInput').value.toLowerCase().trim();
            var dept = document.getElementById('deptFilter').value;

            filtered = allTeachers.filter(function(t) {
                var fullName = ((t.firstName || '') + ' ' + (t.lastName || '')).toLowerCase();
                var matchQ    = !q || fullName.indexOf(q) !== -1 ||
                                (t.department || '').toLowerCase().indexOf(q) !== -1;
                var matchDept = !dept || t.department === dept;
                return matchQ && matchDept;
            });

            currentPage = 1;
            renderList();
        }

        // render list – CLEAN & SAFE
        function renderList() {
            var grid  = document.getElementById('teachersGrid');
            var start = (currentPage - 1) * perPage;
            var slice = filtered.slice(start, start + perPage);

            if (slice.length === 0) {
                grid.innerHTML = '<div class="grid-state">No teachers match your search.</div>';
                updatePagination(0, 0, 0);
                return;
            }

            grid.innerHTML = '';

            slice.forEach(function(t, idx) {
                var fullName = (t.title ? t.title + ' ' : '') + (t.firstName || '') + ' ' + (t.lastName || '');
                var initials = ((t.firstName || ' ')[0] + (t.lastName || ' ')[0]).toUpperCase();
                var color    = COLORS[(start + idx) % COLORS.length];

                // FIXED: clean join, no quotes, no braces
                var subjectText = Array.isArray(t.subjects) && t.subjects.length > 0
                    ? t.subjects.join(', ')
                    : (t.department || '—');

                var card = document.createElement('div');
                card.className = 'teacher-card';
                card.setAttribute('data-id', t.id);

                card.innerHTML = `
                    <div style="flex-shrink:0;">
                        <div class="teacher-avatar" style="background:${color};">${initials}</div>
                    </div>
                    <div class="teacher-info">
                        <div class="teacher-name">${fullName}</div>
                        <div class="teacher-subject">${subjectText}</div>
                        <button class="view-profile-btn">View profile 
                            <img src="{{ asset('images/arrow_forward_blue.png') }}" style="width:10px;height:8px;margin-left:1px;">
                        </button>
                    </div>
                `;

                card.addEventListener('click', function() {
                    showProfile(parseInt(this.getAttribute('data-id')));
                });

                grid.appendChild(card);
            });

            var total = filtered.length;
            updatePagination(start + 1, Math.min(start + perPage, total), total);
        }

        function updatePagination(from, to, total) {
            document.getElementById('showRange').textContent  = total === 0 ? '0' : from + '–' + to;
            document.getElementById('totalCount').textContent = total;

            var maxPage  = Math.ceil(total / perPage) || 1;
            var pageBtns = document.getElementById('pageBtns');

            
            pageBtns.querySelectorAll('.num-btn').forEach(function(b) { b.remove(); });

            var btnNext = document.getElementById('btnNext');
            for (var p = 1; p <= maxPage; p++) {
                var btn = document.createElement('button');
                btn.className = 'page-btn num-btn' + (p === currentPage ? ' active' : '');
                btn.textContent = p;
                (function(pg) {
                    btn.addEventListener('click', function() { goPage(pg); });
                })(p);
                pageBtns.insertBefore(btn, btnNext);
            }
        }

        function changePage(dir) {
            const max = Math.ceil(filtered.length / perPage) || 1;
            currentPage = Math.max(1, Math.min(max, currentPage + dir));
            renderList();
        }
        
        async function showProfile(id) {
            try {
                const res = await TeacherAPI.getTeacherProfile(id);
                const t = res.data;

                // In showProfile(), replace the subjects line with:
                let subjectsDisplay = '—';
                if (Array.isArray(t.subjects) && t.subjects.length > 0) {
                    subjectsDisplay = t.subjects.join(', ');
                } else if (typeof t.subjects === 'string' && t.subjects.trim()) {
                    subjectsDisplay = t.subjects.trim(); // in case backend sends "Data Structures" as string
                }

                // Avatar
                const initials = (t.firstName[0] + t.lastName[0]).toUpperCase();
                document.getElementById('pAvatar').textContent = initials;
                document.getElementById('pAvatar').style.background = '#2563eb';

                document.getElementById('pName').textContent = `${t.title || 'Dr.'} ${t.firstName} ${t.lastName}`;
                document.getElementById('pBadge').textContent = t.department || '—';

                // Contacts
                document.getElementById('pContacts').innerHTML = `
                    <div class="p-contact-row"><img src="{{ asset('images/mail_blue.png') }}" style="width:20px;height:20px;"> ${t.email}</div>
                    <div class="p-contact-row"><img src="{{ asset('images/call.png') }}" style="width:20px;height:20px;"> ${t.phone}</div>
                `;

                // General info
                document.getElementById('pDept').textContent = t.department || '—';
                document.getElementById('pOffice').textContent = t.officeLocation || '—';
                document.getElementById('pHours').textContent = t.officeHours || '—';
                document.getElementById('pSubjects').textContent = subjectsDisplay;
                document.getElementById('pNat').textContent = t.nationality || '—';
                document.getElementById('pLang').textContent = t.languages || '—';

                // Tabs
                renderTimeline(t.experience || []);
                renderEducation(t.education || []);
                renderRoles(t.roles || []);

                document.getElementById('listView').style.display = 'none';
                document.getElementById('profileView').style.display = 'block';
            } catch (err) {
                console.error('Profile load failed:', err);
            }
        }

        function renderTimeline(exp) {
            let html = '';
            exp.forEach(e => {
                html += `
                    <div class="tl-item">
                        <span class="tl-dot ${e.color || 'blue'}"></span>
                        <div class="tl-header">
                            <div>
                                <div class="tl-title">${e.title}</div>
                                <div class="tl-org">${e.org}</div>
                            </div>
                            <span class="tl-date ${e.color || 'blue'}">${e.period}</span>
                        </div>
                        <div class="tl-desc">${e.desc || ''}</div>
                    </div>`;
            });
            document.getElementById('expTimeline').innerHTML = html || '<p>No experience data.</p>';
        }

        function renderEducation(edu) {
            let html = '';
            edu.forEach(e => {
                html += `
                    <div class="study-item">
                        <div class="study-logo" style="background:#2563eb;">${(e.school || '??').substring(0,2).toUpperCase()}</div>
                        <div>
                            <div class="study-degree">${e.degree}</div>
                            <div class="study-school">${e.school}</div>
                            <div class="study-period">${e.period}</div>
                        </div>
                    </div>`;
            });
            document.getElementById('eduList').innerHTML = html || '<p>No education data.</p>';
        }

        function renderRoles(roles) {
            let html = '';
            roles.forEach(r => {
                html += `
                    <div class="role-item">
                        <div class="role-top">
                            <div>
                                <div class="role-title">${r.title}</div>
                                <div class="role-org">${r.org}</div>
                            </div>
                            <span class="role-badge rb-blue">${r.status || 'Current'}</span>
                        </div>
                        <div class="role-desc">${r.desc || ''}</div>
                    </div>`;
            });
            document.getElementById('rolesList').innerHTML = html || '<p>No roles data.</p>';
        }

        function showList() {
            document.getElementById('profileView').style.display = 'none';
            document.getElementById('listView').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function switchTab(name) {
            var names = ['experience', 'education', 'roles'];
            document.querySelectorAll('.tab-btn').forEach(function(b, i) {
                b.classList.toggle('active', names[i] === name);
            });
            document.querySelectorAll('.tab-panel').forEach(function(p) {
                p.classList.toggle('active', p.id === 'tab-' + name);
            });
        }

       
        loadTeachers();
    </script>
</body>
</html>