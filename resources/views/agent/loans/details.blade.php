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
                        <span class="fw-bold text-primary">{{ $loan->application_id ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('User Code')
                        <span class="fw-bold">{{ $loan->user->username ?? 'Guest' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Loan Type')
                        <span class="badge badge--info">{{ ucfirst(str_replace('_', ' ', $loan->loan_type)) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @if($loan->status == 'pending')
                            <span class="badge badge--warning">@lang('Pending')</span>
                        @elseif($loan->status == 'processing')
                            <span class="badge badge--info">@lang('Processing')</span>
                        @elseif($loan->status == 'approved')
                            <span class="badge badge--success">@lang('Approved')</span>
                        @elseif($loan->status == 'rejected')
                            <span class="badge badge--danger">@lang('Rejected')</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Submitted Date')
                        <span>{{ showDateTime($loan->created_at) }}</span>
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
                        <span class="fw-bold">{{ $loan->age }} years</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Location')
                        <span>{{ $loan->city }}{{ $loan->province ? ' ('.$loan->province.')' : '' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Marital Status')
                        <span class="badge badge--dark">{{ ucfirst($loan->marital_status) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Applicant Type')
                        <span>{{ ucfirst($loan->applicant_type) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Family Members')
                        <span class="fw-bold">{{ $loan->family_members }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Employment Information')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Occupation')
                        <span>{{ $loan->occupation }}</span>
                    </li>
                    @if($loan->employment_duration_type)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Employment Type')
                        <span class="badge badge--primary">{{ ucfirst(str_replace('_', ' ', $loan->employment_duration_type)) }}</span>
                    </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Contract Type')
                        <span>{{ ucfirst($loan->contract_type) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Monthly Income')
                        <span class="fw-bold text-success">€{{ showAmount($loan->monthly_net_income) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Employment Length')
                        <span>{{ $loan->employment_length_years }} years</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Loan Details')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Loan Purpose')
                        <span>
                            @if($loan->loan_purpose == 'home_furnishings')
                                @lang('Home Furnishings')
                            @elseif($loan->loan_purpose == 'debt_consolidation')
                                @lang('Debt Consolidation')
                            @elseif($loan->loan_purpose == 'liquidity')
                                @lang('Liquidity')
                            @elseif($loan->loan_purpose == 'vacation')
                                @lang('Vacation')
                            @elseif($loan->loan_purpose == 'health')
                                @lang('Health')
                            @else
                                {{ $loan->loan_purpose_other }}
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Current Financing')
                        @if($loan->has_current_financing)
                            <span class="badge badge--warning">@lang('Yes')</span>
                        @else
                            <span class="badge badge--success">@lang('No')</span>
                        @endif
                    </li>
                    @if($loan->has_current_financing && $loan->current_financing_remaining)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Remaining Debt')
                        <span class="fw-bold text-danger">€{{ showAmount($loan->current_financing_remaining) }}</span>
                    </li>
                    @endif
                    @if($loan->current_financing_details)
                    <li class="list-group-item">
                        <strong>@lang('Financing Details'):</strong>
                        <p class="mb-0 mt-1">{{ $loan->current_financing_details }}</p>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

