<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Content Manager – SUSAdmin</title>
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

        /*  Layout  */
        /* .app-container { display: flex; height: 100vh; width: 100%; overflow: hidden; }
        .main-content {
            flex: 1;
            margin-left: 72px;
            overflow-y: auto;
            transition: margin-left 0.2s ease;
        }
        .sidebar.expanded ~ .main-content,
        .sidebar.expanded + .main-content { margin-left: 220px; } */

        /*  Layout */
        .content-grid { display: grid; grid-template-columns: 340px 1fr; gap: 20px; }
        .course-list { padding: 20px; }
        .course-list h3 { font-size: 15px; font-weight: 600; margin-bottom: 16px; }

        /*  Course items in editor */
        .course-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; border-radius: 10px;
            border: 1px solid var(--border); margin-bottom: 10px;
            cursor: pointer; transition: all .2s;
        }
        .course-item:hover, .course-item.active { border-color: #2563eb; background: #eff6ff; }
        body.dark-mode .course-item.active { background: #1e3a5f; }
        .course-item-info h4 { font-size: 14px; font-weight: 600; }
        .course-item-info p  { font-size: 13px; color: #64748b; margin-top: 2px; }

        /* Buttons  */
        .edit-btn-small {
            border: 1px solid var(--border); background: white; border-radius: 6px;
            padding: 5px 12px; font-size: 13px; font-weight: 500;
            cursor: pointer; color: #475569; transition: all .2s; white-space: nowrap;
        }
        body.dark-mode .edit-btn-small { background: #0f172a; color: #94a3b8; }
        .edit-btn-small:hover { border-color: #2563eb; color: #2563eb; }

        .btn-danger-small {
            border: 1px solid #fca5a5; background: #fff; border-radius: 6px;
            padding: 5px 12px; font-size: 13px; font-weight: 500;
            cursor: pointer; color: #dc2626; transition: all .2s; white-space: nowrap;
        }
        .btn-danger-small:hover { background: #fee2e2; }

        /*  Upload zone  */
        .upload-zone {
            border: 2px dashed var(--border); border-radius: 12px; padding: 32px;
            text-align: center; cursor: pointer; transition: all .2s; margin-bottom: 20px;
        }
        .upload-zone:hover { border-color: #2563eb; background: #eff6ff; }
        .upload-icon { font-size: 28px; margin-bottom: 8px; }
        .upload-text { font-size: 14px; font-weight: 600; color: #374151; }
        .upload-hint { font-size: 13px; color: #94a3b8; margin-top: 4px; }

        /* Assignment form  */
        .assignment-form { padding: 20px; }
        .assignment-form h3 { font-size: 15px; font-weight: 600; margin-bottom: 16px; }
        .form-group { margin-bottom: 14px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        /*  Materials list */
        .materials-list { margin-top: 16px; }
        .material-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px; background: #f8fafc; margin-bottom: 8px;
        }
        body.dark-mode .material-item { background: #0f172a; }
        .material-icon { font-size: 18px; }
        .material-name { font-size: 14px; font-weight: 500; flex: 1; }
        .material-size { font-size: 13px; color: #94a3b8; }
        .material-del { background: none; border: none; cursor: pointer; color: #dc2626; font-size: 16px; }

        /* Tab bar (top level)  */
        .content-tabs {
            display: flex; gap: 4px; background: #f1f5f9;
            padding: 4px; border-radius: 60px; margin-bottom: 24px; width: fit-content;
        }
        .tab-btn {
            padding: 8px 24px; border-radius: 40px; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all .2s; background: transparent; border: none; color: #64748b;
        }
        .tab-btn.active { background: white; color: #2563eb; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .tab-pane { display: none; }
        .tab-pane.active-pane { display: block; }

        
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
        .stats-card {
            background: white; border-radius: 16px; border: 1px solid var(--border);
            padding: 20px; display: flex; align-items: center; gap: 16px;
        }
        .stats-icon {
            width: 52px; height: 52px; background: #eff6ff; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;
        }
        .stats-icon.orange { background: #fff7ed; }
        .stats-icon.green  { background: #f0fdf4; }
        .stats-icon.purple { background: #faf5ff; }
        .stats-number { font-size: 26px; font-weight: 800; color: #0f172a; }
        .stats-label  { font-size: 13px; color: #64748b; font-weight: 500; margin-top: 2px; }
        body.dark-mode .stats-card   { background: #1e293b; }
        body.dark-mode .stats-number { color: #e2e8f0; }
        body.dark-mode .stats-icon   { background: #1e3a5f; }

        
        .overview-section {
            background: white; border-radius: 16px; border: 1px solid var(--border);
            padding: 20px; margin-bottom: 20px;
        }
        body.dark-mode .overview-section { background: #1e293b; }
        .section-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid var(--border);
        }
        .section-title { font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .btn-outline-small {
            background: transparent; border: 1px solid var(--border); border-radius: 8px;
            padding: 6px 14px; font-size: 13px; cursor: pointer; font-weight: 500;
            color: #475569; transition: all .2s;
        }
        .btn-outline-small:hover { background: #eff6ff; border-color: #2563eb; color: #2563eb; }

       
        .modules-grid-preview { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
        .module-preview-card {
            background: #f8fafc; border-radius: 14px; border: 1px solid var(--border);
            padding: 18px; transition: all .2s; position: relative;
        }
        body.dark-mode .module-preview-card { background: #0f172a; }
        .module-preview-card:hover { border-color: #2563eb; box-shadow: 0 4px 16px rgba(37,99,235,.08); }
        .module-preview-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
        .module-preview-icon {
            width: 40px; height: 40px; background: #2563eb; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;
        }
        .module-actions-row { display: flex; gap: 6px; }
        .module-preview-credits {
            background: #e0e7ff; padding: 3px 10px; border-radius: 20px;
            font-size: 13px; font-weight: 600; color: #3730a3;
        }
        .module-preview-title { font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #0f172a; }
        body.dark-mode .module-preview-title { color: #f1f5f9; }
        .module-preview-code  { font-size: 13px; color: #64748b; margin-bottom: 12px; }
        .progress-label-preview { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px; color: #64748b; }
        .progress-bar-preview { width: 100%; height: 5px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
        .progress-fill-preview { height: 100%; background: linear-gradient(90deg,#2563eb,#3b82f6); border-radius: 10px; }
        .module-preview-footer {
            display: flex; gap: 10px; padding-top: 12px;
            border-top: 1px solid var(--border); font-size: 13px; color: #64748b; margin-top: 12px;
            flex-wrap: wrap;
        }
        .module-stat { display: flex; align-items: center; gap: 4px; }

        
        .resources-grid-preview { display: grid; grid-template-columns: repeat(auto-fill,minmax(200px,1fr)); gap: 12px; }
        .resource-preview-item {
            padding: 12px 14px; border: 1px solid var(--border); border-radius: 10px;
            display: flex; align-items: center; gap: 10px; transition: all .2s;
        }
        .resource-preview-item:hover { border-color: #2563eb; background: #eff6ff; }
        body.dark-mode .resource-preview-item:hover { background: #1e3a5f; }
        .resource-preview-icon {
            width: 36px; height: 36px; background: #f1f5f9; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
        }
        body.dark-mode .resource-preview-icon { background: #334155; }
        .resource-preview-name { font-size: 14px; font-weight: 600; }
        .resource-preview-type { font-size: 12px; color: #94a3b8; margin-top: 2px; }

        
        .assignment-table { width: 100%; border-collapse: collapse; }
        .assignment-table th {
            text-align: left; font-size: 13px; font-weight: 600; color: #64748b;
            text-transform: uppercase; letter-spacing: .04em;
            padding: 8px 12px; border-bottom: 1px solid var(--border);
        }
        .assignment-table td { padding: 12px; border-bottom: 1px solid var(--border); font-size: 14px; vertical-align: middle; }
        .assignment-table tr:last-child td { border-bottom: none; }
        .assignment-table tr:hover td { background: #f8fafc; }
        body.dark-mode .assignment-table tr:hover td { background: #0f172a; }

        
        .status-badge {
            padding: 3px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
            display: inline-block;
        }
        .status-open     { background: #fef3c7; color: #b45309; }
        .status-draft    { background: #e0e7ff; color: #3730a3; }
        .status-closed   { background: #dcfce7; color: #166534; }
        .status-upcoming { background: #f1f5f9; color: #475569; }

        
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; padding:20px; }
        .modal-overlay.open { display:flex; }
        .modal-box {
            background: white; border-radius: 16px; max-width: 680px; width: 100%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
        }
        body.dark-mode .modal-box { background: #1e293b; color: #e2e8f0; }
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 24px; border-bottom: 1px solid var(--border); position: sticky; top: 0;
            background: white; z-index: 1; border-radius: 16px 16px 0 0;
        }
        body.dark-mode .modal-header { background: #1e293b; border-bottom-color: #334155; }
        .modal-title { font-size: 17px; font-weight: 700; }
        .modal-close {
            width: 30px; height: 30px; border: none; background: #f1f5f9;
            border-radius: 8px; cursor: pointer; font-size: 16px; color: #64748b;
            display: flex; align-items: center; justify-content: center;
        }
        .modal-close:hover { background: #e2e8f0; }
        body.dark-mode .modal-close { background: #334155; color: #94a3b8; }
        .modal-body { padding: 24px; }
        .modal-actions { display:flex; justify-content:flex-end; gap:10px; padding: 16px 24px; border-top:1px solid var(--border); }
        body.dark-mode .modal-actions { border-top-color: #334155; }

        
        .modal-tabs { display:flex; gap:0; border-bottom: 1px solid var(--border); margin-bottom:20px; }
        .modal-tab-btn {
            padding: 10px 18px; background: transparent; border: none;
            border-bottom: 2px solid transparent; margin-bottom: -1px;
            font-size: 14px; font-weight: 500; color: #64748b; cursor: pointer; transition: all .2s;
        }
        .modal-tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }
        .modal-tab-pane { display: none; }
        .modal-tab-pane.active { display: block; }

        /* ── Module detail content ── */
        .detail-label { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing:.05em; margin-bottom:4px; }
        .detail-value { font-size: 14px; color: #0f172a; margin-bottom:14px; }
        body.dark-mode .detail-value { color: #e2e8f0; }
        .instructor-card-sm {
            display:flex; align-items:center; gap:12px; padding:12px 14px;
            background:#f8fafc; border-radius:10px; margin-bottom:14px;
        }
        body.dark-mode .instructor-card-sm { background: #0f172a; }
        .instructor-avatar-sm {
            width:44px; height:44px; border-radius:50%; background: linear-gradient(135deg,#667eea,#764ba2);
            display:flex; align-items:center; justify-content:center;
            color:white; font-weight:700; font-size:15px; flex-shrink:0;
        }
        .instructor-name-sm { font-size:14px; font-weight:600; }
        .instructor-meta-sm { font-size: 13px; color:#64748b; }
        .detail-divider { height:1px; background:var(--border); margin:16px 0; }

        
        .student-preview-banner {
            background: linear-gradient(135deg,#2563eb 0%,#4f46e5 100%);
            border-radius: 10px; padding: 14px 18px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 12px;
        }
        .student-preview-banner .icon { font-size: 22px; }
        .student-preview-banner .text { color: white; font-size: 14px; }
        .student-preview-banner .text strong { display: block; font-size: 14px; margin-bottom: 2px; }
        .student-view-card {
            border: 1px solid var(--border); border-radius: 12px; overflow: hidden;
        }
        .student-view-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            padding: 20px 22px; color: white;
        }
        .student-view-header h3 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .student-view-header p  { font-size: 14px; opacity: .8; }
        .student-view-body { padding: 18px; background: #f8fafc; }
        body.dark-mode .student-view-body { background: #0f172a; }
        .student-assignment-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 14px; background: white; border: 1px solid var(--border);
            border-radius: 8px; margin-bottom: 8px; font-size: 14px;
        }
        body.dark-mode .student-assignment-item { background: #1e293b; }
        .student-assignment-title { font-weight: 600; margin-bottom: 3px; }
        .student-assignment-meta  { font-size: 13px; color: #64748b; }
        .progress-ring { width: 52px; height: 52px; flex-shrink: 0; }

       
        .empty-state {
            text-align: center; padding: 36px 20px; color: #94a3b8; font-size: 14px;
        }
        .empty-state .empty-icon { font-size: 36px; margin-bottom: 10px; }

        .toast-notification {
            position: fixed; bottom: 28px; right: 28px; background: #1e293b; color: white;
            padding: 12px 18px; border-radius: 10px; font-size: 14px; font-weight: 500;
            z-index: 2000; display: flex; align-items: center; gap: 10px;
            transform: translateX(420px); transition: transform .3s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,.2);
        }
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #16a34a; }
        .toast-error   { background: #dc2626; }

        .confirm-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5);
            z-index: 3000; align-items: center; justify-content: center;
        }
        .confirm-overlay.open { display: flex; }
        .confirm-box {
            background: white; border-radius: 14px; padding: 28px 28px 20px;
            max-width: 380px; width: 100%; text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
        }
        body.dark-mode .confirm-box { background: #1e293b; color: #e2e8f0; }
        .confirm-icon { 
            display: flex !important; 
            justify-content: center !important; 
            align-items: center !important;
            margin-bottom: 12px;
        }
        .confirm-title { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
        .confirm-msg   { font-size: 14px; color: #64748b; margin-bottom: 20px; }
        .confirm-actions { display: flex; gap: 10px; justify-content: center; }
        .btn-confirm-yes { padding: 9px 22px; background: #dc2626; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-confirm-no  { padding: 9px 22px; background: #f1f5f9; color: #475569; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-confirm-yes:hover { background: #b91c1c; }
        .btn-confirm-no:hover  { background: #e2e8f0; }
        .tab-btn {
            display: inline-flex;
            flex-direction: row;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 900px) {
            .content-grid { grid-template-columns: 1fr; }
            .modules-grid-preview { grid-template-columns: repeat(2,1fr); }
            .stats-row { grid-template-columns: repeat(2,1fr); }
        }
        .empty-state { display: flex; flex-direction: column; align-items: center; }
        .empty-icon { display: flex; justify-content: center; }
        .upload-zone { text-align: center; }
        .upload-icon { display: flex; justify-content: center; margin-bottom: 8px; }
        .resource-preview-icon { display: flex; align-items: center; justify-content: center; }
        .resource-preview-name { 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            max-width: 140px; 
        }
        .module-preview-footer span {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        body.dark-mode .stats-number { color: #e2e8f0 !important; }
        body.dark-mode .stats-label { color: #94a3b8 !important; }
        body.dark-mode .tab-btn { color: #e2e8f0 !important; }
        body.dark-mode .tab-btn.active { color: #60a5fa !important; }
        body.dark-mode .section-title { color: #e2e8f0 !important; }
        body.dark-mode .module-preview-title { color: #e2e8f0 !important; }
        body.dark-mode .module-preview-code { color: #94a3b8 !important; }
        body.dark-mode .course-item-info h4 { color: #e2e8f0 !important; }
        body.dark-mode .course-item-info p { color: #94a3b8 !important; }
        body.dark-mode .btn-outline-small { color: #e2e8f0 !important; }
        body.dark-mode .upload-text { color: #e2e8f0 !important; }
        body.dark-mode .material-name { color: #e2e8f0 !important; }
        body.dark-mode .assignment-form h3 { color: #e2e8f0 !important; }
        body.dark-mode .resource-preview-name { color: #e2e8f0 !important; }
        body.dark-mode .content-tabs { background: #cbd5e1 !important; }
        body.dark-mode .tab-btn { color: #1e293b !important; }
        body.dark-mode .tab-btn.active { background: #94a3b8 !important; color: #0f172a !important; } 
        body.dark-mode .module-preview-credits { background: #1e3a5f !important; color: #93c5fd !important; }
        body.dark-mode .status-open { background: #fef3c7 !important; color: #b45309 !important; }
        body.dark-mode .status-draft { background: #e0e7ff !important; color: #3730a3 !important; }
        body.dark-mode .status-closed { background: #dcfce7 !important; color: #166534 !important; }
        body.dark-mode .status-upcoming { background: #f1f5f9 !important; color: #475569 !important; }
        body.dark-mode .btn-secondary { color: #1e293b !important; background: #f1f5f9 !important; }
        body.dark-mode .btn-confirm-no { color: #1e293b !important; background: #f1f5f9 !important; }
        body.dark-mode .btn-danger-small { 
            background: #3b1c1c !important; 
            color: #f87171 !important; 
            border-color: #7f1d1d !important; 
        }
        body.dark-mode .btn-danger-small img { 
            filter: brightness(0) invert(1); 
        }

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

        <!-- Top bar -->
                <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <!-- Page header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1>Content Manager</h1>
                <p>Manage modules, materials and assignments — see exactly what students see</p>
            </div>
            <button class="btn-primary" onclick="openCourseModal()">
                <img src="{{ asset('images/admin_icons/add.png') }}" style="width:16px;height:16px;">New Course
            </button>
        </div>

        <!-- Top-level tabs -->
        <div class="content-tabs">
            <button class="tab-btn active" onclick="switchTab('overview')">
                <img src="{{ asset('images/admin_icons/home_black.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;">Overview
            </button>
            <button class="tab-btn" onclick="switchTab('editor')">
                <img src="{{ asset('images/admin_icons/edit.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;">Editor
            </button>
            <button class="tab-btn" onclick="switchTab('assignments')">
                <img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;">Assignments
            </button>
            <button class="tab-btn" onclick="switchTab('preview')">
                <img src="{{ asset('images/admin_icons/person.png') }}" style="width:25px;height:25px;vertical-align:middle;margin-right:6px;">Student Preview
            </button>
        </div>

        <div id="overviewTab" class="tab-pane active-pane">
            <!-- Stats -->
            <div class="stats-row">
                <div class="stats-card">
                    <div class="stats-icon"><img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:30px;height:30px;"></div>
                    <div><div class="stats-number" id="statCourses">0</div><div class="stats-label">Active Courses</div></div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon orange"><img src="{{ asset('images/admin_icons/doc.png') }}" style="width:30px;height:30px;"></div>
                    <div><div class="stats-number" id="statMaterials">0</div><div class="stats-label">Materials Uploaded</div></div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon green"><img src="{{ asset('images/admin_icons/edit.png') }}" style="width:30px;height:30px;"></div>
                    <div><div class="stats-number" id="statAssignments">0</div><div class="stats-label">Assignments Created</div></div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon purple"><img src="{{ asset('images/admin_icons/users_black.png') }}" style="width:30px;height:30px;"></div>
                    <div><div class="stats-number" id="statStudents">0</div><div class="stats-label">Students Enrolled</div></div>
                </div>
            </div>

            <!-- Modules overview -->
            <div class="overview-section">
                <div class="section-header">
                    <div class="section-title"><img src="{{ asset('images/admin_icons/dashboard.png') }}" style="width:30px;height:30px;">All Modules</div>
                    <div style="display:flex;gap:8px;">
                        <button class="btn-outline-small" onclick="switchTab('editor');openCourseModal()">+ Add Course</button>
                        <button class="btn-outline-small" onclick="switchTab('editor')">Manage →</button>
                    </div>
                </div>
                <div id="overviewModulesGrid" class="modules-grid-preview"></div>
            </div>

            <!-- Recent materials -->
            <div class="overview-section">
                <div class="section-header">
                    <div class="section-title"><img src="{{ asset('images/admin_icons/content.png') }}" style="width:30px;height:30px;">Recent Materials</div>
                    <button class="btn-outline-small" onclick="switchTab('editor');document.getElementById('fileInput').click()">+ Upload</button>
                </div>
                <div id="overviewResourcesList" class="resources-grid-preview"></div>
            </div>
        </div>

        <div id="editorTab" class="tab-pane">
            <div class="content-grid">
                <div class="card course-list">
                    <h3>Courses</h3>
                    <div id="courseList"></div>
                </div>
                <div>
                    <div class="card" style="padding:20px;margin-bottom:16px;">
                        <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                            <div class="upload-icon"><img src="{{ asset('images/admin_icons/upload.png') }}" style="width:40px;height:40px;"></div>
                            <div class="upload-text">Drop files here or click to upload</div>
                            <div class="upload-hint">PDF, PPT, DOCX up to 50MB</div>
                            <input type="file" id="fileInput" style="display:none" multiple onchange="handleFiles(this)">
                        </div>
                        <div class="materials-list" id="materialsList"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="assignmentsTab" class="tab-pane">
            <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;">
                <!-- Table -->
                <div class="overview-section" style="margin-bottom:0;">
                    <div class="section-header">
                        <div class="section-title"><img src="{{ asset('images/admin_icons/edit.png') }}" style="width:16px;height:16px;"> All Assignments</div>
                        <span style="font-size:12px;color:#64748b;" id="assignCountLabel"></span>
                    </div>
                    <div id="assignTableWrap">
                        <table class="assignment-table" id="assignTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Deadline</th>
                                    <th>Group</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="assignTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <!-- Create form -->
                <div class="card assignment-form" style="height:fit-content;">
                    <h3>New Assignment</h3>
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-input" id="assignTitle" placeholder="Assignment title">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-textarea" id="assignDesc" rows="3" placeholder="Describe the assignment..." style="resize:vertical;"></textarea>
                    </div>
                    <div class="form-row">
                        <div>
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-input" id="assignDeadline">
                        </div>
                        <div>
                            <label class="form-label">Weight (%)</label>
                            <input type="number" class="form-input" id="assignWeight" placeholder="e.g. 30" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-row" style="margin-top:0;">
                        <div>
                            <label class="form-label">Assign to Group</label>
                            <select class="form-select" id="assignGroup">
                                <option>All Groups</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select class="form-select" id="assignStatus">
                                <option value="open">Open</option>
                                <option value="draft">Draft</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:10px;">
                        <label class="form-label">Linked Course</label>
                        <select class="form-select" id="assignCourse"></select>
                    </div>
                    <button class="btn-primary" style="width:100%;margin-top:10px;justify-content:center;" onclick="saveAssignment()">
                        Save Assignment
                    </button>
                </div>
            </div>
        </div>

        <div id="previewTab" class="tab-pane">
            <div class="student-preview-banner">
                <div class="icon"><img src="{{ asset('images/admin_icons/person_white.png') }}" style="width:25px;height:25px;"></div>
                <div class="text">
                    <strong>Student View Preview</strong>
                    This is exactly what enrolled students see when they open My Modules. Click any module card to inspect its detail view.
                </div>
            </div>
            <div id="studentModulesGrid" class="modules-grid-preview" style="margin-bottom:24px;"></div>
        </div>

    </main>
</div>


<div class="modal-overlay" id="courseModal">
    <div class="modal-box" style="max-width:560px;">
        <div class="modal-header">
            <div class="modal-title" id="courseModalTitle">Add Course</div>
            <button class="modal-close" onclick="closeCourseModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-tabs">
                <button class="modal-tab-btn active" onclick="switchModalTab('details')">Details</button>
                <button class="modal-tab-btn" onclick="switchModalTab('instructor')">Instructor</button>
                <button class="modal-tab-btn" onclick="switchModalTab('schedule')">Schedule</button>
            </div>

            <!-- Details -->
            <div class="modal-tab-pane active" id="modal-details">
                <div class="form-group"><label class="form-label">Course Name</label><input type="text" class="form-input" id="courseName" placeholder="e.g. Computer Science 101"></div>
                <div class="form-group"><label class="form-label">Module Code</label><input type="text" class="form-input" id="courseCode" placeholder="e.g. CS101"></div>
                <div class="form-row">
                    <div><label class="form-label">Credits</label><input type="number" class="form-input" id="courseCredits" value="15"></div>
                    <div><label class="form-label">Progress (%)</label><input type="number" class="form-input" id="courseProgress" value="0" min="0" max="100"></div>
                </div>
                <div class="form-group"><label class="form-label">Description</label><textarea class="form-textarea" id="courseDesc" rows="3" style="resize:vertical;" placeholder="Short module description..."></textarea></div>
                <div class="form-row">
                    <div><label class="form-label">Enrolled Students</label><input type="number" class="form-input" id="courseStudents" value="0"></div>
                    <div><label class="form-label">Resources Count</label><input type="number" class="form-input" id="courseResources" value="0"></div>
                </div>
            </div>

            <!-- Instructor -->
            <div class="modal-tab-pane" id="modal-instructor">
                <div class="form-group"><label class="form-label">Instructor Name</label><select class="form-select" id="courseTeacher"><option>Dr. Smith</option><option>Prof. Lee</option><option>Dr. Kim</option><option>Prof. Vasquez</option><option>Dr. Sarah Mitchell</option><option>Prof. James Carter</option><option>Dr. Emily Nguyen</option></select></div>
                <div class="form-group"><label class="form-label">Department</label><input type="text" class="form-input" id="courseInstrDept" placeholder="e.g. Computer Science"></div>
                <div class="form-row">
                    <div><label class="form-label">Email</label><input type="email" class="form-input" id="courseInstrEmail" placeholder="instructor@uni.ac.uk"></div>
                    <div><label class="form-label">Office Room</label><input type="text" class="form-input" id="courseInstrRoom" placeholder="e.g. B204"></div>
                </div>
                <div class="form-group"><label class="form-label">Office Hours</label><input type="text" class="form-input" id="courseInstrHours" placeholder="e.g. Mon & Wed 2–4pm"></div>
            </div>

            <!-- Schedule -->
            <div class="modal-tab-pane" id="modal-schedule">
                <p style="font-size:13px;color:#64748b;margin-bottom:14px;">Add learning outcomes / weekly topics that students will see in the Schedule tab of their module view.</p>
                <div id="scheduleItems"></div>
                <button class="btn-outline-small" onclick="addScheduleItem()" style="margin-top:8px;width:100%;justify-content:center;padding:9px;">+ Add Week / Topic</button>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-secondary" onclick="closeCourseModal()">Cancel</button>
            <button class="btn-primary" onclick="saveCourse()">Save Course</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="moduleDetailModal">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="detailModalTitle">Module Detail</div>
                <div style="font-size:12px;color:#64748b;margin-top:2px;" id="detailModalMeta"></div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <button class="edit-btn-small" id="detailEditBtn">Edit Module</button>
                <button class="modal-close" onclick="closeDetailModal()">✕</button>
            </div>
        </div>
        <div class="modal-body">
            <div class="modal-tabs">
                <button class="modal-tab-btn active" onclick="switchDetailTab('overview')">Overview</button>
                <button class="modal-tab-btn" onclick="switchDetailTab('assignments')">Assignments</button>
                <button class="modal-tab-btn" onclick="switchDetailTab('resources')">Resources</button>
                <button class="modal-tab-btn" onclick="switchDetailTab('studentview')">Student View</button>
            </div>

            <div class="modal-tab-pane active" id="detail-overview">
                <div class="detail-label">Description</div>
                <div class="detail-value" id="detailDesc">—</div>
                <div class="detail-divider"></div>
                <div class="detail-label">Module Leader</div>
                <div class="instructor-card-sm" id="detailInstructorCard"></div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:8px;">
                    <div><div class="detail-label">Credits</div><div class="detail-value" id="detailCredits">—</div></div>
                    <div><div class="detail-label">Students</div><div class="detail-value" id="detailStudents">—</div></div>
                    <div><div class="detail-label">Progress</div><div class="detail-value" id="detailProgress">—</div></div>
                </div>
            </div>

            <div class="modal-tab-pane" id="detail-assignments">
                <div id="detailAssignmentsBody"></div>
            </div>

            <div class="modal-tab-pane" id="detail-resources">
                <div class="resources-grid-preview" id="detailResourcesGrid"></div>
            </div>

            <div class="modal-tab-pane" id="detail-studentview">
                <div id="detailStudentViewBody"></div>
            </div>
        </div>
    </div>
</div>


<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:26px;height:26px;"></div>
        <div class="confirm-title" id="confirmTitle">Delete this item?</div>
        <div class="confirm-msg" id="confirmMsg">This action cannot be undone.</div>
        <div class="confirm-actions">
            <button class="btn-confirm-no" onclick="closeConfirm()">Cancel</button>
            <button class="btn-confirm-yes" id="confirmYesBtn">Delete</button>
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

<script src="{{ asset('js/admin-api.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('js/admin-api.js') }}"></script> --}}

<script>
    // ── 1. GLOBAL STATE ──
    let courses = [];
    let materials = []; // Kept empty until you add a materials table to DB
    let assignments = []; // Kept empty until you add an assignments table to DB
    
    let activeCourse    = 0;
    let editCourseIndex = -1;
    let confirmCallback = null;

    // ── 2. DATABASE INITIALIZATION ──
    async function init() {
        try {
            // Fetch everything at once: Stats, Modules, Assignments, and Real Groups!
            const [statsRes, modulesRes, assignRes, groupsRes] = await Promise.all([
                adminApiCall('/content/stats'),
                adminApiCall('/content/modules'),
                adminApiCall('/content/assignments'),
                adminApiCall('/groups') // We created this route earlier for Timetables!
            ]);
            
            const stats = statsRes.data;
            const dbModules = modulesRes.data || [];
            
            // 1. Populate Real Assignments
            assignments = assignRes.data || [];

            // 2. Populate Real Groups in the New Assignment Dropdown
            const groupSelect = document.getElementById('assignGroup');
            if (groupSelect && groupsRes.data) {
                groupSelect.innerHTML = '<option value="All Groups">All Groups</option>' + 
                    groupsRes.data.map(g => `<option value="${g.name}">${g.name}</option>`).join('');
            }

            // 3. Map the Detailed Modules
            courses = dbModules.map(m => ({
                id: m.id,
                name: m.title,     
                code: m.code,
                teacher: m.teacher,
                
                // Mapped the missing detailed mentor info here!
                instrDept: m.instrDept,
                instrEmail: m.instrEmail,
                instrRoom: m.instrRoom,
                instrHours: m.instrHours,
                desc: m.desc,
                
                credits: m.credits,
                progress: m.progress,
                students: m.students,
                resources: m.resources,
                schedule: ['Week 1: Introduction', 'Week 2: Core Concepts', 'Week 3: Advanced Topics']
            }));

            // Update the Top Stats
            if(document.getElementById('statCourses')) document.getElementById('statCourses').textContent = stats.courses;
            if(document.getElementById('statMaterials')) document.getElementById('statMaterials').textContent = stats.materials;
            if(document.getElementById('statAssignments')) document.getElementById('statAssignments').textContent = stats.assignments;
            if(document.getElementById('statStudents')) document.getElementById('statStudents').textContent = stats.students;

            // Render UI
            renderOverview();
            renderCourses();
            renderMaterials();
            renderStudentPreview();
            
        } catch (err) {
            console.error('Failed to load database data:', err);
            showToast('Failed to load data from DB', 'error');
        }
    }

    // ── 3. HELPERS & TOASTS ──
    function esc(s){ return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

    function showToast(msg, type='success'){
        const t = document.createElement('div');
        t.className = `toast-notification toast-${type}`;
        t.innerHTML = `<span>${type==='success'?'<img src="{{ asset('images/admin_icons/check_circle.png') }}" style="width:14px;height:14px;">':'<img src="{{ asset('images/admin_icons/error.png') }}" style="width:14px;height:14px;">'}</span> ${msg}`;
        document.body.appendChild(t);
        setTimeout(()=>t.classList.add('show'),10);
        setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),300); },3000);
    }

    function confirm2(title, msg, cb){
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMsg').textContent   = msg;
        confirmCallback = cb;
        document.getElementById('confirmOverlay').classList.add('open');
    }
    function closeConfirm(){ document.getElementById('confirmOverlay').classList.remove('open'); confirmCallback=null; }
    if(document.getElementById('confirmYesBtn')) {
        document.getElementById('confirmYesBtn').onclick = ()=>{ if(confirmCallback) confirmCallback(); closeConfirm(); };
    }

    function totalStudents(){ return courses.reduce((s,c)=>s+(c.students||0),0); }

    // ── 4. TAB SWITCHING ──
    function switchTab(name){
        ['overview','editor','assignments','preview'].forEach(t=>{
            const pane = document.getElementById(t+'Tab');
            if(pane) pane.classList.toggle('active-pane', t===name);
        });
        document.querySelectorAll('.content-tabs .tab-btn').forEach((b,i)=>{
            b.classList.toggle('active', ['overview','editor','assignments','preview'][i]===name);
        });
        if(name==='overview')    renderOverview();
        if(name==='editor')      { renderCourses(); renderMaterials(); }
        if(name==='assignments') renderAssignmentsTab();
        if(name==='preview')     renderStudentPreview();
    }

    // ── 5. OVERVIEW RENDERING ──
    function renderOverview(){
        renderOverviewModules();
        renderOverviewResources();
    }

    function renderOverviewModules(){
        const el = document.getElementById('overviewModulesGrid');
        if(!el) return;
        if(!courses.length){ el.innerHTML='<div class="empty-state"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:28px;height:28px;"></div>No courses yet.</div>'; return; }
        el.innerHTML = courses.map((c,i)=>`
            <div class="module-preview-card" onclick="openDetailModal(${i})">
                <div class="module-preview-header">
                    <div class="module-preview-icon"><img src="{{ asset('images/admin_icons/view_white.png') }}" style="width:25px;height:25px;"></div>
                    <div class="module-actions-row">
                        <span class="module-preview-credits">${esc(c.credits)} Cr</span>
                    </div>
                </div>
                <div class="module-preview-title">${esc(c.name)}</div>
                <div class="module-preview-code">${esc(c.code)} · ${esc(c.teacher)}</div>
                <div>
                    <div class="progress-label-preview"><span>Progress</span><span>${c.progress}%</span></div>
                    <div class="progress-bar-preview"><div class="progress-fill-preview" style="width:${c.progress}%"></div></div>
                </div>
                <div class="module-preview-footer">
                    <span class="module-stat"><img src="{{ asset('images/admin_icons/users.png') }}" style="width:25px;height:25px;"> ${c.students} students</span>
                    <span class="module-stat"><img src="{{ asset('images/admin_icons/content.png') }}" style="width:25px;height:25px;"> ${c.resources} resources</span>
                    <span class="module-stat"><img src="{{ asset('images/admin_icons/tasks.png') }}" style="width:25px;height:25px;"> ${assignments.filter(a=>a.courseId===c.id).length} tasks</span>
                </div>
            </div>
        `).join('');
    }

    function renderOverviewResources(){
        const el = document.getElementById('overviewResourcesList');
        if(!el) return;
        const icons = { 
            pdf:'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;">', 
            pptx:'<img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">', 
            ppt:'<img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">', 
            docx:'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;">', 
            doc:'<img src="{{ asset('images/admin_icons/content.png') }}" style="width:25px;height:25px;">' 
        };
        if(!materials.length){ el.innerHTML='<div class="empty-state"><div class="empty-icon"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div></div>No materials uploaded.</div>'; return; }
        el.innerHTML = materials.slice(0,8).map((m,i)=>{
            const ext = (m.type||m.name.split('.').pop()).toLowerCase();
            return `<div class="resource-preview-item">
                <div class="resource-preview-icon">${icons[ext]||'📎'}</div>
                <div>
                    <div class="resource-preview-name">${esc(m.name)}</div>
                    <div class="resource-preview-type">${m.size} · ${m.uploadedAt}</div>
                </div>
            </div>`;
        }).join('');
    }

    // ── 6. EDITOR TAB ──
    function renderCourses(){
        const el = document.getElementById('courseList');
        if(!el) return;
        el.innerHTML = courses.map((c,i)=>`
            <div class="course-item ${i===activeCourse?'active':''}" onclick="selectCourse(${i})">
                <div class="course-item-info">
                    <h4>${esc(c.name)}</h4>
                    <p>${esc(c.code)} · ${c.students} students · ${c.progress}% progress</p>
                </div>
                <div style="display:flex;gap:6px;">
                    <button class="edit-btn-small" onclick="event.stopPropagation();openEditCourse(${i})">Edit</button>
                    <button class="btn-danger-small" onclick="event.stopPropagation();deleteCourse(${i})">X</button>
                </div>
            </div>
        `).join('') || '<div class="empty-state" style="display:flex;flex-direction:column;align-items:center;"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No courses yet.</div>';
    }
    function selectCourse(i){ activeCourse=i; renderCourses(); }

    function deleteCourse(i){
        confirm2('Delete course?', `"${courses[i].name}" and all linked data will be removed.`, ()=>{
            courses.splice(i,1);
            if(activeCourse>=courses.length) activeCourse=Math.max(0,courses.length-1);
            renderCourses(); renderOverview();
            showToast('Course deleted');
        });
    }

    function renderMaterials(){
        const icons = { 
            pdf:'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;">', 
            pptx:'<img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">', 
            ppt:'<img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">', 
            docx:'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;">', 
            doc:'<img src="{{ asset('images/admin_icons/content.png') }}" style="width:25px;height:25px;">' 
        };
        const el = document.getElementById('materialsList');
        if(!el) return;
        el.innerHTML = materials.map((m,i)=>{
            const ext=(m.type||m.name.split('.').pop()).toLowerCase();
            return `<div class="material-item">
                <span class="material-icon">${icons[ext]||'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:18px;height:18px;">'}</span>
                <span class="material-name">${esc(m.name)}</span>
                <span class="material-size">${m.size}</span>
                <button class="material-del" onclick="delMaterial(${i})">
                    <img src="{{ asset('images/admin_icons/delete.png') }}" style="width:25px;height:25px;">
                </button>
            </div>`;
        }).join('') || '<div class="empty-state" style="padding:20px;"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No files uploaded yet.</div>';
    }
    function delMaterial(i){ materials.splice(i,1); renderMaterials(); renderOverviewResources(); showToast('Material removed'); }

    function handleFiles(input){
        for(const f of input.files){
            materials.unshift({ name:f.name, size:(f.size/1024).toFixed(0)+' KB', type:f.name.split('.').pop(), uploadedAt:new Date().toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'}) });
        }
        renderMaterials(); renderOverviewResources();
        showToast(`Uploaded ${input.files.length} file(s)`);
        input.value='';
    }

    // ── 7. ASSIGNMENTS TAB ──
    function renderAssignmentsTab(){
        const sel = document.getElementById('assignCourse');
        if(sel) sel.innerHTML = courses.map(c=>`<option value="${c.id}">${esc(c.name)}</option>`).join('');

        const tbody = document.getElementById('assignTableBody');
        if(!tbody) return;
        if(!assignments.length){
            tbody.innerHTML='<tr><td colspan="5"><div class="empty-state"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No assignments yet.</div></td></tr>';
            if(document.getElementById('assignCountLabel')) document.getElementById('assignCountLabel').textContent='';
            return;
        }
        if(document.getElementById('assignCountLabel')) document.getElementById('assignCountLabel').textContent=`${assignments.length} total`;
        tbody.innerHTML = assignments.map(a=>{
            const course = courses.find(c=>c.id===a.courseId);
            return `<tr>
                <td>
                    <div style="font-weight:600;font-size:13px;">${esc(a.title)}</div>
                    <div style="font-size:11px;color:#64748b;">${course?esc(course.name):''} · ${a.weight||'—'}%</div>
                </td>
                <td>${a.deadline||'TBD'}</td>
                <td><span style="font-size:12px;">${esc(a.group)}</span></td>
                <td><span class="status-badge status-${esc(a.status)}">${esc(a.status)}</span></td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <button class="edit-btn-small" onclick="toggleAssignStatus(${a.id})">Toggle</button>
                        <button class="btn-danger-small" onclick="deleteAssignment(${a.id})">✕</button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    function saveAssignment(){
        const title = document.getElementById('assignTitle').value.trim();
        if(!title) return showToast('Enter a title','error');
        const courseId = parseInt(document.getElementById('assignCourse').value);
        assignments.unshift({
            id: Date.now(),
            title,
            description: document.getElementById('assignDesc').value,
            deadline:    document.getElementById('assignDeadline').value||'TBD',
            weight:      parseInt(document.getElementById('assignWeight').value)||20,
            group:       document.getElementById('assignGroup').value,
            status:      document.getElementById('assignStatus').value,
            courseId,
            createdAt:   new Date().toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'})
        });
        showToast(`Assignment "${title}" created`);
        ['assignTitle','assignDesc','assignDeadline','assignWeight'].forEach(id=>document.getElementById(id).value='');
        renderAssignmentsTab();
        if(document.getElementById('statAssignments')) document.getElementById('statAssignments').textContent = assignments.length;
    }

    function deleteAssignment(id){
        confirm2('Delete assignment?','This will remove it from all student views.', ()=>{
            assignments = assignments.filter(a=>a.id!==id);
            renderAssignmentsTab();
            showToast('Assignment deleted');
        });
    }

    function toggleAssignStatus(id){
        const cycle = { open:'draft', draft:'closed', closed:'upcoming', upcoming:'open' };
        const a = assignments.find(x=>x.id===id);
        if(a){ a.status = cycle[a.status]||'open'; renderAssignmentsTab(); }
    }

    // ── 8. STUDENT PREVIEW TAB ──
    function renderStudentPreview(){
        const el = document.getElementById('studentModulesGrid');
        if(!el) return;
        if(!courses.length){ el.innerHTML='<div class="empty-state"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No courses to preview.</div>'; return; }
        el.innerHTML = courses.map((c,i)=>`
            <div class="module-preview-card" style="cursor:pointer;" onclick="openStudentDetailModal(${i})">
                <div class="module-preview-header">
                    <div class="module-preview-icon"><img src="{{ asset('images/admin_icons/view_white.png') }}" style="width:25px;height:25px;"></div>
                    <span class="module-preview-credits">${c.credits} Credits</span>
                </div>
                <div class="module-preview-title">${esc(c.name)}</div>
                <div class="module-preview-code">${esc(c.code)} · ${esc(c.teacher)}</div>
                <div>
                    <div class="progress-label-preview"><span>Course Progress</span><span>${c.progress}%</span></div>
                    <div class="progress-bar-preview"><div class="progress-fill-preview" style="width:${c.progress}%"></div></div>
                </div>
                <div class="module-preview-footer">
                    <span><img src="{{ asset('images/admin_icons/timetable.png') }}" style="width:20px;height:20px;"> Spring 2026</span>
                    <span><img src="{{ asset('images/admin_icons/credits.png') }}" style="width:20px;height:20px;"> ${c.credits} ECTS</span>
                    <span><img src="{{ asset('images/admin_icons/users.png') }}" style="width:20px;height:20px;"> ${c.students}</span>
                </div>
            </div>
        `).join('');
    }

    // ── 9. MODALS & FORMS ──
    let scheduleItemCount = 0;
    function addScheduleItem(val=''){
        const container = document.getElementById('scheduleItems');
        const div = document.createElement('div');
        div.style = 'display:flex;gap:6px;margin-bottom:8px;align-items:center;';
        div.innerHTML = `<input type="text" class="form-input schedule-item-input" value="${esc(val)}" placeholder="Week topic..."><button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#dc2626;font-size:18px;line-height:1;"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:20px;height:20px;"></button>`;
        container.appendChild(div);
    }

    function openCourseModal(){
        editCourseIndex=-1;
        document.getElementById('courseModalTitle').textContent='Add Course';
        ['courseName','courseCode','courseDesc','courseInstrDept','courseInstrEmail','courseInstrRoom','courseInstrHours'].forEach(id=>document.getElementById(id).value='');
        document.getElementById('courseCredits').value  = 15;
        document.getElementById('courseProgress').value = 0;
        document.getElementById('courseStudents').value = 0;
        document.getElementById('courseResources').value= 0;
        document.getElementById('scheduleItems').innerHTML='';
        switchModalTab('details');
        document.getElementById('courseModal').classList.add('open');
    }
    function openEditCourse(i){
        editCourseIndex=i;
        const c=courses[i];
        document.getElementById('courseModalTitle').textContent='Edit Course';
        document.getElementById('courseName').value      = c.name;
        document.getElementById('courseCode').value      = c.code||'';
        document.getElementById('courseDesc').value      = c.desc||'';
        document.getElementById('courseCredits').value   = c.credits;
        document.getElementById('courseProgress').value  = c.progress;
        document.getElementById('courseStudents').value  = c.students||0;
        document.getElementById('courseResources').value = c.resources||0;
        document.getElementById('courseTeacher').value   = c.teacher||'Dr. Smith';
        document.getElementById('courseInstrDept').value = c.instrDept||'';
        document.getElementById('courseInstrEmail').value= c.instrEmail||'';
        document.getElementById('courseInstrRoom').value = c.instrRoom||'';
        document.getElementById('courseInstrHours').value= c.instrHours||'';
        document.getElementById('scheduleItems').innerHTML='';
        (c.schedule||[]).forEach(s=>addScheduleItem(s));
        switchModalTab('details');
        document.getElementById('courseModal').classList.add('open');
    }
    function closeCourseModal(){ document.getElementById('courseModal').classList.remove('open'); }

    function switchModalTab(name){
        ['details','instructor','schedule'].forEach(t=>{
            document.getElementById('modal-'+t).classList.toggle('active', t===name);
        });
        document.querySelectorAll('#courseModal .modal-tab-btn').forEach((b,i)=>{
            b.classList.toggle('active',['details','instructor','schedule'][i]===name);
        });
    }

    function saveCourse(){
        const name = document.getElementById('courseName').value.trim();
        if(!name) return showToast('Enter a course name','error');
        const schedule = Array.from(document.querySelectorAll('.schedule-item-input')).map(i=>i.value.trim()).filter(Boolean);
        const obj = {
            id:          editCourseIndex>=0 ? courses[editCourseIndex].id : Date.now(),
            name,
            code:        document.getElementById('courseCode').value.trim()||(name.substring(0,2).toUpperCase()+Math.floor(Math.random()*900+100)),
            credits:     parseInt(document.getElementById('courseCredits').value)||15,
            progress:    Math.min(100,Math.max(0,parseInt(document.getElementById('courseProgress').value)||0)),
            desc:        document.getElementById('courseDesc').value.trim(),
            teacher:     document.getElementById('courseTeacher').value,
            instrDept:   document.getElementById('courseInstrDept').value.trim(),
            instrEmail:  document.getElementById('courseInstrEmail').value.trim(),
            instrRoom:   document.getElementById('courseInstrRoom').value.trim(),
            instrHours:  document.getElementById('courseInstrHours').value.trim(),
            students:    parseInt(document.getElementById('courseStudents').value)||0,
            resources:   parseInt(document.getElementById('courseResources').value)||0,
            schedule
        };
        if(editCourseIndex>=0) courses[editCourseIndex]=obj;
        else courses.push(obj);
        closeCourseModal();
        renderCourses(); renderOverview();
        showToast(`Course "${name}" saved locally`);
    }

    // ── 10. DETAIL MODAL ──
    let currentDetailIndex = -1;

    function openDetailModal(i){
        currentDetailIndex = i;
        const c = courses[i];
        document.getElementById('detailModalTitle').textContent = c.name;
        document.getElementById('detailModalMeta').textContent  = `${c.code} · ${c.credits} Credits · Spring 2026`;
        document.getElementById('detailEditBtn').onclick = ()=>{ closeDetailModal(); openEditCourse(i); };

        document.getElementById('detailDesc').textContent     = c.desc||'No description.';
        document.getElementById('detailCredits').textContent  = c.credits+' ECTS';
        document.getElementById('detailStudents').textContent = c.students||0;
        document.getElementById('detailProgress').textContent = c.progress+'%';
        const initials = (c.teacher||'??').split(' ').map(w=>w[0]).join('').slice(0,2);
        document.getElementById('detailInstructorCard').innerHTML=`
            <div class="instructor-avatar-sm">${esc(initials)}</div>
            <div>
                <div class="instructor-name-sm">${esc(c.teacher)}</div>
                <div class="instructor-meta-sm">${esc(c.instrDept||'—')} · ${esc(c.instrEmail||'—')}</div>
                <div class="instructor-meta-sm" style="display:flex;align-items:center;gap:6px;">
                    <img src="{{ asset('images/admin_icons/corporate.png') }}" style="width:20px;height:20px;flex-shrink:0;"> ${esc(c.instrRoom||'—')} · 
                    <img src="{{ asset('images/admin_icons/clock_grey.png') }}" style="width:20px;height:20px;flex-shrink:0;"> ${esc(c.instrHours||'—')}
                </div>
            </div>`;

        const linked = assignments.filter(a=>a.courseId===c.id);
        const abody = document.getElementById('detailAssignmentsBody');
        abody.innerHTML = linked.length ? `<table class="assignment-table"><thead><tr><th>Title</th><th>Deadline</th><th>Weight</th><th>Status</th><th>Group</th></tr></thead><tbody>
            ${linked.map(a=>`<tr><td><div style="font-weight:600">${esc(a.title)}</div><div style="font-size:11px;color:#64748b;">${esc(a.description.substring(0,60))}…</div></td><td>${a.deadline}</td><td>${a.weight}%</td><td><span class="status-badge status-${esc(a.status)}">${esc(a.status)}</span></td><td>${esc(a.group)}</td></tr>`).join('')}
        </tbody></table>` : '<div class="empty-state"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No assignments linked to this course.</div>';

        const icons = { 
            pdf:'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;">', 
            pptx:'<img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">', 
            ppt:'<img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">', 
            docx:'<img src="{{ asset('images/admin_icons/doc.png') }}" style="width:25px;height:25px;">', 
            doc:'<img src="{{ asset('images/admin_icons/content.png') }}" style="width:25px;height:25px;">' 
        };
        document.getElementById('detailResourcesGrid').innerHTML = materials.length
            ? materials.map(m=>{ const ext=(m.type||m.name.split('.').pop()).toLowerCase(); return `<div class="resource-preview-item"><div class="resource-preview-icon">${icons[ext]||'📎'}</div><div><div class="resource-preview-name">${esc(m.name)}</div><div class="resource-preview-type">${m.size}</div></div></div>`; }).join('')
            : '<div class="empty-state"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No materials uploaded.</div>';

        renderDetailStudentView(c, linked);
        switchDetailTab('overview');
        document.getElementById('moduleDetailModal').classList.add('open');
    }

    function renderDetailStudentView(c, linked){
        document.getElementById('detailStudentViewBody').innerHTML=`
            <div class="student-view-card">
                <div class="student-view-header">
                    <h3>${esc(c.name)}</h3>
                    <p>${esc(c.code)} · ${esc(c.teacher)} · ${c.credits} Credits</p>
                    <div style="margin-top:12px;">
                        <div style="display:flex;justify-content:space-between;font-size:12px;opacity:.8;margin-bottom:6px;"><span>Course Progress</span><span>${c.progress}%</span></div>
                        <div style="height:6px;background:rgba(255,255,255,.3);border-radius:10px;overflow:hidden;"><div style="height:100%;width:${c.progress}%;background:white;border-radius:10px;"></div></div>
                    </div>
                </div>
                <div class="student-view-body">
                    <div style="font-size:13px;color:#475569;margin-bottom:14px;">${esc(c.desc||'No description.')}</div>
                    <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:10px;">Assignments</div>
                    ${linked.length ? linked.map(a=>`
                        <div class="student-assignment-item">
                            <div>
                                <div class="student-assignment-title">${esc(a.title)}</div>
                                <div class="student-assignment-meta">Due: ${a.deadline} · Weight: ${a.weight}% · ${esc(a.group)}</div>
                            </div>
                            <span class="status-badge status-${esc(a.status)}">${esc(a.status)}</span>
                        </div>`).join('')
                    : '<div class="empty-state" style="padding:16px;"><div class="empty-icon"><img src="{{ asset('images/admin_icons/empty.png') }}" style="width:40px;height:40px;"></div>No assignments.</div>'}
                    ${c.schedule && c.schedule.length ? `
                        <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin:14px 0 10px;">Weekly Schedule</div>
                        ${c.schedule.map((s,i)=>`<div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);font-size:13px;"><span style="color:#2563eb;font-weight:600;min-width:68px;">Week ${i+1}</span><span>${esc(s)}</span></div>`).join('')}`:''}
                </div>
            </div>`;
    }

    function closeDetailModal(){ document.getElementById('moduleDetailModal').classList.remove('open'); }

    function switchDetailTab(name){
        ['overview','assignments','resources','studentview'].forEach(t=>{
            document.getElementById('detail-'+t).classList.toggle('active', t===name);
        });
        document.querySelectorAll('#moduleDetailModal .modal-tab-btn').forEach((b,i)=>{
            b.classList.toggle('active',['overview','assignments','resources','studentview'][i]===name);
        });
    }

    function openStudentDetailModal(i){
        openDetailModal(i);
        switchDetailTab('studentview');
    }

    // ── 11. GLOBAL THEME LOGIC ──
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
    function toggleSidebar() { document.getElementById('sidebar').classList.toggle('expanded'); }
    function showLogoutPopup() { document.getElementById('logoutPopup').classList.add('show'); }
    function hideLogoutPopup() { document.getElementById('logoutPopup').classList.remove('show'); }

    // Start everything!
    window.addEventListener('DOMContentLoaded', init);
</script>
</body>
</html>