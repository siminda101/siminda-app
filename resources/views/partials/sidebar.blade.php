<aside class="sidebar" id="sidebar">

    {{-- BRAND --}}
    <div class="brand">
        <div class="brand-icon">
            <i class="bi bi-building"></i>
        </div>

        <div>
            <h5>SIMINDA</h5>
            <small>SETDA KABUPATEN KUNINGAN</small>
        </div>
    </div>

    {{-- MENU UTAMA --}}
    <div class="menu-label">MENU UTAMA</div>

    <nav class="nav flex-column">

        {{-- DASHBOARD --}}
        <a href="{{ route('dashboard') }}"
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>

    </nav>


    {{-- MASTER DATA --}}
    @if(auth()->user()->role === 'admin')

    <div class="menu-label">MASTER DATA</div>

    <nav class="nav flex-column">

        {{-- KATEGORI --}}
        <a href="{{ route('categories.index') }}"
            class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            <span>Kategori Barang</span>
        </a>

        {{-- RUANGAN --}}
        <a href="{{ route('rooms.index') }}"
            class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i>
            <span>Data Ruangan</span>
        </a>
    </nav>

    @endif


    {{-- PENGELOLAAN INVENTARIS --}}
    <div class="menu-label">PENGELOLAAN INVENTARIS</div>

    <nav class="nav flex-column">

        {{-- INVENTARIS --}}
        <a href="{{ route('inventories.index') }}"
            class="nav-link {{ request()->routeIs('inventories.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-data"></i>
            <span>Inventaris Ruangan</span>
        </a>

        {{-- MUTASI --}}
        {{-- MUTASI --}}
        <a href="{{ route('mutations.index') }}"
            class="nav-link {{ request()->routeIs('mutations.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>Mutasi Barang</span>
        </a>
    </nav>


    {{-- INFORMASI --}}
    <div class="menu-label">INFORMASI</div>

    <nav class="nav flex-column">

        <a href="{{ route('reports.index') }}"
            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan Inventaris</span>
        </a>

    </nav>


    {{-- FOOTER --}}
    <div class="sidebar-footer">
        <div class="fw-bold mb-1">
            <i class="bi bi-shield-check me-1"></i>
            SIMINDA SETDA
        </div>

        Sistem Informasi Manajemen Inventaris
    </div>

</aside>