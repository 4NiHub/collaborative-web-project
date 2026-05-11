<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Timetable Editor – SUSAdmin</title>
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
        .sidebar.expanded + .main-content { margin-left: 220px; }

        * { box-sizing: border-box; }

        .app-container { display: flex; min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-collapsed, 72px);
            background: #2563eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            z-index: 1000;
            transition: width 0.3s ease;
            overflow: hidden;
            color: white;
        }
        .sidebar.expanded { width: var(--sidebar-expanded, 220px); } */

        /* .sidebar-toggle-btn {
            height: 60px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover { background: rgba(255,255,255,0.15); }
        .sidebar-toggle-btn img {
            width: 28px; height: 28px;
            transition: transform 0.4s ease;
        }
        .sidebar.expanded .sidebar-toggle-btn img { transform: rotate(180deg); } */

        /* .sidebar-icon {
            width: calc(100% - 16px);
            height: 48px;
            border-radius: 10px;
            display: flex; align-items: center;
            padding: 0 18px;
            margin: 4px 8px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            color: rgba(255,255,255,0.85);
            position: relative;
            white-space: nowrap;
        }
        .sidebar.expanded .sidebar-label {
            opacity: 0; visibility: hidden; width: 0;
            overflow: hidden;
            font-size: 15px; font-weight: 500;
            transition: opacity 0.22s ease 0.05s, width 0.35s ease, visibility 0.35s;
        }
        .sidebar.expanded .sidebar-label { opacity: 1; visibility: visible; width: auto; }
        .logout-icon:hover { background: rgba(239,68,68,0.2) !important; color: #ef4444 !important; }
        body.dark-mode .sidebar { background: #1e293b; }
        .main-content {
            margin-left: var(--sidebar-collapsed, 72px);
            flex: 1; padding: 24px;
            transition: margin-left 0.3s ease;
            background: #f1f5f9; min-height: 100vh;
        }
        .sidebar.expanded ~ .main-content { margin-left: var(--sidebar-expanded, 220px); }
        body.dark-mode .main-content { background: #0f172a; } */

        body.dark-mode .page-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 20px; gap: 16px; flex-wrap: wrap;
        }
        .page-header h1 { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .page-header p  { font-size: 14px; color: #64748b; }
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        body.dark-mode .page-header h1 { color: #f1f5f9; }
        .btn {
            padding: 10px 18px; border-radius: 10px; font-size: 14px;
            font-weight: 700; cursor: pointer; border: none;
            display: inline-flex; align-items: center; gap: 7px; transition: all 0.2s;
        }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-success { background: #16a34a; color: white; }
        .btn-success:hover { background: #15803d; transform: translateY(-1px); }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #1e293b; }
        .btn-outline:hover { background: #f8fafc; }
        .btn-danger  { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-ghost   { background: transparent; color: #64748b; border: 1.5px solid #e2e8f0; }
        .btn-ghost:hover { background: #f8fafc; }
        body.dark-mode .btn-outline { background: #334155; border-color: #475569; color: #e2e8f0; }
        body.dark-mode .btn-ghost   { border-color: #475569; color: #94a3b8; }
        body.dark-mode .btn-ghost:hover { background: #334155; }

        .control-bar {
            background: white; border-radius: 12px; padding: 14px 20px;
            margin-bottom: 20px; display: flex; gap: 12px; align-items: center;
            flex-wrap: wrap; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        body.dark-mode .control-bar { background: #1e293b; }
        .filter-group { display: flex; align-items: center; gap: 8px; }
        .filter-group label { font-size: 13px; font-weight: 700; color: #64748b; }
        .filter-select {
            padding: 8px 12px; border-radius: 8px; border: 1.5px solid #e2e8f0;
            font-size: 14px; font-weight: 500; background: white; color: #1e293b;
            cursor: pointer; min-width: 140px;
        }
        .filter-select:focus { outline: none; border-color: #2563eb; }
        body.dark-mode .filter-select { background: #334155; color: #e2e8f0; border-color: #475569; }
        .divider-v { width: 1px; height: 28px; background: #e2e8f0; }
        body.dark-mode .divider-v { background: #475569; }
        .stat-chips { display: flex; gap: 10px; margin-left: auto; flex-wrap: wrap; }
        .stat-chip { padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 6px; border: 1.5px solid; }
        .chip-blue  { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .chip-green { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .chip-amber { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .chip-red   { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

        body.dark-mode .stat-chip * {
            color: inherit !important;
        }

        .conflict-panel {
            background: #fef2f2; border: 1.5px solid #fecaca;
            border-radius: 12px; padding: 14px 18px; margin-bottom: 18px; display: none;
        }
        .conflict-panel.visible { display: block; }
        .conflict-panel h4 { font-size: 14px; font-weight: 800; color: #dc2626; margin-bottom: 8px; }
        .conflict-item { font-size: 13px; color: #991b1b; margin-bottom: 4px; }
        body.dark-mode .conflict-panel { background: #3b1c1c; border-color: #7f1d1d; }
        body.dark-mode .conflict-panel h4 { color: #f87171; }
        body.dark-mode .conflict-item { color: #fca5a5; }


        .tt-wrapper { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        body.dark-mode .tt-wrapper { background: #1e293b; }
        .tt-scroll { overflow-x: auto; }

        table.tt { width: 100%; border-collapse: collapse; min-width: 960px; }
        table.tt thead th {
            background: #f8fafc; padding: 14px 16px; font-size: 14px;
            font-weight: 800; color: #334155; text-align: center;
            border-bottom: 2px solid #e2e8f0;
        }
        table.tt thead th.time-head { text-align: center; vertical-align: middle; min-width: 78px; color: #64748b; }
        table.tt thead th.sat-head  { background: #fefce8; color: #78350f; }
        body.dark-mode table.tt thead th { background: #0f172a; color: #94a3b8; border-color: #334155; }
        body.dark-mode table.tt thead th.sat-head { background: #1c1a00; color: #fbbf24; }

        table.tt td {
            border: 1px solid #f1f5f9; padding: 6px;
            vertical-align: top; height: 90px; min-width: 140px; position: relative;
        }
        table.tt td.time-cell {
            color: #475569; text-align: center; padding: 0 10px;
            min-width: 78px; border-color: #e2e8f0;
        }
        table.tt td.sat-col { background: #fefce8; }
        table.tt td:hover:not(.time-cell) { background: #f8fafc; }
        body.dark-mode table.tt td { border-color: #1e293b; }
        body.dark-mode table.tt td.time-cell { background: #0f172a; color: #94a3b8; }
        body.dark-mode table.tt td.sat-col   { background: #1c1a00; }
        body.dark-mode table.tt td:hover:not(.time-cell) { background: #334155; }

        /* Slot card */
        .slot-card {
            border-radius: 8px; padding: 7px 9px; margin-bottom: 4px;
            cursor: grab; position: relative; transition: all 0.18s;
            user-select: none; border-left: 4px solid; overflow: hidden;
        }
        .slot-card:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.1); }
        .slot-card.dragging { opacity: 0.45; cursor: grabbing; }
        .slot-card.type-lecture  { background: #dbeafe; border-color: #2563eb; }
        .slot-card.type-lab      { background: #dcfce7; border-color: #16a34a; }
        .slot-card.type-tutorial { background: #fce7f3; border-color: #db2777; }
        .slot-card.type-seminar  { background: #f3e8ff; border-color: #7c3aed; }
        body.dark-mode .slot-card.type-lecture  { background: #1e3a5f; }
        body.dark-mode .slot-card.type-lab      { background: #14321e; }
        body.dark-mode .slot-card.type-tutorial { background: #3b1228; }
        body.dark-mode .slot-card.type-seminar  { background: #2e1a4a; }
        .slot-subject { font-size: 13px; font-weight: 800; color: #1e293b; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        body.dark-mode .slot-subject { color: #f1f5f9; }
        .slot-meta span { display: block; font-size: 12px; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        body.dark-mode .slot-meta span { color: #94a3b8; }
        .slot-meta .s-teacher { color: #2563eb; font-weight: 600; }
        .slot-actions { position: absolute; top: 4px; right: 4px; display: flex; gap: 3px; opacity: 0; transition: opacity 0.15s; }
        .slot-card:hover .slot-actions { opacity: 1; }
        .slot-btn { width: 20px; height: 20px; border-radius: 5px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; }
        .slot-btn-edit { background: #2563eb; color: white; }
        .slot-btn-del  { background: #ef4444; color: white; }

        .empty-zone {
            border-radius: 8px; border: 2px dashed transparent;
            display: flex; align-items: center; justify-content: center;
            color: #cbd5e1; cursor: pointer; transition: all 0.2s;
        }
        .empty-zone.full { height: 78px; font-size: 22px; }
        .empty-zone.mini { height: 24px; font-size: 15px; margin-top: 2px; }
        .empty-zone:hover { background: #f1f5f9; border-color: #94a3b8; color: #64748b; }
        .empty-zone.drag-over { background: #eff6ff; border-color: #2563eb; color: #2563eb; }
        body.dark-mode .empty-zone:hover { background: #334155; border-color: #475569; color: #94a3b8; }


        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,0.55);
            display: none; align-items: center; justify-content: center; z-index: 2000; padding: 20px;
        }
        .modal-backdrop.open { display: flex; }
        .modal-box {
            background: white; border-radius: 20px; width: 560px;
            max-width: 100%; max-height: 90vh; overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25); animation: modalIn 0.2s ease;
        }
        @keyframes modalIn { from { transform: scale(0.95) translateY(10px); opacity: 0; } to { transform: none; opacity: 1; } }
        body.dark-mode .modal-box { background: #1e293b; }
        .modal-head { padding: 22px 26px 0; display: flex; justify-content: space-between; align-items: center; }
        .modal-head h2 { font-size: 18px; font-weight: 800; color: #0f172a; }
        body.dark-mode .modal-head h2 { color: #f1f5f9; }
        .modal-close { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; font-size: 16px; color: #64748b; display: flex; align-items: center; justify-content: center; }
        .modal-close:hover { background: #e2e8f0; }
        body.dark-mode .modal-close { background: #334155; color: #e2e8f0; }
        .modal-body { padding: 20px 26px 10px; display: flex; flex-direction: column; gap: 16px; }
        .modal-footer { padding: 12px 26px 24px; display: flex; justify-content: flex-end; gap: 10px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label { font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .5px; }
        body.dark-mode .form-label { color: #94a3b8; }
        .form-select {
            padding: 10px 13px; border-radius: 10px; border: 1.5px solid #e2e8f0;
            font-size: 14px; font-weight: 500; background: white; color: #1e293b;
            transition: border-color 0.2s; width: 100%;
        }
        .form-select:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        body.dark-mode .form-select { background: #0f172a; color: #e2e8f0; border-color: #334155; }
        .modal-warn { display: none; background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 10px; padding: 10px 14px; font-size: 14px; color: #dc2626; font-weight: 600; }

        /* Preview modal */
        .preview-modal .modal-box { width: 96vw; max-width: 1300px; }
        .preview-table-wrap { overflow-x: auto; }
        table.preview-tt { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 800px; }
        table.preview-tt th { background: #f8fafc; padding: 10px 12px; font-weight: 700; border: 1px solid #e2e8f0; text-align: center; }
        table.preview-tt td { border: 1px solid #e2e8f0; padding: 6px; vertical-align: top; min-height: 70px; height: 70px; min-width: 120px; }
        table.preview-tt td.p-time { background: #f8fafc; font-weight: 700; color: #475569; text-align: center; font-size: 13px; }
        body.dark-mode table.preview-tt th { background: #0f172a; border-color: #334155; color: #94a3b8; }
        body.dark-mode table.preview-tt td { border-color: #334155; background: #1e293b; }
        body.dark-mode table.preview-tt td.p-time { background: #0f172a; color: #94a3b8; }
        .p-slot { border-radius: 6px; padding: 5px 7px; border-left: 3px solid; margin-bottom: 3px; }
        .p-slot.type-lecture  { background: #dbeafe; border-color: #2563eb; }
        .p-slot.type-lab      { background: #dcfce7; border-color: #16a34a; }
        .p-slot.type-tutorial { background: #fce7f3; border-color: #db2777; }
        .p-slot.type-seminar  { background: #f3e8ff; border-color: #7c3aed; }
        .p-slot-name { font-weight: 700; color: #1e293b; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .p-slot-info { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* Confirm */
        .confirm-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.6); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .confirm-backdrop.open { display: flex; }
        .confirm-box { background: white; border-radius: 18px; padding: 28px; width: 380px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.25); animation: modalIn 0.2s ease; }
        body.dark-mode .confirm-box { background: #1e293b; }
        .confirm-icon { width: 52px; height: 52px; border-radius: 50%; background: #fef2f2; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 24px; }
        .confirm-box h3 { font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        body.dark-mode .confirm-box h3 { color: #f1f5f9; }
        .confirm-box p { font-size: 14px; color: #64748b; margin-bottom: 22px; }
        .confirm-btns { display: flex; gap: 10px; justify-content: center; }

        /* Toast */
        .toast-wrap { position: fixed; bottom: 28px; right: 28px; display: flex; flex-direction: column; gap: 10px; z-index: 4000; }
        .toast { padding: 13px 20px; border-radius: 12px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; max-width: 340px; box-shadow: 0 8px 24px rgba(0,0,0,0.18); color: white; transform: translateX(380px); transition: transform 0.3s ease; }
        .toast.show { transform: none; }
        .toast.t-success { background: #16a34a; }
        .toast.t-error   { background: #dc2626; }
        .toast.t-warn    { background: #d97706; }
        .toast.t-info    { background: #2563eb; }

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

        /* ADD THIS TO FIX DARK MODE PREVIEW COLORS */
        body.dark-mode .p-slot.type-lecture  { background: #1e3a5f; border-color: #3b82f6; }
        body.dark-mode .p-slot.type-lab      { background: #14532d; border-color: #22c55e; }
        body.dark-mode .p-slot.type-tutorial { background: #4c1d95; border-color: #a855f7; }
        body.dark-mode .p-slot.type-seminar  { background: #581c87; border-color: #c084fc; }
        
        body.dark-mode .p-slot-name { color: #f8fafc !important; }
        body.dark-mode .p-slot-info { color: #94a3b8 !important; }
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

    <main class="main-content" id="mainContent">

                <div class="top-bar" id="topBar">
            <div class="top-bar-title">SUS — Smart University System</div>
            <div class="top-bar-spacer"></div>
            <div class="admin-badge" onclick="location.href='profile.html'">
                <div class="admin-avatar">A</div>
                <span class="admin-name">Admin</span>
            </div>
        </div>

        <div class="page-header">
            <div>
                <h1>Timetable Editor</h1>
                <p>Manage class slots · Mon–Sat · Conflict detection · Student preview</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline" onclick="openPreview()">
                    <img src="{{ asset('images/admin_icons/view.png') }}" alt="Users" style="width:25px;height:25px;"> Preview</button>
                <button class="btn btn-success" onclick="publishTimetable()">
                    <img src="{{ asset('images/admin_icons/publish.png') }}" alt="Users" style="width:25px;height:25px;"> Publish</button>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <img src="{{ asset('images/admin_icons/add.png') }}" alt="Users" style="width:25px;height:25px;"> Add Class</button>
            </div>
        </div>

        <!-- Conflict panel -->
        <div class="conflict-panel" id="conflictPanel">
            <h4>
                <img src="{{ asset('images/admin_icons/warning.png') }}" alt="Users" style="width:16px;height:16px;"> 
                Scheduling Conflicts Detected</h4>
            <div id="conflictList"></div>
        </div>

        <!-- Control bar -->
        <div class="control-bar">
            <div class="filter-group">
                <label>View by</label>
                <select class="filter-select" id="viewFilter" onchange="onViewChange()">
                    <option value="all">All Slots</option>
                    <option value="group">Group</option>
                    <option value="teacher">Teacher</option>
                    <option value="room">Room</option>
                </select>
            </div>
            <div class="filter-group" id="entityFilterWrap" style="display:none;">
                <label id="entityLabel">Entity</label>
                <select class="filter-select" id="entityFilter" onchange="renderTable()"></select>
            </div>
            <div class="divider-v"></div>
            <div class="filter-group">
                <label>Type</label>
                <select class="filter-select" id="typeFilter" onchange="renderTable()">
                    <option value="all">All Types</option>
                    <option value="lecture">Lecture</option>
                    <option value="lab">Lab</option>
                    <option value="tutorial">Tutorial</option>
                    <option value="seminar">Seminar</option>
                </select>
            </div>
            <div class="stat-chips">
                <span class="stat-chip chip-blue">
                    <img src="{{ asset('images/admin_icons/records_black.png') }}" style="width:25px;height:25px;">
                    <span id="statTotal">0</span> classes
                </span>

                <span class="stat-chip chip-green">
                    <img src="{{ asset('images/admin_icons/person.png') }}" style="width:25px;height:25px;">
                    <span id="statTeachers">0</span> teachers
                </span>

                <span class="stat-chip chip-amber">
                    <img src="{{ asset('images/admin_icons/corporate.png') }}" style="width:25px;height:25px;">
                    <span id="statRooms">0</span> rooms
                </span>

                <span class="stat-chip chip-red" id="conflictChip" style="display:none;">
                    <img src="{{ asset('images/admin_icons/warning.png') }}" style="width:25px;height:25px;">
                    <span id="statConflicts">0</span> conflicts
                </span>
            </div>
        </div>

        <!-- Timetable -->
        <div class="tt-wrapper">
            <div class="tt-scroll">
                <table class="tt">
                    <thead>
                        <tr>
                            <th class="time-head">
                                <img src="{{ asset('images/admin_icons/clock_grey.png') }}" style="width:30px;height:30px;vertical-align:middle;display:inline;margin-right:15px;">Time
                            </th>
                            <th>Monday</th><th>Tuesday</th><th>Wednesday</th>
                            <th>Thursday</th><th>Friday</th>
                            <th class="sat-head">Saturday</th>
                        </tr>
                    </thead>
                    <tbody id="ttBody">
                        <tr><td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;">Loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>


<div class="modal-backdrop" id="slotModal">
    <div class="modal-box">
        <div class="modal-head">
            <h2 id="slotModalTitle">Add Class</h2>
            <button class="modal-close" onclick="closeSlotModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Subject *</label>
                <select class="form-select" id="fSubject"><option value="">— select subject —</option></select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Teacher *</label>
                    <select class="form-select" id="fTeacher"><option value="">— select teacher —</option></select>
                </div>
                <div class="form-group">
                    <label class="form-label">Group *</label>
                    <select class="form-select" id="fGroup"><option value="">— select group —</option></select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Room *</label>
                    <select class="form-select" id="fRoom"><option value="">— select room —</option></select>
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select class="form-select" id="fType">
                        <option value="lecture">Lecture</option>
                        <option value="lab">Lab</option>
                        <option value="tutorial">Tutorial</option>
                        <option value="seminar">Seminar</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Day *</label>
                    <select class="form-select" id="fDay">
                        <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                        <option>Thursday</option><option>Friday</option><option>Saturday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Time *</label>
                    <select class="form-select" id="fTime">
                        <option>08:00</option><option>09:00</option><option>10:00</option>
                        <option>11:00</option><option>12:00</option><option>13:00</option>
                        <option>14:00</option><option>15:00</option><option>16:00</option>
                    </select>
                </div>
            </div>
            <div class="modal-warn" id="modalWarn"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeSlotModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveSlot()">Save Class</button>
        </div>
    </div>
</div>


<div class="modal-backdrop preview-modal" id="previewModal">
    <div class="modal-box">
        <div class="modal-head">
            <h2>Student Preview</h2>
            <button class="modal-close" onclick="closePreview()">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-row" style="grid-template-columns:auto auto 1fr; gap:10px; align-items:end;">
                <div class="form-group">
                    <label class="form-label">View As</label>
                    <select class="form-select" id="prevViewAs" onchange="onPreviewViewChange()">
                        <option value="all">Full Grid</option>
                        <option value="group">By Group</option>
                        <option value="teacher">By Teacher</option>
                    </select>
                </div>
                <div class="form-group" id="prevEntityWrap" style="display:none;">
                    <label class="form-label" id="prevEntityLabel">Entity</label>
                    <select class="form-select" id="prevEntity" onchange="renderPreview()"></select>
                </div>
            </div>
            <div class="preview-table-wrap" id="previewTableWrap"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closePreview()">Close</button>
            <button class="btn btn-success" onclick="publishTimetable();closePreview()">
                <img src="{{ asset('images/admin_icons/publish.png') }}" style="width:25px;height:25px;">Publish Now</button>

        </div>
    </div>
</div>


<div class="confirm-backdrop" id="confirmBackdrop">
    <div class="confirm-box">
        <div class="confirm-icon">
            <img src="{{ asset('images/admin_icons/delete.png') }}" style="width:25px;height:25px;">
        </div>
        <h3 id="confirmTitle">Remove Class?</h3>
        <p id="confirmMsg">This slot will be removed from the timetable.</p>
        <div class="confirm-btns">
            <button class="btn btn-ghost" onclick="closeConfirm()">Cancel</button>
            <button class="btn btn-danger" id="confirmOk">Remove</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-wrap" id="toastWrap"></div>

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
'use strict';

const DAYS  = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
const TIMES = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00'];

let allSlots = [], teachers = [], groups = [], rooms = [], subjects = [];
let editingId = null, dragSrcId = null;


(async function init() {
    initTheme();
    initSidebar();
    try {
        const [sR, tR, gR, rR, sbR] = await Promise.all([
            AdminTimetableAPI.getAll(),
            AdminTeacherAPI.getTeachers(),
            AdminGroupAPI.getGroups(),
            RoomAPI.getRooms(),
            AdminSubjectAPI.getSubjects()
        ]);
        allSlots = sR.data  || [];
        teachers = tR.data  || [];
        groups   = gR.data  || [];
        rooms    = rR.data  || [];
        subjects = sbR.data || [];
    } catch (err) { toast('Load error: ' + err.message, 'error'); }
    populateFormSelects();
    renderTable();
    checkConflicts();
})();


function initTheme() {}


function initSidebar() {
    const sb = document.getElementById('sidebar');
    document.getElementById('mainContent').addEventListener('click', e => {
        if (sb.classList.contains('expanded') && !sb.contains(e.target)) sb.classList.remove('expanded');
    });
    const pageMap = { dashboard:'dashboard.html', users:'users.html', timetable:'timetable.html', content:'content.html', grading:'grading.html', news:'news.html', attendance:'attendance.html', help:'help.html' };
    document.querySelectorAll('.sidebar-icon[data-page]').forEach(icon => {
        icon.addEventListener('click', () => { const d = pageMap[icon.dataset.page]; if (d) go(d); });
    });
}
function go(url) { window.location.href = url; }

function populateFormSelects() {
    const fill = (id, items, valFn, labelFn, placeholder) => {
        const el = document.getElementById(id);
        el.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(i => { const o = document.createElement('option'); o.value = valFn(i); o.textContent = labelFn(i); el.appendChild(o); });
    };
    fill('fSubject', subjects, s => s.id,     s => `${s.code} – ${s.name}`, '— select subject —');
    fill('fTeacher', teachers, t => t.id,     t => `${t.title} ${t.firstName} ${t.lastName}`, '— select teacher —');
    fill('fGroup',   groups,   g => g.id,     g => g.name, '— select group —');
    fill('fRoom',    rooms,    r => r.id,     r => `${r.name} (${r.building}) · cap ${r.capacity}`, '— select room —');
}

function onViewChange() {
    const view = document.getElementById('viewFilter').value;
    const wrap = document.getElementById('entityFilterWrap');
    const lbl  = document.getElementById('entityLabel');
    const sel  = document.getElementById('entityFilter');

    sel.innerHTML = '';
    if (view === 'all') {
        wrap.style.display = 'none';
    } else {
        wrap.style.display = '';
        if (view === 'group') {
            lbl.textContent = 'Group';
            groups.forEach(g => { const o = document.createElement('option'); o.value = g.id; o.textContent = g.name; sel.appendChild(o); });
        } else if (view === 'teacher') {
            lbl.textContent = 'Teacher';
            teachers.forEach(t => { const o = document.createElement('option'); o.value = t.id; o.textContent = `${t.title} ${t.firstName} ${t.lastName}`; sel.appendChild(o); });
        } else if (view === 'room') {
            lbl.textContent = 'Room';
            rooms.forEach(r => { const o = document.createElement('option'); o.value = r.id; o.textContent = `${r.name} (${r.building})`; sel.appendChild(o); });
        }
    }
    renderTable(); 
}

function filteredSlots() {
    const view   = document.getElementById('viewFilter').value;
    const type   = document.getElementById('typeFilter').value;
    const entity = document.getElementById('entityFilter').value; 
    let s = [...allSlots];
    
    if (type !== 'all') s = s.filter(x => x.type === type);
    
    // Use String() to prevent integer vs string mismatches!
    if (view === 'group'   && entity) s = s.filter(x => String(x.groupId) === String(entity));
    if (view === 'teacher' && entity) s = s.filter(x => String(x.teacherId) === String(entity));
    if (view === 'room'    && entity) s = s.filter(x => String(x.roomId) === String(entity));
    
    return s;
}

const getTName = id => { const t = teachers.find(x => String(x.id) === String(id)); return t ? `${t.title} ${t.firstName} ${t.lastName}` : 'Unknown'; };

const getGName = id => { const g = groups.find(x => String(x.id) === String(id));   return g ? g.name : String(id); };

const getRName = id => { const r = rooms.find(x => String(x.id) === String(id)); return r ? r.name : String(id); };


function renderTable() {
    const slots = filteredSlots();
    const map = {};
    slots.forEach(s => {
        (map[s.day] = map[s.day] || {});
        (map[s.day][s.time] = map[s.day][s.time] || []).push(s);
    });

    let html = '';
    TIMES.forEach(time => {
        html += `<tr><td class="time-cell">${time}</td>`;
        DAYS.forEach(day => {
            const isSat    = day === 'Saturday';
            const daySlots = (map[day] && map[day][time]) || [];
            const tdClass  = isSat ? 'sat-col' : '';
            html += `<td class="${tdClass}"
                ondragover="event.preventDefault();dragOverCell(this)"
                ondragleave="dragLeaveCell(this)"
                ondrop="onDrop(event,'${day}','${time}')">`;
            daySlots.forEach(s => { html += slotCardHtml(s); });
            const ezClass = daySlots.length ? 'mini' : 'full';
            html += `<div class="empty-zone ${ezClass}" onclick="openAddModal('${day}','${time}')">+</div>`;
            html += `</td>`;
        });
        html += `</tr>`;
    });

    document.getElementById('ttBody').innerHTML = html ||
        `<tr><td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;">No classes match the current filter.</td></tr>`;

    updateStats(slots);
}

function slotCardHtml(s) {
    const id = s.id;
    return `<div class="slot-card type-${h(s.type)}" draggable="true"
        ondragstart="onDragStart(event,'${id}')" ondragend="onDragEnd()">
        <div class="slot-subject">${h(s.subject)}</div>
        <div class="slot-meta">
            <span class="s-teacher"><img src="{{ asset('images/admin_icons/person.png') }}" style="width:25px;height:25px;"> ${h(getTName(s.teacherId))}</span>
            <span> <img src="{{ asset('images/admin_icons/users.png') }}" style="width:25px;height:25px;"> ${h(getGName(s.groupId))}</span>
            <span><img src="{{ asset('images/admin_icons/corporate.png') }}" style="width:25px;height:25px;"> ${h(getRName(s.roomId))}</span>
        </div>
        <div class="slot-actions">
            <button class="slot-btn slot-btn-edit" onclick="event.stopPropagation();openEditModal('${id}')" title="Edit"><img src="{{ asset('images/admin_icons/edit.png') }}" style="width:12px;height:12px;"></button>
            <button class="slot-btn slot-btn-del"  onclick="event.stopPropagation();confirmDelete('${id}')" title="Delete"><img src="{{ asset('images/admin_icons/delete.png') }}" style="width:12px;height:12px;"></button>
        </div>
    </div>`;
}

function updateStats(slots) {
    document.getElementById('statTotal').textContent    = slots.length;
    document.getElementById('statTeachers').textContent = new Set(slots.map(s => s.teacherId)).size;
    document.getElementById('statRooms').textContent    = new Set(slots.map(s => s.roomId)).size;
}

async function checkConflicts() {
    try {
        const c = (await AdminTimetableAPI.getConflicts()).data || [];
        document.getElementById('statConflicts').textContent = c.length;
        const panel = document.getElementById('conflictPanel');
        const chip  = document.getElementById('conflictChip');
        if (c.length === 0) { panel.classList.remove('visible'); chip.style.display = 'none'; }
        else {
            panel.classList.add('visible'); chip.style.display = '';
            document.getElementById('conflictList').innerHTML =
                c.map(x => `<div class="conflict-item"><img src="{{ asset('images/admin_icons/warning.png') }}" style="width:12px;height:12px;"> ${h(x.message)}</div>`).join('');
        }
    } catch(_) {}
}

function openAddModal(day, time) {
    editingId = null;
    document.getElementById('slotModalTitle').textContent = ' Add Class';
    ['fSubject','fTeacher','fGroup','fRoom'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fType').value = 'lecture';
    if (day)  document.getElementById('fDay').value  = day;
    if (time) document.getElementById('fTime').value = time;
    clearWarn();
    document.getElementById('slotModal').classList.add('open');
}

function openEditModal(id) {
    const s = allSlots.find(x => x.id === id);
    if (!s) return;
    editingId = id;
    document.getElementById('slotModalTitle').innerHTML = '<img src="{{ asset('images/admin_icons/edit_grey.png') }}" style="width:25px;height:25px;"> Edit Class';
    document.getElementById('fSubject').value = s.subjectId || '';
    document.getElementById('fTeacher').value = String(s.teacherId);
    document.getElementById('fGroup').value   = s.groupId;
    document.getElementById('fRoom').value    = s.roomId;
    document.getElementById('fType').value    = s.type || 'lecture';
    document.getElementById('fDay').value     = s.day;
    document.getElementById('fTime').value    = s.time;
    clearWarn();
    document.getElementById('slotModal').classList.add('open');
}
function closeSlotModal() { document.getElementById('slotModal').classList.remove('open'); }
function clearWarn() { const w = document.getElementById('modalWarn'); w.style.display = 'none'; w.textContent = ''; }
function showWarn(msg) { const w = document.getElementById('modalWarn'); w.textContent = msg; w.style.display = ''; }


async function saveSlot() {
    const subjectId = document.getElementById('fSubject').value;
    const teacherId = document.getElementById('fTeacher').value;
    const groupId   = document.getElementById('fGroup').value;
    const roomId    = document.getElementById('fRoom').value;
    const type      = document.getElementById('fType').value;
    const day       = document.getElementById('fDay').value;
    const time      = document.getElementById('fTime').value;
    if (!subjectId || !teacherId || !groupId || !roomId) { showWarn('Subject, Teacher, Group and Room are required.'); return; }
    const subj = subjects.find(s => s.id == subjectId);
    const data = { day, time, type, subjectId, groupId, roomId, teacherId: parseInt(teacherId), subject: subj ? subj.name : subjectId };
    try {
        if (editingId) {
            const res = await AdminTimetableAPI.updateSlot(editingId, data);
            const idx = allSlots.findIndex(x => x.id === editingId);
            if (idx > -1) allSlots[idx] = res.data;
            toast(`Updated "${data.subject}"`, 'success');
        } else {
            const res = await AdminTimetableAPI.addSlot(data);
            allSlots.push(res.data);
            toast(`Added "${data.subject}" · ${day} ${time}`, 'success');
        }
        closeSlotModal(); renderTable(); checkConflicts();
    } catch (err) { showWarn('<img src="{{ asset('images/admin_icons/warning.png') }}" style="width:25px;height:25px;"> ' + err.message); }
}

function confirmDelete(id) {
    const s = allSlots.find(x => x.id === id);
    if (!s) return;
    document.getElementById('confirmMsg').textContent = `"${s.subject}" · ${s.day} ${s.time}`;
    document.getElementById('confirmBackdrop').classList.add('open');
    document.getElementById('confirmOk').onclick = async () => {
        closeConfirm();
        try {
            await AdminTimetableAPI.deleteSlot(id);
            allSlots = allSlots.filter(x => x.id !== id);
            renderTable(); checkConflicts();
            toast(`Removed "${s.subject}"`, 'success');
        } catch (err) { toast(err.message, 'error'); }
    };
}
function closeConfirm() { document.getElementById('confirmBackdrop').classList.remove('open'); }


function onDragStart(e, id) { dragSrcId = id; e.currentTarget.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; }
function onDragEnd() { document.querySelectorAll('.slot-card,.empty-zone').forEach(el => el.classList.remove('dragging','drag-over')); dragSrcId = null; }
function dragOverCell(td) { td.querySelector('.empty-zone') && td.querySelector('.empty-zone').classList.add('drag-over'); }
function dragLeaveCell(td) { td.querySelector('.empty-zone') && td.querySelector('.empty-zone').classList.remove('drag-over'); }
async function onDrop(e, day, time) {
    e.preventDefault();
    document.querySelectorAll('.empty-zone').forEach(el => el.classList.remove('drag-over'));
    if (!dragSrcId) return;
    const s = allSlots.find(x => x.id === dragSrcId);
    if (!s || (s.day === day && s.time === time)) return;
    try {
        const res = await AdminTimetableAPI.updateSlot(s.id, { ...s, day, time });
        const idx = allSlots.findIndex(x => x.id === s.id);
        if (idx > -1) allSlots[idx] = res.data;
        renderTable(); checkConflicts();
        toast(`Moved "${s.subject}" → ${day} ${time}`, 'success');
    } catch (err) { toast('Move failed: ' + err.message, 'error'); }
}

function openPreview() {
    document.getElementById('previewModal').classList.add('open');
    document.getElementById('prevViewAs').value = 'all';
    document.getElementById('prevEntityWrap').style.display = 'none';
    renderPreview();
}
function closePreview() { document.getElementById('previewModal').classList.remove('open'); }
function onPreviewViewChange() {
    const v   = document.getElementById('prevViewAs').value;
    const wrap = document.getElementById('prevEntityWrap');
    const lbl  = document.getElementById('prevEntityLabel');
    const sel  = document.getElementById('prevEntity');
    sel.innerHTML = '';
    if (v === 'all') { wrap.style.display = 'none'; }
    else {
        wrap.style.display = '';
        if (v === 'group') {
            lbl.textContent = 'Group';
            groups.forEach(g => { const o = document.createElement('option'); o.value = g.id; o.textContent = g.name; sel.appendChild(o); });
        } else {
            lbl.textContent = 'Teacher';
            teachers.forEach(t => { const o = document.createElement('option'); o.value = t.id; o.textContent = `${t.title} ${t.firstName} ${t.lastName}`; sel.appendChild(o); });
        }
    }
    renderPreview();
}

function renderPreview() {
    const v      = document.getElementById('prevViewAs').value;
    const entity = document.getElementById('prevEntity').value;
    let slots    = [...allSlots];
    if (v === 'group'   && entity) slots = slots.filter(s => String(s.groupId) === String(entity));
    if (v === 'teacher' && entity) slots = slots.filter(s => String(s.teacherId) === String(entity));
    const map = {};
    slots.forEach(s => { (map[s.day] = map[s.day]||{}); (map[s.day][s.time] = map[s.day][s.time]||[]).push(s); });
    let html = `<table class="preview-tt"><thead><tr><th>Time</th>${DAYS.map(d=>`<th>${d}</th>`).join('')}</tr></thead><tbody>`;
    TIMES.forEach(time => {
        html += `<tr><td class="p-time">${time}</td>`;
        DAYS.forEach(day => {
            const ds = (map[day] && map[day][time]) || [];
            html += `<td>${ds.map(s => {
                const info = v === 'teacher' ? getGName(s.groupId) : getTName(s.teacherId);
                return `<div class="p-slot type-${s.type}"><div class="p-slot-name">${h(s.subject)}</div><div class="p-slot-info">${h(info)} · ${h(getRName(s.roomId))}</div></div>`;
            }).join('')}</td>`;
        });
        html += `</tr>`;
    });
    html += `</tbody></table>`;
    document.getElementById('previewTableWrap').innerHTML = html;
}

async function publishTimetable() {
    try { 
        await AdminTimetableAPI.publish(); 
        toast('Timetable published! Students can see updates.', 'success'); 
    }
    catch (err) { 
        toast('Publish failed: ' + err.message, 'error'); 
    }
}


function toast(msg, type = 'info') {
    const icons = { 
        success:'<img src="{{ asset('images/admin_icons/check_circle.png') }}" style="width:14px;height:14px;">', 
        error:'<img src="{{ asset('images/admin_icons/error.png') }}" style="width:14px;height:14px;">', 
        warn:'<img src="{{ asset('images/admin_icons/warning.png') }}" style="width:14px;height:14px;">', 
        info:'<img src="{{ asset('images/admin_icons/content.png') }}" style="width:14px;height:14px;">' 
    };
    const el = document.createElement('div');
    el.className = `toast t-${type}`;
    el.innerHTML = `<span>${icons[type]||''}</span> ${msg}`;
    document.getElementById('toastWrap').appendChild(el);
    setTimeout(() => el.classList.add('show'), 10);
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }, 3500);
}

function h(s) { return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }


['slotModal','previewModal','confirmBackdrop'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target !== this) return;
        if (id === 'confirmBackdrop') closeConfirm();
        else if (id === 'previewModal') closePreview();
        else closeSlotModal();
    });
});

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
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('expanded');
    }
    function showLogoutPopup() {
    document.getElementById('logoutPopup').classList.add('show');
    }
    function hideLogoutPopup() {
        document.getElementById('logoutPopup').classList.remove('show');
    }
</script>
</body>
</html>