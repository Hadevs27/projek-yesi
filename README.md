# 🍔 YESI Resto - Smart Restaurant System

Sistem Pemesanan Restoran Pintar (Smart Restaurant Ordering System) yang dirancang secara canggih menggunakan arsitektur modern **Laravel 11** (Backend/Web Admin) dan **Flutter 3** (Mobile App Pelanggan). 

Sistem ini didesain khusus untuk efisiensi tinggi, keamanan transaksi *(Midtrans)*, serta ketahanan data *(Neon PostgreSQL)*.

---

## 🚀 Fitur Unggulan

### 📱 Sisi Pelanggan (Aplikasi Mobile - Flutter)
- **Guest Checkout:** Pelanggan tidak perlu repot membuat akun atau login. Cukup buka aplikasi, dan pesan.
- **Scan QR Meja:** Fitur pemindai QR Code untuk secara otomatis mendeteksi nomor/kode meja pelanggan.
- **Katalog Real-Time:** Daftar menu dan produk unggulan (*Best Sellers*) yang diatur secara terpusat dari Web Admin.
- **Keranjang Cerdas:** Mengelola banyak pesanan dalam satu waktu.
- **Pembayaran Instan (Midtrans Webview):** Mendukung pembayaran transfer bank, e-Wallet (Gopay/ShopeePay), dan QRIS dengan penyelesaian otomatis tanpa harus unggah bukti transfer.

### 💻 Sisi Pengelola (Web Admin - Laravel)
- **Manajemen Katalog:** Kelola kategori, harga, stok, dan gambar makanan dengan mudah.
- **Manajemen Pesanan:** Verifikasi pesanan, pengembalian stok otomatis jika ditolak, dan update status pesanan.
- **Laporan Otomatis:** Cetak rekapitulasi penghasilan berdasarkan rentang waktu tertentu.
- **Keamanan Enkripsi:** Akses Admin dilindungi dengan sistem sesi *(file session)* dan enkripsi *MD5* yang kuat.

### ⚙️ Sisi Infrastruktur & Keamanan Data
Sistem ini menggunakan **Neon PostgreSQL**, database utama berbasis awan (*Serverless Cloud*) yang melayani ribuan pesanan per detik dengan keamanan tinggi.

---

## 🛠️ Persyaratan Sistem (Requirements)

Untuk menjalankan proyek ini di *Localhost*, pastikan komputer Anda memiliki:
- **PHP** >= 8.3 (Disarankan menggunakan *Laragon* atau *XAMPP*)
- **Composer** v2+
- **Flutter SDK** >= 3.44.2 (Untuk *build* aplikasi mobile)
- **Android Studio** atau Emulator (Untuk pengujian aplikasi mobile)
- Koneksi Internet Stabil (Untuk API Midtrans dan Neon Database)

---

## ⚙️ Panduan Instalasi (Setup)

### 1. Menjalankan Backend (Laravel Web Admin)
1. Buka terminal di folder proyek (`d:/0-Projekorang/proyekyesi`).
2. Instal dependensi PHP:
   ```bash
   composer install
   ```
3. Pastikan Anda telah mengonfigurasi file `.env` dengan kredensial database *Neon* serta *Server Key* Midtrans.
4. Karena sistem ini menggunakan arsitektur *Session File* dan *Cache File*, jalankan:
   ```bash
   php artisan optimize:clear
   ```
5. Jalankan server web (Agar bisa diakses oleh HP yang berada dalam 1 WiFi):
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
6. Akses Web Admin di: `http://localhost:8000/admin` (Username: `admin`, Password: `admin`).

### 2. Menjalankan Frontend (Aplikasi Mobile Flutter)
1. Buka folder aplikasi mobile: `cd yesi_customer_app`.
2. Buka file konfigurasi API di `lib/services/api_service.dart`.
3. Ubah `baseUrl` menggunakan *IP Lokal Komputer (IPv4)* Anda jika Anda mengujinya di HP fisik, ATAU gunakan `10.0.2.2` jika menggunakan *Android Emulator*.
   ```dart
   static const String baseUrl = 'http://192.168.x.x:8000/api'; // Ganti dengan IP komputer Anda
   ```
4. Bersihkan memori dan rakit aplikasi:
   ```bash
   flutter clean
   flutter run      # Untuk uji coba langsung
   flutter build apk --release  # Untuk membuat file instalasi .apk
   ```

---

## 🔒 Catatan Penting
- Folder `public/admin_assets` digunakan untuk menyimpan *file* tampilan web. Jangan mengubah nama folder ini tanpa memperbarui konfigurasi di *View* (`.blade.php`).
- Jika Anda mendeploy proyek ini ke layanan *Hosting* (*Production*), ubah opsi `MIDTRANS_IS_PRODUCTION=true` di file `.env`.

---
*Dibuat dengan presisi tinggi demi kemudahan operasional restoran Anda.*
