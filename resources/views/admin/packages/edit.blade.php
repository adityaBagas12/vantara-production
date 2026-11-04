@extends('layouts.admin')

@section('title', 'Ubah Produk — ' . $package->name)

@section('content')
<div style="max-width: 800px; display: flex; flex-direction: column; gap: 24px"
     x-data="{ items: {{ json_encode(old('items', $package->items ?? [])) }} }">

  <div style="display: flex; align-items: center; justify-content: space-between">
    <div>
      <h1 class="d" style="font-size: 32px; font-weight: 800">Ubah Produk</h1>
      <p style="color: var(--admin-mu)">Perbarui informasi paket #{{ $package->id }} — {{ $package->name }}</p>
    </div>
    <a href="{{ route('admin.packages.index') }}" class="btn">← Kembali</a>
  </div>

  @if ($errors->any())
    <div style="padding: 14px 18px; border-radius: 12px; background: #FDECEC; border: 1px solid #F3B9B9; color: #8F1D1D; font-size: 14px">
      <strong>Terjadi kesalahan:</strong>
      <ul style="margin: 4px 0 0 16px; padding: 0">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="card" style="display: flex; flex-direction: column; gap: 20px">
    @csrf
    @method('PUT')

    <div class="fld">
      <label for="name">Nama produk / paket *</label>
      <input id="name" name="name" type="text" value="{{ old('name', $package->name) }}" required>
    </div>

    <div style="display: flex; gap: 16px; flex-wrap: wrap">
      <div class="fld" style="flex: 1 1 200px">
        <label for="category">Kategori *</label>
        <select id="category" name="category" required>
          <option value="">Pilih Kategori...</option>
          @foreach ($categories as $cat)
            <option value="{{ $cat }}" {{ old('category', $package->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
          <option value="__new__" {{ old('category') === '__new__' ? 'selected' : '' }}>+ Tambah kategori baru</option>
        </select>
        <input id="new_category" name="new_category" type="text" value="{{ old('new_category') }}" placeholder="Nama kategori baru" style="{{ old('category') === '__new__' ? '' : 'display: none' }}">
      </div>

      <div class="fld" style="flex: 1 1 200px">
        <label for="price">Harga sewa / hari (Rp) *</label>
        <input id="price" name="price" type="text" inputmode="numeric" value="{{ old('price', $package->price) }}" required oninput="const invalid=/\\D/.test(this.value); this.value=this.value.replace(/\\D/g,''); document.getElementById('price-error').hidden=!invalid; this.setCustomValidity(invalid ? 'Harga hanya boleh diisi angka.' : '')">
        <small id="price-error" hidden style="display: block; color: #9B1C1C; margin-top: 6px">Harga hanya boleh diisi angka.</small>
      </div>

      <div class="fld" style="flex: 1 1 140px">
        <label for="stock">Stok Unit (Kapasitas/Hari) *</label>
        <input id="stock" name="stock" type="number" min="1" value="{{ old('stock', $package->stock ?? 1) }}" required>
      </div>
    </div>

    <div class="fld">
      <label for="description">Deskripsi produk *</label>
      <textarea id="description" name="description" rows="3" required>{{ old('description', $package->description) }}</textarea>
    </div>

    <!-- Foto produk -->
    <div class="fld">
      <label for="image_file">Ubah foto produk (opsional, maks 5MB)</label>
      @if($package->image_path)
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 6px">
          <img src="{{ $package->image_path }}" alt="{{ $package->name }}" class="th" style="object-fit: cover">
          <span style="font-size: 13px; color: var(--admin-mu)">Gambar saat ini aktif</span>
        </div>
        <button class="btn dg sm" type="button" data-delete-action="{{ route('admin.packages.image.destroy', $package->id) }}" onclick="deletePackageImage(this)">Hapus gambar</button>
      @endif
      <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap">
        <label for="image_file" class="btn p" style="cursor: pointer">Pilih gambar baru</label>
        <input id="image_file" name="image_file" type="file" accept="image/*" hidden
               onchange="this.nextElementSibling.textContent = this.files[0]?.name || 'Belum ada file baru dipilih'">
        <span style="font-size: 13px; color: var(--admin-mu)">Belum ada file baru dipilih</span>
      </div>
      <input type="text" name="image_path" value="{{ old('image_path', $package->image_path) }}" placeholder="Atau perbarui URL gambar..." style="margin-top: 6px">
    </div>

    <div class="card" style="padding: 20px; display: flex; flex-direction: column; gap: 10px; background: var(--admin-s2)">
      <h3 class="d" style="font-size: 18px">Galeri Foto Produk</h3>
      <p style="font-size: 13px; color: var(--admin-mu); margin: 0">Tambahkan hingga 3 gambar pendukung yang tampil di bawah foto utama.</p>
      @if($package->gallery_images)
        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 4px">
          @foreach($package->gallery_images as $index => $galleryImage)
            <div style="min-width: 0">
              <img src="{{ $galleryImage }}" alt="Galeri {{ $index + 1 }}" style="display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 10px; border: 1px solid var(--admin-line)">
              <button class="btn dg sm" type="button" data-delete-action="{{ route('admin.packages.gallery-image.destroy', [$package->id, $index]) }}" onclick="deletePackageImage(this)" style="width: 100%; margin-top: 7px">Hapus</button>
            </div>
          @endforeach
        </div>
      @endif
      <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px">
        @for($slot = 1; $slot <= 3; $slot++)
          <div style="min-width: 0; display: flex; flex-direction: column; gap: 7px">
            <label for="gallery_image_{{ $slot }}" class="btn p" style="cursor: pointer; text-align: center">Pilih gambar {{ $slot }}</label>
            <input id="gallery_image_{{ $slot }}" name="gallery_images[]" type="file" accept="image/*" hidden onchange="document.getElementById('gallery-file-name-{{ $slot }}').textContent=this.files[0]?.name || 'Belum ada file'">
            <span id="gallery-file-name-{{ $slot }}" style="font-size: 12px; color: var(--admin-mu); overflow: hidden; text-overflow: ellipsis; white-space: nowrap">Belum ada file</span>
          </div>
        @endfor
      </div>
    </div>

    <!-- Items rincian paket -->
    <div class="fld">
      <label>Rincian isi paket *</label>
      <div style="display: flex; flex-direction: column; gap: 8px">
        <template x-for="(item, index) in items" :key="index">
          <div style="display: flex; gap: 8px">
            <input type="text" :name="`items[${index}]`" x-model="items[index]" placeholder="Contoh: 2 Unit Subwoofer 18 Inch" style="flex-grow: 1" required>
            <button class="btn ic dg" type="button" @click="if(items.length > 1) items.splice(index, 1)" title="Hapus baris">✕</button>
          </div>
        </template>
        <button class="btn" type="button" @click="items.push('')" style="width: fit-content; margin-top: 4px">+ Tambah Baris Item</button>
      </div>
    </div>

    <div style="display: flex; gap: 20px; align-items: center; border-top: 1px solid var(--admin-line); padding-top: 16px">
      <label class="chk" style="color: #17181C">
        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
        Tampilkan di Produk Unggulan
      </label>
      <label class="chk" style="color: #17181C">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
        Aktif di Katalog Publik
      </label>
    </div>

    <div style="display: flex; gap: 12px; margin-top: 8px">
      <button class="btn p" type="submit">Perbarui Produk</button>
      <a class="btn" href="{{ route('admin.packages.index') }}">Batal</a>
    </div>
  </form>

</div>
<script>
  document.getElementById('category')?.addEventListener('change', function () {
    const input = document.getElementById('new_category');
    const isNew = this.value === '__new__';
    input.style.display = isNew ? '' : 'none';
    input.required = isNew;
    if (!isNew) input.value = '';
  });

  function deletePackageImage(button) {
    if (!confirm('Hapus gambar ini?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = button.dataset.deleteAction;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
  }
</script>
@endsection
