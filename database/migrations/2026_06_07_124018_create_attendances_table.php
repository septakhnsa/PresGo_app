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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('presence_location_id')->nullable()->constrained('presence_locations')->onDelete('set null');
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->double('lat_in')->nullable();
            $table->double('long_in')->nullable();
            $table->double('lat_out')->nullable();
            $table->double('long_out')->nullable();
            $table->string('photo_in')->nullable();
            $table->string('photo_out')->nullable();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa', 'terlambat'])->default('alpa');
            $table->text('notes')->nullable();
            $table->boolean('is_verified_in')->default(false);
            $table->boolean('is_verified_out')->default(false);
            $table->timestamps();
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
