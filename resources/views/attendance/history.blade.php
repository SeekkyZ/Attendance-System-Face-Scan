@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>ประวัติการลงเวลาเข้าออก
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="date" class="form-label">วันที่</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="location_id" class="form-label">สถานที่</label>
                            <select class="form-select" id="location_id" name="location_id">
                                <option value="">ทุกสถานที่</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>ค้นหา
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Attendance List -->
                    @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>วันที่/เวลา</th>
                                    <th>สถานที่</th>
                                    <th>ช่วงเวลา</th>
                                    <th>ประเภท</th>
                                    <th>ระยะทาง</th>
                                    <th>หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div>{{ $attendance->attendance_time->setTimezone('Asia/Bangkok')->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $attendance->attendance_time->setTimezone('Asia/Bangkok')->format('H:i:s') }} น.</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $attendance->location->name }}
                                    </td>
                                    <td>
                                        @php
                                            $sessionLabels = [
                                                'morning' => 'เช้า',
                                                'afternoon' => 'กลางวัน',
                                                'evening' => 'บ่าย',
                                                'night' => 'เย็น'
                                            ];
                                            $sessionColors = [
                                                'morning' => 'bg-warning',
                                                'afternoon' => 'bg-info',
                                                'evening' => 'bg-primary',
                                                'night' => 'bg-dark'
                                            ];
                                        @endphp
                                        <span class="badge {{ $sessionColors[$attendance->session] }}">
                                            {{ $sessionLabels[$attendance->session] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->type === 'check_in')
                                            <span class="badge bg-success">
                                                <i class="fas fa-sign-in-alt me-1"></i>เข้างาน
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-sign-out-alt me-1"></i>ออกงาน
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            {{ number_format($attendance->distance, 2) }} ม.
                                        </span>
                                    </td>
                                    <td>
                                        {{ $attendance->note ?: '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $attendances->withQueryString()->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">ไม่มีข้อมูลการลงเวลา</h5>
                        <p class="text-muted">ลองเปลี่ยนเงื่อนไขการค้นหาหรือ <a href="{{ route('attendance.index') }}">เริ่มลงเวลาใหม่</a></p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistics Card -->
            @if($attendances->count() > 0)
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                            <h4 class="text-primary">{{ $attendances->total() }}</h4>
                            <small class="text-muted">รายการทั้งหมด</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-sign-in-alt fa-2x text-success mb-2"></i>
                            <h4 class="text-success">{{ $attendances->where('type', 'check_in')->count() }}</h4>
                            <small class="text-muted">เข้างาน</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-sign-out-alt fa-2x text-danger mb-2"></i>
                            <h4 class="text-danger">{{ $attendances->where('type', 'check_out')->count() }}</h4>
                            <small class="text-muted">ออกงาน</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-map-marked-alt fa-2x text-info mb-2"></i>
                            <h4 class="text-info">{{ $attendances->pluck('location_id')->unique()->count() }}</h4>
                            <small class="text-muted">สถานที่</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
