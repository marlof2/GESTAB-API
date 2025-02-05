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
        Schema::create('block_calendar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained('establishment');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('period', ['allday','morning', 'afternoon', 'night', 'personalized']);
            $table->date('date');
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_calendar');
    }
};
