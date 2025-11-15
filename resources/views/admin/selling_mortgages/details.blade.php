@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Application Info')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Application ID')
                            <span class="fw-bold text-primary">{{ $application->application_id ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Applicant')
                            <span class="fw-bold">{{ $application->first_name }} {{ $application->last_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Age')
                            <span>{{ $application->age }} years</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span>{{ $application->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mobile')
                            <span>{{ $application->mobile }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Location')
                            <span>{{ $application->city }} ({{ $application->province }})</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Business Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Business Name')
                            <span class="fw-bold">{{ $application->business_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('VAT Number')
                            <span>{{ $application->vat_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Business Type')
                            <span>{{ ucfirst($application->business_type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Years in Business')
                            <span>{{ $application->business_years }} years</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Annual Revenue')
                            <span class="fw-bold text-success">€{{ showAmount($application->annual_revenue) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Mortgage Details')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mortgage Amount')
                            <span class="fw-bold text-primary">€{{ showAmount($application->mortgage_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Duration')
                            <span>{{ $application->mortgage_duration_months }} months</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Property Type')
                            <span>{{ ucfirst($application->property_type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Property Location')
                            <span>{{ $application->property_location }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Purpose')
                            <span>{{ ucfirst($application->mortgage_purpose) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            <form action="{{ route('admin.selling.mortgages.status', $application->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $application->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </li>
                        <li class="list-group-item">
                            <strong>@lang('Assign to Agent')</strong>
                            <form action="{{ route('admin.selling.mortgages.assign.agent', $application->id) }}" method="POST" class="mt-2">
                                @csrf
                                <div class="d-flex gap-2">
                                    <select name="agent_id" class="form-control form-control-sm">
                                        <option value="">@lang('Select Agent')</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ $application->agent_id == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->fullname }} ({{ $agent->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn--primary">@lang('Assign')</button>
                                </div>
                                @if($application->agent)
                                    <small class="text-muted d-block mt-1">
                                        @lang('Currently assigned to'): <strong>{{ $application->agent->fullname }}</strong>
                                    </small>
                                @endif
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">
                        <i class="las la-file-pdf"></i> @lang('Document Submission Status')
                    </h5>
                    <div class="row">
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
                                if($application->$field) {
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
                                @if($application->$field)
                                    <div class="text-center">
                                        <div class="document-item p-3 border border-success rounded bg-light mb-2">
                                            <i class="las la-check-circle text-success" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2 small fw-bold">{{ __($label) }}</p>
                                            <small class="badge badge--success">@lang('Submitted')</small>
                                        </div>
                                        <a href="{{ asset('storage/' . $application->$field) }}" target="_blank" class="btn btn-outline--primary btn-sm w-100">
                                            <i class="las la-download"></i> @lang('Download')
                                        </a>
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
