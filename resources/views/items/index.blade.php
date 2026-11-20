@extends('layouts.app')

@section('title', 'Data Barang')
@section('breadcrumb', 'Master Data / Data Barang')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="page-title">Data Barang</h4>
        <p class="page-subtitle">Kelola master data barang inventaris SETDA.</p>
    </div>

    @if(Auth::user()->role === 'admin')
    <a href="{{ route('items.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
    </a>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
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

    <form method="GET" action="{{ route('items.index') }}"
        class="row g-2 mb-4">

        <div class="col-md-5">
            <input type="text" name="search" class="form-control"
                placeholder="Cari kode, nama, atau merek barang..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <a href="{{ route('items.index') }}" class="btn btn-light border">
                Reset
            </a>
        </div>
    </form>

    <div class="d-flex justify-content-between mb-3">
        <h6 class="section-title">Daftar Master Barang</h6>
        <small class="text-secondary">Total {{ $items->total() }} barang</small>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Merek/Model</th>
                    <th>Ukuran</th>
                    <th>Bahan</th>
                    <th>Satuan</th>
                    @if(Auth::user()->role === 'admin')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td>{{ $items->firstItem() + $index }}</td>
                    <td class="fw-semibold">{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->brand ?: '-' }}</td>
                    <td>{{ $item->size ?: '-' }}</td>
                    <td>{{ $item->material ?: '-' }}</td>
                    <td>{{ $item->unit }}</td>

                    @if(Auth::user()->role === 'admin')
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('items.edit', $item->id) }}"
                                class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('items.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->role === 'admin' ? 9 : 8 }}"
                        class="text-center text-secondary py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada data barang.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $items->links() }}
    </div>

</div>

@endsection