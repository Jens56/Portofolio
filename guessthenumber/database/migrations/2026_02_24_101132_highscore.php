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
        Schema::create('highscore', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->integer('attempts');
            $table->integer('score');
            $table->integer('time_taken')->nullable()->comment('Tijd in seconden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highscore');
    }
};
