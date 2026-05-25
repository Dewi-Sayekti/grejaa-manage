<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jemaat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN USER =====
        $admin = User::updateOrCreate(
            ['email' => 'admin@gereja.com'],
            [
                'name' => 'Admin Gereja',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        Jemaat::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'nama_lengkap' => 'Admin Gereja',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-15',
                'alamat' => 'Jl. Gereja No. 1, Jakarta',
                'nomor_hp' => '081234567890',
                'status_pernikahan' => 'Menikah',
                'golongan_darah' => 'O',
                'tanggal_baptis' => '2005-06-20',
                'status_aktif' => 'Aktif',
            ]
        );

        // ===== JEMAAT USER =====
        $jemaat = User::updateOrCreate(
            ['email' => 'jemaat@gereja.com'],
            [
                'name' => 'Maria Sari',
                'password' => Hash::make('password123'),
                'role' => 'jemaat',
                'is_approved' => true,
                'approved_at' => now(),
            ]
        );

        Jemaat::updateOrCreate(
            ['user_id' => $jemaat->id],
            [
                'nama_lengkap' => 'Maria Sari',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1995-08-22',
                'alamat' => 'Jl. Mawar No. 5, Bandung',
                'nomor_hp' => '082198765432',
                'status_pernikahan' => 'Belum Menikah',
                'golongan_darah' => 'AB',
                'tanggal_baptis' => '2010-12-25',
                'status_aktif' => 'Aktif',
            ]
        );

        $this->command->info('✅ Test users created successfully!');
        $this->command->info('   Admin: admin@gereja.com / password123');
        $this->command->info('   Jemaat: jemaat@gereja.com / password123');
    }
}
