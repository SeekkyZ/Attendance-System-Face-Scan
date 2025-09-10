<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'ผู้ดูแลระบบ',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // สร้าง Test User 1
        User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'สมชาย ใจดี',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // สร้าง Test User 2
        User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'สมหญิง รักงาน',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // สร้าง Test User 3
        User::firstOrCreate(
            ['email' => 'user3@example.com'],
            [
                'name' => 'วิทยา เรียนรู้',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // สร้าง Test User 4
        User::firstOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'ทดสอบ ระบบ',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ สร้าง User ทดสอบเรียบร้อยแล้ว');
        $this->command->info('📧 Email: admin@example.com, Password: password123');
        $this->command->info('📧 Email: user1@example.com, Password: password123');
        $this->command->info('📧 Email: user2@example.com, Password: password123');
        $this->command->info('📧 Email: user3@example.com, Password: password123');
        $this->command->info('📧 Email: test@test.com, Password: 123456');
    }
}
