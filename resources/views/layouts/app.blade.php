<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard') - SIMINDA SETDA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #164B85;
            --primary-dark: #10375F;
            --sidebar-width: 260px;
            --bg: #F4F7FB;
            --text: #263445;
            --muted: #8290A3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--primary-dark);
            color: white;
            z-index: 1040;
            overflow-y: auto;
            transition: .3s;
        }

        .brand {
            padding: 25px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: white;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .brand h5 {
            margin: 0;
            font-weight: 700;
            font-size: 17px;
        }

        .brand small {
            color: #B9CBE0;
            font-size: 11px;
        }

        .menu-label {
            color: #8FAAC7;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            padding: 24px 22px 10px;
        }

        .sidebar .nav-link {
            color: #D0DCE9;
            margin: 3px 12px;
            padding: 12px 14px;
            border-radius: 9px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: .2s;
        }

        .sidebar .nav-link i {
            font-size: 17px;
            width: 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #28649D;
            color: white;
        }

        .sidebar-footer {
            margin: 20px 12px;
            padding: 15px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .08);
            font-size: 12px;
            color: #D0DCE9;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            height: 75px;
            background: white;
            border-bottom: 1px solid #E8EDF3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .page-content {
            padding: 30px;
        }

        .page-title {
            font-size: 23px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 13px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #E4EFFB;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 13px;
            box-shadow: 0 3px 15px rgba(35, 55, 80, .04);
        }

        .stat-card {
            padding: 21px;
            height: 100%;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 700;
            margin-top: 17px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--muted);
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
        }

        .table thead th {
            background: #F7F9FC;
            color: #6C7A8D;
            font-size: 12px;
            font-weight: 600;
            padding: 14px;
            border-bottom: 0;
        }

        .table tbody td {
            font-size: 13px;
            padding: 14px;
            vertical-align: middle;
        }

        .mobile-toggle {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: inline-block;
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .4);
                z-index: 1030;
            }

            .topbar {
                padding: 0 18px;
            }

            .page-content {
                padding: 20px 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('partials.sidebar')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">

        @include('partials.navbar')

        <main class="page-content">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        toggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>

    @stack('scripts')

</body>

</html>