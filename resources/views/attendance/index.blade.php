@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-check me-2"></i>เลือกสถานที่เพื่อลงเวลาเข้าออง
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Location Selection -->
                    <div class="mb-4">
                        <label for="location-select" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>เลือกสถานที่
                        </label>
                        <select id="location-select" class="form-select" required>
                            <option value="">กำลังโหลดสถานที่...</option>
                        </select>
                        <div class="form-text">ระบบจะแสดงเฉพาะสถานที่ที่คุณมีสิทธิ์เข้าถึงและอยู่ในระยะ 200 เมตร</div>
                    </div>

                    <!-- Location Info -->
                    <div id="location-info" class="card bg-light mb-4" style="display: none;">
                        <div class="card-body">
                                        <div class="container py-5">
                                            <div class="row justify-content-center">
                                                <div class="col-md-8">
                                                    <div class="card shadow">
                                                        <div class="card-header bg-primary text-white">
                                                            <h5 class="mb-0">
                                                                <i class="fas fa-user-check me-2"></i>ลงเวลาเข้าออก
                                                            </h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <form method="POST" action="{{ url('/attendance/scan') }}">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">กรอกชื่อ-นามสกุล</label>
                                                                    <input type="text" class="form-control" id="name" name="name" required placeholder="ชื่อ-นามสกุล">
                                                                </div>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-camera me-1"></i> สแกนใบหน้าเพื่อบันทึกเวลา
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <small class="text-muted">สถานะ:</small>
                                    <div id="location-status-text">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Period Indicator -->
                    <div class="card bg-info bg-opacity-10 border-info mb-4">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-clock text-info fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1" id="current-time-display">--:--:--</h6>
                                            <small class="text-muted">เวลาปัจจุบัน</small>
                                        </div>
                                        <div class="text-end">
                                            <span id="time-period-badge" class="badge fs-6 px-3 py-2">
                                                <i class="fas fa-sun me-1"></i>กำลังโหลด...
                                            </span>
                                            <div class="mt-1">
                                                <small id="time-period-suggestion" class="text-muted">กำลังตรวจสอบเวลา...</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Options -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="attendance-type" class="form-label">ประเภทการลงเวลา</label>
                            <select id="attendance-type" class="form-select">
                                <option value="check_in">เข้างาน</option>
                                <option value="check_out">ออกงาน</option>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1"></i>
                                <span id="attendance-suggestion">เลือกประเภทการลงเวลาที่เหมาะสม</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="attendance-note" class="form-label">หมายเหตุ</label>
                            <input type="text" id="attendance-note" class="form-control" placeholder="หมายเหตุ (ไม่บังคับ)">
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="d-grid">
                        <button id="scan-face-btn" class="btn btn-primary btn-lg" disabled>
                            <i class="fas fa-user-check me-2"></i>สแกนใบหน้าเพื่อลงเวลา
                        </button>
                    </div>

                    <div id="result-message" class="alert mt-3" style="display: none;"></div>

                    <!-- User Location Status -->
                    <div id="user-location-status" class="mt-3" style="display: none;">
                        <h6><i class="fas fa-map-marker-alt me-1"></i>ตำแหน่งปัจจุบันของคุณ</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">ละติจูด:</small>
                                <div id="user-latitude">-</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">ลองจิจูด:</small>
                                <div id="user-longitude">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today's Attendance Summary -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i>การลงเวลาวันนี้
                    </h6>
                </div>
                <div class="card-body">
                    <div id="today-attendance">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-orange {
    background-color: #fd7e14 !important;
}

.time-period-card {
    transition: all 0.3s ease;
}

.time-period-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.attendance-card {
    transition: all 0.3s ease;
}

.attendance-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#time-period-badge {
    animation: pulse 2s infinite;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
    100% {
        transform: scale(1);
    }
}

#current-time-display {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    font-size: 1.1rem;
}

.form-text {
    font-size: 0.875rem;
}

.form-text .text-success {
    font-weight: 500;
}

.form-text .text-warning {
    font-weight: 500;
}

.form-text .text-info {
    font-weight: 500;
}

.form-text .text-primary {
    font-weight: 500;
}

.form-text .text-danger {
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script>
class AttendanceSelector {
    constructor() {
        this.userPosition = null;
        this.locations = [];
        this.selectedLocation = null;
        this.timeUpdateInterval = null;
        
        this.initializeElements();
        this.initializeTimeDisplay();
        this.requestLocation();
        this.loadTodayAttendance();
    }
    
    initializeElements() {
        document.getElementById('location-select').addEventListener('change', (e) => this.onLocationChange(e));
        document.getElementById('scan-face-btn').addEventListener('click', () => this.redirectToFaceScan());
    }
    
    initializeTimeDisplay() {
        this.updateTimeDisplay();
        // อัปเดตเวลาทุก 1 วินาที
        this.timeUpdateInterval = setInterval(() => {
            this.updateTimeDisplay();
        }, 1000);
    }
    
    updateTimeDisplay() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Bangkok',
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        
        // แสดงเวลาปัจจุบัน
        const timeString = now.toLocaleTimeString('th-TH', options);
        document.getElementById('current-time-display').textContent = timeString;
        
        // กำหนดช่วงเวลาและคำแนะนำ
        const hour = now.getHours();
        const timePeriod = this.getTimePeriod(hour);
        this.updateTimePeriodDisplay(timePeriod, hour);
        this.updateAttendanceSuggestion(timePeriod, hour);
    }
    
    getTimePeriod(hour) {
        if (hour >= 6 && hour < 12) {
            return {
                name: 'เช้า',
                icon: 'fa-sun',
                class: 'bg-warning text-dark',
                description: 'ช่วงเวลาเช้า (06:00-11:59)'
            };
        } else if (hour >= 12 && hour < 14) {
            return {
                name: 'กลางวัน',
                icon: 'fa-sun',
                class: 'bg-danger text-white',
                description: 'ช่วงพักกลางวัน (12:00-13:59)'
            };
        } else if (hour >= 14 && hour < 18) {
            return {
                name: 'บ่าย',
                icon: 'fa-cloud-sun',
                class: 'bg-orange text-white',
                description: 'ช่วงเวลาบ่าย (14:00-17:59)'
            };
        } else if (hour >= 18 && hour < 22) {
            return {
                name: 'เย็น',
                icon: 'fa-moon',
                class: 'bg-info text-white',
                description: 'ช่วงเวลาเย็น (18:00-21:59)'
            };
        } else {
            return {
                name: 'กลางคืน',
                icon: 'fa-moon',
                class: 'bg-dark text-white',
                description: 'ช่วงเวลากลางคืน (22:00-05:59)'
            };
        }
    }
    
    updateTimePeriodDisplay(timePeriod, hour) {
        const badge = document.getElementById('time-period-badge');
        const suggestion = document.getElementById('time-period-suggestion');
        
        badge.className = `badge fs-6 px-3 py-2 ${timePeriod.class}`;
        badge.innerHTML = `<i class="fas ${timePeriod.icon} me-1"></i>${timePeriod.name}`;
        
        suggestion.textContent = `${timePeriod.description} (${hour}:00 น.)`;
    }
    
    updateAttendanceSuggestion(timePeriod, hour) {
        const suggestionElement = document.getElementById('attendance-suggestion');
        const attendanceSelect = document.getElementById('attendance-type');
        
        let suggestion = '';
        let recommendedValue = '';
        let suggestionClass = '';
        
        if (hour >= 6 && hour < 9) {
            // เช้า 6:00-8:59
            suggestion = '🌅 เวลาเข้างานปกติ - แนะนำเลือก "เข้างาน"';
            recommendedValue = 'check_in';
            suggestionClass = 'text-success';
        } else if (hour >= 9 && hour < 12) {
            // เช้าสาย 9:00-11:59  
            suggestion = '⏰ เข้างานสาย - แนะนำเลือก "เข้างาน"';
            recommendedValue = 'check_in';
            suggestionClass = 'text-warning';
        } else if (hour >= 12 && hour < 14) {
            // กลางวัน 12:00-13:59
            suggestion = '🍽️ ช่วงพักกลางวัน - ตรวจสอบตารางการทำงาน';
            recommendedValue = '';
            suggestionClass = 'text-info';
        } else if (hour >= 14 && hour < 17) {
            // บ่าย 14:00-16:59
            suggestion = '☀️ ช่วงบ่าย - ตรวจสอบตารางการทำงาน';
            recommendedValue = '';
            suggestionClass = 'text-primary';
        } else if (hour >= 17 && hour < 19) {
            // เย็น 17:00-18:59
            suggestion = '🌆 เวลาออกงานปกติ - แนะนำเลือก "ออกงาน"';
            recommendedValue = 'check_out';
            suggestionClass = 'text-success';
        } else if (hour >= 19 && hour < 22) {
            // เย็นสาย 19:00-21:59
            suggestion = '🌃 ทำงานล่วงเวลา - แนะนำเลือก "ออกงาน"';
            recommendedValue = 'check_out';
            suggestionClass = 'text-warning';
        } else {
            // กลางคืน 22:00-05:59
            suggestion = '🌙 กะดึก/ล่วงเวลา - แนะนำเลือก "ออกงาน"';
            recommendedValue = 'check_out';
            suggestionClass = 'text-danger';
        }
        
        suggestionElement.innerHTML = `<i class="fas fa-lightbulb me-1"></i><span class="${suggestionClass}">${suggestion}</span>`;
        
        // อัปเดตค่าที่แนะนำใน select box เฉพาะเมื่อยังไม่ได้เลือก
        if (recommendedValue && attendanceSelect.value === 'check_in') {
            attendanceSelect.value = recommendedValue;
        }
    }
    
    async requestLocation() {
        if (navigator.geolocation) {
            try {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000
                    });
                });
                
                this.userPosition = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                
                document.getElementById('user-latitude').textContent = this.userPosition.latitude.toFixed(6);
                document.getElementById('user-longitude').textContent = this.userPosition.longitude.toFixed(6);
                document.getElementById('user-location-status').style.display = 'block';
                
                // โหลดสถานที่ที่ผู้ใช้มีสิทธิ์เข้าถึง
                await this.loadAvailableLocations();
                
            } catch (error) {
                this.showResult('ไม่สามารถเข้าถึงตำแหน่งของคุณได้ กรุณาอนุญาตการเข้าถึงตำแหน่ง', 'danger');
                // โหลดสถานที่แม้ไม่มี GPS
                await this.loadAvailableLocations();
            }
        } else {
            this.showResult('เบราว์เซอร์ไม่รองรับการใช้ตำแหน่ง', 'warning');
            await this.loadAvailableLocations();
        }
    }
    
    async loadAvailableLocations() {
        try {
            const response = await fetch('/face/locations');
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                this.locations = result.data || [];
                this.populateLocationSelect();
            } else {
                this.showResult('ไม่สามารถโหลดรายการสถานที่ได้: ' + (result.message || 'Unknown error'), 'danger');
            }
        } catch (error) {
            this.showResult('เกิดข้อผิดพลาดในการโหลดข้อมูลสถานที่: ' + error.message, 'danger');
        }
    }
    
    populateLocationSelect() {
        const select = document.getElementById('location-select');
        select.innerHTML = '<option value="">เลือกสถานที่...</option>';
        
        if (!this.locations || this.locations.length === 0) {
            select.innerHTML = '<option value="">ไม่มีสถานที่ที่คุณสามารถเข้าถึงได้</option>';
            return;
        }
        
        // คำนวณระยะทางและกรองสถานที่ที่อยู่ในระยะ 200m
        let availableLocations = this.locations;
        
        if (this.userPosition) {
            availableLocations = this.locations.map(location => {
                const distance = this.calculateDistance(
                    this.userPosition.latitude,
                    this.userPosition.longitude,
                    location.latitude,
                    location.longitude
                );
                
                return {
                    ...location,
                    distance: distance,
                    inRange: distance <= 200
                };
            }).filter(location => location.inRange);
            
            if (availableLocations.length === 0) {
                select.innerHTML = '<option value="">ไม่มีสถานที่ที่อยู่ในระยะ 200 เมตร</option>';
                return;
            }
        }
        
        availableLocations.forEach(location => {
            const option = document.createElement('option');
            option.value = location.id;
            option.textContent = location.name + (location.distance ? ` (${Math.round(location.distance)}m)` : '');
            select.appendChild(option);
        });
    }
    
    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Earth's radius in meters
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return R * c;
    }
    
    onLocationChange(e) {
        const locationId = e.target.value;
        
        if (!locationId) {
            this.selectedLocation = null;
            document.getElementById('location-info').style.display = 'none';
            document.getElementById('scan-face-btn').disabled = true;
            return;
        }
        
        this.selectedLocation = this.locations.find(loc => loc.id == locationId);
        
        if (this.selectedLocation && this.userPosition) {
            const distance = this.calculateDistance(
                this.userPosition.latitude,
                this.userPosition.longitude,
                this.selectedLocation.latitude,
                this.selectedLocation.longitude
            );
            
            document.getElementById('location-distance').textContent = Math.round(distance) + ' เมตร';
            
            const inRange = distance <= 200;
            const statusElement = document.getElementById('location-status-text');
            
            if (inRange) {
                statusElement.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>อยู่ในระยะที่อนุญาต</span>';
                document.getElementById('scan-face-btn').disabled = false;
            } else {
                statusElement.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>อยู่นอกระยะที่อนุญาต</span>';
                document.getElementById('scan-face-btn').disabled = true;
            }
            
            document.getElementById('location-info').style.display = 'block';
        } else {
            document.getElementById('scan-face-btn').disabled = false; // อนุญาตถ้าไม่มี GPS
            document.getElementById('location-info').style.display = 'none';
        }
    }
    
    redirectToFaceScan() {
        if (!this.selectedLocation) {
            this.showResult('กรุณาเลือกสถานที่', 'warning');
            return;
        }
        
        const params = new URLSearchParams({
            location: this.selectedLocation.id,
            type: document.getElementById('attendance-type').value,
            note: document.getElementById('attendance-note').value || ''
        });
        
        window.location.href = `/face/scan?${params.toString()}`;
    }
    
    showResult(message, type) {
        const resultDiv = document.getElementById('result-message');
        resultDiv.className = `alert alert-${type}`;
        resultDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>${message}`;
        resultDiv.style.display = 'block';
        
        setTimeout(() => {
            resultDiv.style.display = 'none';
        }, 5000);
    }
    
    async loadTodayAttendance() {
        try {
            const response = await fetch('/attendance/today');
            const result = await response.json();
            
            const container = document.getElementById('today-attendance');
            
            if (result.success && result.data.length > 0) {
                let html = '<div class="row">';
                result.data.forEach(attendance => {
                    const badgeClass = attendance.type === 'check_in' ? 'bg-success' : 'bg-danger';
                    const icon = attendance.type === 'check_in' ? 'fa-sign-in-alt' : 'fa-sign-out-alt';
                    
                    html += `
                        <div class="col-md-6 mb-2">
                            <div class="card attendance-card ${attendance.type}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-1">${attendance.location}</h6>
                                            <small class="text-muted">${attendance.distance}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge session-badge ${badgeClass}">
                                                <i class="fas ${icon} me-1"></i>${attendance.type === 'check_in' ? 'เข้า' : 'ออก'}
                                            </span>
                                            <div class="mt-1">
                                                <small class="text-muted">${attendance.time}</small>
                                            </div>
                                        </div>
                                    </div>
                                    ${attendance.note ? `<div class="mt-2"><small class="text-muted">หมายเหตุ: ${attendance.note}</small></div>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p class="text-muted text-center mb-0">ยังไม่มีการลงเวลาวันนี้</p>';
            }
        } catch (error) {
            document.getElementById('today-attendance').innerHTML = '<p class="text-danger text-center mb-0">ไม่สามารถโหลดข้อมูลได้</p>';
        }
    }
}

// เริ่มต้นระบบเมื่อโหลดหน้าเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    window.attendanceSelector = new AttendanceSelector();
});

// ล้างค่า interval เมื่อออกจากหน้า
window.addEventListener('beforeunload', function() {
    if (window.attendanceSelector && window.attendanceSelector.timeUpdateInterval) {
        clearInterval(window.attendanceSelector.timeUpdateInterval);
    }
});
</script>
@endpush
@endsection
