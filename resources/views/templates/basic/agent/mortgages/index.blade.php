@extends('agent.layouts.app')

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
                                <th>@lang('Email')</th>
                                <th>@lang('Mobile')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mortgages as $mortgage)
                                <tr>
                                    <td><span class="fw-bold">#{{ $mortgage->id }}</span></td>
                                    <td>
                                        <span class="fw-bold">{{ $mortgage->first_name }} {{ $mortgage->last_name }}</span>
                                        @if($mortgage->user)
                                            <br><small class="text-muted">{{ $mortgage->user->username }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $mortgage->email }}</td>
                                    <td>{{ $mortgage->mobile }}</td>
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

