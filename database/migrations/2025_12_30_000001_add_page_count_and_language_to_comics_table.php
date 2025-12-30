<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            if (!Schema::hasColumn('comics', 'page_count')) {
                $table->integer('page_count')->default(0)->after('description');
            }
            if (!Schema::hasColumn('comics', 'language')) {
                $table->string('language')->nullable()->after('page_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            $table->dropColumn(['page_count', 'language']);
        });
    }
};
