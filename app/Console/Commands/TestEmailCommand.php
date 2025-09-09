<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?: $this->ask('กรุณาใส่อีเมลที่ต้องการทดสอบ');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('รูปแบบอีเมลไม่ถูกต้อง');
            return;
        }

        $this->info('กำลังส่งอีเมลทดสอบไปยง: ' . $email);

        try {
            Mail::raw('นี่คือการทดสอบการส่งอีเมลจากระบบลงเวลาเข้าออก Laravel', function($message) use ($email) {
                $message->to($email)
                       ->subject('ทดสอบระบบส่งอีเมล - ' . config('app.name'))
                       ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info('✅ ส่งอีเมลทดสอบสำเร็จ!');
            $this->line('กรุณาตรวจสอบอีเมลของคุณ (รวมทั้งโฟลเดอร์ Spam)');

        } catch (\Exception $e) {
            $this->error('❌ เกิดข้อผิดพลาดในการส่งอีเมล:');
            $this->error($e->getMessage());
            
            $this->line('');
            $this->info('คำแนะนำการแก้ไข:');
            $this->line('1. ตรวจสอบการตั้งค่า SMTP ใน .env');
            $this->line('2. ตรวจสอบ App Password (หาก use Gmail)');
            $this->line('3. รันคำสั่ง: php artisan config:clear');
        }
    }
}
