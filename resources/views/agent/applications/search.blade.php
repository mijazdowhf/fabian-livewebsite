@extends('agent.layouts.app')

@section('panel')
<style>
    .result-card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: box-shadow 0.3s ease;
    }
    .result-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border-left: 3px solid;
    }
    .doc-item {
        border-radius: 5px;
        padding: 0.6rem;
        border: 1px solid;
        font-size: 0.85rem;
    }
    .app-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <!-- Search Section -->
        <div class="card b-radius--10">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="las la-search"></i> @lang('Search Customer Application')
                </h6>
                <form action="{{ route('agent.applications.search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" 
                               name="application_id" 
                               class="form-control" 
                               placeholder="@lang('Enter Application ID or Customer Email')" 
                               value="{{ request('application_id') }}"
                               required
                               autofocus>
                        <button type="submit" class="btn btn--primary">
                            <i class="las la-search"></i> @lang('Search')
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="las la-info-circle"></i> @lang('Search applications by Application ID or Customer Email Address')
                    </small>
                </form>
            </div>
        </div>

        @if(request('application_id'))
            @if(isset($searchResults) && $searchResults->isNotEmpty())
                <!-- Results Summary -->
                <div class="card border-0 mt-3 mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white">
                                <h6 class="text-white mb-1">
                                    <i class="las la-search"></i> @lang('Search Results')
                                </h6>
                                <p class="mb-0">
                                    @lang('Found') <strong>{{ $searchResults->count() }}</strong> @lang('application(s) matching'): 
                                    <span class="badge bg-white text-primary">{{ request('application_id') }}</span>
                                </p>
                            </div>
                            <div>
                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="las la-check-circle text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Results -->
                @foreach($searchResults as $index => $result)
                    <div class="card result-card mb-3">
                        <!-- Header -->
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center gap-2">
                                    @if($result->type === 'personal_loan')
                                        <div class="app-icon bg-white bg-opacity-25">
                                            <i class="las la-file-invoice-dollar text-white"></i>
                                        </div>
                                    @elseif($result->type === 'employee_loan')
                                        <div class="app-icon bg-white bg-opacity-25">
                                            <i class="las la-briefcase text-white"></i>
                                        </div>
                                    @else
                                        <div class="app-icon bg-white bg-opacity-25">
                                            <i class="las la-home text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        @if($result->type === 'personal_loan')
                                            <small class="text-white d-block opacity-75">@lang('Personal Loan')</small>
                                        @elseif($result->type === 'employee_loan')
                                            <small class="text-white d-block opacity-75">@lang('Employee Loan')</small>
                                        @else
                                            <small class="text-white d-block opacity-75">@lang('Home Mortgage')</small>
                                        @endif
                                        <strong class="text-white">{{ $result->application_id }}</strong>
                                    </div>
                                </div>
                                <div>
                                    @if($result->status == 'pending')
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($result->status == 'approved')
                                        <span class="badge badge--success">@lang('Approved')</span>
                                    @elseif($result->status == 'rejected')
                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <!-- Customer Information -->
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="mb-3 pb-2 border-bottom text-dark">
                                                <i class="las la-user text-primary me-1"></i> @lang('Customer Information')
                                            </h6>
                                            @if($result->user)
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-user text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('Name')</small>
                                                        <strong class="text-dark">{{ $result->user->firstname }} {{ $result->user->lastname }}</strong>
                                                    </div>
                                                </div>
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-id-card text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('Username')</small>
                                                        <strong class="text-dark">{{ $result->user->username }}</strong>
                                                    </div>
                                                </div>
                                                <div class="mb-0 d-flex align-items-center">
                                                    <i class="las la-phone text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('Mobile')</small>
                                                        <strong class="text-dark">{{ $result->user->mobile }}</strong>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">
                                                    <i class="las la-user-slash"></i> @lang('Guest Application')
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Application Details -->
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="mb-3 pb-2 border-bottom text-dark">
                                                <i class="las la-info-circle text-info me-1"></i> @lang('Application Details')
                                            </h6>
                                            @if($result->type === 'personal_loan' || $result->type === 'employee_loan')
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-user text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('First Name')</small>
                                                        <strong class="text-dark">{{ $result->first_name }}</strong>
                                                    </div>
                                                </div>
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-user text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('Last Name')</small>
                                                        <strong class="text-dark">{{ $result->last_name }}</strong>
                                                    </div>
                                                </div>
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-map-marker text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('City')</small>
                                                        <strong class="text-dark">{{ $result->city }}</strong>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-building text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('Business Name')</small>
                                                        <strong class="text-dark">{{ $result->business_name }}</strong>
                                                    </div>
                                                </div>
                                                <div class="mb-2 d-flex align-items-center">
                                                    <i class="las la-map-marker text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('City')</small>
                                                        <strong class="text-dark">{{ $result->city }}, {{ $result->province }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="mb-0 d-flex align-items-center">
                                                <i class="las la-calendar text-muted me-2" style="font-size: 1.1rem; width: 22px;"></i>
                                                <div>
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">@lang('Submitted')</small>
                                                    <strong class="text-dark">{{ showDateTime($result->created_at, 'd M Y') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($result->type === 'mortgage')
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
                                        
                                        $submittedCount = 0;
                                        $missingCount = 0;
                                        foreach($documents as $field => $label) {
                                            if($result->$field) {
                                                $submittedCount++;
                                            } else {
                                                $missingCount++;
                                            }
                                        }
                                    @endphp

                                    <!-- Document Status -->
                                    <div class="mt-3">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">
                                                    <i class="las la-file-pdf text-danger"></i> @lang('Document Submission Status')
                                                </h6>
                                                <div>
                                                    <span class="badge badge--success">
                                                        <i class="las la-check"></i> {{ $submittedCount }}/{{ count($documents) }}
                                                    </span>
                                                    <span class="badge badge--danger ms-1">
                                                        <i class="las la-times"></i> {{ $missingCount }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                @foreach($documents as $field => $label)
                                                    <div class="col-md-3 col-sm-6 col-6">
                                                        @if($result->$field)
                                                            <div class="doc-item bg-white border-success text-center">
                                                                <i class="las la-check-circle text-success d-block" style="font-size: 1.3rem;"></i>
                                                                <small class="d-block mt-1">{{ __($label) }}</small>
                                                            </div>
                                                        @else
                                                            <div class="doc-item bg-white border-danger text-center">
                                                                <i class="las la-times-circle text-danger d-block" style="font-size: 1.3rem;"></i>
                                                                <small class="d-block mt-1 text-muted">{{ __($label) }}</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="card-footer">
                                <div class="d-flex gap-2 justify-content-end">
                                    @if($result->type === 'personal_loan')
                                        <a href="{{ route('agent.loans.details', $result->id) }}" 
                                           class="btn btn--primary">
                                            <i class="las la-eye"></i> @lang('View Full Details')
                                        </a>
                                        @if($result->user)
                                            <a href="{{ route('agent.messages.conversation', [$result->user_id, 'loan', $result->id]) }}" 
                                               class="btn btn--info">
                                                <i class="las la-comments"></i> @lang('Contact Customer')
                                            </a>
                                        @endif
                                    @elseif($result->type === 'employee_loan')
                                        <a href="{{ route('agent.employee.loans.details', $result->id) }}" 
                                           class="btn btn--primary">
                                            <i class="las la-eye"></i> @lang('View Full Details')
                                        </a>
                                        @if($result->user)
                                            <a href="{{ route('agent.messages.conversation', [$result->user_id, 'loan', $result->id]) }}" 
                                               class="btn btn--info">
                                                <i class="las la-comments"></i> @lang('Contact Customer')
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('agent.mortgages.details', $result->id) }}" 
                                           class="btn btn--primary">
                                            <i class="las la-eye"></i> @lang('View Full Details')
                                        </a>
                                        @if($result->user)
                                            <a href="{{ route('agent.messages.conversation', [$result->user_id, 'mortgage', $result->id]) }}" 
                                               class="btn btn--info">
                                                <i class="las la-comments"></i> @lang('Contact Customer')
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif(isset($searchResults))
                    <!-- No Results -->
                    <div class="card b-radius--10 mt-3">
                        <div class="card-body text-center py-4">
                            <i class="las la-search text-muted" style="font-size: 2.5rem;"></i>
                            <h6 class="mt-2 mb-2">@lang('No Applications Found')</h6>
                            <p class="text-muted mb-0">
                                @lang('No applications found matching'): <code>{{ request('application_id') }}</code>
                            </p>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="card b-radius--10 mt-3">
                    <div class="card-body text-center py-4">
                        <i class="las la-search text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
                        <p class="text-muted mb-0 mt-2">@lang('Enter Application ID or Email to search')</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
