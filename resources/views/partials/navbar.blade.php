<header class="topbar">

    <div class="d-flex align-items-center gap-3">

        <button class="btn btn-light mobile-toggle" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div>
            <div class="fw-semibold">
                @yield('breadcrumb', 'Dashboard')
            </div>
            <small class="text-secondary">
                Sistem Informasi Manajemen Inventaris
            </small>
        </div>

    </div>

    <div class="d-flex align-items-center gap-3">

        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold small">
                {{ auth()->user()->name }}
            </div>
            <small class="text-secondary">
                {{ ucfirst(auth()->user()->role) }}
            </small>
        </div>

        <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i>
                <span class="d-none d-md-inline">Keluar</span>
            </button>
        </form>

    </div>

</header>