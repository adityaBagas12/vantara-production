# Product Requirement Document (PRD) — Vantara Production

---

## 1. Ringkasan Produk
**Vantara Production** adalah sistem informasi manajemen penyewaan/pemesanan berbasis web yang dirancang untuk memudahkan pelanggan dalam melihat katalog paket, mengecek ketersediaan tanggal, dan melakukan pemesanan. Sistem ini juga dilengkapi portal administratif untuk membantu tim Vantara Production mengelola produk, transaksi, serta memantau laporan keuangan bulanan.

---

## 2. Peta Jalan Pengembangan (Roadmap Phases)

| Fase | Fokus Utama | Modul / Fitur |
| :--- | :--- | :--- |
| **Fase 1** | Eksplorasi & Katalog Paket | Katalog Paket, Detail & Cek Tanggal |
| **Fase 2** | Transaksi & Pemesanan | Keranjang & Checkout |
| **Fase 3** | Portal Admin & Manajemen Transaksi | Login Admin, Dashboard Admin, Kelola Barang, Kelola Status Transaksi |
| **Fase 4** | Analisis & Pelaporan | Laporan Bulanan & Export PDF |

---

## 3. Spesifikasi Fitur Detail

---

### Phase 1: Eksplorasi & Katalog Paket

#### 1. Katalog Paket
* **Deskripsi:** Halaman utama bagi pelanggan untuk melihat gambaran umum layanan dan pilihan paket yang tersedia.
* **Sub-Fitur:**
  * **Profil Usaha:** Menampilkan deskripsi singkat, visi, serta identitas Vantara Production.
  * **Paket Unggulan:** Menampilkan rekomendasi atau *highlight* paket yang paling populer/banyak dipesan.
  * **Daftar Semua Paket:** Katalog komprehensif seluruh paket layanan yang ditawarkan dengan opsi *filtering* atau *sorting*.

#### 2. Detail & Cek Tanggal
* **Deskripsi:** Halaman untuk melihat detail isi paket serta fitur interaktif untuk mengecek ketersediaan tanggal pemesanan.
* **Sub-Fitur:**
  * **Detail Paket:** Menampilkan informasi lengkap paket (deskripsi, item/layanan yang didapat, batasan, serta harga).
  * **Kalender Ketersediaan:** Tampilan visual kalender yang menunjukkan tanggal mana saja yang sudah terisi (*booked*) atau masih tersedia.
  * **Cek Tanggal Kosong:** Fitur pencarian cepat tanggal kosong sesuai dengan rencana tanggal acara pelanggan.

---

### Phase 2: Transaksi & Pemesanan

#### 3. Keranjang & Checkout
* **Deskripsi:** Alur pemesanan bagi pelanggan untuk memilih paket dan mengirimkan pengajuan pesanan.
* **Sub-Fitur:**
  * **Tambah ke Keranjang:** Memasukkan paket layanan pilihan ke dalam daftar belanja sementara.
  * **Kelola Keranjang:** Mengatur detail pesanan (menambah/mengurangi kuantitas, opsi tambahan, atau menghapus paket).
  * **Isi Data Diri:** Formulir pengisian identitas pemesan (nama, nomor telepon/WhatsApp, alamat, tanggal acara, serta catatan khusus).

---

### Phase 3: Portal Admin & Operasional

#### 4. Login Admin
* **Deskripsi:** Sistem autentikasi khusus untuk pengelola/admin Vantara Production.
* **Sub-Fitur:**
  * **Tombol Login Admin:** Pintasan akses menuju halaman *sign-in* internal.
  * **Masuk dengan Akun:** Validasi akses menggunakan kredensial terdaftar (*username/email* & *password*).
  * **Keluar Akun:** Sesi *logout* aman untuk menjaga keamanan data.

#### 5. Dashboard Admin
* **Deskripsi:** Pusat kendali statistik dan informasi penting operasional harian.
* **Sub-Fitur:**
  * **Ringkasan Penjualan:** Ringkasan metrik finansial/penjualan terkini.
  * **Rekap Bulanan:** Grafis/statistis performa transaksi selama periode bulan berjalan.
  * **Pesanan Terkini:** Daftar transaksi terbaru yang masuk dan membutuhkan tindakan/verifikasi cepat dari admin.

#### 6. Kelola Barang / Paket
* **Deskripsi:** Fitur CRUD (*Create, Read, Update, Delete*) untuk katalog paket layanan.
* **Sub-Fitur:**
  * **Tambah Paket:** Formulir untuk menambah varian paket atau layanan baru ke katalog.
  * **Edit Paket:** Memperbarui rincian item, deskripsi, status aktif/non-aktif, atau harga paket.
  * **Hapus Paket:** Menghapus paket dari sistem atau menyembunyikannya dari katalog publik.

#### 7. Kelola Status Transaksi
* **Deskripsi:** Modul operasional untuk memproses pemesanan yang masuk dari pelanggan.
* **Sub-Fitur:**
  * **Daftar Pesanan Masuk:** Tampilan *list* seluruh pesanan masuk lengkap dengan filter status.
  * **Ubah Status Pesanan:** Memperbarui tahapan pesanan (misal: *Pending, DP Received, Confirmed, Completed, Cancelled*).
  * **Catat DP & Ubah Harga:** Pencatatan jumlah Uang Muka (*Down Payment*) yang dibayarkan serta penyesuaian kustom harga jika ada penambahan *add-on*.

---

### Phase 4: Analisis & Pelaporan

#### 8. Laporan Bulanan
* **Deskripsi:** Modul analisis untuk merekap performa bisnis secara rinci dalam jangka waktu tertentu.
* **Sub-Fitur:**
  * **Pilih Periode Laporan:** Filter rentang tanggal/bulan laporan yang ingin ditarik.
  * **Unduh PDF:** Opsi cetak/ekspor berkas laporan rekapitulasi penjualan ke format dokumen PDF.
  * **Riwayat Laporan:** Arsip atau *log* laporan yang telah di-generate sebelumnya.

---

## 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)

1. **User Interface (UI) / User Experience (UX):**
   * Tampilan responsif (dapat diakses dengan nyaman melalui perangkat *Mobile* maupun *Desktop*).
   * Antarmuka intuitif bagi pelanggan saat melakukan pengecekan tanggal.

2. **Keamanan Sistem:**
   * Proteksi halaman admin hanya dapat diakses setelah autentikasi berhasil.
   * Enkripsi data sensitif (misal: *password* admin).

3. **Performa:**
   * Pengecekan status ketersediaan kalender harus berjalan cepat tanpa *lagging*.