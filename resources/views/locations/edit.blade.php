@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Navigation Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('locations.index') }}">
                            <i class="fas fa-building me-1"></i>สถานที่ทั้งหมด
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('locations.show', $location) }}">
                            {{ $location->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">แก้ไข</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>แก้ไขสถานที่: {{ $location->name }}
                    </h5>
                    <a href="{{ route('locations.show', $location) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>กลับ
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('locations.update', $location) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อสถานที่ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $location->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $location->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">ละติจูด <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}" 
                                           step="0.000001" min="-90" max="90" required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">ลองจิจูด <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}" 
                                           step="0.000001" min="-180" max="180" required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="radius" class="form-label">รัศมี (เมตร) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('radius') is-invalid @enderror" 
                                           id="radius" name="radius" value="{{ old('radius', $location->radius) }}" 
                                           min="1" max="1000" required>
                                    @error('radius')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">สถานะ</label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $location->is_active) ? 'selected' : '' }}>เปิดใช้งาน</option>
                                        <option value="0" {{ !old('is_active', $location->is_active) ? 'selected' : '' }}>ปิดใช้งาน</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="get_location" onclick="getCurrentLocation()">
                                <label class="form-check-label" for="get_location">
                                    <i class="fas fa-map-marker-alt me-1"></i>ใช้ตำแหน่งปัจจุบันของฉัน
                                </label>
                            </div>
                            <small class="text-muted">คลิกเพื่อเติมละติจูดและลองจิจูดอัตโนมัติ</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>บันทึกการแก้ไข
                            </button>
                            <a href="{{ route('locations.show', $location) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>ยกเลิก
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function getCurrentLocation() {
    const checkbox = document.getElementById('get_location');
    
    if (checkbox.checked) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                    
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show mt-2';
                    alert.innerHTML = `
                        <i class="fas fa-check me-1"></i>ได้ตำแหน่งปัจจุบันแล้ว
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    checkbox.parentNode.parentNode.appendChild(alert);
                    
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 3000);
                },
                function(error) {
                    checkbox.checked = false;
                    alert('ไม่สามารถดึงตำแหน่งได้: ' + error.message);
                }
            );
        } else {
            checkbox.checked = false;
            alert('เบราว์เซอร์นี้ไม่รองรับการดึงตำแหน่ง');
        }
    }
}
</script>
@endpush
