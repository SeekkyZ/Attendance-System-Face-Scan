@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-map-marker-alt text-primary me-2"></i>{{ $location->name }}
                </h2>
                <div>
                    <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>กลับ
                    </a>
                    @can('update', $location)
                        <a href="{{ route('locations.edit', $location) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>แก้ไข
                        </a>
                    @endcan
                </div>
            </div>

            <div class="row">
                <!-- Main Info Card -->
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>ข้อมูลสถานที่
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>ชื่อสถานที่:</strong></div>
                                <div class="col-md-9">{{ $location->name }}</div>
                            </div>
                            
                            @if($location->description)
                                <div class="row mb-3">
                                    <div class="col-md-3"><strong>คำอธิบาย:</strong></div>
                                    <div class="col-md-9">{{ $location->description }}</div>
                                </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>พิกัด GPS:</strong></div>
                                <div class="col-md-9">
                                    {{ number_format($location->latitude, 6) }}, 
                                    {{ number_format($location->longitude, 6) }}
                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="copyCoordinates('{{ $location->latitude }}, {{ $location->longitude }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            @if($location->address)
                                <div class="row mb-3">
                                    <div class="col-md-3"><strong>ที่อยู่:</strong></div>
                                    <div class="col-md-9">{{ $location->address }}</div>
                                </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>รัศมีตรวจสอบ:</strong></div>
                                <div class="col-md-9">
                                    <span class="badge bg-info">{{ $location->radius }} เมตร</span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>สถานะ:</strong></div>
                                <div class="col-md-9">
                                    @if($location->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>เปิดใช้งาน
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>ปิดใช้งาน
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>สร้างเมื่อ:</strong></div>
                                <div class="col-md-9">{{ $location->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            
                            @if($location->updated_at != $location->created_at)
                                <div class="row mb-3">
                                    <div class="col-md-3"><strong>แก้ไขล่าสุด:</strong></div>
                                    <div class="col-md-9">{{ $location->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Map and Actions -->
                <div class="col-lg-4">
                    <!-- Map Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-map me-2"></i>แผนที่
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div id="miniMap" style="height: 200px; background: #f8f9fa; border-radius: 8px; position: relative;">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <i class="fas fa-map-marked-alt fa-3x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">แผนที่ตำแหน่ง</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-sm" onclick="showFullMap()">
                                    <i class="fas fa-expand me-1"></i>ดูแผนที่เต็ม
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="openGoogleMaps()">
                                    <i class="fas fa-external-link-alt me-1"></i>Google Maps
                                </button>
                            </div>
                        </div>
                    </div>



                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>การดำเนินการ
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($location->is_active)
                                <div class="d-grid gap-2">
                                    <a href="{{ route('face.scan') }}?location={{ $location->id }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-user-check me-1"></i>สแกนใบหน้าเพื่อลงเวลา
                                    </a>
                                    <a href="{{ route('attendance.index') }}?location={{ $location->id }}" 
                                       class="btn btn-outline-success">
                                        <i class="fas fa-history me-1"></i>ดูประวัติการลงเวลา
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning text-center mb-0">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    สถานที่นี้ปิดใช้งานชั่วคราว
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users with access -->
            @can('view', $location)
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-users me-2"></i>ผู้ใช้ที่มีสิทธิ์เข้าถึง
                        </h6>
                        <span class="badge bg-info">{{ $location->users()->count() }} คน</span>
                    </div>
                    <div class="card-body">
                        @if($location->users()->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ชื่อ</th>
                                            <th>อีเมล</th>
                                            <th>สถานะ</th>
                                            <th>เข้าร่วมเมื่อ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($location->users()->take(10)->get() as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->pivot->is_active)
                                                        <span class="badge bg-success">เปิด</span>
                                                    @else
                                                        <span class="badge bg-secondary">ปิด</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->pivot->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($location->users()->count() > 10)
                                <div class="text-center">
                                    <a href="{{ route('locations.users', $location) }}" class="btn btn-outline-primary btn-sm">
                                        ดูทั้งหมด ({{ $location->users()->count() }} คน)
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                <p>ยังไม่มีผู้ใช้ที่มีสิทธิ์เข้าถึงสถานที่นี้</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-map me-2"></i>{{ $location->name }} - แผนที่
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="fullMap" style="height: 500px;"></div>
                <div class="mt-3 text-center">
                    <strong>พิกัด:</strong> {{ $location->latitude }}, {{ $location->longitude }} |
                    <strong>รัศมี:</strong> {{ $location->radius }} เมตร
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Copy coordinates
function copyCoordinates(coords) {
    navigator.clipboard.writeText(coords).then(function() {
        // Show toast or alert
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        alert.innerHTML = `
            <i class="fas fa-check me-1"></i>คัดลอกพิกัดเรียบร้อย
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    });
}

// Show full map
function showFullMap() {
    const modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();
    
    document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
        loadFullMap();
    });
}

// Load full map
function loadFullMap() {
    const lat = {{ $location->latitude }};
    const lng = {{ $location->longitude }};
    const mapDiv = document.getElementById('fullMap');
    
    mapDiv.innerHTML = `
        <iframe 
            src="https://www.openstreetmap.org/export/embed.html?bbox=${lng-0.01}%2C${lat-0.01}%2C${lng+0.01}%2C${lat+0.01}&marker=${lat}%2C${lng}" 
            style="border: 1px solid #ddd; width: 100%; height: 500px; border-radius: 8px;"
            frameborder="0" 
            scrolling="no">
        </iframe>
    `;
}

// Open in Google Maps
function openGoogleMaps() {
    const url = `https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}`;
    window.open(url, '_blank');
}
</script>
@endpush

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    
    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0 !important;
    }
    

    
    .badge {
        font-size: 0.85rem;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush
@endsection
