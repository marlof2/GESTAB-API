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
        Schema::create('professional', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("cpf", 11);
            $table->string("email");
            $table->string("phone");
            $table->string("password");
            $table->boolean("is_active");
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional');
    }
};
