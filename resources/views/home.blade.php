@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Welcome Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body bg-gradient-primary text-white" style="background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-home me-2"></i>
                                ยินดีต้อนรับ, {{ Auth::user()->name }}!
                            </h2>
                            <p class="mb-0 opacity-75">ระบบลงเวลาเข้าออกด้วยการสแกนใบหน้าและตำแหน่ง GPS</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div id="current-datetime" class="fs-5 fw-bold">
                                <div id="current-time">--:--:--</div>
                                <div id="current-date" class="fs-6 opacity-75">-- -- ----</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 quick-action-card">
                        <div class="card-body text-center">
                            <div class="display-4 text-success mb-3">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h5 class="card-title">ลงเวลาเข้าออก</h5>
                            <p class="card-text text-muted">เลือกสถานที่และสแกนใบหน้าเพื่อลงเวลา</p>
                            <a href="{{ route('attendance.index') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-map-marker-alt me-2"></i>เริ่มลงเวลา
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 quick-action-card">
                        <div class="card-body text-center">
                            <div class="display-4 text-info mb-3">
                                <i class="fas fa-history"></i>
                            </div>
                            <h5 class="card-title">ประวัติการลงเวลา</h5>
                            <p class="card-text text-muted">ดูประวัติการเข้าออกงานของคุณ</p>
                            <a href="{{ route('attendance.history') }}" class="btn btn-info btn-lg">
                                <i class="fas fa-list me-2"></i>ดูประวัติ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How to Use Guide -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open me-2 text-primary"></i>
                        วิธีการใช้งานระบบ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="step-guide">
                                <!-- Step 1 -->
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">
                                        <span class="badge bg-primary rounded-circle p-3 fs-5">1</span>
                                    </div>
                                    <div class="step-content ms-3">
                                        <h6 class="fw-bold text-primary">ลงทะเบียนใบหน้า</h6>
                                        <p class="mb-2">ก่อนใช้งานครั้งแรก ต้องลงทะเบียนใบหน้าของคุณ</p>
                                        <a href="{{ route('face.register') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-user-plus me-1"></i>ลงทะเบียนใบหน้า
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Step 2 -->
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">
                                        <span class="badge bg-success rounded-circle p-3 fs-5">2</span>
                                    </div>
                                    <div class="step-content ms-3">
                                        <h6 class="fw-bold text-success">เลือกสถานที่</h6>
                                        <p class="mb-2">คลิก "ลงเวลาเข้าออก" เพื่อเลือกสถานที่ที่คุณต้องการลงเวลา</p>
                                        <div class="small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            ระบบจะแสดงเฉพาะสถานที่ที่อยู่ในระยะ 200 เมตรจากตำแหน่งของคุณ
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Step 3 -->
                                <div class="step-item d-flex mb-4">
                                    <div class="step-number">
                                        <span class="badge bg-warning rounded-circle p-3 fs-5">3</span>
                                    </div>
                                    <div class="step-content ms-3">
                                        <h6 class="fw-bold text-warning">เลือกประเภทการลงเวลา</h6>
                                        <p class="mb-2">เลือกว่าต้องการ "เข้างาน" หรือ "ออกงาน"</p>
                                        <div class="small text-muted">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            ระบบจะแนะนำประเภทการลงเวลาตามช่วงเวลาปัจจุบัน
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Step 4 -->
                                <div class="step-item d-flex">
                                    <div class="step-number">
                                        <span class="badge bg-danger rounded-circle p-3 fs-5">4</span>
                                    </div>
                                    <div class="step-content ms-3">
                                        <h6 class="fw-bold text-danger">สแกนใบหน้า</h6>
                                        <p class="mb-2">คลิก "สแกนใบหน้าเพื่อลงเวลา" และให้ระบบตรวจสอบใบหน้าของคุณ</p>
                                        <div class="small text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            ระบบจะบันทึกเวลา สถานที่ และตำแหน่ง GPS อัตโนมัติ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Status -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-day me-2"></i>สถานะวันนี้
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="today-status">
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">กำลังโหลด...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>สถานที่ที่เข้าถึงได้
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="available-locations">
                                <div class="text-center">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">กำลังโหลด...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Tips -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        เทิปการใช้งาน
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    อนุญาตการเข้าถึงตำแหน่งสำหรับการตรวจสอบ GPS
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    อนุญาตการเข้าถึงกล้องสำหรับการสแกนใบหน้า
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    ลงทะเบียนใบหน้าในที่แสงสว่างเพียงพอ
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-info text-info me-2"></i>
                                    ต้องอยู่ในระยะ 200 เมตรจากสถานที่ที่กำหนด
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info text-info me-2"></i>
                                    สามารถดูประวัติการลงเวลาได้ในเมนูประวัติ
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info text-info me-2"></i>
                                    ระบบจะแนะนำเวลาที่เหมาะสมสำหรับการลงเวลา
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.quick-action-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.quick-action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.step-guide .step-item {
    position: relative;
}

.step-guide .step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 24px;
    top: 60px;
    width: 2px;
    height: 40px;
    background-color: #dee2e6;
}

.step-number {
    flex-shrink: 0;
}

#current-datetime {
    font-family: 'Courier New', monospace;
}

.bg-gradient-primary {
    border-radius: 0.5rem !important;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
class HomePageManager {
    constructor() {
        this.timeInterval = null;
        this.initializeDateTime();
        this.loadTodayStatus();
        this.loadAvailableLocations();
    }
    
    initializeDateTime() {
        this.updateDateTime();
        this.timeInterval = setInterval(() => {
            this.updateDateTime();
        }, 1000);
    }
    
    updateDateTime() {
        const now = new Date();
        const timeOptions = {
            timeZone: 'Asia/Bangkok',
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        
        const dateOptions = {
            timeZone: 'Asia/Bangkok',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        };
        
        document.getElementById('current-time').textContent = now.toLocaleTimeString('th-TH', timeOptions);
        document.getElementById('current-date').textContent = now.toLocaleDateString('th-TH', dateOptions);
    }
    
    async loadTodayStatus() {
        try {
            const response = await fetch('/attendance/today');
            const result = await response.json();
            
            const container = document.getElementById('today-status');
            
            if (result.success && result.data.length > 0) {
                let html = '';
                const checkInCount = result.data.filter(a => a.type === 'check_in').length;
                const checkOutCount = result.data.filter(a => a.type === 'check_out').length;
                
                html += `
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-2">
                                <span class="badge bg-success fs-6 p-2">
                                    <i class="fas fa-sign-in-alt me-1"></i>${checkInCount}
                                </span>
                            </div>
                            <small class="text-muted">เข้างาน</small>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <span class="badge bg-danger fs-6 p-2">
                                    <i class="fas fa-sign-out-alt me-1"></i>${checkOutCount}
                                </span>
                            </div>
                            <small class="text-muted">ออกงาน</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="text-center">
                        <small class="text-muted">ครั้งล่าสุด: ${result.data[result.data.length - 1].time}</small>
                    </div>
                `;
                
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p class="text-muted text-center mb-0">ยังไม่มีการลงเวลาวันนี้</p>';
            }
        } catch (error) {
            document.getElementById('today-status').innerHTML = '<p class="text-danger text-center mb-0">ไม่สามารถโหลดข้อมูลได้</p>';
        }
    }
    
    async loadAvailableLocations() {
        try {
            const response = await fetch('/face/locations');
            const result = await response.json();
            
            const container = document.getElementById('available-locations');
            
            if (result.success && result.data.length > 0) {
                let html = `
                    <div class="text-center mb-2">
                        <span class="badge bg-success fs-6 p-2">
                            <i class="fas fa-map-marker-alt me-1"></i>${result.data.length} สถานที่
                        </span>
                    </div>
                    <div class="small text-muted">
                `;
                
                result.data.slice(0, 3).forEach((location, index) => {
                    html += `<div class="mb-1"><i class="fas fa-building me-1"></i>${location.name}</div>`;
                });
                
                if (result.data.length > 3) {
                    html += `<div class="text-primary">และอีก ${result.data.length - 3} สถานที่...</div>`;
                }
                
                html += `</div>`;
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p class="text-muted text-center mb-0">ไม่มีสถานที่ที่เข้าถึงได้</p>';
            }
        } catch (error) {
            document.getElementById('available-locations').innerHTML = '<p class="text-danger text-center mb-0">ไม่สามารถโหลดข้อมูลได้</p>';
        }
    }
    
    destroy() {
        if (this.timeInterval) {
            clearInterval(this.timeInterval);
        }
    }
}

// เริ่มต้นระบบเมื่อโหลดหน้าเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    window.homePageManager = new HomePageManager();
});

// ล้างค่า interval เมื่อออกจากหน้า
window.addEventListener('beforeunload', function() {
    if (window.homePageManager) {
        window.homePageManager.destroy();
    }
});
</script>
@endpush
@endsection
