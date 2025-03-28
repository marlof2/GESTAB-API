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
            $table->string('name')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->char("type_schedule", 2)->nullable()->comment('Horario marcado (HM) ou ordem de chegada (OC)');
            $table->boolean('need_profile_complete')->default(false);
            $table->string('avatar')->nullable();
            $table->boolean('terms_accepted')->default(false);
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
