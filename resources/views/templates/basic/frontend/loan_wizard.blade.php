@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
<div class="container section">
        <!-- Page Header -->
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Personal Loan')</h2>
            <p class="loan-app-subtitle">@lang('Complete the form below to apply for your personal loan')</p>
        </div>

        <!-- Progress Steps -->
        <div class="loan-steps-wrapper mb-5">
            <div class="loan-steps">
                <div class="loan-step active">
                    <div class="loan-step-icon">
                        <i class="las la-user"></i>
                    </div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">01</span>
                        <span class="loan-step-title">@lang('Personal Info')</span>
                    </div>
                </div>
                <div class="loan-step-line"></div>
                <div class="loan-step">
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

        <!-- Application Form -->
        <form method="POST" action="{{ route('lead.store') }}" id="loanWizard" class="loan-wizard-form">
            @csrf
            
            <!-- Step 1: Personal Information -->
            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon">
                        <i class="las la-user-circle"></i>
                    </div>
                    <div>
                        <h4 class="loan-form-card-title">@lang('Personal Information')</h4>
                        <p class="loan-form-card-subtitle">@lang('Please provide your personal details')</p>
                    </div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-user label-icon"></i>
                                    @lang('First Name')
                                </label>
                                <input type="text" name="first_name" class="loan-form-control" required placeholder="@lang('Enter your first name')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-user label-icon"></i>
                                    @lang('Last Name')
                                </label>
                                <input type="text" name="last_name" class="loan-form-control" required placeholder="@lang('Enter your last name')">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-calendar label-icon"></i>
                                    @lang('Date of Birth')
                                </label>
                                <input type="date" name="date_of_birth" class="loan-form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-id-card label-icon"></i>
                                    @lang('Tax Code')
                                </label>
                                <input type="text" name="tax_code" class="loan-form-control" required placeholder="@lang('Enter tax code')">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-map-marker label-icon"></i>
                                    @lang('City of Residence')
                                </label>
                                <input type="text" name="city" class="loan-form-control" required placeholder="@lang('Enter your city')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-envelope label-icon"></i>
                                    @lang('Email Address')
                                </label>
                                <input type="email" name="email" class="loan-form-control" required placeholder="@lang('example@email.com')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-phone label-icon"></i>
                                    @lang('Mobile Number')
                                </label>
                                <input type="text" name="mobile" class="loan-form-control" required placeholder="@lang('+39 123 456 7890')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Employment Information -->
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
                    <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-user-tie label-icon"></i>
                                    @lang('Employment Type')
                                </label>
                                <div class="custom--nice-select">
                        <select name="employment_type" required>
                            <option value="" disabled selected>@lang('Select employment type')</option>
                            <option value="employee">@lang('Employee')</option>
                            <option value="self_employed">@lang('Self-employed')</option>
                            <option value="retired">@lang('Retired')</option>
                            <option value="other">@lang('Other')</option>
                        </select>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-euro-sign label-icon"></i>
                                    @lang('Monthly Net Income')
                                </label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="net_income" class="loan-form-control with-icon" required placeholder="@lang('0.00')">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Step 3: Loan Purpose -->
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
                    <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-home label-icon"></i>
                                    @lang('Loan Purpose')
                                </label>
                                <div class="custom--nice-select">
                        <select name="loan_purpose" id="loan_purpose" required>
                            <option value="" disabled selected>@lang('Select loan purpose')</option>
                                        <option value="first_home">@lang('First Home Purchase')</option>
                                        <option value="second_home">@lang('Second Home Purchase')</option>
                                        <option value="renovation">@lang('Home Renovation')</option>
                                        <option value="other">@lang('Other Purpose')</option>
                        </select>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6" id="purposeOther" style="display:none;">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-edit label-icon"></i>
                                    @lang('Specify Other Purpose')
                                </label>
                                <input type="text" name="loan_purpose_other" class="loan-form-control" placeholder="@lang('Please specify')">
                            </div>
                    </div>
                    <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-credit-card label-icon"></i>
                                    @lang('Current Financing')
                                </label>
                                <div class="custom--nice-select">
                        <select name="has_current_financing" id="hasFin" required>
                                        <option value="" disabled selected>@lang('Do you have existing loans?')</option>
                            <option value="0">@lang('No')</option>
                            <option value="1">@lang('Yes')</option>
                        </select>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6" id="remainingWrap" style="display:none;">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-money-bill-wave label-icon"></i>
                                    @lang('Remaining Amount')
                                </label>
                                <div class="input-with-icon">
                                    <span class="input-icon-left">€</span>
                                    <input type="number" step="0.01" name="current_financing_remaining" class="loan-form-control with-icon" placeholder="@lang('0.00')">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Submit Button -->
            <div class="loan-form-actions">
                <button type="submit" class="loan-submit-btn">
                    <span class="btn-text">@lang('Submit Personal Loan Application')</span>
                    <i class="las la-arrow-right btn-icon"></i>
                </button>
                <p class="loan-form-note">
                    <i class="las la-shield-alt"></i>
                    @lang('Your information is secure and will be processed confidentially')
                </p>
            </div>
    </form>
    </div>
</div>
@endsection

@push('style')
<style>
    /* ===================== Professional Loan Application Styling ===================== */
    
    /* Application Wrapper */
    .loan-application-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }
    
    /* Page Header */
    .loan-app-header {
        margin-bottom: 3rem;
    }
    
    .loan-app-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: hsl(var(--dark));
        margin-bottom: 0.75rem;
        position: relative;
        display: inline-block;
    }
    
    .loan-app-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, hsl(var(--base)), hsl(var(--base) / 0.6));
        border-radius: 2px;
    }
    
    .loan-app-subtitle {
        font-size: 1.125rem;
        color: hsl(var(--dark) / 0.7);
        margin-top: 1.5rem;
        font-weight: 400;
    }
    
    /* Progress Steps */
    .loan-steps-wrapper {
        max-width: 800px;
        margin: 0 auto 4rem;
    }
    
    .loan-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }
    
    .loan-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .loan-step-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: hsl(var(--white));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: hsl(var(--dark) / 0.4);
        border: 3px solid hsl(var(--border-color));
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .loan-step.active .loan-step-icon {
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.8));
        color: hsl(var(--white));
        border-color: hsl(var(--base));
        box-shadow: 0 8px 20px hsl(var(--base) / 0.3);
    }
    
    .loan-step-content {
        text-align: center;
    }
    
    .loan-step-number {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: hsl(var(--base));
        margin-bottom: 0.25rem;
    }
    
    .loan-step-title {
        font-size: 0.9375rem;
        color: hsl(var(--dark) / 0.7);
        font-weight: 500;
    }
    
    .loan-step.active .loan-step-title {
        color: hsl(var(--dark));
        font-weight: 600;
    }
    
    .loan-step-line {
        flex: 1;
        height: 3px;
        background: hsl(var(--border-color));
        margin: 0 1rem;
        margin-bottom: 4.5rem;
    }
    
    /* Form Card */
    .loan-form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 3rem;
        overflow: visible;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    .loan-form-card:has(.nice-select.open) {
        z-index: 9999 !important;
    }
    
    .loan-form-card:hover {
        border-color: #d0d7de;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .loan-form-card-header {
        background: linear-gradient(135deg, hsl(var(--base) / 0.06), hsl(var(--base) / 0.03));
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .loan-form-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.8));
        color: hsl(var(--white));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px hsl(var(--base) / 0.25);
    }
    
    .loan-form-card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: hsl(var(--dark));
        margin-bottom: 0.25rem;
    }
    
    .loan-form-card-subtitle {
        font-size: 0.9375rem;
        color: hsl(var(--dark) / 0.6);
        margin-bottom: 0;
    }
    
    .loan-form-card-body {
        padding: 2.5rem 2rem;
        padding-bottom: 3rem;
    }
    
    /* Form Groups */
    .loan-form-group {
        margin-bottom: 0;
        position: relative;
    }
    
    .loan-form-group:has(.nice-select.open) {
        z-index: 10000;
    }
    
    .loan-form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        font-weight: 600;
        color: hsl(var(--dark));
        margin-bottom: 0.75rem;
    }
    
    .label-icon {
        font-size: 1.125rem;
        color: hsl(var(--base));
    }
    
    /* Form Controls */
    .loan-form-control {
        width: 100%;
        height: 52px;
        padding: 0 1.25rem;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        font-size: 1rem;
        color: #333333;
        background: #ffffff;
        transition: all 0.3s ease;
        font-family: inherit;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .loan-form-control:hover {
        border-color: #b3bac1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .loan-form-control:focus {
        outline: none;
        border-color: hsl(var(--base));
        box-shadow: 0 0 0 4px hsl(var(--base) / 0.1);
        background: #ffffff;
    }
    
    .loan-form-control::placeholder {
        color: #6c757d;
        font-weight: 400;
    }
    
    /* Input with Icon */
    .input-with-icon {
        position: relative;
    }
    
    .input-icon-left {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.125rem;
        font-weight: 600;
        color: hsl(var(--base));
        z-index: 1;
    }
    
    .loan-form-control.with-icon {
        padding-left: 3rem;
    }
    
    /* Select Styling - Using Standard custom--nice-select */
    .loan-form-group .custom--nice-select {
        position: relative;
    }
    
    .loan-form-group .custom--nice-select .nice-select {
        width: 100%;
        height: 52px;
        line-height: 52px;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        padding: 0 1.25rem;
        padding-right: 3rem;
        font-size: 1rem;
        background-color: #ffffff;
        color: #333333;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .loan-form-group .custom--nice-select .nice-select .current {
        color: #333333;
        font-weight: 500;
        opacity: 1;
    }
    
    .loan-form-group .custom--nice-select .nice-select.disabled .current,
    .loan-form-group .custom--nice-select .nice-select .current:empty {
        color: #6c757d;
    }
    
    .loan-form-group .custom--nice-select .nice-select:hover {
        border-color: #b3bac1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .loan-form-group .custom--nice-select .nice-select:focus,
    .loan-form-group .custom--nice-select .nice-select.open {
        border-color: hsl(var(--base));
        box-shadow: 0 0 0 4px hsl(var(--base) / 0.1);
        z-index: 10000;
    }
    
    .loan-form-group .custom--nice-select .nice-select::after {
        border-bottom: 2.5px solid #6c757d;
        border-right: 2.5px solid #6c757d;
        height: 11px;
        width: 11px;
        right: 1.25rem;
        margin-top: -7px;
        transition: all 0.3s ease;
    }
    
    .loan-form-group .custom--nice-select .nice-select:hover::after {
        border-color: #495057;
    }
    
    .loan-form-group .custom--nice-select .nice-select.open::after {
        border-color: hsl(var(--base));
        transform: rotate(-135deg);
        margin-top: -3px;
    }
    
    .loan-form-group .custom--nice-select .nice-select .list {
        width: 100%;
        max-height: 280px;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25);
        background: #ffffff;
        z-index: 99999;
        margin-top: 8px;
    }
    
    .loan-form-group .custom--nice-select .nice-select .option {
        line-height: 1.5;
        min-height: auto;
        padding: 0.875rem 1.25rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        color: #333333;
        background: #ffffff;
    }
    
    .loan-form-group .custom--nice-select .nice-select .option:hover,
    .loan-form-group .custom--nice-select .nice-select .option.focus,
    .loan-form-group .custom--nice-select .nice-select .option.selected.focus {
        background-color: hsl(var(--base) / 0.08);
        color: #222222;
    }
    
    .loan-form-group .custom--nice-select .nice-select .option.selected {
        font-weight: 600;
        color: hsl(var(--base));
        background-color: hsl(var(--base) / 0.05);
    }
    
    .loan-form-group .custom--nice-select .nice-select .option.disabled {
        color: #999999;
    }
    
    /* Submit Button */
    .loan-form-actions {
        text-align: center;
        margin-top: 3rem;
    }
    
    .loan-submit-btn {
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.85));
        color: hsl(var(--white));
        border: none;
        padding: 1rem 3.5rem;
        border-radius: 50px;
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px hsl(var(--base) / 0.3);
    }
    
    .loan-submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px hsl(var(--base) / 0.4);
    }
    
    .loan-submit-btn .btn-icon {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }
    
    .loan-submit-btn:hover .btn-icon {
        transform: translateX(5px);
    }
    
    .loan-form-note {
        margin-top: 1.5rem;
        font-size: 0.9375rem;
        color: hsl(var(--dark) / 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .loan-form-note i {
        color: hsl(var(--base));
        font-size: 1.125rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .loan-app-title {
            font-size: 2rem;
        }
        
        .loan-steps {
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .loan-step-line {
            width: 3px;
            height: 40px;
            margin: 0;
            margin-bottom: 0;
        }
        
        .loan-form-card-header {
            flex-direction: column;
            text-align: center;
        }
        
        .loan-form-card-body {
            padding: 2rem 1.5rem;
        }
    }
</style>
@endpush

@push('script')
<script>
    (function($){
        'use strict';
        
        // Handle conditional fields
        $('#loan_purpose').on('change', function(){
            if(this.value === 'other') {
                $('#purposeOther').slideDown(300);
            } else {
                $('#purposeOther').slideUp(300);
            }
        });
        
        $('#hasFin').on('change', function(){
            if(this.value === '1') {
                $('#remainingWrap').slideDown(300);
            } else {
                $('#remainingWrap').slideUp(300);
            }
        });
        
    })(jQuery);
</script>
@endpush
