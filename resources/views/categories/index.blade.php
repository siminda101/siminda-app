@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('breadcrumb', 'Master Data / Kategori Barang')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="page-title">Kategori Barang</h4>
        <p class="page-subtitle">Kelola pengelompokan barang inventaris SETDA.</p>
    </div>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card p-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h6 class="section-title mb-1">Daftar Kategori</h6>
            <small class="text-secondary">Total {{ $categories->total() }} kategori</small>
        </div>

        <form action="{{ route('categories.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control"
                placeholder="Cari kategori..."
                value="{{ request('search') }}">
            <button class="btn btn-outline-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="60">No.</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Jenis Barang</th>
                    @if(auth()->user()->role === 'admin')
                    <th width="150">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>
                    <td class="fw-semibold">{{ $category->name }}</td>
                    <td>{{ $category->description ?: '-' }}</td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $category->items_count }} jenis
                        </span>
                    </td>

                    @if(auth()->user()->role === 'admin')
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.edit', $category->id) }}"
                                class="btn btn-sm btn-warning text-white"
                                title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('categories.destroy', $category->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'admin' ? 5 : 4 }}"
                        class="text-center text-secondary py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada data kategori.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $categories->links() }}
    </div>

</div>

@endsection