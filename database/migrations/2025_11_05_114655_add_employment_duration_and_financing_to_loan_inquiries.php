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
        Schema::table('loan_inquiries', function (Blueprint $table) {
            $table->string('employment_duration_type', 50)->nullable()->after('occupation');
            $table->boolean('has_current_financing')->default(false)->after('loan_purpose_other');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_inquiries', function (Blueprint $table) {
            $table->dropColumn(['employment_duration_type', 'has_current_financing']);
        });
    }
};
