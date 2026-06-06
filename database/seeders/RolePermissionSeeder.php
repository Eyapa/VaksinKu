<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'nakes', 'pasien'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create sample admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@vaksinku.test'],
            [
                'name' => 'Admin System',
                'password' => bcrypt('password'),
                'phone' => '081234567890'
            ]
        );
        $admin->assignRole('admin');
        
        // Create sample patient
        $pasien = User::firstOrCreate(
            ['email' => 'pasien@vaksinku.test'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'phone' => '081234567891'
            ]
        );
        $pasien->assignRole('pasien');
    }
}
