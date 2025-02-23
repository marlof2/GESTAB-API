<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('list_id')->constrained('list');
            $table->string('google_event_id');
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('client_email');
            $table->string('html_link')->nullable();
            $table->timestamps();

            $table->index('google_event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
