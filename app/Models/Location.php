<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description', 
        'latitude',
        'longitude',
        'radius',
        'is_active'
    ];
    
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime:Asia/Bangkok',
        'updated_at' => 'datetime:Asia/Bangkok',
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_locations')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    

    
    // คำนวณระยะทางจากตำแหน่งที่กำหนด (Haversine formula)
    public function calculateDistance($userLat, $userLng)
    {
        $earthRadius = 6371000; // รัศมีโลกในหน่วยเมตร
        
        $latDelta = deg2rad($userLat - $this->latitude);
        $lngDelta = deg2rad($userLng - $this->longitude);
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($userLat)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    // ตรวจสอบว่าผู้ใช้อยู่ในรัศมีที่กำหนดหรือไม่
    public function isWithinRadius($userLat, $userLng)
    {
        $distance = $this->calculateDistance($userLat, $userLng);
        return $distance <= $this->radius;
    }
}
