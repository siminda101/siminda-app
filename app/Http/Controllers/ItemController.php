<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    private function checkAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);
    }

    // Menampilkan daftar barang
    public function index(Request $request)
    {
        $items = Item::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('items.index', compact('items', 'categories'));
    }

    // Form tambah barang
    public function create()
    {
        $this->checkAdmin();

        $categories = Category::orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    // Simpan barang
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:150',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'unit' => 'required|string|max:30',
            'specification' => 'nullable|string|max:500',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    // Form edit
    public function edit(Item $item)
    {
        $this->checkAdmin();

        $categories = Category::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    // Update barang
    public function update(Request $request, Item $item)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:100',
            'name' => 'required|string|max:150',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'unit' => 'required|string|max:30',
            'specification' => 'nullable|string|max:500',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    // Hapus barang
    public function destroy(Item $item)
    {
        $this->checkAdmin();

        if ($item->inventories()->exists()) {
            return back()->with('error', 'Barang sudah digunakan dalam inventaris.');
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }
}
