<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_loan_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Personal Information
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->date('date_of_birth');
            $table->integer('age');
            $table->string('tax_code', 40);
            $table->string('email', 100);
            $table->string('mobile', 40);
            $table->string('city', 100);
            $table->string('province', 10)->nullable();
            $table->enum('marital_status', ['married', 'single', 'cohabiting']);
            $table->string('family_status', 100)->nullable();
            $table->integer('family_members');
            
            // Employment Information
            $table->string('employer_name', 150);
            $table->enum('contract_type', ['permanent', 'fixed_term', 'part_time']);
            $table->decimal('monthly_net_income', 12, 2);
            $table->integer('employment_length_years');
            $table->date('employment_start_date')->nullable();
            
            // Loan Details
            $table->decimal('loan_amount', 12, 2);
            $table->integer('loan_duration_months');
            $table->enum('loan_purpose', ['home_purchase', 'renovation', 'debt_consolidation', 'personal_use', 'other']);
            $table->string('loan_purpose_other', 200)->nullable();
            $table->text('current_financing_details')->nullable();
            $table->decimal('current_financing_remaining', 12, 2)->nullable();
            
            // Documents (PDF files) - All stored as file paths
            $table->string('doc_certificate_residency')->nullable();
            $table->string('doc_family_status')->nullable();
            $table->string('doc_marital_status')->nullable();
            $table->string('doc_valid_id')->nullable();
            $table->string('doc_health_card')->nullable();
            $table->string('doc_residence_permit')->nullable();
            $table->string('doc_passport')->nullable();
            $table->string('doc_cu_2025')->nullable();
            $table->string('doc_payslips')->nullable(); // JSON array of 5 payslips
            $table->string('doc_bank_statement')->nullable();
            $table->string('doc_transactions_30days')->nullable();
            $table->string('doc_employment_contract')->nullable();
            $table->string('doc_inps_statement')->nullable();
            $table->string('doc_loan_agreement')->nullable();
            $table->string('doc_isee')->nullable();
            
            // Privacy & Status
            $table->boolean('privacy_authorization')->default(false);
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected'])->default('pending');
            $table->integer('current_step')->default(1);
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'status', 'contract_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_loan_applications');
    }
};
