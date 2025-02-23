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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable();
            $table->foreignId('profile_id')->constrained('profiles');
            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->char("type_schedule", 2)->nullable()->comment('Horario marcado (HM) ou ordem de chegada (OC)');
            $table->string('avatar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
