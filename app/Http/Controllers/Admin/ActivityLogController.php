<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display all activity logs with filters
     */
    public function index(Request $request)
    {
        $pageTitle = 'Activity Logs';
        
        $query = ActivityLog::query()->with(['resource'])->orderBy('created_at', 'desc');
        
        // Filter by actor type
        if ($request->actor_type) {
            $query->where('actor_type', $request->actor_type);
        }
        
        // Filter by action type
        if ($request->action_type) {
            $query->where('action_type', $request->action_type);
        }
        
        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by actor name or description
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('actor_name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('resource_identifier', 'like', "%{$request->search}%");
            });
        }
        
        $logs = $query->paginate(getPaginate());
        
        return view('admin.activity_logs.index', compact('pageTitle', 'logs'));
    }
    
    /**
     * Display agent-specific activity logs
     */
    public function agentActivities(Request $request, $agentId = null)
    {
        $pageTitle = 'Agent Activity Logs';
        
        $query = ActivityLog::query()
            ->where('actor_type', 'agent')
            ->orderBy('created_at', 'desc');
        
        // Filter by specific agent
        if ($agentId) {
            $agent = User::where('role', 'agent')->findOrFail($agentId);
            $query->where('actor_id', $agentId);
            $pageTitle = "Activity Logs - Agent: {$agent->fullname}";
        }
        
        // Additional filters
        if ($request->action_type) {
            $query->where('action_type', $request->action_type);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->paginate(getPaginate());
        $agents = User::where('role', 'agent')->orderBy('firstname')->get();
        
        return view('admin.activity_logs.agent_activities', compact('pageTitle', 'logs', 'agents', 'agentId'));
    }
    
    /**
     * Display user-specific activity logs
     */
    public function userActivities(Request $request, $userId)
    {
        $user = User::where('role', 'user')->findOrFail($userId);
        $pageTitle = "Activity Logs - User: {$user->fullname}";
        
        $logs = ActivityLog::query()
            ->where(function($q) use ($userId) {
                $q->where('actor_id', $userId)
                  ->orWhere('resource_type', 'like', '%User%')
                  ->where('resource_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        return view('admin.activity_logs.user_activities', compact('pageTitle', 'logs', 'user'));
    }
    
    /**
     * Display activity logs for a specific application
     */
    public function applicationActivities(Request $request, $type, $applicationId)
    {
        $resourceType = $type === 'loan' ? 'App\Models\LoanInquiry' : 'App\Models\SellingMortgageApplication';
        
        $logs = ActivityLog::query()
            ->where('resource_type', $resourceType)
            ->where('resource_id', $applicationId)
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        $pageTitle = "Activity Timeline - Application #{$applicationId}";
        
        return view('admin.activity_logs.application_activities', compact('pageTitle', 'logs', 'applicationId', 'type'));
    }
    
    /**
     * Display activity log details
     */
    public function details($id)
    {
        $log = ActivityLog::findOrFail($id);
        $pageTitle = 'Activity Log Details';
        
        return view('admin.activity_logs.details', compact('pageTitle', 'log'));
    }
    
    /**
     * Get activity statistics
     */
    public function statistics(Request $request)
    {
        $pageTitle = 'Activity Statistics';
        
        $days = $request->days ?? 30;
        
        $stats = [
            'total_activities' => ActivityLog::recent($days)->count(),
            'agent_activities' => ActivityLog::recent($days)->byActorType('agent')->count(),
            'user_activities' => ActivityLog::recent($days)->byActorType('user')->count(),
            'document_uploads' => ActivityLog::recent($days)->byActionType('document_upload')->count(),
            'applications_submitted' => ActivityLog::recent($days)->byActionType('application_submit')->count(),
            'status_changes' => ActivityLog::recent($days)->byActionType('status_change')->count(),
        ];
        
        // Activity by day
        $activityByDay = ActivityLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Most active agents
        $activeAgents = ActivityLog::selectRaw('actor_name, COUNT(*) as activity_count')
            ->where('actor_type', 'agent')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('actor_name')
            ->orderByDesc('activity_count')
            ->limit(10)
            ->get();
        
        return view('admin.activity_logs.statistics', compact('pageTitle', 'stats', 'activityByDay', 'activeAgents', 'days'));
    }
}

