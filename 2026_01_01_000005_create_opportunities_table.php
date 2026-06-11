<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->char('country', 2)->index();
            $table->string('opportunity_type', 30);
            $table->string('verdict', 10);
            $table->decimal('score', 5, 1);
            $table->text('rationale');
            $table->text('score_breakdown')->nullable();
            $table->foreignId('anchor_event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
