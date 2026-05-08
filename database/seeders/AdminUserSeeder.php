<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'name'       => 'Administrador',
                'username'   => 'admin',
                'is_admin'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
