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
            $table->unsignedBigInteger('agent_id')->nullable()->after('user_id');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
        
        Schema::table('selling_mortgage_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('user_id');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
        
        Schema::table('employee_loan_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('user_id');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_inquiries', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
        
        Schema::table('selling_mortgage_applications', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
        
        Schema::table('employee_loan_applications', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
