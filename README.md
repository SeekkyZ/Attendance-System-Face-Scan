# ระบบลงเวลาเข้าออกด้วยการสแกนใบหน้า 👤⏰

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP">
    <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap" alt="Bootstrap">
    <img src="https://img.shields.io/badge/Face_API.js-0.22.2-00D4AA?style=for-the-badge" alt="Face API">
    <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql" alt="MySQL">
</p>

## 📖 เกี่ยวกับโปรเจค

ระบบลงเวลาเข้าออกด้วยการสแกนใบหน้า เป็นเว็บแอปพลิเคชันที่พัฒนาด้วย Laravel Framework สำหรับจัดการการลงเวลาเข้าออกของพนักงานด้วยเทคโนโลยี Face Recognition และ GPS Location Validation

### 🎯 วัตถุประสงค์
- เพิ่มความปลอดภัยในการลงเวลาเข้าออก
- ป้องกันการปลอมแปลงข้อมูลการลงเวลา
- ตรวจสอบตำแหน่งพนักงานด้วย GPS
- ลดกระบวนการใช้บัตรหรือรหัสผ่าน

## ✨ ฟีเจอร์หลัก

### 🔐 ระบบยืนยันตัวตน
- **Face Recognition**: ใช้ Face-API.js สำหรับการจดจำใบหน้า
- **Multi-Face Registration**: รองรับการลงทะเบียนใบหน้าหลายมุม
- **Real-time Detection**: ตรวจจับใบหน้าแบบ Real-time
- **Password Reset**: ระบบรีเซ็ตรหัsผ่านผ่านอีเมล

### 📍 ระบบตำแหน่ง
- **GPS Validation**: ตรวจสอบตำแหน่งในรัศมี 200 เมตร
- **Location Management**: จัดการสถานที่ทำงานหลายแห่ง
- **Google Maps Integration**: เลือกตำแหน่งผ่าน Google Maps
- **User Permission System**: กำหนดสิทธิ์เข้าถึงสถานที่

### ⏰ ระบบลงเวลา
- **Smart Time Suggestions**: แนะนำประเภทการลงเวลาตามช่วงเวลา
- **Time Period Display**: แสดงช่วงเวลา (เช้า/กลางวัน/บ่าย/เย็น)
- **Attendance History**: ประวัติการลงเวลาแบบละเอียด
- **Today's Summary**: สรุปการลงเวลาประจำวัน

### 🎨 ประสบการณ์ผู้ใช้
- **Responsive Design**: รองรับทุกอุปกรณ์ (Desktop/Tablet/Mobile)
- **Thai Language**: ภาษาไทยครบถ้วน
- **Modern UI**: ใช้ Bootstrap 5 พร้อม Custom CSS
- **Intuitive Navigation**: การนำทางที่เข้าใจง่าย

## 🛠️ เทคโนโลยีที่ใช้

### Backend
- **Laravel 10.x** - PHP Web Framework
- **MySQL 8.0+** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel UI** - Authentication Scaffolding

### Frontend
- **Bootstrap 5.3** - CSS Framework
- **Face-API.js 0.22.2** - Face Recognition
- **JavaScript ES6+** - Client-side Logic
- **Font Awesome 6.0** - Icons

### APIs & Services
- **Google Maps API** - Location Services
- **Geolocation API** - GPS Positioning
- **SMTP Email** - Password Reset

## 📋 ความต้องการระบบ

### Server Requirements
- **PHP**: 8.1 หรือสูงกว่า
- **Composer**: 2.x
- **MySQL**: 8.0 หรือสูงกว่า
- **Apache/Nginx**: Web Server
- **SSL Certificate**: สำหรับ GPS/Camera Access

### PHP Extensions
```
BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, 
PDO, Tokenizer, XML, GD, Intl
```

### Browser Requirements
- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+

## 🚀 การติดตั้งและใช้งาน

### 1. Clone Repository
```bash
git clone [repository-url]
cd scan
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
แก้ไขไฟล์ `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scan_attendance
DB_USERNAME=root
DB_PASSWORD=your_password

APP_TIMEZONE=Asia/Bangkok
```

### 5. Email Configuration (สำหรับ Password Reset)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 7. Storage Setup
```bash
php artisan storage:link
```

### 8. Start Development Server
```bash
php artisan serve
```

## 📱 การใช้งาน

### สำหรับผู้ใช้ทั่วไป

1. **สมัครสมาชิก/เข้าสู่ระบบ**
   - ลงทะเบียนบัญชีใหม่
   - เข้าสู่ระบบด้วยอีเมล/รหัสผ่าน

2. **ลงทะเบียนใบหน้า**
   - ไปที่หน้า "ลงทะเบียนใบหน้า"
   - อนุญาตการเข้าถึงกล้อง
   - ลงทะเบียนใบหน้าในมุมต่างๆ

3. **ลงเวลาเข้าออก**
   - เลือกสถานที่จากรายการ
   - ตรวจสอบระยะทางในรัศมี 200m
   - สแกนใบหน้าเพื่อลงเวลา

4. **ดูประวัติ**
   - ตรวจสอบประวัติการลงเวลา
   - ดูสรุปรายวัน/รายเดือน

### สำหรับผู้ดูแลระบบ

1. **จัดการสถานที่**
   - เพิ่ม/แก้ไข/ลบสถานที่
   - กำหนดพิกัด GPS
   - ตั้งค่ารัศมีอนุญาต

2. **จัดการผู้ใช้**
   - กำหนดสิทธิ์เข้าถึงสถานที่
   - ตรวจสอบการลงเวลาของพนักงาน

## 🔧 การกำหนดค่าเพิ่มเติม

### Timezone Configuration
ระบบใช้เขตเวลาประเทศไทย (Asia/Bangkok):
```php
// config/app.php
'timezone' => 'Asia/Bangkok',

// .env
APP_TIMEZONE=Asia/Bangkok
```

### Face Recognition Settings
```javascript
// ความไวในการตรวจจับใบหน้า
const FACE_DETECTION_THRESHOLD = 0.6;

// ขนาดภาพสำหรับ Face Recognition
const FACE_IMAGE_SIZE = { width: 640, height: 480 };
```

### GPS Validation Settings
```php
// ระยะทางอนุญาตในการลงเวลา (เมตร)
const ALLOWED_DISTANCE = 200;
```

## 📊 โครงสร้างฐานข้อมูล

### ตารางหลัก
- `users` - ข้อมูลผู้ใช้
- `locations` - ข้อมูลสถานที่
- `face_encodings` - ข้อมูล Face Recognition
- `attendances` - ข้อมูลการลงเวลา
- `user_locations` - สิทธิ์เข้าถึงสถานที่

### ERD Relationship
```
Users (1:M) Face Encodings
Users (M:M) Locations (through user_locations)
Users (1:M) Attendances
Locations (1:M) Attendances
```

## 🧪 การทดสอบ

### Unit Testing
```bash
php artisan test
```

### Feature Testing
```bash
php artisan test --testsuite=Feature
```

### Email Testing
```bash
php artisan email:test your-email@example.com
```

## 🔒 Security Features

- **CSRF Protection**: Laravel Built-in
- **Password Hashing**: Bcrypt Algorithm
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Protection**: Blade Templating
- **GPS Validation**: Location-based Access
- **Face Recognition**: Biometric Authentication
- **Token Expiration**: Password Reset Security

## 📈 Performance Optimization

- **Database Indexing**: Optimized Queries
- **Image Compression**: Face Image Storage
- **CDN Integration**: Face-API.js Models
- **Caching**: Laravel Cache System
- **Asset Minification**: CSS/JS Optimization

## 🐛 การแก้ไขปัญหา

### ปัญหาที่พบบ่อย

1. **กล้องไม่ทำงาน**
   - ตรวจสอบ HTTPS Certificate
   - อนุญาตการเข้าถึงกล้องในเบราว์เซอร์

2. **GPS ไม่ทำงาน**
   - ตรวจสอบ Location Permission
   - ใช้งานผ่าน HTTPS เท่านั้น

3. **Face Recognition ไม่แม่นยำ**
   - ลงทะเบียนใบหน้าในแสงสว่างเพียงพอ
   - ลงทะเบียนหลายมุม

### Log Files
```bash
# ดู Application Logs
tail -f storage/logs/laravel.log

# ดู Web Server Logs (Apache)
tail -f /var/log/apache2/error.log
```

## 📞 การสนับสนุน

### ช่องทางติดต่อ
- **Email**: [lekduang1309@gmail.com]


### ข้อมูลการพัฒนา
- **Developer**: [นายพงศกร เล็กดวง]
- **Version**: 1.0.0
- **Last Updated**: September 2025
- **License**: MIT

## 🚀 การพัฒนาในอนาคต

### Features ที่วางแผนไว้
- [ ] Mobile Application (React Native)
- [ ] Slack/Teams Integration
- [ ] Advanced Analytics Dashboard
- [ ] Multi-tenant Architecture
- [ ] API Rate Limiting
- [ ] Advanced Reporting System

### Performance Improvements
- [ ] Redis Caching Implementation
- [ ] Database Query Optimization
- [ ] Image Processing Optimization
- [ ] Real-time Notifications

## 📄 License

โปรเจคนี้ได้รับอนุญาตภายใต้ [MIT License](https://opensource.org/licenses/MIT)

---

**หมายเหตุ**: โปรเจคนี้พัฒนาขึ้นเพื่อการศึกษาและฝึกงาน โปรดทดสอบอย่างละเอียดก่อนนำไปใช้งานจริงในสภาพแวดล้อมการผลิต

<p align="center">
    Made with ❤️ for Internship Project<br>
    <small>Powered by Laravel & Face-API.js</small>
</p>
