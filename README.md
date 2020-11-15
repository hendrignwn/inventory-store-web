## TokoKu

Diperuntukan untuk Tugas Akhir.

- Master Barang
- Master Jenis Barang
- Master Supplier
- Master Customer
- Stok Barang
- Pembelian Barang
- Transaksi Barang
- Laporan

## Cara Install

- Download XAMPP dengan PHP v7.3
- Download Composer
- Download Gitbash
- clone project ini dengan cara: `git clone https://github.com/HendriGnwn/inventory-store-web.git`
- kemudian masuk ke folder menggunakan terminal, ketik `composer install --ignore-platform-reqs`
- copy `.env.example` , dan rename copy-an tersebut menjadi `.env`
- Buat database
- Import database yang terletak pada folder `database/database.sql`
- sesuaikan atau konfigurasikan database dengan mengubah setting-an pada `.env`
- Jalankan `npm install`
- Jalankan `npm run dev`
- Jalankan `php artisan serve`
- Buka pada browser `http://localhost:8000`

## Kredensial

- <b>Admin</b>
- email: hendri.gnw@gmail.com
- password: admin123
- <b>Karyawan</b>
- email: hendri.karyawan@gmail.com
- password: admin123

## Author

Hendri Gunawan (@hendrignwn)
