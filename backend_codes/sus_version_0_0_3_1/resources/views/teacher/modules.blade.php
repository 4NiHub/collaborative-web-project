<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modules – Smart University System</title>
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
            flex:1; position:relative; z-index:1;
        }
        .sidebar.expanded + .main-content { margin-left:var(--sidebar-expanded); }

        /* ── Module list view ── */
        .list-view { padding:20px; }
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

        .page-header { margin-bottom:28px; }
        .page-header h1 { font-size:26px; font-weight:700; margin-bottom:6px; }
        .page-header p  { color:#64748b; font-size:14px; }

        .modules-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }
        .module-card {
            background:white; border-radius:12px; padding:24px;
            box-shadow:0 1px 3px rgba(0,0,0,0.05);
            cursor:pointer; transition:all 0.2s;
        }
        .module-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,0.1); }
        .module-card-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
        .module-icon-wrap {
            width:40px; height:40px; background:#2563eb; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
        }
        .module-icon-wrap img { width:20px; height:20px; }
        .module-credits-badge {
            background:#f1f5f9; color:#475569; padding:4px 10px;
            border-radius:12px; font-size:11px; font-weight:600;
        }
        .module-name { font-size:17px; font-weight:700; margin-bottom:4px; color:#1e293b; }
        .module-code { font-size:13px; color:#64748b; margin-bottom:14px; }
        .module-card-footer {
            display:flex; justify-content:space-between; align-items:center;
            padding-top:14px; border-top:1px solid #e2e8f0;
            font-size:13px; color:#64748b;
        }
        .module-groups { display:flex; gap:6px; flex-wrap:wrap; }
        .group-chip {
            background:#eff6ff; color:#2563eb; padding:3px 8px;
            border-radius:6px; font-size:11px; font-weight:600;
        }
        .module-students { font-size:13px; color:#64748b; }

        /* ── Module detail view ── */
        .detail-view { display:none; flex-direction:column; min-height:100vh; }
        .detail-view.active { display:flex; }

        /* Blue header bar */
        .detail-header {
            background:#2563eb; color:white; padding:16px 28px;
            display:flex; align-items:center; gap:16px;
        }
        .back-btn {
            padding:7px 14px; background:rgba(255,255,255,0.2); border:none;
            border-radius:7px; color:white; font-size:13px; font-weight:600;
            cursor:pointer; transition:background 0.2s; white-space:nowrap;
        }
        .back-btn:hover { background:rgba(255,255,255,0.3); }
        .detail-header-info { flex:1; text-align:center; }
        .detail-header-title { font-size:18px; font-weight:700; }
        .detail-header-sub   { font-size:13px; opacity:0.85; margin-top:2px; }

        /* Tab bar */
        .detail-tabs {
            background:white; padding:0 28px;
            border-bottom:2px solid #e2e8f0;
            display:flex; gap:0; box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        .tab-btn {
            padding:14px 20px; border:none; background:transparent;
            border-bottom:2px solid transparent; margin-bottom:-2px;
            font-size:14px; font-weight:500; color:#64748b;
            cursor:pointer; transition:all 0.2s;
        }
        .tab-btn.active { color:#2563eb; border-bottom-color:#2563eb; font-weight:600; }
        .tab-btn:hover  { color:#2563eb; }
        .tab-content { display:none; }
        .tab-content.active { display:block; }

        /* Detail body */
        .detail-body { flex:1; background:#f8fafc; padding:24px 28px; }

        /* ── Performance tab ── */
        .perf-toolbar {
            display:flex; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;
        }
        .search-box {
            flex:1; min-width:180px; max-width:280px;
            padding:9px 14px; border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:14px; outline:none; transition:border-color 0.2s;
        }
        .search-box:focus { border-color:#2563eb; }
        .filter-btns { display:flex; gap:6px; }
        .filter-btn {
            padding:7px 16px; border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:13px; font-weight:500; cursor:pointer; background:white;
            color:#64748b; transition:all 0.2s;
        }
        .filter-btn.active { background:#2563eb; color:white; border-color:#2563eb; }
        .filter-btn:hover:not(.active) { border-color:#2563eb; color:#2563eb; }
        .download-btn {
            margin-left:auto; padding:8px 16px; background:white;
            border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:13px; font-weight:600; cursor:pointer; color:#1e293b;
            display:flex; align-items:center; gap:6px; transition:all 0.2s;
        }
        .download-btn:hover { border-color:#2563eb; color:#2563eb; }

        /* Performance table */
        .perf-table-wrap { background:white; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .perf-table { width:100%; border-collapse:collapse; }
        .perf-table th {
            padding:12px 16px; text-align:left; font-size:11px; font-weight:700;
            color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;
            border-bottom:1px solid #e2e8f0; background:white;
        }
        .perf-table td { padding:14px 16px; border-bottom:1px solid #f1f5f9; font-size:14px; color:#1e293b; }
        .perf-table tr:last-child td { border-bottom:none; }
        .perf-table tbody tr:hover td { background:#f8fafc; }
        .student-name  { font-weight:600; color:#1e293b; }
        .student-id    { font-size:12px; color:#94a3b8; }
        .group-chip-sm { font-size:11px; color:#2563eb; font-weight:600; }
        .score-dash    { color:#cbd5e1; }
        .grade-badge {
            display:inline-flex; align-items:center; justify-content:center;
            width:36px; height:28px; border-radius:6px;
            font-size:13px; font-weight:700;
        }
        .grade-a  { background:#dcfce7; color:#166534; }
        .grade-b  { background:#dbeafe; color:#1e40af; }
        .grade-c  { background:#fef3c7; color:#92400e; }
        .grade-d  { background:#ffedd5; color:#9a3412; }
        .grade-f  { background:#fee2e2; color:#991b1b; }
        .status-pass { background:#dcfce7; color:#166534; padding:3px 10px; border-radius:6px; font-size:12px; font-weight:600; }
        .status-fail { background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:6px; font-size:12px; font-weight:600; }
        .dev-panel {
            background:white; border:1.5px dashed #cbd5e1; border-radius:12px;
            padding:28px; box-shadow:0 1px 3px rgba(0,0,0,0.05);
        }
        .dev-panel-title { font-size:18px; font-weight:700; color:#1e293b; margin-bottom:8px; }
        .dev-panel-copy { font-size:14px; color:#64748b; line-height:1.6; }

        /* ── Submissions tab ── */
        .submission-list { display:flex; flex-direction:column; gap:0; background:white; border-radius:12px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .submission-item {
            display:flex; align-items:center; padding:16px 20px;
            border-bottom:1px solid #f1f5f9; transition:background 0.15s;
        }
        .submission-item:last-child { border-bottom:none; }
        .submission-item:hover { background:#f8fafc; }
        .sub-left-bar { width:3px; height:44px; border-radius:2px; margin-right:16px; flex-shrink:0; }
        .sub-left-bar.group-a { background:#2563eb; }
        .sub-left-bar.group-b { background:#f59e0b; }
        .sub-info { flex:1; }
        .sub-student { font-weight:700; font-size:14px; color:#1e293b; margin-bottom:2px; }
        .sub-group-chip {
            display:inline-block; padding:1px 7px; border-radius:4px;
            font-size:11px; font-weight:600; margin-left:6px;
            background:#eff6ff; color:#2563eb;
        }
        .sub-assignment { font-size:13px; color:#64748b; margin-bottom:2px; }
        .sub-meta  { font-size:12px; color:#94a3b8; }
        .sub-icons { display:flex; gap:8px; flex-shrink:0; }
        .sub-icon-btn {
            width:34px; height:34px; background:#f1f5f9; border:none;
            border-radius:8px; cursor:pointer; display:flex; align-items:center;
            justify-content:center; transition:all 0.2s;
        }
        .sub-icon-btn:hover { background:#e2e8f0; }
        .sub-icon-btn img { width:16px; height:16px; }

        /* ── Attendance tab ── */
        .attendance-section { padding:8px 0; }
        .attendance-section h3 { font-size:16px; font-weight:700; margin-bottom:20px; color:#1e293b; }
        .attendance-filters { display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap; }
        .att-field { display:flex; flex-direction:column; gap:6px; flex:1; min-width:200px; }
        .att-label { font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.4px; }

        /* Custom select with dropdown */
        .att-select-wrap { position:relative; }
        .att-select {
            width:100%; padding:10px 36px 10px 14px; border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:14px; background:white; color:#1e293b; outline:none;
            cursor:pointer; transition:border-color 0.2s; appearance:none; -webkit-appearance:none;
        }
        .att-select:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .att-select-arrow {
            position:absolute; right:12px; top:50%; transform:translateY(-50%);
            pointer-events:none; color:#64748b; font-size:12px;
        }

        /* Session dropdown custom styling — like pic 3 */
        .session-dropdown-wrap { position:relative; }
        .session-dropdown-list {
            display:none; position:absolute; top:calc(100% + 4px); left:0; right:0;
            background:white; border:1.5px solid #1e293b; border-radius:8px;
            z-index:200; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.1);
            max-height:220px; overflow-y:auto;
        }
        .session-dropdown-list.open { display:block; }
        .session-dropdown-item {
            padding:10px 14px; font-size:14px; cursor:pointer; color:#1e293b;
            transition:background 0.1s;
        }
        .session-dropdown-item:hover { background:#f1f5f9; }
        .session-dropdown-item.selected { background:#2563eb; color:white; }
        .session-dropdown-trigger {
            width:100%; padding:10px 36px 10px 14px; border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:14px; background:white; color:#1e293b; outline:none;
            cursor:pointer; transition:border-color 0.2s; text-align:left;
            display:flex; justify-content:space-between; align-items:center;
        }
        .session-dropdown-trigger:focus, .session-dropdown-trigger.open { border-color:#1e293b; border-width:2px; }

        .add-att-btn {
            padding:10px 22px; background:#16a34a; color:white; border:none;
            border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;
            transition:background 0.2s; white-space:nowrap; height:44px;
        }
        .add-att-btn:hover { background:#15803d; }

        /* Attendance table */
        .att-table-wrap { background:white; border-radius:10px; overflow:hidden; margin-top:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
        .att-table { width:100%; border-collapse:collapse; }
        .att-table th {
            padding:10px 16px; text-align:left; font-size:11px; font-weight:700;
            color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;
            border-bottom:1px solid #e2e8f0; background:white;
        }
        .att-table td { padding:13px 16px; border-bottom:1px solid #f1f5f9; font-size:14px; color:#1e293b; }
        .att-table tr:last-child td { border-bottom:none; }
        .att-table tbody tr:hover td { background:#f8fafc; }
        .att-session-header td {
            background:#f8fafc; font-size:12px; color:#2563eb; font-weight:600;
            padding:6px 16px; border-bottom:1px solid #e2e8f0;
        }
        /* Radio-style circles for present/absent */
        .att-radio-group { display:flex; align-items:center; justify-content:center; }
        .att-radio {
            width:22px; height:22px; border-radius:50%; border:2px solid #cbd5e1;
            cursor:pointer; transition:all 0.15s; position:relative; flex-shrink:0;
        }
        .att-radio.present { border-color:#16a34a; background:#16a34a; }
        .att-radio.absent  { border-color:#dc2626; background:#dc2626; }
        .att-radio.present::after, .att-radio.absent::after {
            content:''; position:absolute; inset:4px; border-radius:50%; background:white;
        }
        /* Note link */
        .att-note-link { color:#2563eb; font-size:13px; cursor:pointer; text-decoration:none; }
        .att-note-link:hover { text-decoration:underline; }

        /* Attendance history cards at bottom */
        .att-history-label { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin:24px 0 12px; }
        .att-history-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:14px; }
        .att-history-card {
            background:white; border-radius:10px; padding:16px 18px;
            box-shadow:0 1px 3px rgba(0,0,0,0.06); cursor:pointer; transition:box-shadow 0.2s;
        }
        .att-history-card:hover { box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        .att-history-card-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; }
        .att-history-chip { font-size:11px; font-weight:700; padding:3px 8px; border-radius:4px; }
        .att-history-chip.a  { background:#dbeafe; color:#1e40af; }
        .att-history-chip.b  { background:#fef3c7; color:#92400e; }
        .att-pct { font-weight:700; font-size:14px; margin-left: 10px;}
        .att-pct.good { color:#16a34a; }
        .att-pct.warn { color:#f59e0b; }
        .att-pct.bad  { color:#dc2626; }
        .att-history-card-title { font-size:14px; font-weight:700; color:#1e293b; margin-bottom:2px; }
        .att-history-card-sub   { font-size:12px; color:#94a3b8; margin-bottom:10px; }
        .att-history-card-stats { display:flex; gap:18px; }
        .att-stat-item .att-stat-num { font-size:18px; font-weight:700; color:#1e293b; }
        .att-stat-item .att-stat-pct { font-size:13px; font-weight:700; }
        .att-stat-item .att-stat-pct.good { color:#16a34a; }
        .att-stat-item .att-stat-pct.warn { color:#f59e0b; }
        .att-stat-item .att-stat-label { font-size:11px; color:#94a3b8; }

        /* ── Add Attendance Modal ── */
        .att-modal-overlay {
            display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4);
            z-index:3000; align-items:center; justify-content:center; padding:20px;
        }
        .att-modal-overlay.active { display:flex; }
        .att-modal {
            background:white; border-radius:14px; padding:28px 32px;
            max-width:540px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.2);
            max-height:90vh; overflow-y:auto;
        }
        .att-modal-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
        .att-modal-title  { font-size:17px; font-weight:700; color:#1e293b; }
        .att-modal-close  {
            width:32px; height:32px; border:none; background:#f1f5f9;
            border-radius:8px; cursor:pointer; font-size:16px; color:#64748b;
            display:flex; align-items:center; justify-content:center;
        }
        .att-modal-close:hover { background:#e2e8f0; }
        .att-modal-fields { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:20px; }
        .att-modal-field label { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.4px; display:block; margin-bottom:6px; }
        .att-modal-field label .req { color:#dc2626; }
        .att-modal-field select, .att-modal-field input {
            width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:14px; outline:none; transition:border-color 0.2s; box-sizing:border-box;
            background:white; color:#1e293b;
        }
        .att-modal-field select:focus, .att-modal-field input:focus { border-color:#2563eb; }
        .att-modal-students-label { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px; }
        .att-modal-student-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        .att-modal-student-table th {
            font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;
            padding:8px 10px; text-align:left; border-bottom:1px solid #e2e8f0;
        }
        .att-modal-student-table td { padding:10px 10px; border-bottom:1px solid #f8fafc; font-size:14px; color:#1e293b; }
        .att-modal-student-table tr:last-child td { border-bottom:none; }
        .att-modal-student-table .sid { font-size:12px; color:#94a3b8; }
        /* Radio circles in modal */
        .modal-radio-group { display:flex; gap:10px; align-items:center; }
        .modal-radio {
            width:24px; height:24px; border-radius:50%; border:2px solid #cbd5e1;
            cursor:pointer; transition:all 0.15s; display:flex; align-items:center; justify-content:center;
        }
        .modal-radio.selected-present { border-color:#16a34a; background:#16a34a; }
        .modal-radio.selected-absent  { border-color:#dc2626; background:#dc2626; }
        .modal-radio::after { content:''; width:10px; height:10px; border-radius:50%; background:white; display:none; }
        .modal-radio.selected-present::after,
        .modal-radio.selected-absent::after { display:block; }
        .att-modal-footer { display:flex; gap:10px; align-items:center; }
        .att-quick-btn {
            padding:9px 16px; border:1.5px solid #e2e8f0; background:white; border-radius:8px;
            font-size:13px; font-weight:600; cursor:pointer; color:#1e293b; transition:all 0.2s;
        }
        .att-quick-btn:hover { border-color:#2563eb; color:#2563eb; }
        .att-save-btn {
            margin-left:auto; padding:10px 22px; background:#16a34a; color:white; border:none;
            border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; transition:background 0.2s;
        }
        .att-save-btn:hover { background:#15803d; }
        .att-cancel-btn {
            padding:10px 16px; border:1.5px solid #e2e8f0; background:white; border-radius:8px;
            font-size:14px; font-weight:500; cursor:pointer; color:#64748b; transition:all 0.2s;
        }
        .att-cancel-btn:hover { background:#f1f5f9; }

        /* ── Attendance History Modal ── */
        .att-hist-modal-overlay {
            display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4);
            z-index:3000; align-items:center; justify-content:center; padding:20px;
        }
        .att-hist-modal-overlay.active { display:flex; }
        .att-hist-modal {
            background:white; border-radius:14px; padding:0;
            max-width:560px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.2);
            max-height:80vh; overflow-y:auto;
        }
        .att-hist-header {
            display:flex; align-items:center; gap:10px; padding:20px 24px;
            border-bottom:1px solid #e2e8f0; position:sticky; top:0; background:white; z-index:1;
        }
        .att-hist-chip { font-size:12px; font-weight:700; padding:3px 8px; border-radius:4px; background:#dbeafe; color:#1e40af; }
        .att-hist-title { font-size:16px; font-weight:700; color:#1e293b; flex:1; }
        .att-hist-close {
            width:32px; height:32px; border:none; background:#f1f5f9;
            border-radius:8px; cursor:pointer; font-size:16px; color:#64748b;
            display:flex; align-items:center; justify-content:center;
        }
        .att-hist-close:hover { background:#e2e8f0; }
        .att-hist-session {
            border-bottom:1px solid #f1f5f9;
        }
        .att-hist-session-row {
            display:flex; align-items:center; padding:16px 24px;
            cursor:pointer; transition:background 0.15s; gap:12px;
        }
        .att-hist-session-row:hover { background:#f8fafc; }
        .att-hist-session-info { flex:1; }
        .att-hist-session-date { font-size:14px; font-weight:600; color:#1e293b; }
        .att-hist-session-sub  { font-size:12px; color:#94a3b8; margin-top:2px; }
        .att-hist-pct { font-weight:700; font-size:14px; margin-right:6px; }
        .att-hist-pct.good { color:#16a34a; }
        .att-hist-pct.warn { color:#f59e0b; }
        .att-hist-expand-icon { color:#94a3b8; font-size:12px; transition:transform 0.2s; }
        .att-hist-session.expanded .att-hist-expand-icon { transform:rotate(180deg); }
        /* Expanded student rows */
        .att-hist-students { display:none; background:#fafafa; border-top:1px solid #f1f5f9; }
        .att-hist-session.expanded .att-hist-students { display:block; }
        .att-hist-student-table { width:100%; border-collapse:collapse; }
        .att-hist-student-table th {
            font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase;
            padding:8px 24px; text-align:left; letter-spacing:0.5px;
        }
        .att-hist-student-table td { padding:10px 24px; font-size:13px; color:#1e293b; border-bottom:1px solid #f1f5f9; }
        .att-hist-student-table tr:last-child td { border-bottom:none; }
        .att-hist-student-table .sid { color:#94a3b8; font-size:12px; }
        .hist-radio-present { color:#16a34a; font-weight:700; font-size:18px; }
        .hist-radio-absent  { color:#dc2626; font-weight:700; font-size:18px; }
        .save-changes-btn {
            margin:12px 24px 16px; padding:9px 20px; background:#2563eb; color:white; border:none;
            border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:background 0.2s;
        }
        .save-changes-btn:hover { background:#1d4ed8; }

        /* Interactive history radio buttons */
        .hist-radio-btn { font-size:18px; color:#e2e8f0; transition:color 0.15s; padding:2px 4px; border-radius:4px; }
        .hist-radio-btn:hover { background:#f1f5f9; }
        .hist-radio-btn.hist-radio-present { color:#16a34a; }
        .hist-radio-btn.hist-radio-absent  { color:#dc2626; }

        /* ── Submissions layout: list + side panel ── */
        .submissions-layout {
            display:flex; gap:0; min-height:400px;
        }
        .submissions-left {
            flex:1; min-width:0; transition:all 0.25s ease;
        }
        .grade-side-panel {
            width:320px; flex-shrink:0;
            background:white; border-left:1px solid #e2e8f0;
            display:flex; flex-direction:column;
            margin-left:20px; border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.07);
            overflow:hidden;
        }

        /* ── Grade Panel internals (shared styles) ── */
        .grade-panel-header {
            padding:20px 24px 16px; border-bottom:1px solid #e2e8f0;
            display:flex; justify-content:space-between; align-items:flex-start;
        }
        .grade-panel-close {
            width:28px; height:28px; border:none; background:#f1f5f9;
            border-radius:6px; cursor:pointer; font-size:16px; color:#64748b;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .grade-panel-close:hover { background:#e2e8f0; }
        .grade-panel-title { font-size:16px; font-weight:700; color:#1e293b; }
        .grade-panel-body  { flex:1; padding:20px 24px; overflow-y:auto; }
        .grade-panel-file-box {
            display:flex; align-items:center; gap:8px; padding:10px 14px;
            border:1.5px solid #e2e8f0; border-radius:8px; margin-bottom:16px;
            font-size:14px; color:#475569;
        }
        .grade-panel-file-box img { width:16px; height:16px; opacity:0.6; }
        .grade-prev-box {
            background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px;
            padding:14px 16px; margin-bottom:16px;
        }        .grade-prev-label   { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px; }
        .grade-prev-score   { font-size:28px; font-weight:700; color:#16a34a; line-height:1; }
        .grade-prev-score span { font-size:16px; color:#94a3b8; font-weight:400; }
        .grade-prev-comment { font-size:13px; color:#475569; margin-top:6px; }
        .grade-field-label  { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px; display:block; }
        .grade-score-input {
            width:100%; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:8px;
            font-size:14px; outline:none; transition:border-color 0.2s; box-sizing:border-box;
            text-align:center; font-weight:600; margin-bottom:16px;
        }
        .grade-score-input:focus { border-color:#2563eb; }
        .grade-feedback-input {
            width:100%; min-height:100px; padding:10px 14px; border:1.5px solid #e2e8f0;
            border-radius:8px; font-size:14px; outline:none; resize:vertical;
            transition:border-color 0.2s; box-sizing:border-box; font-family:inherit;
        }
        .grade-feedback-input:focus { border-color:#2563eb; }
        .grade-panel-footer {
            padding:16px 24px; border-top:1px solid #e2e8f0;
            display:flex; gap:10px;
        }
        .grade-save-btn {
            flex:1; padding:11px; background:#2563eb; color:white; border:none;
            border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; transition:background 0.2s;
        }
        .grade-save-btn:hover { background:#1d4ed8; }
        .grade-cancel-btn {
            padding:11px 16px; border:1.5px solid #e2e8f0; background:white; border-radius:8px;
            font-size:14px; font-weight:500; cursor:pointer; color:#64748b; transition:all 0.2s;
        }
        .grade-cancel-btn:hover { background:#f1f5f9; }

        /* dark mode for grade panel */
        body.dark-mode .grade-side-panel { background:#1e293b; border-color:#334155; }
        body.dark-mode .grade-panel-header { border-color:#334155; }
        body.dark-mode .grade-panel-title  { color:#f1f5f9; }
        body.dark-mode .grade-panel-close  { background:#334155; color:#e2e8f0; }
        body.dark-mode .grade-panel-file-box { background:#334155; border-color:#475569; color:#94a3b8; }
        body.dark-mode .grade-prev-box { background:#052e16; border-color:#166534; }
        body.dark-mode .grade-prev-label { color:#6b7280; }
        body.dark-mode .grade-prev-comment { color:#94a3b8; }
        body.dark-mode .grade-field-label { color:#94a3b8; }
        body.dark-mode #gradePanelStudent { color:#f1f5f9; }
        body.dark-mode #gradePanelAssignment { color:#94a3b8; }
        body.dark-mode .grade-score-input, body.dark-mode .grade-feedback-input { background:#334155; border-color:#475569; color:#e2e8f0; }
        body.dark-mode .grade-panel-footer { border-color:#334155; }
        body.dark-mode .grade-cancel-btn   { background:#334155; border-color:#475569; color:#e2e8f0; }
        body.dark-mode .att-select, body.dark-mode .session-dropdown-trigger,
        body.dark-mode .att-modal, body.dark-mode .att-hist-modal { background:#1e293b; color:#e2e8f0; border-color:#334155; }
        body.dark-mode .session-dropdown-list { background:#1e293b; border-color:#475569; }
        body.dark-mode .session-dropdown-item { color:#e2e8f0; }
        body.dark-mode .session-dropdown-item:hover { background:#334155; }
        body.dark-mode .att-table-wrap, body.dark-mode .att-history-card { background:#1e293b; }
        body.dark-mode .att-table th { background:#1e293b; border-color:#334155; }
        body.dark-mode .att-table td { border-color:#334155; color:#e2e8f0; }
        body.dark-mode .att-table tbody tr:hover td { background:#334155; }
        body.dark-mode .att-history-label { color:#94a3b8; }
        body.dark-mode .att-history-card-title, body.dark-mode .att-stat-item .att-stat-num { color:#f1f5f9; }
        body.dark-mode .att-modal-field select, body.dark-mode .att-modal-field input { background:#334155; border-color:#475569; color:#e2e8f0; }
        body.dark-mode .att-modal-close, body.dark-mode .att-hist-close { background:#334155; color:#e2e8f0; }
        body.dark-mode .att-modal-title, body.dark-mode .att-hist-title,
        body.dark-mode .att-hist-session-date, body.dark-mode .attendance-section h3 { color:#f1f5f9; }
        body.dark-mode .att-hist-header { background:#1e293b; border-color:#334155; }
        body.dark-mode .att-hist-session { border-color:#334155; }
        body.dark-mode .att-hist-session-row:hover { background:#334155; }
        body.dark-mode .att-hist-students { background:#0f172a; border-color:#334155; }
        body.dark-mode .att-hist-student-table td { border-color:#334155; color:#e2e8f0; }
        body.dark-mode .att-quick-btn, body.dark-mode .att-cancel-btn { background:#334155; border-color:#475569; color:#e2e8f0; }
        body.dark-mode .att-modal-student-table th { border-color:#334155; }
        body.dark-mode .att-modal-student-table td { border-color:#334155; color:#e2e8f0; }
        body.dark-mode .dev-panel { background:#1e293b; border-color:#475569; }
        body.dark-mode .dev-panel-title { color:#f1f5f9; }
        body.dark-mode .dev-panel-copy { color:#cbd5e1; }

        /* dark mode */
        body.dark-mode .sidebar { background:#1e293b; }
        body.dark-mode .sidebar-icon::after { background:#334155; }
        body.dark-mode .sidebar-icon::before { border-right-color:#334155; }
        body.dark-mode .logout-icon { border-top-color:rgba(255,255,255,0.05); }
        body.dark-mode .top-bar, body.dark-mode .module-card,
        body.dark-mode .perf-table-wrap, body.dark-mode .detail-tabs,
        body.dark-mode .submission-list, body.dark-mode .att-select,
        body.dark-mode .filter-btn:not(.active), body.dark-mode .download-btn,
        body.dark-mode .search-box, body.dark-mode .sub-icon-btn { background:#1e293b; color:#e2e8f0; border-color:#334155; }
        body.dark-mode .detail-body { background:#0f172a; }
        body.dark-mode .page-title, body.dark-mode .page-header h1,
        body.dark-mode .module-name, body.dark-mode .detail-header-title,
        body.dark-mode .student-name, body.dark-mode .sub-student,
        body.dark-mode .attendance-section h3 { color:#f1f5f9; }
        body.dark-mode .perf-table th { background:#1e293b; border-bottom-color:#334155; }
        body.dark-mode .perf-table td { border-bottom-color:#334155; color:#e2e8f0; }
        body.dark-mode .perf-table tbody tr:hover td { background:#334155; }
        body.dark-mode .submission-item { border-bottom-color:#334155; }
        body.dark-mode .submission-item:hover { background:#334155; }
        body.dark-mode .module-card-footer { border-top-color:#334155; }
        body.dark-mode .tab-btn { color:#94a3b8; }
        body.dark-mode .tab-btn.active { color:#60a5fa; border-bottom-color:#60a5fa; }
        body.dark-mode .detail-tabs { border-bottom-color:#334155; }

        @media (max-width:768px) { .modules-grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="app-container">

    <aside class="sidebar">
        <div class="sidebar-toggle-btn"><img src="{{ asset('images/arrow_menu_open.png') }}" alt="Toggle"></div>
        <div class="sidebar-icons">
            <div class="sidebar-icon" data-page="dashboard" data-tooltip="Dashboard"><img src="{{ asset('images/home.png') }}" alt=""><span class="sidebar-label">Dashboard</span></div>
            <div class="sidebar-icon" data-page="timetable" data-tooltip="Timetable"><img src="{{ asset('images/calendar.png') }}" alt=""><span class="sidebar-label">Timetable</span></div>
            <div class="sidebar-icon active" data-page="modules" data-tooltip="My Modules"><img src="{{ asset('images/modules.png') }}" alt=""><span class="sidebar-label">My Modules</span></div>
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

        <!-- MODULE LIST VIEW -->
        <div class="list-view" id="listView">
            <div class="top-bar">
                <div class="logo-container">
                    <img src="{{ asset('images/sus_logo.png') }}" alt="SUS" class="logo-light">
                    <img src="{{ asset('images/sus_logo_dark.png') }}" alt="SUS" class="logo-dark">
                </div>
                <h1 class="page-title">Smart University System</h1>
            </div>
            <div class="page-header">
                <h1>My Modules</h1>
                <p>Manage your assigned courses, students and submissions</p>
            </div>
            <div class="modules-grid" id="modulesGrid">
                <p style="color:#64748b;font-size:14px;">Loading modules...</p>
            </div>
        </div>

        <!-- MODULE DETAIL VIEW -->
        <div class="detail-view" id="detailView">
            <!-- Blue header -->
            <div class="detail-header">
                <button class="back-btn" onclick="backToList()">&#8592; Back to Modules</button>
                <div class="detail-header-info">
                    <div class="detail-header-title" id="detailTitle">Data Structures</div>
                    <div class="detail-header-sub" id="detailSub">CS301 &bull; 15 credits &bull; Groups: CS-301-A, CS-301-B</div>
                </div>
                <div style="width:140px;"></div>
            </div>

            <!-- Tabs -->
            <div class="detail-tabs">
                <button class="tab-btn active" data-tab="attendance" onclick="switchTab('attendance')">Attendance</button>
                <button class="tab-btn" data-tab="performance" onclick="switchTab('performance')">Performance</button>
                <button class="tab-btn" data-tab="submissions" onclick="switchTab('submissions')">Submissions</button>
            </div>

            <!-- Tab bodies -->
            <div class="detail-body">

                <!-- ATTENDANCE TAB -->
                <div class="tab-content active" id="tab-attendance">
                    <div class="attendance-section">
                        <h3>Subject Attendance</h3>
                        <div class="attendance-filters">
                            <!-- Group select -->
                            <div class="att-field">
                                <label class="att-label">Group</label>
                                <div class="att-select-wrap">
                                    <select class="att-select" id="attGroupSelect" onchange="onAttGroupChange()"></select>
                                    <span class="att-select-arrow">&#9660;</span>
                                </div>
                            </div>
                            <!-- Session custom dropdown (pic 3 style) -->
                            <div class="att-field">
                                <label class="att-label">Session</label>
                                <div class="session-dropdown-wrap" id="sessionDropdownWrap">
                                    <button class="session-dropdown-trigger" id="sessionTrigger" onclick="toggleSessionDropdown()">
                                        <span id="sessionTriggerText">Select session...</span>
                                        <span style="color:#64748b;font-size:12px;">&#9660;</span>
                                    </button>
                                    <div class="session-dropdown-list" id="sessionDropdownList"></div>
                                </div>
                            </div>
                            <button class="add-att-btn" onclick="openAddAttModal()">+ Add Attendance</button>
                        </div>

                        <!-- Attendance history cards -->
                        <div id="attHistorySection">
                            <div class="att-history-label">Attendance History by Group</div>
                            <div class="att-history-grid" id="attHistoryGrid"></div>
                        </div>
                    </div>
                </div>

                <!-- PERFORMANCE TAB -->
                <div class="tab-content" id="tab-performance">
                    <div class="perf-toolbar">
                        <input class="search-box" id="searchInput" type="text" placeholder="Search student name or ID...">
                        <div class="filter-btns">
                            <button class="filter-btn active" data-group="all" onclick="filterGroup('all')">All Groups</button>
                        </div>
                    </div>
                    <div class="perf-table-wrap">
                        <table class="perf-table" id="perfTable">
                            <thead>
                                <tr>
                                    <th>STUDENT</th>
                                    <th>QUIZ 1</th>
                                    <th>MIDTERM</th>
                                    <th>HW 3</th>
                                    <th>LAB 1</th>
                                    <th>FINAL EXAM</th>
                                    <th>GRADE</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="perfTbody">
                                <tr><td colspan="8" style="text-align:center;color:#64748b;padding:32px;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SUBMISSIONS TAB -->
                <div class="tab-content" id="tab-submissions">
                    <div class="submissions-layout">
                        <!-- Left: list -->
                        <div class="submissions-left">
                            <div class="perf-toolbar" style="margin-bottom:20px;">
                                <div class="filter-btns">
                                    <button class="filter-btn active" data-subgroup="all" onclick="filterSubGroup('all')">All Groups</button>
                                </div>
                            </div>
                            <div class="submission-list" id="submissionList">
                                <p style="padding:24px;color:#64748b;">Loading...</p>
                            </div>
                        </div>
                        <!-- Right: grade panel (hidden until a submission is clicked) -->
                        <div class="grade-side-panel" id="gradeSidePanel" style="display:none;">
                            <div class="grade-panel-header">
                                <div>
                                    <div class="grade-panel-title">Grade Submission</div>
                                </div>
                                <button class="grade-panel-close" onclick="closeGradePanel()">&#x2715;</button>
                            </div>
                            <div class="grade-panel-body">
                                <div style="font-size:15px;font-weight:700;color:#1e293b;margin-bottom:2px;" id="gradePanelStudent"></div>
                                <div style="font-size:13px;color:#64748b;margin-bottom:16px;" id="gradePanelAssignment"></div>
                                <div class="grade-panel-file-box">
                                    <img src="{{ asset('images/doc.png') }}" alt="">
                                    <span id="gradePanelFile">file.pdf</span>
                                </div>
                                <div class="grade-prev-box" id="gradePrevBox" style="display:none;">
                                    <div class="grade-prev-label">Previous Grade</div>
                                    <div class="grade-prev-score" id="gradePrevScore">—<span>/100</span></div>
                                    <div class="grade-prev-comment" id="gradePrevComment"></div>
                                </div>
                                <label class="grade-field-label">Score (0 – 100)</label>
                                <input class="grade-score-input" type="number" id="gradeScoreInput" min="0" max="100" placeholder="—">
                                <label class="grade-field-label">Feedback &amp; Comments</label>
                                <textarea class="grade-feedback-input" id="gradeFeedbackInput" placeholder="Write feedback..."></textarea>
                            </div>
                            <div class="grade-panel-footer">
                                <button class="grade-save-btn" onclick="saveGrade()">Save Grade</button>
                                <button class="grade-cancel-btn" onclick="closeGradePanel()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<!-- Add Attendance Modal -->
<div class="att-modal-overlay" id="addAttModal">
    <div class="att-modal">
        <div class="att-modal-header">
            <div class="att-modal-title">Add Attendance – New Session</div>
            <button class="att-modal-close" onclick="closeAddAttModal()">&#x2715;</button>
        </div>
        <div class="att-modal-fields">
            <div class="att-modal-field">
                <label>Group <span class="req">*</span></label>
                <select id="addAttGroup" onchange="refreshModalStudents()"></select>
            </div>
            <div class="att-modal-field">
                <label>Date <span class="req">*</span></label>
                <input type="date" id="addAttDate">
            </div>
            <div class="att-modal-field">
                <label>Time</label>
                <input type="time" id="addAttTime" value="09:00">
            </div>
        </div>
        <div class="att-modal-students-label">Students</div>
        <table class="att-modal-student-table" id="addAttStudentTable">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th style="text-align:center;">Present</th>
                    <th style="text-align:center;">Absent</th>
                </tr>
            </thead>
            <tbody id="addAttStudentBody"></tbody>
        </table>
        <div class="att-modal-footer">
            <button class="att-quick-btn" onclick="setAllModalStatus('present')">All Present</button>
            <button class="att-quick-btn" onclick="setAllModalStatus('absent')">All Absent</button>
            <button class="att-cancel-btn" onclick="closeAddAttModal()">Cancel</button>
            <button class="att-save-btn" onclick="saveAttendance()">Save Attendance</button>
        </div>
    </div>
</div>

<!-- Attendance History Modal -->
<div class="att-hist-modal-overlay" id="attHistModal">
    <div class="att-hist-modal">
        <div class="att-hist-header">
            <span class="att-hist-chip" id="histChip">CS-301-A</span>
            <span class="att-hist-title" id="histTitle">Data Structures – Attendance History</span>
            <button class="att-hist-close" onclick="closeHistModal()">&#x2715;</button>
        </div>
        <div id="histSessionsList"></div>
    </div>
</div>

<script src="{{ asset('js/api.js') }}"></script>
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

    let currentModule = null;
    let currentGroupFilter = 'all';
    let currentSubGroupFilter = 'all';
    let selectedSessionKey = null; // "2026-03-20 · 09:00" format

    // Search filter
    document.getElementById('searchInput').addEventListener('input', function() {
        renderPerformanceTable(currentGroupFilter);
    });

    async function loadModules() {
        try {
            var res = await TeacherAPI.getModules();
            var modules = res.data || [];
            var grid = document.getElementById('modulesGrid');
            if (modules.length === 0) { grid.innerHTML = '<p style="color:#64748b;">No modules assigned.</p>'; return; }
            grid.innerHTML = '';
            modules.forEach(function(m) {
                var card = document.createElement('div');
                card.className = 'module-card';
                var groupChips = (m.groups || []).map(function(g) { return '<span class="group-chip">'+g+'</span>'; }).join('');
                card.innerHTML =
                    '<div class="module-card-top">' +
                        '<div class="module-icon-wrap"><img src="{{ asset('images/modules.png') }}" alt=""></div>' +
                        '<div class="module-credits-badge">'+m.credits+' Credits</div>' +
                    '</div>' +
                    '<div class="module-name">'+m.name+'</div>' +
                    '<div class="module-code">'+m.code+'</div>' +
                    '<div class="module-card-footer">' +
                        '<div class="module-groups">'+groupChips+'</div>' +
                        '<div class="module-students"><img src="{{ asset('images/person.png') }}" style="width:14px;height:14px;vertical-align:middle;"> '+(m.totalStudents||0)+' Students</div>' +
                    '</div>';
                card.addEventListener('click', function() { openModuleDetail(m.id); });
                grid.appendChild(card);
            });
        } catch(err) {
            document.getElementById('modulesGrid').innerHTML = '<p style="color:#dc2626;">Could not load modules.</p>';
        }
    }

    async function openModuleDetail(moduleId) {
        document.getElementById('listView').style.display = 'none';
        document.getElementById('detailView').classList.add('active');
        document.getElementById('perfTbody').innerHTML = '<tr><td colspan="8" style="text-align:center;color:#64748b;padding:32px;">Loading...</td></tr>';
        document.getElementById('submissionList').innerHTML = '<p style="padding:24px;color:#64748b;">Loading...</p>';

        try {
            var res = await TeacherAPI.getModuleDetails(moduleId);
            currentModule = res.data;
            selectedSession = null;
            document.getElementById('sessionTriggerText').textContent = 'Select session...';

            document.getElementById('detailTitle').textContent = currentModule.name;
            document.getElementById('detailSub').textContent   =
                currentModule.code + ' \u2022 ' + currentModule.credits + ' credits \u2022 Groups: ' + (currentModule.groups || []).join(', ');

            // Performance and submissions are intentionally kept as placeholders for now.
            var searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.value = '';
                searchInput.disabled = true;
                searchInput.placeholder = 'Performance section is in development';
            }

            var filterBtns = document.querySelector('#tab-performance .filter-btns');
            filterBtns.innerHTML = '<button class="filter-btn active" type="button" disabled>In Development</button>';
            /*
            filterBtns.innerHTML = '<button class="filter-btn active" data-group="all" onclick="filterGroup(\'all\')">All Groups</button>';
            (currentModule.groups || []).forEach(function(g) {
                var btn = document.createElement('button');
                btn.className = 'filter-btn';
                btn.setAttribute('data-group', g);
                btn.textContent = g;
                btn.onclick = function() { filterGroup(g); };
                filterBtns.appendChild(btn);
            });
            */

            var subFilterBtns = document.querySelector('#tab-submissions .filter-btns');
            subFilterBtns.innerHTML = '<button class="filter-btn active" type="button" disabled>In Development</button>';
            /*
            subFilterBtns.innerHTML = '<button class="filter-btn active" data-subgroup="all" onclick="filterSubGroup(\'all\')">All Groups</button>';
            (currentModule.groups || []).forEach(function(g) {
                var btn = document.createElement('button');
                btn.className = 'filter-btn';
                btn.setAttribute('data-subgroup', g);
                btn.textContent = g;
                btn.onclick = function() { filterSubGroup(g); };
                subFilterBtns.appendChild(btn);
            });
            */

            currentGroupFilter = 'all';
            currentSubGroupFilter = 'all';
            renderPerformanceTable('all');
            renderSubmissions('all');


            await loadModuleAttendance(currentModule.id);   // loads → ATT_SESSIONS
            populateAttendance();

        } catch(err) {
            console.error('[ModuleDetail]', err.message);
        }
    }

    function filterGroup(group) {
        currentGroupFilter = group;
        document.querySelectorAll('#tab-performance .filter-btn').forEach(function(b) {
            b.classList.toggle('active', b.getAttribute('data-group') === group);
        });
        renderPerformanceTable(group);
    }

    function filterSubGroup(group) {
        currentSubGroupFilter = group;
        document.querySelectorAll('#tab-submissions .filter-btn').forEach(function(b) {
            b.classList.toggle('active', b.getAttribute('data-subgroup') === group);
        });
        renderSubmissions(group);
    }

    function getGradeClass(grade) {
        if (!grade) return '';
        var g = grade.charAt(0);
        if (g==='A') return 'grade-a';
        if (g==='B') return 'grade-b';
        if (g==='C') return 'grade-c';
        if (g==='D') return 'grade-d';
        return 'grade-f';
    }

    function renderPerformanceTable(groupFilter) {
        var tbody = document.getElementById('perfTbody');
        if (!tbody) return;

        tbody.innerHTML =
            '<tr><td colspan="8" style="padding:24px;">' +
                '<div class="dev-panel">' +
                    '<div class="dev-panel-title">Performance section is currently in development</div>' +
                    '<div class="dev-panel-copy">The previous gradebook implementation has been intentionally disabled while the database-driven version is being rebuilt.</div>' +
                '</div>' +
            '</td></tr>';

        // Legacy implementation intentionally kept below but disabled during development.
        return;
        if (!currentModule) return;
        var search = (document.getElementById('searchInput').value || '').toLowerCase();
        var students = (currentModule.students || []).filter(function(s) {
            var matchGroup = groupFilter === 'all' || s.group === groupFilter;
            var matchSearch = !search || s.name.toLowerCase().includes(search) || s.id.includes(search);
            return matchGroup && matchSearch;
        });

        var tbody = document.getElementById('perfTbody');
        if (students.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#64748b;padding:32px;">No students found.</td></tr>';
            return;
        }
        tbody.innerHTML = students.map(function(s) {
            function score(v) { return v !== null && v !== undefined ? v : '<span class="score-dash">\u2014</span>'; }
            var gc = getGradeClass(s.grade);
            return '<tr>' +
                '<td><div class="student-name">'+s.name+'</div><div class="student-id">'+s.id+'</div><div class="group-chip-sm">'+s.group+'</div></td>' +
                '<td>'+score(s.quiz1)+'</td>' +
                '<td>'+score(s.midterm)+'</td>' +
                '<td>'+score(s.hw3)+'</td>' +
                '<td>'+score(s.lab1)+'</td>' +
                '<td>'+score(s.finalExam)+'</td>' +
                '<td><span class="grade-badge '+gc+'">'+( s.grade||'—')+'</span></td>' +
                '<td><span class="status-'+(s.status==='PASS'?'pass':'fail')+'">'+s.status+'</span></td>' +
            '</tr>';
        }).join('');
    }

    // Mock grade data keyed by student name
    var GRADE_DATA = {
        'Emma Johnson':  { score: 79, feedback: 'Good work overall. Watch indexing errors.' },
        'Liam Park':     { score: 91, feedback: 'Excellent submission.' },
        'Priya Sharma':  { score: null, feedback: '' },
        'Emma Wilson':   { score: 87, feedback: 'Well structured. Minor formatting issues.' },
        'Sofia Rossi':   { score: 78, feedback: 'Satisfactory.' },
        'Marcus Owen':   { score: 62, feedback: 'Needs improvement on edge cases.' }
    };

    var gradeTarget = null; // current submission being graded

    function renderSubmissions(groupFilter) {
        var list = document.getElementById('submissionList');
        if (!list) return;

        list.innerHTML =
            '<div class="dev-panel">' +
                '<div class="dev-panel-title">Submissions section is currently in development</div>' +
                '<div class="dev-panel-copy">The older submission list and grading flow have been intentionally paused until the new backend-connected version is ready.</div>' +
            '</div>';

        document.getElementById('gradeSidePanel').style.display = 'none';

        // Legacy implementation intentionally kept below but disabled during development.
        return;
        if (!currentModule) return;
        var subs = (currentModule.submissions || []).filter(function(s) {
            return groupFilter === 'all' || s.group === groupFilter;
        });
        var list = document.getElementById('submissionList');
        if (subs.length === 0) { list.innerHTML = '<p style="padding:24px;color:#64748b;">No submissions found.</p>'; return; }
        list.innerHTML = subs.map(function(s, idx) {
            var barCls = s.group && s.group.endsWith('-A') ? 'group-a' : 'group-b';
            return '<div class="submission-item" id="subItem_' + idx + '">' +
                '<div class="sub-left-bar ' + barCls + '"></div>' +
                '<div class="sub-info">' +
                    '<div class="sub-student">' + s.student + '<span class="sub-group-chip">' + s.group + '</span></div>' +
                    '<div class="sub-assignment">' + s.assignment + '</div>' +
                    '<div class="sub-meta">' + s.date + ' &middot; ' + s.file + '</div>' +
                '</div>' +
                '<div class="sub-icons">' +
                    '<button class="sub-icon-btn" title="Grade submission" onclick="openGradePanel(\'' + encodeURIComponent(JSON.stringify(s)) + '\')"><img src="{{ asset('images/grade.png') }}" alt="Grade"></button>' +
                    '<button class="sub-icon-btn" title="Download PDF" onclick="alert(\'Download: \' + \'' + s.file + '\')"><img src="{{ asset('images/download.png') }}" alt="Download"></button>' +
                    '<button class="sub-icon-btn" title="View PDF" onclick="alert(\'View: \' + \'' + s.file + '\')"><img src="{{ asset('images/see.png') }}" alt="View"></button>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function openGradePanel(encodedSub) {
        var s = JSON.parse(decodeURIComponent(encodedSub));
        gradeTarget = s;
        var gradeInfo = GRADE_DATA[s.student] || {};
        var hasGrade  = gradeInfo.score !== null && gradeInfo.score !== undefined;

        document.getElementById('gradePanelStudent').textContent    = s.student + ' \u00b7 ' + s.group;
        document.getElementById('gradePanelAssignment').textContent = 'HW 3 \u2013 ' + s.assignment;
        document.getElementById('gradePanelFile').textContent       = s.file;
        document.getElementById('gradeScoreInput').value    = hasGrade ? gradeInfo.score : '';
        document.getElementById('gradeFeedbackInput').value = gradeInfo.feedback || '';

        var prevBox = document.getElementById('gradePrevBox');
        if (hasGrade) {
            prevBox.style.display = '';
            document.getElementById('gradePrevScore').innerHTML = gradeInfo.score + '<span>/100</span>';
            document.getElementById('gradePrevComment').textContent = gradeInfo.feedback || '';
        } else {
            prevBox.style.display = 'none';
        }
        document.getElementById('gradeSidePanel').style.display = 'flex';
        document.getElementById('gradeSidePanel').style.flexDirection = 'column';
    }

    function closeGradePanel() {
        document.getElementById('gradeSidePanel').style.display = 'none';
        gradeTarget = null;
    }

    async function saveGrade() {
        if (!gradeTarget) return;
        var score    = parseInt(document.getElementById('gradeScoreInput').value);
        var feedback = document.getElementById('gradeFeedbackInput').value.trim();
        if (isNaN(score) || score < 0 || score > 100) {
            alert('Please enter a score between 0 and 100.');
            return;
        }
        var btn = document.querySelector('.grade-save-btn');
        btn.disabled = true; btn.textContent = 'Saving...';
        try {
            await TeacherAPI.gradeSubmission(
                currentModule ? currentModule.id : 'M1',
                gradeTarget.student,
                gradeTarget.assignment,
                score,
                feedback
            );
            // Update mock data in memory
            GRADE_DATA[gradeTarget.student] = { score: score, feedback: feedback };
            if (currentModule) {
                currentModule.students.forEach(function(st) {
                    if (st.name === gradeTarget.student) st.hw3 = score;
                });
            }
            btn.textContent = '✓ Saved!';
            btn.style.background = '#16a34a';
            setTimeout(function() {
                btn.disabled = false; btn.textContent = 'Save Grade'; btn.style.background = '';
                closeGradePanel();
            }, 1000);
        } catch(err) {
            btn.disabled = false; btn.textContent = 'Save Grade';
            alert('Could not save grade. Please try again.');
        }
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.classList.toggle('active', b.getAttribute('data-tab') === tabName);
        });
        document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
        document.getElementById('tab-'+tabName).classList.add('active');
    }

    function backToList() {
        document.getElementById('detailView').classList.remove('active');
        document.getElementById('listView').style.display = '';
        currentModule = null;
    }

    // === REAL ATTENDANCE FROM DATABASE ===
    var ATT_SESSIONS = {}; // will be { "BSc Computer Science": [sessions...], "MSc Artificial Intelligence": [...] }

    async function loadModuleAttendance(moduleId) {
        try {
            // console.log('[Attendance] Requesting for module:', moduleId);
            
            const res = await TeacherAPI.getModuleAttendance(moduleId);
            // console.log('[Attendance] Raw API response:', res);
            // console.log('[Attendance] Sessions count:', res?.data?.sessions?.length || 0);

            ATT_SESSIONS = {};

            // Get real groups from currentModule (fallback if not ready)
            const groups = currentModule?.groups || ["All Groups"];
            const allSessions = res?.data?.sessions || [];

            // Filter sessions per group based on student.group field
            groups.forEach(groupName => {
                ATT_SESSIONS[groupName] = allSessions.filter(sess => 
                    sess.students.some(student => 
                        (student.group || 'Unknown') === groupName
                    )
                );
            });

            // Fallback: if no sessions matched any group (e.g. backend didn't send group yet)
            // put everything in "All Groups"
            const hasAnyData = Object.values(ATT_SESSIONS).some(arr => arr.length > 0);
            if (!hasAnyData && allSessions.length > 0) {
                ATT_SESSIONS["All Groups"] = allSessions;
                console.warn('[Attendance] No group-specific data → fallback to All Groups');
            }

            // console.log('[Attendance] Final ATT_SESSIONS:', ATT_SESSIONS);
            // console.log('[Attendance] Sessions per group:', 
            //     Object.fromEntries(
            //         Object.entries(ATT_SESSIONS).map(([k, v]) => [k, v.length])
            //     )
            // );

            // Force re-render UI with the grouped data
            populateAttendance();

        } catch (err) {
            console.error('[Attendance] Load failed:', err.message);
            ATT_SESSIONS = {};
            populateAttendance(); // still try to render (will show empty state)
        }
    }

    var selectedSession = null;  // currently selected session key  "date · time"

    function calcPct(sessions) {
        var total = 0, present = 0;
        sessions.forEach(function(s) {
            s.students.forEach(function(st) {
                total++;
                if (st.status === 'present') present++;
            });
        });
        return total === 0 ? 0 : Math.round(present / total * 100);
    }

    function pctClass(p) { return p >= 80 ? 'good' : p >= 60 ? 'warn' : 'bad'; }

    function populateAttendance() {
        if (!currentModule) return;

        // Group select — only "All Groups" since we merged
        const grpSel = document.getElementById('attGroupSelect');
        grpSel.innerHTML = '<option value="__all__">All Groups</option>';

        // Session dropdown
        buildSessionDropdown('__all__');

        // Render history
        renderAttHistoryCards();
    }

    function buildSessionDropdown(groupKey) {
        const list = document.getElementById('sessionDropdownList');
        if (!list) return;
        list.innerHTML = '';

        let sessions = [];

        // Get real past sessions for this group
        const groupSessions = (groupKey === '__all__') 
            ? Object.values(ATT_SESSIONS).flat() 
            : (ATT_SESSIONS[groupKey] || []);

        // Sort by date descending (newest first)
        groupSessions.sort((a,b) => new Date(b.date) - new Date(a.date));

        // Add real past sessions
        groupSessions.forEach(s => {
            const key = `${s.date} · ${s.time}`;
            // const label = `${s.date} · ${s.time} (${s.students.length} students)`;
            const label = `${s.date} · ${s.time}`;
            if (!sessions.some(x => x.key === key)) {
                sessions.push({ key, label });
            }
        });

        // Optional: add a few future slots for "Add Attendance"
        const today = new Date();
        for (let i = 1; i <= 3; i++) {
            const d = new Date(today);
            d.setDate(today.getDate() + i * 7);
            const dateStr = d.toISOString().slice(0,10);
            const key = `${dateStr} · 09:00`;
            const label = `${dateStr} · 09:00 (New session)`;
            sessions.push({ key, label, isFuture: true });
        }

        // Populate dropdown
        sessions.forEach(s => {
            const item = document.createElement('div');
            item.className = 'session-dropdown-item' + (selectedSession === s.key ? ' selected' : '');
            item.textContent = s.label;
            item.onclick = () => selectSession(s.key, s.label, s.isFuture);
            list.appendChild(item);
        });

        // Default to newest real session or first future
        if (sessions.length > 0 && !selectedSession) {
            selectSession(sessions[0].key, sessions[0].label, sessions[0].isFuture);
        }
    }

    function selectSession(key, label) {
        selectedSession = key;
        document.getElementById('sessionTriggerText').textContent = label;
        document.getElementById('sessionDropdownList').classList.remove('open');
        document.getElementById('sessionTrigger').classList.remove('open');
        // Selecting a future session shows nothing — teacher must click Add Attendance
        // History cards remain visible
        renderAttHistoryCards();
    }

    function toggleSessionDropdown() {
        var list = document.getElementById('sessionDropdownList');
        var trigger = document.getElementById('sessionTrigger');
        list.classList.toggle('open');
        trigger.classList.toggle('open');
    }

    function onAttGroupChange() {
        selectedSession = null;
        document.getElementById('sessionTriggerText').textContent = 'Select session...';
        
        const g = document.getElementById('attGroupSelect').value;
        if (g) {
            buildSessionDropdown(g);   // ← this was sometimes failing silently
        }
        renderAttHistoryCards();
    }

    function renderAttHistoryCards() {
        const grid = document.getElementById('attHistoryGrid');
        grid.innerHTML = '';

        const groups = currentModule?.groups || [];

        // Skip rendering if no groups at all
        if (groups.length === 0) {
            grid.innerHTML = '<p style="color:#64748b;padding:20px;text-align:center;">No groups assigned to this module.</p>';
            return;
        }

        let anyRendered = false;

        groups.forEach((groupName, idx) => {
            const groupSessions = ATT_SESSIONS[groupName] || [];

            // Skip this group if no sessions AND no students in group
            if (groupSessions.length === 0) {
                // Optional: still show empty card if you want to indicate "no records yet"
                // But for now — hide completely
                return;
            }

            // Count unique students in this group's sessions
            const uniqueStudents = new Set();
            groupSessions.forEach(s => {
                s.students.forEach(st => uniqueStudents.add(st.id));
            });

            // Hide group if zero students appeared in any session
            if (uniqueStudents.size === 0) {
                return;
            }

            anyRendered = true;

            // Calculate stats
            let totalAtt = 0, totalPres = 0;
            groupSessions.forEach(s => {
                s.students.forEach(st => {
                    totalAtt++;
                    if (st.status === 'present') totalPres++;
                });
            });

            const pct = totalAtt > 0 ? Math.round((totalPres / totalAtt) * 100) : 0;
            const pc = pctClass(pct);

            const studentCount = uniqueStudents.size;

            const chipCls = idx === 0 ? 'a' : idx === 1 ? 'b' : 'a'; // blue, orange, repeat

            const card = document.createElement('div');
            card.className = 'att-history-card';
            card.onclick = () => openHistModal(groupName);

            card.innerHTML = `
                <div class="att-history-card-top">
                    <span class="att-history-chip ${chipCls}">${groupName}</span>
                    <span class="att-pct ${pc}">${pct}%</span>
                </div>
                <div class="att-history-card-title">${currentModule?.name || 'Data Structures'}</div>
                <div class="att-history-card-sub">
                    ${studentCount} student${studentCount === 1 ? '' : 's'} • ${groupSessions.length} session${groupSessions.length === 1 ? '' : 's'}
                </div>
                <div class="att-history-card-stats">
                    <div class="att-stat-item">
                        <div class="att-stat-num">${groupSessions.length}</div>
                        <div class="att-stat-label">Sessions</div>
                    </div>
                    <div class="att-stat-item">
                        <div class="att-stat-pct ${pc}">${pct}%</div>
                        <div class="att-stat-label">Attendance</div>
                    </div>
                </div>
            `;

            grid.appendChild(card);
        });

        // If no groups had students → show message
        if (!anyRendered) {
            grid.innerHTML = '<p style="color:#64748b;padding:24px;text-align:center;">No students with attendance records in any group yet.</p>';
        }
    }
    // ── Add Attendance Modal ──
    function openAddAttModal() {
        try{
            // Populate group dropdown
            var sel = document.getElementById('addAttGroup');
            sel.innerHTML = '';
            (currentModule.groups || []).forEach(function(g) {
                var o = document.createElement('option'); o.value = g; o.textContent = g; sel.appendChild(o);
            });
            var today = new Date(); var yyyy = today.getFullYear();
            var mm = String(today.getMonth()+1).padStart(2,'0'); var dd = String(today.getDate()).padStart(2,'0');
            var todayStr = yyyy+'-'+mm+'-'+dd;
            var dateInput = document.getElementById('addAttDate');
            var timeInput = document.getElementById('addAttTime');
            dateInput.min = todayStr;
            // Pre-fill from selected session if one is chosen
            if (selectedSession) {
                var parts = selectedSession.split(' · ');
                dateInput.value = parts[0] ? parts[0].trim() : todayStr;
                timeInput.value = parts[1] ? parts[1].trim() : '09:00';
            } else {
                dateInput.value = todayStr;
                timeInput.value = '09:00';
            }
            refreshModalStudents();
            document.getElementById('addAttModal').classList.add('active');
        }catch(err) {
            console.error('[ModuleDetail]', err.message);
        }
    }

    function refreshModalStudents() {
        var grp = document.getElementById('addAttGroup').value;
        var students = [];

        (ATT_SESSIONS[grp] || []).forEach(function(s) {
            s.students.forEach(function(st) {
                if (!students.find(function(x) { return x.id === st.id; })) {
                    students.push({ id: st.id, name: st.name, status: 'present' });
                }
            });
        });

        if (students.length === 0 && currentModule) {
            (currentModule.students || [])
                .filter(function(s) { return s.group === grp; })
                .forEach(function(s) {
                    students.push({ id: s.id, name: s.name, status: 'present' });
                });
        }

        var tbody = document.getElementById('addAttStudentBody');
        tbody.innerHTML = students.map(function(st) {
            return '<tr>' +
                '<td><span class="sid">' + st.id + '</span></td>' +
                '<td style="font-weight:600;">' + st.name + '</td>' +
                '<td style="text-align:center;">' + modalRadioBtn(st.id, 'present', true) + '</td>' +
                '<td style="text-align:center;">' + modalRadioBtn(st.id, 'absent', false) + '</td>' +
            '</tr>';
        }).join('');
    }

    function modalRadioBtn(id, status, selected) {
        var cls = selected ? 'modal-radio selected-' + status : 'modal-radio';
        return '<div class="' + cls + '" onclick="selectModalStatus(\'' + id + '\',\'' + status + '\',this)"></div>';
    }

    function selectModalStatus(studentId, status, el) {
        var row = el.closest('tr');
        row.querySelectorAll('.modal-radio').forEach(function(r) {
            r.className = 'modal-radio';
        });
        el.className = 'modal-radio selected-' + status;
    }

    function setAllModalStatus(status) {
        document.querySelectorAll('#addAttStudentBody tr').forEach(function(row) {
            row.querySelectorAll('.modal-radio').forEach(function(r) {
                r.className = 'modal-radio';
            });
            var radios = row.querySelectorAll('.modal-radio');
            var idx = status === 'present' ? 0 : 1;
            if (radios[idx]) {
                radios[idx].className = 'modal-radio selected-' + status;
            }
        });
    }

    async function saveAttendance() {
        const grp = document.getElementById('addAttGroup').value;
        const date = document.getElementById('addAttDate').value;
        const time = document.getElementById('addAttTime').value || '09:00';

        if (!date) {
            alert('Please select a date.');
            return;
        }

        const attendances = [];
        document.querySelectorAll('#addAttStudentBody tr').forEach(row => {
            const id = row.querySelector('.sid')?.textContent;
            if (!id) return;

            let status = 'present';
            const radios = row.querySelectorAll('.modal-radio');
            if (radios[1]?.classList.contains('selected-absent')) status = 'absent';

            attendances.push({ id: parseInt(id, 10), status });
        });

        const btn = document.querySelector('.att-save-btn');
        btn.disabled = true;
        btn.textContent = 'Saving...';

        try {
            const response = await TeacherAPI.saveAttendance(currentModule.id, grp, date, time, attendances);
            console.log('NEW ATTENDANCE -> Parsed response:', response);

            await loadModuleAttendance(currentModule.id);
            populateAttendance();
            // alert('Attendance saved successfully!');
            closeAddAttModal();
        } catch (err) {
            console.error('Save new attendance failed:', err);
            alert('Save failed. Please try again.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save Attendance';
        }
    }

    function openHistModal(group) {
        if (!currentModule) return;

        histEditState = {};
        document.getElementById('histChip').textContent = group;
        document.getElementById('histTitle').textContent = `${currentModule?.name || 'Module'} - Attendance History (${group})`;

        const sessions = ATT_SESSIONS[group] || [];
        const list = document.getElementById('histSessionsList');

        if (sessions.length === 0) {
            list.innerHTML = '<p style="padding:24px;color:#64748b;text-align:center;">No attendance records for this group yet.</p>';
            return;
        }

        list.innerHTML = sessions.map(function(s, sIdx) {
            var present = s.students.filter(function(x) { return x.status === 'present'; }).length;
            var absent  = s.students.filter(function(x) { return x.status === 'absent'; }).length;
            var pct     = Math.round(present / s.students.length * 100);
            var pc      = pctClass(pct);

            var studentRows = s.students.map(function(st, stIdx) {
                function radioCircle(statusVal) {
                    var isActive = st.status === statusVal;
                    var colorCls = statusVal === 'present' ? 'hist-radio-present' : 'hist-radio-absent';
                    var labelLetter = statusVal === 'present' ? 'P' : 'A';

                    return '<span class="hist-radio-btn ' + (isActive ? colorCls + ' active' : '') + '" ' +
                        'data-group="' + group + '" data-sidx="' + sIdx + '" data-stidx="' + stIdx + '" data-status="' + statusVal + '" ' +
                        'onclick="toggleHistRadio(this)" style="cursor:pointer;user-select:none;">' +
                        '&#9679; <small style="color:#94a3b8;font-size:11px;">' + labelLetter + '</small></span>';
                }

                return '<tr>' +
                    '<td><span class="sid">' + st.id + '</span></td>' +
                    '<td style="font-weight:600;">' + st.name + '</td>' +
                    '<td style="text-align:center;">' + radioCircle('present') + '</td>' +
                    '<td style="text-align:center;">' + radioCircle('absent') + '</td>' +
                '</tr>';
            }).join('');

            return '<div class="att-hist-session" id="histSess_' + sIdx + '">' +
                '<div class="att-hist-session-row" onclick="toggleHistSession(' + sIdx + ')">' +
                    '<div class="att-hist-session-info">' +
                        '<div class="att-hist-session-date">' + s.date + ' &middot; ' + s.time + '</div>' +
                        '<div class="att-hist-session-sub" id="histSub_' + sIdx + '">' + present + ' present &bull; ' + absent + ' absent</div>' +
                    '</div>' +
                    '<span class="att-hist-pct ' + pc + '" id="histPct_' + sIdx + '">' + pct + '%</span>' +
                    '<span class="att-hist-expand-icon">&#9660;</span>' +
                '</div>' +
                '<div class="att-hist-students">' +
                    '<table class="att-hist-student-table">' +
                        '<thead><tr><th>ID</th><th>Name</th><th style="text-align:center;">Present</th><th style="text-align:center;">Absent</th></tr></thead>' +
                        '<tbody id="histTbody_' + sIdx + '">' + studentRows + '</tbody>' +
                    '</table>' +
                    '<button class="save-changes-btn" onclick="saveHistChanges(\'' + group + '\',' + sIdx + ')">Save Changes</button>' +
                '</div>' +
            '</div>';
        }).join('');

        document.getElementById('attHistModal').classList.add('active');
    }

    function toggleHistRadio(el) {
        var group  = el.getAttribute('data-group');
        var sIdx   = parseInt(el.getAttribute('data-sidx'), 10);
        var stIdx  = parseInt(el.getAttribute('data-stidx'), 10);
        var status = el.getAttribute('data-status');

        ATT_SESSIONS[group][sIdx].students[stIdx].status = status;

        var tbody = document.getElementById('histTbody_' + sIdx);
        var st = ATT_SESSIONS[group][sIdx].students[stIdx];

        function radioCircle(statusVal) {
            var isActive = st.status === statusVal;
            var colorCls = statusVal === 'present' ? 'hist-radio-present' : 'hist-radio-absent';
            var labelLetter = statusVal === 'present' ? 'P' : 'A';

            return '<span class="hist-radio-btn ' + (isActive ? colorCls + ' active' : '') + '" ' +
                'data-group="' + group + '" data-sidx="' + sIdx + '" data-stidx="' + stIdx + '" data-status="' + statusVal + '" ' +
                'onclick="toggleHistRadio(this)" style="cursor:pointer;user-select:none;">' +
                '&#9679; <small style="color:#94a3b8;font-size:11px;">' + labelLetter + '</small></span>';
        }

        var tr = tbody.querySelectorAll('tr')[stIdx];
        if (tr) {
            tr.cells[2].innerHTML = radioCircle('present');
            tr.cells[3].innerHTML = radioCircle('absent');
        }

        var students = ATT_SESSIONS[group][sIdx].students;
        var present = students.filter(function(x) { return x.status === 'present'; }).length;
        var absent  = students.filter(function(x) { return x.status === 'absent'; }).length;
        var pct     = Math.round(present / students.length * 100);
        var subEl   = document.getElementById('histSub_' + sIdx);
        var pctEl   = document.getElementById('histPct_' + sIdx);

        if (subEl) subEl.textContent = present + ' present • ' + absent + ' absent';
        if (pctEl) {
            pctEl.textContent = pct + '%';
            pctEl.className = 'att-hist-pct ' + pctClass(pct);
        }
    }

    async function saveHistChanges(group, sIdx) {
        const session = ATT_SESSIONS[group][sIdx];
        const date = session.date;
        const time = session.time;

        const attendances = session.students.map(st => ({
            id: st.id,
            status: st.status
        }));

        const btn = document.querySelector(`#histSess_${sIdx} .save-changes-btn`);
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Saving...';
        }

        try {
            const response = await apiCall(`/teacher/modules/${currentModule.id}/attendance`, {
                method: 'POST',
                body: JSON.stringify({
                    groupId: group,
                    date: date,
                    time: time,
                    students: attendances
                })
            });

            console.log('HISTORY SAVE -> Parsed response:', response);

            await loadModuleAttendance(currentModule.id);
            populateAttendance();
            openHistModal(group);

            if (btn) {
                btn.textContent = 'Saved!';
                btn.style.background = '#16a34a';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = 'Save Changes';
                    btn.style.background = '';
                }, 1500);
            }
        } catch (err) {
            console.error('Save history changes failed:', err);
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Save Changes';
            }
            alert('Save failed. Please try again.');
        }
    }

    function closeAddAttModal() {
        document.getElementById('addAttModal').classList.remove('active');
    }

    function toggleHistSession(idx) {
        var el = document.getElementById('histSess_' + idx);
        el.classList.toggle('expanded');
    }

    function closeHistModal() {
        document.getElementById('attHistModal').classList.remove('active');
    }

    // Close modals on overlay click
    document.getElementById('addAttModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddAttModal();
    });
    document.getElementById('attHistModal').addEventListener('click', function(e) {
        if (e.target === this) closeHistModal();
    });
    // Close session dropdown on outside click
    document.addEventListener('click', function(e) {
        var wrap = document.getElementById('sessionDropdownWrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('sessionDropdownList').classList.remove('open');
            document.getElementById('sessionTrigger').classList.remove('open');
        }
    });

    loadModules();
</script>
</body>
</html>
