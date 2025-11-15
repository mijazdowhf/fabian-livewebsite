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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('referred_by')->nullable()->after('role');
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
            $table->string('referral_code', 20)->unique()->nullable()->after('referred_by');
        });
        
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('referral_bonus', 28, 8)->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referred_by', 'referral_code']);
        });
        
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('referral_bonus');
        });
    }
};
