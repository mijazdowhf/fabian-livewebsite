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
        Schema::create('agent_user_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The customer/user
            $table->unsignedBigInteger('agent_id'); // The assigned agent
            $table->unsignedBigInteger('application_id'); // The mortgage application
            $table->string('application_type')->default('mortgage'); // mortgage or loan
            $table->unsignedBigInteger('sender_id'); // Who sent the message (user or agent)
            $table->string('sender_type'); // 'user' or 'agent'
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['user_id', 'agent_id']);
            $table->index(['application_id', 'application_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_user_messages');
    }
};
