@extends('layouts.admin')

@section('title', 'Tampilan Landing — Admin Vantara')

@section('content')
<div>
  <span class="eye">Tampilan Landing</span>
  <h1 class="d" style="font-size: 34px; margin-top: 8px">Gambar Landing Page</h1>
  <p style="color: var(--admin-mu); margin-top: 8px">Ubah gambar pada bagian Hero dan Tentang Kami. Gambar produk tetap dikelola dari menu Produk.</p>
</div>

<form action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data" class="card" style="display: flex; flex-direction: column; gap: 28px; max-width: 920px">
  @csrf
  @method('PUT')

  <div class="fld">
    <label for="hero_image">Gambar Hero</label>
    @if($siteSetting?->hero_image_path)
      <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px">
        <img src="{{ $siteSetting->hero_image_path }}" alt="Gambar hero saat ini" style="width: 100%; max-width: 520px; height: 220px; object-fit: cover; border: 1px solid var(--admin-line); border-radius: 14px">
        <button class="btn dg sm" type="submit" form="delete-hero-image" onclick="return confirm('Hapus gambar hero?')">Hapus Gambar</button>
      </div>
    @else
      <span style="color: var(--admin-mu); font-size: 14px">Belum ada gambar; ikon bawaan akan tetap ditampilkan.</span>
    @endif
    <input id="hero_image" name="hero_image" type="file" accept="image/jpeg,image/png,image/webp">
    <span style="color: var(--admin-mu); font-size: 13px">JPEG, PNG, atau WEBP. Maksimal 5 MB.</span>
    @error('hero_image')<span style="color: #B91C1C; font-size: 13px">{{ $message }}</span>@enderror
  </div>

  <div class="fld">
    <label for="about_image">Gambar Tentang Kami</label>
    @if($siteSetting?->about_image_path)
      <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px">
        <img src="{{ $siteSetting->about_image_path }}" alt="Gambar Tentang Kami saat ini" style="width: 100%; max-width: 520px; height: 220px; object-fit: cover; border: 1px solid var(--admin-line); border-radius: 14px">
        <button class="btn dg sm" type="submit" form="delete-about-image" onclick="return confirm('Hapus gambar Tentang Kami?')">Hapus Gambar</button>
      </div>
    @else
      <span style="color: var(--admin-mu); font-size: 14px">Belum ada gambar; ikon bawaan akan tetap ditampilkan.</span>
    @endif
    <input id="about_image" name="about_image" type="file" accept="image/jpeg,image/png,image/webp">
    <span style="color: var(--admin-mu); font-size: 13px">JPEG, PNG, atau WEBP. Maksimal 5 MB.</span>
    @error('about_image')<span style="color: #B91C1C; font-size: 13px">{{ $message }}</span>@enderror
  </div>

  <div>
    <button class="btn p" type="submit">Simpan Gambar</button>
  </div>
</form>

<form id="delete-hero-image" action="{{ route('admin.landing-page.destroy', ['type' => 'hero']) }}" method="POST" style="display: none">
  @csrf
  @method('DELETE')
</form>
<form id="delete-about-image" action="{{ route('admin.landing-page.destroy', ['type' => 'about']) }}" method="POST" style="display: none">
  @csrf
  @method('DELETE')
</form>
@endsection
