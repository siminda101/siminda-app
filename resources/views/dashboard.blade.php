@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

@php
$totalCondition = $totalGood + $totalMinor + $totalSevere;

$goodPercent = $totalCondition > 0
? round(($totalGood / $totalCondition) * 100, 1) : 0;

$minorPercent = $totalCondition > 0
? round(($totalMinor / $totalCondition) * 100, 1) : 0;

$severePercent = $totalCondition > 0
? round(($totalSevere / $totalCondition) * 100, 1) : 0;
@endphp

{{-- HEADER DASHBOARD --}}
<div class="mb-4">
    <h4 class="page-title">Dashboard</h4>
    <p class="page-subtitle">
        Selamat datang di Sistem Informasi Manajemen Inventaris
        SETDA Kabupaten Kuningan.
    </p>
</div>

{{-- STATISTIK UTAMA --}}
<div class="row g-3 mb-4">

    {{-- Total Barang --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <span class="badge text-bg-light">Inventaris</span>
            </div>

            <div class="stat-value">
                {{ number_format($totalItems) }}
            </div>

            <div class="stat-label">Total Barang</div>

            <small class="text-secondary mt-2">
                {{ number_format($totalItemTypes) }} jenis barang
            </small>
        </div>
    </div>

    {{-- Total Ruangan --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-door-open"></i>
                </div>
                <span class="badge text-bg-light">Ruangan</span>
            </div>

            <div class="stat-value">
                {{ number_format($totalRooms) }}
            </div>

            <div class="stat-label">Total Ruangan</div>

            <small class="text-secondary mt-2">
                {{ $activeRooms }} aktif · {{ $inactiveRooms }} nonaktif
            </small>
        </div>
    </div>

    {{-- Rusak Ringan --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <span class="badge text-bg-light">Perhatian</span>
            </div>

            <div class="stat-value">
                {{ number_format($totalMinor) }}
            </div>

            <div class="stat-label">Barang Rusak Ringan</div>
        </div>
    </div>

    {{-- Rusak Berat --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="d-flex justify-content-between">
                <div class="stat-icon bg-danger-subtle text-danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <span class="badge text-bg-light">Perhatian</span>
            </div>

            <div class="stat-value">
                {{ number_format($totalSevere) }}
            </div>

            <div class="stat-label">Barang Rusak Berat</div>
        </div>
    </div>

</div>

{{-- KONDISI BARANG DAN AKSES CEPAT --}}
<div class="row g-3 mb-4">

    {{-- Ringkasan Kondisi --}}
    <div class="col-lg-7">
        <div class="card p-4 h-100">

            <h6 class="section-title mb-3">
                <i class="bi bi-pie-chart me-2 text-primary"></i>
                Ringkasan Kondisi Barang
            </h6>

            @if($totalCondition > 0)

            {{-- Kondisi Baik --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="small">
                        Baik ({{ number_format($totalGood) }} barang)
                    </span>
                    <span class="small fw-semibold">
                        {{ $goodPercent }}%
                    </span>
                </div>

                <div class="progress" style="height: 9px;">
                    <div class="progress-bar bg-success"
                        role="progressbar"
                        @style(['width'=> $goodPercent . '%'])>
                    </div>
                </div>
            </div>

            {{-- Rusak Ringan --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="small">
                        Rusak Ringan ({{ number_format($totalMinor) }} barang)
                    </span>
                    <span class="small fw-semibold">
                        {{ $minorPercent }}%
                    </span>
                </div>

                <div class="progress" style="height: 9px;">
                    <div class="progress-bar bg-warning"
                        role="progressbar"
                        @style(['width'=> $minorPercent . '%'])>
                    </div>
                </div>
            </div>

            {{-- Rusak Berat --}}
            <div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small">
                        Rusak Berat ({{ number_format($totalSevere) }} barang)
                    </span>
                    <span class="small fw-semibold">
                        {{ $severePercent }}%
                    </span>
                </div>

                <div class="progress" style="height: 9px;">
                    <div class="progress-bar bg-danger"
                        role="progressbar"
                        @style(['width'=> $severePercent . '%'])>
                    </div>
                </div>
            </div>

            @else

            <div class="text-center text-secondary py-4">
                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                Belum ada data inventaris.
            </div>

            @endif

            <small class="text-secondary mt-3">
                Persentase dihitung berdasarkan seluruh jumlah barang
                dari ketiga kondisi.
            </small>

        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="col-lg-5">
        <div class="card p-4 h-100">

            <h6 class="section-title mb-3">
                <i class="bi bi-lightning-charge me-2 text-primary"></i>
                Akses Cepat
            </h6>

            @if(auth()->user()->role === 'admin')

            <a href="{{ route('inventories.index') }}"
                class="btn btn-outline-primary text-start mb-2">
                <i class="bi bi-plus-circle me-2"></i>
                Input Inventaris
            </a>

            <a href="{{ route('rooms.create') }}"
                class="btn btn-outline-primary text-start mb-2">
                <i class="bi bi-door-open me-2"></i>
                Tambah Ruangan
            </a>

            <a href="{{ route('mutations.create') }}"
                class="btn btn-outline-primary text-start">
                <i class="bi bi-arrow-left-right me-2"></i>
                Ajukan Mutasi Barang
            </a>

            @else

            <a href="{{ route('inventories.index') }}"
                class="btn btn-outline-primary text-start mb-2">
                <i class="bi bi-clipboard-data me-2"></i>
                Lihat Inventaris
            </a>

            <a href="{{ route('mutations.index') }}"
                class="btn btn-outline-primary text-start">
                <i class="bi bi-arrow-left-right me-2"></i>
                Persetujuan Mutasi

                <span class="badge bg-warning text-dark ms-1">
                    {{ $pendingMutations }}
                </span>
            </a>

            @endif

        </div>
    </div>

</div>

{{-- RINGKASAN MUTASI --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-secondary small">Total Mutasi</div>
            <h4 class="fw-bold mb-0">
                {{ number_format($totalMutations) }}
            </h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-secondary small">
                Menunggu Persetujuan
            </div>
            <h4 class="fw-bold text-warning mb-0">
                {{ number_format($pendingMutations) }}
            </h4>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-secondary small">Mutasi Disetujui</div>
            <h4 class="fw-bold text-success mb-0">
                {{ number_format($approvedMutations) }}
            </h4>
        </div>
    </div>

</div>

{{-- AKTIVITAS MUTASI TERBARU --}}
<div class="card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="section-title mb-0">
            <i class="bi bi-clock-history me-2 text-primary"></i>
            Aktivitas Mutasi Terbaru
        </h6>

        <a href="{{ route('mutations.index') }}"
            class="small text-decoration-none">
            Lihat Semua
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Barang</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Pengaju</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($recentMutations as $index => $mutation)

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td class="fw-semibold">
                        {{ $mutation->roomInventory?->item?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $mutation->fromRoom?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $mutation->toRoom?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $mutation->creator?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $mutation->mutation_date
                                ? $mutation->mutation_date->format('d/m/Y')
                                : '-' }}
                    </td>

                    <td>
                        @if($mutation->status === 'pending')
                        <span class="badge bg-warning text-dark">
                            Menunggu
                        </span>
                        @elseif($mutation->status === 'approved')
                        <span class="badge bg-success">
                            Disetujui
                        </span>
                        @elseif($mutation->status === 'rejected')
                        <span class="badge bg-danger">
                            Ditolak
                        </span>
                        @else
                        <span class="badge bg-secondary">
                            {{ $mutation->status }}
                        </span>
                        @endif
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="7"
                        class="text-center text-secondary py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada aktivitas mutasi barang.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

{{-- INVENTARIS PER RUANGAN --}}
<div class="card p-4">

    <div class="mb-3">
        <h6 class="section-title mb-1">
            <i class="bi bi-building me-2 text-primary"></i>
            Ringkasan Inventaris Per Ruangan
        </h6>

        <small class="text-secondary">
            Jumlah barang mencakup kondisi baik, rusak ringan,
            dan rusak berat.
        </small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Ruangan</th>
                    <th>Jenis Inventaris</th>
                    <th>Baik</th>
                    <th>Rusak Ringan</th>
                    <th>Rusak Berat</th>
                    <th>Total Barang</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($roomInventories as $index => $room)

                @php
                $good = (int) ($room->total_good ?? 0);
                $minor = (int) ($room->total_minor ?? 0);
                $severe = (int) ($room->total_severe ?? 0);

                $total = $good + $minor + $severe;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td class="fw-semibold">
                        {{ $room->name }}
                    </td>

                    <td>
                        {{ $room->inventories_count }}
                    </td>

                    <td>{{ number_format($good) }}</td>

                    <td>{{ number_format($minor) }}</td>

                    <td>{{ number_format($severe) }}</td>

                    <td class="fw-bold">
                        {{ number_format($total) }}
                    </td>

                    <td>
                        @if($room->is_active)
                        <span class="badge bg-success">
                            Aktif
                        </span>
                        @else
                        <span class="badge bg-secondary">
                            Nonaktif
                        </span>
                        @endif
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="8"
                        class="text-center text-secondary py-4">
                        Belum ada data ruangan.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection