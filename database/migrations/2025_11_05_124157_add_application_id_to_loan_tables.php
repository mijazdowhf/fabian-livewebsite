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
            $table->string('application_id', 20)->unique()->nullable()->after('id');
        });
        
        Schema::table('selling_mortgage_applications', function (Blueprint $table) {
            $table->string('application_id', 20)->unique()->nullable()->after('id');
        });
        
        Schema::table('employee_loan_applications', function (Blueprint $table) {
            $table->string('application_id', 20)->unique()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_inquiries', function (Blueprint $table) {
            $table->dropUnique(['application_id']);
            $table->dropColumn('application_id');
        });
        
        Schema::table('selling_mortgage_applications', function (Blueprint $table) {
            $table->dropUnique(['application_id']);
            $table->dropColumn('application_id');
        });
        
        Schema::table('employee_loan_applications', function (Blueprint $table) {
            $table->dropUnique(['application_id']);
            $table->dropColumn('application_id');
        });
    }
};
