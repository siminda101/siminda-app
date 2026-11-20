@extends('layouts.app')

@section('title', 'Data Ruangan')
@section('breadcrumb', 'Master Data / Data Ruangan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title">Data Ruangan</h4>
        <p class="page-subtitle">Kelola data ruangan Bagian Umum Setda.</p>
    </div>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Ruangan
    </a>
    @endif
</div>

<div class="card p-4">

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form method="GET" action="{{ route('rooms.index') }}" class="mb-3">
        <div class="input-group" style="max-width: 450px;">
            <input type="text"
                name="search"
                class="form-control"
                value="{{ request('search') }}"
                placeholder="Cari kode atau nama ruangan...">

            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>

            @if(request('search'))
            <a href="{{ route('rooms.index') }}" class="btn btn-light border">
                Reset
            </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>Kode Ruangan</th>
                    <th>Nama Ruangan</th>
                    <th>Unit Kerja</th>
                    <th>Jumlah Jenis Inventaris</th>
                    <th>Status</th>

                    @if(auth()->user()->role === 'admin')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($rooms as $index => $room)
                <tr>
                    <td>{{ $rooms->firstItem() + $index }}</td>

                    <td>{{ $room->code ?: '-' }}</td>

                    <td class="fw-semibold">
                        {{ $room->name }}
                    </td>

                    <td>{{ $room->work_unit }}</td>

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

                    @if(auth()->user()->role === 'admin')
                    <td>
                        <div class="d-flex gap-1">

                            {{-- Tombol Edit --}}
                            <a href="{{ route('rooms.edit', $room->id) }}"
                                class="btn btn-sm btn-warning"
                                title="Edit Ruangan">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- Tombol Aktifkan / Nonaktifkan --}}
                            <form action="{{ route('rooms.toggleStatus', $room->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin mengubah status ruangan ini?')">

                                @csrf
                                @method('PATCH')

                                @if($room->is_active)
                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    title="Nonaktifkan Ruangan">
                                    <i class="bi bi-toggle-on"></i>
                                </button>
                                @else
                                <button type="submit"
                                    class="btn btn-sm btn-success"
                                    title="Aktifkan Ruangan">
                                    <i class="bi bi-toggle-off"></i>
                                </button>
                                @endif

                            </form>
                        </div>
                    </td>
                    @endif

                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}"
                        class="text-center py-4 text-muted">
                        Belum ada data ruangan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $rooms->links() }}
    </div>

</div>

@endsection