@extends('layouts.app')

@push('styles')
<style>
#map {
    border: 2px solid #dee2e6;
}

.pac-container {
    z-index: 1051 !important;
}

#map-container {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.location-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Navigation Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('user-locations') }}">
                            <i class="fas fa-map-marker-alt me-1"></i>สถานที่ของฉัน
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">เพิ่มสถานที่ใหม่</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>เพิ่มสถานที่ใหม่
                    </h5>
                    <a href="{{ route('user-locations') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>กลับ
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('locations.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อสถานที่ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">ละติจูด <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                           step="0.000001" min="-90" max="90" required
                                           placeholder="เช่น 13.756331">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">ค่าบวกสำหรับซีกโลกเหนือ</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">ลองจิจูด <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                           step="0.000001" min="-180" max="180" required
                                           placeholder="เช่น 100.501765">
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">ค่าบวกสำหรับซีกโลกตะวันออก</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Location Presets -->
                        <div class="mb-3">
                            <label class="form-label">ตำแหน่งที่ใช้บ่อย</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setLocation(13.756331, 100.501765, 'สยาม')">
                                    <i class="fas fa-map-pin me-1"></i>สยาม
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setLocation(13.747222, 100.534722, 'เซ็นทรัลเวิลด์')">
                                    <i class="fas fa-map-pin me-1"></i>เซ็นทรัลเวิลด์
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setLocation(13.736717, 100.523186, 'ลุมพินี')">
                                    <i class="fas fa-map-pin me-1"></i>ลุมพินี
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setLocation(13.764946, 100.538362, 'ชิตลม')">
                                    <i class="fas fa-map-pin me-1"></i>ชิตลม
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="radius" class="form-label">รัศมี (เมตร) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('radius') is-invalid @enderror" 
                                   id="radius" name="radius" value="{{ old('radius', 200) }}" 
                                   min="1" max="1000" required>
                            @error('radius')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">กำหนดรัศมีที่อนุญาตให้ลงเวลาได้ (1-1000 เมตร)</div>
                        </div>

                        <!-- Location Picker -->
                        <div class="mb-3">
                            <label class="form-label">เลือกตำแหน่งบนแผนที่</label>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="get-location">
                                    <i class="fas fa-crosshairs me-1"></i>ใช้ตำแหน่งปัจจุบัน
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="show-map-btn">
                                    <i class="fas fa-map me-1"></i>เลือกจากแผนที่
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="openGoogleMaps()">
                                    <i class="fas fa-external-link-alt me-1"></i>เปิด Google Maps
                                </button>
                            </div>
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>วิธีใช้งาน:</strong> กด "ใช้ตำแหน่งปัจจุบัน" หรือ "เปิด Google Maps" เพื่อค้นหาพิกัด แล้วคัดลอกมาใส่ในช่องละติจูดและลองจิจูด
                                </small>
                            </div>
                            <div id="location-status" class="alert alert-info" style="display: none;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="location-message"></span>
                            </div>
                            
                            <!-- Google Maps Container -->
                            <div id="map-container" style="display: none;">
                                <div class="card mt-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-map-marked-alt me-2"></i>เลือกตำแหน่งบนแผนที่</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="hide-map-btn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="map" style="height: 400px; border-radius: 8px;"></div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                คลิกบนแผนที่เพื่อเลือกตำแหน่ง หรือลากเครื่องหมายเพื่อปรับตำแหน่ง
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>ย้อนกลับ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>บันทึกสถานที่
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let map;
let marker;
let geocoder;
let radiusCircle;
let isMapInitialized = false;

function initMap() {
    console.log('Initializing Google Maps...');
    
    // Default location (Bangkok)
    const defaultLat = 13.7563;
    const defaultLng = 100.5018;
    
    // Get current values or use default
    const currentLat = parseFloat(document.getElementById('latitude').value) || defaultLat;
    const currentLng = parseFloat(document.getElementById('longitude').value) || defaultLng;
    
    // Initialize map
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: { lat: currentLat, lng: currentLng },
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true
    });
    
    // Initialize geocoder
    geocoder = new google.maps.Geocoder();
    
    // Initialize marker
    marker = new google.maps.Marker({
        position: { lat: currentLat, lng: currentLng },
        map: map,
        draggable: true,
        title: 'ตำแหน่งที่เลือก'
    });
    
    // Initialize radius circle
    const radiusValue = parseInt(document.getElementById('radius').value) || 200;
    radiusCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.15,
        map: map,
        center: { lat: currentLat, lng: currentLng },
        radius: radiusValue
    });
    
    // Add click event to map
    map.addListener('click', function(event) {
        updateLocation(event.latLng.lat(), event.latLng.lng());
    });
    
    // Add drag event to marker
    marker.addListener('dragend', function(event) {
        updateLocation(event.latLng.lat(), event.latLng.lng());
    });
    
    // Search box
    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'ค้นหาสถานที่...';
    input.className = 'form-control';
    input.style.margin = '10px';
    input.style.width = '300px';
    
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    
    searchBox.addListener('places_changed', function() {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;
        
        const place = places[0];
        if (!place.geometry || !place.geometry.location) return;
        
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        
        updateLocation(lat, lng);
        map.setCenter({ lat, lng });
        map.setZoom(17);
    });
    
    isMapInitialized = true;
    console.log('Google Maps initialized successfully');
}

// Global callback function for Google Maps API
window.initMap = initMap;

function updateLocation(lat, lng) {
    // Update form inputs
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
    
    // Update marker position
    marker.setPosition({ lat, lng });
    
    // Update radius circle center
    if (radiusCircle) {
        radiusCircle.setCenter({ lat, lng });
    }
    
    // Reverse geocoding to get address
    geocoder.geocode({ location: { lat, lng } }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const locationMessage = document.getElementById('location-message');
            const locationStatus = document.getElementById('location-status');
            
            locationMessage.textContent = `ตำแหน่ง: ${results[0].formatted_address}`;
            locationStatus.className = 'alert alert-success';
            locationStatus.style.display = 'block';
        }
    });
}

function updateRadius() {
    const radiusValue = parseInt(document.getElementById('radius').value) || 200;
    if (radiusCircle) {
        radiusCircle.setRadius(radiusValue);
    }
}

function openGoogleMaps() {
    try {
        console.log('Opening Google Maps modal');
        
        const lat = document.getElementById('latitude').value || '13.7563';
        const lng = document.getElementById('longitude').value || '100.5018';
        
        const url = `https://www.google.com/maps?q=${lat},${lng}&z=15`;
        
        // Simple way - just open in new tab
        const newWindow = window.open(url, '_blank');
        
        if (newWindow) {
            // Show success message
            const locationStatus = document.getElementById('location-status');
            const locationMessage = document.getElementById('location-message');
            
            if (locationStatus && locationMessage) {
                locationMessage.textContent = 'เปิด Google Maps แล้ว กรุณาคัดลอกพิกัดกลับมาใส่ในฟอร์ม';
                locationStatus.className = 'alert alert-info';
                locationStatus.style.display = 'block';
            }
        } else {
            alert('ไม่สามารถเปิด Google Maps ได้ กรุณาตรวจสอบการตั้งค่า popup blocker ของเบราว์เซอร์');
        }
    } catch (error) {
        console.error('Error opening Google Maps:', error);
        alert('เกิดข้อผิดพลาดในการเปิด Google Maps');
    }
}

function setLocation(lat, lng, name) {
    try {
        console.log(`Setting location: ${name} (${lat}, ${lng})`);
        
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (!latInput || !lngInput) {
            console.error('Input elements not found');
            return;
        }
        
        latInput.value = lat;
        lngInput.value = lng;
        
        // Update map if it exists
        if (typeof map !== 'undefined' && map && marker) {
            const position = { lat, lng };
            map.setCenter(position);
            marker.setPosition(position);
            if (radiusCircle) {
                radiusCircle.setCenter(position);
            }
        }
        
        // Show success message
        const locationStatus = document.getElementById('location-status');
        const locationMessage = document.getElementById('location-message');
        
        if (locationStatus && locationMessage) {
            locationMessage.textContent = `ตั้งตำแหน่ง: ${name} (${lat}, ${lng})`;
            locationStatus.className = 'alert alert-success';
            locationStatus.style.display = 'block';
        }
    } catch (error) {
        console.error('Error setting location:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    const getLocationBtn = document.getElementById('get-location');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const locationStatus = document.getElementById('location-status');
    const locationMessage = document.getElementById('location-message');
    
    if (!getLocationBtn || !latitudeInput || !longitudeInput || !locationStatus || !locationMessage) {
        console.error('Required elements not found');
        return;
    }
    
    // Add global error handler
    window.addEventListener('error', function(e) {
        console.error('Global error:', e);
    });

    getLocationBtn.addEventListener('click', function() {
        console.log('Get location button clicked');
        
        if (!navigator.geolocation) {
            locationMessage.textContent = 'เบราว์เซอร์ไม่รองรับการใช้ตำแหน่ง';
            locationStatus.className = 'alert alert-warning';
            locationStatus.style.display = 'block';
            return;
        }
        
        getLocationBtn.disabled = true;
        getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังค้นหา...';
        
        const options = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 60000
        };
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                console.log('Position received:', position);
                
                latitudeInput.value = position.coords.latitude.toFixed(6);
                longitudeInput.value = position.coords.longitude.toFixed(6);
                
                locationMessage.textContent = `ได้รับตำแหน่งปัจจุบันเรียบร้อยแล้ว (${position.coords.latitude.toFixed(4)}, ${position.coords.longitude.toFixed(4)})`;
                locationStatus.className = 'alert alert-success';
                locationStatus.style.display = 'block';
                
                getLocationBtn.disabled = false;
                getLocationBtn.innerHTML = '<i class="fas fa-check me-1"></i>ใช้ตำแหน่งปัจจุบัน';
            },
            function(error) {
                console.error('Geolocation error:', error);
                
                let errorMessage = 'ไม่สามารถเข้าถึงตำแหน่งได้: ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'การเข้าถึงตำแหน่งถูกปฏิเสธ กรุณาอนุญาตการเข้าถึงตำแหน่งในเบราว์เซอร์';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'ข้อมูลตำแหน่งไม่พร้อมใช้งาน';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'หมดเวลาในการค้นหาตำแหน่ง';
                        break;
                    default:
                        errorMessage += 'เกิดข้อผิดพลาดที่ไม่ทราบสาเหตุ';
                        break;
                }
                
                locationMessage.textContent = errorMessage;
                locationStatus.className = 'alert alert-danger';
                locationStatus.style.display = 'block';
                
                getLocationBtn.disabled = false;
                getLocationBtn.innerHTML = '<i class="fas fa-crosshairs me-1"></i>ใช้ตำแหน่งปัจจุบัน';
            },
            options
        );
    });
    
    // Show/Hide Map Events
    const showMapBtn = document.getElementById('show-map-btn');
    const hideMapBtn = document.getElementById('hide-map-btn');
    const mapContainer = document.getElementById('map-container');
    
    showMapBtn.addEventListener('click', function() {
        console.log('Show map button clicked');
        
        try {
            // For now, show message about embedded maps
            alert('แผนที่ภายในยังไม่พร้อมใช้งาน กรุณาใช้ปุ่ม "เปิด Google Maps" แทน\nหรือใช้ปุ่มตำแหน่งที่ใช้บ่อยด้านบน');
            
        } catch (error) {
            console.error('Error showing map:', error);
            alert('เกิดข้อผิดพลาดในการแสดงแผนที่');
        }
    });
    
    hideMapBtn.addEventListener('click', function() {
        console.log('Hide map button clicked');
        mapContainer.style.display = 'none';
        showMapBtn.innerHTML = '<i class="fas fa-map me-1"></i>เลือกจากแผนที่';
    });
    
    // Update map when lat/lng inputs change (reuse existing variables)
    // const latitudeInput and longitudeInput already declared above
    
    function updateMapFromInputs() {
        const lat = parseFloat(latitudeInput.value);
        const lng = parseFloat(longitudeInput.value);
        
        if (map && marker && !isNaN(lat) && !isNaN(lng)) {
            const position = { lat, lng };
            map.setCenter(position);
            marker.setPosition(position);
        }
    }
    
    latitudeInput.addEventListener('input', updateMapFromInputs);
    longitudeInput.addEventListener('input', updateMapFromInputs);
    
    // Update radius circle when radius input changes
    const radiusInput = document.getElementById('radius');
    radiusInput.addEventListener('input', updateRadius);
});
</script>
@endpush
@endsection
