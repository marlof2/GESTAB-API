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
        Schema::create('establishmentprofessional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establishment_id')->nullable();
            $table->unsignedBigInteger('professional_id')->nullable();
            $table->timestamps();

            $table->foreign('establishment_id')->references('id')->on('establishment')->onDelete('set null');
            $table->foreign('professional_id')->references('id')->on('professional')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishmentprofessional');
    }
};
