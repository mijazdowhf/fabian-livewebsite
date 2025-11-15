@extends('agent.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Application Information')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Application ID')
                        <span class="fw-bold text-primary">{{ $mortgage->application_id ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('User Code')
                        <span class="fw-bold">{{ $mortgage->user->username ?? 'Guest' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @if($mortgage->status == 'pending')
                            <span class="badge badge--warning">@lang('Pending')</span>
                        @elseif($mortgage->status == 'processing')
                            <span class="badge badge--info">@lang('Processing')</span>
                        @elseif($mortgage->status == 'approved')
                            <span class="badge badge--success">@lang('Approved')</span>
                        @elseif($mortgage->status == 'rejected')
                            <span class="badge badge--danger">@lang('Rejected')</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Submitted Date')
                        <span>{{ showDateTime($mortgage->created_at) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Business Information')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Business Name')
                        <span class="fw-bold">{{ $mortgage->business_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('VAT Number')
                        <span>{{ $mortgage->vat_number }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Business Type')
                        <span>{{ ucfirst($mortgage->business_type) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Years in Business')
                        <span>{{ $mortgage->business_years }} years</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Annual Revenue')
                        <span class="fw-bold text-success">€{{ showAmount($mortgage->annual_revenue) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Mortgage Details')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Mortgage Amount')
                        <span class="fw-bold text-primary">€{{ showAmount($mortgage->mortgage_amount) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Duration')
                        <span>{{ $mortgage->mortgage_duration_months }} months</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Property Type')
                        <span>{{ ucfirst($mortgage->property_type) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Property Location')
                        <span>{{ $mortgage->property_location }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Purpose')
                        <span>{{ ucfirst($mortgage->mortgage_purpose) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Personal Details')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Age')
                        <span class="fw-bold">{{ $mortgage->age }} years</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('City')
                        <span>{{ $mortgage->city }}{{ $mortgage->province ? ' ('.$mortgage->province.')' : '' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Marital Status')
                        <span class="badge badge--dark">{{ ucfirst($mortgage->marital_status) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Family Members')
                        <span class="fw-bold">{{ $mortgage->family_members }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @php
        $documents = [
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
        
        $missingDocs = collect($documents)->filter(fn($label, $field) => !$mortgage->$field)->count();
    @endphp

    @if($missingDocs > 0)
    <div class="col-xl-12 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1 border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning mb-3">
                    <i class="las la-upload"></i> @lang('Upload Missing Document')
                </h5>
                <form action="{{ route('agent.mortgage.document.upload', $mortgage->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">@lang('Select Document Type') <span class="text-danger">*</span></label>
                            <select name="document_type" id="documentType" class="form-control" required>
                                <option value="">@lang('-- Select Missing Document --')</option>
                                @foreach($documents as $field => $label)
                                    @if(!$mortgage->$field)
                                        <option value="{{ $field }}">{{ __($label) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">@lang('Upload PDF File') <span class="text-danger">*</span></label>
                            <input type="file" name="document" class="form-control" accept="application/pdf" required>
                            <small class="text-muted">@lang('Only PDF files (max 100MB)')</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn--primary w-100">
                                <i class="las la-upload"></i> @lang('Upload Document')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="col-xl-12 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 mb-3">
                    <i class="las la-file-pdf"></i> @lang('Document Submission Status')
                </h5>
                <p class="text-muted small mb-3">
                    <i class="las la-info-circle"></i> @lang('List of all required documents. Document access is restricted to admin only.')
                </p>
                <div class="row">
                    @php
                        $submittedCount = 0;
                        $missingCount = 0;
                        foreach($documents as $field => $label) {
                            if($mortgage->$field) {
                                $submittedCount++;
                            } else {
                                $missingCount++;
                            }
                        }
                    @endphp
                    
                    <div class="col-12 mb-3">
                        <div class="d-flex gap-3 justify-content-center">
                            <span class="badge badge--success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                <i class="las la-check-circle"></i> @lang('Submitted'): {{ $submittedCount }}/{{ count($documents) }}
                            </span>
                            <span class="badge badge--danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                <i class="las la-times-circle"></i> @lang('Missing'): {{ $missingCount }}/{{ count($documents) }}
                            </span>
                        </div>
                    </div>
                    
                    @foreach($documents as $field => $label)
                        <div class="col-md-3 mb-3">
                            @if($mortgage->$field)
                                <div class="document-item text-center p-3 border border-success rounded bg-light">
                                    <i class="las la-check-circle text-success" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2 small fw-bold">{{ __($label) }}</p>
                                    <small class="badge badge--success">@lang('Submitted')</small>
                                </div>
                            @else
                                <div class="document-item text-center p-3 border border-danger rounded" style="background-color: #fff5f5;">
                                    <i class="las la-times-circle text-danger" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2 small fw-bold text-muted">{{ __($label) }}</p>
                                    <small class="badge badge--danger">@lang('Missing')</small>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

