@extends('agent.layouts.app')
@section('panel')
    <!-- Referral Link Section -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%);">
                                    <i class="las la-gift text-white" style="font-size: 32px;"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">@lang('Your Referral Link')</h5>
                                    <p class="mb-0 text-muted">@lang('Earn') <span class="text-primary fw-bold">{{ $commissionRate }}%</span> @lang('commission on every referred agent package purchase')</p>
                                </div>
                            </div>
                            
                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <input type="text" class="form-control border-0 py-3" id="referralLink" value="{{ $referralLink }}" readonly style="background: #f8f9fa; font-size: 14px; color: #495057;">
                                <button class="btn btn-primary px-4" type="button" onclick="copyReferralLink()" style="border: none;">
                                    <i class="las la-copy me-1"></i> @lang('Copy Link')
                                </button>
                            </div>
                            
                            <div class="mt-3 p-3" style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #025AA3;">
                                <small class="text-muted">
                                    <i class="las la-user text-primary"></i> @lang('Your Referral Username'): 
                                    <strong class="text-dark ms-1">{{ $agent->username }}</strong>
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="p-3">
                                <div class="mb-2">
                                    <i class="las la-info-circle text-primary" style="font-size: 48px;"></i>
                                </div>
                                <h6 class="fw-bold mb-2">@lang('How It Works')</h6>
                                <small class="text-muted">
                                    @lang('Share your link with other agents. When they register and purchase a package, you earn') {{ $commissionRate }}% @lang('commission automatically!')
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Connection Alert -->
                    @if(!$agent->stripe_connected)
                        <div class="alert alert-warning mt-4" style="border-left: 4px solid #ffc107;">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="lab la-stripe" style="font-size: 32px; margin-right: 15px;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">@lang('Connect Your Stripe Account')</h6>
                                        <p class="mb-0 small">@lang('Receive referral commissions INSTANTLY via Stripe Connect')</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 mb-3" style="background: #fff3cd; border-radius: 6px;">
                                <strong class="small d-block mb-2"><i class="las la-bolt"></i> @lang('How Automatic Split Payment Works'):</strong>
                                <ul class="small mb-0 ps-3">
                                    <li>@lang('Your referral purchases a package (e.g., $100)')</li>
                                    <li>@lang('Stripe automatically splits the payment at checkout')</li>
                                    <li>@lang('Your Stripe account receives') {{ $commissionRate }}% (${{ $commissionRate }}) @lang('instantly')</li>
                                    <li>@lang('Platform receives') {{ 100 - $commissionRate }}% (${{ 100 - $commissionRate }}) @lang('automatically')</li>
                                    <li class="text-success">✅ @lang('No waiting, no withdrawals needed!')</li>
                                </ul>
                            </div>
                            <a href="{{ route('agent.stripe.settings') }}" class="btn btn-primary btn-sm">
                                <i class="las la-link"></i> @lang('Connect Stripe Account Now')
                            </a>
                        </div>
                    @else
                        <div class="alert alert-success mt-4" style="border-left: 4px solid #28a745;">
                            <div class="d-flex align-items-center">
                                <i class="las la-check-circle text-success" style="font-size: 32px; margin-right: 15px;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">✅ @lang('Stripe Account Connected!')</h6>
                                    <p class="mb-1 small">
                                        <strong>@lang('Email'):</strong> {{ $agent->stripe_email }}<br>
                                        <strong>@lang('Account ID'):</strong> <code>{{ $agent->stripe_account_id }}</code>
                                    </p>
                                    <small class="text-muted">
                                        <i class="las la-info-circle"></i> @lang('Your {{ commissionRate }}% referral commissions will be automatically transferred to this Stripe account when referrals purchase packages.', ['commissionRate' => $commissionRate])
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Test Mode Notice -->
                        <div class="alert alert-info mt-3" style="border-left: 4px solid #17a2b8;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-flask"></i> @lang('Test Mode Behavior')
                            </h6>
                            <p class="small mb-2">
                                @lang('During testing (test API keys), Stripe Connect is disabled and commissions are tracked internally in your balance.')
                                @lang('This allows you to test the full payment flow without needing test Stripe Connect accounts.')
                            </p>
                            <div class="p-2" style="background: #e7f3f8; border-radius: 6px;">
                                <small class="d-block mb-1"><strong>@lang('Test Mode'):</strong> @lang('Commission added to your internal balance')</small>
                                <small class="d-block"><strong>@lang('Live Mode'):</strong> @lang('Commission automatically transferred to your Stripe account') ({{ $agent->stripe_account_id }})</small>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="las la-lightbulb"></i> @lang('When you switch to live mode with live API keys, Stripe Connect will activate automatically!')
                            </small>
                        </div>
                    @endif

                    <!-- Commission Stats -->
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 12px;">
                                <div class="card-body text-center p-4">
                                    <i class="las la-wallet text-white mb-2" style="font-size: 36px; opacity: 0.8;"></i>
                                    <h3 class="text-white fw-bold mb-1">{{ showAmount($agent->balance ?? 0) }}</h3>
                                    <p class="text-white-50 mb-0 small">@lang('Available Balance')</p>
                                    <a href="{{ route('agent.withdraw') }}" class="btn btn-light btn-sm mt-2">
                                        <i class="las la-arrow-up"></i> @lang('Withdraw')
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); border-radius: 12px;">
                                <div class="card-body text-center p-4">
                                    <i class="las la-money-bill-wave text-white mb-2" style="font-size: 36px; opacity: 0.8;"></i>
                                    <h3 class="text-white fw-bold mb-1">{{ showAmount($agent->total_commission_earned ?? 0) }}</h3>
                                    <p class="text-white-50 mb-0 small">@lang('Total Earned')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #CA19A8 0%, #de3dbf 100%); border-radius: 12px;">
                                <div class="card-body text-center p-4">
                                    <i class="las la-users text-white mb-2" style="font-size: 36px; opacity: 0.8;"></i>
                                    <h3 class="text-white fw-bold mb-1">{{ $referrals->total() }}</h3>
                                    <p class="text-white-50 mb-0 small">@lang('Referred Agents')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referred Agents Table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="las la-users text-primary"></i> @lang('Referred Agents')
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Full Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Package Status')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Joined At')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $refer)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $refer->username }}</span>
                                        </td>
                                        <td>{{ $refer->fullname }}</td>
                                        <td>{{ $refer->email }}</td>
                                        <td>
                                            @if($refer->paid_package_agent)
                                                <span class="badge badge--success">@lang('Purchased')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($refer->paid_package_agent)
                                                <span class="text-success fw-bold">{{ $commissionRate }}% @lang('Earned')</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ showDateTime($refer->created_at) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">
                                            <div class="py-4">
                                                <i class="las la-users" style="font-size: 48px; opacity: 0.3;"></i>
                                                <p class="mt-2">@lang('No referrals yet. Start sharing your referral link!')</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($referrals->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($referrals) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function copyReferralLink() {
        const linkInput = document.getElementById('referralLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        
        navigator.clipboard.writeText(linkInput.value).then(function() {
            iziToast.success({
                title: '@lang("Copied!")',
                message: '@lang("Referral link copied to clipboard")',
                position: 'topRight'
            });
        }).catch(function(err) {
            // Fallback for older browsers
            document.execCommand('copy');
            iziToast.success({
                title: '@lang("Copied!")',
                message: '@lang("Referral link copied to clipboard")',
                position: 'topRight'
            });
        });
    }
</script>
@endpush
