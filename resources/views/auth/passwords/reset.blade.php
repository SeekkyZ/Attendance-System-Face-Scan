@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i>สร้างรหัสผ่านใหม่
                    </h5>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-key text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">
                            กรุณาสร้างรหัสผ่านใหม่สำหรับบัญชีของคุณ
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>อีเมลแอดเดรส
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ $email ?? old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   readonly>

                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>รหัสผ่านใหม่
                            </label>
                            <div class="input-group">
                                <input id="password" 
                                       type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="ใส่รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">
                                <i class="fas fa-lock me-1"></i>ยืนยันรหัสผ่านใหม่
                            </label>
                            <div class="input-group">
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control form-control-lg" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="ใส่รหัสผ่านใหม่อีกครั้ง">
                                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="password-match-feedback" class="form-text"></div>
                        </div>

                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-check me-2"></i>รีเซ็ตรหัสผ่าน
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-link">
                            <i class="fas fa-arrow-left me-1"></i>กลับไปหน้า Login
                        </a>
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

.password-strength {
    height: 4px;
    border-radius: 2px;
    margin-top: 5px;
    transition: all 0.3s ease;
}

.strength-weak {
    background-color: #dc3545;
    width: 33%;
}

.strength-medium {
    background-color: #ffc107;
    width: 66%;
}

.strength-strong {
    background-color: #28a745;
    width: 100%;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password-confirm');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const matchFeedback = document.getElementById('password-match-feedback');
    const submitBtn = document.getElementById('submitBtn');
    
    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Check password match
    function checkPasswordMatch() {
        if (passwordConfirm.value === '') {
            matchFeedback.textContent = '';
            matchFeedback.className = 'form-text';
            return;
        }
        
        if (password.value === passwordConfirm.value) {
            matchFeedback.innerHTML = '<i class="fas fa-check text-success me-1"></i>รหัสผ่านตรงกัน';
            matchFeedback.className = 'form-text text-success';
            submitBtn.disabled = false;
        } else {
            matchFeedback.innerHTML = '<i class="fas fa-times text-danger me-1"></i>รหัสผ่านไม่ตรงกัน';
            matchFeedback.className = 'form-text text-danger';
            submitBtn.disabled = true;
        }
    }
    
    password.addEventListener('input', checkPasswordMatch);
    passwordConfirm.addEventListener('input', checkPasswordMatch);
    
    // Form validation
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        if (password.value !== passwordConfirm.value) {
            e.preventDefault();
            alert('รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง');
        }
    });
});
</script>
@endpush
@endsection
