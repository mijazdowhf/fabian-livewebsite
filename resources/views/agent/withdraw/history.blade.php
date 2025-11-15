@extends('agent.layouts.app')

@section('panel')
<div class="row">
    <div class="col-12">
        <div class="card box-shadow3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="card-title mb-1">@lang('Withdrawal History')</h5>
                        <p class="text-muted small mb-0">@lang('View all your withdrawal requests')</p>
                    </div>
                    <a href="{{ route('agent.withdraw') }}" class="btn btn--primary">
                        <i class="las la-plus"></i> @lang('New Withdrawal')
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('Transaction ID')</th>
                                <th>@lang('Method')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Receivable')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $withdrawal->trx }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ getImage(getFilePath('withdrawMethod') .'/'. $withdrawal->method->image, getFileSize('withdrawMethod')) }}" 
                                                 alt="{{ $withdrawal->method->name }}" 
                                                 class="me-2" 
                                                 style="width: 30px; height: 30px; object-fit: contain;">
                                            <span>{{ __($withdrawal->method->name) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ showAmount($withdrawal->amount) }} {{ gs('cur_text') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-danger">{{ showAmount($withdrawal->charge) }} {{ gs('cur_text') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ showAmount($withdrawal->after_charge) }} {{ gs('cur_text') }}</span>
                                    </td>
                                    <td>
                                        @if($withdrawal->status == Status::PAYMENT_PENDING)
                                            <span class="badge bg-warning">@lang('Pending')</span>
                                        @elseif($withdrawal->status == Status::PAYMENT_SUCCESS)
                                            <span class="badge bg-success">@lang('Approved')</span>
                                        @elseif($withdrawal->status == Status::PAYMENT_REJECT)
                                            <span class="badge bg-danger">@lang('Rejected')</span>
                                        @else
                                            <span class="badge bg-secondary">@lang('Initiated')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ showDateTime($withdrawal->created_at) }}</div>
                                        <small class="text-muted">{{ diffForHumans($withdrawal->created_at) }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="las la-inbox text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mb-0">@lang('No withdrawal history found')</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($withdrawals->hasPages())
                    <div class="mt-4">
                        {{ paginateLinks($withdrawals) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    .hover-effect {
        transition: all 0.3s ease;
    }
    .hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }
</style>
@endpush
@endsection

