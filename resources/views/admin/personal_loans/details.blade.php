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
                            @lang('Type of Request')
                            @if($application->loan_type == 'personal_loan')
                                <span class="badge badge--info">@lang('Personal Loan')</span>
                            @elseif($application->loan_type == 'microcredit')
                                <span class="badge badge--success">@lang('Microcredit')</span>
                            @elseif($application->loan_type == 'leasing')
                                <span class="badge badge--warning">@lang('Leasing')</span>
                            @elseif($application->loan_type == 'salary_secured')
                                <span class="badge badge--primary">@lang('Salary-secured Loan')</span>
                            @else
                                <span class="badge badge--secondary">@lang('N/A')</span>
                            @endif
                        </li>
                    </ul>

                    <h5 class="mb-20 mt-3 text-muted">@lang('Personal Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Full Name')
                            <span class="fw-bold">{{ $application->first_name }} {{ $application->last_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date of Birth')
                            <span>{{ showDateTime($application->date_of_birth, 'd M, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Age')
                            <span class="fw-bold">{{ $application->age }} years</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Tax Code')
                            <span>{{ $application->tax_code }}</span>
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
                            <span>{{ $application->city }}{{ $application->province ? ' ('.$application->province.')' : '' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Marital Status')
                            <span class="badge badge--dark">{{ ucfirst($application->marital_status) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Applicant Type')
                            <span>{{ ucfirst($application->applicant_type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Family Members')
                            <span class="fw-bold">{{ $application->family_members }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Employment Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Occupation')
                            <span>{{ $application->occupation }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Employment Duration Type')
                            @if($application->employment_duration_type == 'fixed_term')
                                <span class="badge badge--warning">@lang('Fixed-term')</span>
                            @elseif($application->employment_duration_type == 'permanent')
                                <span class="badge badge--success">@lang('Permanent')</span>
                            @elseif($application->employment_duration_type == 'vat_number')
                                <span class="badge badge--info">@lang('VAT Number')</span>
                            @else
                                <span class="badge badge--secondary">@lang('N/A')</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Contract Type')
                            <span>{{ ucfirst($application->contract_type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Monthly Net Income')
                            <span class="fw-bold text-success">€{{ showAmount($application->monthly_net_income) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Employment Length')
                            <span>{{ $application->employment_length_years }} years</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Loan Details')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Application Type')
                            <span class="badge badge--info">Personal Loan</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Loan Purpose')
                            <span>
                                @if($application->loan_purpose == 'home_furnishings')
                                    @lang('Home Furnishings')
                                @elseif($application->loan_purpose == 'debt_consolidation')
                                    @lang('Debt Consolidation')
                                @elseif($application->loan_purpose == 'liquidity')
                                    @lang('Liquidity')
                                @elseif($application->loan_purpose == 'vacation')
                                    @lang('Vacation')
                                @elseif($application->loan_purpose == 'health')
                                    @lang('Health')
                                @else
                                    {{ $application->loan_purpose_other }}
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Current Financing')
                            @if($application->has_current_financing)
                                <span class="badge badge--warning">@lang('Yes')</span>
                            @else
                                <span class="badge badge--success">@lang('No')</span>
                            @endif
                        </li>
                        @if($application->current_financing_details)
                            <li class="list-group-item">
                                <strong>@lang('Current Financing Details'):</strong>
                                <p class="mb-0 mt-2">{{ $application->current_financing_details }}</p>
                            </li>
                        @endif
                        @if($application->current_financing_remaining)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Remaining Amount')
                                <span class="fw-bold text-danger">€{{ showAmount($application->current_financing_remaining) }}</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Privacy Authorized')
                            @if($application->privacy_authorization)
                                <span class="badge badge--success"><i class="las la-check"></i> Yes</span>
                            @else
                                <span class="badge badge--danger"><i class="las la-times"></i> No</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            <form action="{{ route('admin.personal.loans.status', $application->id) }}" method="POST">
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
                            <form action="{{ route('admin.personal.loans.assign.agent', $application->id) }}" method="POST" class="mt-2">
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
                    <h5 class="card-title border-bottom pb-2">@lang('Application Timeline')</h5>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>@lang('Submitted'):</strong> {{ showDateTime($application->created_at) }}</p>
                            <p><strong>@lang('Last Updated'):</strong> {{ showDateTime($application->updated_at) }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline--danger confirmationBtn" 
                                    data-question="@lang('Are you sure to delete this application?')"
                                    data-action="{{ route('admin.personal.loans.delete', $application->id) }}">
                                <i class="las la-trash"></i> @lang('Delete Application')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

