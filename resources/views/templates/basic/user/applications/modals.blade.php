<!-- Modals for Mortgage Details -->
@foreach($mortgages as $mortgage)
    <div class="modal fade" id="mortgageModal{{ $mortgage->id }}" tabindex="-1" aria-labelledby="mortgageModalLabel{{ $mortgage->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title text-white fw-bold" id="mortgageModalLabel{{ $mortgage->id }}">
                        <i class="las la-home"></i> @lang('Home Mortgage Details')
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert" style="background: linear-gradient(135deg, #e6f2fa 0%, #ffffff 100%); border: none; border-radius: 10px; border-left: 4px solid #025AA3; padding: 18px;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-2" style="font-size: 13px;">@lang('Application ID')</small>
                                <strong class="text-dark" style="font-size: 16px;">{{ $mortgage->application_id }}</strong>
                            </div>
                            <div class="col-md-6 text-md-end">
                                @if($mortgage->status === 'pending')
                                    <span class="badge bg-warning px-3 py-2" style="font-size: 14px;">@lang('Pending')</span>
                                @elseif($mortgage->status === 'approved')
                                    <span class="badge bg-success px-3 py-2" style="font-size: 14px;">@lang('Approved')</span>
                                @elseif($mortgage->status === 'rejected')
                                    <span class="badge bg-danger px-3 py-2" style="font-size: 14px;">@lang('Rejected')</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2" style="font-size: 14px;">{{ ucfirst($mortgage->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-user" style="color: #025AA3;"></i> @lang('Personal Information')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Full Name')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->first_name }} {{ $mortgage->last_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Date of Birth')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->date_of_birth ? $mortgage->date_of_birth->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Email')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Mobile')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->mobile }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('City')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->city }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Province')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->province }}</p>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-home" style="color: #025AA3;"></i> @lang('Mortgage Details')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Property Type')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->property_type }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Property Location')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->property_location }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Mortgage Amount')</small>
                            <p class="text-dark fw-bold mb-0" style="font-size: 16px; color: #025AA3;">€{{ number_format($mortgage->mortgage_amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Mortgage Duration')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->mortgage_duration_months }} @lang('months')</p>
                        </div>
                        <div class="col-md-12">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Mortgage Purpose')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->mortgage_purpose }}</p>
                        </div>
                    </div>

                    @if($mortgage->business_name)
                        <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                            <i class="las la-briefcase" style="color: #025AA3;"></i> @lang('Business Information')
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Business Name')</small>
                                <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->business_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Business Type')</small>
                                <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->business_type }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('VAT Number')</small>
                                <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->vat_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Annual Revenue')</small>
                                <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">€{{ number_format($mortgage->annual_revenue, 2) }}</p>
                            </div>
                        </div>
                    @endif

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-wallet" style="color: #025AA3;"></i> @lang('Financial Information')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Monthly Net Income')</small>
                            <p class="text-dark fw-bold mb-0" style="font-size: 16px; color: #025AA3;">€{{ number_format($mortgage->monthly_net_income, 2) }}</p>
                        </div>
                        @if($mortgage->current_financing_remaining)
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Current Financing Remaining')</small>
                                <p class="text-dark fw-bold mb-0" style="font-size: 16px; color: #CA19A8;">€{{ number_format($mortgage->current_financing_remaining, 2) }}</p>
                            </div>
                        @endif
                    </div>

                    @if($mortgage->agent)
                        <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                            <i class="las la-user-tie" style="color: #025AA3;"></i> @lang('Assigned Agent')
                        </h6>
                        <div class="alert alert-light border" style="border-radius: 10px;">
                            <strong class="text-dark" style="font-size: 15px;">{{ $mortgage->agent->fullname }}</strong><br>
                            <small class="text-muted" style="font-size: 13px;">@lang('Agent ID'): {{ $mortgage->agent->username }}</small>
                        </div>
                    @endif

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Submitted On')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Last Updated')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $mortgage->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($mortgage->admin_notes)
                        <div class="alert alert-info mt-3" style="border-radius: 10px; padding: 15px;">
                            <strong style="font-size: 15px;">@lang('Admin Notes'):</strong><br>
                            <p class="mb-0 mt-2" style="font-size: 14px;">{{ $mortgage->admin_notes }}</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 bg-light" style="border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 14px; padding: 10px 24px;">
                        <i class="las la-times"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Modals for Personal Loan Details -->
@foreach($personalLoans as $loan)
    <div class="modal fade" id="loanModal{{ $loan->id }}" tabindex="-1" aria-labelledby="loanModalLabel{{ $loan->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title text-white fw-bold" id="loanModalLabel{{ $loan->id }}">
                        <i class="las la-money-bill"></i> @lang('Personal Loan Details')
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert" style="background: linear-gradient(135deg, #fce9f8 0%, #ffffff 100%); border: none; border-radius: 10px; border-left: 4px solid #CA19A8; padding: 18px;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-2" style="font-size: 13px;">@lang('Application ID')</small>
                                <strong class="text-dark" style="font-size: 16px;">{{ $loan->application_id }}</strong>
                            </div>
                            <div class="col-md-6 text-md-end">
                                @if($loan->status === 'pending')
                                    <span class="badge bg-warning px-3 py-2" style="font-size: 14px;">@lang('Pending')</span>
                                @elseif($loan->status === 'approved')
                                    <span class="badge bg-success px-3 py-2" style="font-size: 14px;">@lang('Approved')</span>
                                @elseif($loan->status === 'rejected')
                                    <span class="badge bg-danger px-3 py-2" style="font-size: 14px;">@lang('Rejected')</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2" style="font-size: 14px;">{{ ucfirst($loan->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-user" style="color: #CA19A8;"></i> @lang('Personal Information')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Full Name')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->first_name }} {{ $loan->last_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Email')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Mobile')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->mobile }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('City')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->city }}</p>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-money-bill" style="color: #CA19A8;"></i> @lang('Loan Information')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Loan Type')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ ucfirst($loan->loan_type ?? 'Personal Loan') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Applicant Type')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ ucfirst($loan->applicant_type ?? 'Individual') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Loan Purpose')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ ucfirst(str_replace('_', ' ', $loan->loan_purpose ?? 'N/A')) }}</p>
                        </div>
                        @if($loan->loan_purpose_other)
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Purpose Details')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->loan_purpose_other }}</p>
                        </div>
                        @endif
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-briefcase" style="color: #CA19A8;"></i> @lang('Employment Details')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Occupation')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ ucfirst($loan->occupation ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Contract Type')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ ucfirst(str_replace('_', ' ', $loan->contract_type ?? 'N/A')) }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Employment Length')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->employment_length_years ?? 'N/A' }} @lang('years')</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Monthly Net Income')</small>
                            <p class="text-dark fw-bold mb-0" style="font-size: 16px; color: #025AA3;">€{{ number_format($loan->monthly_net_income ?? 0, 2) }}</p>
                        </div>
                    </div>

                    @if($loan->has_current_financing)
                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-credit-card" style="color: #CA19A8;"></i> @lang('Current Financing')
                    </h6>
                    <div class="row g-3">
                        @if($loan->current_financing_details)
                        <div class="col-md-12">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Financing Details')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->current_financing_details }}</p>
                        </div>
                        @endif
                        @if($loan->current_financing_remaining)
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Remaining Amount')</small>
                            <p class="text-dark fw-bold mb-0" style="font-size: 16px; color: #CA19A8;">€{{ number_format($loan->current_financing_remaining, 2) }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                        <i class="las la-users" style="color: #CA19A8;"></i> @lang('Family Details')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Marital Status')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ ucfirst($loan->marital_status ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Family Members')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->family_members ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($loan->agent)
                        <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom" style="font-size: 16px;">
                            <i class="las la-user-tie" style="color: #CA19A8;"></i> @lang('Assigned Agent')
                        </h6>
                        <div class="alert alert-light border" style="border-radius: 10px;">
                            <strong class="text-dark" style="font-size: 15px;">{{ $loan->agent->fullname }}</strong><br>
                            <small class="text-muted" style="font-size: 13px;">@lang('Agent ID'): {{ $loan->agent->username }}</small>
                        </div>
                    @endif

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Submitted On')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1" style="font-size: 13px;">@lang('Last Updated')</small>
                            <p class="text-dark mb-0 fw-semibold" style="font-size: 15px;">{{ $loan->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 14px; padding: 10px 24px;">
                        <i class="las la-times"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Modals for Employee Loan Details -->
@foreach($employeeLoans as $empLoan)
    <div class="modal fade" id="empLoanModal{{ $empLoan->id }}" tabindex="-1" aria-labelledby="empLoanModalLabel{{ $empLoan->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #0373cc 0%, #025AA3 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title text-white fw-bold" id="empLoanModalLabel{{ $empLoan->id }}">
                        <i class="las la-briefcase"></i> @lang('Employee Loan Details')
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert" style="background: linear-gradient(135deg, #e6f2fa 0%, #ffffff 100%); border: none; border-radius: 10px; border-left: 4px solid #0373cc;">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">@lang('Application ID')</small>
                                <strong class="text-dark">{{ $empLoan->application_id }}</strong>
                            </div>
                            <div class="col-md-6 text-md-end">
                                @if($empLoan->status === 'pending')
                                    <span class="badge bg-warning px-3 py-2">@lang('Pending')</span>
                                @elseif($empLoan->status === 'approved')
                                    <span class="badge bg-success px-3 py-2">@lang('Approved')</span>
                                @elseif($empLoan->status === 'rejected')
                                    <span class="badge bg-danger px-3 py-2">@lang('Rejected')</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">{{ ucfirst($empLoan->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom">
                        <i class="las la-user" style="color: #0373cc;"></i> @lang('Personal Information')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Full Name')</small>
                            <p class="text-dark mb-0">{{ $empLoan->first_name }} {{ $empLoan->last_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Email')</small>
                            <p class="text-dark mb-0">{{ $empLoan->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Mobile')</small>
                            <p class="text-dark mb-0">{{ $empLoan->mobile }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('City')</small>
                            <p class="text-dark mb-0">{{ $empLoan->city }}</p>
                        </div>
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom">
                        <i class="las la-briefcase" style="color: #0373cc;"></i> @lang('Loan Details')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Loan Amount')</small>
                            <p class="text-dark fw-bold mb-0">€{{ number_format($empLoan->loan_amount ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Loan Duration')</small>
                            <p class="text-dark mb-0">{{ $empLoan->loan_duration_months ?? 'N/A' }} @lang('months')</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Loan Purpose')</small>
                            <p class="text-dark mb-0">{{ ucfirst(str_replace('_', ' ', $empLoan->loan_purpose ?? 'N/A')) }}</p>
                        </div>
                        @if($empLoan->loan_purpose_other)
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Purpose Details')</small>
                            <p class="text-dark mb-0">{{ $empLoan->loan_purpose_other }}</p>
                        </div>
                        @endif
                    </div>

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom">
                        <i class="las la-building" style="color: #0373cc;"></i> @lang('Employment Information')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Employer Name')</small>
                            <p class="text-dark mb-0">{{ $empLoan->employer_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Contract Type')</small>
                            <p class="text-dark mb-0">{{ ucfirst(str_replace('_', ' ', $empLoan->contract_type ?? 'N/A')) }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Employment Length')</small>
                            <p class="text-dark mb-0">{{ $empLoan->employment_length_years ?? 'N/A' }} @lang('years')</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Employment Start Date')</small>
                            <p class="text-dark mb-0">{{ $empLoan->employment_start_date ? $empLoan->employment_start_date->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Monthly Net Income')</small>
                            <p class="text-dark fw-bold mb-0">€{{ number_format($empLoan->monthly_net_income ?? 0, 2) }}</p>
                        </div>
                    </div>

                    @if($empLoan->current_financing_remaining > 0)
                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom">
                        <i class="las la-credit-card" style="color: #0373cc;"></i> @lang('Current Financing')
                    </h6>
                    <div class="row g-3">
                        @if($empLoan->current_financing_details)
                        <div class="col-md-12">
                            <small class="text-muted d-block">@lang('Financing Details')</small>
                            <p class="text-dark mb-0">{{ $empLoan->current_financing_details }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Remaining Amount')</small>
                            <p class="text-dark fw-bold mb-0">€{{ number_format($empLoan->current_financing_remaining, 2) }}</p>
                        </div>
                    </div>
                    @endif

                    <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom">
                        <i class="las la-users" style="color: #0373cc;"></i> @lang('Family Details')
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Marital Status')</small>
                            <p class="text-dark mb-0">{{ ucfirst($empLoan->marital_status ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Family Status')</small>
                            <p class="text-dark mb-0">{{ ucfirst($empLoan->family_status ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Family Members')</small>
                            <p class="text-dark mb-0">{{ $empLoan->family_members ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($empLoan->agent)
                        <h6 class="text-dark fw-bold mt-4 mb-3 pb-2 border-bottom">
                            <i class="las la-user-tie" style="color: #0373cc;"></i> @lang('Assigned Agent')
                        </h6>
                        <div class="alert alert-light border" style="border-radius: 10px;">
                            <strong class="text-dark">{{ $empLoan->agent->fullname }}</strong><br>
                            <small class="text-muted">@lang('Agent ID'): {{ $empLoan->agent->username }}</small>
                        </div>
                    @endif

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Submitted On')</small>
                            <p class="text-dark mb-0">{{ $empLoan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">@lang('Last Updated')</small>
                            <p class="text-dark mb-0">{{ $empLoan->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="border-radius: 0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        <i class="las la-times"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

