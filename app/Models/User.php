<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Asia/Bangkok',
        'password' => 'hashed',
        'created_at' => 'datetime:Asia/Bangkok',
        'updated_at' => 'datetime:Asia/Bangkok',
    ];
    
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'user_locations')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function faceEncodings()
    {
        return $this->hasMany(FaceEncoding::class);
    }
    
    // ตรวจสอบว่าผู้ใช้มีใบหน้าลงทะเบียนแล้วหรือไม่
    public function hasFaceRegistered()
    {
        return $this->faceEncodings()->where('is_active', true)->exists();
    }
    
    // ตรวจสอบว่าผู้ใช้มีสิทธิ์เข้าถึงสถานที่นี้หรือไม่
    public function hasAccessToLocation($locationId)
    {
        return $this->locations()
                   ->where('location_id', $locationId)
                   ->wherePivot('is_active', true)
                   ->exists();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
