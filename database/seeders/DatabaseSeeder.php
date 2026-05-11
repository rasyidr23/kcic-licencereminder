<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator KCIC',
            'email' => 'admin@kcic.co.id',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Manager Viewer',
            'email' => 'viewer@kcic.co.id',
            'password' => \Illuminate\Support\Facades\Hash::make('viewer123'),
            'role' => 'viewer',
        ]);

        $this->call([
            LicenceSeeder::class,
        ]);
    }
}
