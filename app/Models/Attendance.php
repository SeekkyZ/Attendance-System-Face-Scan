<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'location_id',
        'session',
        'type',
        'attendance_time',
        'user_latitude',
        'user_longitude',
        'distance',
        'note'
    ];
    
    protected $casts = [
        'attendance_time' => 'datetime:Asia/Bangkok',
        'user_latitude' => 'decimal:8',
        'user_longitude' => 'decimal:8',
        'distance' => 'decimal:2',
        'created_at' => 'datetime:Asia/Bangkok',
        'updated_at' => 'datetime:Asia/Bangkok',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    // กำหนดช่วงเวลาตามเวลา
    public static function getCurrentSession()
    {
        $hour = Carbon::now('Asia/Bangkok')->hour;
        
        if ($hour >= 6 && $hour < 12) {
            return 'morning'; // เช้า
        } elseif ($hour >= 12 && $hour < 14) {
            return 'afternoon'; // กลางวัน  
        } elseif ($hour >= 14 && $hour < 18) {
            return 'evening'; // บ่าย
        } else {
            return 'night'; // เย็น/กลางคืน
        }
    }
    
    // ตรวจสอบว่าได้ลงเวลาในช่วงนี้แล้วหรือยัง
    public static function hasAttendanceToday($userId, $locationId, $session, $type)
    {
        return self::where('user_id', $userId)
                   ->where('location_id', $locationId)
                   ->where('session', $session)
                   ->where('type', $type)
                   ->whereDate('attendance_time', Carbon::today())
                   ->exists();
    }
}
