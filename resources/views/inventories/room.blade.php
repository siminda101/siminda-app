@extends('layouts.app')

@section('title', 'Detail Inventaris Ruangan')
@section('breadcrumb', 'Inventaris / Detail Ruangan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="page-title">{{ $room->name }}</h4>
        <p class="page-subtitle">
            Kode: {{ $room->code }} |
            {{ $room->work_unit }}
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('inventories.index') }}"
            class="btn btn-light border">
            Kembali
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('inventories.create', ['room_id' => $room->id]) }}"
            class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Barang
        </a>
        @endif
    </div>
</div>

{{-- NOTIFIKASI BERHASIL --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}

    <button type="button" class="btn-close"
        data-bs-dismiss="alert"></button>
</div>
@endif

{{-- NOTIFIKASI ERROR --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle me-2"></i>
    {{ session('error') }}

    <button type="button" class="btn-close"
        data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card p-4">

    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Merek / Model</th>
                    <th>Tahun</th>
                    <th>Baik</th>
                    <th>Rusak Ringan</th>
                    <th>Rusak Berat</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inventories as $index => $inventory)
                <tr>
                    <td>{{ $inventories->firstItem() + $index }}</td>

                    <td>{{ $inventory->item->code }}</td>

                    <td class="fw-semibold">
                        {{ $inventory->item->name }}
                    </td>

                    <td>{{ $inventory->item->brand ?? '-' }}</td>

                    <td>{{ $inventory->acquisition_year ?? '-' }}</td>

                    <td>{{ $inventory->quantity_good }}</td>

                    <td>{{ $inventory->quantity_minor }}</td>

                    <td>{{ $inventory->quantity_severe }}</td>

                    <td class="fw-bold">
                        {{ $inventory->quantity_good + $inventory->quantity_minor + $inventory->quantity_severe }}
                    </td>

                    <td>
                        @if(auth()->user()->role === 'admin')

                        <div class="d-flex gap-2">

                            {{-- TOMBOL EDIT --}}
                            <a href="{{ route('inventories.edit', $inventory->id) }}"
                                class="btn btn-sm btn-warning"
                                title="Edit Barang">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- TOMBOL HAPUS --}}
                            <form action="{{ route('inventories.destroy', $inventory->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus barang {{ $inventory->item->name }}? Data yang dihapus tidak dapat dikembalikan.')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    title="Hapus Barang">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </div>

                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="10"
                        class="text-center py-4 text-muted">
                        Belum ada inventaris di ruangan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($inventories->count() > 0)
            <tr class="table-primary fw-bold">
                <td colspan="5" class="text-end">TOTAL</td>
                <td>{{ $summary->total_good ?? 0 }}</td>
                <td>{{ $summary->total_minor ?? 0 }}</td>
                <td>{{ $summary->total_severe ?? 0 }}</td>
                <td>{{ $summary->total_all ?? 0 }}</td>
                <td>-</td>
            </tr>
            @endif

        </table>
    </div>

    <div class="mt-3">
        {{ $inventories->links() }}
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('input[type="number"]').forEach(function(input) {

            input.addEventListener('wheel', function(event) {
                if (document.activeElement === input) {
                    input.blur();
                }
            }, {
                passive: true
            });

        });

    });
</script>

@endsection