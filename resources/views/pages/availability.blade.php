@extends('layouts.app')

@section('title', 'Cek Ketersediaan Jadwal — Vantara Production')
@section('meta_description', 'Cek ketersediaan jadwal rental sound system, lighting, dan perlengkapan event Vantara Production secara online.')

@section('content')
<div style="min-height: 1200px"
     x-data="calendarWidget({{ $selectedPackageId ?? 'null' }})"
     x-init="selectedDate = '{{ old('event_date', request('event_date') ?? request('date') ?? session('event_date')) }}'">

  <section class="wrap" style="padding-top: 56px; padding-bottom: 32px">
    <span class="eye">Ketersediaan</span>
    <div style="display: flex; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-top: 8px">
      <h1 class="d" style="font-size: 52px; line-height: 1.05; font-weight: 800; flex-grow: 1">Cek Jadwal &amp; Ketersediaan</h1>
      <span style="color: var(--mu)">Pembaruan realtime</span>
    </div>
  </section>

  <section class="wrap" style="padding-bottom: 96px">
    <div class="card" style="padding: 40px; display: flex; flex-wrap: wrap; gap: 48px">
      <!-- Calendar Area -->
      <div style="flex: 2 1 480px">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; flex-wrap: wrap">
          <div class="fld" style="flex: 1 1 260px">
            <label for="filter_pkg">Pilih Paket / Peralatan</label>
            <select id="filter_pkg" x-model="packageId" @change="fetchMonthData()" style="background: var(--s2); border: 1px solid #3A3F4C; border-radius: 12px; padding: 12px 14px; color: var(--tx); min-height: 46px">
              <option value="">Semua Jadwal Vantara</option>
              @foreach($packages as $pkg)
                <option value="{{ $pkg->id }}" {{ $selectedPackageId == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }} ({{ $pkg->category }})</option>
              @endforeach
            </select>
          </div>
        </div>

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
              <span x-show="c.isCurrentMonth && !c.isPast && !c.isBooked && selectedDate === c.dateString" class="c s" x-text="c.day"></span>
              <button x-show="c.isCurrentMonth && !c.isPast && !c.isBooked && selectedDate !== c.dateString" class="c" type="button" @click="onDayClick(c)" x-text="c.day" style="width: 100%"></button>
            </div>
          </template>
        </div>

        <div style="display: flex; gap: 28px; margin-top: 20px; font-size: 14px; color: var(--mu); flex-wrap: wrap">
          <span><i class="sw"></i>Tersedia</span>
          <span><i class="sw" style="background: repeating-linear-gradient(135deg,#1C1F27,#1C1F27 3px,#14161B 3px,#14161B 6px)"></i>Sudah dibooking</span>
          <span><i class="sw" style="background: var(--ac); border-color: var(--ac)"></i>Dipilih</span>
        </div>
      </div>

      <!-- Hasil Pengecekan Sidebar -->
      <div style="flex: 1 1 300px; align-self: flex-start; background: var(--s2); border: 1px solid var(--line); border-radius: 20px; padding: 28px; display: flex; flex-direction: column; gap: 16px">
        <span class="eye">Hasil pengecekan</span>
        <p style="color: var(--mu)">Tanggal dipilih</p>
        <strong class="d" style="font-size: 28px; margin-top: -10px" x-text="selectedDate ? selectedDate.split('-').reverse().join(' / ') : 'Belum dipilih'"></strong>

        <template x-if="selectedDate && !isDateBooked(selectedDate)">
          <span class="badge inline"><i class="dot"></i>Jadwal tersedia</span>
        </template>
        <template x-if="selectedDate && isDateBooked(selectedDate)">
          <span class="badge inline" style="background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.35); color: #FCA5A5">Sudah dibooking</span>
        </template>

        <p style="color: var(--mu); font-size: 15px">Pilih produk di katalog atau lanjut ke booking untuk mengamankan tanggal acara Anda.</p>

        <div style="display: flex; flex-direction: column; gap: 10px">
          <a class="btn p" :href="`{{ route('checkout.index') }}?event_date=${selectedDate}`" :disabled="!selectedDate || isDateBooked(selectedDate)" :style="(!selectedDate || isDateBooked(selectedDate)) ? 'pointer-events: none; opacity: 0.5' : ''">
            Lanjut Booking Tanggal Ini
          </a>
          <a class="btn" :href="`{{ route('packages.catalog') }}?date=${selectedDate}`">Lihat Katalog Paket</a>
          <a class="btn wa" :href="`https://wa.me/6282282422317?text=${encodeURIComponent('Halo Admin Vantara Production, saya ingin konsultasi ketersediaan jadwal untuk tanggal ' + (selectedDate ? selectedDate.split('-').reverse().join('/') : 'acara'))}`" target="_blank" rel="noopener">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.53 7.62C9.33 7.62 9 7.7 8.71 8.01C8.42 8.32 7.6 9.09 7.6 10.65C7.6 12.21 8.74 13.72 8.9 13.93C9.06 14.14 11.14 17.34 14.32 18.72C15.08 19.04 15.67 19.24 16.14 19.38C16.9 19.63 17.59 19.59 18.14 19.51C18.75 19.42 20.03 18.73 20.29 17.98C20.56 17.23 20.56 16.59 20.48 16.45C20.4 16.32 20.19 16.24 19.89 16.09C19.58 15.93 18.08 15.2 17.8 15.1C17.52 15 17.32 14.95 17.11 15.25C16.91 15.56 16.34 16.24 16.17 16.44C16 16.65 15.83 16.67 15.52 16.52C15.22 16.37 14.24 16.05 13.08 15.01C12.18 14.2 11.57 13.2 11.41 12.92C11.25 12.65 11.39 12.5 11.55 12.35C11.69 12.21 11.86 11.99 12.01 11.81C12.16 11.64 12.22 11.51 12.32 11.31C12.42 11.1 12.37 10.92 12.29 10.77C12.21 10.62 11.6 9.12 11.35 8.5C11.1 7.9 10.85 7.98 10.66 7.97C10.48 7.97 10.27 7.96 10.07 7.96C9.86 7.96 9.53 7.62 9.53 7.62Z"/>
            </svg>
            <span>Konsultasi Tanggal via WA</span>
          </a>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
