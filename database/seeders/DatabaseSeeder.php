<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // เรียก UserLocationSeeder เพื่อให้ผู้ใช้มีสิทธิ์เข้าถึงสถานที่
        $this->call(UserLocationSeeder::class);
    }
}
