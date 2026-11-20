@extends('layouts.app')

@section('title', 'Edit Inventaris')
@section('breadcrumb', 'Inventaris / Edit Barang')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Edit Inventaris Barang</h4>
    <p class="page-subtitle">
        Perbarui identitas, tahun perolehan, dan kondisi barang.
    </p>
</div>

<div class="card p-4">

    <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>
        Ruangan:
        <strong>{{ $roomInventory->room->name }}</strong>.
        Untuk memindahkan barang, gunakan fitur Mutasi Barang.
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Periksa kembali data berikut:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('inventories.update', $roomInventory->id) }}"
        method="POST">
        @csrf
        @method('PUT')

        <h6 class="fw-bold mb-3">A. Identitas Barang</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Kode Barang *</label>
                <input type="text" name="code"
                    class="form-control"
                    value="{{ old('code', $roomInventory->item->code) }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nama Barang *</label>
                <input type="text" name="name"
                    class="form-control"
                    value="{{ old('name', $roomInventory->item->name) }}"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kategori *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $roomInventory->item->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Merek / Model</label>
                <input type="text" name="brand"
                    class="form-control"
                    value="{{ old('brand', $roomInventory->item->brand) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ukuran</label>
                <input type="text" name="size"
                    class="form-control"
                    value="{{ old('size', $roomInventory->item->size) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Bahan</label>
                <input type="text" name="material"
                    class="form-control"
                    value="{{ old('material', $roomInventory->item->material) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Satuan *</label>
                <select name="unit" class="form-select" required>
                    @foreach(['Unit', 'Buah', 'Set', 'Pasang'] as $unit)
                    <option value="{{ $unit }}"
                        {{ old('unit', $roomInventory->item->unit) == $unit ? 'selected' : '' }}>
                        {{ $unit }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <input type="text" name="acquisition_year"
                    class="form-control"
                    inputmode="numeric"
                    pattern="[0-9]{4}"
                    maxlength="4"
                    value="{{ old('acquisition_year', $roomInventory->acquisition_year) }}"
                    placeholder="Contoh: 2024">
            </div>

            <div class="col-12">
                <label class="form-label">Spesifikasi / Keterangan Barang</label>
                <textarea name="specification" rows="3"
                    class="form-control">{{ old('specification', $roomInventory->item->specification) }}</textarea>
            </div>

            <h6 class="fw-bold mb-3">B. Jumlah dan Kondisi Barang</h6>

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Kondisi Baik *</label>
                    <input type="text"
                        name="quantity_good"
                        class="form-control jumlah-barang"
                        inputmode="numeric"
                        pattern="[0-9]+"
                        value="{{ old('quantity_good', $roomInventory->quantity_good) }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rusak Ringan *</label>
                    <input type="text"
                        name="quantity_minor"
                        class="form-control jumlah-barang"
                        inputmode="numeric"
                        pattern="[0-9]+"
                        value="{{ old('quantity_minor', $roomInventory->quantity_minor) }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rusak Berat *</label>
                    <input type="text"
                        name="quantity_severe"
                        class="form-control jumlah-barang"
                        inputmode="numeric"
                        pattern="[0-9]+"
                        value="{{ old('quantity_severe', $roomInventory->quantity_severe) }}"
                        required>
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan Mutasi / Catatan</label>
                    <textarea name="mutation_notes" rows="3"
                        class="form-control">{{ old('mutation_notes', $roomInventory->mutation_notes) }}</textarea>
                </div>

            </div>


        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Simpan Perubahan
            </button>

            <a href="{{ route('inventories.room', $roomInventory->room_id) }}"
                class="btn btn-light border">
                Batal
            </a>
        </div>

    </form>
</div>

<script>
    document.querySelectorAll('input[type="number"]').forEach(function(input) {
        input.addEventListener('wheel', function(event) {
            event.preventDefault();
        }, {
            passive: false
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.jumlah-barang').forEach(function(input) {

            input.addEventListener('focus', function() {
                if (this.value === '0') {
                    this.select();
                }
            });

        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.jumlah-barang').forEach(function(input) {

            input.addEventListener('focus', function() {
                if (this.value === '0') {
                    this.select();
                }
            });

        });

    });
</script>

@endsection