@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>สมัครสมาชิก - {{ config('app.name') }}
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-user me-1"></i>ชื่อ-นามสกุล
                            </label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="กรอกชื่อ-นามสกุล">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-envelope me-1"></i>อีเมล
                            </label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="example@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-lock me-1"></i>รหัสผ่าน
                            </label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       placeholder="รหัสผ่านอย่างน้อย 8 ตัวอักษร">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-lock me-1"></i>ยืนยันรหัสผ่าน
                            </label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="กรอกรหัสผ่านอีกครั้ง">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        ฉันยอมรับ<a href="#" class="text-primary">เงื่อนไขการใช้งาน</a>และ<a href="#" class="text-primary">นโยบายความเป็นส่วนตัว</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary me-3" id="submitBtn">
                                    <i class="fas fa-user-plus me-1"></i>สมัครสมาชิก
                                </button>
                                <a class="btn btn-link" href="{{ route('login') }}">
                                    มีบัญชีอยู่แล้ว? เข้าสู่ระบบ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>ขั้นตอนถัดไป
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="text-primary mb-2">
                                <i class="fas fa-user-plus fa-3x"></i>
                            </div>
                            <h6>1. สมัครสมาชิก</h6>
                            <small class="text-muted">กรอกข้อมูลเพื่อสร้างบัญชี</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="text-success mb-2">
                                <i class="fas fa-camera fa-3x"></i>
                            </div>
                            <h6>2. ลงทะเบียนใบหน้า</h6>
                            <small class="text-muted">สแกนใบหน้าสำหรับการยืนยันตัวตน</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="text-warning mb-2">
                                <i class="fas fa-map-marker-alt fa-3x"></i>
                            </div>
                            <h6>3. เริ่มลงเวลา</h6>
                            <small class="text-muted">ลงเวลาเข้าออกงานด้วยใบหน้าและ GPS</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Simple loading indicator
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>กำลังสมัครสมาชิก...';
        
        // Re-enable after 10 seconds in case of issues
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus me-1"></i>สมัครสมาชิก';
        }, 10000);
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-top-left-radius: 15px !important;
    border-top-right-radius: 15px !important;
}

.btn {
    border-radius: 25px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.step-card {
    transition: transform 0.2s;
}

.step-card:hover {
    transform: translateY(-5px);
}
</style>
@endpush
@endsection
