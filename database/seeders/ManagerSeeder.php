<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Manager::insert([
            ['name' => 'Michael Lee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sarah Miller', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'David Clark', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Emily White', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Robert Hall', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anna King', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'James Carter', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laura Scott', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
