# Audit Proyek Laravel Vantara Production

Audit ini dilakukan pada workspace lokal dengan asumsi beban produksi ringan sampai menengah: beberapa admin dan pelanggan dapat mengakses aplikasi secara bersamaan. Tahap audit kode tidak mengubah kode aplikasi; file ini adalah satu-satunya artefak laporan yang dibuat.

## 1. Ringkasan eksekutif

**What this repo does:** aplikasi Laravel untuk katalog paket rental, keranjang, booking, pengecekan ketersediaan, portal admin, invoice, pengelolaan gambar landing page, biaya operasional, dan laporan pendapatan. Pelanggan tidak memiliki akun; admin masuk melalui portal yang dilindungi middleware.

| Aspek | Skor | Ringkasan |
|---|---:|---|
| Fungsionalitas | 7/10 | Alur katalog, checkout, admin, invoice, dan laporan tersedia. Beberapa aturan finansial dan stok masih mudah tidak konsisten. |
| Keamanan | 6/10 | Beberapa temuan konfigurasi dan login sudah diperbaiki di working tree; invoice publik dan kontrol stok masih berisiko. |
| Keandalan data | 5/10 | Checkout memakai transaksi DB, tetapi pengecekan stok terjadi sebelum transaksi tanpa lock/reservasi atomik. |
| Performa | 6/10 | Query utama cukup kecil dan beberapa relasi di-eager-load; kalender ketersediaan melakukan perulangan tanggal di PHP dan dashboard melakukan agregasi berulang. |
| Pengujian | 5/10 | Ada test feature untuk alur utama, tetapi runner tidak dapat dimulai di workspace ini dan belum ada coverage untuk IDOR, rate limit, race stok, upload, atau otorisasi detail. |
| Kesiapan deploy | 5/10 | Debug dan contoh kredensial sudah dibersihkan; storage upload, README, validasi runtime, dan worktree masih perlu ditinjau. |

## 2. Build, analisis otomatis, audit dependensi, dan test

- PHP terdeteksi **8.3.30**. `composer.json` meminta PHP `^8.3` dan Laravel `^13.17`; `composer.lock` memuat Laravel Framework `v13.33.0`, DomPDF `v3.1.2`, dan Tinker `v3.0.2`.
- Node terdeteksi **v24.13.1**, npm **11.8.0**. `package.json` memakai Vite 8, Tailwind 4, Alpine 3.17, dan Laravel Vite Plugin 3.
- `npm run build` **berhasil** pada pemeriksaan sebelumnya. Vite memberi warning bahwa `/images/whatsapp.png` tidak diselesaikan saat build dan akan dicari saat runtime. File tersebut memang ada di `public/images/whatsapp.png`.
- `php artisan optimize:clear`, `about`, `route:list`, `config:cache`, `config:clear`, `route:cache`, dan `route:clear` masing-masing tidak menghasilkan output dan dihentikan setelah sekitar 25–30 detik. Keberhasilan cache atau isi route belum terverifikasi.
- `composer validate` menggantung sekitar 30 detik, sedangkan `composer audit` dan `composer outdated --direct` masing-masing menggantung sekitar 10 detik. Status validitas, kerentanan, dan paket usang belum terverifikasi.
- `npm audit --audit-level=low` gagal setelah sekitar 10 detik dengan `getaddrinfo ENOTFOUND registry.npmjs.org`; hasil kerentanan npm belum terverifikasi.
- `vendor/bin/pint --test` tidak menghasilkan output dan dihentikan setelah sekitar 30 detik.
- Konfigurasi PHPUnit memakai `DB_CONNECTION=mysql`, host `127.0.0.1`, dan database `vantara_production_test` (`phpunit.xml:19-24`), terpisah dari `.env` utama. `php artisan test --compact` tidak menghasilkan output dan dihentikan setelah sekitar 30 detik.
- `php artisan serve --host=127.0.0.1 --port=8000` berhasil start. Smoke request ke `/`, `/katalog`, `/admin/login`, dan `/pesanan/VAN-20261005-0001` masing-masing timeout 10 detik; request tidak menghasilkan status HTTP sebelum dihentikan. Pengujian awal dari sandbox juga ditolak oleh pembatasan socket lokal.
- Route, controller, model, migration, seeder, middleware, dan view tetap dipetakan manual karena `route:list` tidak selesai.

## 3. Temuan

### Must fix

1. **Debug production sudah dimatikan (direvisi)** (`vercel.json:27-36`)
   - **What this is:** konfigurasi Vercel sekarang menetapkan `APP_ENV=production` dan `APP_DEBUG=false`.
   - **Problem:** temuan debug aktif dari audit awal tidak lagi terlihat di working tree.
   - **Fix:** pertahankan `APP_DEBUG=false` pada environment deployment.
   - **If we skip it:** jika nilai environment platform menimpa file ini dengan `true`, risiko kebocoran stack trace kembali muncul.

2. **Invoice publik mengekspos data pribadi melalui kode pesanan yang dapat ditebak** (`routes/web.php:42`, `app/Http/Controllers/CheckoutController.php:155-157`, `app/Models/Order.php:89-105`)
   - **What this is:** siapa pun yang mengetahui URL `/pesanan/{order_code}` dapat mengambil invoice tanpa login.
   - **Problem:** kode dibuat berurutan berdasarkan tanggal dan nomor terakhir; halaman memuat nama, telepon, email, alamat acara, catatan, dan nilai transaksi.
   - **Fix:** gunakan token akses acak sekali pakai/bertanda tangan yang terpisah dari nomor invoice, atau minta verifikasi pemesan sebelum menampilkan data.
   - **If we skip it:** orang dapat meng-enumerasi URL dan membaca data customer lain.

3. **Seeder admin sudah memakai environment (direvisi)** (`database/seeders/DatabaseSeeder.php:18-31`, `.env.example:13-14`)
   - **What this is:** seeder sekarang membaca `ADMIN_EMAIL` dan `ADMIN_PASSWORD`, lalu berhenti jika kosong.
   - **Problem:** risiko password literal dari audit awal sudah dihilangkan. Seeder tetap harus dijalankan hanya pada environment yang sengaja dikonfigurasi.
   - **Fix:** pertahankan secret di environment manager dan jangan mencetak nilainya ke log.
   - **If we skip it:** seeder yang dijalankan dengan secret lemah tetap menghasilkan akun admin yang mudah diserang.

4. **Pengecekan stok memiliki race condition** (`app/Http/Controllers/CheckoutController.php:88-105, 114-145`)
   - **What this is:** checkout menghitung stok tersedia, lalu baru membuat order dalam transaksi.
   - **Problem:** dua request bersamaan dapat sama-sama melihat stok yang sama sebelum salah satunya menyimpan order, sehingga kapasitas terjual melebihi stok.
   - **Fix:** lakukan reservasi atomik di transaksi dengan row lock/constraint yang sesuai, atau simpan reservation dan validasi ulang di dalam transaksi.
   - **If we skip it:** booking ganda dapat terjadi pada tanggal dan paket yang sama.

5. **APP_KEY contoh sudah dikosongkan (direvisi)** (`.env.example:2-4`)
   - **What this is:** `.env.example` sekarang memuat `APP_KEY=` tanpa nilai.
   - **Problem:** risiko membagikan key melalui contoh konfigurasi sudah dihilangkan. Nilai `.env` aktif tidak ditampilkan dan belum diaudit nilainya.
   - **Fix:** generate key unik pada setiap environment dan jangan commit `.env`.
   - **If we skip it:** penggunaan key yang sama lintas environment tetap dapat membahayakan cookie terenkripsi.

6. **Login admin sudah memiliki rate limiting (direvisi)** (`routes/web.php:46-50`, `app/Providers/AppServiceProvider.php:22-43`, `app/Http/Controllers/Admin/AuthController.php:39-43`)
   - **What this is:** route memakai limiter `admin-login` sebanyak 5 percobaan per menit berdasarkan email dan IP, dengan pesan 429/redirect yang jelas.
   - **Problem:** implementasi ini belum lolos test runtime karena PHPUnit dan Artisan menggantung pada environment audit.
   - **Fix:** pertahankan limiter dan buktikan lewat test di CI/local runtime yang sehat.
   - **If we skip it:** regresi route atau cache limiter dapat menghilangkan perlindungan brute force.

7. **Upload production tidak memiliki storage persisten yang jelas** (`app/Http/Controllers/Admin/AdminPackageController.php:79-82, 141-143`, `.env.example:31`, `vercel.json:1-24`)
   - **What this is:** gambar disimpan ke disk `public` lokal, sedangkan deployment Vercel bersifat serverless.
   - **Problem:** file upload dapat hilang saat instance diganti dan tidak ada bucket/object storage yang dikonfigurasi.
   - **Fix:** gunakan object storage persisten untuk production dan simpan URL/path hasil upload.
   - **If we skip it:** gambar katalog atau landing page dapat menghilang setelah redeploy.

### Should fix

8. **Subtotal kategori laporan dapat berbeda dari total kesepakatan** (`app/Http/Controllers/Admin/AdminReportController.php:104-111`, `app/Models/Order.php:62-81`)
   - **What this is:** beberapa tampilan memakai subtotal hasil negosiasi, tetapi breakdown kategori masih menjumlahkan `order_items.subtotal` langsung.
   - **Problem:** order lama atau order dengan data subtotal belum tersinkron dapat membuat kategori tidak sama dengan total pendapatan laporan.
   - **Fix:** tetapkan satu sumber kebenaran transaksi yang dialokasikan saat total berubah, lalu gunakan nilai itu di semua laporan.
   - **If we skip it:** angka total laporan dan breakdown kategori dapat berbeda.

9. **Dashboard belum menghitung pendapatan bersih** (`app/Http/Controllers/Admin/DashboardController.php:20-58`)
   - **What this is:** dashboard menghitung pendapatan dan DP, sedangkan biaya operasional hanya dihitung di laporan bulanan.
   - **Problem:** admin melihat angka kotor di dashboard dan angka bersih di laporan tanpa penjelasan bahwa metriknya berbeda.
   - **Fix:** tampilkan kotor, biaya, dan bersih dengan definisi yang sama pada dashboard atau beri label yang tegas.
   - **If we skip it:** keputusan operasional dapat memakai angka pendapatan yang salah konteks.

10. **Endpoint availability mengambil dan memproses semua order dalam rentang bulan di PHP** (`app/Http/Controllers/AvailabilityController.php:53-139`)
   - **What this is:** query mengambil seluruh order beserta item, lalu melakukan loop tanggal dan item untuk setiap order.
   - **Problem:** waktu dan memory tumbuh bersama jumlah order, hari sewa, dan paket aktif.
   - **Fix:** agregasikan booking per tanggal/paket di database atau simpan reservation harian dengan indeks.
   - **If we skip it:** kalender menjadi lambat ketika data booking bertambah.

11. **Pembuatan nomor order masih check-then-insert** (`app/Models/Order.php:89-105`, `app/Http/Controllers/CheckoutController.php:112-117`)
   - **What this is:** kode berikutnya dicari, dicek, lalu order dibuat terpisah.
   - **Problem:** dua checkout bersamaan dapat memilih nomor sama sebelum unique constraint menolak salah satunya.
   - **Fix:** tangani duplicate-key dengan retry atau gunakan sequence/ID yang aman terhadap konkurensi.
   - **If we skip it:** sebagian checkout dapat gagal dengan error database saat trafik bersamaan.

12. **Validasi masih tersebar di banyak controller** (`app/Http/Controllers/CheckoutController.php:56-69`, `CartController.php:38-42`, `AdminPackageController.php:54-68, 121-136`, `AdminOrderController.php:65-87`)
   - **What this is:** setiap endpoint memakai `$request->validate()` inline.
   - **Problem:** aturan dan pesan error sulit dipakai ulang serta controller menanggung validasi dan logika bisnis sekaligus.
   - **Fix:** pindahkan endpoint yang stabil ke Form Request secara bertahap; mulai dari checkout dan paket admin.
   - **If we skip it:** perubahan aturan mudah tidak konsisten antar endpoint.

13. **File gambar lama tidak dibersihkan saat diganti** (`app/Http/Controllers/Admin/AdminPackageController.php:138-145`)
   - **What this is:** upload baru mengganti path database tetapi tidak menghapus file lama.
   - **Problem:** storage terus menumpuk file yang tidak lagi direferensikan.
   - **Fix:** hapus file lama setelah upload baru berhasil dan sebelum menyimpan path baru.
   - **If we skip it:** biaya storage dan daftar file yatim bertambah.

14. **Test database bergantung pada MySQL lokal yang sudah tersedia** (`phpunit.xml:19-24`)
   - **What this is:** test memakai `DB_CONNECTION=mysql` dan database `vantara_production_test`.
   - **Problem:** test tidak mandiri dan berisiko gagal atau menyentuh database yang salah bila konfigurasi lokal keliru.
   - **Fix:** gunakan SQLite in-memory bila kompatibel, atau dokumentasikan database test terpisah dan jalankan migrasi otomatis.
   - **If we skip it:** CI dan developer baru tidak dapat menjalankan test dengan konsisten.

15. **Cakupan test belum mencakup batas keamanan dan konkurensi** (`tests/Feature` secara keseluruhan)
   - **What this is:** ada test alur checkout, admin, laporan, landing page, dan biaya operasional.
   - **Problem:** belum terlihat test untuk akses invoice orang lain, brute force login, upload invalid, race stok, dan persistence storage.
   - **Fix:** tambahkan satu test feature untuk setiap kontrak keamanan dan cabang stok penting.
   - **If we skip it:** regresi keamanan dapat lolos tanpa terdeteksi.

### Nice to have

16. **README masih README bawaan Laravel** (`README.md:1-53`)
   - **What this is:** dokumentasi menjelaskan Laravel secara umum, bukan cara menjalankan Vantara.
   - **Problem:** tidak ada setup `.env`, key, migration, storage link, build frontend, kebutuhan database, atau akun demo.
   - **Fix:** ganti dengan panduan setup proyek dan deployment yang aman.
   - **If we skip it:** onboarding dan deployment manual lebih lambat serta rawan salah konfigurasi.

17. **Konfigurasi dan nomor kontak bisnis tersebar di controller/view** (`app/Http/Controllers/CheckoutController.php:160`, `resources/views/pages/order_success.blade.php`, `resources/views/admin/orders/show.blade.php`)
   - **What this is:** nomor WhatsApp, alamat, dan identitas bisnis ditulis langsung di beberapa file.
   - **Problem:** perubahan satu data harus dicari dan diubah di banyak tempat.
   - **Fix:** pindahkan ke config atau site settings yang sudah dipakai untuk konten landing.
   - **If we skip it:** informasi publik bisa tidak konsisten.

18. **Warning build asset WhatsApp belum ditangani** (`resources/css/vantara.css`, output `npm run build`)
   - **What this is:** Vite melaporkan `/images/whatsapp.png` tidak terselesaikan saat build.
   - **Problem:** asset masih bergantung pada URL runtime dan tidak diverifikasi oleh manifest.
   - **Fix:** letakkan asset di lokasi public yang memang dipanggil atau import asset melalui pipeline Vite.
   - **If we skip it:** ikon dapat hilang bila base URL atau deployment path berubah.

Temuan kecil lain tidak dimasukkan agar daftar tetap fokus pada 18 risiko yang dapat ditindaklanjuti.

## 4. Hal yang sudah baik

- Semua route admin sensitif berada di bawah `AdminMiddleware` (`routes/web.php:49-75`).
- Login melakukan validasi email, memeriksa `is_admin`, meregenerasi session setelah berhasil, dan logout meng-invalidate session (`app/Http/Controllers/Admin/AuthController.php:30-70`).
- Form Blade memakai CSRF token pada endpoint mutasi yang terlihat.
- Password model memakai cast `hashed` dan field password disembunyikan (`app/Models/User.php:13-30`).
- Upload paket dan landing page memvalidasi tipe gambar serta batas 5 MB.
- Checkout menyimpan order dan item dalam `DB::transaction()` (`app/Http/Controllers/CheckoutController.php:114-145`).
- Query listing katalog dan paket admin memakai pagination (`app/Http/Controllers/PackageController.php:79`, `app/Http/Controllers/Admin/AdminPackageController.php:33`).
- `.env`, `vendor`, `node_modules`, `public/storage`, log, dan cache PHPUnit tercakup di `.gitignore` (`.gitignore:1-24`).
- Ada test feature untuk checkout, admin, laporan, landing image, schema, dan operational cost.

## 5. Prioritas perbaikan

1. **Kecil:** matikan `APP_DEBUG` di Vercel, hapus APP_KEY dari `.env.example`, dan ganti password seeder.
2. **Sedang:** lindungi invoice customer dengan token akses acak dan tambahkan rate limiting login.
3. **Besar:** ubah reservasi stok menjadi atomik dengan lock/constraint dan test konkurensi.
4. **Sedang:** pindahkan upload production ke object storage persisten.
5. **Sedang:** satukan sumber kebenaran subtotal/pendapatan bersih untuk laporan, dashboard, dan breakdown kategori.
6. **Kecil:** siapkan database test terisolasi dan pulihkan eksekusi PHPUnit/Pint di lingkungan lokal/CI.
7. **Kecil:** tulis README setup proyek dan rapikan konfigurasi bisnis yang tersebar.

## 6. Hal yang belum dapat diverifikasi

- Status kerentanan dan paket usang Composer belum dapat diverifikasi karena `composer validate` (sekitar 30 detik), `composer audit` (sekitar 10 detik), dan `composer outdated --direct` (sekitar 10 detik) menggantung tanpa output.
- Status kerentanan npm belum dapat diverifikasi karena `npm audit --audit-level=low` gagal sekitar 10 detik dengan `getaddrinfo ENOTFOUND registry.npmjs.org`.
- Hasil `php artisan optimize:clear`, `about`, `route:list`, `config:cache`, `config:clear`, `route:cache`, dan `route:clear` belum terverifikasi karena setiap proses menggantung sekitar 25–30 detik. Cache tidak dapat dipastikan berhasil dibersihkan melalui Artisan.
- `vendor/bin/pint --test` menggantung sekitar 30 detik tanpa output.
- `php artisan test --compact` menggantung sekitar 30 detik tanpa output. Konfigurasi test sudah diperiksa dan menunjuk ke MySQL `127.0.0.1` dengan database terpisah `vantara_production_test`, bukan database utama.
- Smoke test HTTP belum memperoleh status karena server berhasil start tetapi request ke halaman utama, katalog, login admin, dan invoice timeout setelah 10 detik. Sandbox juga sempat menolak socket lokal.
- `git rev-parse --show-toplevel` berhasil, tetapi `git status --short` gagal dengan `fatal: this operation must be run in a work tree`; daftar perubahan dan file tracked belum dapat diverifikasi.
- Tidak dilakukan `composer install`, `npm install`, migrate apa pun, `migrate:fresh`, `db:wipe`, atau seed database utama.
- Isi secret `.env` tidak ditampilkan dan tidak diaudit nilainya; hanya nama konfigurasi yang diperiksa.
- Audit tidak membaca seluruh isi dependency `vendor`, `node_modules`, file desain, atau generated build output satu per satu.

## 7. Kesiapan push ke GitHub

**Status: Belum aman.** Folder workspace baru memiliki `.git` dan remote configuration, tetapi `git status --short` gagal dengan `fatal: this operation must be run in a work tree`; status tracked/untracked tidak dapat dipastikan.

Sebelum push pertama:

- Pastikan `.env` tidak masuk commit; `.gitignore` mencakup `.env`, vendor, node_modules, public/storage, log, dan cache PHPUnit.
- Pertahankan `APP_KEY` kosong di `.env.example`, `APP_DEBUG=false` di deployment, dan isi secret hanya melalui environment target.
- Perbaiki kondisi working tree Git sebelum commit pertama dan tinjau seluruh file baru serta file besar secara manual; audit ini tidak membuat repository atau remote.
- Periksa asset build, upload, dan file desain besar sebelum commit pertama.
- Perbarui README dengan setup, versi PHP/Node, database test, storage link, build, dan akun demo tanpa menaruh password production.
- Jalankan ulang audit dependency, Pint, PHPUnit, dan smoke test setelah proses Composer/Artisan serta koneksi database lokal dapat selesai tanpa timeout.

Lisensi yang paling sederhana adalah mempertahankan MIT bila pemilik proyek menyetujuinya. Branch awal yang disarankan: `main` untuk production dan branch kerja berawalan `codex/` atau `feature/`; tidak ada branch atau remote yang dibuat oleh audit ini.

## Verdict

Proyek memiliki fondasi Laravel yang cukup baik untuk aplikasi rental skala kecil, tetapi belum aman untuk production sebelum debug dimatikan, invoice publik diberi kontrol akses, kredensial default dihapus, dan race condition stok diperbaiki.

Lean: sekitar 2-3 helper/config source dapat dipusatkan dengan menggabungkan aturan subtotal, kontak bisnis, dan validasi yang berulang; tidak ada dependency besar yang bisa dihapus dengan bukti dari audit ini.

## Pertanyaan tindak lanjut

Temuan mana yang ingin diperbaiki lebih dulu?
