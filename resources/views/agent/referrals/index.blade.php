@extends('agent.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4 text-dark">
                        <i class="las la-gift"></i> @lang('My Referrals')
                    </h4>

                    <!-- Referral Link -->
                    <div class="alert alert-primary" style="border-radius: 10px;">
                        <h6 class="fw-bold mb-3">
                            <i class="las la-link"></i> @lang('Your Referral Link')
                        </h6>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="referralLink" value="{{ $referralLink }}" readonly>
                            <button class="btn btn-primary" type="button" onclick="copyReferralLink()">
                                <i class="las la-copy"></i> @lang('Copy')
                            </button>
                        </div>
                        <small class="text-muted">
                            @lang('Share this link to refer new agents. You earn 10% commission when they purchase a package.')
                        </small>
                    </div>

                    <!-- Commission Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-2">${{ showAmount($agent->commission_balance) }}</h3>
                                    <p class="mb-0">@lang('Commission Balance')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-2">${{ showAmount($agent->total_commission_earned) }}</h3>
                                    <p class="mb-0">@lang('Total Earned')</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-2">{{ $totalReferrals }}</h3>
                                    <p class="mb-0">@lang('Total Referrals')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referred Agents -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="las la-users"></i> @lang('Referred Agents')
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Agent Name')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Package Status')</th>
                                    <th>@lang('Commission Earned')</th>
                                    <th>@lang('Joined Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrals as $referral)
                                    <tr>
                                        <td>{{ $referral->firstname }} {{ $referral->lastname }}</td>
                                        <td>{{ $referral->username }}</td>
                                        <td>{{ $referral->email }}</td>
                                        <td>
                                            @if($referral->paid_package_agent)
                                                <span class="badge badge--success">@lang('Purchased')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($referral->paid_package_agent)
                                                <strong class="text-success">10%</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $referral->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted">@lang('No referrals yet')</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($referrals->hasPages())
                    <div class="card-footer">
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
        navigator.clipboard.writeText(linkInput.value).then(function() {
            const btn = event.target.closest('button');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="las la-check"></i> @lang("Copied!")';
            setTimeout(function() {
                btn.innerHTML = originalHtml;
            }, 2000);
        });
    }
</script>
@endpush
