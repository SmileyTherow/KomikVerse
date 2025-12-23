<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            // add new hashed column and attempts; keep old plain `code` temporarily (if exists)
            if (!Schema::hasColumn('otps', 'code_hash')) {
                $table->string('code_hash')->nullable()->after('code');
            }
            if (!Schema::hasColumn('otps', 'attempts')) {
                $table->unsignedInteger('attempts')->default(0)->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            if (Schema::hasColumn('otps', 'code_hash')) {
                $table->dropColumn('code_hash');
            }
            if (Schema::hasColumn('otps', 'attempts')) {
                $table->dropColumn('attempts');
            }
        });
    }
};
