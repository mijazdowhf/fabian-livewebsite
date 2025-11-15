@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <!-- Page Header -->
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 3 of 3: Loan Details')</p>
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
                <div class="loan-step completed">
                    <div class="loan-step-icon">
                        <i class="las la-check"></i>
                    </div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">02</span>
                        <span class="loan-step-title">@lang('Employment')</span>
                    </div>
                </div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step active">
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

        <!-- Step 3 Form -->
        <form method="POST" action="{{ route('lead.step3.store') }}" id="loanWizardStep3" class="loan-wizard-form">
            @csrf
            
            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon">
                        <i class="las la-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h4 class="loan-form-card-title">@lang('Loan Purpose & Details')</h4>
                        <p class="loan-form-card-subtitle">@lang('Specify the purpose of your loan')</p>
                    </div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-tag label-icon"></i>
                                    @lang('Application Type')
                                </label>
                                <div class="loan-readonly-field">
                                    <i class="las la-hand-holding-usd"></i>
                                    @lang('Personal Loan')
                                </div>
                                <input type="hidden" name="application_type" value="personal_loan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-bullseye label-icon"></i>
                                    @lang('Loan Purpose (Finalità del prestito)')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="loan_purpose" id="loan_purpose">
                                        <option value="" disabled selected>@lang('Select loan purpose')</option>
                                        <option value="home_furnishings" {{ old('loan_purpose', session('loan_data.loan_purpose')) == 'home_furnishings' ? 'selected' : '' }}>@lang('Home Furnishings (Arredo casa)')</option>
                                        <option value="debt_consolidation" {{ old('loan_purpose', session('loan_data.loan_purpose')) == 'debt_consolidation' ? 'selected' : '' }}>@lang('Debt Consolidation (Consolidamento debiti)')</option>
                                        <option value="liquidity" {{ old('loan_purpose', session('loan_data.loan_purpose')) == 'liquidity' ? 'selected' : '' }}>@lang('Liquidity (Liquidità)')</option>
                                        <option value="vacation" {{ old('loan_purpose', session('loan_data.loan_purpose')) == 'vacation' ? 'selected' : '' }}>@lang('Vacation (Vacanze)')</option>
                                        <option value="health" {{ old('loan_purpose', session('loan_data.loan_purpose')) == 'health' ? 'selected' : '' }}>@lang('Health (Salute)')</option>
                                        <option value="other" {{ old('loan_purpose', session('loan_data.loan_purpose')) == 'other' ? 'selected' : '' }}>@lang('Other (Altro)')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="loan_purpose_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6" id="purposeOther" style="display:none;">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-edit label-icon"></i>
                                    @lang('Specify Other Purpose')
                                </label>
                                <input type="text" name="loan_purpose_other" class="loan-form-control" value="{{ old('loan_purpose_other', session('loan_data.loan_purpose_other')) }}" placeholder="@lang('Please specify')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-credit-card label-icon"></i>
                                    @lang('Current Financing (Finanziamenti in corso)')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="has_current_financing" id="has_current_financing">
                                        <option value="" disabled selected>@lang('Select')</option>
                                        <option value="1" {{ old('has_current_financing', session('loan_data.has_current_financing')) == '1' ? 'selected' : '' }}>@lang('Yes (Si)')</option>
                                        <option value="0" {{ old('has_current_financing', session('loan_data.has_current_financing')) == '0' ? 'selected' : '' }}>@lang('No')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="has_current_financing_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6" id="remainingAmountWrap" style="display:none;">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-money-bill-wave label-icon"></i>
                                    @lang('Remaining Debt (Debito residuo)')
                                </label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="current_financing_remaining" class="loan-form-control with-icon" value="{{ old('current_financing_remaining', session('loan_data.current_financing_remaining')) }}" placeholder="@lang('3,000')">
                                </div>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-12" id="financingDetailsWrap" style="display:none;">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-edit label-icon"></i>
                                    @lang('Current Financing Details')
                                </label>
                                <textarea name="current_financing_details" class="loan-form-textarea" rows="3" placeholder="@lang('e.g., 1 personal loan remaining €3,000')">{{ old('current_financing_details', session('loan_data.current_financing_details')) }}</textarea>
                                <small class="form-text text-muted">@lang('Describe any existing loans or financing you currently have')</small>
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <div class="privacy-checkbox">
                                    <input type="checkbox" name="privacy_authorization" id="privacy" value="1" {{ old('privacy_authorization', session('loan_data.privacy_authorization')) ? 'checked' : '' }}>
                                    <label for="privacy" class="privacy-label">
                                        <i class="las la-shield-alt"></i>
                                        @lang('I have read, confirm, and accept the terms and conditions and privacy policy')
                                    </label>
                                </div>
                                <span class="error-message" id="privacy_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="loan-form-actions">
                <a href="{{ route('lead.step2') }}" class="loan-back-btn">
                    <i class="las la-arrow-left"></i>
                    @lang('Back')
                </a>
                <button type="submit" class="loan-submit-btn">
                    <span class="btn-text">@lang('Submit Application')</span>
                    <i class="las la-check-circle btn-icon"></i>
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
        $('#loanWizardStep3').on('submit', function(e) {
            var isValid = true;
            
            // Clear all previous errors
            $('.error-message').text('').hide();
            $('.loan-form-control, .nice-select').removeClass('is-invalid');
            
            // Validate select fields
            var selectFields = [
                {name: 'loan_purpose', label: 'Loan Purpose'},
                {name: 'has_current_financing', label: 'Current Financing'}
            ];
            
            selectFields.forEach(function(field) {
                var select = $('[name="' + field.name + '"]');
                if (!select.val() || select.val() === '') {
                    isValid = false;
                    select.closest('.custom--nice-select').find('.nice-select').addClass('is-invalid');
                    $('#' + field.name + '_error').text('Please select ' + field.label.toLowerCase()).show();
                }
            });
            
            // Validate other purpose if other is selected
            if ($('[name="loan_purpose"]').val() === 'other') {
                var otherPurpose = $('[name="loan_purpose_other"]');
                if (!otherPurpose.val() || otherPurpose.val().trim() === '') {
                    isValid = false;
                    otherPurpose.addClass('is-invalid');
                    otherPurpose.closest('.loan-form-group').find('.error-message').text('Please specify the other purpose').show();
                }
            }
            
            // Validate privacy checkbox
            if (!$('#privacy').is(':checked')) {
                isValid = false;
                $('#privacy_error').text('You must authorize the processing of your personal data to continue').show();
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
        
        // Show other purpose field if other is selected
        $('#loan_purpose').on('change', function(){
            if(this.value === 'other') {
                $('#purposeOther').slideDown(300);
            } else {
                $('#purposeOther').slideUp(300);
            }
        });
        
        // Trigger on page load if already selected
        if($('#loan_purpose').val() === 'other') {
            $('#purposeOther').show();
        }
        
        // Show/hide financing details
        $('[name="has_current_financing"]').on('change', function() {
            if ($(this).val() == '1') {
                $('#remainingAmountWrap').slideDown();
                $('#financingDetailsWrap').slideDown();
            } else {
                $('#remainingAmountWrap').slideUp();
                $('#financingDetailsWrap').slideUp();
            }
        });
        
        // Trigger on page load
        if ($('[name="has_current_financing"]').val() == '1') {
            $('#remainingAmountWrap').show();
            $('#financingDetailsWrap').show();
        }
        
        // Clear error on input
        $('.loan-form-control, .loan-form-textarea').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).closest('.loan-form-group').find('.error-message').text('').hide();
        });
        
        // Clear error on select change
        $('select').on('change', function() {
            $(this).closest('.custom--nice-select').find('.nice-select').removeClass('is-invalid');
            $(this).closest('.loan-form-group').find('.error-message').text('').hide();
        });
        
        // Clear error on checkbox change
        $('#privacy').on('change', function() {
            if ($(this).is(':checked')) {
                $('#privacy_error').text('').hide();
            }
        });
        
    })(jQuery);
</script>
@endpush

