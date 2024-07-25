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
            $table->string('responsible');
            $table->string('cpf')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('phone')->nullable();
            $table->boolean("type_schedule")->comment('Horario marcado (1) ou ordem de chegada (2)');
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
