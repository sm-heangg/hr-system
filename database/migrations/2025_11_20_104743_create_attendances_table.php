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

            // employee who owns this attendance
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            // work date
            $table->date('date');

            // which shift this record is for: e.g. 'morning', 'afternoon'
            $table->string('shift', 20); // we'll use 'morning' | 'afternoon'

            // check-in / check-out time for this shift
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            // status for this shift (lowercase to match your enum)
            $table->enum('status', ['present', 'absent', 'late', 'leave'])->default('present');

            $table->text('notes')->nullable();

            $table->timestamps();

            // prevent duplicate records for same employee + date + shift
            $table->unique(['employee_id', 'date', 'shift']);
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
