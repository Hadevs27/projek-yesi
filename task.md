# Task List Pengembangan Mobile Customer YESI

Berdasarkan PRD `PRD_YESI_Mobile_Customer_Neon_MySQL.md`, berikut adalah fase-fase pengerjaan yang perlu dilakukan dalam pengembangan sistem ini:

## Phase 1 – Audit Existing System
- [ ] Audit database MySQL
- [ ] Audit modul web
- [ ] Audit alur checkout
- [ ] Audit Midtrans
- [ ] Audit status order
- [ ] Audit struktur user

## Phase 2 – Database Migration
- [ ] Desain PostgreSQL (Neon)
- [ ] Migrasi data dari MySQL ke Neon
- [ ] Validasi data
- [ ] Tetapkan Neon sebagai primary database

## Phase 3 – Backend API
- [ ] Product API
- [ ] Category API
- [ ] Order API
- [ ] Payment API
- [ ] Tracking API
- [ ] Cancellation API

## Phase 4 – Backup System
- [ ] Mekanisme perubahan data
- [ ] Sinkronisasi Neon → MySQL
- [ ] Retry
- [ ] Log
- [ ] Monitoring

## Phase 5 – Flutter Customer
- [ ] Setup Flutter
- [ ] Home
- [ ] Catalog
- [ ] Detail
- [ ] Cart
- [ ] Checkout
- [ ] Payment
- [ ] Tracking

## Phase 6 – Integrasi
- [ ] Integrasi API
- [ ] Integrasi Midtrans
- [ ] Integrasi status order
- [ ] Integrasi web Admin
- [ ] Integrasi web Kasir

## Phase 7 – Testing
- [ ] API test
- [ ] Mobile test
- [ ] Payment test
- [ ] Order test
- [ ] Backup test
- [ ] Recovery test

## Phase 8 – Release
- [ ] Build Android
- [ ] Build iOS
- [ ] Deployment backend
- [ ] Konfigurasi database
- [ ] Monitoring
