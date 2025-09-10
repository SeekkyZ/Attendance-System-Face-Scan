@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>จัดการสถานที่
                    </h5>
                    <a href="{{ route('locations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>เพิ่มสถานที่ใหม่
                    </a>
                </div>
                <div class="card-body">
                    @if($locations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ชื่อสถานที่</th>
                                    <th>คำอธิบาย</th>
                                    <th>ตำแหน่ง</th>
                                    <th>รัศมี</th>
                                    <th>ผู้ใช้</th>
                                    <th>สถานะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $location)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $location->name }}</div>
                                        <small class="text-muted">{{ $location->description ?? 'ไม่มีคำอธิบาย' }}</small>
                                    </td>
                                    <td>{{ Str::limit($location->description, 50) ?: '-' }}</td>
                                    <td>
                                        <small>
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ number_format($location->latitude, 6) }},<br>
                                            {{ number_format($location->longitude, 6) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $location->radius }} เมตร
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $location->users->count() }} คน
                                        </span>
                                    </td>
                                    <td>
                                        @if($location->is_active)
                                            <span class="badge bg-success">เปิดใช้งาน</span>
                                        @else
                                            <span class="badge bg-danger">ปิดใช้งาน</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-outline-primary" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('locations.users', $location) }}" class="btn btn-sm btn-outline-info" title="จัดการผู้ใช้">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="ลบ" onclick="confirmDelete({{ $location->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $location->id }}" action="{{ route('locations.destroy', $location) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $locations->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">ยังไม่มีสถานที่</h5>
                        <p class="text-muted">เริ่มต้นด้วยการ <a href="{{ route('locations.create') }}">เพิ่มสถานที่ใหม่</a></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(locationId) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบสถานที่นี้? การกระทำนี้ไม่สามารถยกเลิกได้')) {
        document.getElementById('delete-form-' + locationId).submit();
    }
}
</script>
@endpush
@endsection
