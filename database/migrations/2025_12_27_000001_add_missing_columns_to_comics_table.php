<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('comics')) {
            // Jika table comics belum ada, buat table dasar
            Schema::create('comics', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->nullable()->unique();
                $table->string('author')->nullable();
                $table->string('publisher')->nullable();
                $table->integer('year')->nullable();
                $table->string('language')->nullable();
                $table->string('isbn')->nullable();
                $table->text('description')->nullable();
                $table->integer('stock')->default(0);
                $table->string('status')->default('available');
                $table->string('cover_path')->nullable();
                $table->timestamps();
            });
            return;
        }

        // Jika table sudah ada, tambahkan kolom yang mungkin belum ada
        Schema::table('comics', function (Blueprint $table) {
            if (! Schema::hasColumn('comics', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }
            if (! Schema::hasColumn('comics', 'author')) {
                $table->string('author')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('comics', 'publisher')) {
                $table->string('publisher')->nullable()->after('author');
            }
            if (! Schema::hasColumn('comics', 'year')) {
                $table->integer('year')->nullable()->after('publisher');
            }
            if (! Schema::hasColumn('comics', 'language')) {
                $table->string('language')->nullable()->after('year');
            }
            if (! Schema::hasColumn('comics', 'isbn')) {
                $table->string('isbn')->nullable()->after('language');
            }
            if (! Schema::hasColumn('comics', 'description')) {
                $table->text('description')->nullable()->after('isbn');
            }
            if (! Schema::hasColumn('comics', 'stock')) {
                $table->integer('stock')->default(0)->after('description');
            }
            if (! Schema::hasColumn('comics', 'status')) {
                $table->string('status')->default('available')->after('stock');
            }
            if (! Schema::hasColumn('comics', 'cover_path')) {
                $table->string('cover_path')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comics', function (Blueprint $table) {
            if (Schema::hasColumn('comics', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('comics', 'author')) {
                $table->dropColumn('author');
            }
            if (Schema::hasColumn('comics', 'publisher')) {
                $table->dropColumn('publisher');
            }
            if (Schema::hasColumn('comics', 'year')) {
                $table->dropColumn('year');
            }
            if (Schema::hasColumn('comics', 'language')) {
                $table->dropColumn('language');
            }
            if (Schema::hasColumn('comics', 'isbn')) {
                $table->dropColumn('isbn');
            }
            if (Schema::hasColumn('comics', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('comics', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('comics', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('comics', 'cover_path')) {
                $table->dropColumn('cover_path');
            }
        });
    }
};
