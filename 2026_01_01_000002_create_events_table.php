<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('year');
            $table->string('division', 40);
            $table->string('event_type', 40);
            $table->string('status', 20)->default('Scheduled');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->char('country', 2)->index();
            $table->string('region', 40)->nullable();
            $table->string('facility')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index(['country', 'sector_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
