<p align="center">
  <img src="https://ui-avatars.com/api/?name=Secure+Vote&background=0f172a&color=38bdf8&size=200&rounded=true" alt="SecureVote Logo">
</p>

# SecureVote: Sistem E-Voting Berbasis Enkripsi & Logika Proposisional

**SecureVote** adalah sebuah sistem informasi pemilihan (e-voting) terpusat berbasis web yang dikembangkan khusus untuk pemilihan **Gubernur ITSA PCR** (Information Technology Student Association - Politeknik Caltex Riau). Proyek ini dibangun menggunakan *framework* **Laravel 11**, dirancang untuk memastikan kerahasiaan, keamanan, dan integritas penuh dalam setiap tahap pemilihan.

Sistem ini diintegrasikan secara mendalam dengan konsep **Matematika Diskrit**, khususnya penerapan **Logika Proposisional** dalam arsitektur keamanan *login* dan validasi suara. 

## 🌟 Fitur Utama
1. **Otentikasi Berlapis Berbasis Logika Proposisional**
2. **Kerahasiaan Suara dengan Enkripsi AES-256-CBC**
3. **Pencegahan Double-Vote (Pemilihan Ganda)**
4. **Three-Tier Role Management** (Super Admin, Panitia KPU, dan Pemilih/Mahasiswa)
5. **UI/UX Modern (Deep Space Glassmorphism)**

---

## 🔐 Implementasi Logika Proposisional pada Otentikasi

Keunikan utama dari sistem SecureVote adalah penggunaan teori himpunan dan **Logika Proposisional** yang ketat pada saat pemilih melakukan *login* atau memberikan hak suaranya.

Dalam sistem kami, terdapat **4 Variabel Proposisi Utama**:
- **p**: NIM pengguna terdaftar di *database*.
- **q**: *Password* pengguna cocok (*hash matching*).
- **r**: Status akun pengguna dalam keadaan **Aktif** (`is_active = true`).
- **s**: Pengguna **belum memberikan suara** pada periode aktif (`is_voted = false`).

### Alur Keputusan Login (Truth Table Implementation)

Proses *login* divalidasi menggunakan konjungsi logis dari variabel di atas:

1. **Gagal Login (NIM Salah)**: `¬p`
   *Sistem memblokir akses karena NIM tidak dikenali.*
2. **Gagal Login (Password Salah)**: `p ∧ ¬q`
   *NIM dikenali, tetapi kata sandi salah.*
3. **Gagal Login (Akun Nonaktif)**: `p ∧ q ∧ ¬r`
   *Kredensial benar, tetapi akun ditangguhkan/diblokir oleh panitia.*
4. **Login Berhasil (Otentikasi Awal Valid)**: `p ∧ q ∧ r`
   *Kredensial benar dan akun aktif. Pengguna dapat masuk ke Dasbor Pemilih.*

### Alur Validasi Hak Suara (Voting Access)
Selain otentikasi awal, sistem juga menerapkan validasi tingkat kedua (*Voting Validator*) saat pengguna mencoba menekan tombol Coblos/Vote:

- **Hak Suara Sah**: `p ∧ q ∧ r ∧ s`
  *Pengguna aktif dan belum memilih. Formulir surat suara akan ditampilkan.*
- **Akses Ditolak (Sudah Memilih)**: `(p ∧ q ∧ r) ∧ ¬s`
  *Pengguna aktif tetapi **sudah memberikan suara**. Sistem langsung memblokir akses ke halaman pemilihan untuk mencegah manipulasi data (Double-Vote protection).*

---

## 🗄️ Implementasi Relasi Database (Eloquent ORM)

Arsitektur *database* SecureVote didesain dengan ketat menggunakan *Foreign Key* dan relasi antar tabel (RDBMS). Berikut adalah relasi utama yang digunakan dalam *Eloquent ORM* Laravel:

### 1. `Role` (One-to-Many) `User`
Setiap akun pengguna (`User`) wajib memiliki satu peran (`Role`), yang menentukan tingkat otoritas mereka (Admin, Panitia, atau Pemilih).
- **User `belongsTo` Role**: `$user->role->name`
- **Role `hasMany` Users**: `$role->users`

### 2. `VotingPeriod` (One-to-Many) `Candidate`
Setiap Kandidat Paslon terikat pada satu Periode Pemilihan secara eksklusif. Hal ini memungkinkan sistem menyimpan riwayat kandidat dari tahun-tahun sebelumnya tanpa bercampur dengan kandidat tahun aktif.
- **Candidate `belongsTo` VotingPeriod**
- **VotingPeriod `hasMany` Candidates**

### 3. `VotingPeriod` & `Candidate` (One-to-Many) `Vote`
Setiap suara (Vote) merekam `voting_period_id` dan `candidate_id`. Uniknya, ID Kandidat (*candidate_id*) juga dienkripsi ke dalam kolom `encrypted_choice` menggunakan metode **AES-256-CBC**, sehingga DBA (Database Administrator) yang melihat tabel `votes` tidak dapat membaca secara langsung siapa yang dipilih oleh seorang *voter* tanpa kunci aplikasi.

### 4. `User` (One-to-One / One-to-Many) `Vote`
- Sistem membatasi relasi seorang pemilih (`User`) hanya memiliki maksimal 1 suara (`Vote`) pada periode pemilihan yang sedang berlangsung (One-to-One secara fungsional dalam satu siklus).

---

## 🚀 Panduan Instalasi & Pengujian

### Prasyarat
- PHP >= 8.2
- Composer
- Database MySQL/MariaDB

### Langkah Instalasi
1. Lakukan *clone* repository ini: `git clone <repo-url>`
2. Masuk ke direktori aplikasi: `cd securevote_app`
3. Salin file environment: `cp .env.example .env`
4. Sesuaikan konfigurasi *database* di file `.env`.
5. *Install* dependensi: `composer install`
6. *Generate* App Key (Penting untuk algoritma enkripsi suara): `php artisan key:generate`
7. Lakukan migrasi beserta *Seeder* bawaan:
   ```bash
   php artisan migrate:fresh --seed
   ```
8. Jalankan server: `php artisan serve`

### Panduan Akun Uji Coba (Dummy Accounts)
*Seeder* secara otomatis membuat beberapa akun uji coba dengan *password default* `password123`.

- **Admin System**: `ADMIN01`
- **Panitia KPU**: `PANITIA01`
- **Pemilih/Voter**: `2501001`, `2501002`, `2501003`

Untuk pengujian perlindungan ganda (Logika `¬s`), Anda dapat masuk dengan akun pemilih, melakukan voting, lalu mencoba untuk login kembali dan mencoblos ulang.

---
*Dibuat untuk memenuhi tugas Projek Implementasi Matematika Diskrit dan Pengembangan Sistem Cerdas.*
