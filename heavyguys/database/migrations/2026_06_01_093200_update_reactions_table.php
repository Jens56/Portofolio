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
        Schema::table('reactions', function (Blueprint $table) {
            $table->text('content')->nullable();
            $table->dropColumn('type');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropColumn('content');
            $table->enum('type', ['like', 'dislike'])->default('like');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
