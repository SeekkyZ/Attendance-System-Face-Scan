<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('face_encodings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('encoding'); // เก็บ face encoding เป็น JSON
            $table->string('image_path')->nullable(); // เส้นทางรูปภาพใบหน้า
            $table->string('label')->nullable(); // ป้ายกำกับ เช่น "หน้าหลัก", "หน้าข้าง"
            $table->float('confidence')->default(0); // ค่าความมั่นใจในการจดจำ
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_encodings');
    }
};
