<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'gallery_images' => 'nullable|array|max:3',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
            'stock' => 'nullable|integer|min:1',
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
            'stock.min' => 'Stok unit minimal 1.',
        ]);

        $imagePath = $validated['image_path'] ?? null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('packages', 'public');
            $imagePath = '/storage/'.$path;
        }

        $galleryImages = collect($request->file('gallery_images', []))->take(3)->map(function ($file): string {
            return '/storage/'.$file->store('packages', 'public');
        })->values()->all();

        $slug = Str::slug($validated['name']);

        Package::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => (int) ($validated['stock'] ?? 1),
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'gallery_images' => $galleryImages ?: null,
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
            'stock' => 'nullable|integer|min:1',
            'description' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_path' => 'nullable|string|max:1000',
            'gallery_images' => 'nullable|array|max:3',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ], [
            'image_file.image' => 'File harus berupa gambar (JPEG, PNG, JPG, WEBP).',
            'image_file.max' => 'Ukuran gambar maksimal 5 MB.',
            'stock.min' => 'Stok unit minimal 1.',
        ]);

        $slug = $package->slug;
        if ($validated['name'] !== $package->name) {
            $slug = Str::slug($validated['name']);
            $slugTaken = Package::where('slug', $slug)
                ->where('id', '!=', $package->id)
                ->exists();

            if ($slugTaken) {
                return back()->withInput()->withErrors([
                    'name' => 'Nama paket tersebut sudah menghasilkan slug yang dipakai produk lain. Gunakan nama yang berbeda.',
                ]);
            }
        }

        $imagePath = $package->image_path;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('packages', 'public');
            $imagePath = '/storage/'.$path;
        } elseif ($request->filled('image_path')) {
            $imagePath = $validated['image_path'];
        }

        $galleryImages = $package->gallery_images ?? [];
        foreach (collect($request->file('gallery_images', []))->take(3) as $file) {
            $galleryImages[] = '/storage/'.$file->store('packages', 'public');
        }
        $galleryImages = array_slice($galleryImages, 0, 3);

        $package->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => (int) ($validated['stock'] ?? 1),
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'gallery_images' => $galleryImages ?: null,
            'items' => array_values(array_filter($validated['items'])),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket layanan "'.$package->name.'" berhasil diperbarui.');
    }

    /**
     * Hapus foto produk tanpa menghapus paketnya.
     */
    public function destroyImage(int $id): RedirectResponse
    {
        $package = Package::findOrFail($id);
        $imagePath = $package->image_path;

        if ($imagePath && Str::startsWith($imagePath, '/storage/')) {
            Storage::disk('public')->delete(Str::after($imagePath, '/storage/'));
        }

        $package->update(['image_path' => null]);

        return back()->with('success', 'Foto produk berhasil dihapus.');
    }

    public function destroyGalleryImage(int $package, int $index): RedirectResponse
    {
        $product = Package::findOrFail($package);
        $galleryImages = $product->gallery_images ?? [];

        if (! array_key_exists($index, $galleryImages)) {
            return back()->with('error', 'Gambar galeri tidak ditemukan.');
        }

        $imagePath = $galleryImages[$index];
        if (Str::startsWith($imagePath, '/storage/')) {
            Storage::disk('public')->delete(Str::after($imagePath, '/storage/'));
        }

        array_splice($galleryImages, $index, 1);
        $product->update(['gallery_images' => array_values($galleryImages) ?: null]);

        return back()->with('success', 'Gambar galeri berhasil dihapus.');
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
    public function toggleActive(Request $request, int $id): RedirectResponse
    {
        $package = Package::findOrFail($id);
        $package->is_active = $request->has('is_active')
            ? $request->boolean('is_active')
            : ! $package->is_active;
        $package->save();

        $statusStr = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status paket \"{$package->name}\" berhasil {$statusStr}.");
    }
}
