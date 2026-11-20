@extends('layouts.app')

@section('title', 'Edit Ruangan')
@section('breadcrumb', 'Master Data / Data Ruangan / Edit')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Edit Data Ruangan</h4>
    <p class="page-subtitle">Perbarui informasi ruangan inventaris.</p>
</div>

<div class="card p-4">

    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Kode Ruangan *</label>

                <input type="text" name="code"
                    class="form-control @error('code') is-invalid @enderror"
                    value="{{ old('code', $room->code) }}" required>

                @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Ruangan *</label>

                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $room->name) }}" required>

                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Provinsi *</label>

                <input type="text" name="province"
                    class="form-control"
                    value="{{ old('province', $room->province) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Kota/Kabupaten *</label>

                <input type="text" name="city"
                    class="form-control"
                    value="{{ old('city', $room->city) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Unit Kerja *</label>

                <input type="text" name="work_unit"
                    class="form-control"
                    value="{{ old('work_unit', $room->work_unit) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Satuan Kerja *</label>

                <input type="text" name="work_section"
                    class="form-control"
                    value="{{ old('work_section', $room->work_section) }}" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi Ruangan</label>

                <textarea name="description" rows="3"
                    class="form-control">{{ old('description', $room->description) }}</textarea>
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Update Ruangan
            </button>

            <a href="{{ route('rooms.index') }}"
                class="btn btn-light border">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection