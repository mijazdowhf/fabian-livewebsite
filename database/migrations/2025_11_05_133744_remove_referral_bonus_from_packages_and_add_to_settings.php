<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add referral_bonus to general_settings first
        if (!Schema::hasColumn('general_settings', 'referral_bonus')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->decimal('referral_bonus', 28, 8)->default(10.00);
            });
        }
        
        // Remove referral_bonus from packages table if it exists
        if (Schema::hasColumn('packages', 'referral_bonus')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('referral_bonus');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('referral_bonus', 28, 8)->default(0)->after('price');
        });
        
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('referral_bonus');
        });
    }
};
