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
        Schema::create('list', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->time("time");
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('services_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('establishment_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('services_id')->references('id')->on('services')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('set null');
            $table->foreign('establishment_id')->references('id')->on('establishment')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list');
    }
};
