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
                                    <th>@lang('ID')</th>
                                    <th>@lang('Applicant')</th>
                                    <th>@lang('Email/Mobile')</th>
                                    <th>@lang('Age')</th>
                                    <th>@lang('Employer')</th>
                                    <th>@lang('Income')</th>
                                    <th>@lang('Loan Amount')</th>
                                    <th>@lang('Purpose')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $app)
                                    <tr>
                                        <td><span class="fw-bold">#{{ $app->id }}</span></td>
                                        <td><span class="fw-bold">{{ $app->first_name }} {{ $app->last_name }}</span>@if($app->user)<br><small class="text-muted"><a href="{{ route('admin.users.detail', $app->user_id) }}">{{ $app->user->username }}</a></small>@endif</td>
                                        <td>{{ $app->email }}<br><small>{{ $app->mobile }}</small></td>
                                        <td>{{ $app->age }}</td>
                                        <td>{{ $app->employer_name }}</td>
                                        <td><span class="fw-bold">€{{ showAmount($app->monthly_net_income) }}</span></td>
                                        <td><span class="fw-bold text-success">€{{ showAmount($app->loan_amount) }}</span></td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $app->loan_purpose)) }}</td>
                                        <td>
                                            @if($app->status == 'pending')<span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($app->status == 'processing')<span class="badge badge--info">@lang('Processing')</span>
                                            @elseif($app->status == 'approved')<span class="badge badge--success">@lang('Approved')</span>
                                            @else<span class="badge badge--danger">@lang('Rejected')</span>@endif
                                        </td>
                                        <td>{{ showDateTime($app->created_at) }}<br><small>{{ diffForHumans($app->created_at) }}</small></td>
                                        <td><a href="{{ route('admin.employee.loans.details', $app->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-eye"></i> @lang('View')</a></td>
                                    </tr>
                                @empty
                                    <tr><td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($applications->hasPages())<div class="card-footer py-4">{{ paginateLinks($applications) }}</div>@endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')<x-search-form placeholder="Search..." />@endpush

