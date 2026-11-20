@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('breadcrumb', 'Master Data / Kategori / Edit')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Edit Kategori Barang</h4>
    <p class="page-subtitle">Perbarui informasi kategori inventaris.</p>
</div>

<div class="card p-4">
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $category->name) }}">

            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="description" rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>

            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>

            <a href="{{ route('categories.index') }}" class="btn btn-light border">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection