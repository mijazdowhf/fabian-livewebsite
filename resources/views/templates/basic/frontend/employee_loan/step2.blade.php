@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Employee Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 2 of 4: Employment Information')</p>
        </div>

        <div class="loan-steps-wrapper mb-5">
            <div class="loan-steps">
                <div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">01</span><span class="loan-step-title">@lang('Personal')</span></div></div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step active"><div class="loan-step-icon"><i class="las la-briefcase"></i></div><div class="loan-step-content"><span class="loan-step-number">02</span><span class="loan-step-title">@lang('Employment')</span></div></div>
                <div class="loan-step-line"></div>
                <div class="loan-step"><div class="loan-step-icon"><i class="las la-file-invoice-dollar"></i></div><div class="loan-step-content"><span class="loan-step-number">03</span><span class="loan-step-title">@lang('Loan Details')</span></div></div>
                <div class="loan-step-line"></div>
                <div class="loan-step"><div class="loan-step-icon"><i class="las la-file-pdf"></i></div><div class="loan-step-content"><span class="loan-step-number">04</span><span class="loan-step-title">@lang('Documents')</span></div></div>
            </div>
        </div>

        <form method="POST" action="{{ route('employee.loan.step2.store') }}" id="employeeLoanStep2" class="loan-wizard-form">
            @csrf
            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon"><i class="las la-briefcase"></i></div>
                    <div><h4 class="loan-form-card-title">@lang('Employment Information')</h4><p class="loan-form-card-subtitle">@lang('Tell us about your employment')</p></div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-building label-icon"></i>@lang('Employer Name')</label>
                                <input type="text" name="employer_name" class="loan-form-control" value="{{ old('employer_name', session('employee_loan.employer_name')) }}" placeholder="@lang('Company name')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-file-contract label-icon"></i>@lang('Contract Type')</label>
                                <div class="custom--nice-select">
                                    <select name="contract_type" id="contract_type">
                                        <option value="" disabled selected>@lang('Select contract type')</option>
                                        <option value="permanent" {{ old('contract_type', session('employee_loan.contract_type')) == 'permanent' ? 'selected' : '' }}>@lang('Permanent')</option>
                                        <option value="fixed_term" {{ old('contract_type', session('employee_loan.contract_type')) == 'fixed_term' ? 'selected' : '' }}>@lang('Fixed Term')</option>
                                        <option value="part_time" {{ old('contract_type', session('employee_loan.contract_type')) == 'part_time' ? 'selected' : '' }}>@lang('Part Time')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="contract_type_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-euro-sign label-icon"></i>@lang('Monthly Net Income (€)')</label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="monthly_net_income" class="loan-form-control with-icon" value="{{ old('monthly_net_income', session('employee_loan.monthly_net_income')) }}" placeholder="@lang('1,800')">
                                </div>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-clock label-icon"></i>@lang('Employment Length (years)')</label>
                                <input type="number" name="employment_length_years" class="loan-form-control" value="{{ old('employment_length_years', session('employee_loan.employment_length_years')) }}" placeholder="@lang('4')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-calendar-check label-icon"></i>@lang('Employment Start Date')</label>
                                <input type="date" name="employment_start_date" class="loan-form-control" value="{{ old('employment_start_date', session('employee_loan.employment_start_date')) }}">
                                <span class="error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="loan-form-actions">
                <a href="{{ route('employee.loan.wizard') }}" class="loan-back-btn"><i class="las la-arrow-left"></i>@lang('Back')</a>
                <button type="submit" class="loan-submit-btn"><span class="btn-text">@lang('Next: Loan Details')</span><i class="las la-arrow-right btn-icon"></i></button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('Template::frontend.personal_loan.styles')
@include('Template::frontend.employee_loan.validation_script')

