<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Room;
use App\Models\RoomInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    private function checkAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }

    // Form tambah inventaris
    public function create(Request $request)
    {
        $this->checkAdmin();

        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();
        $categories = Category::orderBy('name')->get();

        $selectedRoom = $request->query('room_id');

        return view('inventories.create', compact(
            'rooms',
            'categories',
            'selectedRoom'
        ));
    }
    // Hapus barang dari inventaris ruangan
    public function destroy(RoomInventory $roomInventory)
    {
        $this->checkAdmin();

        // Cegah penghapusan barang yang memiliki riwayat mutasi
        if (
            \App\Models\Mutation::where(
                'room_inventory_id',
                $roomInventory->id
            )->exists()
        ) {
            return back()->with(
                'error',
                'Barang tidak dapat dihapus karena memiliki riwayat mutasi.'
            );
        }

        DB::transaction(function () use ($roomInventory) {

            $item = $roomInventory->item;

            // Hapus data inventaris ruangan
            $roomInventory->delete();

            // Hapus master barang hanya jika tidak digunakan lagi
            if ($item && !$item->roomInventories()->exists()) {
                $item->delete();
            }
        });

        return redirect()
            ->route('inventories.room', $roomInventory->room_id)
            ->with('success', 'Barang berhasil dihapus dari inventaris.');
    }

    // Form edit inventaris
    public function edit(RoomInventory $roomInventory)
    {
        $this->checkAdmin();

        $roomInventory->load('item', 'room');

        $categories = Category::orderBy('name')->get();

        return view('inventories.edit', compact(
            'roomInventory',
            'categories'
        ));
    }

    // Update inventaris
    public function update(Request $request, RoomInventory $roomInventory)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:255',

            'brand' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'specification' => 'nullable|string',

            'acquisition_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),

            'quantity_good' => 'required|integer|min:0',
            'quantity_minor' => 'required|integer|min:0',
            'quantity_severe' => 'required|integer|min:0',

            'mutation_notes' => 'nullable|string',
        ]);

        if (
            $validated['quantity_good'] == 0 &&
            $validated['quantity_minor'] == 0 &&
            $validated['quantity_severe'] == 0
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity_good' => 'Jumlah barang harus lebih dari 0.',
                ]);
        }

        DB::transaction(function () use ($validated, $roomInventory) {

            // Update identitas barang
            $roomInventory->item->update([
                'category_id' => $validated['category_id'],
                'code' => $validated['code'],
                'name' => $validated['name'],
                'brand' => $validated['brand'] ?? null,
                'size' => $validated['size'] ?? null,
                'material' => $validated['material'] ?? null,
                'unit' => $validated['unit'],
                'specification' => $validated['specification'] ?? null,
            ]);

            // Update jumlah dan kondisi inventaris
            $roomInventory->update([
                'acquisition_year' => $validated['acquisition_year'] ?? null,
                'quantity_good' => $validated['quantity_good'],
                'quantity_minor' => $validated['quantity_minor'],
                'quantity_severe' => $validated['quantity_severe'],
                'mutation_notes' => $validated['mutation_notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('inventories.room', $roomInventory->room_id)
            ->with('success', 'Data inventaris berhasil diperbarui.');
    }

    // Menampilkan daftar ruangan
    public function index()
    {
        $rooms = Room::withCount('inventories')
            ->orderBy('name')
            ->get();

        return view('inventories.index', compact('rooms'));
    }

    // Menampilkan inventaris berdasarkan ruangan
    // Menampilkan inventaris berdasarkan ruangan
    public function room(Room $room)
    {
        $inventories = RoomInventory::with('item')
            ->where('room_id', $room->id)
            ->latest()
            ->paginate(10);

        $summary = RoomInventory::where('room_id', $room->id)
            ->selectRaw('
            SUM(quantity_good) as total_good,
            SUM(quantity_minor) as total_minor,
            SUM(quantity_severe) as total_severe,
            SUM(quantity_good + quantity_minor + quantity_severe) as total_all
        ')
            ->first();

        return view('inventories.room', compact(
            'room',
            'inventories',
            'summary'
        ));
    }

    // Simpan barang sekaligus inventaris ruangan
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',

            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:255',

            'brand' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'specification' => 'nullable|string',

            'acquisition_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),

            'quantity_good' => 'required|integer|min:0',
            'quantity_minor' => 'required|integer|min:0',
            'quantity_severe' => 'required|integer|min:0',

            'mutation_notes' => 'nullable|string',
        ]);


        // Minimal satu barang harus diinput
        if (
            $validated['quantity_good'] == 0 &&
            $validated['quantity_minor'] == 0 &&
            $validated['quantity_severe'] == 0
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity_good' => 'Jumlah barang harus lebih dari 0.',
                ]);
        }

        DB::transaction(function () use ($validated) {

            // Setiap input membuat master barang baru
            $item = Item::create([
                'category_id' => $validated['category_id'],
                'code' => $validated['code'],
                'name' => $validated['name'],
                'brand' => $validated['brand'] ?? null,
                'size' => $validated['size'] ?? null,
                'material' => $validated['material'] ?? null,
                'unit' => $validated['unit'],
                'specification' => $validated['specification'] ?? null,
            ]);

            // Hubungkan barang dengan ruangan
            RoomInventory::create([
                'room_id' => $validated['room_id'],
                'item_id' => $item->id,
                'acquisition_year' => $validated['acquisition_year'] ?? null,
                'quantity_good' => $validated['quantity_good'],
                'quantity_minor' => $validated['quantity_minor'],
                'quantity_severe' => $validated['quantity_severe'],
                'mutation_notes' => $validated['mutation_notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('inventories.room', $validated['room_id'])
            ->with('success', 'Barang berhasil ditambahkan ke inventaris ruangan.');
    }
}
