<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Modules - Smart University System</title>
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
        .sidebar:not(.expanded) .sidebar-icon:nth-child(1):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(2):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(3):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(4):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(5):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(6):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(7):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(8):hover::before { opacity: 1; }
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::after,
        .sidebar:not(.expanded) .sidebar-icon:nth-child(9):hover::before { opacity: 1; }
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

        body.dark-mode .sidebar { background: #1e293b; }
        body.dark-mode .sidebar-icon::after { background: #334155; }
        body.dark-mode .sidebar-icon::before { border-right-color: #334155; }

        .logout-icon {
            margin-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 0px;
        }
        .logout-icon:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        body.dark-mode .logout-icon { border-top-color: rgba(255, 255, 255, 0.05); }

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
        .logo-dark  { display: none; }
        body.dark-mode .logo-light { display: none; }
        body.dark-mode .logo-dark  { display: block; }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }

        .page-header { margin-bottom: 32px; }
        .page-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .page-header p  { color: #64748b; font-size: 14px; }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .module-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s;
        }
        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        .module-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .module-icon {
            width: 40px; height: 40px;
            background: #2563eb;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white;
        }
        .module-credits {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .module-title { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
        .module-code  { color: #64748b; font-size: 13px; margin-bottom: 16px; }

        .progress-section { margin-bottom: 20px; }
        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .progress-text  { color: #64748b; }
        .progress-value { font-weight: 600; }
        .progress-bar {
            width: 100%; height: 8px;
            background: #e2e8f0;
            border-radius: 10px; overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            border-radius: 10px;
            transition: width 0.3s;
        }
        .module-footer {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }
        .module-meta { display: flex; gap: 16px; font-size: 13px; color: #64748b; }
        .meta-item   { display: flex; align-items: center; gap: 4px; }

        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 16px;
            max-width: 900px; width: 100%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-close {
            width: 32px; height: 32px;
            border: none; background: #f1f5f9;
            border-radius: 8px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; font-size: 18px;
        }
        .modal-close:hover { background: #e2e8f0; }
        .modal-body { padding: 24px; }

        body.dark-mode .modal-content          { background: #1e293b; color: #e2e8f0; }
        body.dark-mode .modal-header           { border-bottom-color: #334155; }
        body.dark-mode .modal-close            { background: #334155; color: #e2e8f0; }
        body.dark-mode .modal-close:hover      { background: #475569; }
        body.dark-mode .tab-nav                { border-bottom-color: #334155; }
        body.dark-mode .tab-btn                { color: #94a3b8; }
        body.dark-mode .tab-btn.active         { color: #60a5fa; border-bottom-color: #60a5fa; }
        body.dark-mode .instructor-card        { background: #0f172a; }
        body.dark-mode .assignment-item        { border-color: #334155; background: #1e293b; }
        body.dark-mode .schedule-item          { background: #0f172a; }
        body.dark-mode .material-link          { background: #1e293b; border-color: #334155; color: #94a3b8; }
        body.dark-mode .material-link:hover    { border-color: #60a5fa; color: #60a5fa; }
        body.dark-mode .resource-card          { border-color: #334155; background: #1e293b; }
        body.dark-mode .resource-card:hover    { border-color: #60a5fa; background: #0f172a; }
        body.dark-mode .resource-icon          { background: #334155; }
        body.dark-mode .detail-text,
        body.dark-mode .resource-type          { color: #94a3b8; }

        .detail-section { margin-bottom: 32px; }
        .detail-section h3 { font-size: 16px; font-weight: 600; margin-bottom: 16px; }
        .detail-text { color: #475569; line-height: 1.6; font-size: 14px; }

        .instructor-card {
            display: flex; align-items: center; gap: 16px;
            padding: 16px; background: #f8fafc;
            border-radius: 10px; margin-bottom: 16px;
        }
        .instructor-avatar {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 20px;
            flex-shrink: 0;
        }
        .instructor-info h4 { font-weight: 600; margin-bottom: 4px; }
        .instructor-info p  { color: #64748b; font-size: 13px; }
        .contact-info {
            display: flex; flex-direction: column;
            gap: 8px; margin-top: 8px;
            font-size: 13px; color: #64748b;
        }
        .contact-item { display: flex; align-items: center; gap: 8px; }

        .assignments-list { display: flex; flex-direction: column; gap: 12px; }
        .assignment-item {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .assignment-info h4 { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
        .assignment-info p  { color: #64748b; font-size: 13px; }

        .status-badge {
            padding: 6px 12px; border-radius: 6px;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
        }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-pending   { background: #fee2e2; color: #991b1b; }
        .status-upcoming  { background: #e0e7ff; color: #3730a3; }

        .tab-nav {
            display: flex; gap: 12px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .tab-btn {
            padding: 12px 0;
            border: none; background: transparent;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            font-size: 14px; font-weight: 500;
            color: #64748b; cursor: pointer; transition: all 0.2s;
        }
        .tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .schedule-list { display: flex; flex-direction: column; gap: 12px; }
        .schedule-item {
            display: flex; gap: 16px;
            padding: 12px; background: #f8fafc; border-radius: 8px;
        }
        .schedule-week { font-weight: 600; color: #2563eb; min-width: 80px; }
        .schedule-content { flex: 1; }
        .schedule-title { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
        .schedule-materials { display: flex; gap: 12px; margin-top: 8px; }
        .material-link {
            display: flex; align-items: center; gap: 4px;
            padding: 4px 10px; background: white;
            border: 1px solid #e2e8f0; border-radius: 6px;
            font-size: 12px; color: #64748b;
            text-decoration: none; cursor: pointer;
        }
        .material-link:hover { border-color: #2563eb; color: #2563eb; }

        .resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }
        .resource-card {
            padding: 16px; border: 1px solid #e2e8f0;
            border-radius: 10px; cursor: pointer; transition: all 0.2s;
        }
        .resource-card:hover { border-color: #2563eb; background: #f8fafc; }
        .resource-icon {
            width: 40px; height: 40px; background: #f1f5f9;
            border-radius: 8px; display: flex; align-items: center;
            justify-content: center; margin-bottom: 12px; color: #64748b;
        }
        .resource-title { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
        .resource-type  { color: #64748b; font-size: 12px; }

        .assignment-actions {
            display: flex; flex-direction: column;
            align-items: flex-end; gap: 8px;
        }
        .btn-submit {
            padding: 7px 16px; background: #2563eb;
            color: white; border: none; border-radius: 6px;
            font-size: 12px; font-weight: 600; cursor: pointer;
            transition: background 0.2s; white-space: nowrap;
        }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-retake {
            padding: 7px 16px; background: transparent;
            color: #d97706; border: 1.5px solid #d97706;
            border-radius: 6px; font-size: 12px; font-weight: 600;
            cursor: pointer; transition: all 0.2s; white-space: nowrap;
        }
        .btn-retake:hover { background: #fef3c7; border-color: #b45309; color: #b45309; }

        .submit-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 2000;
            align-items: center; justify-content: center;
            padding: 20px;
        }
        .submit-modal-overlay.active { display: flex; }
        .submit-modal {
            background: white; border-radius: 14px;
            padding: 28px 32px; max-width: 440px; width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .submit-modal h3 { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
        .submit-modal .submit-modal-meta { font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .submit-modal label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .submit-modal textarea {
            width: 100%; min-height: 100px;
            border: 1.5px solid #e2e8f0; border-radius: 8px;
            padding: 10px 12px; font-size: 14px;
            font-family: var(--font); resize: vertical;
            box-sizing: border-box; transition: border-color 0.2s;
        }
        .submit-modal textarea:focus { outline: none; border-color: #2563eb; }
        .submit-modal-actions {
            display: flex; justify-content: flex-end;
            gap: 10px; margin-top: 20px;
        }
        .btn-cancel-modal {
            padding: 9px 18px; border: 1.5px solid #e2e8f0;
            background: white; border-radius: 7px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; color: #64748b; transition: all 0.2s;
        }
        .btn-cancel-modal:hover { background: #f1f5f9; }
        .btn-confirm-submit {
            padding: 9px 18px; background: #2563eb;
            color: white; border: none; border-radius: 7px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-confirm-submit:hover    { background: #1d4ed8; }
        .btn-confirm-submit:disabled { background: #93c5fd; cursor: not-allowed; }
        .submit-success-msg { text-align: center; padding: 20px 0 4px; }
        .submit-success-msg img { width: 44px; height: 44px; margin: 0 auto 12px; display: block; }
        .submit-success-msg p { color: #16a34a; font-weight: 600; font-size: 15px; }

        body.dark-mode .submit-modal           { background: #1e293b; color: #e2e8f0; }
        body.dark-mode .submit-modal textarea  { background: #0f172a; border-color: #334155; color: #e2e8f0; }
        body.dark-mode .btn-cancel-modal       { background: #334155; border-color: #475569; color: #e2e8f0; }
        body.dark-mode .btn-cancel-modal:hover { background: #475569; }
        body.dark-mode .btn-retake:hover       { background: #451a03; }

        .file-upload-area {
            position: relative;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            margin-top: 6px;
        }
        .file-upload-area:hover { border-color: #2563eb; background: #eff6ff; }
        .file-upload-area.drag-over { border-color: #2563eb; background: #eff6ff; }
        .file-upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .file-upload-label { pointer-events: none; }
        .file-upload-label span { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 4px; }
        .file-upload-label small { font-size: 11px; color: #94a3b8; }

        .file-selected {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            padding: 8px 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 7px;
            font-size: 13px;
            color: #166534;
        }
        .file-selected span { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .file-remove-btn {
            background: none; border: none; cursor: pointer;
            color: #64748b; font-size: 14px; padding: 0 4px;
            line-height: 1;
        }
        .file-remove-btn:hover { color: #dc2626; }

        body.dark-mode .file-upload-area { border-color: #334155; }
        body.dark-mode .file-upload-area:hover,
        body.dark-mode .file-upload-area.drag-over { background: #1e3a5f; border-color: #3b82f6; }
        body.dark-mode .file-upload-label span { color: #94a3b8; }
        body.dark-mode .file-selected { background: #14532d; border-color: #166634; color: #bbf7d0; }

        body.dark-mode .top-bar,
        body.dark-mode .module-card     { background: #1e293b; color: #e2e8f0; }
        body.dark-mode .module-title,
        body.dark-mode .page-title,
        body.dark-mode h1,
        body.dark-mode h3,
        body.dark-mode h4               { color: #f1f5f9; }
        body.dark-mode .module-card     { border: 1px solid #334155; }
        body.dark-mode .progress-bar    { background: #334155; }
        body.dark-mode .module-footer   { border-top-color: #334155; }

        @media (max-width: 768px) {
            .modules-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 24px;
            }
            .resources-grid { grid-template-columns: 1fr; }
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
                <div class="sidebar-icon active" data-page="modules" data-tooltip="My Modules">
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

        <main class="main-content">
            <div class="top-bar">
                <div class="logo-container">
                    <img src="{{ asset('images/sus_logo.png') }}" alt="SuS" class="logo-light">
                    <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SuS" class="logo-dark">
                </div>
                <h1 class="page-title">Smart University System</h1>
            </div>

            <div class="page-header">
                <h1>My Modules</h1>
                <p>Track your enrolled courses and progress</p>
            </div>

            <div class="modules-grid" id="modulesGrid">
                <div class="state-msg" id="loadingMsg">Loading modules...</div>
            </div>
        </main>
    </div>

    <div class="modal" id="moduleModal">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 id="modalTitle" style="font-size:20px;font-weight:700;margin-bottom:4px;">Loading...</h2>
                    <p class="modal-header-meta" id="modalMeta">—</p>
                </div>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <div class="modal-body">

                <div class="tab-nav">
                    <button class="tab-btn active" data-modal-tab="overview">Overview</button>
                    <button class="tab-btn" data-modal-tab="schedule">Schedule</button>
                    <button class="tab-btn" data-modal-tab="resources">Resources</button>
                </div>

                <div class="tab-content active" id="overview-tab">
                    <div class="detail-section">
                        <h3>Module Description</h3>
                        <p class="detail-text" id="modalDescription">—</p>
                    </div>

                    <div class="detail-section">
                        <h3>Module Leader</h3>
                        <div class="instructor-card">
                            <div class="instructor-avatar" id="instructorAvatar">?</div>
                            <div class="instructor-info">
                                <h4 id="instructorName">-</h4>
                                <p id="instructorDept">—</p>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <img src="{{ asset('images/mail.png') }}" style="width:20px;height:20px;display:block;margin-left:3px;">
                                        <span id="instructorEmail">—</span>
                                    </div>
                                    <div class="contact-item">
                                        <img src="{{ asset('images/pin.png') }}" style="width:20px;height:20px;display:block;margin-left:3px;">
                                        <span id="instructorRoom">—</span>
                                    </div>
                                    <div class="contact-item">
                                        <img src="{{ asset('images/clock.png') }}" style="width:20px;height:20px;display:block;margin-left:3px;">
                                        <span id="instructorHours">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Assignments</h3>
                        <div class="assignments-list" id="assignmentList">
                            <p style="color:#64748b;font-size:14px;">Loading assessments...</p>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="schedule-tab">
                    <div class="detail-section">
                        <h3>Weekly Schedule</h3>
                        <p class="detail-text" style="margin-bottom:20px;">Lectures, workshops and materials for the semester</p>
                        <div class="schedule-list" id="scheduleList">
                            <p style="color:#64748b;font-size:14px;">Schedule information will be available here.</p>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="resources-tab">
                    <div class="detail-section">
                        <h3>Learning Resources</h3>
                        <div class="resources-grid" id="resourcesGrid">
                            <p style="color:#64748b;font-size:14px;">Loading resources...</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="submit-modal-overlay" id="submitModalOverlay">
        <div class="submit-modal">
            <h3 id="submitModalTitle">Submit Assignment</h3>
            <p class="submit-modal-meta" id="submitModalMeta">—</p>

            <div id="submitModalForm">
                <label for="submissionNotes">Notes / Comments (optional)</label>
                <textarea id="submissionNotes" placeholder="Add any notes for your submission…"></textarea>

                <label for="submissionFile" style="margin-top:16px;">Attach File</label>
                <div class="file-upload-area" id="fileUploadArea">
                    <input type="file" id="submissionFile" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                    <div class="file-upload-label">
                        <img src="{{ asset('images/doc.png') }}" style="width:24px;height:24px;display:block;margin:0 auto 8px;">
                        <span>Click to upload or drag and drop</span>
                        <small>PDF, DOC, DOCX, PPT, PPTX, ZIP</small>
                    </div>
                </div>
                <div class="file-selected" id="fileSelected" style="display:none;">
                    <img src="{{ asset('images/check_circle.png') }}" style="width:16px;height:16px;">
                    <span id="fileSelectedName"></span>
                    <button class="file-remove-btn" onclick="removeFile()">✕</button>
                </div>

                <div class="submit-modal-actions">
                    <button class="btn-cancel-modal" onclick="closeSubmitModal()">Cancel</button>
                    <button class="btn-confirm-submit" id="confirmSubmitBtn">Submit</button>
                </div>
            </div>

            <div class="submit-success-msg" id="submitSuccessMsg" style="display:none;">
                <img src="{{ asset('images/check_circle.png') }}" alt="Success">
                <p id="submitSuccessText">Submitted successfully!</p>
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
            localStorage.setItem('darkMode',
                document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });

        document.querySelector('.logout-icon').addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) AuthAPI.logout();
        });

        var sidebar     = document.querySelector('.sidebar');
        var toggleBtn   = document.querySelector('.sidebar-toggle-btn');
        var mainContent = document.querySelector('.main-content');

        toggleBtn.addEventListener('click', function() { sidebar.classList.toggle('expanded'); });
        mainContent.addEventListener('click', function(e) {
            if (sidebar.classList.contains('expanded') && !sidebar.contains(e.target)) {
                sidebar.classList.remove('expanded');
            }
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

        document.querySelectorAll('.tab-btn[data-modal-tab]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
                document.getElementById(this.dataset.modalTab + '-tab').classList.add('active');
            });
        });

        document.getElementById('moduleModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        function closeModal() {
            document.getElementById('moduleModal').classList.remove('active');
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
            document.querySelector('.tab-btn[data-modal-tab="overview"]').classList.add('active');
            document.getElementById('overview-tab').classList.add('active');
        }

        function openSubmitModal(encodedAssignment, isRetake) {
            var a = JSON.parse(decodeURIComponent(encodedAssignment));
            document.getElementById('submitModalTitle').textContent =
                isRetake ? 'Retake: ' + a.title : 'Submit: ' + a.title;
            document.getElementById('submitModalMeta').textContent =
                'Due: ' + a.dueDate + '  •  Weight: ' + a.weight + '%';
            document.getElementById('submissionNotes').value        = '';
            document.getElementById('submissionFile').value         = '';
            document.getElementById('fileSelected').style.display   = 'none';
            document.getElementById('fileUploadArea').style.display = '';
            document.getElementById('submitModalForm').style.display  = '';
            document.getElementById('submitSuccessMsg').style.display = 'none';
            document.getElementById('confirmSubmitBtn').disabled    = false;
            document.getElementById('confirmSubmitBtn').textContent = isRetake ? 'Confirm Retake' : 'Submit';
            document.getElementById('confirmSubmitBtn').onclick = function() {
                confirmSubmission(a, isRetake);
            };
            document.getElementById('submitModalOverlay').classList.add('active');
        }

        function closeSubmitModal() {
            document.getElementById('submitModalOverlay').classList.remove('active');
        }

        var fileInput  = document.getElementById('submissionFile');
        var uploadArea = document.getElementById('fileUploadArea');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) showSelectedFile(this.files[0]);
        });

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        uploadArea.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            var file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                showSelectedFile(file);
            }
        });

        function showSelectedFile(file) {
            document.getElementById('fileSelected').style.display   = 'flex';
            document.getElementById('fileUploadArea').style.display = 'none';
            document.getElementById('fileSelectedName').textContent = file.name;
        }

        function removeFile() {
            document.getElementById('submissionFile').value         = '';
            document.getElementById('fileSelected').style.display   = 'none';
            document.getElementById('fileUploadArea').style.display = '';
        }

        async function confirmSubmission(assignment, isRetake) {
            var btn = document.getElementById('confirmSubmitBtn');
            btn.disabled    = true;
            btn.textContent = isRetake ? 'Submitting retake…' : 'Submitting…';
            try {
                var file  = document.getElementById('submissionFile').files[0];
                var notes = document.getElementById('submissionNotes').value;

                var formData = new FormData();
                formData.append('assignmentId', assignment.id);
                formData.append('notes', notes);
                formData.append('isRetake', isRetake);
                if (file) formData.append('file', file);

                // await AssignmentAPI.submit(formData);
                await new Promise(function(r) { setTimeout(r, 900); });

                document.getElementById('submitModalForm').style.display  = 'none';
                document.getElementById('submitSuccessMsg').style.display = '';
                document.getElementById('submitSuccessText').textContent  =
                    isRetake ? 'Retake submitted successfully!' : 'Assignment submitted successfully!';
                setTimeout(closeSubmitModal, 2000);
            } catch (err) {
                btn.disabled    = false;
                btn.textContent = isRetake ? 'Confirm Retake' : 'Submit';
                alert('Submission failed. Please try again.');
                console.error('[Submit] Error:', err.message);
            }
        }

        document.getElementById('submitModalOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeSubmitModal();
        });

        async function loadModules() {
            var grid       = document.getElementById('modulesGrid');
            var loadingMsg = document.getElementById('loadingMsg');
            try {
                var res     = await ModuleAPI.getEnrolledModules();
                var modules = res.data || [];

                if (loadingMsg) loadingMsg.style.display = 'none';

                if (modules.length === 0) {
                    grid.innerHTML = '<div class="state-msg">You are not enrolled in any modules this semester.</div>';
                    return;
                }

                grid.innerHTML = '';
                modules.forEach(function(m) {
                    var card = document.createElement('div');
                    card.className = 'module-card';
                    card.setAttribute('data-id', m.id);
                    card.innerHTML =
                        '<div class="module-header">' +
                            '<div class="module-icon">' +
                                '<img src="{{ asset('images/modules.png') }}" style="width:20px;height:20px;display:block;margin-left:1px;">' +
                            '</div>' +
                            '<div class="module-credits">' + m.credits + ' Credits</div>' +
                        '</div>' +
                        '<h3 class="module-title">' + m.name + '</h3>' +
                        '<p class="module-code">' + m.code + ' • ' + m.instructor.name + '</p>' +
                        '<div class="progress-section">' +
                            '<div class="progress-label">' +
                                '<span class="progress-text">Course Progress</span>' +
                                '<span class="progress-value">' + m.progress + '%</span>' +
                            '</div>' +
                            '<div class="progress-bar">' +
                                '<div class="progress-fill" style="width:' + m.progress + '%;"></div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="module-footer">' +
                            '<div class="module-meta">' +
                                '<div class="meta-item">' +
                                    '<img src="{{ asset('images/calendar_today.png') }}" style="width:20px;height:20px;display:block;margin-left:3px;">' +
                                    'Spring 2026' +
                                '</div>' +
                            '</div>' +
                        '</div>';

                    card.addEventListener('click', function() {
                        openModuleModal(this.getAttribute('data-id'));
                    });
                    grid.appendChild(card);
                });

            } catch (err) {
                grid.innerHTML = '<div class="state-msg error">Could not load modules. Please refresh the page.</div>';
                console.error('[Modules] Load failed:', err.message);
            }
        }

        async function openModuleModal(moduleId) {
            document.getElementById('moduleModal').classList.add('active');
            document.getElementById('modalTitle').textContent       = 'Loading...';
            document.getElementById('modalMeta').textContent        = '—';
            document.getElementById('modalDescription').textContent = 'Loading module details...';
            document.getElementById('assignmentList').innerHTML     = '<p style="color:#64748b;font-size:14px;">Loading...</p>';
            document.getElementById('resourcesGrid').innerHTML      = '<p style="color:#64748b;font-size:14px;">Loading...</p>';

            try {
                var res = await ModuleAPI.getModuleDetails(moduleId);
                var m   = res.data;

                document.getElementById('modalTitle').textContent = m.name;
                document.getElementById('modalMeta').textContent  =
                    m.code + ' • ' + m.credits + ' Credits • Spring 2026';

                document.getElementById('modalDescription').textContent =
                    m.description || 'No description available.';

                var instrName = m.instructor ? m.instructor.name : '—';
                var initials  = instrName.split(' ')
                    .filter(function(w) { return w.length > 0 && w[0] === w[0].toUpperCase(); })
                    .map(function(w) { return w[0]; }).join('').slice(0, 2);

                document.getElementById('instructorAvatar').textContent = initials || '?';
                document.getElementById('instructorName').textContent   = instrName;
                document.getElementById('instructorDept').textContent   =
                    m.instructor && m.instructor.department  ? m.instructor.department  : 'Lecturer';
                document.getElementById('instructorEmail').textContent  =
                    m.instructor && m.instructor.email       ? m.instructor.email       : '—';
                document.getElementById('instructorRoom').textContent   =
                    m.instructor && m.instructor.room        ? m.instructor.room        : '—';
                document.getElementById('instructorHours').textContent  =
                    m.instructor && m.instructor.officeHours ? m.instructor.officeHours : '—';

                var assignList = document.getElementById('assignmentList');
                if (m.assessments && m.assessments.length > 0) {
                    assignList.innerHTML = '';
                    m.assessments.forEach(function(a) {
                        var statusClass = 'status-' + (a.status || 'upcoming');

                        var actionBtn = '';
                        if (a.status === 'pending' || a.status === 'upcoming') {
                            actionBtn = '<button class="btn-submit" onclick="openSubmitModal(\'' +
                                encodeURIComponent(JSON.stringify(a)) + '\', false)">Submit</button>';
                        } else if (a.status === 'completed') {
                            actionBtn = '<button class="btn-retake" onclick="openSubmitModal(\'' +
                                encodeURIComponent(JSON.stringify(a)) + '\', true)">Retake</button>';
                        }

                        assignList.innerHTML +=
                            '<div class="assignment-item">' +
                                '<div class="assignment-info">' +
                                    '<h4>' + a.title + '</h4>' +
                                    '<p>Due: ' + a.dueDate + ' &nbsp;•&nbsp; Weight: ' + a.weight + '%</p>' +
                                '</div>' +
                                '<div class="assignment-actions">' +
                                    '<div class="status-badge ' + statusClass + '">' +
                                        (a.status || 'upcoming') +
                                    '</div>' +
                                    actionBtn +
                                '</div>' +
                            '</div>';
                    });
                } else {
                    assignList.innerHTML = '<p style="color:#64748b;font-size:14px;">No assessments listed yet.</p>';
                }

                var scheduleList = document.getElementById('scheduleList');
                if (m.learningOutcomes && m.learningOutcomes.length > 0) {
                    scheduleList.innerHTML = '';
                    m.learningOutcomes.forEach(function(outcome, idx) {
                        scheduleList.innerHTML +=
                            '<div class="schedule-item">' +
                                '<div class="schedule-week">Week ' + (idx + 1) + ':</div>' +
                                '<div class="schedule-content">' +
                                    '<div class="schedule-title">' + outcome + '</div>' +
                                    '<div class="schedule-materials">' +
                                        '<a class="material-link">' +
                                            '<img src="{{ ('images/slides.png') }}" style="width:18px;height:18px;display:block;margin-left:3px;"> Lecture Slides' +
                                        '</a>' +
                                        '<a class="material-link">' +
                                            '<img src="{{ asset('images/doc.png') }}" style="width:18px;height:18px;display:block;margin-left:3px;"> Workshop Brief' +
                                        '</a>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    });
                } else {
                    scheduleList.innerHTML = '<p style="color:#64748b;font-size:14px;">Schedule not available yet.</p>';
                }

                var resourcesGrid = document.getElementById('resourcesGrid');
                if (m.resources && m.resources.length > 0) {
                    resourcesGrid.innerHTML = '';
                    m.resources.forEach(function(r) {
                        resourcesGrid.innerHTML +=
                            '<div class="resource-card" onclick="window.open(\'' + r.url + '\', \'_blank\')">' +
                                '<div class="resource-icon">' +
                                    '<img src="{{ asset('images/doc.png') }}" style="width:18px;height:18px;display:block;margin-left:3px;">' +
                                '</div>' +
                                '<div class="resource-title">' + r.title + '</div>' +
                                '<div class="resource-type">' + (r.type || 'Document').toUpperCase() + '</div>' +
                            '</div>';
                    });
                } else {
                    resourcesGrid.innerHTML = '<p style="color:#64748b;font-size:14px;">No resources uploaded yet.</p>';
                }

            } catch (err) {
                document.getElementById('modalTitle').textContent       = 'Could not load module';
                document.getElementById('modalDescription').textContent = 'Please close and try again.';
                console.error('[Module Detail] Load failed:', err.message);
            }
        }

        loadModules();
    </script>
</body>
</html>