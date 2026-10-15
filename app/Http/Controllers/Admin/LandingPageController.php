<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function edit(): View
    {
        return view('admin.landing-page.edit', [
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'about_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ], [
            'hero_image.image' => 'Gambar hero harus berupa JPEG, PNG, atau WEBP.',
            'hero_image.max' => 'Ukuran gambar hero maksimal 5 MB.',
            'about_image.image' => 'Gambar Tentang Kami harus berupa JPEG, PNG, atau WEBP.',
            'about_image.max' => 'Ukuran gambar Tentang Kami maksimal 5 MB.',
        ]);

        $siteSetting = SiteSetting::query()->firstOrCreate([]);
        $imageFields = [
            'hero_image' => 'hero_image_path',
            'about_image' => 'about_image_path',
        ];

        foreach ($imageFields as $inputName => $column) {
            if (isset($validated[$inputName])) {
                $siteSetting->{$column} = '/storage/'.$validated[$inputName]->store('landing-page', 'public');
            }
        }

        $siteSetting->save();

        return redirect()->route('admin.landing-page.edit')
            ->with('success', 'Gambar landing page berhasil diperbarui.');
    }

    public function destroy(string $type): RedirectResponse
    {
        $imageFields = [
            'hero' => 'hero_image_path',
            'about' => 'about_image_path',
        ];

        abort_unless(array_key_exists($type, $imageFields), 404);

        $siteSetting = SiteSetting::query()->first();
        if (! $siteSetting) {
            return redirect()->route('admin.landing-page.edit');
        }

        $column = $imageFields[$type];
        $imagePath = $siteSetting->{$column};

        if ($imagePath && Str::startsWith($imagePath, '/storage/')) {
            Storage::disk('public')->delete(Str::after($imagePath, '/storage/'));
        }

        $siteSetting->update([$column => null]);

        return redirect()->route('admin.landing-page.edit')
            ->with('success', 'Gambar '.($type === 'hero' ? 'hero' : 'Tentang Kami').' berhasil dihapus.');
    }
}
