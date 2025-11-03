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
       
        Schema::create('medication_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')->constrained('medications')->onDelete('cascade');
            $table->time('time'); // مثل 08:00 صباحًا
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_schedules');
    }
};
