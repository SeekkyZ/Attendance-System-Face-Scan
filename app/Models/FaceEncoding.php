<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceEncoding extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'encoding',
        'image_path',
        'label',
        'confidence',
        'is_active'
    ];
    
    protected $casts = [
        'encoding' => 'array',
        'confidence' => 'float',
        'is_active' => 'boolean',
        'created_at' => 'datetime:Asia/Bangkok',
        'updated_at' => 'datetime:Asia/Bangkok',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // คำนวณระยะทางระหว่าง face encodings (Euclidean distance)
    public static function calculateDistance($encoding1, $encoding2)
    {
        if (!is_array($encoding1) || !is_array($encoding2)) {
            return 999; // ค่าสูงสุดถ้าข้อมูลไม่ถูกต้อง
        }
        
        if (count($encoding1) !== count($encoding2)) {
            return 999; // ค่าสูงสุดถ้าขนาดไม่ตรงกัน
        }
        
        $sum = 0;
        for ($i = 0; $i < count($encoding1); $i++) {
            $diff = $encoding1[$i] - $encoding2[$i];
            $sum += $diff * $diff;
        }
        
        return sqrt($sum);
    }
    
    // ค้นหาใบหน้าที่ใกล้เคียงที่สุด
    public static function findBestMatch($inputEncoding, $threshold = 0.6)
    {
        $faces = self::where('is_active', true)->get();
        $bestMatch = null;
        $bestDistance = 999;
        
        foreach ($faces as $face) {
            $distance = self::calculateDistance($inputEncoding, $face->encoding);
            
            if ($distance < $bestDistance && $distance <= $threshold) {
                $bestDistance = $distance;
                $bestMatch = $face;
            }
        }
        
        return $bestMatch ? [
            'face' => $bestMatch,
            'distance' => $bestDistance,
            'confidence' => 1 - $bestDistance // แปลงเป็นค่าความมั่นใจ
        ] : null;
    }
}
