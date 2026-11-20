@extends('layouts.app')

@section('title', 'Tambah Inventaris')
@section('breadcrumb', 'Inventaris / Tambah Barang')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Tambah Inventaris Ruangan</h4>
    <p class="page-subtitle">
        Masukkan identitas barang dan jumlahnya dalam satu form.
    </p>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card p-4">

    <form action="{{ route('inventories.store') }}" method="POST">
        @csrf

        <h6 class="fw-bold mb-3">A. Informasi Ruangan dan Barang</h6>

        <div class="row g-3">

            {{-- RUANGAN --}}
            <select name="room_id" class="form-select" required>
                <option value="">Pilih Ruangan</option>

                @foreach($rooms as $room)
                <option value="{{ $room->id }}"
                    {{ old('room_id', $selectedRoom) == $room->id ? 'selected' : '' }}>
                    {{ $room->name }}
                </option>
                @endforeach
            </select>

            @error('room_id')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- KODE BARANG --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Kode Barang <span class="text-danger">*</span>
            </label>

            <input type="text" name="code"
                class="form-control"
                value="{{ old('code') }}"
                placeholder="Contoh: 02.06.01" required>

            @error('code')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- NAMA BARANG --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Nama Barang <span class="text-danger">*</span>
            </label>

            <input type="text" name="name"
                class="form-control"
                value="{{ old('name') }}"
                placeholder="Contoh: Meja Kerja" required>

            @error('name')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- KATEGORI --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Kategori <span class="text-danger">*</span>
            </label>

            <div class="input-group">
                <select name="category_id" id="category_id"
                    class="form-select" required>

                    <option value="">Pilih Kategori</option>

                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                <button type="button"
                    class="btn btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalKategori">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>

            @error('category_id')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- MEREK --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">Merek / Model</label>

            <input type="text" name="brand"
                class="form-control"
                value="{{ old('brand') }}"
                placeholder="Contoh: Olympic">
        </div>

        {{-- UKURAN --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ukuran</label>

            <input type="text" name="size"
                class="form-control"
                value="{{ old('size') }}"
                placeholder="Contoh: 120 x 60 cm">
        </div>

        {{-- BAHAN --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">Bahan</label>

            <input type="text" name="material"
                class="form-control"
                value="{{ old('material') }}"
                placeholder="Contoh: Kayu">
        </div>

        {{-- SATUAN --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Satuan <span class="text-danger">*</span>
            </label>

            <select name="unit" class="form-select" required>
                <option value="Unit" {{ old('unit', 'Unit') == 'Unit' ? 'selected' : '' }}>Unit</option>
                <option value="Buah" {{ old('unit') == 'Buah' ? 'selected' : '' }}>Buah</option>
                <option value="Set" {{ old('unit') == 'Set' ? 'selected' : '' }}>Set</option>
                <option value="Pasang" {{ old('unit') == 'Pasang' ? 'selected' : '' }}>Pasang</option>
            </select>
        </div>

        {{-- TAHUN PEROLEHAN --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                Tahun Pembuatan / Pembelian
            </label>

            <input type="number" name="acquisition_year"
                class="form-control"
                value="{{ old('acquisition_year') }}"
                min="1900" max="{{ date('Y') + 1 }}"
                placeholder="Contoh: 2024">
        </div>

        {{-- SPESIFIKASI --}}
        <div class="col-12">
            <label class="form-label fw-semibold">
                Spesifikasi / Keterangan Barang
            </label>

            <textarea name="specification" rows="3"
                class="form-control"
                placeholder="Masukkan spesifikasi atau keterangan barang">{{ old('specification') }}</textarea>
        </div>

</div>

<hr class="my-4">

<h6 class="fw-bold mb-3">B. Jumlah dan Kondisi Barang</h6>

<div class="row g-3">

    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Kondisi Baik
        </label>

        <input type="text"
            name="quantity_good"
            class="form-control jumlah-barang"
            inputmode="numeric"
            pattern="[0-9]+"
            value="{{ old('quantity_good', 0) }}"
            required>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Rusak Ringan
        </label>

        <input type="text"
            name="quantity_minor"
            class="form-control jumlah-barang"
            inputmode="numeric"
            pattern="[0-9]+"
            value="{{ old('quantity_minor', 0) }}"
            required>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Rusak Berat
        </label>

        <input type="text"
            name="quantity_severe"
            class="form-control jumlah-barang"
            inputmode="numeric"
            pattern="[0-9]+"
            value="{{ old('quantity_severe', 0) }}"
            required>
        @error('quantity_good')
        <div class="col-12 text-danger small">{{ $message }}</div>
        @enderror

    </div>

    <div class="mt-3">
        <label class="form-label fw-semibold">
            Keterangan Mutasi / Catatan
        </label>

        <textarea name="mutation_notes" rows="2"
            class="form-control"
            placeholder="Catatan tambahan jika ada">{{ old('mutation_notes') }}</textarea>
    </div>

    <hr class="my-4">

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Simpan Inventaris
        </button>

        <a href="{{ $selectedRoom
    ? route('inventories.room', $selectedRoom)
    : route('inventories.index') }}"
            class="btn btn-light border">
            Batal
        </a>
    </div>

    </form>
</div>


{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label">Nama Kategori</label>

                <input type="text" id="namaKategori"
                    class="form-control"
                    placeholder="Contoh: Perabotan">

                <div id="errorKategori"
                    class="text-danger small mt-2"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Batal</button>

                <button type="button" class="btn btn-primary"
                    id="simpanKategori">Simpan Kategori</button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const tombol = document.getElementById('simpanKategori');
        const inputNama = document.getElementById('namaKategori');
        const error = document.getElementById('errorKategori');
        const dropdown = document.getElementById('category_id');
        const modalElement = document.getElementById('modalKategori');

        tombol.addEventListener('click', async function() {

            const nama = inputNama.value.trim();
            error.textContent = '';

            if (!nama) {
                error.textContent = 'Nama kategori wajib diisi.';
                return;
            }

            tombol.disabled = true;
            tombol.textContent = 'Menyimpan...';

            try {
                const response = await fetch(
                    "{{ route('categories.quickStore') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: nama
                        })
                    }
                );

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(
                        result.errors?.name?.[0] ||
                        result.message ||
                        'Gagal menyimpan kategori.'
                    );
                }

                dropdown.add(new Option(
                    result.category.name,
                    result.category.id,
                    true,
                    true
                ));

                bootstrap.Modal
                    .getOrCreateInstance(modalElement)
                    .hide();

                inputNama.value = '';

            } catch (err) {
                error.textContent = err.message;
            } finally {
                tombol.disabled = false;
                tombol.textContent = 'Simpan Kategori';
            }
        });
    });
</script>

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