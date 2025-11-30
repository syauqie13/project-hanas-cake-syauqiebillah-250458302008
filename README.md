🧁 Hana's Cake: Sistem Informasi Hybrid POS & E-Commerce

Deskripsi Singkat Aplikasi

Hana's Cake adalah Sistem Informasi Manajemen Penjualan dan Inventori yang dirancang secara hybrid, mengintegrasikan fungsionalitas Point of Sale (POS) untuk transaksi offline dan platform E-Commerce untuk pemesanan online. Sistem ini dibangun khusus untuk mengatasi masalah sinkronisasi stok bahan baku pada usaha bakery, di mana setiap penjualan produk jadi otomatis mengurangi bahan baku sesuai dengan resep yang telah ditetapkan (Recipe Management).

Fitur Utama (Core Modules)

Sistem ini memiliki 10 modul utama dengan fokus pada integrasi data real-time:

Fungsionalitas Inti

Sistem Hybrid POS & E-Commerce: Pencatatan transaksi dari dua kanal berbeda dalam satu database.

Sinkronisasi Stok Resep: Logika otomatis mengurangi stok bahan baku saat produk jadi terjual.

Multi Role User: Hak akses berjenjang (Admin, Karyawan, Pelanggan) untuk menjaga keamanan sistem.

Manajemen Pelanggan (CRM Dasar): Penyimpanan dan pelacakan riwayat pembelian pelanggan.

Laporan & Kontrol

Dashboard KPI: Ringkasan visual kinerja penjualan harian dan bulanan.

Ekspor Laporan: Opsi export data transaksi ke format PDF dan Excel.

Manajemen Promo Dinamis: Admin dapat membuat dan menerapkan diskon pada transaksi.

Teknologi yang Digunakan

Kategori

Teknologi

Peran

Backend Framework

Laravel 10

PHP Framework utama untuk logika bisnis, routing, dan ORM.

Frontend Framework

Livewire 3 & Alpine.js

Untuk membuat antarmuka real-time dan interaktif (terutama di modul POS).

Database

MySQL / MariaDB

Basis data relasional untuk menyimpan data transaksi, stok, dan resep.

Web Server

Laravel Herd / Nginx

Lingkungan server lokal yang cepat dan zero-configuration.

Styling

Tailwind CSS

Framework CSS untuk desain yang responsive dan modern.

Cara Instalasi Project

Pastikan Anda telah menginstal PHP (v8.1+), Composer, Node.js/NPM, dan MySQL/Laragon/Herd sebelum memulai.

Clone Repository:

git clone [https://github.com/NamaAnda/nama-repo-anda.git](https://github.com/NamaAnda/nama-repo-anda.git)
cd hanas-cake


Instalasi Dependensi PHP:

composer install


Instalasi Dependensi Frontend:

npm install
npm run dev
# atau npm run build jika deploy ke production


Konfigurasi Environment:

Duplikasi file .env.example menjadi .env.

Buat Application Key: php artisan key:generate

Atur koneksi database di file .env (pastikan nama database sudah dibuat di MySQL Anda):

DB_DATABASE=hanas_cake_db
DB_USERNAME=root
DB_PASSWORD=


Migrasi dan Seeder:

Jalankan migrasi untuk membuat struktur tabel:

php artisan migrate


(Opsional) Jalankan seeder untuk mengisi data awal (user admin, produk, dll.):

php artisan db:seed


Cara Menjalankan Project

Start Server Lokal:
Jika menggunakan Laravel Herd/Laragon, cukup pastikan server MySQL/Apache/Nginx Anda berjalan.
Jika menggunakan server bawaan Laravel (untuk pengembangan):

php artisan serve


Akses Aplikasi:

Buka browser dan akses alamat yang ditampilkan oleh terminal (biasanya http://127.0.0.1:8000 atau URL yang diberikan Herd/Laragon).

(Opsional) Akun Demo

Role

Email

Password

URL

Admin

admin@hanascake.com

password

/admin/dashboard

Karyawan

kasir@hanascake.com

password

/karyawan/pos

Pelanggan

(Daftar/Register di halaman depan)



/

Preview Tampilan
