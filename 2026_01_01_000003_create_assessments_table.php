<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();
            $table->char('country', 2);
            $table->unsignedTinyInteger('market_potential')->default(3);
            $table->unsignedTinyInteger('competition_intensity')->default(3);
            $table->unsignedTinyInteger('audience_access')->default(3);
            $table->unsignedTinyInteger('internal_strength')->default(3);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['sector_id', 'country']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
