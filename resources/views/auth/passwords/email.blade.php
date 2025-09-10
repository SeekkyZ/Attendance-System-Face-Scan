@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>รีเซ็ตรหัสผ่าน
                    </h5>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open text-primary" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">
                            กรุณาใส่อีเมลของคุณเพื่อรับลิงก์สำหรับสร้างรหัสผ่านใหม่
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>อีเมลแอดเดรส
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="กรุณาใส่อีเมลของคุณ">

                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>ส่งลิงก์รีเซ็ตรหัสผ่าน
                            </button>
                            
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับไปหน้า Login
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            หากคุณไม่ได้รับอีเมลภายใน 5 นาที กรุณาตรวจสอบในโฟลเดอร์ Spam
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.btn-lg {
    padding: 12px 24px;
}

.form-control-lg {
    padding: 12px 16px;
}
</style>
@endpush
@endsection
