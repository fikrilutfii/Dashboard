<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Peternakan Ayam' }} — Abadi Sentosa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --luxury-obsidian: #0c0c0e;
            --luxury-gold: #c5a059;
            --luxury-gold-light: #f3e8d2;
            --luxury-gold-glow: rgba(197, 160, 89, 0.18);
            --luxury-bg: #f4f4f6;
            --luxury-card-bg: rgba(255, 255, 255, 0.88);
            --luxury-text-primary: #09090b;
            --luxury-text-muted: #71717a;
            --luxury-border: rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--luxury-bg);
            color: var(--luxury-text-primary);
            letter-spacing: -0.01em;
            margin: 0;
            padding: 0;
        }

        /* Glassmorphic Squircle Cards */
        .ios-card {
            background: var(--luxury-card-bg);
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04), 0 2px 6px rgba(0,0,0,0.02);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .ios-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        /* Luxury Input */
        .ios-input {
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 14px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 500;
            color: #09090b;
            width: 100%;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
            box-sizing: border-box;
        }
        .ios-input:focus {
            background: #ffffff;
            border-color: var(--luxury-gold);
            box-shadow: 0 0 0 4px var(--luxury-gold-glow);
        }

        /* Luxury Buttons */
        .ios-btn {
            border-radius: 14px;
            padding: 11px 22px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: -0.01em;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        .ios-btn-primary {
            background: #09090b;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }
        .ios-btn-primary:hover {
            background: #1f1f23;
            transform: scale(0.985);
            color: #ffffff;
        }
        .ios-btn-gold {
            background: linear-gradient(135deg, #c5a059, #a37f38);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(197, 160, 89, 0.25);
        }
        .ios-btn-gold:hover {
            background: linear-gradient(135deg, #b8934c, #96732f);
            transform: scale(0.985);
            color: #ffffff;
        }
        .ios-btn-secondary {
            background: rgba(0,0,0,0.04);
            color: #18181b;
            border: 1px solid rgba(0,0,0,0.06);
        }
        .ios-btn-secondary:hover {
            background: rgba(0,0,0,0.08);
            color: #000000;
        }
        .ios-btn-danger {
            background: rgba(244, 63, 94, 0.08);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.15);
        }
        .ios-btn-danger:hover {
            background: rgba(244, 63, 94, 0.16);
        }

        /* Stat Card */
        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 22px 24px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 20px -5px rgba(0,0,0,0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -10px rgba(0,0,0,0.08);
        }

        /* Status Glowing Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-amber .badge-dot { background: #d97706; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-green .badge-dot { background: #10b981; }
        .badge-red   { background: #ffe4e6; color: #9f1239; }
        .badge-red .badge-dot { background: #f43f5e; }
        .badge-zinc  { background: #f4f4f5; color: #52525b; }
        .badge-zinc .badge-dot { background: #71717a; }
        .badge-blue  { background: #e0f2fe; color: #075985; }
        .badge-blue .badge-dot { background: #0284c7; }

        /* Dynamic Island Topbar */
        .dynamic-island-container {
            position: fixed;
            top: 14px;
            left: calc(270px + (100% - 270px) / 2);
            transform: translateX(-50%);
            z-index: 50;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dynamic-island {
            background: rgba(12, 12, 14, 0.92);
            backdrop-filter: blur(30px) saturate(200%);
            -webkit-backdrop-filter: blur(30px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.35);
            color: white;
        }

        /* Sleek Obsidian Sidebar */
        .farm-sidebar {
            background: #0c0c0e;
            width: 270px;
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            overflow-y: auto;
            z-index: 40;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
        }
        .farm-sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 14px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            margin: 2px 12px;
        }
        .farm-sidebar-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }
        .farm-sidebar-link.active {
            background: rgba(197, 160, 89, 0.15);
            color: #c5a059;
            font-weight: 600;
            border: 1px solid rgba(197, 160, 89, 0.2);
        }
        .farm-sidebar-icon {
            width: 22px; height: 22px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: currentColor;
        }
        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.28);
            padding: 20px 24px 6px;
        }
        .sidebar-submenu { padding-left: 48px; margin-right: 12px; }
        .sidebar-submenu a {
            display: block;
            padding: 8px 12px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 13px;
            font-weight: 500;
            margin: 2px 0;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-submenu a:hover { color: #ffffff; background: rgba(255, 255, 255, 0.05); }
        .sidebar-submenu a.active { color: #c5a059; font-weight: 600; }

        .main-content {
            margin-left: 270px;
            min-height: 100vh;
            padding-top: 80px; /* Space for Dynamic Island */
        }

        .page-header {
            padding: 24px 40px 16px;
        }

        /* Table Luxury iOS style */
        .ios-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .ios-table thead tr th {
            padding: 14px 18px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #71717a;
            background: rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }
        .ios-table tbody tr { transition: background 0.15s; }
        .ios-table tbody tr:hover { background: rgba(197, 160, 89, 0.04); }
        .ios-table tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            font-size: 14px;
            color: #09090b;
        }

        /* Animations */
        .farm-flash { animation: slideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideDown { from { opacity:0; transform: translateY(-12px); } to { opacity:1; transform: translateY(0); } }

        @media (max-width: 992px) {
            .dynamic-island-container { left: 50%; }
            .farm-sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .farm-sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false, masterOpen: {{ request()->routeIs('farm.master.*') ? 'true' : 'false' }}, invoiceOpen: {{ request()->routeIs('farm.invoices.*', 'farm.billing.*') ? 'true' : 'false' }} }">

<!-- Mobile Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 md:hidden" x-cloak></div>

<!-- Dynamic Island Topbar (Floating Capsule) -->
<div class="dynamic-island-container">
    <div class="dynamic-island">
        <!-- System Live Pill -->
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 10px #10b981;"></span>
            <span style="font-size:12px;font-weight:700;letter-spacing:0.05em;color:#e4e4e7;text-transform:uppercase;">DIVISI PETERNAKAN</span>
        </div>

        <div style="height:14px;width:1px;background:rgba(255,255,255,0.15);"></div>

        <!-- Division Title -->
        <div style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.85);display:flex;align-items:center;gap:6px;">
            <span>Abadi Sentosa</span>
        </div>

        <div style="height:14px;width:1px;background:rgba(255,255,255,0.15);"></div>

        <!-- Profile Pill -->
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#c5a059,#8c6b28);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:white;">
                {{ strtoupper(substr(Auth::user()?->name ?? 'U', 0, 1)) }}
            </div>
            <span style="font-size:13px;font-weight:600;color:#ffffff;">{{ Auth::user()?->name ?? 'User' }}</span>
        </div>
    </div>
</div>

<!-- Obsidian Minimalist Sidebar -->
<aside class="farm-sidebar" :class="{ 'open': sidebarOpen }">
    <!-- Brand Logo -->
    <div style="padding: 28px 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.06);">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#c5a059,#96732f);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;box-shadow:0 6px 16px rgba(197,160,89,0.25);">
                <svg style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
            </div>
            <div>
                <div style="color:white;font-weight:800;font-size:15px;line-height:1.2;letter-spacing:-0.02em;">PETERNAKAN</div>
                <div style="color:#c5a059;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;">Abadi Sentosa</div>
            </div>
        </div>
    </div>

    <!-- Nav Links -->
    <nav style="padding: 16px 0; flex:1;">
        <!-- Dashboard -->
        <a href="{{ route('farm.dashboard') }}" class="farm-sidebar-link {{ request()->routeIs('farm.dashboard') ? 'active' : '' }}">
            <div class="farm-sidebar-icon">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            Dashboard
        </a>

        <!-- Penjualan -->
        <div class="sidebar-section-label">PENJUALAN</div>

        <button @click="invoiceOpen = !invoiceOpen" class="farm-sidebar-link w-full text-left {{ request()->routeIs('farm.invoices.*', 'farm.billing.*') ? 'active' : '' }}" style="justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="farm-sidebar-icon">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                Faktur Penjualan
            </div>
            <svg :class="invoiceOpen ? 'rotate-180' : ''" style="width:14px;height:14px;transition:transform 0.2s;opacity:0.5;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="invoiceOpen" x-transition class="sidebar-submenu">
            <a href="{{ route('farm.invoices.index') }}" class="{{ request()->routeIs('farm.invoices.*') && !request()->routeIs('farm.billing.*') ? 'active' : '' }}">Daftar Faktur</a>
            <a href="{{ route('farm.billing.index') }}" class="{{ request()->routeIs('farm.billing.*') ? 'active' : '' }}">Laporan Tagihan</a>
        </div>

        <!-- Operasional -->
        <div class="sidebar-section-label">OPERASIONAL</div>

        <a href="{{ route('farm.transportation.index') }}" class="farm-sidebar-link {{ request()->routeIs('farm.transportation.*') ? 'active' : '' }}">
            <div class="farm-sidebar-icon">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            Transportasi
        </a>

        <a href="{{ route('farm.operational.index') }}" class="farm-sidebar-link {{ request()->routeIs('farm.operational.*') ? 'active' : '' }}">
            <div class="farm-sidebar-icon">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            Log Operasional
        </a>

        <a href="{{ route('farm.expenses.index') }}" class="farm-sidebar-link {{ request()->routeIs('farm.expenses.*') ? 'active' : '' }}">
            <div class="farm-sidebar-icon">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            Pengeluaran
        </a>

        <a href="{{ route('farm.payroll.index') }}" class="farm-sidebar-link {{ request()->routeIs('farm.payroll.*') ? 'active' : '' }}">
            <div class="farm-sidebar-icon">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            Penggajian
        </a>

        <!-- Master Data -->
        <div class="sidebar-section-label">DATA MASTER</div>

        <button @click="masterOpen = !masterOpen" class="farm-sidebar-link w-full text-left {{ request()->routeIs('farm.master.*') ? 'active' : '' }}" style="justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="farm-sidebar-icon">
                    <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7zM9 9h6M9 13h6"/></svg>
                </div>
                Master Data
            </div>
            <svg :class="masterOpen ? 'rotate-180' : ''" style="width:14px;height:14px;transition:transform 0.2s;opacity:0.5;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="masterOpen" x-transition class="sidebar-submenu">
            <a href="{{ route('farm.master.customers.index') }}" class="{{ request()->routeIs('farm.master.customers.*') ? 'active' : '' }}">Customer</a>
            <a href="{{ route('farm.master.suppliers.index') }}" class="{{ request()->routeIs('farm.master.suppliers.*') ? 'active' : '' }}">Supplier</a>
            <a href="{{ route('farm.master.coops.index') }}" class="{{ request()->routeIs('farm.master.coops.*') ? 'active' : '' }}">Kandang</a>
        </div>
    </nav>

    <!-- Bottom Switch Division -->
    <div style="padding: 20px 16px; border-top: 1px solid rgba(255,255,255,0.06); margin-top: auto;">
        <form method="POST" action="{{ route('division.switch') }}">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:14px;background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.55);font-size:13px;font-weight:600;border:1px solid rgba(255,255,255,0.08);cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.color='#ffffff';" onmouseout="this.style.background='rgba(255,255,255,0.04)';this.style.color='rgba(255,255,255,0.55)';">
                <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Ganti Divisi Kerja
            </button>
        </form>
    </div>
</aside>

<!-- Main Content Area -->
<div class="main-content">
    <!-- Page Header -->
    <header class="page-header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:16px;">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden" style="padding:8px;border:none;background:transparent;border-radius:10px;cursor:pointer;">
                    <svg style="width:22px;height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 style="font-size:24px;font-weight:800;color:#09090b;margin:0;line-height:1.2;letter-spacing:-0.03em;">{{ $title ?? 'Dashboard' }}</h1>
                    @isset($subtitle)
                    <p style="font-size:13.5px;color:#71717a;margin:2px 0 0;font-weight:500;">{{ $subtitle }}</p>
                    @endisset
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                @isset($headerActions)
                    {{ $headerActions }}
                @endisset
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="farm-flash" style="margin: 12px 40px 0; padding:14px 20px; background:#d1fae5; border-radius:16px; color:#065f46; font-weight:600; font-size:14px; border:1px solid #a7f3d0; display:flex; align-items:center; gap:10px;">
        <svg style="width:20px;height:20px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error') || (isset($errors) && $errors->any()))
    <div class="farm-flash" style="margin: 12px 40px 0; padding:14px 20px; background:#ffe4e6; border-radius:16px; color:#9f1239; font-weight:600; font-size:14px; border:1px solid #fecdd3;">
        @if(session('error')){{ session('error') }}@endif
        @if(isset($errors) && $errors->any())
        <ul style="margin:4px 0 0 20px;font-weight:500;font-size:13px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <main style="padding: 24px 40px 48px;">
        {{ $slot }}
    </main>
</div>

</body>
</html>
