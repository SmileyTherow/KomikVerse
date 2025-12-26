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
        // Pastikan table users ada sebelum modifikasi
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom gender (cek dulu)
            if (! Schema::hasColumn('users', 'gender')) {
                // Jika kolom birthdate ada, letakkan setelahnya; kalau tidak, tambahkan tanpa posisi khusus
                if (Schema::hasColumn('users', 'birthdate')) {
                    $table->string('gender', 20)->nullable()->after('birthdate');
                } else {
                    $table->string('gender', 20)->nullable();
                }
            }

            // Tambah kolom bio (cek dulu)
            if (! Schema::hasColumn('users', 'bio')) {
                // Jika kolom gender ada (mungkin barusan ditambahkan), letakkan setelah gender; kalau tidak, tambahkan biasa
                if (Schema::hasColumn('users', 'gender')) {
                    $table->text('bio')->nullable()->after('gender');
                } else {
                    $table->text('bio')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bio')) {
                $table->dropColumn('bio');
            }
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
