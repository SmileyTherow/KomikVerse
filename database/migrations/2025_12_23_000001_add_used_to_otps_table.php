<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('otps') && ! Schema::hasColumn('otps', 'used')) {
            Schema::table('otps', function (Blueprint $table) {
                $table->boolean('used')->default(false)->after('code_hash');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('otps') && Schema::hasColumn('otps', 'used')) {
            Schema::table('otps', function (Blueprint $table) {
                $table->dropColumn('used');
            });
        }
    }
};
