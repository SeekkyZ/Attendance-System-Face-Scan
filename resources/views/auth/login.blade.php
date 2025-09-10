@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                    <!-- ลบหน้า login ออก ไม่ใช้ระบบ login -->
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        <i class="fas fa-key me-1"></i>ลืมรหัสผ่าน?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
