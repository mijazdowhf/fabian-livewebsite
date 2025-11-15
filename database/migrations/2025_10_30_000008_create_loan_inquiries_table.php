<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->date('date_of_birth');
            $table->string('tax_code', 40);
            $table->string('email', 100);
            $table->string('mobile', 40);
            $table->string('city', 100);
            $table->enum('employment_type', ['employee','self_employed','retired','other']);
            $table->decimal('net_income', 12, 2);
            $table->string('loan_purpose', 120);
            $table->string('loan_purpose_other', 120)->nullable();
            $table->boolean('has_current_financing')->default(false);
            $table->decimal('current_financing_remaining', 12, 2)->nullable();
            $table->timestamps();
            $table->index(['user_id','employment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_inquiries');
    }
};


