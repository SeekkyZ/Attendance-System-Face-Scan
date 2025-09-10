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
            $table->string('name'); // ใช้ชื่อแทน user_id
            $table->text('encoding');
            $table->string('image_path')->nullable();
            $table->string('label')->nullable();
            $table->float('confidence')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['name', 'is_active']);
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
