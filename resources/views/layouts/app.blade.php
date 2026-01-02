<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quotation App')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; min-height: 100vh; }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 0 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }
        .navbar-brand {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand:hover {
            color: #e0e7ff;
        }
        .navbar-brand svg {
            width: 28px;
            height: 28px;
        }
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        .nav-link svg {
            width: 16px;
            height: 16px;
        }
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .user-info {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .user-info svg {
            width: 20px;
            height: 20px;
        }
        .btn-logout {
            background: rgba(239, 68, 68, 0.9);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-logout:hover {
            background: #dc2626;
        }
        .btn-logout svg {
            width: 16px;
            height: 16px;
        }
        
        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }
        .dropdown-toggle {
            cursor: pointer;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 200px;
            padding: 8px 0;
            display: none;
            z-index: 1001;
            margin-top: 8px;
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }
        .dropdown-item:hover {
            background: #f3f4f6;
        }
        .dropdown-item svg {
            width: 18px;
            height: 18px;
            color: #6b7280;
        }
        .dropdown-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 8px 0;
        }
        
        /* Main Content */
        .main-content {
            padding: 30px 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Common Styles */
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .card-header h1 { font-size: 18px; color: #333; }
        .card-body { padding: 24px; }
        .btn { display: inline-block; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-success { background: #059669; color: #fff; }
        .btn-success:hover { background: #047857; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        
        /* Mobile Menu Toggle */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 8px;
        }
        .mobile-menu-btn svg {
            width: 24px;
            height: 24px;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            .navbar-menu {
                position: absolute;
                top: 60px;
                left: 0;
                right: 0;
                background: #1e3a8a;
                flex-direction: column;
                padding: 16px;
                gap: 8px;
                display: none;
            }
            .navbar-menu.show {
                display: flex;
            }
            .nav-link {
                width: 100%;
                justify-content: flex-start;
            }
            .navbar-right {
                flex-direction: column;
                width: 100%;
                gap: 12px;
                padding-top: 12px;
                border-top: 1px solid rgba(255,255,255,0.2);
            }
        }
        
        @yield('styles')
    </style>
</head>
<body>
    @auth
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Quotation App
            </a>
            
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <div class="navbar-menu" id="navbarMenu">
            <a href="/"
                class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5a1 1 0 01-1-1v-4H10v4a1 1 0 01-1 1H4a1 1 0 01-1-1V9.75z" />
                    </svg>

                    Home
                </a>
                <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Customers
                </a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Products
                </a>
                <a href="{{ route('quotations.index') }}" class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Quotations
                </a>
                <a href="{{ route('companies.index') }}" class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                    Companies
                </a>
                <a href="{{ route('terms-conditions.index') }}" class="nav-link {{ request()->routeIs('terms-conditions.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Terms & Conditions
                </a>
                <a href="{{ route('accessories.index') }}" class="nav-link {{ request()->routeIs('accessories.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Accessories
                </a>
                
                <div class="navbar-right">
                    <div class="user-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ Auth::user()->name }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth
    
    <div class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </div>
    
    <script>
        function toggleMobileMenu() {
            document.getElementById('navbarMenu').classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>
</html>
