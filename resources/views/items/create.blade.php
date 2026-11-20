@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('breadcrumb', 'Master Data / Data Barang / Tambah')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Tambah Data Barang</h4>
    <p class="page-subtitle">
        Masukkan informasi barang ke master inventaris.
    </p>
</div>

<div class="card p-4">

    <form action="{{ route('items.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            {{-- KODE BARANG --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Kode Barang <span class="text-danger">*</span>
                </label>

                <input type="text"
                    name="code"
                    class="form-control @error('code') is-invalid @enderror"
                    value="{{ old('code') }}"
                    placeholder="Masukkan kode barang"
                    required>

                @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- NAMA BARANG --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Nama Barang <span class="text-danger">*</span>
                </label>

                <input type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Meja Kerja"
                    required>

                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- KATEGORI + TAMBAH KATEGORI --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Kategori <span class="text-danger">*</span>
                </label>

                <div class="input-group">
                    <select name="category_id"
                        id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required>

                        <option value="">Pilih kategori</option>

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
                <div class="text-danger small mt-1">
                    {{ $message }}
                </div>
                @enderror
            </div>

            {{-- MEREK / MODEL --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Merek/Model
                </label>

                <input type="text"
                    name="brand"
                    class="form-control"
                    value="{{ old('brand') }}"
                    placeholder="Contoh: Olympic">
            </div>

            {{-- UKURAN --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Ukuran</label>

                <input type="text"
                    name="size"
                    class="form-control"
                    value="{{ old('size') }}"
                    placeholder="Contoh: 120 x 60 cm">
            </div>

            {{-- BAHAN --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">Bahan</label>

                <input type="text"
                    name="material"
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
                    <option value="Unit"
                        {{ old('unit', 'Unit') == 'Unit' ? 'selected' : '' }}>
                        Unit
                    </option>

                    <option value="Buah"
                        {{ old('unit') == 'Buah' ? 'selected' : '' }}>
                        Buah
                    </option>

                    <option value="Set"
                        {{ old('unit') == 'Set' ? 'selected' : '' }}>
                        Set
                    </option>

                    <option value="Pasang"
                        {{ old('unit') == 'Pasang' ? 'selected' : '' }}>
                        Pasang
                    </option>
                </select>
            </div>

            {{-- SPESIFIKASI --}}
            <div class="col-12">
                <label class="form-label fw-semibold">
                    Spesifikasi / Keterangan
                </label>

                <textarea name="specification"
                    rows="3"
                    class="form-control"
                    placeholder="Keterangan tambahan barang">{{ old('specification') }}</textarea>
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Barang
            </button>

            <a href="{{ route('items.index') }}"
                class="btn btn-light border">
                Batal
            </a>
        </div>

    </form>

</div>


{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="modalKategori" tabindex="-1"
    aria-labelledby="judulModalKategori" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="judulModalKategori">
                    Tambah Kategori Baru
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <label class="form-label">
                    Nama Kategori
                </label>

                <input type="text"
                    id="namaKategori"
                    class="form-control"
                    placeholder="Contoh: Perabotan">

                <div id="errorKategori"
                    class="text-danger small mt-2">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                    class="btn btn-primary"
                    id="simpanKategori">
                    Simpan Kategori
                </button>
            </div>

        </div>
    </div>
</div>


{{-- JAVASCRIPT TAMBAH KATEGORI --}}
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
                inputNama.focus();
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

                // Tambahkan kategori baru ke dropdown
                const option = new Option(
                    result.category.name,
                    result.category.id,
                    true,
                    true
                );

                dropdown.add(option);

                // Tutup modal
                bootstrap.Modal
                    .getOrCreateInstance(modalElement)
                    .hide();

                // Bersihkan input
                inputNama.value = '';
                error.textContent = '';

            } catch (err) {

                error.textContent = err.message;

            } finally {

                tombol.disabled = false;
                tombol.textContent = 'Simpan Kategori';

            }

        });

    });
</script>

@endsection