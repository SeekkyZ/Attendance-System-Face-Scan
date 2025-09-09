<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create test user
        $user = User::where('email', 'test@test.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
        }

        // Create test locations
        $location1 = Location::create([
            'name' => 'สำนักงานใหญ่',
            'description' => 'สำนักงานใหญ่แห่งแรก',
            'latitude' => 13.7563,
            'longitude' => 100.5018,
            'radius' => 200,
            'is_active' => true
        ]);

        $location2 = Location::create([
            'name' => 'สาขา 2',
            'description' => 'สำนักงานสาขาที่ 2',
            'latitude' => 13.7460,
            'longitude' => 100.5350,
            'radius' => 150,
            'is_active' => true
        ]);

        // Attach user to locations
        $user->locations()->attach($location1->id, [
            'is_active' => true,
            'created_at' => now('Asia/Bangkok'),
            'updated_at' => now('Asia/Bangkok')
        ]);

        $user->locations()->attach($location2->id, [
            'is_active' => true,
            'created_at' => now('Asia/Bangkok'),
            'updated_at' => now('Asia/Bangkok')
        ]);

        $this->command->info('Test data seeded successfully!');
    }
}
