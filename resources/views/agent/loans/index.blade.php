@extends('agent.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <div class="p-3 pb-0">
                        <form action="" method="GET">
                            <div class="d-flex flex-wrap gap-2">
                                <div class="flex-fill">
                                    <input type="text" name="search" class="form-control" value="{{ request()->search }}" placeholder="@lang('Search by Application ID or User Code')">
                                </div>
                                <div>
                                    <button class="btn btn--primary w-100 h-100" type="submit"><i class="fa fa-search"></i> @lang('Search')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>@lang('Application ID')</th>
                                <th>@lang('Loan Type')</th>
                                <th>@lang('Applicant Code')</th>
                                <th>@lang('Age')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $loan->application_id ?? 'N/A' }}</span></td>
                                    <td>
                                        @if($loan->loan_type == 'personal_loan')
                                            <span class="badge badge--info">@lang('Personal')</span>
                                        @elseif($loan->loan_type == 'microcredit')
                                            <span class="badge badge--success">@lang('Microcredit')</span>
                                        @elseif($loan->loan_type == 'leasing')
                                            <span class="badge badge--warning">@lang('Leasing')</span>
                                        @elseif($loan->loan_type == 'salary_secured')
                                            <span class="badge badge--primary">@lang('Salary')</span>
                                        @else
                                            <span class="badge badge--secondary">@lang('N/A')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($loan->user)
                                            <span class="fw-bold">{{ $loan->user->username }}</span>
                                        @else
                                            <span class="text-muted">@lang('Guest')</span>
                                        @endif
                                    </td>
                                    <td>{{ $loan->age ?? 'N/A' }}</td>
                                    <td>{{ $loan->city }}</td>
                                    <td>
                                        @if($loan->status == 'pending')
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                        @elseif($loan->status == 'processing')
                                            <span class="badge badge--info">@lang('Processing')</span>
                                        @elseif($loan->status == 'approved')
                                            <span class="badge badge--success">@lang('Approved')</span>
                                        @elseif($loan->status == 'rejected')
                                            <span class="badge badge--danger">@lang('Rejected')</span>
                                        @endif
                                    </td>
                                    <td>{{ showDateTime($loan->created_at) }}<br>{{ diffForHumans($loan->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('agent.loans.details', $loan->id) }}" class="btn btn-sm btn-outline--primary">
                                            <i class="la la-eye"></i> @lang('View')
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
            @if ($loans->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($loans) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

