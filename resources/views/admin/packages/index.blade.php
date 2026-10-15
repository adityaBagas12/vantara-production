@extends('layouts.admin')

@section('title', 'Kelola Produk — Admin Vantara Production')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px">
  <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap">
    <div style="flex-grow: 1">
      <h1 class="d" style="font-size: 36px; font-weight: 800">Kelola Produk</h1>
      <p style="color: var(--admin-mu)">Tambah, ubah, dan hapus produk rental.</p>
    </div>
    <a class="btn p" href="{{ route('admin.packages.create') }}">+ Tambah Produk</a>
  </div>

  <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start">
    <!-- Tabel Produk -->
    <div class="card" style="flex: 2 1 480px; padding: 20px 24px; display: flex; flex-direction: column; gap: 14px">
      <form action="{{ route('admin.packages.index') }}" method="GET" class="fld">
        <label for="cari">Cari produk</label>
        <div style="display: flex; gap: 8px">
          <input id="cari" name="search" type="text" value="{{ request('search') }}" placeholder="Ketik nama atau kategori produk" style="flex-grow: 1">
          <button class="btn" type="submit">Cari</button>
        </div>
      </form>

      <div style="overflow-x: auto">
        <table>
          <thead>
            <tr>
              <th>Produk</th>
              <th>Harga / hari</th>
              <th>Stok Unit</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($packages as $pkg)
              <tr>
                <td>
                  <div style="display: flex; align-items: center; gap: 14px">
                    @if($pkg->image_path)
                      <img src="{{ $pkg->image_path }}" alt="{{ $pkg->name }}" class="th" style="object-fit: cover">
                    @else
                      <span class="th">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                      </span>
                    @endif
                    <div>
                      <strong style="color: var(--admin-tx)">{{ $pkg->name }}</strong>
                      <p style="color: var(--admin-mu); font-size: 13px">{{ $pkg->category }}</p>
                    </div>
                  </div>
                </td>
                <td style="white-space: nowrap; font-family: var(--font-mono); font-weight: 600">
                  Rp {{ number_format($pkg->price, 0, ',', '.') }}
                </td>
                <td style="white-space: nowrap">
                  <span style="display: inline-block; padding: 4px 10px; border-radius: 8px; background: var(--admin-s2); border: 1px solid var(--admin-line); font-family: var(--font-mono); font-size: 13px; font-weight: 600">
                    {{ $pkg->stock ?? 1 }} Unit
                  </span>
                </td>
                <td>
                  <form action="{{ route('admin.packages.toggleActive', $pkg->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" style="background: transparent; border: 0; padding: 0; cursor: pointer" title="Klik untuk ubah status">
                      @if($pkg->is_active)
                        <span class="tg a">Tersedia</span>
                      @else
                        <span class="tg g">Nonaktif</span>
                      @endif
                    </button>
                  </form>
                </td>
                <td>
                  <div style="display: flex; gap: 8px">
                    <a class="btn ic" href="{{ route('admin.packages.edit', $pkg->id) }}" aria-label="Ubah produk" title="Ubah produk">
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 14l1-4 8-8 3 3-8 8zM9.5 3.5l3 3"/></svg>
                    </a>
                    <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Hapus permanen paket \'{{ addslashes($pkg->name) }}\'?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn ic dg" type="submit" aria-label="Hapus produk" title="Hapus produk">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6 4V2.5h4V4M4 4l.7 9.5h6.6L12 4"/></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align: center; color: var(--admin-mu)">Belum ada data produk.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($packages->hasPages())
        <div style="margin-top: 12px">
          {{ $packages->links() }}
        </div>
      @endif
    </div>

    <!-- Panel Tambah Cepat / Info -->
    <div style="flex: 1 1 340px; display: flex; flex-direction: column; gap: 20px">
      <div class="card" style="display: flex; flex-direction: column; gap: 16px">
        <h2 class="d" style="font-size: 22px">Aksi Cepat</h2>
        <p style="font-size: 14px; color: var(--admin-mu)">
          Gunakan tombol di bawah untuk menambahkan varian sound system, lighting, atau orgen baru ke katalog sewa.
        </p>
        <a class="btn p" href="{{ route('admin.packages.create') }}" style="text-align: center">
          + Formulir Tambah Produk
        </a>
      </div>

      <div class="card" style="display: flex; flex-direction: column; gap: 12px">
        <strong style="color: var(--admin-tx)">Petunjuk Pengelolaan Produk</strong>
        <p style="font-size: 14px; color: var(--admin-mu)">
          • Klik badge status (Tersedia / Nonaktif) untuk toggle langsung tanpa membuka form.<br>
          • Gunakan ikon pensil untuk mengubah nama, rincian teknis, dan harga sewa harian.<br>
          • Icon sampah akan menghapus paket dari daftar katalog online.
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
