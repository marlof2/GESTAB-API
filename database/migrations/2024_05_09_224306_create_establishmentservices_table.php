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
        Schema::create('establishmentservices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establishment_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->timestamps();

            $table->foreign('establishment_id')->references('id')->on('establishment')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
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
