<p align="center">
  <img src="https://ui-avatars.com/api/?name=Secure+Vote&background=0f172a&color=38bdf8&size=200&rounded=true" alt="SecureVote Logo">
</p>

# SecureVote: Sistem E-Voting Berbasis Himpunan & Logika Proposisional

**SecureVote** adalah sebuah sistem informasi pemilihan (e-voting) terpusat berbasis web yang dikembangkan khusus untuk pemilihan **Gubernur ITSA PCR** (Information Technology Student Association - Politeknik Caltex Riau). Proyek ini dibangun menggunakan *framework* **Laravel 11**, dirancang untuk memastikan kerahasiaan, keamanan, dan integritas penuh dalam setiap tahap pemilihan.

Sistem ini diintegrasikan secara mendalam dengan konsep **Matematika Diskrit**, khususnya penerapan **Teori Himpunan & Logika Proposisional** dalam arsitektur keamanan *login* dan validasi suara. 

## 🌟 Fitur Utama
1. **Otentikasi Berlapis Berbasis Teori Himpunan & Logika Proposisional**
2. **Kerahasiaan Suara dengan Enkripsi AES-256-CBC**
3. **Pencegahan Double-Vote (Pemilihan Ganda)**
4. **Three-Tier Role Management** (Super Admin, Panitia KPU, dan Pemilih/Mahasiswa)
5. **UI/UX Modern (Deep Space Glassmorphism)**

---

## 🔐 Implementasi Teori Himpunan & Logika Proposisional pada Sistem Login

Keunikan utama dari sistem SecureVote adalah penggunaan landasan **Matematika Diskrit** yang ketat, khususnya **Teori Himpunan** dan **Logika Proposisional** pada saat pengguna melakukan *login* ke dalam sistem.

### 1. Definisi Teori Himpunan (Set Theory)
Sistem memandang entitas data pengguna sebagai elemen-elemen dalam himpunan tertentu:
- **Himpunan Semesta (U):** Seluruh data mahasiswa ITSA PCR yang tercatat di sistem.
- **Himpunan A:** Himpunan entitas mahasiswa yang memberikan kredensial (NIM & Password) yang valid.
- **Himpunan B:** Himpunan entitas mahasiswa yang memiliki status akun **Aktif** (`is_active = true`).
- **Himpunan C:** Himpunan entitas mahasiswa yang **Belum Memilih** (`is_voted = false`).

Untuk dapat menembus gerbang *login* dengan sukses, seorang pengguna ($x$) secara matematis harus berada pada irisan dari Himpunan A dan Himpunan B: 
$x \in (A \cap B)$

Sedangkan untuk berhak melakukan **Pencoblosan (Voting)**, pengguna wajib berada pada irisan ketiga himpunan tersebut:
$x \in (A \cap B \cap C)$

### 2. Formulasi Logika Proposisional (Propositional Logic)
Proses validasi logika kode *backend* Laravel diterjemahkan secara harfiah dari batasan Himpunan di atas. Kami mendefinisikan 4 variabel proposisi utama:
- **p**: NIM pengguna terdaftar di *database*.
- **q**: *Password* pengguna cocok (*hash matching*).
- **r**: Status akun pengguna dalam keadaan Aktif (`is_active = true`).
- **s**: Pengguna belum memberikan suara pada periode berjalan (`is_voted = false`).

### Alur Keputusan Login Berbasis Tabel Kebenaran (Truth Table)
Sistem menggunakan konjungsi ( $\land$ ) dan negasi ( $\lnot$ ) logis untuk mengatur status akses:

1. **Gagal Login (NIM Tidak Ditemukan)**: $\lnot p$
   *Sistem langsung menolak karena proposisi identitas ($p$) bernilai **False**.*
2. **Gagal Login (Password Salah)**: $p \land \lnot q$
   *Identitas benar, tetapi otentikasi sandi ($q$) bernilai **False**.*
3. **Gagal Login (Akun Nonaktif)**: $p \land q \land \lnot r$
   *Kredensial sepenuhnya benar, tetapi otoritas sistem menetapkan akun ditangguhkan ($r$ bernilai **False**).*
4. **Login Berhasil (Otentikasi Awal Valid)**: $p \land q \land r$
   *Seluruh kondisi bernilai **True**. Pengguna diizinkan masuk ke Dasbor Pemilih.*

### Lapis Kedua: Logika Validasi Hak Suara (Voting Access)
Setelah pengguna masuk (*login*), sistem tidak langsung memberikan akses kertas suara. Ada *Middleware Validator* yang memeriksa proposisi sisa:

- **Hak Suara Sah (Akses Kertas Suara Diizinkan)**: $p \land q \land r \land s$
  *Seluruh nilai proposisi adalah **True**. Pengguna dipersilakan untuk menekan tombol "Coblos".*
- **Akses Ditolak (Sudah Memilih)**: $(p \land q \land r) \land \lnot s$
  *Pengguna aktif, tetapi mereka sudah pernah mencoblos ($s$ berubah menjadi **False**). Sistem seketika memblokir komponen pencoblosan, yang secara fungsional bertindak sebagai **Absolute Double-Vote Protection**.*

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
