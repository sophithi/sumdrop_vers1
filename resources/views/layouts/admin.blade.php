<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SumDrop POS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f1f5f9;
            --surface: #ffffff;
            --surface-strong: #eef2ff;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #475569;
            --accent: #2563eb;
            --accent-soft: #eff6ff;
            --accent-dark: #1d4ed8;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', 'Kantumruy Pro', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        :lang(km) {
            line-height: 1.65;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        .app-shell {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(180deg, #0f1729 0%, #1a2847 100%);
            color: #fff;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 12px rgba(15, 23, 42, 0.15);
        }

        .main {
            margin-left: 0;
        }

        .app-shell .main {
            grid-column: 2;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: grid;
            place-items: center;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .brand h1 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .brand p {
            margin: 0.35rem 0 0;
            color: #cbd5e1;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 0.6rem;
        }

        .nav-section {
            padding-top: 1rem;
            border-top: 1px solid rgba(96, 165, 250, 0.15);
        }

        .nav-section:first-child {
            padding-top: 0;
            border-top: none;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #60a5fa;
            padding: 0.75rem 1rem 0.5rem;
            margin: 0;
        }

        /* Scrollbar styling for fixed sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(96, 165, 250, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(96, 165, 250, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(96, 165, 250, 0.5);
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.1rem;
            border-radius: 10px;
            color: #cbd5e1;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            position: relative;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-item a:hover {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border-left-color: #60a5fa;
            transform: translateX(2px);
        }

        .nav-item a.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.08) 100%);
            color: #ffffff;
            border-left-color: #60a5fa;
            box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.3);
        }

        .sidebar-lang-toggle {
            display: flex;
            gap: 0.4rem;
            margin-top: auto;
        }

        .sidebar-lang-toggle a {
            flex: 1;
            text-align: center;
            padding: 0.5rem 0.6rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #94a3b8;
            background: rgba(148, 163, 184, 0.08);
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .sidebar-lang-toggle a:hover {
            background: rgba(96, 165, 250, 0.15);
            color: #cbd5e1;
        }

        .sidebar-lang-toggle a.active {
            background: rgba(59, 130, 246, 0.28);
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 1.2rem 1.5rem;
            margin: 1rem -1.5rem -2rem -1.5rem;
            border-top: 1px solid rgba(96, 165, 250, 0.2);
            color: #cbd5e1;
            font-size: 0.9rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(96, 165, 250, 0.04) 100%);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-footer-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-signout-btn {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid rgba(248, 113, 113, 0.3);
            background: rgba(248, 113, 113, 0.1);
            color: #fca5a5;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .sidebar-signout-btn:hover {
            background: #dc2626;
            color: #fff;
        }

        .sidebar-footer-info strong {
            display: block;
            color: #ffffff;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .sidebar-footer-info span {
            display: block;
            font-size: 0.82rem;
            color: #60a5fa;
        }

        .main {
            background: var(--surface-strong);
            padding: 1.8rem 1.8rem 2rem;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex: 1;
            min-width: 0;
        }

        .topbar-left > div {
            min-width: 0;
        }

        .topbar h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .topbar small {
            color: var(--muted);
            display: block;
            margin-top: 0.25rem;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .panel-head h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive .table-list,
        .table-responsive .receipts-table {
            min-width: 640px;
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .filter-form .filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .filter-form .filter-actions-end {
            justify-self: end;
        }

        .page-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .page-title {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .page-subtitle {
            margin: 0.35rem 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .card,
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.4rem;
            box-shadow: var(--shadow);
        }

        .table-list {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .table-list th,
        .table-list td {
            padding: 0.95rem 0;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .table-list th {
            font-weight: 600;
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.75rem 1.1rem;
            border-radius: 12px;
            border: none;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn:hover {
            background: var(--accent-dark);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        .btn-danger {
            background: var(--danger);
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-muted {
            background: #f8fafc;
            color: var(--text);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 0.82rem;
        }

        .badge-size {
            background: #ede9fe;
            color: #5b21b6;
        }

        .badge-unit {
            background: #e0f2fe;
            color: #075985;
        }

        .badge-low-stock {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-cash {
            background: #dcfce7;
            color: #166534;
        }

        .status-bank {
            background: #e0f2fe;
            color: #0c4a6e;
        }

        .status-mobile {
            background: #ffe7d9;
            color: #9a3412;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 0.5rem;
        }

        .page-head {
            margin-bottom: 1.35rem;
        }

        .page-head h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .page-head p {
            margin: 0.45rem 0 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        label {
            display: block;
            margin-bottom: 0.45rem;
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 0.75rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            color: var(--text);
            font-family: inherit;
            font-size: 0.95rem;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .field {
            margin-bottom: 1rem;
        }

        .field-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .field-error,
        .alert-error,
        .alert-success {
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.92rem;
            line-height: 1.4;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #bbf0cd;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .field-error {
            color: #991b1b;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .back-link {
            color: var(--muted);
            font-size: 0.92rem;
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--text);
        }

        .menu-toggle {
            display: none;
            width: 46px;
            height: 46px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font-size: 22px;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            opacity: 0;
            visibility: hidden;
            transition: .3s;
            z-index: 1500;
        }

        /* Laptop */
        @media (max-width: 1280px) {
            .main {
                padding: 1.5rem;
            }

            .container {
                max-width: 100%;
            }
        }

        /* Tablet & small laptop */
        @media (max-width: 1024px) {
            .app-shell {
                display: block;
            }

            .sidebar {
                position: fixed;
                left: -290px;
                top: 0;
                width: 280px;
                height: 100vh;
                transition: .3s;
                z-index: 2000;
            }

            .sidebar.active {
                left: 0;
            }

            .overlay {
                display: block;
            }

            .overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .main {
                margin-left: 0;
                padding: 1.125rem;
            }

            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .field-row {
                grid-template-columns: 1fr;
            }
        }

        /* Phone */
        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .topbar-left {
                width: 100%;
            }

            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .page-actions .btn,
            .page-actions form {
                flex: 1 1 auto;
            }

            .filter-form .filter-actions-end {
                justify-self: stretch;
            }

            .filter-form .filter-actions-end button {
                width: 100%;
            }
        }

        /* Small phone */
        @media (max-width: 480px) {
            .main {
                padding: 0.875rem;
            }

            .card,
            .panel {
                padding: 1.125rem;
                border-radius: 16px;
            }

            .topbar h2 {
                font-size: 1.25rem;
            }

            .page-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .page-actions .btn,
            .page-actions form {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="app-shell">
        <aside class="sidebar">
            

            <nav>
                @php $isAdmin = auth()->user()?->role === 'admin'; @endphp
                <ul class="nav-list">
                    <!-- Main Section -->
                    <li class="nav-section">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <span>{{ $isAdmin ? __('menu.dashboard') : __('menu.pos') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                <span>{{ __('menu.orders') }}</span>
                            </a>
                        </li>
                    </li>
                    <!-- Operations Section -->
                    <li class="nav-section">
                        <p class="nav-section-title">{{ __('menu.operations') }}</p>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <span>{{ __('menu.menu') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.*') ? 'active' : '' }}">
                                <span>{{ __('menu.stock') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchases.index') }}" class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                                <span>{{ __('menu.purchases') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('receipts.index') }}" class="{{ request()->routeIs('receipts.*') ? 'active' : '' }}">
                                <span>{{ __('menu.receipt') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <span>{{ __('menu.categories') }}</span>
                            </a>
                        </li>
                    </li>
                    <!-- Management Section (Admin Only) -->
                    @if($isAdmin)
                        <li class="nav-section">
                            <p class="nav-section-title">{{ __('menu.management') }}</p>
                            <li class="nav-item">
                                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                    <span>{{ __('menu.reports') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <span>{{ __('menu.users') }}</span>
                                </a>
                            </li>
                        </li>
                    @endif
                </ul>
            </nav>
            <div class="sidebar-lang-toggle">
                <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'km') }}" class="{{ app()->getLocale() === 'km' ? 'active' : '' }}">ខ្មែរ</a>
            </div>
            <div class="sidebar-footer">
                <div class="sidebar-footer-info">
                    <strong>{{ auth()->user()?->name ?? __('menu.user_fallback') }}</strong>
                    <span>{{ auth()->user()?->role === 'admin' ? __('menu.role_admin') : __('menu.role_staff') }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-signout-btn" title="{{ __('menu.sign_out') }}" aria-label="{{ __('menu.sign_out') }}">⏻</button>
                </form>
            </div>
        </aside>

        <div class="overlay" id="overlay"></div>

        <main class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="{{ __('menu.open_menu') }}">☰</button>
                    <div>
                        <h2>@yield('page-title', 'Dashboard')</h2>
                        @hasSection('page-subtitle')
                            <small>@yield('page-subtitle')</small>
                        @endif
                    </div>
                </div>
                <div class="page-actions">
                    @yield('page-actions')
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.querySelector(".sidebar");
        const toggle = document.getElementById("menuToggle");
        const overlay = document.getElementById("overlay");

        toggle?.addEventListener("click", () => {
            sidebar.classList.toggle("active");
            overlay.classList.toggle("active");
        });

        overlay.addEventListener("click", () => {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        });

        document.querySelectorAll(".sidebar a").forEach(item => {
            item.addEventListener("click", () => {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.remove("active");
                    overlay.classList.remove("active");
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>