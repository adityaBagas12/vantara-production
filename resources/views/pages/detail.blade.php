@extends('layouts.app')

@section('title', $package->name . ' — Vantara Production')
@section('meta_description', $package->description)

@section('content')
<div style="min-height: 1900px"
     x-data="calendarWidget({{ $package->id }})"
     x-init="selectedDate = '{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date')) }}'; rangeStart = selectedDate; rangeEnd = '{{ request('event_end_date') ?? session('event_end_date') }}'">

  <!-- Detail Header & Summary -->
  <section class="wrap" style="padding-top: 40px; padding-bottom: 72px">
    <p style="color: var(--mu); font-size: 14px; margin-bottom: 28px">
      <a href="{{ route('packages.catalog') }}" style="text-decoration: underline">Katalog</a> / {{ $package->category }} / {{ $package->name }}
    </p>

    <div style="display: flex; flex-wrap: wrap; gap: 56px">
      <!-- Media preview -->
      <div style="flex: 1 1 480px; display: flex; flex-direction: column; gap: 14px">
        <div class="pic" style="height: 440px; border-radius: 28px; border: 1px solid var(--line)">
          @if($package->image_path)
            <img src="{{ $package->image_path }}" alt="{{ $package->name }}" style="width: 100%; height: 100%; object-fit: cover">
          @else
            @php $catLower = strtolower($package->category); @endphp
            @if(str_contains($catLower, 'sound'))
              <svg width="150" height="150" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x="12" y="4" width="24" height="40" rx="4"/><circle cx="24" cy="30" r="7"/><circle cx="24" cy="30" r="3"/><circle cx="24" cy="14" r="3"/></svg>
            @elseif(str_contains($catLower, 'light'))
              <svg width="150" height="150" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><path d="M16 6h16l4 12H12z"/><path d="M12 18 6 42M36 18l6 24M24 18v24"/></svg>
            @elseif(str_contains($catLower, 'mic') || str_contains($catLower, 'orgen') || str_contains($catLower, 'band'))
              <svg width="150" height="150" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x="17" y="4" width="14" height="24" rx="7"/><path d="M9 22a15 15 0 0 0 30 0M24 37v7M16 44h16"/></svg>
            @else
              <svg width="150" height="150" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><rect x="4" y="10" width="40" height="28" rx="4"/><path d="M14 18v14M24 18v14M34 18v14"/><circle cx="14" cy="24" r="2.5"/><circle cx="24" cy="30" r="2.5"/><circle cx="34" cy="22" r="2.5"/></svg>
            @endif
          @endif
        </div>
        <div style="display: flex; gap: 14px">
          <div class="pic k2" style="flex: 1 1 0; height: 96px; border-radius: 16px; border: 1px solid var(--line)"></div>
          <div class="pic k3" style="flex: 1 1 0; height: 96px; border-radius: 16px; border: 1px solid var(--line)"></div>
          <div class="pic" style="flex: 1 1 0; height: 96px; border-radius: 16px; border: 1px solid var(--line)"></div>
        </div>
      </div>

      <!-- Specs & Details -->
      <div style="flex: 1 1 400px; display: flex; flex-direction: column; gap: 22px">
        <span class="badge inline"><i class="dot"></i>Tersedia (Stok: {{ $package->stock ?? 1 }} Unit/Hari)</span>
        <h1 class="d" style="font-size: 52px; line-height: 1.04; font-weight: 800">{{ $package->name }}</h1>
        <p class="price" style="font-size: 32px">Rp {{ number_format($package->price, 0, ',', '.') }} <span style="font-size: 16px; color: var(--mu); font-weight: 500; font-family: 'Figtree', sans-serif">/ hari</span></p>
        <p style="color: var(--mu); font-size: 17px">{{ $package->description }}</p>

        <div class="card" style="padding: 22px 24px; display: flex; flex-direction: column; gap: 10px">
          <strong class="d" style="font-size: 18px">Isi paket</strong>
          @forelse($package->items ?? [] as $item)
            <span style="color: var(--mu)">• {{ $item }}</span>
          @empty
            <span style="color: var(--mu)">• Standar peralatan panggung Vantara</span>
          @endforelse
        </div>

        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center">
          <a class="btn p" href="#cek-tanggal-acara" @click.prevent="document.getElementById('cek-tanggal-acara')?.scrollIntoView({ behavior: 'smooth', block: 'start' })">Booking Produk Ini</a>
          <a class="btn" href="{{ route('packages.catalog') }}">Kembali ke Katalog</a>
          <a class="btn wa" href="https://wa.me/6282282422317?text={{ urlencode('Halo Admin Vantara Production, saya ingin konsultasi mengenai paket ' . $package->name) }}" target="_blank" rel="noopener" title="Konsultasi cepat paket ini via WhatsApp Admin">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
            </svg>
            <span>Konsultasi Paket Ini</span>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Ketersediaan & Kalender Section -->
  <section id="cek-tanggal-acara" class="wrap" style="padding-bottom: 96px; scroll-margin-top: 24px">
    <div class="card" style="padding: 40px; display: flex; flex-wrap: wrap; gap: 48px">
      <!-- Calendar Area -->
      <div style="flex: 2 1 480px">
        <span class="eye">Ketersediaan</span>
        <h2 class="d" style="font-size: 34px; margin: 8px 0 24px">Cek tanggal acara</h2>

        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 18px">
          <button class="btn ic" type="button" @click="prevMonth()" aria-label="Bulan sebelumnya">‹</button>
          <strong class="d" style="flex-grow: 1; text-align: center; font-size: 22px" x-text="`${monthNames[currentMonth - 1]} ${currentYear}`"></strong>
          <button class="btn ic" type="button" @click="nextMonth()" aria-label="Bulan berikutnya">›</button>
        </div>

        <div class="cal">
          <template x-for="h in ['Sen','Sel','Rab','Kam','Jum','Sab','Min']" :key="h">
            <span class="h" x-text="h"></span>
          </template>

          <template x-for="(c, idx) in calendarDays" :key="idx">
            <div>
              <span x-show="!c.isCurrentMonth"></span>
              <span x-show="c.isCurrentMonth && c.isPast" class="c x" x-text="c.day" title="Tanggal telah berlalu"></span>
              <span x-show="c.isCurrentMonth && !c.isPast && c.isBooked" class="c b" x-text="c.day" title="Sudah dibooking (Terkunci)"></span>
              <button x-show="c.isCurrentMonth && !c.isPast && !c.isBooked && isDateSelected(c.dateString)" class="c s" type="button" @click="onDayClick(c)" x-text="c.day" style="width: 100%" title="Klik untuk membatalkan pilihan"></button>
              <button x-show="c.isCurrentMonth && !c.isPast && !c.isBooked && !isDateSelected(c.dateString)" class="c" type="button" @click="onDayClick(c)" x-text="c.day" style="width: 100%"></button>
            </div>
          </template>
        </div>

        <div style="display: flex; gap: 28px; margin-top: 20px; font-size: 14px; color: var(--mu); flex-wrap: wrap">
          <span><i class="sw"></i>Tersedia</span>
          <span><i class="sw" style="background: repeating-linear-gradient(135deg,#1C1F27,#1C1F27 3px,#14161B 3px,#14161B 6px)"></i>Sudah dibooking</span>
          <span><i class="sw" style="background: var(--ac); border-color: var(--ac)"></i>Dipilih</span>
          <span><i class="sw" style="background: #7F1D1D; border-color: #991B1B"></i>Reset untuk ganti tanggal mulai</span>
        </div>
      </div>

        <!-- Hasil Pengecekan Sidebar -->
      <div style="flex: 1 1 300px; align-self: flex-start; background: var(--s2); border: 1px solid var(--line); border-radius: 20px; padding: 28px; display: flex; flex-direction: column; gap: 16px">
        <span class="eye">Hasil pengecekan</span>
        <p style="color: var(--mu)">Tanggal dipilih</p>
        <strong class="d" style="font-size: 24px; margin-top: -10px" x-text="dateSummary()"></strong>
        <span style="color: var(--ac); font-size: 14px" x-show="rangeStart && !rangeEnd">1 hari · Pilih tanggal lain jika ingin menambah hari</span>
        <span style="color: var(--mu); font-size: 14px" x-show="rangeEnd" x-text="durationLabel()"></span>

        <template x-if="rangeStart && !rangeHasBookedDate()">
          <div>
            <span class="badge inline"><i class="dot"></i>Tersedia</span>
            <span style="display: inline-block; margin-left: 6px; font-size: 12px; color: var(--mu)" x-show="checkResult?.remaining_stock !== undefined">
              (Sisa <strong style="color: var(--ac)" x-text="checkResult?.remaining_stock"></strong> unit)
            </span>
          </div>
        </template>
        <template x-if="rangeStart && rangeHasBookedDate()">
          <span class="badge inline" style="background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.35); color: #FCA5A5">Sudah Terisi Penuh</span>
        </template>

        <p style="color: var(--mu); font-size: 14px" x-text="checkResult?.message ?? 'Lanjutkan booking untuk mengamankan tanggal ini. Harga akhir disepakati bersama Admin lewat WhatsApp.'"></p>

        <form action="{{ route('cart.store') }}" method="POST" @submit="if(!selectedDate || rangeHasBookedDate()){ $event.preventDefault(); return false; }">
          @csrf
          <input type="hidden" name="package_id" value="{{ $package->id }}">
          <input type="hidden" name="event_date" :value="selectedDate">
          <input type="hidden" name="event_end_date" :value="rangeEnd || selectedDate">

          <div style="margin-bottom: 14px; background: var(--s1); border: 1px solid var(--line); border-radius: 12px; padding: 12px">
            <label for="detail_qty" style="display: block; font-size: 12px; color: var(--mu); margin-bottom: 6px; font-weight: 600">Jumlah Unit Dipesan:</label>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap">
              <input id="detail_qty" type="number" name="quantity" value="1" min="1" :max="checkResult?.remaining_stock ?? {{ $package->stock ?? 1 }}"
                     style="width: 70px; background: var(--s2); border: 1px solid #3A3F4C; border-radius: 8px; padding: 6px 10px; color: var(--tx); font-family: var(--font-mono); text-align: center" required>
              <span style="font-size: 13px; color: var(--mu)">Unit (Stok: {{ $package->stock ?? 1 }})</span>
              <button type="button" x-show="rangeStart" @click="resetRange()" class="btn" style="padding: 8px 12px; min-height: 38px; font-size: 12px; white-space: nowrap; background: #7F1D1D; border-color: #991B1B; color: #FDECEC">Reset pilihan</button>
            </div>
          </div>

          <div style="display: flex; flex-direction: column; gap: 10px">
            <button class="btn p" type="submit" :disabled="!selectedDate || rangeHasBookedDate()" style="width: 100%">
              <span x-text="!selectedDate ? 'Pilih Tanggal Dahulu' : (rangeHasBookedDate() ? 'Ada Tanggal Penuh' : `Lanjut Booking ${durationLabel()}`)"></span>
            </button>
            <a class="btn wa" href="https://wa.me/6282282422317?text={{ urlencode('Halo Admin Vantara Production, saya ingin konsultasi ketersediaan dan sewa paket ' . $package->name) }}" target="_blank" rel="noopener" style="width: 100%; text-align: center">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
              </svg>
              <span>Konsultasi Cepat via WhatsApp</span>
            </a>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
@endsection
