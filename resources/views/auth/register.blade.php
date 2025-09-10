@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">สมัครสมาชิก</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- ลบหน้า register ออก ไม่ใช้ระบบลงทะเบียน -->
                    <p>ระบบลงทะเบียนถูกปิดใช้งาน</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
