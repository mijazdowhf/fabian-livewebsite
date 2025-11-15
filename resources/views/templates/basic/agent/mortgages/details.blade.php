@extends('agent.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-12">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Home Mortgage Application Details')</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>@lang('Name'):</strong> {{ $mortgage->first_name }} {{ $mortgage->last_name }}</p>
                        <p><strong>@lang('Email'):</strong> {{ $mortgage->email }}</p>
                        <p><strong>@lang('Mobile'):</strong> {{ $mortgage->mobile }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang('Status'):</strong>
                            @if($mortgage->status == 'pending')
                                <span class="badge badge--warning">@lang('Pending')</span>
                            @elseif($mortgage->status == 'approved')
                                <span class="badge badge--success">@lang('Approved')</span>
                            @endif
                        </p>
                        <p><strong>@lang('Submitted'):</strong> {{ showDateTime($mortgage->created_at) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

