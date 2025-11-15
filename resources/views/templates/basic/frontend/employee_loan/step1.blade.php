@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Employee Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 1 of 4: Personal Information')</p>
        </div>

        <!-- Progress Steps -->
        <div class="loan-steps-wrapper mb-5">
            <div class="loan-steps">
                <div class="loan-step active">
                    <div class="loan-step-icon"><i class="las la-user"></i></div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">01</span>
                        <span class="loan-step-title">@lang('Personal')</span>
                    </div>
                </div>
                <div class="loan-step-line"></div>
                <div class="loan-step">
                    <div class="loan-step-icon"><i class="las la-briefcase"></i></div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">02</span>
                        <span class="loan-step-title">@lang('Employment')</span>
                    </div>
                </div>
                <div class="loan-step-line"></div>
                <div class="loan-step">
                    <div class="loan-step-icon"><i class="las la-file-invoice-dollar"></i></div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">03</span>
                        <span class="loan-step-title">@lang('Loan Details')</span>
                    </div>
                </div>
                <div class="loan-step-line"></div>
                <div class="loan-step">
                    <div class="loan-step-icon"><i class="las la-file-pdf"></i></div>
                    <div class="loan-step-content">
                        <span class="loan-step-number">04</span>
                        <span class="loan-step-title">@lang('Documents')</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('employee.loan.step1.store') }}" id="employeeLoanStep1" class="loan-wizard-form">
            @csrf
            
            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon"><i class="las la-user-circle"></i></div>
                    <div>
                        <h4 class="loan-form-card-title">@lang('Personal Information')</h4>
                        <p class="loan-form-card-subtitle">@lang('Please provide your personal details')</p>
                    </div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-user label-icon"></i>@lang('First Name')</label>
                                <input type="text" name="first_name" class="loan-form-control" value="{{ old('first_name', session('employee_loan.first_name')) }}" placeholder="@lang('Enter your first name')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-user label-icon"></i>@lang('Last Name')</label>
                                <input type="text" name="last_name" class="loan-form-control" value="{{ old('last_name', session('employee_loan.last_name')) }}" placeholder="@lang('Enter your last name')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-calendar label-icon"></i>@lang('Date of Birth')</label>
                                <input type="date" name="date_of_birth" class="loan-form-control" value="{{ old('date_of_birth', session('employee_loan.date_of_birth')) }}">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-sort-numeric-up label-icon"></i>@lang('Age')</label>
                                <input type="number" name="age" class="loan-form-control" value="{{ old('age', session('employee_loan.age')) }}" placeholder="@lang('35')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-id-card label-icon"></i>@lang('Tax Code')</label>
                                <input type="text" name="tax_code" class="loan-form-control" value="{{ old('tax_code', session('employee_loan.tax_code')) }}" placeholder="@lang('Enter tax code')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-envelope label-icon"></i>@lang('Email Address')</label>
                                <input type="email" name="email" class="loan-form-control" value="{{ old('email', session('employee_loan.email')) }}" placeholder="@lang('name@email.com')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-phone label-icon"></i>@lang('Telephone')</label>
                                <input type="text" name="mobile" class="loan-form-control" value="{{ old('mobile', session('employee_loan.mobile')) }}" placeholder="@lang('+39 333 0000000')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-map-marker label-icon"></i>@lang('Municipality of Residence')</label>
                                <input type="text" name="city" class="loan-form-control" value="{{ old('city', session('employee_loan.city')) }}" placeholder="@lang('Milan')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-map label-icon"></i>@lang('Province')</label>
                                <input type="text" name="province" class="loan-form-control" value="{{ old('province', session('employee_loan.province')) }}" placeholder="@lang('MI')" maxlength="2">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-heart label-icon"></i>@lang('Marital Status')</label>
                                <div class="custom--nice-select">
                                    <select name="marital_status" id="marital_status">
                                        <option value="" disabled selected>@lang('Select marital status')</option>
                                        <option value="married" {{ old('marital_status', session('employee_loan.marital_status')) == 'married' ? 'selected' : '' }}>@lang('Married')</option>
                                        <option value="single" {{ old('marital_status', session('employee_loan.marital_status')) == 'single' ? 'selected' : '' }}>@lang('Single')</option>
                                        <option value="cohabiting" {{ old('marital_status', session('employee_loan.marital_status')) == 'cohabiting' ? 'selected' : '' }}>@lang('Cohabiting')</option>
                                    </select>
                                </div>
                                <span class="error-message" id="marital_status_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-users label-icon"></i>@lang('Family Status')</label>
                                <input type="text" name="family_status" class="loan-form-control" value="{{ old('family_status', session('employee_loan.family_status')) }}" placeholder="@lang('e.g., Married with 2 children')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="loan-form-group">
                                <label class="loan-form-label"><i class="las la-home label-icon"></i>@lang('Number of Family Members')</label>
                                <input type="number" name="family_members" class="loan-form-control" value="{{ old('family_members', session('employee_loan.family_members')) }}" placeholder="@lang('3')">
                                <span class="error-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="loan-form-actions">
                <a href="{{ route('loan.type.selector') }}" class="loan-back-btn">
                    <i class="las la-arrow-left"></i>@lang('Back')
                </a>
                <button type="submit" class="loan-submit-btn">
                    <span class="btn-text">@lang('Next: Employment')</span>
                    <i class="las la-arrow-right btn-icon"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('Template::frontend.personal_loan.styles')
@include('Template::frontend.employee_loan.validation_script')

