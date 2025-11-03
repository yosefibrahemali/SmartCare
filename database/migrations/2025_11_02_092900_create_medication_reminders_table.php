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
        Schema::create('medication_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->onDelete('cascade');
            $table->string('medication_name')->nullable();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('relative_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('notify_at'); // وقت إرسال التذكير
            $table->enum('status', ['pending', 'confirmed', 'snoozed'])->default('pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_reminders');
    }
};
