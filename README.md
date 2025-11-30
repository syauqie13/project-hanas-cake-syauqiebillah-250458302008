# 🧁 Hana's Cake: Sistem Informasi Hybrid POS & E-Commerce

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**Sistem Manajemen Penjualan & Inventori Modern untuk Usaha Bakery**

[Demo](#preview-tampilan) • [Fitur](#-fitur-utama-core-modules) • [Instalasi](#-cara-instalasi-project) • [Dokumentasi](#-teknologi-yang-digunakan)

</div>

---

## 📋 Deskripsi Singkat Aplikasi

**Hana's Cake** adalah Sistem Informasi Manajemen Penjualan dan Inventori yang dirancang secara **hybrid**, mengintegrasikan fungsionalitas **Point of Sale (POS)** untuk transaksi offline dan platform **E-Commerce** untuk pemesanan online. 

### 🎯 Keunggulan Utama

Sistem ini dibangun khusus untuk mengatasi masalah **sinkronisasi stok bahan baku** pada usaha bakery, di mana setiap penjualan produk jadi **otomatis mengurangi bahan baku** sesuai dengan resep yang telah ditetapkan (**Recipe Management**).

> 💡 **Solusi Cerdas:** Tidak perlu lagi menghitung manual stok tepung, telur, atau mentega setiap kali menjual kue. Sistem melakukannya secara otomatis!

---

## ✨ Fitur Utama (Core Modules)

Sistem ini memiliki **10 modul utama** dengan fokus pada integrasi data real-time:

### 🏪 Fungsionalitas Inti

| Fitur | Deskripsi |
|-------|-----------|
| 🔄 **Sistem Hybrid POS & E-Commerce** | Pencatatan transaksi dari dua kanal berbeda dalam satu database terpadu |
| 📊 **Sinkronisasi Stok Resep** | Logika otomatis mengurangi stok bahan baku saat produk jadi terjual |
| 👥 **Multi Role User** | Hak akses berjenjang (Admin, Karyawan, Pelanggan) untuk menjaga keamanan sistem |
| 🎂 **Recipe Management** | Kelola resep produk dengan detail bahan baku yang dibutuhkan |
| 🛒 **Manajemen Pelanggan (CRM Dasar)** | Penyimpanan dan pelacakan riwayat pembelian pelanggan |
| 🏷️ **Manajemen Promo Dinamis** | Admin dapat membuat dan menerapkan diskon pada produk |

### 📈 Laporan & Kontrol

- 📊 **Dashboard KPI**: Ringkasan visual kinerja penjualan harian dan bulanan
- 📄 **Ekspor Laporan**: Opsi export data transaksi ke format **PDF** dan **Excel**
- 🔔 **Notifikasi Stok Minim**: Peringatan dalam aplikasi otomatis saat bahan baku menipis
- 📦 **Zona Pengiriman**: Manajemen ongkos kirim berdasarkan wilayah

---

## 🛠️ Teknologi yang Digunakan

<table>
  <thead>
    <tr>
      <th>Kategori</th>
      <th>Teknologi</th>
      <th>Peran</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Backend Framework</strong></td>
      <td><img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel"></td>
      <td>PHP Framework utama untuk logika bisnis, routing, dan ORM</td>
    </tr>
    <tr>
      <td><strong>Frontend Framework</strong></td>
      <td><img src="https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire&logoColor=white" alt="Livewire"> <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?logo=alpine.js&logoColor=white" alt="Alpine.js"></td>
      <td>Untuk membuat antarmuka real-time dan interaktif (terutama di modul POS)</td>
    </tr>
    <tr>
      <td><strong>Database</strong></td>
      <td><img src="https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white" alt="MySQL"> / <img src="https://img.shields.io/badge/MariaDB-10-003545?logo=mariadb&logoColor=white" alt="MariaDB"></td>
      <td>Basis data relasional untuk menyimpan data transaksi, stok, dan resep</td>
    </tr>
    <tr>
      <td><strong>Web Server</strong></td>
      <td><img src="https://img.shields.io/badge/Laravel_Herd-Latest-FF2D20?logo=laravel&logoColor=white" alt="Herd"> / <img src="https://img.shields.io/badge/Nginx-Latest-009639?logo=nginx&logoColor=white" alt="Nginx"></td>
      <td>Lingkungan server lokal yang cepat dan zero-configuration</td>
    </tr>
    <tr>
      <td><strong>Styling</strong></td>
      <td><img src="https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?logo=tailwind-css&logoColor=white" alt="Tailwind"></td>
      <td>Framework CSS untuk desain yang responsive dan modern</td>
    </tr>
    <tr>
      <td><strong>Additional Tools</strong></td>
      <td><img src="https://img.shields.io/badge/Vite-4.0-646CFF?logo=vite&logoColor=white" alt="Vite"> <img src="https://img.shields.io/badge/Composer-2.5-885630?logo=composer&logoColor=white" alt="Composer"></td>
      <td>Build tools dan dependency management</td>
    </tr>
  </tbody>
</table>

---

## 🚀 Cara Instalasi Project

### Prerequisites

Pastikan Anda telah menginstal:
- ✅ PHP (v8.2+)
- ✅ Composer
- ✅ Node.js/NPM (v16+)
- ✅ MySQL/MariaDB atau Laravel Herd/Laragon

### Langkah Instalasi

#### 1️⃣ Clone Repository

```bash
git clone https://github.com/syauqie13/hanas-cake-web.git
cd hanas-cake
```

#### 2️⃣ Instalasi Dependensi PHP

```bash
composer install
```

#### 3️⃣ Instalasi Dependensi Frontend

```bash
npm install
npm run dev
# atau npm run build jika deploy ke production
```

#### 4️⃣ Konfigurasi Environment

Duplikasi file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Generate Application Key:

```bash
php artisan key:generate
```

Atur koneksi database di file `.env` (pastikan nama database sudah dibuat di MySQL Anda):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hanas_cake
DB_USERNAME=root
DB_PASSWORD=
```

#### 5️⃣ Migrasi dan Seeder

Jalankan migrasi untuk membuat struktur tabel:

```bash
php artisan migrate
```

**(Opsional)** Jalankan seeder untuk mengisi data awal (user admin, produk, dll.):

```bash
php artisan db:seed
```

---

## ▶️ Cara Menjalankan Project

### Start Server Lokal

**Opsi 1: Menggunakan Laravel Herd/Laragon**
- Pastikan server MySQL/Apache/Nginx Anda berjalan
- Akses melalui URL yang diberikan oleh Herd/Laragon (contoh: `http://hanas-cake.test`)

**Opsi 2: Menggunakan Server Bawaan Laravel**

```bash
php artisan serve
```

### Akses Aplikasi

Buka browser dan akses alamat yang ditampilkan oleh terminal (biasanya `http://127.0.0.1:8000`).

### Jalankan Vite (untuk development)

Di terminal terpisah, jalankan:

```bash
npm run dev
```

---

## 👤 Akun Demo

Gunakan akun berikut untuk testing sistem:

| Role | Email | Password | URL | Akses |
|------|-------|----------|-----|-------|
| 🔴 **Admin** | `admin@hanascake.com` | `password` | `/admin/dashboard` | Full access ke semua modul |
| 🟡 **Karyawan** | `kasir@hanascake.com` | `password` | `/user/dashboard` | DAshboard, POS, Manajemen Produk, Transaksi |
| 🟢 **Pelanggan** | *(Daftar/Register di halaman depan)* | - | `/` | E-Commerce, Keranjang, Checkout |

> ⚠️ **Catatan Keamanan:** Pastikan untuk mengubah password default sebelum deploy ke production!

---

## 📸 Preview Tampilan

### Dashboard Admin
![Dashboard Admin](link-ke-screenshot-dashboard.png)

### Interface POS Karyawan
![POS Interface](link-ke-screenshot-pos.png)

### E-Commerce (Pelanggan)
![E-Commerce](link-ke-screenshot-ecommerce.png)

---

## 📁 Struktur Project

```
hanas-cake/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controller untuk routing
│   │   └── Livewire/          # Livewire components
│   ├── Models/                # Eloquent models
│   └── Services/              # Business logic services
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Data seeder
├── resources/
│   ├── views/                 # Blade templates
│   └── js/                    # Frontend JS/Alpine
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes (jika ada)
└── public/                    # Assets publik
```

---

## 🤝 Contributing

Kontribusi selalu terbuka! Jika Anda ingin berkontribusi:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📄 License

Project ini menggunakan lisensi **MIT License**. Lihat file `LICENSE` untuk detail lebih lanjut.

---

## 📞 Kontak & Support

Jika ada pertanyaan atau butuh bantuan:

- 📧 Email: syauqiebillah13@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/syauqie13/hanas-cake-web.git)
- 💬 Diskusi: [GitHub Discussions](https://github.com/syauqie13//hanas-cake-web/discussions)

---

<div align="center">

**Dibuat dengan ❤️ menggunakan Laravel & Livewire**

⭐ Jangan lupa berikan star jika project ini membantu Anda!

[⬆ Kembali ke atas](#-hanas-cake-sistem-informasi-hybrid-pos--e-commerce)

</div>
