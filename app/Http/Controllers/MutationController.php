<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use App\Models\Room;
use App\Models\RoomInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutationController extends Controller
{
    private function checkAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }

    private function checkPimpinan(): void
    {
        abort_unless(Auth::user()?->role === 'pimpinan', 403);
    }

    // Daftar mutasi
    public function index()
    {
        $mutations = Mutation::with([
            'roomInventory.item',
            'fromRoom',
            'toRoom',
            'creator'
        ])
            ->latest()
            ->paginate(10);

        return view('mutations.index', compact('mutations'));
    }

    // Form pengajuan mutasi
    public function create()
    {
        $this->checkAdmin();

        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('mutations.create', compact('rooms'));
    }

    // Menampilkan pilihan barang berdasarkan ruangan asal
    public function getInventories(Room $room)
    {
        $this->checkAdmin();

        $inventories = RoomInventory::with('item')
            ->where('room_id', $room->id)
            ->get();

        return response()->json($inventories);
    }

    // Simpan pengajuan mutasi
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'room_inventory_id' => 'required|exists:room_inventories,id',
            'from_room_id' => 'required|exists:rooms,id',
            'to_room_id' => 'required|exists:rooms,id|different:from_room_id',
            'mutation_date' => 'required|date',
            'quantity_good' => 'required|integer|min:0',
            'quantity_minor' => 'required|integer|min:0',
            'quantity_severe' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if (
            $validated['quantity_good'] == 0 &&
            $validated['quantity_minor'] == 0 &&
            $validated['quantity_severe'] == 0
        ) {
            return back()->withInput()->withErrors([
                'quantity_good' => 'Jumlah mutasi harus lebih dari 0.'
            ]);
        }

        $inventory = RoomInventory::findOrFail(
            $validated['room_inventory_id']
        );

        if ($validated['from_room_id'] == $validated['to_room_id']) {
            return back()->withInput()->withErrors([
                'to_room_id' => 'Ruangan tujuan harus berbeda dari ruangan asal.'
            ]);
        }

        foreach (
            [
                'quantity_good',
                'quantity_minor',
                'quantity_severe'
            ] as $field
        ) {
            if ($validated[$field] > $inventory->$field) {
                return back()->withInput()->withErrors([
                    $field => 'Jumlah mutasi melebihi stok yang tersedia.'
                ]);
            }
        }

        Mutation::create([
            ...$validated,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('mutations.index')
            ->with('success', 'Pengajuan mutasi berhasil dibuat dan menunggu persetujuan Pimpinan.');
    }

    // Persetujuan mutasi oleh Pimpinan
    public function approve(Mutation $mutation)
    {
        $this->checkPimpinan();

        try {
            DB::transaction(function () use ($mutation) {

                $mutation = Mutation::whereKey($mutation->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($mutation->status !== 'pending') {
                    throw new \RuntimeException(
                        'Pengajuan ini sudah diproses sebelumnya.'
                    );
                }

                $source = RoomInventory::whereKey($mutation->room_inventory_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                foreach (
                    [
                        'quantity_good',
                        'quantity_minor',
                        'quantity_severe'
                    ] as $field
                ) {
                    if ($source->$field < $mutation->$field) {
                        throw new \RuntimeException(
                            'Stok ruangan asal tidak mencukupi.'
                        );
                    }
                }

                $source->decrement('quantity_good', $mutation->quantity_good);
                $source->decrement('quantity_minor', $mutation->quantity_minor);
                $source->decrement('quantity_severe', $mutation->quantity_severe);

                $destination = RoomInventory::where('room_id', $mutation->to_room_id)
                    ->where('item_id', $source->item_id)
                    ->lockForUpdate()
                    ->first();

                if (!$destination) {
                    $destination = RoomInventory::create([
                        'room_id' => $mutation->to_room_id,
                        'item_id' => $source->item_id,
                        'acquisition_year' => $source->acquisition_year,
                        'quantity_good' => 0,
                        'quantity_minor' => 0,
                        'quantity_severe' => 0,
                    ]);
                }

                $destination->increment('quantity_good', $mutation->quantity_good);
                $destination->increment('quantity_minor', $mutation->quantity_minor);
                $destination->increment('quantity_severe', $mutation->quantity_severe);

                $mutation->update([
                    'status' => 'approved',
                ]);
            });
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('mutations.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('mutations.index')
            ->with('success', 'Mutasi berhasil disetujui dan stok telah diperbarui.');
    }

    // Penolakan mutasi oleh Pimpinan
    public function reject(Mutation $mutation)
    {
        $this->checkPimpinan();

        abort_unless($mutation->status === 'pending', 403);

        $mutation->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('mutations.index')
            ->with('success', 'Pengajuan mutasi telah ditolak.');
    }
}
