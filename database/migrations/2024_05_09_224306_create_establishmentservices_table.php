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
        Schema::create('establishment_services', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('establishment_id')->constrained('establishment')->cascadeOnDelete();
            // $table->foreignId('service_id')->constrained('services');
            // $table->string('name', 100);
            // $table->decimal('amount')->nullable();
            // $table->time('time')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishmentservices');
    }
};
