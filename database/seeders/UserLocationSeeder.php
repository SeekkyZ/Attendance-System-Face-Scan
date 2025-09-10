<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\UserLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ดึงผู้ใช้และสถานที่ทั้งหมด
        $users = User::all();
        $locations = Location::all();
        
        // ตรวจสอบว่ามีผู้ใช้และสถานที่อยู่หรือไม่
        if ($users->isEmpty()) {
            $this->command->info('ไม่มีผู้ใช้ในระบบ กรุณาสร้างผู้ใช้ก่อน');
            return;
        }
        
        if ($locations->isEmpty()) {
            $this->command->info('ไม่มีสถานที่ในระบบ กรุณาสร้างสถานที่ก่อน');
            return;
        }
        
        // ให้ผู้ใช้ทั้งหมดมีสิทธิ์เข้าถึงสถานที่ทั้งหมด
        foreach ($users as $user) {
            foreach ($locations as $location) {
                // ตรวจสอบว่ามี relationship อยู่แล้วหรือไม่
                $exists = UserLocation::where('user_id', $user->id)
                                    ->where('location_id', $location->id)
                                    ->exists();
                
                if (!$exists) {
                    UserLocation::create([
                        'user_id' => $user->id,
                        'location_id' => $location->id,
                        'is_active' => true,
                        'created_at' => now('Asia/Bangkok'),
                        'updated_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }
        
        $this->command->info("อนุญาตสิทธิ์ผู้ใช้ {$users->count()} คน เข้าถึงสถานที่ {$locations->count()} แห่งเรียบร้อยแล้ว");
    }
}
