<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'uuid' => Str::uuid(),
            'name' => 'admin',
            'username' => 'admin',
            'phone' => '0877777',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'is_active' => '1',
        ]);

        User::factory(200)->create();
    }
}
