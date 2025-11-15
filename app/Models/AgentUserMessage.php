<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentUserMessage extends Model
{
    protected $fillable = [
        'user_id',
        'agent_id',
        'application_id',
        'application_type',
        'sender_id',
        'sender_type',
        'message',
        'attachment',
        'attachment_original_name',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user (customer) who is part of this conversation
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the agent who is part of this conversation
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the sender of this message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the application (mortgage or loan)
     */
    public function application()
    {
        if ($this->application_type === 'mortgage') {
            return $this->belongsTo(SellingMortgageApplication::class, 'application_id');
        }
        return $this->belongsTo(LoanInquiry::class, 'application_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages between a specific user and agent
     */
    public function scopeConversation($query, $userId, $agentId, $applicationId = null)
    {
        $query->where('user_id', $userId)->where('agent_id', $agentId);
        
        if ($applicationId) {
            $query->where('application_id', $applicationId);
        }
        
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope for agent's inbox
     */
    public function scopeAgentInbox($query, $agentId)
    {
        return $query->where('agent_id', $agentId)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope for user's messages
     */
    public function scopeUserMessages($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Check if sender is agent
     */
    public function isSentByAgent()
    {
        return $this->sender_type === 'agent';
    }

    /**
     * Check if sender is user
     */
    public function isSentByUser()
    {
        return $this->sender_type === 'user';
    }
}
