<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('comic_likes')) {
            Schema::create('comic_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('comic_id')->constrained('comics')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'comic_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('comic_likes');
    }
};
