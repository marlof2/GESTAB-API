<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('establishment_id')->constrained('establishment')->onDelete('cascade');
            $table->bigInteger('payment_id');
            $table->bigInteger('preference_id')->nullable();
            $table->integer('plan_id')->comment('1 - mensal, 2 - anual')->nullable();
            $table->string('external_reference', 255)->nullable();
            $table->string('status')->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->integer('quantity_professionals')->nullable();
            $table->boolean('remove_ads_client')->default(false);
            $table->string('period')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('subscription_start')->nullable();
            $table->timestamp('subscription_end')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
