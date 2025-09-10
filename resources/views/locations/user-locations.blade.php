@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>สถานที่ที่ฉันมีสิทธิ์เข้าถึง
                        </h5>
                        <span class="badge bg-primary mt-1">{{ isset($userLocations) ? count($userLocations) : 0 }} สถานที่</span>
                    </div>
                    <a href="{{ route('locations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>เพิ่มสถานที่ใหม่
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($userLocations) && count($userLocations) > 0)
                        <div class="row">
                            @foreach($userLocations as $location)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="card-title mb-1">{{ $location->name }}</h6>
                                                    @if($location->description)
                                                        <p class="card-text text-muted small mb-2">{{ $location->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="location-details">
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <div class="border-end">
                                                            <h6 class="text-primary mb-1">{{ $location->radius }}m</h6>
                                                            <small class="text-muted">รัศมี</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <h6 class="text-success mb-1">
                                                            @if($location->pivot->is_active)
                                                                <i class="fas fa-check-circle"></i> เปิดใช้งาน
                                                            @else
                                                                <i class="fas fa-times-circle text-danger"></i> ปิดใช้งาน
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted">สถานะ</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <hr class="my-3">
                                            
                                            <div class="location-info">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-map-pin text-muted me-2"></i>
                                                    <small class="text-muted">
                                                        {{ number_format($location->latitude, 6) }}, 
                                                        {{ number_format($location->longitude, 6) }}
                                                    </small>
                                                </div>
                                                
                                                @if($location->address)
                                                    <div class="d-flex align-items-start mb-2">
                                                        <i class="fas fa-location-dot text-muted me-2 mt-1"></i>
                                                        <small class="text-muted">{{ $location->address }}</small>
                                                    </div>
                                                @endif
                                                
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-calendar-plus text-muted me-2"></i>
                                                    <small class="text-muted">
                                                        เข้าร่วมเมื่อ {{ $location->pivot->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 d-grid gap-2">
                                                @if($location->pivot->is_active)
                                                    <a href="{{ route('face.scan') }}?location={{ $location->id }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-user-check me-1"></i>สแกนใบหน้าเพื่อลงเวลา
                                                    </a>
                                                    
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            onclick="showLocationOnMap({{ $location->latitude }}, {{ $location->longitude }}, '{{ $location->name }}')">
                                                        <i class="fas fa-map me-1"></i>ดูบนแผนที่
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-ban me-1"></i>ไม่สามารถใช้งานได้
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($userLocations instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center">
                                {{ $userLocations->links() }}
                            </div>
                        @endif
                        
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-map-marker-alt text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted mb-3">ยังไม่มีสถานที่ที่คุณสามารถเข้าถึงได้</h5>
                            <p class="text-muted mb-4">
                                คุณสามารถสร้างสถานที่ใหม่ หรือติดต่อผู้ดูแลระบบเพื่อขอสิทธิ์ในการเข้าถึงสถานที่ต่างๆ
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('locations.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>เพิ่มสถานที่ใหม่
                                </a>
                                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>กลับหน้าหลัก
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-map me-2"></i><span id="modalLocationName">ตำแหน่งบนแผนที่</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px; width: 100%;"></div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>พิกัด:</strong>
                            <span id="mapCoordinates"></span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button class="btn btn-sm btn-outline-primary" onclick="openInGoogleMaps()">
                                <i class="fas fa-external-link-alt me-1"></i>เปิดใน Google Maps
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .location-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
    }
    
    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
    
    .badge {
        font-size: 0.9rem;
    }
    
    .location-info small {
        font-size: 0.85rem;
    }
    
    .border-end {
        border-right: 1px solid #dee2e6 !important;
    }
</style>
@endpush

@push('scripts')
<script>
let currentLat, currentLng, currentName;

function showLocationOnMap(lat, lng, name) {
    currentLat = lat;
    currentLng = lng;
    currentName = name;
    
    document.getElementById('modalLocationName').textContent = name;
    document.getElementById('mapCoordinates').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // แสดง modal
    const modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();
    
    // รอให้ modal แสดงเสร็จก่อนโหลดแผนที่
    document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
        initMap();
    });
}

function initMap() {
    // ใช้ OpenStreetMap แทน Google Maps เพื่อไม่ต้องใช้ API key
    const mapDiv = document.getElementById('map');
    mapDiv.innerHTML = `
        <iframe 
            src="https://www.openstreetmap.org/export/embed.html?bbox=${currentLng-0.01}%2C${currentLat-0.01}%2C${currentLng+0.01}%2C${currentLat+0.01}&marker=${currentLat}%2C${currentLng}" 
            style="border: 1px solid black; width: 100%; height: 400px;"
            frameborder="0" 
            scrolling="no" 
            marginheight="0" 
            marginwidth="0">
        </iframe>
    `;
}

function openInGoogleMaps() {
    const url = `https://www.google.com/maps?q=${currentLat},${currentLng}`;
    window.open(url, '_blank');
}
</script>
@endpush
@endsection
