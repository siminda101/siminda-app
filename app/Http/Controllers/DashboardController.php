<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomInventory;
use App\Models\Mutation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik ruangan
        $totalRooms = Room::count();

        $activeRooms = Room::where('is_active', true)->count();

        $inactiveRooms = Room::where('is_active', false)->count();

        // Statistik jenis inventaris
        $totalItemTypes = RoomInventory::distinct('item_id')
            ->count('item_id');

        // Total barang berdasarkan kondisi
        $inventorySummary = RoomInventory::selectRaw('
            COALESCE(SUM(quantity_good), 0) as total_good,
            COALESCE(SUM(quantity_minor), 0) as total_minor,
            COALESCE(SUM(quantity_severe), 0) as total_severe
        ')->first();

        $totalGood = (int) $inventorySummary->total_good;
        $totalMinor = (int) $inventorySummary->total_minor;
        $totalSevere = (int) $inventorySummary->total_severe;

        $totalItems = $totalGood + $totalMinor + $totalSevere;

        // Statistik mutasi
        $totalMutations = Mutation::count();

        $pendingMutations = Mutation::where('status', 'pending')->count();

        $approvedMutations = Mutation::where('status', 'approved')->count();

        $rejectedMutations = Mutation::where('status', 'rejected')->count();

        // Inventaris per ruangan
        $roomInventories = Room::withCount('inventories')
            ->withSum('inventories as total_good', 'quantity_good')
            ->withSum('inventories as total_minor', 'quantity_minor')
            ->withSum('inventories as total_severe', 'quantity_severe')
            ->orderBy('name')
            ->get();

        // Mutasi terbaru
        $recentMutations = Mutation::with([
            'roomInventory.item',
            'fromRoom',
            'toRoom',
            'creator'
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRooms',
            'activeRooms',
            'inactiveRooms',
            'totalItemTypes',
            'totalGood',
            'totalMinor',
            'totalSevere',
            'totalItems',
            'totalMutations',
            'pendingMutations',
            'approvedMutations',
            'rejectedMutations',
            'roomInventories',
            'recentMutations'
        ));
    }
}
