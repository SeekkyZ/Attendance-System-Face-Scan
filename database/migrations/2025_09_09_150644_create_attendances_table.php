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
            $table->string('name'); // ใช้ชื่อแทน user_id
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->enum('session', ['morning', 'afternoon', 'evening', 'night']);
            $table->enum('type', ['check_in', 'check_out']);
            $table->datetime('attendance_time');
            $table->decimal('user_latitude', 10, 8);
            $table->decimal('user_longitude', 11, 8);
            $table->decimal('distance', 8, 2);
            $table->string('note')->nullable();
            $table->timestamps();
            $table->index(['name', 'attendance_time']);
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
