<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            if (!Schema::hasColumn('comics', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('publisher');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            if (Schema::hasColumn('comics', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
};
