<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selling_mortgage_applications', function (Blueprint $table) {
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
            
            // Business Information
            $table->string('business_name', 150);
            $table->string('vat_number', 50);
            $table->enum('business_type', ['sole_proprietor', 'partnership', 'corporation', 'other']);
            $table->integer('business_years');
            $table->decimal('annual_revenue', 12, 2);
            $table->decimal('monthly_net_income', 12, 2);
            
            // Mortgage Details
            $table->decimal('mortgage_amount', 12, 2);
            $table->integer('mortgage_duration_months');
            $table->enum('property_type', ['residential', 'commercial', 'mixed']);
            $table->string('property_location', 200)->nullable();
            $table->text('mortgage_purpose')->nullable();
            $table->text('current_financing_details')->nullable();
            $table->decimal('current_financing_remaining', 12, 2)->nullable();
            
            // Documents (PDF files)
            $table->string('doc_certificate_residency')->nullable();
            $table->string('doc_family_status')->nullable();
            $table->string('doc_marital_status')->nullable();
            $table->string('doc_valid_id')->nullable();
            $table->string('doc_health_card')->nullable();
            $table->string('doc_residence_permit')->nullable();
            $table->string('doc_tax_return_2025')->nullable();
            $table->string('doc_tax_return_2024')->nullable();
            $table->string('doc_electronic_receipt_2025')->nullable();
            $table->string('doc_electronic_receipt_2024')->nullable();
            $table->string('doc_vat_assignment')->nullable();
            $table->string('doc_bank_statement')->nullable();
            $table->string('doc_transactions_30days')->nullable();
            $table->string('doc_loan_agreement')->nullable();
            
            // Privacy & Status
            $table->boolean('privacy_authorization')->default(false);
            $table->enum('status', ['pending', 'processing', 'approved', 'rejected'])->default('pending');
            $table->integer('current_step')->default(1);
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['user_id', 'status', 'business_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selling_mortgage_applications');
    }
};
