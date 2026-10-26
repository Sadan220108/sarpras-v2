<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun contoh untuk lokal (password: password).
        // Ubah nama, jabatan, dan password lewat menu Pengguna sebelum dipasang di server.
        $akun = [
            ['Pemohon Contoh', 'pemohon@sarpras.test', 'pemohon', 'Kepala', 'Laboran'],
            ['Admin SarPras',  'admin@sarpras.test',   'admin',   'Petugas SarPras', 'SarPras'],
        ];

        foreach ($akun as [$nama, $email, $role, $jabatan, $unit]) {
            User::updateOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => 'password', 'role' => $role, 'jabatan' => $jabatan, 'unit' => $unit]
            );
        }
    }
}
