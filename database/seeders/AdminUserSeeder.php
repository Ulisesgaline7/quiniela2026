<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'ugalinez'],
            [
                'name'       => 'Ulises Galinez',
                'username'   => 'ugalinez',
                'is_admin'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
