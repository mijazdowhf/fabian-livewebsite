@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <!-- Page Header -->
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 2 of 3: Employment Information')</p>
        </div>

        <!-- Progress Steps -->
        <div class="loan-steps-wrapper mb-5">
            <div class="loan-steps">
                <div class="loan-step completed">
                    <div class="loan-step-icon">
                        <i class="las la-check"></i>
                    </div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">01</span>
                        <span class="loan-step-title">@lang('Personal Info')</span>
                    </div>
                </div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step active">
                    <div class="loan-step-icon">
                        <i class="las la-briefcase"></i>
                    </div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">02</span>
                        <span class="loan-step-title">@lang('Employment')</span>
                    </div>
                </div>
                <div class="loan-step-line"></div>
                <div class="loan-step">
                    <div class="loan-step-icon">
                        <i class="las la-file-invoice-dollar"></i>
                    </div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">03</span>
                        <span class="loan-step-title">@lang('Loan Details')</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 Form -->
        <form method="POST" action="{{ route('lead.step2.store') }}" id="loanWizardStep2" class="loan-wizard-form">
            @csrf
            
            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon">
                        <i class="las la-briefcase"></i>
                    </div>
                    <div>
                        <h4 class="loan-form-card-title">@lang('Employment Information')</h4>
                        <p class="loan-form-card-subtitle">@lang('Tell us about your employment status')</p>
                    </div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-user-tie label-icon"></i>
                                    @lang('Occupation (Occupazione)')
                                </label>
                                <input type="text" name="occupation" class="loan-form-control" value="{{ old('occupation', session('loan_data.occupation')) }}" placeholder="@lang('Enter your occupation')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-clock label-icon"></i>
                                    @lang('Employment Duration Type (Dipendente a tempo)')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="employment_duration_type" id="employment_duration_type">
                                        <option value="" disabled selected>@lang('Select type')</option>
                                        <option value="fixed_term" {{ old('employment_duration_type', session('loan_data.employment_duration_type')) == 'fixed_term' ? 'selected' : '' }}>@lang('Fixed-term (Determinato)')</option>
                                        <option value="permanent" {{ old('employment_duration_type', session('loan_data.employment_duration_type')) == 'permanent' ? 'selected' : '' }}>@lang('Permanent (Indeterminato)')</option>
                                        <option value="vat_number" {{ old('employment_duration_type', session('loan_data.employment_duration_type')) == 'vat_number' ? 'selected' : '' }}>@lang('VAT Number (Partita IVA)')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="employment_duration_type_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-file-contract label-icon"></i>
                                    @lang('Contract Type (Tipo di contratto)')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="contract_type" id="contract_type">
                                        <option value="" disabled selected>@lang('Select contract type')</option>
                                        <option value="private" {{ old('contract_type', session('loan_data.contract_type')) == 'private' ? 'selected' : '' }}>@lang('Private (Privato)')</option>
                                        <option value="public" {{ old('contract_type', session('loan_data.contract_type')) == 'public' ? 'selected' : '' }}>@lang('Public (Pubblico)')</option>
                                        <option value="self_employed" {{ old('contract_type', session('loan_data.contract_type')) == 'self_employed' ? 'selected' : '' }}>@lang('Self-Employed (Autonomo)')</option>
                                        <option value="retired" {{ old('contract_type', session('loan_data.contract_type')) == 'retired' ? 'selected' : '' }}>@lang('Retired (Pensionato)')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="contract_type_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-euro-sign label-icon"></i>
                                    @lang('Monthly Net Income (Reddito netto mensile)')
                                </label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="monthly_net_income" class="loan-form-control with-icon" value="{{ old('monthly_net_income', session('loan_data.monthly_net_income')) }}" placeholder="@lang('1,800')">
                                </div>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-clock label-icon"></i>
                                    @lang('Employment Length (Anzianità lavorativa)')
                                </label>
                                <input type="number" name="employment_length_years" class="loan-form-control" value="{{ old('employment_length_years', session('loan_data.employment_length_years')) }}" placeholder="@lang('4')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="loan-form-actions">
                <a href="{{ route('lead.wizard') }}" class="loan-back-btn">
                    <i class="las la-arrow-left"></i>
                    @lang('Back')
                </a>
                <button type="submit" class="loan-submit-btn">
                    <span class="btn-text">@lang('Next: Loan Details')</span>
                    <i class="las la-arrow-right btn-icon"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('Template::frontend.personal_loan.styles')

@push('script')
<script>
    (function($){
        'use strict';
        
        // Custom form validation
        $('#loanWizardStep2').on('submit', function(e) {
            var isValid = true;
            
            // Clear all previous errors
            $('.error-message').text('').hide();
            $('.loan-form-control, .nice-select').removeClass('is-invalid');
            
            // Validate required text fields
            var requiredFields = [
                {name: 'occupation', label: 'Occupation'},
                {name: 'monthly_net_income', label: 'Monthly Net Income'},
                {name: 'employment_length_years', label: 'Employment Length'}
            ];
            
            requiredFields.forEach(function(field) {
                var input = $('[name="' + field.name + '"]');
                if (!input.val() || input.val().trim() === '') {
                    isValid = false;
                    input.addClass('is-invalid');
                    input.closest('.loan-form-group').find('.error-message').text(field.label + ' is required').show();
                }
            });
            
            // Validate select boxes
            var selectFields = [
                {name: 'employment_duration_type', label: 'Employment Duration Type'},
                {name: 'contract_type', label: 'Contract Type'}
            ];
            
            requiredFields.forEach(function(field) {
                var input = $('[name="' + field.name + '"]');
                if (!input.val() || input.val().trim() === '') {
                    isValid = false;
                    input.addClass('is-invalid');
                    input.closest('.loan-form-group').find('.error-message').text(field.label + ' is required').show();
                }
            });
            
            // Validate income is positive
            var income = parseFloat($('[name="monthly_net_income"]').val());
            if (income && income <= 0) {
                isValid = false;
                $('[name="monthly_net_income"]').addClass('is-invalid');
                $('[name="monthly_net_income"]').closest('.loan-form-group').find('.error-message').text('Income must be greater than 0').show();
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                var firstError = $('.is-invalid').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 300);
                }
                return false;
            }
        });
        
        // Clear error on input
        $('.loan-form-control').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).closest('.loan-form-group').find('.error-message').text('').hide();
        });
        
        // Clear error on select change
        $('select').on('change', function() {
            $(this).closest('.custom--nice-select').find('.nice-select').removeClass('is-invalid');
            $(this).closest('.loan-form-group').find('.error-message').text('').hide();
        });
        
    })(jQuery);
</script>
@endpush

