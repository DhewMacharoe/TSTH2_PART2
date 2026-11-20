# Panduan Hosting & Konfigurasi Ekosistem TSTH2

Dokumen ini berisi panduan teknis untuk deployment / hosting 4 layanan web terpadu TSTH2.

---

## 1. Pemetaan Layanan & Database

| No | Folder Proyek | Port Dev | Rekomendasi Subdomain | Nama Database MySQL | Deskripsi Layanan |
|:---|:---|:---:|:---|:---|:---|
| 1 | `pengunjung/` | `8000` | `tsth2.id` / `www.tsth2.id` | `tsth2_pengunjung` | Portal Publik Utama, Berita, Riset, Galeri, Profil & CMS Web Admin |
| 2 | `logbook/` | `8001` | `logbook.tsth2.id` | `tsth2_logbook` | Sistem Pencatatan Logbook Karyawan & Monitoring Target Divisi |
| 3 | `warehouse/` | `8002` | `warehouse.tsth2.id` | `tsth2_warehouse` | Manajemen Inventaris Gudang GA, Lab RdI, & Alur Permintaan Barang |
| 4 | `katalog/` | `8003` | `katalog.tsth2.id` | `tsth2_katalog` | Katalog Publik Flora Herbal/Hortikultura, Koleksi & QR Accession Code |

---

## 2. Kredensial Akun Bawaan (Default Seeded Accounts)

Semua akun berikut telah di-seed ke masing-masing database dengan password standar: `password` (kecuali akun bawaan khusus katalog):

### A. Portal Pengunjung & Web CMS (`tsth2_pengunjung`)
- **Web Administrator**: `admin@tsth2.id` atau `web@tsth2.id` (Password: `password`)
- **Pimpinan / Direktur**: `pimpinan@tsth2.id` atau `dir@tsth2.id` (Password: `password`)

### B. Portal Logbook Karyawan (`tsth2_logbook`)
Data di-seed langsung dari dump riil sistem operasional TSTH2 (`logbook_dump.sql` berisi 230 aktivitas logbook riil). Seluruh password di-reset ke: `password`.
- **Superadmin Logbook**: `admin@gmail.com` atau `admin@tsth2.id` (Password: `password`)
- **Pimpinan TSTH2**: `pimpinan@gmail.com` atau `pimpinan@tsth2.id` (Password: `password`)
- **Kepala Departemen**:
  - `donni@gmail.com` (Donni Napitupulu — GA)
  - `maria@gmail.com` (Maria Elfiana — Riset & Inovasi)
  - `kadep@gmail.com` (Akun test default GA)
- **Kepala Divisi**:
  - `joy@gmail.com` (Joy Damanik — Operasional)
  - `ika@gmail.com` (Ika Yuliasari — GH/SH)
  - `alfonsus@gmail.com` (Alfonsus Sadewa — Genomic)
  - `panca@gmail.com` (Panca Sibuea — Hilirisasi)
  - `kadiv@gmail.com` (Akun test default Operasional)
- **Karyawan / Employee**:
  - `irvandi@gmail.com`, `welrkincon@gmail.com`, `dionisius@gmail.com` (Operasional)
  - `ester@gmail.com`, `miftah@gmail.com` (Genomic)
  - `natalia@gmail.com`, `herbinawan@gmail.com`, `srifani@gmail.com`, `timo@gmail.com` (GH/SH)
  - `abdon@gmail.com` (Hilirisasi)
  - `ko@gmail.com`, `karyawan@tsth2.id` (Akun test default)

### C. Portal Warehouse & Gudang (`tsth2_warehouse`)
- **PIC Gudang (GA)**: `gudang@tsth2.id` atau `pic@tsth2.id` (Password: `password`)
- **PIC Lab (RdI)**: `piclab@tsth2.id` (Password: `password`)
- **Staf Warehouse**: `wh@tsth2.id` (Password: `password`)
- **Karyawan Pemohon**: `karyawan@tsth2.id` atau `agro@tsth2.id` (Password: `password`)
- **Pimpinan Approval**: `pimpinan@tsth2.id` atau `dir@tsth2.id` (Password: `password`)

### D. Portal Katalog Flora & Tanaman (`tsth2_katalog`)
- **Admin Katalog Flora**: `katalog@tsth2.id` atau `admin@tsth2.id` (Password: `password`)
- **Kurator Herbarium**: `elisabeth.pasaribu@tsth2.test` (Password: `semangat`)
- **Kurator Lapangan**: `budi.herbal@tsth2.test` (Password: `herbal123`)

---

## 3. Langkah Instalasi di Server Produksi / Hosting

Jalankan perintah berikut di masing-masing folder proyek:

```bash
# 1. Masuk ke folder proyek (contoh: pengunjung)
cd pengunjung

# 2. Salin environment dan sesuaikan kredensial database hosting
cp .env.example .env
# Edit .env (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL)

# 3. Generate Application Key & Symlink Storage
php artisan key:generate
php artisan storage:link

# 4. Jalankan Migrasi & Database Seeder Lengkap
php artisan migrate:fresh --seed

# 5. Optimasi Cache Laravel untuk Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Ulangi langkah di atas untuk folder `logbook/`, `warehouse/`, dan `katalog/`.

---

## 4. Konfigurasi Cross-Service URL di `.env`

Agar navbar dan tombol integrasi antar layanan dapat berpindah dengan lancar di server hosting, sesuaikan variabel URL di masing-masing `.env`:

### Pada `pengunjung/.env`:
```env
APP_URL=https://tsth2.id
URL_LOGBOOK=https://logbook.tsth2.id
URL_WAREHOUSE=https://warehouse.tsth2.id
URL_KATALOG=https://katalog.tsth2.id
```

### Pada `logbook/.env`:
```env
APP_URL=https://logbook.tsth2.id
URL_PENGUNJUNG=https://tsth2.id
URL_WAREHOUSE=https://warehouse.tsth2.id
URL_KATALOG=https://katalog.tsth2.id
```

### Pada `warehouse/.env`:
```env
APP_URL=https://warehouse.tsth2.id
URL_PENGUNJUNG=https://tsth2.id
URL_LOGBOOK=https://logbook.tsth2.id
URL_KATALOG=https://katalog.tsth2.id
```

### Pada `katalog/.env`:
```env
APP_URL=https://katalog.tsth2.id
URL_PENGUNJUNG=https://tsth2.id
URL_LOGBOOK=https://logbook.tsth2.id
URL_WAREHOUSE=https://warehouse.tsth2.id
```

