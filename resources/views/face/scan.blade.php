@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>แสกนใบหน้าเพื่อลงเวลาเข้าออก
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Camera Section -->
                    <div class="face-scanner-container">
                        <div class="video-container mb-3">
                            <video id="scan-video" autoplay playsinline></video>
                            <canvas id="scan-canvas" style="display: none;"></canvas>
                            <div id="face-overlay" class="face-overlay" style="display: none;"></div>
                            <div id="recognition-overlay" class="recognition-overlay" style="display: none;">
                                <div class="recognition-info">
                                    <div class="user-name"></div>
                                    <div class="confidence"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mb-3">
                            <button id="start-scanner" class="btn btn-primary me-2">
                                <i class="fas fa-camera me-1"></i>เริ่มสแกน
                            </button>
                            <button id="stop-scanner" class="btn btn-secondary" disabled>
                                <i class="fas fa-stop me-1"></i>หยุดสแกน
                            </button>
                        </div>
                        
                        <!-- Attendance Options -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="location-select" class="form-label">เลือกสถานที่</label>
                                <select id="location-select" class="form-select">
                                    <option value="">กรุณาเลือกสถานที่</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="attendance-type" class="form-label">ประเภทการลงเวลา</label>
                                <select id="attendance-type" class="form-select">
                                    <option value="check_in">เข้างาน</option>
                                    <option value="check_out">ออกงาน</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="attendance-note" class="form-label">หมายเหตุ</label>
                                <input type="text" id="attendance-note" class="form-control" placeholder="หมายเหตุ (ไม่บังคับ)">
                            </div>
                        </div>
                        
                        <div id="scan-result" class="alert" style="display: none;"></div>
                        
                        <!-- Location Status -->
                        <div id="location-status" class="location-info" style="display: none;">
                            <h6><i class="fas fa-map-marker-alt me-1"></i>ข้อมูลตำแหน่ง</h6>
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
                        
                        <!-- Recognition Status -->
                        <div id="recognition-status" class="text-center mt-3" style="display: none;">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                <span>กำลังตรวจจับใบหน้า...</span>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
class FaceScanner {
    constructor() {
        this.video = document.getElementById('scan-video');
        this.canvas = document.getElementById('scan-canvas');
        this.ctx = this.canvas.getContext('2d');
        this.stream = null;
        this.isModelLoaded = false;
        this.scanning = false;
        this.userPosition = null;
        this.locations = [];
        
        this.initializeElements();
        this.loadModels();
        this.requestLocation();
        this.loadLocations();
        this.loadTodayAttendance();
    }
    
    initializeElements() {
        document.getElementById('start-scanner').addEventListener('click', () => this.startScanner());
        document.getElementById('stop-scanner').addEventListener('click', () => this.stopScanner());
    }
    
    async loadModels() {
        try {
            this.showResult('กำลังโหลดโมเดลการจดจำใบหน้า...', 'info');
            
            // ใช้ CDN หรือ public models
            const MODEL_URL = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights';
            
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            
            this.isModelLoaded = true;
            this.showResult('โมเดลโหลดเสร็จเรียบร้อย สามารถเริ่มใช้งานได้', 'success');
            
            setTimeout(() => {
                document.getElementById('scan-result').style.display = 'none';
            }, 3000);
            
        } catch (error) {
            console.error('Error loading models:', error);
            this.showResult('ไม่สามารถโหลดโมเดลได้ กรุณารีเฟรชหน้าใหม่หรือตรวจสอบการเชื่อมต่ออินเทอร์เน็ต', 'danger');
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
                document.getElementById('location-status').style.display = 'block';
                
            } catch (error) {
                this.showResult('ไม่สามารถเข้าถึงตำแหน่งของคุณได้ กรุณาอนุญาตการเข้าถึงตำแหน่ง', 'danger');
            }
        }
    }
    
    async loadLocations() {
        try {
            const response = await fetch('/face/locations');
            const result = await response.json();
            
            if (result.success) {
                this.locations = result.data;
                const select = document.getElementById('location-select');
                select.innerHTML = '<option value="">กรุณาเลือกสถานที่</option>';
                
                result.data.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location.id;
                    option.textContent = location.name;
                    option.dataset.latitude = location.latitude;
                    option.dataset.longitude = location.longitude;
                    option.dataset.radius = location.radius;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading locations:', error);
        }
    }
    
    async startScanner() {
        if (!this.isModelLoaded) {
            this.showResult('กรุณารอให้โมเดลโหลดเสร็จก่อน', 'warning');
            return;
        }
        
        if (!this.userPosition) {
            this.showResult('กรุณาอนุญาตการเข้าถึงตำแหน่งก่อนสแกน', 'warning');
            return;
        }
        
        const locationId = document.getElementById('location-select').value;
        if (!locationId) {
            this.showResult('กรุณาเลือกสถานที่ก่อนเริ่มสแกน', 'warning');
            return;
        }
        
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ 
                video: { width: 640, height: 480, facingMode: 'user' } 
            });
            this.video.srcObject = this.stream;
            this.scanning = true;
            
            document.getElementById('start-scanner').disabled = true;
            document.getElementById('stop-scanner').disabled = false;
            document.getElementById('recognition-status').style.display = 'block';
            
            this.video.addEventListener('loadedmetadata', () => {
                this.canvas.width = this.video.videoWidth;
                this.canvas.height = this.video.videoHeight;
                this.detectAndRecognize();
            });
            
        } catch (error) {
            console.error('Camera error:', error);
            this.showResult('ไม่สามารถเข้าถึงกล้องได้', 'danger');
        }
    }
    
    async detectAndRecognize() {
        if (!this.scanning) return;
        
        if (!this.video.videoWidth || !this.video.videoHeight) {
            requestAnimationFrame(() => this.detectAndRecognize());
            return;
        }
        
        try {
            const detections = await faceapi.detectAllFaces(
                this.video,
                new faceapi.TinyFaceDetectorOptions()
            ).withFaceLandmarks().withFaceDescriptors();
            
            const overlay = document.getElementById('face-overlay');
            const recognitionOverlay = document.getElementById('recognition-overlay');
            
            if (detections.length > 0) {
                const detection = detections[0];
                this.drawFaceBox(detection.detection.box);
                
                overlay.style.display = 'block';
                document.getElementById('recognition-status').style.display = 'none';
                
                // ส่งข้อมูลไปจดจำ
                await this.processRecognition(detection);
                
            } else {
                overlay.style.display = 'none';
                recognitionOverlay.style.display = 'none';
                document.getElementById('recognition-status').style.display = 'block';
            }
            
        } catch (error) {
            console.error('Detection error:', error);
        }
        
        if (this.scanning) {
            requestAnimationFrame(() => this.detectAndRecognize());
        }
    }
    
    async processRecognition(detection) {
        const locationId = document.getElementById('location-select').value;
        const type = document.getElementById('attendance-type').value;
        const note = document.getElementById('attendance-note').value;
        
        try {
            const response = await fetch('/face/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    encoding: Array.from(detection.descriptor),
                    location_id: locationId,
                    latitude: this.userPosition.latitude,
                    longitude: this.userPosition.longitude,
                    type: type,
                    note: note
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.stopScanner();
                this.showResult(result.message, 'success');
                this.loadTodayAttendance();
                document.getElementById('attendance-note').value = '';
            } else {
                this.showResult(result.message, 'danger');
            }
            
        } catch (error) {
            console.error('Recognition error:', error);
            this.showResult('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'danger');
        }
    }
    
    drawFaceBox(box) {
        const overlay = document.getElementById('face-overlay');
        const videoRect = this.video.getBoundingClientRect();
        const scaleX = videoRect.width / this.video.videoWidth;
        const scaleY = videoRect.height / this.video.videoHeight;
        
        overlay.style.left = (box.x * scaleX) + 'px';
        overlay.style.top = (box.y * scaleY) + 'px';
        overlay.style.width = (box.width * scaleX) + 'px';
        overlay.style.height = (box.height * scaleY) + 'px';
    }
    
    stopScanner() {
        this.scanning = false;
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
        
        document.getElementById('start-scanner').disabled = false;
        document.getElementById('stop-scanner').disabled = true;
        document.getElementById('recognition-status').style.display = 'none';
        document.getElementById('face-overlay').style.display = 'none';
        document.getElementById('recognition-overlay').style.display = 'none';
    }
    
    showResult(message, type) {
        const resultDiv = document.getElementById('scan-result');
        resultDiv.className = `alert alert-${type}`;
        resultDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>${message}`;
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

// เริ่มต้น Scanner เมื่อโหลดหน้าเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    const scanner = new FaceScanner();
    
    // อ่าน URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const locationId = urlParams.get('location');
    const attendanceType = urlParams.get('type');
    const note = urlParams.get('note');
    
    // Pre-select values from URL parameters
    if (attendanceType) {
        document.getElementById('attendance-type').value = attendanceType;
    }
    
    if (note) {
        document.getElementById('attendance-note').value = note;
    }
    
    // Pre-select location when locations are loaded
    if (locationId) {
        // Wait for locations to be loaded then select
        const checkLocationSelect = setInterval(() => {
            const locationSelect = document.getElementById('location-select');
            if (locationSelect.options.length > 1) {
                locationSelect.value = locationId;
                // Trigger change event to update location info
                locationSelect.dispatchEvent(new Event('change'));
                clearInterval(checkLocationSelect);
            }
        }, 100);
    }
});
</script>

<style>
.video-container {
    position: relative;
    width: 100%;
    max-width: 640px;
    margin: 0 auto;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
}

#scan-video {
    width: 100%;
    height: auto;
    display: block;
}

.face-overlay {
    position: absolute;
    border: 3px solid #28a745;
    border-radius: 5px;
    pointer-events: none;
    box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.3);
}

.recognition-overlay {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
}
</style>
@endpush
@endsection
