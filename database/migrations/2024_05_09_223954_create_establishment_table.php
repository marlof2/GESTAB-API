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
        Schema::create('establishment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_of_person_id')->constrained('type_of_person');
            $table->string('name');
            $table->foreignId('responsible_id')->constrained('users');
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('client_can_schedule')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishment');
    }
};
