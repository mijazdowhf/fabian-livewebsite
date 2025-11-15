@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Application ID')</th>
                                    <th>@lang('Loan Type')</th>
                                    <th>@lang('Applicant')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Agent')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $application->application_id ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($application->loan_type == 'personal_loan')
                                                <span class="badge badge--info">@lang('Personal')</span>
                                            @elseif($application->loan_type == 'microcredit')
                                                <span class="badge badge--success">@lang('Microcredit')</span>
                                            @elseif($application->loan_type == 'leasing')
                                                <span class="badge badge--warning">@lang('Leasing')</span>
                                            @elseif($application->loan_type == 'salary_secured')
                                                <span class="badge badge--primary">@lang('Salary')</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('N/A')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $application->first_name }} {{ $application->last_name }}</span>
                                            @if($application->user)
                                                <br>
                                                <small class="text-muted">
                                                    <a href="{{ route('admin.users.detail', $application->user_id) }}">{{ $application->user->username }}</a>
                                                </small>
                                            @else
                                                <br>
                                                <small class="text-muted">Guest</small>
                                            @endif
                                        </td>
                                        <td>{{ $application->email }}</td>
                                        <td>{{ $application->mobile }}</td>
                                        <td>
                                            @if($application->agent)
                                                <span class="badge badge--success">{{ $application->agent->username }}</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('Unassigned')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->status == 'pending')
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($application->status == 'processing')
                                                <span class="badge badge--info">@lang('Processing')</span>
                                            @elseif($application->status == 'approved')
                                                <span class="badge badge--success">@lang('Approved')</span>
                                            @elseif($application->status == 'rejected')
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($application->created_at) }}<br>
                                            <small>{{ diffForHumans($application->created_at) }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.personal.loans.details', $application->id) }}" 
                                               class="btn btn-sm btn-outline--primary">
                                                <i class="las la-eye"></i> @lang('View')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($applications->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($applications) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by name, email, mobile, tax code" />
@endpush

