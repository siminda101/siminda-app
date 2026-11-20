@extends('layouts.app')

@section('title', 'Pengajuan Mutasi')
@section('breadcrumb', 'Mutasi Barang / Pengajuan')

@section('content')

<div class="mb-4">
    <h4 class="page-title">Pengajuan Mutasi Barang</h4>
    <p class="page-subtitle">
        Ajukan pemindahan barang dari satu ruangan ke ruangan lainnya.
    </p>
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

<div class="card p-4">

    <form action="{{ route('mutations.store') }}" method="POST">
        @csrf

        <h6 class="fw-bold mb-3">A. Informasi Mutasi</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Ruangan Asal *
                </label>

                <select name="from_room_id" id="from_room_id"
                    class="form-select" required>
                    <option value="">Pilih Ruangan Asal</option>

                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                        {{ old('from_room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Ruangan Tujuan *
                </label>

                <select name="to_room_id" id="to_room_id"
                    class="form-select" required>
                    <option value="">Pilih Ruangan Tujuan</option>

                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                        {{ old('to_room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Tanggal Mutasi *
                </label>

                <input type="date" name="mutation_date"
                    class="form-control"
                    value="{{ old('mutation_date', date('Y-m-d')) }}"
                    required>
            </div>

        </div>

        <hr class="my-4">

        <h6 class="fw-bold mb-3">B. Pilih Barang</h6>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Barang yang Dimutasi *
            </label>

            <select name="room_inventory_id" id="room_inventory_id"
                class="form-select" required disabled>
                <option value="">Pilih ruangan asal terlebih dahulu</option>
            </select>

            @error('room_inventory_id')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div id="stockInfo" class="alert alert-info d-none">
            <strong>Stok tersedia:</strong>
            <div class="row mt-2">
                <div class="col-md-4">
                    Baik: <strong id="stockGood">0</strong>
                </div>
                <div class="col-md-4">
                    Rusak Ringan: <strong id="stockMinor">0</strong>
                </div>
                <div class="col-md-4">
                    Rusak Berat: <strong id="stockSevere">0</strong>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <h6 class="fw-bold mb-3">C. Jumlah Barang yang Dimutasi</h6>

        <p class="text-muted small">
            Masukkan jumlah barang yang akan dipindahkan berdasarkan kondisi.
            Jumlah tidak boleh melebihi stok tersedia.
        </p>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label fw-semibold">Kondisi Baik *</label>
                <input type="text" inputmode="numeric"
                    name="quantity_good" id="quantity_good"
                    class="form-control jumlah-mutasi"
                    value="{{ old('quantity_good', 0) }}"
                    pattern="[0-9]+" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Rusak Ringan *</label>
                <input type="text" inputmode="numeric"
                    name="quantity_minor" id="quantity_minor"
                    class="form-control jumlah-mutasi"
                    value="{{ old('quantity_minor', 0) }}"
                    pattern="[0-9]+" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Rusak Berat *</label>
                <input type="text" inputmode="numeric"
                    name="quantity_severe" id="quantity_severe"
                    class="form-control jumlah-mutasi"
                    value="{{ old('quantity_severe', 0) }}"
                    pattern="[0-9]+" required>
            </div>

        </div>

        <div id="quantityError" class="text-danger small mt-2"></div>

        <div class="mt-3">
            <label class="form-label fw-semibold">
                Keterangan Mutasi
            </label>

            <textarea name="notes" rows="3"
                class="form-control"
                placeholder="Contoh: Pemindahan barang karena penataan ulang ruangan.">{{ old('notes') }}</textarea>
        </div>

        <div class="alert alert-warning mt-4">
            <i class="bi bi-info-circle me-1"></i>
            Pengajuan ini akan menunggu persetujuan Pimpinan.
            Stok barang belum berubah sebelum pengajuan disetujui.
        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"
                id="submitMutation">
                <i class="bi bi-send me-1"></i>
                Kirim Pengajuan
            </button>

            <a href="{{ route('mutations.index') }}"
                class="btn btn-light border">
                Batal
            </a>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const fromRoom = document.getElementById('from_room_id');
        const toRoom = document.getElementById('to_room_id');
        const itemSelect = document.getElementById('room_inventory_id');

        const stockInfo = document.getElementById('stockInfo');
        const stockGood = document.getElementById('stockGood');
        const stockMinor = document.getElementById('stockMinor');
        const stockSevere = document.getElementById('stockSevere');

        const quantityGood = document.getElementById('quantity_good');
        const quantityMinor = document.getElementById('quantity_minor');
        const quantitySevere = document.getElementById('quantity_severe');

        const quantityError = document.getElementById('quantityError');
        const submitButton = document.getElementById('submitMutation');

        let available = {
            good: 0,
            minor: 0,
            severe: 0
        };

        async function loadInventories() {

            itemSelect.innerHTML = '<option value="">Memuat barang...</option>';
            itemSelect.disabled = true;
            stockInfo.classList.add('d-none');

            quantityGood.value = 0;
            quantityMinor.value = 0;
            quantitySevere.value = 0;

            if (!fromRoom.value) {
                itemSelect.innerHTML =
                    '<option value="">Pilih ruangan asal terlebih dahulu</option>';
                return;
            }

            try {
                const url = "{{ route('mutations.inventories', ':id') }}"
                    .replace(':id', fromRoom.value);

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data barang.');
                }

                const inventories = await response.json();

                itemSelect.innerHTML =
                    '<option value="">Pilih Barang</option>';

                if (inventories.length === 0) {
                    itemSelect.innerHTML =
                        '<option value="">Tidak ada barang di ruangan ini</option>';
                }

                inventories.forEach(function(inventory) {

                    const item = inventory.item;

                    if (!item) return;

                    const option = new Option(
                        item.code + ' - ' + item.name,
                        inventory.id
                    );

                    option.dataset.good = inventory.quantity_good;
                    option.dataset.minor = inventory.quantity_minor;
                    option.dataset.severe = inventory.quantity_severe;

                    itemSelect.add(option);
                });

                itemSelect.disabled = false;

            } catch (error) {
                itemSelect.innerHTML =
                    '<option value="">Gagal memuat barang</option>';

                alert(error.message);
            }
        }

        function updateStock() {

            const option = itemSelect.selectedOptions[0];

            if (!option || !option.value) {
                stockInfo.classList.add('d-none');
                return;
            }

            available.good = Number(option.dataset.good || 0);
            available.minor = Number(option.dataset.minor || 0);
            available.severe = Number(option.dataset.severe || 0);

            stockGood.textContent = available.good;
            stockMinor.textContent = available.minor;
            stockSevere.textContent = available.severe;

            stockInfo.classList.remove('d-none');

            quantityGood.value = 0;
            quantityMinor.value = 0;
            quantitySevere.value = 0;

            validateQuantities();
        }

        function validateQuantities() {

            const good = Number(quantityGood.value || 0);
            const minor = Number(quantityMinor.value || 0);
            const severe = Number(quantitySevere.value || 0);

            let message = '';

            if (!/^\d+$/.test(quantityGood.value) ||
                !/^\d+$/.test(quantityMinor.value) ||
                !/^\d+$/.test(quantitySevere.value)) {
                message = 'Jumlah harus berupa bilangan bulat tidak negatif.';
            } else if (
                good > available.good ||
                minor > available.minor ||
                severe > available.severe
            ) {
                message = 'Jumlah mutasi melebihi stok yang tersedia.';
            } else if (good + minor + severe === 0) {
                message = 'Minimal satu barang harus dimutasi.';
            }

            quantityError.textContent = message;
            submitButton.disabled = Boolean(message);

            return !message;
        }

        fromRoom.addEventListener('change', loadInventories);

        toRoom.addEventListener('change', function() {
            if (fromRoom.value && fromRoom.value === toRoom.value) {
                alert('Ruangan tujuan harus berbeda dari ruangan asal.');
                toRoom.value = '';
            }
        });

        itemSelect.addEventListener('change', updateStock);

        [quantityGood, quantityMinor, quantitySevere].forEach(function(input) {

            input.addEventListener('focus', function() {
                if (this.value === '0') {
                    this.select();
                }
            });

            input.addEventListener('input', validateQuantities);
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            if (!validateQuantities()) {
                event.preventDefault();
            }

            if (!fromRoom.value || fromRoom.value === toRoom.value) {
                event.preventDefault();
                alert('Pilih ruangan asal dan tujuan yang berbeda.');
            }
        });

        if (fromRoom.value) {
            loadInventories();
        }
    });
</script>

@endsection