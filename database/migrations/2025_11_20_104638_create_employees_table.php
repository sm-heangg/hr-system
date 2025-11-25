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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Linked user account
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Unique employee code (EMP001, EMP002)
            $table->string('employee_code', 191)->unique();

            // Department / Position
            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained()
                ->cascadeOnDelete();

            // HR data
            $table->date('hire_date');
            $table->integer('salary')->nullable();

            // Employee status (active OR stop)
            $table->enum('status', ['active', 'stop'])->default('active');

            // Shift configuration (works with morning & afternoon attendance logic)
            $table->time('shift_morning_start')->nullable();    // e.g. 07:00
            $table->time('shift_morning_end')->nullable();      // e.g. 12:00
            $table->time('shift_afternoon_start')->nullable();  // e.g. 14:00
            $table->time('shift_afternoon_end')->nullable();    // e.g. 17:00

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
