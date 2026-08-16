# PRODUCT REQUIREMENTS DOCUMENT (PRD)

# Pengembangan Aplikasi Mobile Customer – YESI

**Versi:** 1.1  
**Status:** Draft / Baseline Pengembangan  
**Tanggal:** 15 Agustus 2026

---

## 1. Ringkasan Produk

YESI merupakan sistem penjualan yang saat ini memiliki aplikasi web untuk kebutuhan operasional **Admin** dan **Kasir**, termasuk pengelolaan produk, kategori, pesanan, pembayaran, dan laporan.

Pengembangan pada PRD ini berfokus pada penambahan **aplikasi mobile khusus Customer/Pelanggan**. Aplikasi mobile bukan pengganti aplikasi web, tetapi menjadi kanal baru agar pelanggan dapat melakukan transaksi dari perangkat mobile.

Fitur utama baru pada pengembangan ini adalah **QR Meja**. Admin/Kasir dapat membuat dan mencetak QR Code untuk setiap meja melalui web. Customer cukup memindai QR Code meja menggunakan aplikasi mobile. Setelah QR berhasil dipindai, aplikasi mengetahui meja yang digunakan dan customer dapat langsung memilih produk serta membuat pesanan.

Customer **tidak perlu login atau membuat akun**. Data identitas pelanggan disimpan pada data pesanan ketika checkout.

Untuk pembayaran, customer dapat memilih:
- Transfer Bank;
- QRIS;
- Tunai kepada Kasir.

Arsitektur database yang digunakan:

- **Neon PostgreSQL** sebagai database utama dan sumber data yang benar (source of truth).
- **MySQL** sebagai database cadangan (backup/disaster recovery).
- Setiap perubahan penting pada Neon akan diteruskan ke MySQL secara **near-real-time**, sehingga MySQL tetap memiliki salinan data yang terbaru tanpa menjadikan kedua database sebagai sumber utama sekaligus.

---

# 2. Latar Belakang

Sistem YESI saat ini telah memiliki fungsi utama transaksi pada web. Namun, pelanggan masih membutuhkan pengalaman yang lebih praktis melalui perangkat mobile.

Pengembangan mobile dilakukan untuk:

1. Mempermudah pelanggan melihat produk.
2. Mempermudah pelanggan melakukan pemesanan.
3. Mempermudah pelanggan melakukan pembayaran.
4. Memungkinkan pelanggan melihat status pesanan.
5. Mengurangi ketergantungan pelanggan terhadap akses web.
6. Memisahkan pengalaman pelanggan dari fungsi operasional Admin dan Kasir.
7. Menyediakan arsitektur backend yang dapat digunakan oleh web dan mobile.

---

# 3. Tujuan Produk

## 3.1 Tujuan Utama

Membangun aplikasi mobile Customer yang memungkinkan pelanggan melakukan proses pembelian secara mandiri tanpa login, mulai dari melihat produk sampai melacak pesanan.

## 3.2 Tujuan Teknis

- Menyediakan REST API sebagai penghubung aplikasi mobile dengan backend.
- Menggunakan Neon PostgreSQL sebagai database utama.
- Menyediakan mekanisme sinkronisasi/penyalinan data Neon ke MySQL.
- Menjaga konsistensi data produk, stok, pesanan, pembayaran, dan status pesanan.
- Mempertahankan web Admin dan Kasir sebagai aplikasi operasional utama.
- Memisahkan hak akses Customer dengan Admin dan Kasir.

---

# 4. Sasaran Pengguna

## 4.1 Customer / Pelanggan

Customer menggunakan aplikasi mobile untuk:

- melihat produk;
- mencari produk;
- melihat detail produk;
- menambahkan produk ke keranjang;
- melakukan checkout;
- melakukan pembayaran;
- melihat status pesanan;
- membatalkan pesanan sesuai aturan;
- melihat informasi pesanan.

Customer tidak memiliki akun dan tidak perlu login.

## 4.2 Admin

Admin tetap menggunakan aplikasi web.

Fungsi Admin tetap berada di web, termasuk:

- pengelolaan sistem;
- pengelolaan produk;
- pengelolaan kategori;
- pengelolaan pengguna internal;
- laporan;
- fungsi administrasi lainnya.

## 4.3 Kasir

Kasir tetap menggunakan aplikasi web untuk:

- melihat pesanan;
- memproses transaksi;
- memproses pembayaran sesuai alur sistem;
- memperbarui status pesanan;
- mengelola kebutuhan operasional transaksi.

---

# 5. Scope Produk

## 5.1 Scope Mobile Customer

### A. Beranda

Customer dapat:

- melihat produk unggulan/produk terbaru;
- melihat kategori;
- melihat promosi/informasi jika tersedia;
- membuka detail produk.

### B. Katalog Produk

Customer dapat:

- melihat daftar produk;
- mencari produk;
- memfilter berdasarkan kategori;
- melihat harga;
- melihat stok/ketersediaan;
- membuka detail produk.

### C. Detail Produk

Menampilkan:

- nama produk;
- foto produk;
- deskripsi;
- harga;
- stok/ketersediaan;
- kategori;
- informasi lain yang dibutuhkan.

Customer dapat menambahkan produk ke keranjang.

### D. Keranjang

Customer dapat:

- melihat item yang dipilih;
- menambah jumlah;
- mengurangi jumlah;
- menghapus item;
- melihat subtotal;
- melihat total.

Keranjang dapat disimpan secara lokal pada perangkat sebelum checkout.

### E. Scan QR Meja

Customer dapat menggunakan fitur kamera pada aplikasi untuk memindai QR Code yang tersedia pada meja.

QR Code harus berisi identifier meja yang aman dan tidak mudah dimanipulasi. Setelah QR berhasil dipindai:

1. Aplikasi memvalidasi QR ke backend.
2. Backend memastikan meja masih aktif.
3. Aplikasi menampilkan identitas meja.
4. Session/order customer dikaitkan dengan meja tersebut.
5. Customer dapat mulai memilih produk.

Contoh:

```text
Meja 05
Status: Aktif

[ Mulai Pesan ]
```

Customer tidak perlu mengetik nomor meja secara manual.

### F. Checkout

Customer mengisi:

- nama;
- nomor WhatsApp/telepon;
- alamat;
- catatan tambahan jika diperlukan.

Tidak ada proses login.

### G. Pembayaran

Customer memilih salah satu metode pembayaran pada saat checkout:

#### 1. Transfer Bank

Customer memilih metode Transfer Bank.

Sistem menampilkan:
- nama bank;
- nomor rekening;
- nama pemilik rekening;
- nominal yang harus dibayar;
- nomor order sebagai referensi.

Customer dapat mengunggah bukti transfer bila business rule membutuhkan verifikasi manual.

Status pembayaran awal:

```text
MENUNGGU_VERIFIKASI_TRANSFER
```

Setelah pembayaran diverifikasi oleh Admin/Kasir, status menjadi:

```text
PAID
```

#### 2. QRIS

Customer memilih QRIS.

Sistem menampilkan QR pembayaran yang digunakan oleh sistem pembayaran.

Flow:

```text
Customer
   ↓
Pilih QRIS
   ↓
Tampilkan QRIS
   ↓
Customer melakukan pembayaran
   ↓
Payment Gateway / Verifikasi
   ↓
Update status pembayaran
```

Jika QRIS menggunakan Midtrans, proses pembayaran dan callback harus dilakukan melalui backend.

#### 3. Tunai ke Kasir

Customer memilih pembayaran tunai.

Status awal:

```text
UNPAID
```

Order diteruskan ke Kasir dengan keterangan:

```text
Metode Pembayaran: CASH
Status: MENUNGGU PEMBAYARAN DI KASIR
```

Customer kemudian membayar langsung ke Kasir.

Kasir melakukan konfirmasi pembayaran melalui web.

Setelah dikonfirmasi:

```text
PAID
```

### G. Pembayaran

### H. Status Pesanan

Customer dapat melihat:

- nomor pesanan;
- tanggal pesanan;
- total;
- status pembayaran;
- status pesanan.

Contoh status:

1. Menunggu Pembayaran
2. Pembayaran Berhasil
3. Diproses
4. Dikirim / Siap Diambil
5. Selesai
6. Dibatalkan

Status final mengikuti business rule yang diterapkan pada sistem existing.

### I. Lacak Pesanan

Karena customer tidak login, pelacakan pesanan menggunakan kombinasi:

- nomor pesanan;
- nomor WhatsApp/nomor telepon.

Sistem harus memvalidasi kedua informasi tersebut sebelum menampilkan detail pesanan.

### J. Pembatalan Pesanan

Customer dapat mengajukan pembatalan hanya pada status yang diizinkan sistem.

Aturan pembatalan ditentukan oleh backend dan tidak boleh hanya bergantung pada aplikasi mobile.

---

# 6. Out of Scope

Fungsi berikut tidak masuk ke aplikasi mobile Customer:

- login customer;
- register customer;
- reset password;
- dashboard Admin;
- dashboard Kasir;
- CRUD produk oleh customer;
- CRUD kategori;
- laporan administrasi;
- manajemen user internal;
- pengelolaan kasir;
- pengaturan sistem;
- fungsi internal operasional Admin/Kasir.

Admin dan Kasir tetap menggunakan web.

---

# 7. Prinsip Arsitektur

## 7.1 Satu Database Utama

Neon menjadi **source of truth**.

Artinya:

> Data transaksi, produk, stok, pembayaran, dan status pesanan yang dianggap benar adalah data yang berada di Neon.

## 7.2 MySQL Sebagai Database Cadangan

MySQL tidak menjadi sumber utama transaksi.

MySQL digunakan sebagai:

- backup;
- disaster recovery copy;
- arsip pemulihan.

## 7.3 Near-Real-Time Backup

Perubahan pada Neon diteruskan ke MySQL sesegera mungkin.

Alur sederhana:

```text
Aplikasi
   |
   v
Backend API
   |
   v
Neon PostgreSQL
   |
   | perubahan data
   v
Sync / Replication Worker
   |
   v
MySQL Backup
```

Targetnya adalah **near-real-time**, bukan menjadikan MySQL dan Neon sebagai dua database aktif yang sama-sama menerima transaksi utama.

---

# 8. Arsitektur Sistem

```text
                         ┌─────────────────────────┐
                         │     Neon PostgreSQL     │
                         │   PRIMARY DATABASE      │
                         │   SOURCE OF TRUTH       │
                         └────────────┬────────────┘
                                      │
                              REST API / Backend
                                      │
                     ┌────────────────┴────────────────┐
                     │                                 │
              ┌──────▼───────┐                 ┌──────▼─────────┐
              │   WEB APP    │                 │  MOBILE APP    │
              │              │                 │                │
              │ Admin        │                 │ Customer       │
              │ Kasir        │                 │ Guest          │
              └──────────────┘                 └────────────────┘

                         Neon Change / Sync
                                      │
                                      ▼
                           ┌──────────────────┐
                           │  MySQL Backup    │
                           │  Disaster        │
                           │  Recovery       │
                           └──────────────────┘
```

---

# 9. Peran Sistem

| Platform | Role | Fungsi |
|---|---|---|
| Web | Admin | Administrasi sistem |
| Web | Kasir | Operasional transaksi |
| Mobile | Customer | Pembelian dan tracking pesanan |
| Backend API | System | Integrasi data dan business logic |
| Neon | Database | Sumber data utama |
| MySQL | Backup | Salinan cadangan |

---

# 10. User Flow Customer

## 10.1 Flow Utama

```text
Buka Aplikasi
    ↓
Beranda
    ↓
Lihat Produk
    ↓
Detail Produk
    ↓
Tambah ke Keranjang
    ↓
Keranjang
    ↓
Checkout
    ↓
Isi Data Customer
    ↓
Konfirmasi Order
    ↓
Pembayaran
    ↓
Order Berhasil
    ↓
Nomor Pesanan
    ↓
Lacak Pesanan
```

## 10.2 Flow Scan QR Meja

```text
Customer Duduk di Meja
        ↓
Buka Mobile YESI
        ↓
Pilih Scan QR
        ↓
Scan QR Meja
        ↓
Backend Validasi QR
        ↓
Meja Ditemukan
        ↓
Tampilkan "Meja 05"
        ↓
Customer Pilih Produk
        ↓
Keranjang
        ↓
Checkout
```

## 10.3 Flow Pembayaran

### Transfer Bank

```text
Checkout
   ↓
Pilih Transfer
   ↓
Create Order
   ↓
Neon
   ↓
Tampilkan Rekening + Nominal
   ↓
Customer Transfer
   ↓
Upload Bukti (jika diperlukan)
   ↓
Kasir/Admin Verifikasi
   ↓
PAID
```

### QRIS

```text
Checkout
   ↓
Pilih QRIS
   ↓
Create Order
   ↓
Generate / Tampilkan QRIS
   ↓
Customer Membayar
   ↓
Payment Callback / Webhook
   ↓
PAID
```

### Tunai

```text
Checkout
   ↓
Pilih Tunai
   ↓
Create Order
   ↓
MENUNGGU PEMBAYARAN DI KASIR
   ↓
Customer Bayar ke Kasir
   ↓
Kasir Konfirmasi di Web
   ↓
PAID
```

Setelah pembayaran berhasil:

```text
PAID
 ↓
Order Diproses
 ↓
Kasir melihat order di Web
 ↓
Customer melihat status di Mobile
```

## 10.4 Flow Tracking

```text
Menu Lacak Pesanan
        ↓
Input Nomor Pesanan
        +
Nomor WhatsApp
        ↓
Backend Validasi
        ↓
Pesanan Ditemukan?
     /       \
   Tidak      Ya
    ↓         ↓
 Error     Detail Order
              ↓
        Status Pesanan
```

---

# 11. Functional Requirements

## FR-001 Beranda

Sistem harus menampilkan halaman beranda yang berisi minimal:

- daftar kategori;
- produk;
- produk terbaru/unggulan jika tersedia;
- navigasi ke halaman katalog.

## FR-002 Katalog Produk

Sistem harus menyediakan daftar produk yang berasal dari database utama.

Customer dapat membuka detail masing-masing produk.

## FR-003 Pencarian Produk

Customer dapat mencari produk berdasarkan nama atau atribut pencarian yang ditentukan.

## FR-004 Filter Produk

Customer dapat memfilter produk berdasarkan kategori dan filter lain yang tersedia.

## FR-005 Detail Produk

Sistem harus menampilkan informasi produk secara lengkap.

## FR-006 Keranjang

Customer dapat:

- menambahkan produk;
- mengubah quantity;
- menghapus produk;
- melihat total.

Sistem harus memastikan quantity tidak melebihi stok yang tersedia.

## FR-007 QR Meja

Sistem harus menyediakan QR Code unik untuk setiap meja.

QR Code harus:
- memiliki identifier meja;
- dapat divalidasi oleh backend;
- dapat diaktifkan/nonaktifkan;
- dapat dicetak dari web;
- dapat dipindai menggunakan kamera aplikasi mobile.

Setelah dipindai, order/session customer harus terhubung dengan meja yang bersangkutan.

## FR-017 Checkout

Sistem harus meminta data:

- nama customer;
- nomor telepon/WhatsApp;
- alamat;
- catatan bila diperlukan.

## FR-008 Pembuatan Order

Backend harus membuat nomor order unik.

Contoh:

```text
ORD-20260815-0001
```

Format final dapat ditentukan pada tahap implementasi.

## FR-009 Validasi Stok

Saat checkout, backend wajib memvalidasi stok terbaru pada Neon.

Customer tidak boleh membuat order melebihi stok.

## FR-010 Pembayaran

Sistem harus membuat transaksi pembayaran melalui backend dan integrasi Midtrans.

Credential rahasia payment gateway tidak boleh disimpan di aplikasi mobile.

## FR-011 Payment Callback

Backend harus menerima callback/webhook dari payment gateway dan memperbarui data pembayaran.

## FR-012 Tracking

Customer dapat melakukan tracking menggunakan:

- nomor order;
- nomor telepon/WhatsApp.

## FR-013 Pembatalan

Sistem harus menerapkan aturan pembatalan berdasarkan status order.

## FR-014 Order History Lokal

Aplikasi dapat menyimpan nomor order yang pernah dibuat pada perangkat agar customer dapat mengaksesnya kembali.

Fitur ini bukan pengganti akun customer.

## FR-015 Sinkronisasi Status

Perubahan status order dari Web Admin/Kasir harus dapat terlihat pada Mobile setelah data berhasil diperbarui pada backend.

---

# 12. Database Requirements

## 12.1 Neon PostgreSQL

Neon menyimpan data operasional utama seperti:

**Connection String Development / Environment:**

```text
postgresql://neondb_owner:npg_rH23DlEhFmQZ@ep-royal-sunset-ay77t8wj-pooler.c-5.us-east-2.aws.neon.tech/neondb?sslmode=require&channel_binding=require
```

**Catatan keamanan:** connection string di atas mengandung credential database. Pada implementasi aplikasi, jangan menuliskannya langsung di source code, Git repository, dokumentasi publik, atau aplikasi mobile. Simpan menggunakan environment variable seperti `DATABASE_URL` dan segera rotasi password/credential apabila string ini pernah dibagikan ke pihak lain.

Data operasional utama:

- products;
- categories;
- users internal;
- orders;
- order_items;
- payments;
- order status;
- konfigurasi yang dibutuhkan sistem;
- data lain yang digunakan web dan mobile.

Struktur tabel final harus mengikuti database existing setelah proses migrasi dan mapping.

## 12.2 Data Meja dan QR

Database utama harus memiliki data meja, minimal:

```text
table_id
table_code
table_name / table_number
qr_token
status
created_at
updated_at
```

Status meja contoh:

```text
ACTIVE
INACTIVE
MAINTENANCE
```

QR Code sebaiknya tidak menyimpan data sensitif secara langsung. QR cukup membawa token atau identifier meja yang divalidasi oleh backend.

## 12.3 Order dan Meja

Order perlu menyimpan relasi meja jika order berasal dari QR meja.

Contoh:

```text
order_id
order_number
table_id
customer_name
customer_phone
customer_note
payment_method
payment_status
order_status
```

Order yang berasal dari customer mobile harus menyimpan `table_id` agar Kasir mengetahui meja tujuan pesanan.

## 12.4 MySQL Backup

MySQL menyimpan salinan data penting yang dibutuhkan untuk pemulihan.

Minimal mencakup:

- produk;
- kategori;
- order;
- detail order;
- pembayaran;
- status;
- data internal yang diperlukan untuk restore.

## 12.3 Identitas Data

Setiap entitas harus memiliki identifier yang konsisten.

Contoh:

- product_id;
- category_id;
- order_id;
- order_item_id;
- payment_id.

Identifier harus tetap konsisten antara Neon dan MySQL backup.

---

# 13. Sinkronisasi Neon ke MySQL

## 13.1 Tujuan

Menjaga MySQL memiliki salinan data terbaru dari Neon sehingga sistem dapat melakukan pemulihan bila database utama bermasalah.

## 13.2 Alur

```text
Neon
  ↓
Perubahan Data
  ↓
Event / CDC / Sync Process
  ↓
Validasi
  ↓
MySQL Backup
  ↓
Catat hasil sinkronisasi
```

## 13.3 Jenis Perubahan

Minimal harus mampu menangani:

- INSERT;
- UPDATE;
- DELETE jika data memang boleh dihapus secara permanen.

## 13.4 Status Sinkronisasi

Sistem dapat memiliki status:

```text
PENDING
PROCESSING
SUCCESS
FAILED
RETRYING
```

## 13.5 Penanganan Kegagalan

Jika sinkronisasi gagal:

1. Data di Neon tetap menjadi data utama.
2. Sistem mencatat error.
3. Proses melakukan retry.
4. Kegagalan tidak boleh mengubah atau merusak data utama di Neon.
5. Admin teknis dapat melihat log sinkronisasi.

## 13.6 Prinsip Konsistensi

Tidak boleh ada proses yang membuat MySQL menjadi sumber utama baru hanya karena sinkronisasi mengalami keterlambatan.

---

# 14. REST API

Mobile tidak boleh terhubung langsung ke database.

Arsitektur:

```text
Flutter
   ↓
REST API
   ↓
Backend
   ↓
Neon
```

## 14.1 Endpoint Produk

```http
GET /api/products
GET /api/products/{id}
GET /api/categories
GET /api/categories/{id}/products
```

## 14.2 Endpoint Keranjang

Keranjang dapat dikelola lokal pada aplikasi. Jika server-side cart dibutuhkan, endpoint dapat disediakan:

```http
GET    /api/cart
POST   /api/cart/items
PUT    /api/cart/items/{id}
DELETE /api/cart/items/{id}
```

## 14.3 Endpoint Order

```http
POST /api/orders
GET  /api/orders/{order_number}
POST /api/orders/track
POST /api/orders/{order_number}/cancel
```

## 14.4 Endpoint Meja dan QR

```http
GET  /api/tables/{table_code}
POST /api/tables/{table_code}/validate
```

Endpoint pembuatan dan pengelolaan QR dilakukan melalui Web Admin/Kasir sesuai hak akses:

```http
POST /api/admin/tables
POST /api/admin/tables/{id}/qr
PUT  /api/admin/tables/{id}
GET  /api/admin/tables
```

## 14.5 Endpoint Pembayaran

```http
POST /api/payments/create
GET  /api/payments/{order_number}
POST /api/payments/webhook
POST /api/payments/{order_number}/confirm-cash
POST /api/payments/{order_number}/verify-transfer
```

Endpoint verifikasi tunai/transfer hanya boleh digunakan oleh Admin/Kasir sesuai permission.

## 14.5 Prinsip API

- menggunakan HTTPS pada production;
- validasi input di server;
- menggunakan response JSON;
- memiliki HTTP status code yang benar;
- tidak mengembalikan data sensitif;
- tidak mengekspos credential;
- memiliki rate limiting untuk endpoint yang sensitif;
- menggunakan logging.

---

# 15. Format Response API

Contoh response berhasil:

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": []
}
```

Contoh error:

```json
{
  "success": false,
  "message": "Produk tidak ditemukan",
  "error_code": "PRODUCT_NOT_FOUND"
}
```

Format final dapat disesuaikan dengan standar backend yang dipilih.

---

# 16. Aturan Customer Tanpa Login

Karena customer tidak menggunakan akun:

## 16.1 Tidak Ada

- password;
- session login customer;
- profile account;
- forgot password;
- role customer account.

## 16.2 Identitas Order

Identitas customer melekat pada order:

```text
customer_name
customer_phone
customer_address
customer_note
```

## 16.3 Tracking

Tracking wajib meminta setidaknya dua informasi:

```text
order_number
customer_phone
```

Hal ini mencegah orang yang hanya mengetahui nomor order untuk melihat seluruh informasi pesanan.

---

# 17. Pembayaran

## 17.1 Prinsip

Midtrans hanya berkomunikasi melalui backend.

Jangan menyimpan:

- Server Key;
- credential rahasia;

di Flutter.

## 17.2 Flow

```text
Flutter
   ↓
POST /api/orders
   ↓
Neon
   ↓
POST /api/payments/create
   ↓
Midtrans
   ↓
Customer Payment
   ↓
Midtrans Webhook
   ↓
Backend
   ↓
Neon
```

## 17.3 Status Pembayaran

Contoh:

```text
PENDING
PAID
FAILED
EXPIRED
CANCELLED
```

Status final menyesuaikan integrasi Midtrans yang digunakan.

---

# 18. Status Order

Contoh state machine:

```text
PENDING_PAYMENT
       │
       ▼
     PAID
       │
       ▼
   PROCESSING
       │
       ▼
   SHIPPING / READY
       │
       ▼
    COMPLETED
```

Status alternatif:

```text
CANCELLED
FAILED
EXPIRED
```

Backend harus menjadi pihak yang menentukan transisi yang valid.

---

# 19. Business Rules

## BR-001 QR Meja

Setiap meja harus memiliki QR Code yang unik.

## BR-002 Validasi Meja

QR yang dipindai customer harus divalidasi oleh backend sebelum order dibuat.

## BR-003 Order Berbasis Meja

Order dari mobile harus menyimpan meja asal order.

## BR-004 Metode Pembayaran

Customer dapat memilih:
- Transfer Bank;
- QRIS;
- Tunai ke Kasir.

## BR-005 Konfirmasi Tunai

Pembayaran tunai hanya menjadi `PAID` setelah Kasir melakukan konfirmasi di web.

## BR-006 Konfirmasi Transfer

Transfer manual hanya menjadi `PAID` setelah bukti/verifikasi dinyatakan valid sesuai business rule.

## BR-007 QRIS

Status pembayaran QRIS harus mengikuti hasil payment gateway/webhook jika menggunakan integrasi payment gateway.

## BR-008 Satu Source of Truth

Neon adalah database utama.

## BR-009 Backup

MySQL hanya digunakan sebagai database cadangan.

## BR-010 Sinkronisasi

Perubahan data pada Neon diteruskan ke MySQL secara near-real-time.

## BR-011 Customer Access

Customer hanya dapat mengakses order yang telah diverifikasi menggunakan informasi order yang benar.

## BR-012 Status Order

Status order ditentukan backend dan diperbarui oleh proses resmi sistem.

# 20. Keamanan

## 20.1 API Security

Backend harus:

- melakukan validasi input;
- menggunakan prepared query/ORM;
- melakukan authorization sesuai endpoint;
- menerapkan rate limiting;
- mencatat aktivitas penting;
- menghindari informasi sensitif di response.

## 20.2 Database Security

Credential database:

- tidak boleh berada di source code;
- menggunakan environment variables;
- tidak boleh dikirim ke aplikasi mobile.

## 20.3 Payment Security

Credential Midtrans hanya berada di backend.

## 20.4 Data Customer

Data customer yang ditampilkan harus dibatasi sesuai kebutuhan.

Customer tidak boleh melihat:

- data customer lain;
- data internal;
- informasi admin/kasir;
- credential;
- data database.

---

# 21. Teknologi yang Direkomendasikan

## Mobile

```text
Flutter
Dart
```

## Backend

Bisa menggunakan salah satu pendekatan berikut:

### Opsi A – Backend modern

```text
Next.js
TypeScript
Drizzle ORM
REST API
```

### Opsi B – Backend existing

```text
PHP
REST API
```

Pilihan final harus disesuaikan dengan strategi migrasi web existing.

## Database utama

```text
Neon PostgreSQL
```

## Database backup

```text
MySQL
```

## Payment

```text
Midtrans
```

## Push Notification (opsional)

```text
Firebase Cloud Messaging
```

---

# 22. Non-Functional Requirements

## NFR-001 Performance

API harus memiliki waktu respons yang baik untuk operasi umum seperti:

- daftar produk;
- detail produk;
- tracking order.

## NFR-002 Availability

Sistem harus tetap dapat melayani transaksi selama Neon tersedia.

## NFR-003 Reliability

Kegagalan sinkronisasi MySQL tidak boleh menghentikan transaksi utama.

## NFR-004 Scalability

API harus dapat dikembangkan untuk menerima pertambahan customer dan transaksi.

## NFR-005 Maintainability

Struktur backend, API, dan database harus terpisah dari UI mobile.

## NFR-006 Security

Credential database dan payment gateway tidak boleh ditanam di mobile.

## NFR-007 Backup

Data utama di Neon harus memiliki salinan cadangan di MySQL.

## NFR-008 Logging

Sistem harus memiliki log untuk:

- order;
- payment;
- webhook;
- error API;
- sinkronisasi database.

---

# 23. Struktur Aplikasi Mobile

Contoh struktur navigasi:

```text
Home
Produk
Pesanan
Cart
```

Tidak ada menu Account.

## Home

- Banner/informasi
- Kategori
- Produk pilihan

## Produk

- Search
- Filter
- Product list
- Product detail

## Pesanan

- Lacak pesanan
- Pesanan yang tersimpan lokal

## Cart

- Item
- Quantity
- Total
- Checkout

---

# 24. Struktur Backend

Contoh:

```text
backend/
├── api/
│   ├── products/
│   ├── categories/
│   ├── orders/
│   ├── payments/
│   └── tracking/
│
├── modules/
│   ├── product/
│   ├── order/
│   ├── payment/
│   └── synchronization/
│
├── database/
│   └── neon/
│
├── services/
│   ├── midtrans/
│   └── mysql-backup/
│
└── logs/
```

Struktur final dapat berubah sesuai framework yang dipilih.

---

# 25. Integrasi Web Existing

Web Admin dan Kasir tetap digunakan.

Target arsitektur akhir:

```text
                Neon
                 │
          Backend / API
           /           \
          /             \
     Web Admin       Web Kasir
          \
           \
        Mobile Customer
```

Jika web existing masih PHP, proses migrasi dapat dilakukan bertahap.

---

# 26. Strategi Migrasi Database

Karena database existing masih MySQL, migrasi ke Neon tidak boleh dilakukan tanpa perencanaan.

## Tahap 1 – Audit

Periksa:

- tabel;
- primary key;
- foreign key;
- tipe data;
- query;
- stored procedure;
- trigger;
- relasi;
- transaksi;
- penggunaan fungsi MySQL khusus.

## Tahap 2 – Mapping

Membuat mapping:

```text
MySQL table
      ↓
PostgreSQL table
```

## Tahap 3 – Migrasi Schema

Membuat struktur PostgreSQL yang setara.

## Tahap 4 – Migrasi Data

Memindahkan data existing dari MySQL ke Neon.

## Tahap 5 – Validasi

Membandingkan:

- jumlah data;
- relasi;
- total transaksi;
- stok;
- status.

## Tahap 6 – Switch

Setelah valid:

```text
Neon = Primary
MySQL = Backup
```

---

# 27. Strategi Pengembangan

## Phase 1 – Audit Existing System

- audit database MySQL;
- audit modul web;
- audit alur checkout;
- audit Midtrans;
- audit status order;
- audit struktur user.

## Phase 2 – Database Migration

- desain PostgreSQL;
- migrasi data;
- validasi data;
- tetapkan Neon sebagai primary.

## Phase 3 – Backend API

- product API;
- category API;
- order API;
- payment API;
- tracking API;
- cancellation API.

## Phase 4 – Backup System

- mekanisme perubahan data;
- sinkronisasi Neon → MySQL;
- retry;
- log;
- monitoring.

## Phase 5 – Flutter Customer

- setup Flutter;
- home;
- catalog;
- detail;
- cart;
- checkout;
- payment;
- tracking.

## Phase 6 – Integrasi

- integrasi API;
- integrasi Midtrans;
- integrasi status order;
- integrasi web Admin;
- integrasi web Kasir.

## Phase 7 – Testing

- API test;
- mobile test;
- payment test;
- order test;
- backup test;
- recovery test.

## Phase 8 – Release

- build Android;
- build iOS;
- deployment backend;
- konfigurasi database;
- monitoring.

---

# 28. Testing Requirements

## 28.1 Functional Testing

Minimal menguji:

- produk tampil;
- search bekerja;
- filter bekerja;
- cart bekerja;
- checkout bekerja;
- order terbentuk;
- payment bekerja;
- status berubah;
- tracking bekerja;
- pembatalan mengikuti aturan.

## 28.2 API Testing

Menggunakan:

- Postman;
- Insomnia;
- automated API test jika tersedia.

## 28.3 Payment Testing

Menggunakan environment sandbox Midtrans sebelum production.

## 28.4 Backup Testing

Simulasikan:

```text
Neon tersedia
↓
Order masuk
↓
MySQL mendapatkan data
```

Kemudian:

```text
Neon gagal
↓
Gunakan backup MySQL
↓
Data dapat direstore
```

## 28.5 Synchronization Testing

Test:

- INSERT;
- UPDATE;
- DELETE;
- duplicate event;
- retry;
- sync failure;
- connection loss.

---

# 29. Acceptance Criteria

## AC-001 Produk

Given customer membuka aplikasi,

When aplikasi berhasil terhubung ke API,

Then daftar produk tampil dari database utama.

## AC-002 Keranjang

Given customer memilih produk,

When menekan tambah ke cart,

Then produk masuk ke keranjang dengan quantity yang benar.

## AC-003 Checkout

Given cart berisi produk,

When customer mengisi data checkout dan melakukan konfirmasi,

Then order dibuat di Neon.

## AC-004 Pembayaran

Given order telah dibuat,

When customer menyelesaikan pembayaran,

Then payment status diperbarui melalui backend/webhook.

## AC-005 Tracking

Given customer memiliki nomor order dan nomor WhatsApp yang benar,

When customer melakukan tracking,

Then detail dan status order ditampilkan.

## AC-006 Admin/Kasir

Given order berhasil dibuat dari mobile,

When Admin/Kasir membuka web,

Then order dapat dilihat melalui sistem operasional.

## AC-007 Status

Given Kasir mengubah status order,

When status tersimpan di Neon,

Then customer dapat melihat status terbaru melalui mobile.

## AC-008 Backup

Given data berhasil masuk ke Neon,

When proses sinkronisasi berjalan,

Then data tersebut tersedia pada MySQL backup dalam waktu near-real-time.

## AC-009 Failure Handling

Given sinkronisasi MySQL gagal,

When sistem mendeteksi kegagalan,

Then transaksi pada Neon tetap aman dan proses sinkronisasi dapat melakukan retry.

## AC-010 QR Meja

Given Admin/Kasir membuat QR untuk Meja 05,

When Customer scan QR tersebut,

Then mobile menampilkan Meja 05 dan order berikutnya terhubung ke meja tersebut.

## AC-011 Pembayaran Tunai

Given Customer memilih Tunai,

When order dibuat,

Then status pembayaran menjadi menunggu pembayaran di Kasir dan Kasir dapat mengonfirmasi pembayaran melalui Web.

## AC-012 Pembayaran Transfer

Given Customer memilih Transfer,

When Customer menyelesaikan transfer sesuai instruksi,

Then order berada pada status menunggu verifikasi sampai Admin/Kasir menyatakan pembayaran valid.

## AC-013 Pembayaran QRIS

Given Customer memilih QRIS,

When pembayaran berhasil dan webhook/payment verification diterima,

Then status pembayaran diperbarui menjadi PAID.

## AC-014 Security

Given customer tidak login,

When customer mencoba melihat order yang bukan miliknya,

Then backend menolak akses.

---

# 30. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Migrasi MySQL → PostgreSQL gagal | Tinggi | Audit dan testing migrasi |
| Perbedaan tipe data | Sedang | Mapping schema |
| Query MySQL tidak kompatibel | Tinggi | Refactor query |
| Sinkronisasi backup gagal | Tinggi | Retry + monitoring |
| Data order tidak konsisten | Sangat tinggi | Neon sebagai source of truth |
| Credential bocor | Sangat tinggi | Environment variables |
| Payment callback gagal | Tinggi | Retry + reconciliation |
| Stok race condition | Tinggi | Validasi transaksi di backend |
| Network mobile lambat | Sedang | Timeout, retry, loading state |
| Customer menyalahgunakan tracking | Sedang | Validasi nomor order + nomor HP |

---

# 31. Monitoring

Sistem perlu memonitor:

- kesehatan API;
- error API;
- error payment;
- webhook;
- jumlah order;
- kegagalan sinkronisasi;
- status backup;
- waktu sinkronisasi terakhir.

Contoh:

```text
Last Sync:
2026-08-15 17:10:23

Sync Status:
SUCCESS

Pending Sync:
0

Failed Sync:
0
```

---

# 32. Recovery / Disaster Recovery

Jika Neon mengalami masalah:

```text
1. Hentikan sementara operasi yang berisiko.
2. Periksa kondisi database utama.
3. Ambil data dari MySQL backup.
4. Restore data ke Neon atau database pemulihan.
5. Validasi data.
6. Aktifkan kembali backend.
7. Verifikasi web dan mobile.
```

MySQL backup tidak digunakan sebagai database utama secara normal.

---

# 33. Environment

## Development

```text
Flutter
    ↓
Development API
    ↓
Neon Development Database
    ↓
MySQL Backup Development
```

## Staging

```text
Flutter / Web
    ↓
Staging API
    ↓
Neon Staging
    ↓
MySQL Backup
```

## Production

```text
Mobile Customer
       ↓
Production API
       ↓
Neon Production
       ↓
MySQL Disaster Recovery
```

Environment production tidak boleh menggunakan credential development.

---

# 34. KPI Produk

Setelah dirilis, metrik yang dapat dipantau:

- jumlah customer menggunakan mobile;
- jumlah order melalui mobile;
- conversion checkout;
- payment success rate;
- rata-rata waktu checkout;
- jumlah order dibatalkan;
- error API;
- keberhasilan sinkronisasi backup;
- waktu rata-rata sinkronisasi Neon → MySQL.

---

# 35. Definition of Done

Fitur dianggap selesai apabila:

- requirement sudah diimplementasikan;
- API sudah diuji;
- UI mobile sudah diuji;
- integrasi database berjalan;
- payment sandbox berhasil;
- tracking berhasil;
- Admin dan Kasir dapat melihat order mobile;
- backup Neon → MySQL berjalan;
- retry sinkronisasi berfungsi;
- tidak ada credential rahasia di mobile;
- error handling tersedia;
- dokumentasi API tersedia;
- dokumentasi deployment tersedia.

---

# 36. Prioritas Fitur

## P0 – Wajib

- scan QR meja;
- katalog;
- detail produk;
- cart;
- checkout;
- pembuatan order;
- payment Transfer;
- payment QRIS;
- payment Tunai;
- tracking;
- status order;
- REST API;
- Neon primary;
- MySQL backup;
- sinkronisasi near-real-time;
- integrasi web Admin/Kasir.

## P1 – Penting

- pencarian;
- filter kategori;
- pembatalan;
- local order history;
- push notification.

## P2 – Pengembangan Lanjutan

- promo;
- voucher;
- rekomendasi produk;
- wishlist;
- review;
- loyalty program;
- analitik customer.

---

# 37. Keputusan Arsitektur Final

Arsitektur final yang menjadi acuan pengembangan:

```text
                         ┌──────────────────────┐
                         │   NEON POSTGRESQL     │
                         │   PRIMARY DATABASE    │
                         │   SOURCE OF TRUTH     │
                         └───────────┬──────────┘
                                     │
                                Backend API
                                     │
                    ┌────────────────┼────────────────┐
                    │                │                │
                    ▼                ▼                ▼
              Mobile Customer   Web Admin        Web Kasir
                   Flutter       Existing          Existing

                                     │
                                     │ Near-Real-Time
                                     │ Sync
                                     ▼
                           ┌────────────────────┐
                           │   MYSQL BACKUP     │
                           │ Disaster Recovery  │
                           └────────────────────┘
```

### Prinsip final

1. **Customer hanya menggunakan mobile.**
2. **Customer memulai pemesanan dengan scan QR meja.**
3. **Admin dan Kasir tetap menggunakan web.**
4. **Customer tidak perlu login.**
5. **Mobile tidak pernah mengakses database secara langsung.**
6. **Semua akses mobile melalui REST API.**
7. **Neon adalah database utama.**
8. **MySQL adalah database cadangan.**
9. **Data Neon disalin ke MySQL secara near-real-time.**
10. **Neon tetap menjadi sumber data yang paling dipercaya.**
11. **Kegagalan sinkronisasi MySQL tidak boleh merusak transaksi utama.**
12. **Data meja, order, pembayaran, stok, dan status harus memiliki satu sumber kebenaran.**
13. **Web dan mobile harus menggunakan data operasional yang sama.**
14. **Metode pembayaran Customer adalah Transfer, QRIS, atau Tunai ke Kasir.**

---

# 38. Catatan Implementasi

PRD ini merupakan baseline produk dan arsitektur. Sebelum coding penuh, perlu dilakukan audit terhadap sistem YESI existing untuk memastikan:

- struktur tabel MySQL;
- relasi antar tabel;
- file PHP yang digunakan web;
- business rule checkout;
- logika stok;
- status pesanan;
- integrasi Midtrans;
- struktur user Admin dan Kasir;
- query MySQL yang harus dimigrasikan ke PostgreSQL;
- kebutuhan migrasi data ke Neon.

Setelah audit tersebut, dokumen berikutnya yang disarankan adalah:

1. **Database Design / ERD**
2. **Software Design Document (SDD)**
3. **API Specification**
4. **User Flow**
5. **System Architecture**
6. **Technical Task Breakdown**
7. **Migration Plan MySQL → PostgreSQL**
8. **Backup & Synchronization Design**

---

# 39. Ringkasan Satu Kalimat

> **YESI dikembangkan menjadi sistem dengan web untuk Admin dan Kasir serta mobile tanpa login untuk Customer, menggunakan Neon PostgreSQL sebagai database utama dan MySQL sebagai database cadangan yang diperbarui secara near-real-time melalui mekanisme sinkronisasi.**
