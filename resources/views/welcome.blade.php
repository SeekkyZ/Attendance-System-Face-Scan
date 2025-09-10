<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกเวลาเข้าออก - Smart Attendance</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Kanit', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" x="0" y="0" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="white" fill-opacity="0.1"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none; /* แก้ไขปัญหากดปุ่มไม่ได้ */
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .stats-card {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 3rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                min-height: 80vh;
                text-align: center;
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation (no login/register) -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fas fa-clock me-2"></i>Smart Attendance
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        ระบบบันทึกเวลาเข้าออก<br>
                        <span style="background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            อัจฉริยะ
                        </span>
                    </h1>
                    <p class="lead mb-4">
                        ระบบลงเวลาเข้าออกที่ทันสมัย ด้วยเทคโนโลยีการสแกนใบหน้า และการตรวจสอบตำแหน่ง GPS 
                        เพื่อความแม่นยำและความปลอดภัยสูงสุด
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4" style="position: relative; z-index: 10;">
                        <a href="/attendance" class="btn btn-custom btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>เข้าสู่หน้าลงเวลาเข้าออก
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stats-card">
                                <h3 class="fw-bold mb-1">99.9%</h3>
                                <small>ความแม่นยำ</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <h3 class="fw-bold mb-1">200m</h3>
                                <small>รัศมีตรวจสอบ</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <h3 class="fw-bold mb-1">24/7</h3>
                                <small>ใช้งานได้ตลอดเวลา</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-card">
                                <h3 class="fw-bold mb-1">AI</h3>
                                <small>ระบบจดจำใบหน้า</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="fw-bold section-title">คุณสมบัติเด่น</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h5 class="fw-bold mb-3">สแกนใบหน้า</h5>
                        <p class="text-muted">
                            เทคโนโลยี AI ล่าสุดในการจดจำใบหน้า ให้ความแม่นยำสูงและรวดเร็ว 
                            ไม่จำเป็นต้องสัมผัสอุปกรณ์
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-3">ตรวจสอบตำแหน่ง</h5>
                        <p class="text-muted">
                            ระบบ GPS ตรวจสอบว่าคุณอยู่ในพื้นที่ที่กำหนดจริง ในรัศมี 200 เมตร 
                            ป้องกันการลงเวลาผิดสถานที่
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5 class="fw-bold mb-3">ประวัติการทำงาน</h5>
                        <p class="text-muted">
                            ดูประวัติการลงเวลาเข้าออกได้ทันที พร้อมรายงานที่ละเอียด 
                            และสามารถส่งออกข้อมูลได้
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-3">ตรวจสอบตำแหน่ง</h5>
                        <p class="text-muted">
                            ระบบตรวจสอบตำแหน่ง GPS อัตโนมัติ 
                            เพื่อความแม่นยำในการลงเวลาเข้าออก
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-3">รองรับมือถือ</h5>
                        <p class="text-muted">
                            ใช้งานผ่านเว็บเบราว์เซอร์บนมือถือได้ทันที ไม่ต้องดาวน์โหลดแอป 
                            รองรับทุกระบบปฏิบัติการ
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-3">ความปลอดภัย</h5>
                        <p class="text-muted">
                            ข้อมูลของคุณปลอดภัยด้วยระบบเข้ารหัส ไม่มีการเก็บภาพใบหน้าจริง 
                            เก็บเฉพาะรหัสเข้ารหัสเท่านั้น
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold section-title">วิธีการใช้งาน</h2>
                    <p class="lead text-muted">เพียง 4 ขั้นตอนง่าย ๆ</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            1
                        </div>
                    </div>
                    <h5 class="fw-bold">สมัครสมาชิก</h5>
                    <p class="text-muted">สร้างบัญชีผู้ใช้และเข้าสู่ระบบ</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            2
                        </div>
                    </div>
                    <h5 class="fw-bold">ลงทะเบียนใบหน้า</h5>
                    <p class="text-muted">บันทึกข้อมูลใบหน้าสำหรับการจดจำ</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            3
                        </div>
                    </div>
                    <h5 class="fw-bold">เลือกสถานที่</h5>
                    <p class="text-muted">เลือกสถานที่ทำงานที่ต้องการลงเวลา</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            4
                        </div>
                    </div>
                    <h5 class="fw-bold">สแกนใบหน้า</h5>
                    <p class="text-muted">ลงเวลาเข้าออกด้วยการสแกนใบหน้า</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">พร้อมเริ่มต้นใช้งานแล้วหรือยัง?</h2>
            <p class="lead mb-4">ลองใช้ระบบบันทึกเวลาเข้าออกที่ทันสมัยที่สุดวันนี้</p>
            <a href="/attendance" class="btn btn-light btn-lg me-3">
                <i class="fas fa-tachometer-alt me-2"></i>เข้าสู่หน้าลงเวลาเข้าออก
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-dark text-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Smart Attendance System
                    </h6>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth scroll animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to feature cards
            const cards = document.querySelectorAll('.feature-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });
            
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
            
            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
