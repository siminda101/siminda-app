<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    private function checkAdmin(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403, 'Anda tidak memiliki akses.');
    }

    // Menampilkan kategori
    public function index(Request $request)
    {
        $categories = Category::query()
            ->withCount('items')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories'));
    }

    // Menampilkan form tambah
    public function create()
    {
        $this->checkAdmin();

        return view('categories.create');
    }

    // Menyimpan kategori
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Category $category)
    {
        $this->checkAdmin();

        return view('categories.edit', compact('category'));
    }

    // Memperbarui kategori
    public function quickStore(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ]);
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        $this->checkAdmin();

        if ($category->items()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
