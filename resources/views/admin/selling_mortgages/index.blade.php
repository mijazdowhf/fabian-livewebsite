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
                                    <th>@lang('Applicant')</th>
                                    <th>@lang('Business')</th>
                                    <th>@lang('VAT')</th>
                                    <th>@lang('Mortgage')</th>
                                    <th>@lang('Agent')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $app)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $app->application_id ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $app->first_name }} {{ $app->last_name }}</span>
                                            @if($app->user)
                                                <br>
                                                <small class="text-muted">
                                                    <a href="{{ route('admin.users.detail', $app->user_id) }}">{{ $app->user->username }}</a>
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $app->business_name }}</td>
                                        <td>{{ $app->vat_number }}</td>
                                        <td class="fw-bold text-primary">€{{ showAmount($app->mortgage_amount) }}</td>
                                        <td>
                                            @if($app->agent)
                                                <span class="badge badge--success">{{ $app->agent->username }}</span>
                                            @else
                                                <span class="badge badge--secondary">@lang('Unassigned')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($app->status == 'pending')
                                                <span class="badge badge--warning">Pending</span>
                                            @elseif($app->status == 'processing')
                                                <span class="badge badge--info">Processing</span>
                                            @elseif($app->status == 'approved')
                                                <span class="badge badge--success">Approved</span>
                                            @else
                                                <span class="badge badge--danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($app->created_at) }}<br>
                                            <small>{{ diffForHumans($app->created_at) }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.selling.mortgages.details', $app->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-eye"></i> View
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
    <x-search-form placeholder="Search..." />
@endpush
