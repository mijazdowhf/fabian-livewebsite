@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Employee Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 3 of 4: Loan Details')</p>
        </div>

        <div class="loan-steps-wrapper mb-5">
            <div class="loan-steps">
                <div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">01</span><span class="loan-step-title">@lang('Personal')</span></div></div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">02</span><span class="loan-step-title">@lang('Employment')</span></div></div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step active"><div class="loan-step-icon"><i class="las la-file-invoice-dollar"></i></div><div class="loan-step-content"><span class="loan-step-number">03</span><span class="loan-step-title">@lang('Loan Details')</span></div></div>
                <div class="loan-step-line"></div>
                <div class="loan-step"><div class="loan-step-icon"><i class="las la-file-pdf"></i></div><div class="loan-step-content"><span class="loan-step-number">04</span><span class="loan-step-title">@lang('Documents')</span></div></div>
            </div>
        </div>

        <form method="POST" action="{{ route('employee.loan.step3.store') }}" id="employeeLoanStep3" class="loan-wizard-form">
            @csrf
            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon"><i class="las la-file-invoice-dollar"></i></div>
                    <div><h4 class="loan-form-card-title">@lang('Loan Details')</h4><p class="loan-form-card-subtitle">@lang('Specify loan amount and purpose')</p></div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-euro-sign label-icon"></i>@lang('Loan Amount (€)')</label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="loan_amount" class="loan-form-control with-icon" value="{{ old('loan_amount', session('employee_loan.loan_amount')) }}" placeholder="@lang('50,000')">
                                </div>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-calendar-alt label-icon"></i>@lang('Duration (months)')</label>
                                <input type="number" name="loan_duration_months" class="loan-form-control" value="{{ old('loan_duration_months', session('employee_loan.loan_duration_months')) }}" placeholder="@lang('120')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-bullseye label-icon"></i>@lang('Loan Purpose')</label>
                                <div class="custom--nice-select">
                                    <select name="loan_purpose" id="loan_purpose">
                                        <option value="" disabled selected>@lang('Select purpose')</option>
                                        <option value="home_purchase" {{ old('loan_purpose', session('employee_loan.loan_purpose')) == 'home_purchase' ? 'selected' : '' }}>@lang('Home Purchase')</option>
                                        <option value="renovation" {{ old('loan_purpose', session('employee_loan.loan_purpose')) == 'renovation' ? 'selected' : '' }}>@lang('Renovation')</option>
                                        <option value="debt_consolidation" {{ old('loan_purpose', session('employee_loan.loan_purpose')) == 'debt_consolidation' ? 'selected' : '' }}>@lang('Debt Consolidation')</option>
                                        <option value="personal_use" {{ old('loan_purpose', session('employee_loan.loan_purpose')) == 'personal_use' ? 'selected' : '' }}>@lang('Personal Use')</option>
                                        <option value="other" {{ old('loan_purpose', session('employee_loan.loan_purpose')) == 'other' ? 'selected' : '' }}>@lang('Other')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="loan_purpose_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6" id="purposeOther" style="display:none;">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-edit label-icon"></i>@lang('Specify Other')</label>
                                <input type="text" name="loan_purpose_other" class="loan-form-control" value="{{ old('loan_purpose_other', session('employee_loan.loan_purpose_other')) }}" placeholder="@lang('Specify')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-list label-icon"></i>@lang('Current Financing Details')</label>
                                <textarea name="current_financing_details" class="loan-form-textarea" rows="3" placeholder="@lang('List any existing loans')">{{ old('current_financing_details', session('employee_loan.current_financing_details')) }}</textarea>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-money-bill-wave label-icon"></i>@lang('Total Remaining (€)')</label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="current_financing_remaining" class="loan-form-control with-icon" value="{{ old('current_financing_remaining', session('employee_loan.current_financing_remaining')) }}" placeholder="@lang('3,000')">
                                </div>
                                <span class="error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="loan-form-actions">
                <a href="{{ route('employee.loan.step2') }}" class="loan-back-btn"><i class="las la-arrow-left"></i>@lang('Back')</a>
                <button type="submit" class="loan-submit-btn"><span class="btn-text">@lang('Next: Documents')</span><i class="las la-arrow-right btn-icon"></i></button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('Template::frontend.personal_loan.styles')
@include('Template::frontend.employee_loan.validation_script')

@push('script')
<script>
    $('#loan_purpose').on('change', function(){
        if(this.value === 'other') { $('#purposeOther').slideDown(300); } else { $('#purposeOther').slideUp(300); }
    });
    if($('#loan_purpose').val() === 'other') { $('#purposeOther').show(); }
</script>
@endpush

