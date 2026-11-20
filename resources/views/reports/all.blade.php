@extends('layouts.app')

@section('title', 'Laporan Seluruh Inventaris')
@section('breadcrumb', 'Laporan / Seluruh Ruangan')

@section('content')

<style>
    .kir-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .kir-table th,
    .kir-table td {
        border: 1px solid #333;
        padding: 5px;
        text-align: center;
        vertical-align: middle;
    }

    .kir-table th {
        background: #e9ecef !important;
    }

    .room-report {
        margin-bottom: 35px;
    }

    @media print {
        @page {
            size: landscape;
            margin: 12mm;
        }

        .no-print,
        .sidebar,
        .navbar,
        .breadcrumb {
            display: none !important;
        }

        .main-content,
        .content,
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .kir-table {
            font-size: 9px;
        }

        .kir-table th {
            background: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .room-report {
            page-break-after: always;
        }

        .room-report:last-child {
            page-break-after: auto;
        }

        tr {
            page-break-inside: avoid;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="page-title">Laporan Seluruh Ruangan</h4>
        <p class="page-subtitle">Rekap inventaris Bagian Umum SETDA Kabupaten Kuningan.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Cetak / PDF
        </button>
    </div>
</div>

<div class="card p-4">

    <div class="text-center mb-4">
        <h5 class="fw-bold">REKAPITULASI KARTU INVENTARIS RUANGAN</h5>
        <h6 class="fw-bold">SEKRETARIAT DAERAH KABUPATEN KUNINGAN</h6>
    </div>

    @foreach($rooms as $room)
    <div class="room-report">

        <div class="mb-3">
            <strong>Ruangan:</strong> {{ $room->name }}<br>
            <strong>Kode:</strong> {{ $room->code }}<br>
            <strong>Unit Kerja:</strong> {{ $room->work_unit }}<br>
            <strong>Satuan Kerja:</strong> {{ $room->work_section }}
        </div>

        <table class="kir-table">
            <thead>
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Nama Barang</th>
                    <th rowspan="2">Merek / Model</th>
                    <th rowspan="2">Ukuran</th>
                    <th rowspan="2">Bahan</th>
                    <th rowspan="2">Tahun</th>
                    <th rowspan="2">Kode Barang</th>
                    <th colspan="4">Jumlah Barang</th>
                    <th rowspan="2">Keterangan Mutasi</th>
                </tr>
                <tr>
                    <th>Baik</th>
                    <th>Rusak Ringan</th>
                    <th>Rusak Berat</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($room->inventories as $index => $inventory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $inventory->item->name ?? '-' }}</td>
                    <td>{{ $inventory->item->brand ?? '-' }}</td>
                    <td>{{ $inventory->item->size ?? '-' }}</td>
                    <td>{{ $inventory->item->material ?? '-' }}</td>
                    <td>{{ $inventory->acquisition_year ?? '-' }}</td>
                    <td>{{ $inventory->item->code ?? '-' }}</td>
                    <td>{{ $inventory->quantity_good }}</td>
                    <td>{{ $inventory->quantity_minor }}</td>
                    <td>{{ $inventory->quantity_severe }}</td>
                    <td>
                        {{ $inventory->quantity_good + $inventory->quantity_minor + $inventory->quantity_severe }}
                    </td>
                    <td>{{ $inventory->mutation_notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">Belum ada inventaris.</td>
                </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr class="fw-bold">
                    <td colspan="7">TOTAL RUANGAN</td>
                    <td>{{ $room->inventories->sum('quantity_good') }}</td>
                    <td>{{ $room->inventories->sum('quantity_minor') }}</td>
                    <td>{{ $room->inventories->sum('quantity_severe') }}</td>
                    <td>
                        {{ $room->inventories->sum('quantity_good')
                                + $room->inventories->sum('quantity_minor')
                                + $room->inventories->sum('quantity_severe') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

    </div>
    @endforeach

    <div class="mt-4">
        <h6 class="fw-bold">REKAPITULASI SELURUH RUANGAN</h6>

        <table class="kir-table">
            <tr>
                <th>Total Baik</th>
                <th>Total Rusak Ringan</th>
                <th>Total Rusak Berat</th>
                <th>Total Seluruh Barang</th>
            </tr>
            <tr>
                <td>{{ $summary['good'] }}</td>
                <td>{{ $summary['minor'] }}</td>
                <td>{{ $summary['severe'] }}</td>
                <td>{{ $summary['total'] }}</td>
            </tr>
        </table>
    </div>

</div>

@endsection