@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <!-- Page Header -->
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Personal Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 1 of 3: Personal Information')</p>
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

        <!-- Step 1 Form -->
        <form method="POST" action="{{ route('lead.step1.store') }}" id="loanWizardStep1" class="loan-wizard-form">
            @csrf
            
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
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-tag label-icon"></i>
                                    @lang('Type of Request (Tipo di richiesta)')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="loan_type" id="loan_type">
                                        <option value="" disabled selected>@lang('Select loan type')</option>
                                        <option value="personal_loan" {{ old('loan_type', session('loan_data.loan_type')) == 'personal_loan' ? 'selected' : '' }}>@lang('Personal Loan')</option>
                                        <option value="microcredit" {{ old('loan_type', session('loan_data.loan_type')) == 'microcredit' ? 'selected' : '' }}>@lang('Microcredit')</option>
                                        <option value="leasing" {{ old('loan_type', session('loan_data.loan_type')) == 'leasing' ? 'selected' : '' }}>@lang('Leasing')</option>
                                        <option value="salary_secured" {{ old('loan_type', session('loan_data.loan_type')) == 'salary_secured' ? 'selected' : '' }}>@lang('Salary-secured Loan')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="loan_type_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-user label-icon"></i>
                                    @lang('First Name')
                                </label>
                                <input type="text" name="first_name" class="loan-form-control" value="{{ old('first_name', session('loan_data.first_name', $user->firstname ?? '')) }}" placeholder="@lang('Enter your first name')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-user label-icon"></i>
                                    @lang('Last Name')
                                </label>
                                <input type="text" name="last_name" class="loan-form-control" value="{{ old('last_name', session('loan_data.last_name', $user->lastname ?? '')) }}" placeholder="@lang('Enter your last name')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-calendar label-icon"></i>
                                    @lang('Date of Birth')
                                </label>
                                <input type="date" name="date_of_birth" class="loan-form-control" value="{{ old('date_of_birth', session('loan_data.date_of_birth', '')) }}">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-sort-numeric-up label-icon"></i>
                                    @lang('Age')
                                </label>
                                <input type="number" name="age" class="loan-form-control" value="{{ old('age', session('loan_data.age', '')) }}" placeholder="@lang('Enter your age')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-id-card label-icon"></i>
                                    @lang('Tax Code')
                                </label>
                                <input type="text" name="tax_code" class="loan-form-control" value="{{ old('tax_code', session('loan_data.tax_code', '')) }}" placeholder="@lang('Enter tax code')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-envelope label-icon"></i>
                                    @lang('Contact Email')
                                </label>
                                <input type="email" name="email" class="loan-form-control" value="{{ old('email', session('loan_data.email', $user->email ?? '')) }}" placeholder="@lang('example@email.com')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-phone label-icon"></i>
                                    @lang('Mobile Number')
                                </label>
                                <input type="text" name="mobile" class="loan-form-control" value="{{ old('mobile', session('loan_data.mobile', $user->mobile ?? '')) }}" placeholder="@lang('+39 333 0000000')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-map-marker label-icon"></i>
                                    @lang('Municipality of Residence')
                                </label>
                                <input type="text" name="city" class="loan-form-control" value="{{ old('city', session('loan_data.city', $user->city ?? '')) }}" placeholder="@lang('Milan')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-map label-icon"></i>
                                    @lang('Province')
                                </label>
                                <input type="text" name="province" class="loan-form-control" value="{{ old('province', session('loan_data.province', $user->state ?? '')) }}" placeholder="@lang('MI')" maxlength="2">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-heart label-icon"></i>
                                    @lang('Marital Status')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="marital_status" id="marital_status" required>
                                        <option value="" disabled selected>@lang('Select marital status')</option>
                                        <option value="married" {{ old('marital_status', session('loan_data.marital_status')) == 'married' ? 'selected' : '' }}>@lang('Married')</option>
                                        <option value="single" {{ old('marital_status', session('loan_data.marital_status')) == 'single' ? 'selected' : '' }}>@lang('Single')</option>
                                        <option value="cohabiting" {{ old('marital_status', session('loan_data.marital_status')) == 'cohabiting' ? 'selected' : '' }}>@lang('Cohabiting')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="marital_status_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-users label-icon"></i>
                                    @lang('Applicant Type')
                                </label>
                                <div class="custom--nice-select">
                                    <select name="applicant_type" id="applicant_type" required>
                                        <option value="" disabled selected>@lang('Select applicant type')</option>
                                        <option value="single" {{ old('applicant_type', session('loan_data.applicant_type')) == 'single' ? 'selected' : '' }}>@lang('Single Applicant')</option>
                                        <option value="joint" {{ old('applicant_type', session('loan_data.applicant_type')) == 'joint' ? 'selected' : '' }}>@lang('Joint Applicant')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="applicant_type_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label">
                                    <i class="las la-home label-icon"></i>
                                    @lang('Number of Family Members')
                                </label>
                                <input type="number" name="family_members" class="loan-form-control" value="{{ old('family_members', session('loan_data.family_members')) }}" placeholder="@lang('Enter number of family members')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="loan-form-actions">
                <button type="submit" class="loan-submit-btn">
                    <span class="btn-text">@lang('Next: Employment Information')</span>
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
        $('#loanWizardStep1').on('submit', function(e) {
            var isValid = true;
            
            // Clear all previous errors
            $('.error-message').text('').hide();
            $('.loan-form-control, .nice-select').removeClass('is-invalid');
            
            // Validate required fields
            var requiredFields = [
                {name: 'first_name', label: 'First Name'},
                {name: 'last_name', label: 'Last Name'},
                {name: 'date_of_birth', label: 'Date of Birth'},
                {name: 'age', label: 'Age'},
                {name: 'tax_code', label: 'Tax Code'},
                {name: 'email', label: 'Email'},
                {name: 'mobile', label: 'Mobile Number'},
                {name: 'city', label: 'City'},
                {name: 'family_members', label: 'Number of Family Members'}
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
                {name: 'loan_type', label: 'Type of Request'},
                {name: 'marital_status', label: 'Marital Status'},
                {name: 'applicant_type', label: 'Applicant Type'}
            ];
            
            selectFields.forEach(function(field) {
                var select = $('[name="' + field.name + '"]');
                if (!select.val() || select.val() === '') {
                    isValid = false;
                    select.closest('.custom--nice-select').find('.nice-select').addClass('is-invalid');
                    $('#' + field.name + '_error').text('Please select ' + field.label.toLowerCase()).show();
                    
                    // Scroll to first error
                    if (isValid === false) {
                        $('html, body').animate({
                            scrollTop: $('#' + field.name + '_error').offset().top - 100
                        }, 300);
                    }
                }
            });
            
            // Validate email format
            var email = $('[name="email"]').val();
            if (email && !isValidEmail(email)) {
                isValid = false;
                $('[name="email"]').addClass('is-invalid');
                $('[name="email"]').closest('.loan-form-group').find('.error-message').text('Please enter a valid email address').show();
            }
            
            // Validate age
            var age = parseInt($('[name="age"]').val());
            if (age && (age < 18 || age > 100)) {
                isValid = false;
                $('[name="age"]').addClass('is-invalid');
                $('[name="age"]').closest('.loan-form-group').find('.error-message').text('Age must be between 18 and 100').show();
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
        
        // Email validation helper
        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
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

