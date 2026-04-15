# My Personal Finance Management (Finansialku)

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 📋 Deskripsi

**My Personal Financial Management** adalah sistem informasi keuangan pribadi berbasis web yang membantu  mencatat, mengelola, dan memantau keuangan sehari-hari — baik uang tunai (cash) maupun saldo rekening bank — dengan tampilan yang bersih dan sederhana. Website ini dirancang untuk individu yang ingin mencatat keuangan pribadi mereka tanpa kerumitan. Sistem ini mendukung multi-user, di mana setiap pengguna hanya bisa mengakses dan mengelola data milik mereka sendiri.

### ✨ Fitur Utama


- **Cash Management**

  - Jenis pemasukan cash: Tambah, edit, hapus kategori (contoh: Gaji, Freelance, Bonus)
  - Pemasukan Cash: Catat, hapus, edit pemasukan tunai dengan tanggal dan catatan
  - Jenis pengeluaran cash: Tambah, hapus, edit kategori pengeluaran cash (Contoh: Makan, Transportasi) 
  - Pengeluaran cash: Catat, edit, hapus pengeluaran tunai
  - Saldo cash otomatis: Sistem menghitung otomatis sisa uang tunai

- **Bank Management**

  - Akun Bank: Kelola banyak rekening bank (BCA, BRI, BNI, Mandiri, dll)
  - Jenis pemasukan bank: Tambah, hapus, edit jenis pemasukan bank (Contoh: Gaji bulanan, Bonus, Freelance)
  - Pemasukan bank: Catat pemasukan rekening bank tertentu (Contoh: Bank BRI, saldo Rp. 5000.000, Gaji Bulanan)
  - Jenis pengeluaran bank: Tambah, hapus, edit jenis pengeluaran bank (Contoh: Topup E-Wallet, Pengisian token listrik, Bayar internet, dll)
  - Pengeluaran bank: Catat pengeluaran rekening bank
  - Saldo per Rekening: Sistem menghitung otomatis saldo tiap rekening bank secara terpisah

- **Dashboard**

  - Total Uang (Cash + semua saldo bank)
  - Ringkasan saldo per akun bank
  - Rekapitulasi pengeluaran bank berdasarkan jenis bulan lalu
  - Akses cepat ke halaman tambah transaksi

##  🛠️ Tech Stack
  - Backend: Laravel 11.x
  - Backend API: Laravel Restful API
  - Authentication: Laravel Breeze + Laravel Sanctum
  - Frontend: Laravel Blade, TailwindCSS 3.x
  - JavaScript: AlpineJS 3.x
  - Database: MySQL

## 🖼️ Screenshots

### Employee Dashboard
![Dashboard](docs/images/dashboard_employee.png)
*Dashboard dengan statistik lengkap dan grafik*

### Book Management
![Book Management](docs/images/books_employee.png)
*Manage data buku*

### Book Form
![Book Form](docs/images/add_books.png)
*Form penambahan buku*

### Categories Management
![Categories Management](docs/images/categories_employee.png)
*Manage data kategori*


### Employee Management
![Patient Detail](docs/images/employee_list.png)
*Manage data pegawai/staff*


### Student Dashboard
![Patient Detail](docs/images/dashboard_student.png)
*Dashboard siswa*

### Student Catalog
![Student Catalog](docs/images/catalog_student.png)
*List buku yang dapat dipinjam siswa*

### History Peminjaman
![Histroy Peminjaman](docs/images/history_borrowings.png)
*Peminjaman yang pernah dilakukan siswa*

### Profile
![Profile](docs/images/profile_student.png)
*Profile*

---

##  Prasyarat Instalasi

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + TailwindCSS
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Breeze + Laravel Sanctum
- **PHP**: 8.2+
- **Package Manager**: Composer, NPM

### Langkah Instalasi

#### 1. Clone Repository
```bash
git clone https://github.com/AnggitSeptiansyah/personal-finance.git
cd personal-finance
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

#### 3. Environment Setup
```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Environment Setup
```bash
# install laravel breze
composer require laravel/breeze --dev

php artisan breeze:install blade
```

#### 4. Laravel sanctum
```bash
# install laravel sanctum
php artisan install:api
```

#### 4. Konfigurasi Database

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_finance
DB_USERNAME=your_db_username
DB_PASSWORD=your_password
```

Buat database:
```bash
mysql -u root -p
```
```sql
CREATE DATABASE library_management;
EXIT;
```

## Run Migration and DB Seed
```bash
php artisan migrate

```


## Build Assets
```bash
npm run dev

```

## Jalankan server
```bash
php artisan serve
```

