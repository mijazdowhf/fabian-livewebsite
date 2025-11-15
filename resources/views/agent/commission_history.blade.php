@extends('agent.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 12px;">
                        <div class="card-body p-4 text-center">
                            <i class="las la-wallet text-white mb-2" style="font-size: 48px; opacity: 0.8;"></i>
                            <h3 class="text-white fw-bold mb-1">{{ showAmount($agent->balance ?? 0) }}</h3>
                            <p class="text-white-50 mb-0">@lang('Current Balance')</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); border-radius: 12px;">
                        <div class="card-body p-4 text-center">
                            <i class="las la-money-bill-wave text-white mb-2" style="font-size: 48px; opacity: 0.8;"></i>
                            <h3 class="text-white fw-bold mb-1">{{ showAmount($agent->total_commission_earned ?? 0) }}</h3>
                            <p class="text-white-50 mb-0">@lang('Total Commissions')</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%); border-radius: 12px;">
                        <div class="card-body p-4 text-center">
                            <i class="las la-history text-white mb-2" style="font-size: 48px; opacity: 0.8;"></i>
                            <h3 class="text-white fw-bold mb-1">{{ $transactions->total() }}</h3>
                            <p class="text-white-50 mb-0">@lang('Total Transactions')</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stripe Connection Status -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; background: {{ $agent->stripe_connected ? '#28a745' : '#ffc107' }};">
                                <i class="lab la-stripe text-white" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">@lang('Stripe Account')</h6>
                                @if($agent->stripe_connected)
                                    <p class="mb-0 text-muted">
                                        <i class="las la-check-circle text-success"></i> @lang('Connected') - {{ $agent->stripe_email }}
                                    </p>
                                @else
                                    <p class="mb-0 text-muted">
                                        <i class="las la-times-circle text-warning"></i> @lang('Not Connected')
                                    </p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('agent.stripe.settings') }}" class="btn btn-primary">
                            <i class="las la-cog"></i> @lang('Manage Stripe')
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="las la-receipt text-primary"></i> @lang('Commission Transaction History')
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('Date & Time')</th>
                                    <th>@lang('Transaction ID')</th>
                                    <th>@lang('Details')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Balance After')</th>
                                    <th>@lang('Stripe Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong class="d-block">{{ $transaction->created_at->format('d M Y') }}</strong>
                                                <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary" style="border-radius: 8px; font-family: monospace;">
                                                {{ $transaction->trx }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $transaction->details }}</small>
                                        </td>
                                        <td>
                                            <strong class="text-success" style="font-size: 16px;">
                                                +{{ showAmount($transaction->amount) }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ showAmount($transaction->post_balance) }}</span>
                                        </td>
                                        <td>
                                            @if($agent->stripe_connected)
                                                <span class="badge badge--success">
                                                    <i class="las la-check"></i> @lang('Sent to Stripe')
                                                </span>
                                            @else
                                                <span class="badge badge--warning">
                                                    <i class="las la-clock"></i> @lang('In System Balance')
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="las la-receipt text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-3">@lang('No commission transactions yet')</p>
                                            <a href="{{ route('agent.referrals') }}" class="btn btn-primary mt-2">
                                                <i class="las la-gift"></i> @lang('View Referrals')
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ paginateLinks($transactions) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

