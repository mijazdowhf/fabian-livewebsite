@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="mt-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <!-- Page Header -->
                    <div class="mb-4">
                        <h2 class="text-dark fw-bold mb-2">
                            <i class="las la-folder-open" style="color: #025AA3;"></i> @lang('My Applications')
                        </h2>
                        <p class="text-muted">@lang('View and track all your loan and mortgage applications')</p>
                    </div>

                    @php
                        $totalApplications = $mortgages->count() + $personalLoans->count();
                    @endphp

                    @if($totalApplications === 0)
                        <!-- Empty State -->
                        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <div class="d-inline-block rounded-circle p-4" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%);">
                                        <i class="las la-folder-open text-white" style="font-size: 60px;"></i>
                                    </div>
                                </div>
                                <h4 class="text-dark fw-bold mb-3">@lang('No Applications Yet')</h4>
                                <p class="text-muted mb-4">@lang('You have not submitted any applications yet. Start by applying for a loan or mortgage.')</p>
                                <div class="mt-4">
                                    <a href="{{ route('selling.mortgage.apply') }}" class="btn btn-lg px-4 me-2 mb-2" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); color: white; border-radius: 10px;">
                                        <i class="las la-home"></i> @lang('Apply for Home Mortgage')
                                    </a>
                                    <a href="{{ route('personal.loan.apply') }}" class="btn btn-lg px-4 mb-2" style="background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%); color: white; border-radius: 10px;">
                                        <i class="las la-money-bill"></i> @lang('Apply for Personal Loan')
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%);">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="text-white-50 mb-2 small">@lang('Home Mortgages')</p>
                                                <h2 class="text-white fw-bold mb-0">{{ $mortgages->count() }}</h2>
                                            </div>
                                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                                <i class="las la-home text-white" style="font-size: 36px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%);">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="text-white-50 mb-2 small">@lang('Personal Loans')</p>
                                                <h2 class="text-white fw-bold mb-0">{{ $personalLoans->count() }}</h2>
                                            </div>
                                            <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                                <i class="las la-money-bill text-white" style="font-size: 36px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabbed Card -->
                        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                            <!-- Tabs Navigation -->
                            <div class="card-header bg-white border-0 pt-4 px-4" style="border-radius: 15px 15px 0 0;">
                                <ul class="nav nav-tabs border-0" id="applicationsTab" role="tablist" style="gap: 10px;">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="mortgages-tab" data-bs-toggle="tab" data-bs-target="#mortgages" type="button" role="tab" style="border-radius: 10px; border: none; padding: 12px 24px; font-weight: 600; transition: all 0.3s;">
                                            <i class="las la-home"></i> @lang('Home Mortgages') <span class="badge rounded-pill ms-2" style="background: #025AA3;">{{ $mortgages->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="personal-loans-tab" data-bs-toggle="tab" data-bs-target="#personal-loans" type="button" role="tab" style="border-radius: 10px; border: none; padding: 12px 24px; font-weight: 600; transition: all 0.3s;">
                                            <i class="las la-money-bill"></i> @lang('Personal Loans') <span class="badge rounded-pill ms-2" style="background: #CA19A8;">{{ $personalLoans->count() }}</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tabs Content -->
                            <div class="card-body p-4">
                                <div class="tab-content" id="applicationsTabContent">
                                    
                                    <!-- Home Mortgages Tab -->
                                    <div class="tab-pane fade show active" id="mortgages" role="tabpanel">
                                        @if($mortgages->count() > 0)
                                            <div class="row g-3">
                                                @foreach($mortgages as $mortgage)
                                                    <div class="col-md-6">
                                                        <div class="card border h-100" style="border-radius: 12px; border: 1px solid #e0e0e0 !important; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(2, 90, 163, 0.15)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); border-radius: 8px; font-size: 13px; color: white;">
                                                                        {{ $mortgage->application_id }}
                                                                    </span>
                                                                    @if($mortgage->status === 'pending')
                                                                        <span class="badge bg-warning px-3 py-2" style="border-radius: 8px;">@lang('Pending')</span>
                                                                    @elseif($mortgage->status === 'approved')
                                                                        <span class="badge bg-success px-3 py-2" style="border-radius: 8px;">@lang('Approved')</span>
                                                                    @elseif($mortgage->status === 'rejected')
                                                                        <span class="badge bg-danger px-3 py-2" style="border-radius: 8px;">@lang('Rejected')</span>
                                                                    @else
                                                                        <span class="badge bg-secondary px-3 py-2" style="border-radius: 8px;">{{ ucfirst($mortgage->status) }}</span>
                                                                    @endif
                                                                </div>
                                                                
                                                                <h6 class="text-dark fw-bold mb-3">{{ $mortgage->property_type }}</h6>
                                                                
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="las la-euro-sign text-muted me-2"></i>
                                                                            <div>
                                                                                <small class="text-muted d-block" style="font-size: 11px;">@lang('Amount')</small>
                                                                                <strong class="text-dark">€{{ number_format($mortgage->mortgage_amount, 0) }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="las la-clock text-muted me-2"></i>
                                                                            <div>
                                                                                <small class="text-muted d-block" style="font-size: 11px;">@lang('Duration')</small>
                                                                                <strong class="text-dark">{{ $mortgage->mortgage_duration_months }}m</strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3 pb-3 border-bottom">
                                                                    @if($mortgage->agent)
                                                                        <small class="text-muted">
                                                                            <i class="las la-user-tie"></i> {{ $mortgage->agent->fullname }}
                                                                        </small>
                                                                    @else
                                                                        <small class="text-muted">
                                                                            <i class="las la-info-circle"></i> @lang('Not Assigned')
                                                                        </small>
                                                                    @endif
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <small class="text-muted">
                                                                        <i class="las la-calendar"></i> {{ $mortgage->created_at->format('d M Y') }}
                                                                    </small>
                                                                    <button type="button" class="btn btn-sm" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); color: white; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#mortgageModal{{ $mortgage->id }}">
                                                                        <i class="las la-eye"></i> @lang('View')
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="las la-folder-open text-muted" style="font-size: 64px;"></i>
                                                <h5 class="text-muted mt-3">@lang('No home mortgage applications yet')</h5>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Personal Loans Tab -->
                                    <div class="tab-pane fade" id="personal-loans" role="tabpanel">
                                        @if($personalLoans->count() > 0)
                                            <div class="row g-3">
                                                @foreach($personalLoans as $loan)
                                                    <div class="col-md-6">
                                                        <div class="card border h-100" style="border-radius: 12px; border: 1px solid #e0e0e0 !important; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(202, 25, 168, 0.15)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%); border-radius: 8px; font-size: 13px; color: white;">
                                                                        {{ $loan->application_id }}
                                                                    </span>
                                                                    @if($loan->status === 'pending')
                                                                        <span class="badge bg-warning px-3 py-2" style="border-radius: 8px;">@lang('Pending')</span>
                                                                    @elseif($loan->status === 'approved')
                                                                        <span class="badge bg-success px-3 py-2" style="border-radius: 8px;">@lang('Approved')</span>
                                                                    @elseif($loan->status === 'rejected')
                                                                        <span class="badge bg-danger px-3 py-2" style="border-radius: 8px;">@lang('Rejected')</span>
                                                                    @else
                                                                        <span class="badge bg-secondary px-3 py-2" style="border-radius: 8px;">{{ ucfirst($loan->status) }}</span>
                                                                    @endif
                                                                </div>
                                                                
                                                                <h6 class="text-dark fw-bold mb-3">{{ ucfirst($loan->loan_type ?? 'Personal Loan') }}</h6>
                                                                
                                                                <div class="row g-2 mb-3">
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="las la-briefcase text-muted me-2"></i>
                                                                            <div>
                                                                                <small class="text-muted d-block" style="font-size: 11px;">@lang('Purpose')</small>
                                                                                <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $loan->loan_purpose ?? 'N/A')) }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="las la-euro-sign text-muted me-2"></i>
                                                                            <div>
                                                                                <small class="text-muted d-block" style="font-size: 11px;">@lang('Monthly Income')</small>
                                                                                <strong class="text-dark">€{{ number_format($loan->monthly_net_income ?? 0, 0) }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3 pb-3 border-bottom">
                                                                    @if($loan->agent)
                                                                        <small class="text-muted">
                                                                            <i class="las la-user-tie"></i> {{ $loan->agent->fullname }}
                                                                        </small>
                                                                    @else
                                                                        <small class="text-muted">
                                                                            <i class="las la-info-circle"></i> @lang('Not Assigned')
                                                                        </small>
                                                                    @endif
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <small class="text-muted">
                                                                        <i class="las la-calendar"></i> {{ $loan->created_at->format('d M Y') }}
                                                                    </small>
                                                                    <button type="button" class="btn btn-sm" style="background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%); color: white; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#loanModal{{ $loan->id }}">
                                                                        <i class="las la-eye"></i> @lang('View')
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="las la-folder-open text-muted" style="font-size: 64px;"></i>
                                                <h5 class="text-muted mt-3">@lang('No personal loan applications yet')</h5>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('Template::user.applications.modals')
@endsection

@push('style')
<style>
    /* Custom Tab Styling */
    #applicationsTab .nav-link {
        color: #6c757d;
        background: #f8f9fa;
        border: 1px solid #e9ecef !important;
    }
    
    #applicationsTab .nav-link:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    #applicationsTab .nav-link.active {
        background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(2, 90, 163, 0.3);
    }
    
    #applicationsTab .nav-link.active .badge {
        background: white !important;
        color: #025AA3 !important;
    }
    
    /* Tab content animation */
    .tab-pane {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
