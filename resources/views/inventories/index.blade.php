@extends('layouts.app')

@section('title', 'Inventaris Ruangan')
@section('breadcrumb', 'Pengelolaan Inventaris / Inventaris Ruangan')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Inventaris Ruangan</h4>
    <p class="page-subtitle">
        Pilih ruangan untuk melihat dan mengelola inventaris barang.
    </p>
</div>

<div class="row g-3">

    @forelse($rooms as $room)
    <div class="col-md-6 col-xl-4">
        <div class="card p-4 h-100">

            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon"
                    style="background:#E4EFFB;color:#164B85;">
                    <i class="bi bi-door-open"></i>
                </div>

                <span class="badge bg-primary align-self-start">
                    {{ $room->inventories_count }} jenis barang
                </span>
            </div>

            <h5 class="fw-bold">{{ $room->name }}</h5>

            <p class="text-secondary small mb-1">
                Kode: {{ $room->code }}
            </p>

            <p class="text-secondary small mb-3">
                {{ $room->work_unit }}
            </p>

            <a href="{{ route('inventories.room', $room->id) }}"
                class="btn btn-outline-primary mt-auto">
                <i class="bi bi-eye me-1"></i>
                Lihat Inventaris
            </a>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card p-5 text-center">
            <i class="bi bi-door-closed fs-1 text-secondary"></i>
            <h5 class="mt-3">Belum Ada Ruangan</h5>
            <p class="text-secondary">
                Tambahkan data ruangan terlebih dahulu melalui menu Data Ruangan.
            </p>
        </div>
    </div>
    @endforelse

</div>

@endsection