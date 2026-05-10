# KCIC Licence Reminder Dashboard

Aplikasi **Licence Reminder** adalah sebuah platform berbasis web yang dibangun menggunakan **Laravel 10/11**. Aplikasi ini dirancang khusus untuk mempermudah pemantauan, pencatatan, dan pemberian notifikasi otomatis terkait masa kedaluwarsa lisensi (Subscription & Perpetual) dari berbagai vendor perangkat lunak (PT) di KCIC.

## Fitur Utama

1. **Dashboard & Visualisasi Statistik (Baru!):** Halaman ringkasan dengan diagram pie (aktif vs non-aktif, tipe lisensi) dan daftar "Segera Kedaluwarsa" untuk prioritas pantauan.
2. **Manajemen Lisensi (CRUD) Modern:** Tambah, lihat, ubah, dan hapus data lisensi dengan UI modal bergaya *floating* dan *pill badges* yang intuitif.
3. **Log Riwayat Pembaruan:** Melacak riwayat perubahan secara otomatis (Update History Log) setiap kali informasi lisensi diperbarui, ditampilkan dalam format "timeline" yang bersih.
4. **Pengingat Email Otomatis (Cron Job):** Mengirimkan peringatan melalui email berdasarkan batas waktu kedaluwarsa lisensi secara periodik dengan pengaturan email target yang dinamis.
5. **Navigasi Sidebar yang Responsif:** Sistem *sidebar* ramping yang secara otomatis bersembunyi di perangkat *mobile* (Bilingual: Bahasa Inggris (EN) & Bahasa Indonesia (ID)).
6. **Pencarian, Pengurutan, & Pagination Khusus:** Urutan angka "No" yang dinamis berdasarkan *sort descending/ascending*, serta fitur penyesuaian jumlah data per halaman.

---

## 🛠️ Persyaratan Sistem (Prerequisites)

Sebelum menjalankan project ini, pastikan komputer Anda sudah terpasang perangkat lunak berikut:

*   **PHP** (Minimal versi 8.1 atau lebih tinggi)
*   **Composer** (Manajer paket PHP)
*   **Node.js & NPM** (Opsional, jika ingin mengkompilasi *assets* di masa depan)
*   **Git** (Untuk *cloning* repositori)
*   **Laragon / XAMPP** (Sebagai penyedia web server dan database MySQL lokal)

---

## 🚀 Cara Menjalankan Project (Local Development)

Berikut adalah panduan lengkap dari awal untuk menjalankan project ini di komputer Anda (*Local PC*):

### 1. Clone Repository (Unduh Kode)
Buka terminal (atau Git Bash) dan jalankan perintah berikut untuk mengunduh project dari GitHub:
```bash
git clone https://github.com/USERNAME/NAMA-REPOSITORY.git
```
*(Catatan: Ganti URL di atas dengan URL repositori GitHub Anda yang sebenarnya).*

### 2. Masuk ke Folder Project
Setelah proses unduh selesai, masuk ke dalam folder project:
```bash
cd nama-folder-project-anda
```

### 3. Instalasi Dependensi PHP (Composer)
Jalankan perintah ini untuk mengunduh semua pustaka (pustaka bawaan Laravel dan pihak ketiga) yang dibutuhkan aplikasi:
```bash
composer install
```

### 4. Persiapan File Konfigurasi Lingkungan (*Environment*)
Salin file `.env.example` bawaan menjadi file `.env` baru yang akan digunakan oleh sistem Anda:
```bash
copy .env.example .env
```
*(Gunakan `cp .env.example .env` jika Anda menggunakan Mac/Linux/Git Bash).*

### 5. Buat Kunci Enkripsi Aplikasi (App Key)
Generate sebuah kunci keamanan (App Key) khusus untuk aplikasi Anda:
```bash
php artisan key:generate
```

### 6. Konfigurasi Database
1. Buka aplikasi **Laragon** (atau XAMPP) lalu nyalakan **Apache** dan **MySQL**.
2. Buka phpMyAdmin (atau aplikasi *database client* lainnya).
3. Buat sebuah database baru yang kosong, misalnya beri nama **`licencereminder`**.
4. Buka file `.env` yang ada di *code editor* Anda (misal: VS Code), temukan bagian koneksi *database*, lalu sesuaikan nilainya:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=licencereminder
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migrasi Database
Untuk membentuk kerangka tabel otomatis ke dalam *database* kosong yang tadi Anda buat, jalankan:
```bash
php artisan migrate
```

### 8. Masukkan Data Palsu / Awal (Seeder) - *Opsional*
Jika Anda ingin agar tabel langsung terisi dengan contoh data awal untuk proses *testing*, jalankan:
```bash
php artisan db:seed
```

### 9. Jalankan Web Server Lokal
Jalankan perintah berikut untuk menyalakan *server development*:
```bash
php artisan serve
```

### 🎉 Selesai!
Buka aplikasi browser Anda (Google Chrome / Edge) dan kunjungi alamat berikut:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## ⏰ Konfigurasi Email Pengingat Otomatis (Cron Job)

Aplikasi ini dilengkapi pengingat email otomatis yang dijadwalkan setiap jam 08:00 pagi.

**Untuk Testing Manual (Saat Ini Juga):**
Buka terminal baru di dalam project dan jalankan:
```bash
php artisan app:send-licence-reminders
```

**Untuk Testing Otomatis Bekerja Latar Belakang (Di Lokal):**
```bash
php artisan schedule:work
```

> **Catatan Penting:** Pastikan Anda sudah mengatur email pengirim (SMTP Setup) di dalam file `.env` (misal: menggunakan akun Gmail App Password) dan mengatur target email penerima di menu **Settings** pada halaman website.
