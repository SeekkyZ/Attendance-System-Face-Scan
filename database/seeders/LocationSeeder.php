<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'สำนักงานใหญ่',
                'description' => 'อาคารสำนักงานใหญ่ ชั้น 1',
                'latitude' => 13.7563,
                'longitude' => 100.5018,
                'radius' => 200,
                'is_active' => true
            ],
            [
                'name' => 'สาขา 1',
                'description' => 'สาขาที่ 1 ย่านสีลม',
                'latitude' => 13.7244,
                'longitude' => 100.5316,
                'radius' => 150,
                'is_active' => true
            ],
            [
                'name' => 'สาขา 2',
                'description' => 'สาขาที่ 2 ย่านสุขุมวิท',
                'latitude' => 13.7420,
                'longitude' => 100.5597,
                'radius' => 200,
                'is_active' => true
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
