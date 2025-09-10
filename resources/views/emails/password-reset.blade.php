@component('mail::message')
# รีเซ็ตรหัสผ่าน

คุณได้รับอีเมลนี้เนื่องจากเราได้รับคำขอรีเซ็ตรหัสผ่านสำหรับบัญชีของคุณ

@component('mail::button', ['url' => $url, 'color' => 'primary'])
รีเซ็ตรหัสผ่าน
@endcomponent

ลิงก์รีเซ็ตรหัสผ่านนี้จะหมดอายุใน {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} นาที

หากคุณไม่ได้ขอรีเซ็ตรหัสผ่าน ไม่จำเป็นต้องดำเนินการใดๆ

ขอแสดงความนับถือ<br>
{{ config('app.name') }}

@slot('subcopy')
หากคุณมีปัญหาในการคลิกปุ่ม "รีเซ็ตรหัสผ่าน" ให้คัดลอกและวาง URL ด้านล่างนี้ในเว็บเบราว์เซอร์ของคุณ:
<span class="break-all">[{{ $url }}]({{ $url }})</span>
@endslot
@endcomponent
