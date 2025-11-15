@extends('agent.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <div class="p-3 pb-0 mb-3">
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
                <div class="table-responsive--md table-responsive mt-3">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>@lang('Application ID')</th>
                                <th>@lang('User Code')</th>
                                <th>@lang('Business Name')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mortgages as $mortgage)
                                <tr>
                                    <td><span class="fw-bold text-primary">{{ $mortgage->application_id ?? 'N/A' }}</span></td>
                                    <td>
                                        @if($mortgage->user)
                                            <span class="fw-bold">{{ $mortgage->user->username }}</span>
                                        @else
                                            <span class="text-muted">@lang('Guest')</span>
                                        @endif
                                    </td>
                                    <td>{{ $mortgage->business_name }}</td>
                                    <td>{{ $mortgage->city }}</td>
                                    <td>
                                        @if($mortgage->status == 'pending')
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                        @elseif($mortgage->status == 'approved')
                                            <span class="badge badge--success">@lang('Approved')</span>
                                        @elseif($mortgage->status == 'rejected')
                                            <span class="badge badge--danger">@lang('Rejected')</span>
                                        @endif
                                    </td>
                                    <td>{{ showDateTime($mortgage->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('agent.mortgages.details', $mortgage->id) }}" class="btn btn-sm btn-outline--primary">
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
            @if ($mortgages->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($mortgages) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

