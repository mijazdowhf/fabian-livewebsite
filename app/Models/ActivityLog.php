<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'actor_type',
        'actor_id',
        'actor_name',
        'action',
        'action_type',
        'description',
        'resource_type',
        'resource_id',
        'resource_identifier',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the actor (user, agent, or admin) who performed the action
     */
    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the resource (loan, mortgage, etc.) the action was performed on
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by actor type
     */
    public function scopeByActorType($query, $actorType)
    {
        return $query->where('actor_type', $actorType);
    }

    /**
     * Scope to filter by action type
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope to filter by resource
     */
    public function scopeByResource($query, $resourceType, $resourceId = null)
    {
        $query->where('resource_type', $resourceType);
        
        if ($resourceId) {
            $query->where('resource_id', $resourceId);
        }
        
        return $query;
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Static method to log an activity
     */
    public static function logActivity(array $data)
    {
        return self::create(array_merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $data));
    }

    /**
     * Log document upload activity
     */
    public static function logDocumentUpload($actor, $resource, $documentName, $additionalData = [])
    {
        return self::logActivity([
            'actor_type' => self::getActorType($actor),
            'actor_id' => $actor->id ?? null,
            'actor_name' => $actor->fullname ?? $actor->username ?? 'System',
            'action' => 'upload',
            'action_type' => 'document_upload',
            'description' => "{$actor->fullname} uploaded document: {$documentName}",
            'resource_type' => get_class($resource),
            'resource_id' => $resource->id ?? null,
            'resource_identifier' => $resource->application_number ?? $resource->id ?? null,
            'metadata' => array_merge([
                'document_name' => $documentName,
                'file_size' => $additionalData['file_size'] ?? null,
                'file_type' => $additionalData['file_type'] ?? null,
            ], $additionalData),
        ]);
    }

    /**
     * Log application submission activity
     */
    public static function logApplicationSubmit($actor, $resource, $applicationType)
    {
        return self::logActivity([
            'actor_type' => self::getActorType($actor),
            'actor_id' => $actor->id ?? null,
            'actor_name' => $actor->fullname ?? $actor->username ?? 'System',
            'action' => 'submit',
            'action_type' => 'application_submit',
            'description' => "{$actor->fullname} submitted {$applicationType} application",
            'resource_type' => get_class($resource),
            'resource_id' => $resource->id ?? null,
            'resource_identifier' => $resource->application_number ?? $resource->id ?? null,
            'metadata' => [
                'application_type' => $applicationType,
                'submitted_for_user' => $resource->user_id ?? null,
            ],
        ]);
    }

    /**
     * Log status change activity
     */
    public static function logStatusChange($actor, $resource, $oldStatus, $newStatus)
    {
        return self::logActivity([
            'actor_type' => self::getActorType($actor),
            'actor_id' => $actor->id ?? null,
            'actor_name' => $actor->fullname ?? $actor->username ?? 'System',
            'action' => 'update',
            'action_type' => 'status_change',
            'description' => "{$actor->fullname} changed status from {$oldStatus} to {$newStatus}",
            'resource_type' => get_class($resource),
            'resource_id' => $resource->id ?? null,
            'resource_identifier' => $resource->application_number ?? $resource->id ?? null,
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);
    }

    /**
     * Get actor type from object
     */
    private static function getActorType($actor)
    {
        if (!$actor) {
            return 'system';
        }

        $class = get_class($actor);
        
        if (str_contains($class, 'User')) {
            return $actor->role ?? 'user';
        }
        
        if (str_contains($class, 'Admin')) {
            return 'admin';
        }
        
        return 'system';
    }

    /**
     * Format the activity for display
     */
    public function getFormattedActivityAttribute()
    {
        return [
            'actor' => $this->actor_name,
            'action' => $this->action,
            'description' => $this->description,
            'time' => $this->created_at->diffForHumans(),
            'metadata' => $this->metadata,
        ];
    }
}
