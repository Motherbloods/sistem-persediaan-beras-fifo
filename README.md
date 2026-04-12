# SiPadi — Sistem Informasi Persediaan Beras
## CV Santri Abadi Indonesia

Sistem informasi persediaan beras berbasis web menggunakan **Laravel** dengan metode **FIFO (First In, First Out)**.

---

## ⚙️ Teknologi

| Komponen | Versi |
|---|---|
| PHP | >= 8.2 |
| Laravel | 11.x |
| MySQL | >= 8.0 |
| Bootstrap | 5.3 |

---

## 📁 Struktur Penempatan File

Salin file-file dari output ke dalam project Laravel sesuai path berikut:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── JenisBerasController.php
│   │   ├── LaporanController.php
│   │   ├── MonitoringController.php
│   │   ├── StokKeluarController.php
│   │   ├── StokMasukController.php
│   │   ├── SupplierController.php
│   │   └── UserController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── FifoQueue.php
│   ├── JenisBeras.php
│   ├── Supplier.php
│   ├── StokKeluar.php
│   ├── StokMasuk.php
│   └── User.php
└── Services/
    └── FifoService.php

bootstrap/
└── app.php

database/
├── migrations/
│   └── 2026_01_01_000001_create_all_tables.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── JenisBerasSeeder.php
    ├── SupplierSeeder.php
    └── UserSeeder.php

resources/views/
├── auth/login.blade.php
├── dashboard/index.blade.php
├── errors/403.blade.php
├── jenis-beras/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── laporan/
│   ├── masuk.blade.php
│   ├── keluar.blade.php
│   ├── persediaan.blade.php
│   └── pdf/
│       ├── masuk.blade.php
│       ├── keluar.blade.php
│       └── persediaan.blade.php
├── layouts/app.blade.php
├── monitoring/index.blade.php
├── partials/_filter.blade.php
├── stok-masuk/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── show.blade.php
├── stok-keluar/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── show.blade.php
├── supplier/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── users/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── profil.blade.php

routes/
└── web.php
```

---

## 🚀 Langkah Instalasi

### 1. Buat project Laravel baru
```bash
composer create-project laravel/laravel sipadi
cd sipadi
```

### 2. Konfigurasi database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipadi_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Buat database
```sql
CREATE DATABASE sipadi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Salin semua file ke dalam project
Tempatkan file sesuai struktur di atas.

### 5. Jalankan migration + seeder
```bash
php artisan migrate --seed
```

Output yang diharapkan:
```
========================================
  Seeding: Sistem Persediaan Beras
  CV Santri Abadi Indonesia
========================================
  ✔ UserSeeder: 2 user berhasil dibuat.
  ✔ JenisBerasSeeder: 6 jenis beras berhasil dibuat.
  ✔ SupplierSeeder: 4 supplier berhasil dibuat.
========================================
  Seeding selesai!

  Login Admin:
  Email    : admin@santriabadi.com
  Password : admin123

  Login Petugas Gudang:
  Email    : gudang@santriabadi.com
  Password : gudang123
========================================
```

### 6. Daftarkan locale Bahasa Indonesia (untuk translatedFormat)
Di `config/app.php`:
```php
'locale' => 'id',
'faker_locale' => 'id_ID',
```

Kemudian install locale:
```bash
composer require laravelcollective/html
```

Atau gunakan Carbon locale di `AppServiceProvider.php`:
```php
use Carbon\Carbon;

public function boot(): void
{
    Carbon::setLocale('id');
}
```

### 7. Jalankan server
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🔑 Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | admin@santriabadi.com | admin123 |
| Petugas Gudang | gudang@santriabadi.com | gudang123 |

> **Penting:** Segera ganti password setelah pertama login!

---

## ✅ Fitur per Role

| Fitur | Admin | Gudang |
|---|---|---|
| Dashboard & statistik | ✅ | ✅ |
| Monitoring stok real-time | ✅ | ✅ |
| Input stok masuk | ✅ | ✅ |
| Input stok keluar (FIFO) | ✅ | ✅ |
| Kelola jenis beras | ✅ | ❌ |
| Kelola supplier | ✅ | ❌ |
| Kelola pengguna | ✅ | ❌ |
| Laporan + export PDF | ✅ | ❌ |
| Edit profil sendiri | ✅ | ✅ |

---

## 🔄 Cara Kerja FIFO

1. Saat **stok masuk** disimpan → otomatis membuat baris baru di tabel `fifo_queues` dengan `jumlah_tersisa = jumlah_masuk`.
2. Saat **stok keluar** diproses → `FifoService::prosesKeluar()` mengambil batch dari `fifo_queues` dengan urutan `tanggal_masuk ASC` (tertua duluan).
3. Batch dihabiskan satu per satu sampai kuota terpenuhi. Batch yang habis statusnya berubah ke `'habis'`.
4. Seluruh operasi FIFO dibungkus `DB::transaction()` + `lockForUpdate()` untuk mencegah race condition.

---

## 🗄️ Struktur Tabel

| Tabel | Keterangan |
|---|---|
| `users` | Akun pengguna sistem |
| `jenis_beras` | Master data produk beras |
| `suppliers` | Master data pemasok |
| `stok_masuks` | Transaksi penerimaan beras |
| `fifo_queues` | Antrian stok per batch (jantung FIFO) |
| `stok_keluars` | Transaksi distribusi beras |

---

*SiPadi © 2026 — Aprilia Pramudita / 220101004 / Universitas Duta Bangsa Surakarta*
