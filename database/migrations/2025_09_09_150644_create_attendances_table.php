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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->enum('session', ['morning', 'afternoon', 'evening', 'night']); // เช้า, กลางวัน, บ่าย, เย็น
            $table->enum('type', ['check_in', 'check_out']); // เข้า หรือ ออก
            $table->datetime('attendance_time');
            $table->decimal('user_latitude', 10, 8);
            $table->decimal('user_longitude', 11, 8);
            $table->decimal('distance', 8, 2); // ระยะทางในหน่วยเมตร
            $table->string('note')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'attendance_time']);
            $table->index(['location_id', 'attendance_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
