# 🚂 Railway Deployment Guide

## การ Deploy บน Railway

### 1. เตรียมโปรเจค
```bash
# Clone repository
git clone [your-repo-url]
cd scan

# Install dependencies
composer install --no-dev --optimize-autoloader
```

### 2. ตั้งค่า Railway

1. **สร้าง Project ใหม่บน Railway**
   - ไปที่ https://railway.app
   - สร้าง New Project
   - เลือก Deploy from GitHub repo

2. **เพิ่ม MySQL Database**
   - คลิก "Add Service"
   - เลือก "Database"
   - เลือก "MySQL"

### 3. ตั้งค่า Environment Variables

ใน Railway Dashboard ตั้งค่าตัวแปรเหล่านี้:

```env
APP_NAME="Face Recognition Attendance System"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_TIMEZONE=Asia/Bangkok

# Database จะถูกตั้งค่าอัตโนมัติโดย Railway
# MYSQLHOST, MYSQLPORT, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD

# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
```

### 4. Generate App Key

ใน Railway Console รันคำสั่ง:
```bash
php artisan key:generate --show
```
แล้วคัดลอก key ไปใส่ใน `APP_KEY`

### 5. การ Deploy

Railway จะ deploy อัตโนมัติเมื่อ:
- Push code ไป GitHub
- หรือ Deploy manually ผ่าน Dashboard

### 6. หลัง Deploy เสร็จ

1. **ตั้งค่า Domain**
   - ใน Railway Dashboard
   - ไปที่ Settings > Domains
   - Generate Domain หรือใส่ Custom Domain

2. **ตรวจสอบ Database**
   - เข้าไปดูใน MySQL Database Service
   - ตรวจสอบว่า tables ถูกสร้างแล้ว

3. **ทดสอบระบบ**
   - เข้าไปที่ URL ของแอป
   - ลงทะเบียนผู้ใช้ใหม่
   - ทดสอบการลงเวลา

## 🔧 Troubleshooting

### ปัญหาที่พบบ่อย

1. **"could not find driver" Error**
   - ตรวจสอบว่า `ext-pdo` และ `ext-pdo_mysql` อยู่ใน `composer.json`
   - Re-deploy อีกครั้ง

2. **Migration ไม่ทำงาน**
   ```bash
   # รันใน Railway Console
   php artisan migrate --force
   ```

3. **Storage Permission**
   ```bash
   # รันใน Railway Console
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

4. **App Key ไม่ถูกต้อง**
   ```bash
   php artisan key:generate --force
   ```

### การดู Logs

```bash
# Application Logs
php artisan log:show

# Railway Logs
# ดูใน Railway Dashboard > Deployments > Logs
```

## 📱 การใช้งานหลัง Deploy

### HTTPS เท่านั้น
- ระบบต้องใช้งานผ่าน HTTPS เท่านั้น
- เพื่อให้ Camera และ GPS ทำงานได้

### การตั้งค่า Email
- ต้องใช้ Gmail App Password
- เปิด 2FA แล้วสร้าง App Password

### Face-API Models
- Models จะดาวน์โหลดจาก CDN อัตโนมัติ
- หรือใส่ไฟล์ใน `public/models/` ก่อน deploy

---

🎉 **Happy Deployment!** 🎉
