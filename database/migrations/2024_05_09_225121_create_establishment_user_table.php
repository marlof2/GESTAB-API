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
        Schema::create('establishment_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id')->constrained('establishment')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('have_plan_establishment')->default(false);
            $table->string('created_by_functionality')->comment('Por qual funcionalidade foi criado o registo. ME = para Meu estabelecimento e EP = Estabelecimento profissional')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishment_user');
    }
};
