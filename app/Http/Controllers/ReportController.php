<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomInventory;

class ReportController extends Controller
{
    // Halaman daftar laporan
    public function index()
    {
        $rooms = Room::withCount('inventories')
            ->orderBy('name')
            ->get();

        return view('reports.index', compact('rooms'));
    }

    // Cetak laporan satu ruangan
    public function room(Room $room)
    {
        $inventories = RoomInventory::with('item')
            ->where('room_id', $room->id)
            ->orderBy('id')
            ->get();

        $summary = [
            'good' => $inventories->sum('quantity_good'),
            'minor' => $inventories->sum('quantity_minor'),
            'severe' => $inventories->sum('quantity_severe'),
        ];

        $summary['total'] = $summary['good']
            + $summary['minor']
            + $summary['severe'];

        return view('reports.room', compact(
            'room',
            'inventories',
            'summary'
        ));
    }

    // Cetak laporan seluruh ruangan
    public function all()
    {
        $rooms = Room::with('inventories.item')
            ->orderBy('name')
            ->get();

        $summary = [
            'good' => 0,
            'minor' => 0,
            'severe' => 0,
        ];

        foreach ($rooms as $room) {
            $summary['good'] += $room->inventories->sum('quantity_good');
            $summary['minor'] += $room->inventories->sum('quantity_minor');
            $summary['severe'] += $room->inventories->sum('quantity_severe');
        }

        $summary['total'] = $summary['good']
            + $summary['minor']
            + $summary['severe'];

        return view('reports.all', compact(
            'rooms',
            'summary'
        ));
    }
}
