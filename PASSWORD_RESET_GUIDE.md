# Email Setup Guide - ระบบรีเซ็ตรหัสผ่าน

## ✅ ปัญหาที่แก้ไขแล้ว

**PHP Fatal Error** ใน `CustomResetPasswordNotification.php` ได้รับการแก้ไขแล้ว:
- ลบ type hinting `object $notifiable` ออกจาก method signatures
- ใช้ `$notifiable` เพียงอย่างเดียวตาม parent class

## 📧 ขั้นตอนการตั้งค่า Gmail SMTP

### 1. เตรียม Gmail Account:
```
1. เข้า Google Account Settings
2. Security > 2-Step Verification (เปิดใช้งาน)
3. Security > App passwords
4. เลือก "Mail" และ "Other (Laravel App)"
5. คัดลอก 16-digit password ที่ได้
```

### 2. อัปเดตไฟล์ .env:
ในไฟล์ `.env` ให้แก้ไข:
```env
MAIL_USERNAME=your-real-email@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_FROM_ADDRESS="your-real-email@gmail.com"
```

### 3. Clear Cache:
```bash
php artisan config:clear
php artisan cache:clear
```

## 🧪 การทดสอบระบบ

### ทดสอบการส่งอีเมล:
```bash
php artisan email:test your-email@gmail.com
```

### ทดสอบ Password Reset:
1. เข้า: `https://b5072798240c.ngrok-free.app/login`
2. คลิก: "ลืมรหัสผ่าน?"
3. ใส่อีเมลของคุณ
4. ตรวจสอบอีเมลที่ได้รับ

## 🎯 ระบบที่พร้อมใช้งาน

✅ **หน้าเว็บ:**
- `/login` - มีลิงก์ "ลืมรหัสผ่าน?"
- `/password/reset` - ฟอร์มขอรีเซ็ต
- `/password/reset/{token}` - ฟอร์มตั้งรหัsผ่านใหม่

✅ **Email Template:**
- ข้อความเป็นภาษาไทย
- ปุ่มสวยงาม
- ข้อมูลครบถ้วน

✅ **Security Features:**
- Token หมดอายุ 60 นาที
- Password validation
- Real-time password matching

## 🔧 การแก้ไขปัญหา

### ไม่ได้รับอีเมล:
1. ตรวจสอบ Spam folder
2. ตรวจสอบ App Password ถูกต้อง
3. ตรวจสอบ 2-Step Verification เปิดอยู่

### มี Error:
```bash
# ดู error logs
Get-Content storage/logs/laravel.log -Tail 50
```

ตอนนี้ระบบ Password Reset พร้อมใช้งาน! 🚀
