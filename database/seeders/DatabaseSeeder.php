<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Candidate;
use App\Models\VotingPeriod;
use App\Models\Vote;
use App\Models\Agenda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $rolePanitia = Role::create(['name' => 'panitia']);
        $rolePemilih = Role::create(['name' => 'pemilih']);

        // 2. Users (DPT)
        $admin = User::create([
            'nim' => 'ADMIN01',
            'name' => 'Administrator Sistem',
            'email' => 'fathur24ti@mahasiswa.pcr.ac.id',
            'password' => Hash::make('AdminPcr@2026!'),
            'role_id' => $roleAdmin->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        User::create([
            'nim' => 'PANITIA01',
            'name' => 'Ketua Panitia KPU',
            'email' => 'assani200306@gmail.com',
            'password' => Hash::make('PanitiaPcr@2026!'),
            'role_id' => $rolePanitia->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        $voter1 = User::create([
            'nim' => '2501001',
            'name' => 'Mahasiswa Pemilih 1',
            'email' => 'zilaagila6@gmail.com',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        $voter2 = User::create([
            'nim' => '2501002',
            'name' => 'Mahasiswa Pemilih 2 (Nonaktif)',
            'email' => 'mhs2@mahasiswa.pcr.ac.id',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => false,
            'is_voted' => false,
        ]);

        $voter3 = User::create([
            'nim' => '2501003',
            'name' => 'Mahasiswa Pemilih 3',
            'email' => 'mhs3@mahasiswa.pcr.ac.id',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        // Kandidat 1 Users
        $kandidat1_ketua = User::create([
            'nim' => '2301099',
            'name' => 'Andi Wijaya',
            'email' => 'andiwijaya@mahasiswa.pcr.ac.id',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        $kandidat1_wakil = User::create([
            'nim' => '2301101',
            'name' => 'Siti Nurhaliza',
            'email' => 'sitinurhaliza@mahasiswa.pcr.ac.id',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        // Kandidat 2 Users
        $kandidat2_ketua = User::create([
            'nim' => '2301100',
            'name' => 'Budi Santoso',
            'email' => 'budisantoso@mahasiswa.pcr.ac.id',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        $kandidat2_wakil = User::create([
            'nim' => '2301102',
            'name' => 'Ahmad Fais',
            'email' => 'ahmadfais@mahasiswa.pcr.ac.id',
            'password' => Hash::make('MhsPcr@2026!'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        // 3. Past Periods (History)
        $pastYears = ['2022/2023', '2023/2024', '2024/2025'];
        
        foreach ($pastYears as $index => $year) {
            $pastPeriod = VotingPeriod::create([
                'period_name' => $year,
                'is_active' => false,
                'start_at' => now()->subYears(3 - $index)->subDays(2),
                'end_at' => now()->subYears(3 - $index),
                'created_by' => $admin->id
            ]);

            $cand1 = Candidate::create([
                'voting_period_id' => $pastPeriod->id,
                'nim' => '1901' . rand(100, 999),
                'name' => 'Gubernur Periode ' . $year . ' (A)',
                'email' => 'gubA' . $index . '@mahasiswa.pcr.ac.id',
                'vice_nim' => '1901' . rand(100, 999),
                'vice_name' => 'Wakil Gubernur Periode ' . $year . ' (A)',
                'vice_email' => 'wakilA' . $index . '@mahasiswa.pcr.ac.id',
                'vision' => 'Visi A tahun ' . $year,
                'mission' => 'Misi A',
                'photo_url' => 'https://ui-avatars.com/api/?name=A+' . $index . '&background=random&color=fff&size=256',
            ]);

            $cand2 = Candidate::create([
                'voting_period_id' => $pastPeriod->id,
                'nim' => '1901' . rand(100, 999),
                'name' => 'Gubernur Periode ' . $year . ' (B)',
                'email' => 'gubB' . $index . '@mahasiswa.pcr.ac.id',
                'vice_nim' => '1901' . rand(100, 999),
                'vice_name' => 'Wakil Gubernur Periode ' . $year . ' (B)',
                'vice_email' => 'wakilB' . $index . '@mahasiswa.pcr.ac.id',
                'vision' => 'Visi B tahun ' . $year,
                'mission' => 'Misi B',
                'photo_url' => 'https://ui-avatars.com/api/?name=B+' . $index . '&background=random&color=fff&size=256',
            ]);

            // Simulate votes
            $voters = [$voter1, $voter3];
            foreach ($voters as $v) {
                $chosen = rand(0, 1) == 0 ? $cand1 : $cand2;
                Vote::create([
                    'user_id' => $v->id,
                    'voting_period_id' => $pastPeriod->id,
                    'candidate_id' => $chosen->id,
                    'encrypted_choice' => Crypt::encryptString($chosen->id),
                    'voting_token' => strtoupper(Str::random(8)),
                    'voted_at' => now()->subYears(3 - $index)->subDay(),
                ]);
            }
        }

        // 4. Current Period
        $activePeriod = VotingPeriod::create([
            'period_name' => '2025/2026',
            'is_active' => true,
            'start_at' => now(),
            'end_at' => now()->addWeeks(2),
            'created_by' => $admin->id
        ]);

        Candidate::create([
            'voting_period_id' => $activePeriod->id,
            'nim' => $kandidat1_ketua->nim,
            'name' => $kandidat1_ketua->name,
            'email' => $kandidat1_ketua->email,
            'vice_nim' => $kandidat1_wakil->nim,
            'vice_name' => $kandidat1_wakil->name,
            'vice_email' => $kandidat1_wakil->email,
            'vision' => 'Menjadikan ITSA PCR unggul, inovatif, dan berdaya saing global di era Society 5.0.',
            'mission' => '1. Mengadakan perlombaan IT nasional. 2. Mengaktifkan kembali seluruh klub belajar mahasiswa.',
            'photo_url' => 'https://ui-avatars.com/api/?name=Andi+Wijaya&background=0D8ABC&color=fff&size=256',
        ]);

        Candidate::create([
            'voting_period_id' => $activePeriod->id,
            'nim' => $kandidat2_ketua->nim,
            'name' => $kandidat2_ketua->name,
            'email' => $kandidat2_ketua->email,
            'vice_nim' => $kandidat2_wakil->nim,
            'vice_name' => $kandidat2_wakil->name,
            'vice_email' => $kandidat2_wakil->email,
            'vision' => 'ITSA PCR yang solid, kolaboratif, dan menjadi rumah nyaman bagi seluruh anggotanya.',
            'mission' => '1. Membangun relasi kuat antar angkatan. 2. Mewujudkan proyek open source bersama.',
            'photo_url' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=198754&color=fff&size=256',
        ]);

        // 5. Agendas
        Agenda::create([
            'title' => 'Pendaftaran Kandidat',
            'description' => 'Masa pendaftaran dan penyerahan berkas bagi bakal calon Gubernur dan Wakil Gubernur.',
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(5),
            'is_active' => true,
        ]);

        Agenda::create([
            'title' => 'Debat Terbuka Kandidat',
            'description' => 'Penyampaian visi misi dan debat terbuka antar kandidat yang disiarkan secara langsung.',
            'start_date' => now()->subDays(2),
            'end_date' => now()->subDays(1),
            'is_active' => true,
        ]);

        Agenda::create([
            'title' => 'Pencoblosan Suara',
            'description' => 'Masa pemungutan suara secara elektronik melalui sistem SecureVote.',
            'start_date' => now(),
            'end_date' => now()->addWeeks(2),
            'is_active' => true,
        ]);

        Agenda::create([
            'title' => 'Rekapitulasi dan Pengumuman',
            'description' => 'Penghitungan suara otomatis oleh sistem dan pengumuman pemenang pemilu.',
            'start_date' => now()->addWeeks(2)->addDay(),
            'end_date' => now()->addWeeks(2)->addDays(2),
            'is_active' => true,
        ]);
    }
}
