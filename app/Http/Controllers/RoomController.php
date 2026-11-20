<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    private function checkAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }

    // Menampilkan daftar ruangan
    public function index(Request $request)
    {
        $rooms = Room::withCount('inventories')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('rooms.index', compact('rooms'));
    }
    // Form tambah ruangan
    public function create()
    {
        $this->checkAdmin();

        return view('rooms.create');
    }

    // Simpan ruangan
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:rooms,code',
            'name' => 'required|string|max:150',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'work_unit' => 'required|string|max:150',
            'work_section' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ]);

        Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Data ruangan berhasil ditambahkan.');
    }

    // Form edit ruangan
    public function edit(Room $room)
    {
        $this->checkAdmin();

        return view('rooms.edit', compact('room'));
    }

    // Update ruangan
    public function update(Request $request, Room $room)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'code')->ignore($room->id),
            ],
            'name' => 'required|string|max:150',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'work_unit' => 'required|string|max:150',
            'work_section' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ]);

        $room->update($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    // Mengubah status aktif/nonaktif ruangan
    public function toggleStatus(Room $room)
    {
        $this->checkAdmin();

        $room->update([
            'is_active' => !$room->is_active,
        ]);

        $status = $room->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('rooms.index')
            ->with('success', "Ruangan berhasil {$status}.");
    }
}
