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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Who performed the action
            $table->string('actor_type'); // 'user', 'agent', 'admin', 'system'
            $table->unsignedBigInteger('actor_id')->nullable(); // ID of user/agent/admin
            $table->string('actor_name')->nullable(); // Name for reference
            
            // What action was performed
            $table->string('action'); // 'upload', 'view', 'download', 'update', 'submit', 'delete'
            $table->string('action_type'); // 'document_upload', 'application_submit', 'status_change', etc.
            $table->text('description'); // Human-readable description
            
            // On what resource
            $table->string('resource_type'); // 'loan_inquiry', 'selling_mortgage_application', 'document'
            $table->unsignedBigInteger('resource_id')->nullable(); // ID of the resource
            $table->string('resource_identifier')->nullable(); // Application number, document name, etc.
            
            // Additional metadata
            $table->json('metadata')->nullable(); // Additional data (file names, old values, new values, etc.)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes for quick searching
            $table->index(['actor_type', 'actor_id']);
            $table->index(['resource_type', 'resource_id']);
            $table->index('action_type');
            $table->index('created_at');
        });
        
        // Add document tracking fields to loan_inquiries table
        if (Schema::hasTable('loan_inquiries')) {
            Schema::table('loan_inquiries', function (Blueprint $table) {
                $table->json('document_uploads')->nullable()->after('status'); // Track uploaded documents
                $table->unsignedBigInteger('uploaded_by_agent_id')->nullable()->after('document_uploads');
                $table->timestamp('documents_locked_at')->nullable()->after('uploaded_by_agent_id');
            });
        }
        
        // Add document tracking fields to selling_mortgage_applications table
        if (Schema::hasTable('selling_mortgage_applications')) {
            Schema::table('selling_mortgage_applications', function (Blueprint $table) {
                $table->json('document_uploads')->nullable()->after('status'); // Track uploaded documents
                $table->unsignedBigInteger('uploaded_by_agent_id')->nullable()->after('document_uploads');
                $table->timestamp('documents_locked_at')->nullable()->after('uploaded_by_agent_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        
        if (Schema::hasTable('loan_inquiries') && Schema::hasColumn('loan_inquiries', 'document_uploads')) {
            Schema::table('loan_inquiries', function (Blueprint $table) {
                $table->dropColumn(['document_uploads', 'uploaded_by_agent_id', 'documents_locked_at']);
            });
        }
        
        if (Schema::hasTable('selling_mortgage_applications') && Schema::hasColumn('selling_mortgage_applications', 'document_uploads')) {
            Schema::table('selling_mortgage_applications', function (Blueprint $table) {
                $table->dropColumn(['document_uploads', 'uploaded_by_agent_id', 'documents_locked_at']);
            });
        }
    }
};
