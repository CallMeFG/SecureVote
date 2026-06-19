<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Candidate;
use App\Models\VotingPeriod;
use App\Models\Vote;
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
            'email' => 'admin@pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $roleAdmin->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        User::create([
            'nim' => 'PANITIA01',
            'name' => 'Ketua Panitia KPU',
            'email' => 'kpu@pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $rolePanitia->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        $voter1 = User::create([
            'nim' => '2501001',
            'name' => 'Mahasiswa Pemilih 1',
            'email' => 'mhs1@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        $voter2 = User::create([
            'nim' => '2501002',
            'name' => 'Mahasiswa Pemilih 2 (Nonaktif)',
            'email' => 'mhs2@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $rolePemilih->id,
            'is_active' => false,
            'is_voted' => false,
        ]);

        $voter3 = User::create([
            'nim' => '2501003',
            'name' => 'Mahasiswa Pemilih 3',
            'email' => 'mhs3@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password123'),
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
                'vice_nim' => '1901' . rand(100, 999),
                'vice_name' => 'Wakil Gubernur Periode ' . $year . ' (A)',
                'vision' => 'Visi A tahun ' . $year,
                'mission' => 'Misi A',
                'photo_url' => 'https://ui-avatars.com/api/?name=A+' . $index . '&background=random&color=fff&size=256',
            ]);

            $cand2 = Candidate::create([
                'voting_period_id' => $pastPeriod->id,
                'nim' => '1901' . rand(100, 999),
                'name' => 'Gubernur Periode ' . $year . ' (B)',
                'vice_nim' => '1901' . rand(100, 999),
                'vice_name' => 'Wakil Gubernur Periode ' . $year . ' (B)',
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
            'nim' => '2301099',
            'name' => 'Andi Wijaya',
            'vice_nim' => '2301101',
            'vice_name' => 'Siti Nurhaliza',
            'vision' => 'Menjadikan ITSA PCR unggul, inovatif, dan berdaya saing global di era Society 5.0.',
            'mission' => '1. Mengadakan perlombaan IT nasional. 2. Mengaktifkan kembali seluruh klub belajar mahasiswa.',
            'photo_url' => 'https://ui-avatars.com/api/?name=Andi+Wijaya&background=0D8ABC&color=fff&size=256',
        ]);

        Candidate::create([
            'voting_period_id' => $activePeriod->id,
            'nim' => '2301100',
            'name' => 'Budi Santoso',
            'vice_nim' => '2301102',
            'vice_name' => 'Ahmad Fais',
            'vision' => 'ITSA PCR yang solid, kolaboratif, dan menjadi rumah nyaman bagi seluruh anggotanya.',
            'mission' => '1. Membangun relasi kuat antar angkatan. 2. Mewujudkan proyek open source bersama.',
            'photo_url' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=198754&color=fff&size=256',
        ]);
    }
}
