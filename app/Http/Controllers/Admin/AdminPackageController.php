<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPackageController extends Controller
{
    /**
     * Tampilkan daftar seluruh paket layanan.
     */
    public function index(Request $request): View
    {
        $query = Package::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $packages = $query->orderBy('category')->orderBy('name')->paginate(10);
        $categories = Package::distinct()->pluck('category')->filter();

        return view('admin.packages.index', compact('packages', 'categories'));
    }

    /**
     * Tampilkan formulir tambah paket baru.
     */
    public function create(): View
    {
        $categories = ['Sound System', 'Lighting & Special Effect', 'Videobooth 360', 'Band Wedding'];

        return view('admin.packages.create', compact('categories'));
    }

    /**
     * Simpan paket baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:packages,name',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_path' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama paket wajib diisi.',
            'name.unique' => 'Nama paket sudah ada di katalog.',
            'category.required' => 'Kategori paket wajib dipilih.',
            'price.required' => 'Harga paket wajib diisi.',
            'items.required' => 'Minimal tambahkan 1 item rincian paket.',
            'image_file.image' => 'File harus berupa gambar (JPEG, PNG, JPG, WEBP).',
            'image_file.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $imagePath = $validated['image_path'] ?? null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('packages', 'public');
            $imagePath = '/storage/'.$path;
        }

        $slug = Str::slug($validated['name']);

        Package::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'items' => array_values(array_filter($validated['items'])),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket layanan "'.$validated['name'].'" berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit paket.
     */
    public function edit(int $id): View
    {
        $package = Package::findOrFail($id);
        $categories = ['Sound System', 'Lighting & Special Effect', 'Videobooth 360', 'Band Wedding'];

        return view('admin.packages.edit', compact('package', 'categories'));
    }

    /**
     * Perbarui data paket layanan.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:packages,name,'.$package->id,
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_path' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'image_file.image' => 'File harus berupa gambar (JPEG, PNG, JPG, WEBP).',
            'image_file.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $imagePath = $package->image_path;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('packages', 'public');
            $imagePath = '/storage/'.$path;
        } elseif ($request->filled('image_path')) {
            $imagePath = $validated['image_path'];
        }

        $package->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'category' => $validated['category'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'items' => array_values(array_filter($validated['items'])),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket layanan "'.$package->name.'" berhasil diperbarui.');
    }

    /**
     * Hapus paket dari katalog.
     */
    public function destroy(int $id): RedirectResponse
    {
        $package = Package::findOrFail($id);
        $name = $package->name;
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket "'.$name.'" telah dihapus dari sistem.');
    }

    /**
     * Ubah status aktif/nonaktif paket.
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $package = Package::findOrFail($id);
        $package->is_active = ! $package->is_active;
        $package->save();

        $statusStr = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status paket \"{$package->name}\" berhasil {$statusStr}.");
    }
}
