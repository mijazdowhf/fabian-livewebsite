@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-6 mb-30">
            <div class="card b-radius--10 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-3">@lang('Personal Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Name')</span><span class="fw-bold">{{ $application->first_name }} {{ $application->last_name }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Age')</span><span>{{ $application->age }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Tax Code')</span><span>{{ $application->tax_code }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Email')</span><span>{{ $application->email }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Mobile')</span><span>{{ $application->mobile }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Location')</span><span>{{ $application->city }} ({{ $application->province }})</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Marital')</span><span>{{ ucfirst($application->marital_status) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Family')</span><span>{{ $application->family_members }} members</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-30">
            <div class="card b-radius--10 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-3">@lang('Employment & Loan')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Employer')</span><span class="fw-bold">{{ $application->employer_name }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Contract')</span><span>{{ ucfirst($application->contract_type) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Income')</span><span class="text-success fw-bold">€{{ showAmount($application->monthly_net_income) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Loan Amount')</span><span class="text-primary fw-bold">€{{ showAmount($application->loan_amount) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Duration')</span><span>{{ $application->loan_duration_months }} months</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Purpose')</span><span>{{ ucfirst(str_replace('_', ' ', $application->loan_purpose)) }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>@lang('Status')</span>
                            <form action="{{ route('admin.employee.loans.status', $application->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $application->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 mb-30">
            <div class="card b-radius--10 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-3">@lang('Documents')</h5>
                    <div class="row">
                        @foreach(['doc_valid_id'=>'Valid ID','doc_payslips'=>'Payslips','doc_employment_contract'=>'Employment Contract','doc_certificate_residency'=>'Certificate of Residency','doc_family_status'=>'Family Status','doc_marital_status'=>'Marital Status','doc_health_card'=>'Health Card','doc_residence_permit'=>'Residence Permit','doc_passport'=>'Passport','doc_cu_2025'=>'CU 2025','doc_bank_statement'=>'Bank Statement','doc_transactions_30days'=>'Transactions 30 Days','doc_inps_statement'=>'INPS Statement','doc_loan_agreement'=>'Loan Agreement','doc_isee'=>'ISEE'] as $field => $label)
                            @if($application->$field)
                                <div class="col-md-4 mb-3">
                                    <a href="{{ asset('storage/' . $application->$field) }}" target="_blank" class="btn btn-outline--info btn-sm w-100">
                                        <i class="las la-file-pdf"></i> {{ $label }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

