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
            $table->string('stripe_account_id', 100)->nullable()->after('referral_code');
            $table->string('stripe_email', 100)->nullable()->after('stripe_account_id');
            $table->boolean('stripe_connected')->default(false)->after('stripe_email');
            $table->timestamp('stripe_connected_at')->nullable()->after('stripe_connected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_account_id', 'stripe_email', 'stripe_connected', 'stripe_connected_at']);
        });
    }
};
