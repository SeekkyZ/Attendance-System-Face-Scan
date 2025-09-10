@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>ลงทะเบียนใบหน้า
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>คำแนะนำ:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>มองตรงไปที่กล้อง ไม่สวมแว่นดำหรือหน้ากาก</li>
                            <li>ทำให้แสงสว่างเพียงพอและหลีกเลี่ยงแสงส่องจากด้านหลัง</li>
                            <li>ควรลงทะเบียนใบหน้าในหลายมุมเพื่อความแม่นยำ</li>
                            <li>กดปุ่ม "บันทึกใบหน้า" เมื่อเห็นกรอบสีเขียวรอบใบหน้า</li>
                        </ul>
                    </div>

                    <!-- Camera Section -->
                    <div class="face-register-container">
                        <div class="video-container mb-3">
                            <video id="register-video" autoplay playsinline></video>
                            <canvas id="register-canvas" style="display: none;"></canvas>
                            <div id="face-overlay" class="face-overlay" style="display: none;"></div>
                        </div>
                        
                        <div class="text-center mb-3">
                            <button id="start-camera" class="btn btn-primary me-2">
                                <i class="fas fa-camera me-1"></i>เปิดกล้อง
                            </button>
                            <button id="capture-face" class="btn btn-success me-2" disabled>
                                <i class="fas fa-camera-retro me-1"></i>บันทึกใบหน้า
                            </button>
                            <button id="stop-camera" class="btn btn-secondary" disabled>
                                <i class="fas fa-stop me-1"></i>หยุดกล้อง
                            </button>
                        </div>
                        
                        <div class="mb-3">
                            <label for="face-label" class="form-label">ป้ายกำกับ (ไม่บังคับ)</label>
                            <input type="text" id="face-label" class="form-control" placeholder="เช่น หน้าหลัก, หน้าข้าง">
                        </div>
                        
                        <div id="face-status" class="alert" style="display: none;"></div>
                        
                        <!-- Face Detection Status -->
                        <div id="detection-status" class="text-center mt-3" style="display: none;">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                <span>กำลังตรวจจับใบหน้า...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Registered Faces -->
            @if($userFaces->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>ใบหน้าที่ลงทะเบียนแล้ว ({{ $userFaces->count() }} รายการ)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($userFaces as $face)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card face-card h-100">
                                <div class="card-body text-center p-3">
                                    <div class="face-image-container mb-3">
                                        @if($face->image_path && file_exists(storage_path('app/public/' . $face->image_path)))
                                            <img src="{{ asset('storage/' . $face->image_path) }}" 
                                                 class="face-image img-fluid rounded-3 shadow-sm" 
                                                 alt="{{ $face->label }}"
                                                 onclick="openImageModal('{{ asset('storage/' . $face->image_path) }}', '{{ $face->label }}')">
                                        @else
                                            <div class="face-placeholder bg-light rounded-3 d-flex align-items-center justify-content-center shadow-sm">
                                                <i class="fas fa-user fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h6 class="mb-2">{{ $face->label ?: 'ไม่ระบุชื่อ' }}</h6>
                                    <small class="text-muted d-block mb-2">ลงทะเบียนเมื่อ {{ $face->created_at->format('d/m/Y H:i') }}</small>
                                    <small class="badge bg-success mb-3">
                                        <i class="fas fa-check-circle me-1"></i>ใช้งานได้
                                    </small>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteFace({{ $face->id }})">
                                            <i class="fas fa-trash me-1"></i>ลบ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">
                    <i class="fas fa-user-circle me-2"></i>รูปใบหน้า
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modalImage" src="" class="img-fluid rounded" alt="Face Image" style="max-height: 70vh;">
                <p id="modalImageLabel" class="mt-3 h6 text-muted"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
class FaceRegister {
    constructor() {
        this.video = document.getElementById('register-video');
        this.canvas = document.getElementById('register-canvas');
        this.ctx = this.canvas.getContext('2d');
        this.stream = null;
        this.isModelLoaded = false;
        this.faceDetected = false;
        
        this.initializeElements();
        this.loadModels();
    }
    
    initializeElements() {
        document.getElementById('start-camera').addEventListener('click', () => this.startCamera());
        document.getElementById('capture-face').addEventListener('click', () => this.captureFace());
        document.getElementById('stop-camera').addEventListener('click', () => this.stopCamera());
    }
    
    async loadModels() {
        try {
            this.showStatus('กำลังโหลดโมเดลการจดจำใบหน้า...', 'info');
            
            // ใช้ CDN หรือ public models
            const MODEL_URL = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights';
            
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            
            this.isModelLoaded = true;
            this.showStatus('โมเดลโหลดเสร็จเรียบร้อย สามารถเริ่มใช้งานได้', 'success');
            
            setTimeout(() => {
                document.getElementById('face-status').style.display = 'none';
            }, 3000);
            
        } catch (error) {
            console.error('Error loading models:', error);
            this.showStatus('ไม่สามารถโหลดโมเดลได้ กรุณารีเฟรชหน้าใหม่', 'danger');
        }
    }
    
    async startCamera() {
        if (!this.isModelLoaded) {
            this.showStatus('กรุณารอให้โมเดลโหลดเสร็จก่อน', 'warning');
            return;
        }
        
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ 
                video: { width: 640, height: 480, facingMode: 'user' } 
            });
            this.video.srcObject = this.stream;
            
            document.getElementById('start-camera').disabled = true;
            document.getElementById('stop-camera').disabled = false;
            document.getElementById('detection-status').style.display = 'block';
            
            this.video.addEventListener('loadedmetadata', () => {
                this.canvas.width = this.video.videoWidth;
                this.canvas.height = this.video.videoHeight;
                this.detectFaces();
            });
            
        } catch (error) {
            console.error('Camera error:', error);
            this.showStatus('ไม่สามารถเข้าถึงกล้องได้', 'danger');
        }
    }
    
    async detectFaces() {
        if (!this.video.videoWidth || !this.video.videoHeight) {
            requestAnimationFrame(() => this.detectFaces());
            return;
        }
        
        try {
            const detections = await faceapi.detectAllFaces(
                this.video,
                new faceapi.TinyFaceDetectorOptions()
            ).withFaceLandmarks().withFaceDescriptors();
            
            // Clear previous overlays
            const overlay = document.getElementById('face-overlay');
            
            if (detections.length > 0) {
                const detection = detections[0]; // ใช้ใบหน้าแรกที่พบ
                this.drawFaceBox(detection.detection.box);
                
                if (!this.faceDetected) {
                    this.faceDetected = true;
                    document.getElementById('capture-face').disabled = false;
                    document.getElementById('detection-status').style.display = 'none';
                    overlay.style.display = 'block';
                }
                
                this.currentDetection = detection;
            } else {
                if (this.faceDetected) {
                    this.faceDetected = false;
                    document.getElementById('capture-face').disabled = true;
                    document.getElementById('detection-status').style.display = 'block';
                    overlay.style.display = 'none';
                }
            }
            
        } catch (error) {
            console.error('Detection error:', error);
        }
        
        if (this.stream && this.stream.active) {
            requestAnimationFrame(() => this.detectFaces());
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
    
    async captureFace() {
        if (!this.currentDetection) {
            this.showStatus('ไม่พบใบหน้าในขณะนี้', 'warning');
            return;
        }
        
        try {
            this.showStatus('กำลังบันทึกข้อมูลใบหน้า...', 'info');
            
            // วาดภาพจากวิดีโอลงบน canvas
            this.ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
            
            // แปลง canvas เป็น base64
            const imageData = this.canvas.toDataURL('image/png');
            
            // ส่งข้อมูลไปยังเซิร์ฟเวอร์
            const response = await fetch('/face/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    encoding: Array.from(this.currentDetection.descriptor),
                    image: imageData,
                    label: document.getElementById('face-label').value
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showStatus(result.message, 'success');
                document.getElementById('face-label').value = '';
                
                // รีเฟรชหน้าเพื่อแสดงใบหน้าใหม่
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                this.showStatus(result.message, 'danger');
            }
            
        } catch (error) {
            console.error('Capture error:', error);
            this.showStatus('เกิดข้อผิดพลาดในการบันทึก', 'danger');
        }
    }
    
    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
        
        document.getElementById('start-camera').disabled = false;
        document.getElementById('stop-camera').disabled = true;
        document.getElementById('capture-face').disabled = true;
        document.getElementById('detection-status').style.display = 'none';
        document.getElementById('face-overlay').style.display = 'none';
        
        this.faceDetected = false;
    }
    
    showStatus(message, type) {
        const statusDiv = document.getElementById('face-status');
        statusDiv.className = `alert alert-${type}`;
        statusDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>${message}`;
        statusDiv.style.display = 'block';
    }
}

async function deleteFace(faceId) {
    if (!confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลใบหน้านี้?')) {
        return;
    }
    
    try {
        const response = await fetch(`/face/${faceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        } else {
            alert('เกิดข้อผิดพลาด: ' + result.message);
        }
        
    } catch (error) {
        alert('เกิดข้อผิดพลาดในการลบข้อมูล');
    }
}

function openImageModal(imageSrc, label) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImageLabel').textContent = label || 'ไม่ระบุชื่อ';
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// เริ่มต้นเมื่อโหลดหน้าเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    new FaceRegister();
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

#register-video {
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

/* Face Card Styling */
.face-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.face-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.face-image-container {
    width: 100%;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.face-image {
    width: 100%;
    height: 280px;
    object-fit: cover;
    cursor: pointer;
    transition: all 0.3s ease;
}

.face-image:hover {
    transform: scale(1.05);
}

.face-placeholder {
    width: 100%;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Modal Styling */
.modal-body img {
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .face-image-container {
        height: 220px;
    }
    
    .face-image {
        height: 220px;
    }
    
    .face-placeholder {
        height: 220px;
    }
}

@media (max-width: 576px) {
    .face-image-container {
        height: 200px;
    }
    
    .face-image {
        height: 200px;
    }
    
    .face-placeholder {
        height: 200px;
    }
}
</style>
@endpush
@endsection
