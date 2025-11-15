<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\SellingMortgageApplication;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MortgageController extends Controller
{
    // List all home mortgages assigned to this agent
    public function index()
    {
        $pageTitle = 'My Assigned Home Mortgages';
        $emptyMessage = 'No home mortgages assigned yet';
        $mortgages = SellingMortgageApplication::where('agent_id', auth()->id())
            ->with('user')
            ->when(request()->search, function($query) {
                $query->where('application_id', 'like', '%'.request()->search.'%')
                      ->orWhere(function($q) {
                          $q->whereHas('user', function($userQuery) {
                              $userQuery->where('username', 'like', '%'.request()->search.'%');
                          });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
        
        return view('agent.mortgages.index', compact('pageTitle', 'mortgages', 'emptyMessage'));
    }
    
    // View mortgage details
    public function details($id)
    {
        $pageTitle = 'Home Mortgage Application Details';
        $mortgage = SellingMortgageApplication::where('agent_id', auth()->id())
            ->with('user')
            ->findOrFail($id);
        
        return view('agent.mortgages.details', compact('pageTitle', 'mortgage'));
    }
    
    // Upload missing document
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document' => 'required|file|mimes:pdf|max:102400', // 100MB max
        ]);
        
        $mortgage = SellingMortgageApplication::where('agent_id', auth()->id())
            ->findOrFail($id);
        
        // Check if document is actually missing
        $documentType = $request->document_type;
        if ($mortgage->$documentType) {
            $notify[] = ['error', 'This document has already been uploaded.'];
            return back()->withNotify($notify);
        }
        
        // Store the document
        $documentPath = $request->file('document')->store('selling_mortgage_documents', 'public');
        
        // Update the mortgage application
        $mortgage->$documentType = $documentPath;
        $mortgage->save();
        
        // Document name mapping
        $documentNames = [
            'doc_certificate_residency' => 'Certificate of Residency',
            'doc_family_status' => 'Family Status Certificate',
            'doc_marital_status' => 'Marital Status Certificate',
            'doc_valid_id' => 'Valid ID Document',
            'doc_health_card' => 'Health Card',
            'doc_residence_permit' => 'Residence Permit',
            'doc_tax_return_2025' => 'Tax Return 2025',
            'doc_tax_return_2024' => 'Tax Return 2024',
            'doc_electronic_receipt_2025' => 'Electronic Receipt 2025',
            'doc_electronic_receipt_2024' => 'Electronic Receipt 2024',
            'doc_vat_assignment' => 'VAT Assignment Certificate',
            'doc_bank_statement' => 'Bank Statement',
            'doc_transactions_30days' => 'Transaction History (30 days)',
            'doc_loan_agreement' => 'Loan Agreement',
        ];
        
        $documentName = $documentNames[$documentType] ?? $documentType;
        
        // Create activity log
        ActivityLog::logDocumentUpload(
            auth()->user(), 
            $mortgage, 
            $documentName,
            [
                'document_type' => $documentType,
                'document_field' => $documentType,
                'application_id' => $mortgage->application_id,
                'uploaded_by' => 'agent',
                'agent_id' => auth()->id(),
                'agent_name' => auth()->user()->fullname,
                'user_id' => $mortgage->user_id,
                'user_name' => $mortgage->user->fullname ?? 'N/A',
                'file_path' => $documentPath,
            ]
        );
        
        $notify[] = ['success', "Document '{$documentName}' uploaded successfully!"];
        return back()->withNotify($notify);
    }
}
