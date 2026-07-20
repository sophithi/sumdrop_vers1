<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SumDrop POS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
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

        /* Responsive design */
        @media (max-width: 768px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .app-shell .main {
                grid-column: auto;
            }
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

        .sidebar-footer {
            margin-top: auto;
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
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .topbar h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .topbar small {
            color: var(--muted);
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

        @media (max-width:1024px) {

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
                padding: 18px;
            }

            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .topbar {
                gap: 15px;
                flex-wrap: wrap;
            }

            .page-actions {
                flex-wrap: wrap;
            }

        }

        /* Phone */

        @media (max-width:768px) {

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-actions {
                width: 100%;
            }

            .page-actions form {
                width: 100%;
            }

            .page-actions .btn {
                width: 100%;
            }

        }

        /* Small phone */

        @media (max-width:480px) {

            .main {
                padding: 14px;
            }

            .card,
            .panel {
                padding: 18px;
                border-radius: 16px;
            }

            .topbar h2 {
                font-size: 20px;
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
                        <p class="nav-section-title">Operations</p>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <span>{{ __('menu.menu') }}</span>
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
                            <p class="nav-section-title">Management</p>
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
            <div class="sidebar-footer">
                <div class="sidebar-footer-info">
                    <strong>{{ auth()->user()?->name ?? 'User' }}</strong>
                    <span>{{ auth()->user()?->role ? ucfirst(auth()->user()->role) : 'Staff' }}</span>
                </div>
            </div>
        </aside>

        <div class="overlay" id="overlay"></div>

        <main class="main">
            <div class="topbar">
                <div>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    @hasSection('page-subtitle')
                        <small>@yield('page-subtitle')</small>
                    @endif
                </div>
                <div class="page-actions">
                    @yield('page-actions')

                    <!-- Language Switcher -->
                    <div style="position: relative;">
                        <select id="language-select"
                            style="appearance: none; padding-right: 2rem; cursor: pointer; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 16 16%22><path fill=%22%230f172a%22 d=%22M4.5 6L8 10l3.5-4z%22/></svg>'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.2rem;">
                            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                            <option value="km" {{ app()->getLocale() === 'km' ? 'selected' : '' }}>ខ្មែរ</option>
                        </select>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">{{ __('menu.sign_out') }}</button>
                    </form>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.querySelector(".sidebar");
        const toggle = document.getElementById("menuToggle");
        const overlay = document.getElementById("overlay");

        toggle.addEventListener("click", () => {
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

        /* Language */
        document.getElementById('language-select').addEventListener('change', function (e) {
            const lang = e.target.value;
            const baseUrl = "{{ route('language.switch', ['locale' => '__LOCALE__']) }}".replace('__LOCALE__', lang);
            window.location.href = baseUrl;
        });
    </script>

    @stack('scripts')
</body>

</html>