# Aplikasi Manajemen Film API

Ini adalah aplikasi API yang dibangun dengan Laravel 10 untuk mengelola database film, termasuk informasi tentang film, genre, pemeran, dan ulasan pengguna. Aplikasi ini juga dilengkapi dengan sistem otentikasi pengguna menggunakan JWT dan integrasi dengan Cloudinary untuk penyimpanan gambar poster film.

## Fitur Utama

*   **Manajemen Film**: Operasi CRUD (Create, Read, Update, Delete) untuk data film.
*   **Manajemen Genre**: Operasi CRUD untuk genre film.
*   **Manajemen Pemeran**: Operasi CRUD untuk data pemeran.
*   **Manajemen Peran Pengguna**: Operasi CRUD untuk peran pengguna (akses terbatas untuk admin).
*   **Manajemen Ulasan**: Pengguna terverifikasi dapat menambahkan dan memperbarui ulasan untuk film.
*   **Manajemen Profil Pengguna**: Pengguna terverifikasi dapat mengelola profil mereka.
*   **Otentikasi Pengguna**:
    *   Registrasi pengguna baru.
    *   Login pengguna dengan JWT (JSON Web Token).
    *   Logout pengguna.
    *   Verifikasi akun pengguna melalui OTP (One-Time Password).
    *   Regenerasi OTP.
*   **Unggah Gambar**: Poster film diunggah dan dikelola melalui Cloudinary.
*   **Middleware**:
    *   `isadmin`: Membatasi akses ke endpoint tertentu hanya untuk pengguna dengan peran admin.
    *   `isverified`: Membatasi akses ke endpoint tertentu hanya untuk pengguna yang telah memverifikasi akun mereka.

## Teknologi yang Digunakan

*   PHP 8.1
*   Laravel Framework 10
*   Tymon JWT-auth (untuk otentikasi API berbasis token)
*   Cloudinary Laravel (untuk manajemen unggah gambar)
*   Database: (Harap sebutkan database yang Anda gunakan, misal: MySQL, PostgreSQL, SQLite)

## Endpoint API Utama

Semua endpoint di-prefix dengan `/api/v1`.

### Otentikasi (`/auth`)

*   `POST /auth/register`: Registrasi pengguna baru.
    *   Body: `name`, `email`, `password`, `password_confirmation`
*   `POST /auth/login`: Login pengguna.
    *   Body: `email`, `password`
*   `GET /auth/me`: Mendapatkan detail pengguna saat ini (memerlukan token otentikasi).
*   `POST /auth/logout`: Logout pengguna (memerlukan token otentikasi).
*   `POST /auth/verifikasi-akun`: Memverifikasi akun pengguna dengan OTP (memerlukan token otentikasi).
    *   Body: `otp_code`
*   `POST /auth/generate-otp-code`: Membuat atau mengirim ulang OTP (memerlukan token otentikasi).

### Film (`/movie`)

*   `GET /movie`: Menampilkan semua film.
*   `POST /movie`: Menambahkan film baru (memerlukan token admin).
    *   Body (form-data): `title`, `summary`, `poster` (file gambar), `genre_id`, `year`
*   `GET /movie/{id}`: Menampilkan detail film berdasarkan ID.
*   `PUT /movie/{id}`: Memperbarui film berdasarkan ID (memerlukan token admin).
    *   Body (form-data): `title`, `summary`, `poster` (opsional, file gambar), `genre_id`, `year`
*   `DELETE /movie/{id}`: Menghapus film berdasarkan ID (memerlukan token admin).

### Genre (`/genre`)

*   `GET /genre`: Menampilkan semua genre.
*   `POST /genre`: Menambahkan genre baru (memerlukan token admin).
    *   Body: `name`
*   `GET /genre/{id}`: Menampilkan detail genre berdasarkan ID.
*   `PUT /genre/{id}`: Memperbarui genre berdasarkan ID (memerlukan token admin).
    *   Body: `name`
*   `DELETE /genre/{id}`: Menghapus genre berdasarkan ID (memerlukan token admin).

### Pemeran (`/cast`)

*   `GET /cast`: Menampilkan semua pemeran.
*   `POST /cast`: Menambahkan pemeran baru (memerlukan token admin).
    *   Body: `name`, `profile_image` (opsional, file gambar)
*   `GET /cast/{id}`: Menampilkan detail pemeran berdasarkan ID.
*   `PUT /cast/{id}`: Memperbarui pemeran berdasarkan ID (memerlukan token admin).
    *   Body: `name`, `profile_image` (opsional, file gambar)
*   `DELETE /cast/{id}`: Menghapus pemeran berdasarkan ID (memerlukan token admin).

### Relasi Pemeran-Film (`/cast-movie`)

*   `GET /cast-movie`: Menampilkan semua relasi pemeran dan film.
*   `POST /cast-movie`: Menambahkan relasi antara pemeran dan film (memerlukan token admin).
    *   Body: `cast_id`, `movie_id`

### Peran (`/role`) - Khusus Admin

*   `GET /role`: Menampilkan semua peran (memerlukan token admin).
*   `POST /role`: Menambahkan peran baru (memerlukan token admin).
    *   Body: `name`
*   `PUT /role/{id}`: Memperbarui peran (memerlukan token admin).
*   `DELETE /role/{id}`: Menghapus peran (memerlukan token admin).

### Profil (`/profile`)

*   `POST /profile`: Membuat atau memperbarui profil pengguna (memerlukan token pengguna terverifikasi).
    *   Body: `bio`, `social_media_url`, `profile_picture` (opsional, file gambar)

### Ulasan (`/reviews`)

*   `POST /reviews`: Membuat atau memperbarui ulasan untuk film (memerlukan token pengguna terverifikasi).
    *   Body: `movie_id`, `rating` (1-5), `comment`

## Petunjuk Instalasi

1.  **Clone Repositori**
    ```bash
    git clone <URL_REPOSITORI_ANDA>
    cd <NAMA_DIREKTORI_PROYEK>
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Setup File Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Kemudian, buka file `.env` dan sesuaikan konfigurasi berikut:
    *   `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (sesuaikan dengan konfigurasi database Anda).
    *   `CLOUDINARY_URL` atau `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET` (untuk integrasi Cloudinary).
    *   `JWT_SECRET` (Anda bisa menggeneratenya nanti).
    *   Pastikan `APP_URL` diisi dengan benar jika Anda berencana menggunakan fitur verifikasi email atau fitur lain yang bergantung padanya.

4.  **Generate Kunci Aplikasi**
    ```bash
    php artisan key:generate
    ```

5.  **Generate JWT Secret (jika belum ada di .env)**
    ```bash
    php artisan jwt:secret
    ```
    Ini akan menambahkan `JWT_SECRET` ke file `.env` Anda.

6.  **Jalankan Migrasi Database**
    ```bash
    php artisan migrate
    ```

7.  **Jalankan Seeder**
    Untuk mengisi data awal (pengguna admin dan peran):
    ```bash
    php artisan db:seed --class=RoleSeeder
    php artisan db:seed --class=UserSeeder
    ```
    Atau, jika `DatabaseSeeder.php` sudah dikonfigurasi untuk memanggil keduanya:
    ```bash
    php artisan db:seed
    ```

    Setelah menjalankan seeder, Anda dapat login sebagai admin dengan kredensial berikut:
    *   **Email**: `admin@mail.com`
    *   **Password**: `password`

8.  **Jalankan Aplikasi**
    ```bash
    php artisan serve
    ```
    Aplikasi akan berjalan di `http://localhost:8000` (atau port lain jika 8000 sudah digunakan).

## Kontribusi

Jika Anda ingin berkontribusi, silakan fork repositori ini dan buat pull request.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE). (Jika Anda memiliki file LICENSE di root proyek, jika tidak, Anda bisa menghapus bagian ini atau memilih lisensi lain).