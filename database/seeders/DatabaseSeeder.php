<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $rolePanitia = Role::create(['name' => 'panitia']);
        $rolePemilih = Role::create(['name' => 'pemilih']);

        // 2. Users (DPT)
        User::create([
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

        User::create([
            'nim' => '2501001',
            'name' => 'Mahasiswa Pemilih 1',
            'email' => 'mhs1@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        User::create([
            'nim' => '2501002',
            'name' => 'Mahasiswa Pemilih 2 (Nonaktif)',
            'email' => 'mhs2@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $rolePemilih->id,
            'is_active' => false, // Untuk tes skenario
            'is_voted' => false,
        ]);

        User::create([
            'nim' => '2501003',
            'name' => 'Mahasiswa Pemilih 3',
            'email' => 'mhs3@mahasiswa.pcr.ac.id',
            'password' => Hash::make('password123'),
            'role_id' => $rolePemilih->id,
            'is_active' => true,
            'is_voted' => false,
        ]);

        // 3. Candidates
        Candidate::create([
            'nim' => '2301099',
            'name' => 'Andi Wijaya',
            'vision' => 'Menjadikan HIMA TI unggul dan inovatif.',
            'mission' => '1. Mengadakan lomba IT. 2. Mengaktifkan klub belajar.',
        ]);

        Candidate::create([
            'nim' => '2301100',
            'name' => 'Budi Santoso',
            'vision' => 'HIMA TI yang solid dan kolaboratif.',
            'mission' => '1. Membangun relasi antar angkatan. 2. Proyek bersama.',
        ]);
        
        // 4. Voting Period (Default: Closed)
        \App\Models\VotingPeriod::create([
            'is_active' => false,
            'start_at' => now(),
            'end_at' => now()->addDays(1),
            'created_by' => 1
        ]);
    }
}
