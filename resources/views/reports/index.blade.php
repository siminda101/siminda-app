@extends('layouts.app')

@section('title', 'Laporan Inventaris')
@section('breadcrumb', 'Informasi / Laporan Inventaris')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title">Laporan Inventaris</h4>
        <p class="page-subtitle">
            Lihat dan cetak laporan inventaris Bagian Umum SETDA Kabupaten Kuningan.
        </p>
    </div>

    <a href="{{ route('reports.all') }}"
        class="btn btn-primary">
        <i class="bi bi-printer me-1"></i>
        Cetak Semua Ruangan
    </a>
</div>

<div class="card p-4">

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>Kode Ruangan</th>
                    <th>Nama Ruangan</th>
                    <th>Jumlah Jenis Inventaris</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rooms as $index => $room)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $room->code }}</td>
                    <td class="fw-semibold">{{ $room->name }}</td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $room->inventories_count }} jenis
                        </span>
                    </td>
                    <td>
                        @if($room->is_active)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('reports.room', $room->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>
                            Lihat / Cetak
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Belum ada data ruangan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection