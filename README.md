# PanganPeduli
Platform web untuk menghubungkan penyedia makanan berlebih dengan penerima manfaat dan lembaga sosial. Proyek ini merupakan prototipe lokal untuk portofolio, dengan alur donasi, pengambilan, dan distribusi makanan.

## Fitur
- Landing page responsif dengan ringkasan dampak dan donasi terbaru.
- Katalog donasi makanan dengan pencarian dan filter kategori.
- Formulir donasi untuk restoran, toko roti, katering, dan donatur.
- Status donasi: Tersedia, Dipesan, Diambil, dan Selesai.
- Dashboard admin sederhana untuk mengelola donasi dan status.
- Penyimpanan data menggunakan PHP + MySQL.
- Validasi input dasar dan prepared statements.

## Teknologi
- PHP 8.1+
- MySQL / MariaDB
- HTML5, CSS3, JavaScript
- PDO untuk akses database

## Instalasi lokal (XAMPP)
1. Ekstrak folder `PanganPeduli` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka `http://localhost/phpmyadmin`, lalu impor `database/panganpeduli.sql`.
4. Sesuaikan kredensial database di `config/database.php` jika diperlukan.
5. Buka `http://localhost/PanganPeduli/`.

## Login admin demo
- Email: `admin@panganpeduli.local`
- Password: `password`

Akun demo memakai password hash yang ditanam dalam seed SQL. Ganti password sebelum penggunaan publik.

## Catatan penting
Proyek ini adalah demonstrasi portofolio, bukan layanan donasi produksi. Jangan unggah data pribadi atau data penerima manfaat sungguhan. Sebelum digunakan publik, tambahkan HTTPS, CSRF protection, pembatasan percobaan login, verifikasi akun, audit log, kebijakan privasi, dan proses moderasi. Pengelolaan makanan perlu mengikuti aturan keamanan pangan setempat; sistem tidak menggantikan pemeriksaan kelayakan makanan.
