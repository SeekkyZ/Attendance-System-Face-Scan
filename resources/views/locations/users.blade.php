@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
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
                    <li class="breadcrumb-item active" aria-current="page">จัดการผู้ใช้</li>
                </ol>
            </nav>

            <div class="row">
                <!-- Location Info -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-building me-2"></i>{{ $location->name }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-2">{{ $location->description ?? 'ไม่มีคำอธิบาย' }}</p>
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}
                            </small><br>
                            <small class="text-muted">
                                <i class="fas fa-circle-dot me-1"></i>รัศมี {{ $location->radius }} เมตร
                            </small>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-users me-2"></i>ผู้ใช้ที่มีสิทธิ์เข้าถึง
                                <span class="badge bg-primary">{{ $location->users->count() }}</span>
                            </h6>
                            <a href="{{ route('locations.show', $location) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>กลับ
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Add User Form -->
                            <div class="mb-4">
                                <form method="POST" action="{{ route('locations.add-user', $location) }}" class="row g-3">
                                    @csrf
                                    <div class="col-md-8">
                                        <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                            <option value="">เลือกผู้ใช้ที่ต้องการเพิ่ม...</option>
                                            @foreach($availableUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-plus me-1"></i>เพิ่มผู้ใช้
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Users List -->
                            @if($location->users->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ชื่อผู้ใช้</th>
                                                <th>อีเมล</th>
                                                <th>สถานะ</th>
                                                <th>วันที่เพิ่ม</th>
                                                <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($location->users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 32px; height: 32px; font-size: 14px;">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>
                                                            <span>{{ $user->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if($user->pivot->is_active)
                                                            <span class="badge bg-success">เปิดใช้งาน</span>
                                                        @else
                                                            <span class="badge bg-secondary">ปิดใช้งาน</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $user->pivot->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="{{ route('locations.remove-user', $location) }}" 
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้คนนี้?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h6 class="text-muted">ยังไม่มีผู้ใช้ที่มีสิทธิ์เข้าถึงสถานที่นี้</h6>
                                    <p class="text-muted">เพิ่มผู้ใช้ด้านบนเพื่อให้สามารถเข้าถึงสถานที่นี้ได้</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
