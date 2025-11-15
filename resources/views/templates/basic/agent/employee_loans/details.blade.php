@extends('agent.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-12">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Employee Loan Application Details')</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>@lang('Name'):</strong> {{ $loan->first_name }} {{ $loan->last_name }}</p>
                        <p><strong>@lang('Email'):</strong> {{ $loan->email }}</p>
                        <p><strong>@lang('Mobile'):</strong> {{ $loan->mobile }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang('Status'):</strong>
                            @if($loan->status == 'pending')
                                <span class="badge badge--warning">@lang('Pending')</span>
                            @elseif($loan->status == 'approved')
                                <span class="badge badge--success">@lang('Approved')</span>
                            @endif
                        </p>
                        <p><strong>@lang('Submitted'):</strong> {{ showDateTime($loan->created_at) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

