@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="card-title font-weight-bold">@lang('Stripe Connect Settings')</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fa fa-info-circle"></i> @lang('Stripe Connect Setup')</h5>
                                <p>@lang('To enable split payments with referral commissions, you need to connect your Stripe account.')</p>
                                <ul>
                                    <li>@lang('Customer pays $100 to your platform account')</li>
                                    <li>@lang('10% ($10) automatically transfers to referrer\'s Stripe account')</li>
                                    <li>@lang('90% ($90) stays in your platform account')</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($isConnected && $adminStripeAccount)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <h5><i class="fa fa-check-circle"></i> @lang('Stripe Account Connected')</h5>
                                    <p><strong>@lang('Account ID'):</strong> {{ $adminStripeAccountId }}</p>
                                    <p><strong>@lang('Status'):</strong> 
                                        @if($adminStripeAccount->charges_enabled && $adminStripeAccount->details_submitted)
                                            <span class="badge bg-success">@lang('Active')</span>
                                        @else
                                            <span class="badge bg-warning">@lang('Pending Verification')</span>
                                        @endif
                                    </p>
                                    @if($adminStripeAccount->email)
                                        <p><strong>@lang('Email'):</strong> {{ $adminStripeAccount->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.stripe.connect.disconnect') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to disconnect your Stripe account?')">
                                <i class="fa fa-unlink"></i> @lang('Disconnect Stripe Account')
                            </button>
                        </form>
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fa fa-exclamation-triangle"></i> @lang('Stripe Account Not Connected')</h5>
                                    <p>@lang('Please connect your Stripe account to enable split payments.')</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.stripe.connect.connect') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="stripe_account_id">@lang('Stripe Account ID') <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="stripe_account_id" 
                                               name="stripe_account_id" 
                                               value="{{ old('stripe_account_id', $adminStripeAccountId) }}"
                                               placeholder="acct_xxxxxxxxxxxxx"
                                               required>
                                        <small class="form-text text-muted">
                                            @lang('Enter your Stripe account ID (starts with acct_). You can find this in your Stripe Dashboard under Settings > Account.')
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-link"></i> @lang('Connect Stripe Account')
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    @if(!$stripeClientId)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fa fa-exclamation-triangle"></i> @lang('Configuration Required')</h5>
                                    <p>@lang('Please add STRIPE_CONNECT_CLIENT_ID to your .env file for full OAuth functionality.')</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

