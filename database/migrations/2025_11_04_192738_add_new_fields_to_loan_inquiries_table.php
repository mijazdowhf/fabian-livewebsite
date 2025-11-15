<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_inquiries', function (Blueprint $table) {
            // Remove old fields that will be replaced
            $table->dropColumn(['employment_type', 'loan_purpose', 'loan_purpose_other', 'has_current_financing', 'current_financing_remaining', 'date_of_birth', 'tax_code', 'city']);
        });

        Schema::table('loan_inquiries', function (Blueprint $table) {
            // Personal Information
            $table->date('date_of_birth')->after('last_name');
            $table->string('tax_code', 40)->after('date_of_birth');
            $table->string('city', 100)->after('mobile');
            $table->string('province', 10)->after('city')->nullable();
            $table->enum('marital_status', ['married', 'single', 'cohabiting'])->after('province');
            $table->enum('applicant_type', ['single', 'joint'])->after('marital_status');
            $table->integer('age')->after('applicant_type');
            $table->integer('family_members')->after('age');
            
            // Employment Information
            $table->enum('occupation', ['permanent_employee', 'self_employed', 'retired'])->after('family_members');
            $table->enum('contract_type', ['private', 'public', 'self_employed', 'retired'])->after('occupation');
            $table->decimal('monthly_net_income', 12, 2)->after('contract_type');
            $table->integer('employment_length_years')->after('monthly_net_income');
            
            // Loan Details
            $table->enum('application_type', ['personal_loan'])->default('personal_loan')->after('employment_length_years');
            $table->enum('loan_purpose', ['home_furnishings', 'debt_consolidation', 'liquidity', 'other'])->after('application_type');
            $table->string('loan_purpose_other', 200)->nullable()->after('loan_purpose');
            $table->text('current_financing_details')->nullable()->after('loan_purpose_other');
            $table->decimal('current_financing_remaining', 12, 2)->nullable()->after('current_financing_details');
            
            // Privacy
            $table->boolean('privacy_authorization')->default(false)->after('current_financing_remaining');
            
            // Status tracking
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected'])->default('pending')->after('privacy_authorization');
            $table->integer('current_step')->default(1)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('loan_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'province', 'marital_status', 'applicant_type', 'age', 'family_members',
                'occupation', 'contract_type', 'monthly_net_income', 'employment_length_years',
                'application_type', 'loan_purpose_other', 'current_financing_details', 
                'privacy_authorization', 'status', 'current_step'
            ]);
        });

        Schema::table('loan_inquiries', function (Blueprint $table) {
            // Restore old fields
            $table->date('date_of_birth')->after('last_name');
            $table->string('tax_code', 40)->after('date_of_birth');
            $table->string('city', 100)->after('mobile');
            $table->enum('employment_type', ['employee','self_employed','retired','other'])->after('city');
            $table->decimal('net_income', 12, 2)->after('employment_type');
            $table->string('loan_purpose', 120)->after('net_income');
            $table->string('loan_purpose_other', 120)->nullable()->after('loan_purpose');
            $table->boolean('has_current_financing')->default(false)->after('loan_purpose_other');
            $table->decimal('current_financing_remaining', 12, 2)->nullable()->after('has_current_financing');
        });
    }
};
