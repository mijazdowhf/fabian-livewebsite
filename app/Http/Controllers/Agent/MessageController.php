<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentUserMessage;
use App\Models\SellingMortgageApplication;
use App\Models\LoanInquiry;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Show agent's message inbox
     */
    public function index()
    {
        $pageTitle = 'User Messages';
        $agent = auth()->user();
        
        // Get all conversations grouped by user and application
        $conversations = AgentUserMessage::where('agent_id', $agent->id)
            ->with(['user', 'sender'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                return $message->user_id . '_' . $message->application_id;
            })
            ->map(function($messages) {
                $lastMessage = $messages->first();
                $unreadCount = $messages->where('sender_type', 'user')->where('is_read', false)->count();
                
                // Determine read status based on who sent the last message
                $isRead = true;
                if ($lastMessage->sender_type === 'agent') {
                    // If agent sent last message, check if user has read it
                    $isRead = $lastMessage->is_read;
                } else {
                    // If user sent last message, check if there are any unread user messages
                    $isRead = $unreadCount === 0;
                }
                
                return [
                    'user' => $lastMessage->user,
                    'application_id' => $lastMessage->application_id,
                    'application_type' => $lastMessage->application_type,
                    'last_message' => $lastMessage->message,
                    'last_message_time' => $lastMessage->created_at,
                    'unread_count' => $unreadCount,
                    'last_sender_type' => $lastMessage->sender_type,
                    'is_read' => $isRead,
                ];
            })
            ->sortByDesc('last_message_time')
            ->values();
        
        // Total unread count
        $totalUnread = AgentUserMessage::where('agent_id', $agent->id)
            ->where('sender_type', 'user')
            ->unread()
            ->count();
        
        return view('agent.messages.index', compact('pageTitle', 'conversations', 'totalUnread'));
    }
    
    /**
     * Show conversation with specific user
     */
    public function conversation($userId, $type, $applicationId)
    {
        $agent = auth()->user();
        
        // Verify this application is assigned to this agent
        if ($type === 'mortgage') {
            $application = SellingMortgageApplication::where('agent_id', $agent->id)
                ->where('id', $applicationId)
                ->where('user_id', $userId)
                ->with('user')
                ->firstOrFail();
        } else {
            $application = LoanInquiry::where('agent_id', $agent->id)
                ->where('id', $applicationId)
                ->where('user_id', $userId)
                ->with('user')
                ->firstOrFail();
        }
        
        // Get conversation messages
        $messages = AgentUserMessage::conversation($userId, $agent->id, $applicationId)
            ->with(['sender'])
            ->get();
        
        // Mark user messages as read
        AgentUserMessage::where('user_id', $userId)
            ->where('agent_id', $agent->id)
            ->where('application_id', $applicationId)
            ->where('sender_type', 'user')
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);
        
        $pageTitle = 'Conversation with ' . $application->user->fullname;
        
        return view('agent.messages.conversation', compact(
            'pageTitle',
            'application',
            'messages',
            'type',
            'userId'
        ));
    }
    
    /**
     * Send reply to user
     */
    public function sendReply(Request $request, $userId, $type, $applicationId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);
        
        $agent = auth()->user();
        
        // Verify this application is assigned to this agent
        if ($type === 'mortgage') {
            $application = SellingMortgageApplication::where('agent_id', $agent->id)
                ->where('id', $applicationId)
                ->where('user_id', $userId)
                ->firstOrFail();
        } else {
            $application = LoanInquiry::where('agent_id', $agent->id)
                ->where('id', $applicationId)
                ->where('user_id', $userId)
                ->firstOrFail();
        }
        
        $attachmentPath = null;
        $originalName = null;
        
        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $attachmentPath = $file->store('agent_user_messages', 'public');
        }
        
        // Create message
        AgentUserMessage::create([
            'user_id' => $userId,
            'agent_id' => $agent->id,
            'application_id' => $applicationId,
            'application_type' => $type,
            'sender_id' => $agent->id,
            'sender_type' => 'agent',
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'attachment_original_name' => $originalName,
        ]);
        
        $notify[] = ['success', 'Reply sent successfully!'];
        return back()->withNotify($notify);
    }
}
