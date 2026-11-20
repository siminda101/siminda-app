@extends('layouts.app')

@section('title', 'Edit Barang')
@section('breadcrumb', 'Master Data / Data Barang / Edit')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Edit Data Barang</h4>
    <p class="page-subtitle">Perbarui informasi master barang.</p>
</div>

<div class="card p-4">

    <form action="{{ route('items.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Kode Barang *</label>
                <input type="text" name="code" class="form-control"
                    value="{{ old('code', $item->code) }}" required>
                @error('code')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Barang *</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $item->name) }}" required>
                @error('name')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Kategori *</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Merek/Model</label>
                <input type="text" name="brand" class="form-control"
                    value="{{ old('brand', $item->brand) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Ukuran</label>
                <input type="text" name="size" class="form-control"
                    value="{{ old('size', $item->size) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Bahan</label>
                <input type="text" name="material" class="form-control"
                    value="{{ old('material', $item->material) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Satuan *</label>
                <input type="text" name="unit" class="form-control"
                    value="{{ old('unit', $item->unit) }}" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Spesifikasi / Keterangan</label>
                <textarea name="specification" rows="3"
                    class="form-control">{{ old('specification', $item->specification) }}</textarea>
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>

            <a href="{{ route('dashboard') }}" class="btn btn-light border">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection