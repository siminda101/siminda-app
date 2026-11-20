@extends('layouts.app')

@section('title', 'Mutasi Barang')
@section('breadcrumb', 'Pengelolaan Inventaris / Mutasi Barang')

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title">Mutasi Barang</h4>
        <p class="page-subtitle">
            Pengelolaan pemindahan barang antar ruangan.
        </p>
    </div>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('mutations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajukan Mutasi
    </a>
    @endif
</div>

{{-- PESAN SUKSES --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close"
        data-bs-dismiss="alert"></button>
</div>
@endif

{{-- PESAN ERROR --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close"
        data-bs-dismiss="alert"></button>
</div>
@endif

{{-- RINGKASAN STATUS --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="stat-label">Menunggu Persetujuan</div>
            <div class="stat-value text-warning">
                {{ $mutations->total() > 0 ? $mutations->getCollection()->where('status', 'pending')->count() : 0 }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="stat-label">Disetujui</div>
            <div class="stat-value text-success">
                {{ $mutations->getCollection()->where('status', 'approved')->count() }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value text-danger">
                {{ $mutations->getCollection()->where('status', 'rejected')->count() }}
            </div>
        </div>
    </div>

</div>

{{-- TABEL MUTASI --}}
<div class="card p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Riwayat Pengajuan Mutasi</h6>
        <span class="text-muted small">
            Total data: {{ $mutations->total() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Ruangan Asal</th>
                    <th>Ruangan Tujuan</th>
                    <th>Jumlah</th>
                    <th>Pengaju</th>
                    <th>Status</th>

                    @if(auth()->user()->role === 'pimpinan')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>

                @forelse($mutations as $index => $mutation)

                <tr>
                    <td>
                        {{ $mutations->firstItem() + $index }}
                    </td>

                    <td>
                        {{ $mutation->mutation_date?->format('d/m/Y') ?? '-' }}
                    </td>

                    <td>
                        <div class="fw-semibold">
                            {{ $mutation->roomInventory?->item?->name ?? 'Barang tidak ditemukan' }}
                        </div>
                        <small class="text-muted">
                            Kode:
                            {{ $mutation->roomInventory?->item?->code ?? '-' }}
                        </small>
                    </td>

                    <td>
                        {{ $mutation->fromRoom?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $mutation->toRoom?->name ?? '-' }}
                    </td>

                    <td>
                        <small>
                            Baik: {{ $mutation->quantity_good }}<br>
                            Rusak Ringan: {{ $mutation->quantity_minor }}<br>
                            Rusak Berat: {{ $mutation->quantity_severe }}
                        </small>

                        <div class="fw-bold mt-1">
                            Total:
                            {{ $mutation->quantity_good + $mutation->quantity_minor + $mutation->quantity_severe }}
                        </div>
                    </td>

                    <td>
                        {{ $mutation->creator?->name ?? '-' }}
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

                    @if(auth()->user()->role === 'pimpinan')
                    <td>
                        @if($mutation->status === 'pending')

                        <div class="d-flex gap-1">

                            <form
                                action="{{ route('mutations.approve', $mutation->id) }}"
                                method="POST"
                                onsubmit="return confirm('Setujui pengajuan mutasi ini?')">
                                @csrf

                                <button type="submit"
                                    class="btn btn-sm btn-success"
                                    title="Setujui">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>

                            <form
                                action="{{ route('mutations.reject', $mutation->id) }}"
                                method="POST"
                                onsubmit="return confirm('Tolak pengajuan mutasi ini?')">
                                @csrf

                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    title="Tolak">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>

                        </div>

                        @else
                        <span class="text-muted">Selesai</span>
                        @endif
                    </td>
                    @endif

                </tr>

                @empty

                <tr>
                    <td colspan="{{ auth()->user()->role === 'pimpinan' ? 9 : 8 }}"
                        class="text-center py-5">

                        <i class="bi bi-arrow-left-right fs-1 text-muted"></i>

                        <p class="text-muted mt-2 mb-0">
                            Belum ada pengajuan mutasi barang.
                        </p>

                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('mutations.create') }}"
                            class="btn btn-sm btn-primary mt-3">
                            Buat Pengajuan Mutasi
                        </a>
                        @endif

                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <div class="mt-3">
        {{ $mutations->links() }}
    </div>

</div>

@endsection