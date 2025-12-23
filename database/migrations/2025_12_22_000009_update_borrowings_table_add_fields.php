<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowings', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable()->after('user_id')->index();
                $table->foreign('admin_id')->references('id')->on('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('borrowings', 'status')) {
                $table->string('status')->default('requested')->after('admin_id')->index();
            }

            if (!Schema::hasColumn('borrowings', 'requested_at')) {
                $table->timestamp('requested_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('borrowings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('requested_at');
            }
            if (!Schema::hasColumn('borrowings', 'borrowed_at')) {
                $table->timestamp('borrowed_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('borrowings', 'due_at')) {
                $table->timestamp('due_at')->nullable()->after('borrowed_at');
            }
            if (!Schema::hasColumn('borrowings', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('due_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (Schema::hasColumn('borrowings', 'admin_id')) {
                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            }

            if (Schema::hasColumn('borrowings', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('borrowings', 'requested_at')) {
                $table->dropColumn('requested_at');
            }
            if (Schema::hasColumn('borrowings', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('borrowings', 'borrowed_at')) {
                $table->dropColumn('borrowed_at');
            }
            if (Schema::hasColumn('borrowings', 'due_at')) {
                $table->dropColumn('due_at');
            }
            if (Schema::hasColumn('borrowings', 'returned_at')) {
                $table->dropColumn('returned_at');
            }
        });
    }
};
