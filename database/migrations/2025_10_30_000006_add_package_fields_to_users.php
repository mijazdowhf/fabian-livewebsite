<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'package_paid_at')) {
                $table->timestamp('package_paid_at')->nullable()->after('package_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'package_paid_at')) {
                $table->dropColumn('package_paid_at');
            }
            if (Schema::hasColumn('users', 'package_id')) {
                $table->dropColumn('package_id');
            }
        });
    }
};


