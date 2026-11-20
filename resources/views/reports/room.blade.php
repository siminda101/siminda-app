@extends('layouts.app')

@section('title', 'Cetak KIR - ' . $room->name)
@section('breadcrumb', 'Laporan / Cetak Ruangan')

@section('content')

<style>
    .kir-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    .kir-table th,
    .kir-table td {
        border: 1px solid #333;
        padding: 6px;
        text-align: center;
        vertical-align: middle;
    }

    .kir-table th {
        background: #e9ecef !important;
        font-weight: bold;
    }

    .kir-table .text-left {
        text-align: left;
    }

    .report-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .report-info {
        margin-bottom: 15px;
        font-size: 13px;
    }

    @media print {
        @page {
            size: landscape;
            margin: 12mm;
        }

        body {
            background: white !important;
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

        tr {
            page-break-inside: avoid;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="page-title">Laporan KIR Ruangan</h4>
        <p class="page-subtitle">{{ $room->name }}</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}"
            class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i>
            Cetak / PDF
        </button>
    </div>
</div>

<div class="card p-4">

    <div class="report-header">
        <h5 class="fw-bold mb-1">KARTU INVENTARIS RUANGAN</h5>
        <h6 class="fw-bold">SEKRETARIAT DAERAH KABUPATEN KUNINGAN</h6>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td width="150">Provinsi</td>
                <td>: {{ $room->province ?? 'Jawa Barat' }}</td>
            </tr>
            <tr>
                <td>Kabupaten/Kota</td>
                <td>: {{ $room->city ?? 'Kuningan' }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>: {{ $room->work_unit ?? 'Setda Kabupaten Kuningan' }}</td>
            </tr>
            <tr>
                <td>Satuan Kerja</td>
                <td>: {{ $room->work_section ?? 'Bagian Umum' }}</td>
            </tr>
            <tr>
                <td>Ruangan</td>
                <td>: {{ $room->name }}</td>
            </tr>
        </table>
    </div>

    <div class="table-responsive">
        <table class="kir-table">
            <thead>
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Nama Barang</th>
                    <th rowspan="2">Merek / Model</th>
                    <th rowspan="2">Ukuran</th>
                    <th rowspan="2">Bahan</th>
                    <th rowspan="2">Tahun Perolehan</th>
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
                @forelse($inventories as $index => $inventory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $inventory->item->name ?? '-' }}</td>
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
                    <td class="text-left">
                        {{ $inventory->mutation_notes ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">Belum ada data inventaris.</td>
                </tr>
                @endforelse
            </tbody>

            <tfoot>
                <tr class="fw-bold">
                    <td colspan="7">TOTAL</td>
                    <td>{{ $summary['good'] }}</td>
                    <td>{{ $summary['minor'] }}</td>
                    <td>{{ $summary['severe'] }}</td>
                    <td>{{ $summary['total'] }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-5 d-flex justify-content-end">
        <div class="text-center" style="width: 250px;">
            Kuningan, {{ now()->translatedFormat('d F Y') }}
            <br>
            Mengetahui,
            <br><br><br><br>
            <strong>(........................................)</strong>
            <br>
            NIP. ................................
        </div>
    </div>

</div>

@endsection