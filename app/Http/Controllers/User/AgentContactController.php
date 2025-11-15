<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AgentUserMessage;
use App\Models\SellingMortgageApplication;
use App\Models\LoanInquiry;
use Illuminate\Http\Request;

class AgentContactController extends Controller
{
    /**
     * Show contact agent page
     */
    public function index()
    {
        $pageTitle = 'Contact My Agent';
        $user = auth()->user();
        
        // Get user's assigned agents (from mortgages and loans)
        $mortgages = SellingMortgageApplication::where('user_id', $user->id)
            ->whereNotNull('agent_id')
            ->with('agent')
            ->get();
        
        $loans = LoanInquiry::where('user_id', $user->id)
            ->whereNotNull('agent_id')
            ->with('agent')
            ->get();
        
        // Get unread message count
        $unreadCount = AgentUserMessage::where('user_id', $user->id)
            ->where('sender_type', 'agent')
            ->unread()
            ->count();
        
        return view('Template::user.agent_contact.index', compact(
            'pageTitle', 
            'mortgages', 
            'loans', 
            'unreadCount'
        ));
    }
    
    /**
     * Show conversation with agent for specific application
     */
    public function conversation($type, $applicationId)
    {
        $user = auth()->user();
        
        // Get the application
        if ($type === 'mortgage') {
            $application = SellingMortgageApplication::where('user_id', $user->id)
                ->where('id', $applicationId)
                ->with('agent')
                ->firstOrFail();
        } else {
            $application = LoanInquiry::where('user_id', $user->id)
                ->where('id', $applicationId)
                ->with('agent')
                ->firstOrFail();
        }
        
        if (!$application->agent_id) {
            $notify[] = ['error', 'No agent has been assigned to this application yet.'];
            return redirect()->route('user.contact.agent')->withNotify($notify);
        }
        
        // Get conversation messages
        $messages = AgentUserMessage::conversation($user->id, $application->agent_id, $applicationId)
            ->with(['sender'])
            ->get();
        
        // Mark agent messages as read
        AgentUserMessage::where('user_id', $user->id)
            ->where('agent_id', $application->agent_id)
            ->where('application_id', $applicationId)
            ->where('sender_type', 'agent')
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);
        
        $pageTitle = 'Conversation with ' . $application->agent->fullname;
        
        return view('Template::user.agent_contact.conversation', compact(
            'pageTitle',
            'application',
            'messages',
            'type'
        ));
    }
    
    /**
     * Send message to agent
     */
    public function sendMessage(Request $request, $type, $applicationId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);
        
        $user = auth()->user();
        
        // Get the application
        if ($type === 'mortgage') {
            $application = SellingMortgageApplication::where('user_id', $user->id)
                ->where('id', $applicationId)
                ->firstOrFail();
        } else {
            $application = LoanInquiry::where('user_id', $user->id)
                ->where('id', $applicationId)
                ->firstOrFail();
        }
        
        if (!$application->agent_id) {
            $notify[] = ['error', 'No agent has been assigned to this application.'];
            return back()->withNotify($notify);
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
            'user_id' => $user->id,
            'agent_id' => $application->agent_id,
            'application_id' => $applicationId,
            'application_type' => $type,
            'sender_id' => $user->id,
            'sender_type' => 'user',
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'attachment_original_name' => $originalName,
        ]);
        
        $notify[] = ['success', 'Message sent successfully!'];
        return back()->withNotify($notify);
    }
}
