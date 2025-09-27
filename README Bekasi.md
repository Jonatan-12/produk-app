# Produk App (Laravel + JWT + Blade)

Aplikasi CRUD Produk sederhana menggunakan **Laravel API (JWT Auth)** dan **Blade + Axios + SweetAlert2** untuk frontend.  
Fitur:

- Autentikasi login/logout dengan **JWT Token**
- CRUD Produk (Tambah, Edit, Hapus, Lihat)
- Protected API route menggunakan middleware `auth:api`
- SweetAlert2 untuk notifikasi modern

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js & NPM (opsional, jika mau compile asset Laravel Mix/Vite)
- Git (opsional)

---

## Setup Project

1. **Clone repo**
   bash
   git clone https://github.com/username/produk-app.git
   cd produk-app

2. Install dependencies\*\*
   bash
   composer install

3. **Copy `.env`**
   bash
   cp .env.example .env

4. **Generate key Laravel**
   bash
   php artisan key:generate

5. **Atur database di `.env`**
   env
   DB_DATABASE=produk_db
   DB_USERNAME=root
   DB_PASSWORD=

6. **Migrasi database**
   bash
   php artisan migrate

7. **Install JWT package & generate secret**
   bash
   composer require tymon/jwt-auth
   php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
   php artisan jwt:secret

---

## ▶ Menjalankan Aplikasi

1. Jalankan server Laravel:
   bash
   php artisan serve`

   Default: [http://127.0.0.1:8000](http://127.0.0.1:8000)

2. Akses halaman frontend:
   - Login dengan user yang ada di tabel `users`
   - Setelah login, token disimpan di `localStorage`
   - CRUD produk dapat dilakukan lewat tampilan Blade

---

## API Endpoint

| Method | Endpoint           | Deskripsi               |
| ------ | ------------------ | ----------------------- |
| POST   | `/api/login`       | Login user, return JWT  |
| POST   | `/api/logout`      | Logout (invalidate JWT) |
| GET    | `/api/me`          | Data user login         |
| GET    | `/api/produk`      | List produk             |
| POST   | `/api/produk`      | Tambah produk           |
| GET    | `/api/produk/{id}` | Detail produk           |
| PUT    | `/api/produk/{id}` | Update produk           |
| DELETE | `/api/produk/{id}` | Hapus produk            |

---

## Frontend

Frontend menggunakan:

- **Blade template**
- **Axios** untuk request API
- **SweetAlert2** untuk notifikasi login/logout/CRUD
- Token JWT disimpan di `localStorage` dan dikirim otomatis di header `Authorization`

---

## Testing

1. Register atau seed user di tabel `users` (gunakan `php artisan tinker` atau migration seeder).
2. Login dengan email + password via form.
3. CRUD produk bisa dilakukan setelah login.

---

## Struktur Project (penting)

app/
└── Http/
└── Controllers/
└── Api/
├── AuthController.php
├── ProdukController.php
└── ReportController.php
resources/
└── views/
└── produk.blade.php # UI frontend
routes/
└── api.php # API routes

---

## Akun Testing

- Default user ada di tabel `users`.
- Bisa buat akun manual:
  ```bash
  php artisan tinker
  >>> \App\Models\User::create([
         'name' => 'Admin',
         'email' => 'admin@mail.com',
         'password' => bcrypt('admin123')
     ]);
  ```

Login dengan:

- **Email**: `admin@mail.com`
- **Password**: `admin123`

---
