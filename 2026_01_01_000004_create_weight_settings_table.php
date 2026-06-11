<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weight_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('w_potential')->default(35);
            $table->unsignedTinyInteger('w_competition')->default(25);
            $table->unsignedTinyInteger('w_audience')->default(20);
            $table->unsignedTinyInteger('w_internal')->default(20);
            $table->unsignedTinyInteger('go_threshold')->default(70);
            $table->unsignedTinyInteger('review_threshold')->default(45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weight_settings');
    }
};
