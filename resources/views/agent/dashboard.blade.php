@extends('agent.layouts.app')

@section('panel')
    <div class="row gy-4">
        <!-- Balance Card -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-gradient-primary text-white box-shadow3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-white-50 mb-1">@lang('Available Balance')</p>
                            <h2 class="text-white mb-0">{{ gs('cur_sym') }}{{ showAmount(auth()->user()->balance) }}</h2>
                        </div>
                        <div class="balance-icon">
                            <i class="las la-wallet" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('agent.withdraw') }}" class="btn btn-light btn-sm flex-fill">
                            <i class="las la-arrow-up"></i> @lang('Withdraw')
                        </a>
                        <a href="{{ route('agent.withdraw.history') }}" class="btn btn-outline-light btn-sm flex-fill">
                            <i class="las la-history"></i> @lang('History')
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Referrals Card -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-gradient-success text-white box-shadow3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">@lang('Total Referrals')</p>
                            <h2 class="text-white mb-0">{{ \App\Models\User::where('referred_by', auth()->id())->where('role', 'agent')->count() }}</h2>
                        </div>
                        <div>
                            <i class="las la-users" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <a href="{{ route('agent.referrals') }}" class="btn btn-light btn-sm mt-4 w-100">
                        <i class="las la-eye"></i> @lang('View Referrals')
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Assigned Applications Card -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-gradient-info text-white box-shadow3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">@lang('Assigned Applications')</p>
                            <h2 class="text-white mb-0">{{ \App\Models\LoanInquiry::where('agent_id', auth()->id())->count() + \App\Models\SellingMortgageApplication::where('agent_id', auth()->id())->count() }}</h2>
                        </div>
                        <div>
                            <i class="las la-file-invoice" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <a href="{{ route('agent.loans.index') }}" class="btn btn-light btn-sm mt-4 w-100">
                        <i class="las la-list"></i> @lang('View Applications')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-4">
        <div class="col-12">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-center">
                        <span>@lang('Referral Links')</span>
                    </h5>

                    @php
                        $username = auth()->user()->username ?? '';
                        $signupReferral = route('user.register') . '?ref=' . $username;
                    @endphp

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">@lang('Your Referral Link')</label>
                            <div class="input-group">
                                <input id="agent-ref-signup" type="text" class="form-control" readonly value="{{ $signupReferral }}">
                                <button class="btn btn--base" type="button" data-copy="#agent-ref-signup">@lang('Copy')</button>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="las la-user"></i> @lang('Your Username'): <strong>{{ $username }}</strong> | 
                                @lang('Share this link to refer new agents and earn commission')
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('script')
<script>
    (function(){
        document.querySelectorAll('[data-copy]')?.forEach(function(btn){
            btn.addEventListener('click', function(){
                var sel = this.getAttribute('data-copy');
                var input = document.querySelector(sel);
                if(!input) return;
                input.select();
                input.setSelectionRange(0, 99999);
                try {
                    navigator.clipboard.writeText(input.value);
                } catch (e) {
                    document.execCommand('copy');
                }
                if (window.iziToast) {
                    iziToast.success({title: 'Copied', message: 'Referral link copied to clipboard', position: 'topRight'});
                }
            });
        });
    })();
    </script>
@endpush
@endsection


