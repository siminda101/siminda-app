@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('breadcrumb', 'Master Data / Data Ruangan / Tambah')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Tambah Data Ruangan</h4>
    <p class="page-subtitle">Masukkan informasi ruangan inventaris.</p>
</div>

<div class="card p-4">

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Data belum berhasil disimpan.</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Kode Ruangan *</label>
                <input type="text" name="code"
                    class="form-control"
                    value="{{ old('code') }}"
                    placeholder="Contoh: RD-001" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nama Ruangan *</label>
                <input type="text" name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Rumah Dinas Bupati" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Provinsi *</label>
                <input type="text" name="province"
                    class="form-control"
                    value="{{ old('province', 'Jawa Barat') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kota/Kabupaten *</label>
                <input type="text" name="city"
                    class="form-control"
                    value="{{ old('city', 'Kuningan') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Unit Kerja *</label>
                <input type="text" name="work_unit"
                    class="form-control"
                    value="{{ old('work_unit', 'Setda Kabupaten Kuningan') }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Satuan Kerja *</label>
                <input type="text" name="work_section"
                    class="form-control"
                    value="{{ old('work_section', 'Bagian Umum') }}"
                    required>
            </div>

            <div class="col-12">
                <label class="form-label">Deskripsi Ruangan</label>
                <textarea name="description" rows="3"
                    class="form-control"
                    placeholder="Keterangan tambahan">{{ old('description') }}</textarea>
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Simpan Ruangan
            </button>

            <a href="{{ route('rooms.index') }}"
                class="btn btn-light border">
                Batal
            </a>
        </div>

    </form>
</div>

@endsection