@extends('agent.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="lab la-stripe text-primary"></i> @lang('Stripe Account Settings')
                    </h5>
                    <p class="text-muted mb-0 mt-2">@lang('Connect your Stripe account to receive referral commissions directly')</p>
                </div>
                <div class="card-body p-4">
                    @if($agent->stripe_connected)
                        <div class="alert alert-success mb-4" style="border-left: 4px solid #28a745;">
                            <div class="d-flex align-items-center">
                                <i class="las la-check-circle" style="font-size: 32px; margin-right: 15px;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">@lang('Stripe Account Connected!')</h6>
                                    <p class="mb-0 small">@lang('Connected on'): {{ $agent->stripe_connected_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Restricted Account Notice -->
                        <div class="alert alert-info mb-4" style="border-left: 4px solid #17a2b8;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-info-circle"></i> @lang('Account Status: Restricted?')
                            </h6>
                            <p class="mb-2 small">
                                @lang('If your Stripe account shows "Restricted" status, this is NORMAL for test accounts.')
                                @lang('Restricted accounts can still receive payments in test mode.')
                            </p>
                            <div class="p-3 mb-2" style="background: #e7f3f8; border-radius: 6px;">
                                <strong class="small d-block mb-1">@lang('For TEST MODE'):</strong>
                                <ul class="small mb-0 ps-3">
                                    <li>✅ @lang('Restricted accounts work fine for testing')</li>
                                    <li>✅ @lang('You can receive test payments')</li>
                                    <li>✅ @lang('No verification needed for test mode')</li>
                                </ul>
                            </div>
                            <div class="p-3" style="background: #fff3cd; border-radius: 6px;">
                                <strong class="small d-block mb-1">@lang('For LIVE MODE (Production)'):</strong>
                                <ul class="small mb-0 ps-3">
                                    <li>⚠️ @lang('You will need to complete verification')</li>
                                    <li>⚠️ @lang('Provide business information')</li>
                                    <li>⚠️ @lang('Verify identity documents')</li>
                                </ul>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="las la-lightbulb"></i> @lang('To check account status, go to') 
                                <a href="https://dashboard.stripe.com/test/connect/accounts" target="_blank">Stripe Connect Dashboard</a>
                            </small>
                        </div>
                    @else
                        <div class="alert alert-warning mb-4" style="border-left: 4px solid #ffc107;">
                            <div class="d-flex align-items-center">
                                <i class="las la-exclamation-circle" style="font-size: 32px; margin-right: 15px;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">@lang('Stripe Account Not Connected')</h6>
                                    <p class="mb-0 small">@lang('Connect your Stripe account to receive referral commissions directly to your Stripe account')</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!$agent->stripe_account_id)
                        <!-- Create New Stripe Account -->
                        <div class="alert alert-info mb-4" style="border-left: 4px solid #17a2b8;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-magic"></i> @lang('Automatic Account Creation')
                            </h6>
                            <p class="mb-2 small">
                                @lang('We will automatically create a Stripe Connect account for you and guide you through the setup process.')
                                @lang('No need to manually create accounts or copy account IDs!')
                            </p>
                        </div>
                        
                        <form action="{{ route('agent.stripe.create.account') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="las la-envelope"></i> @lang('Your Email Address')
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           value="{{ old('email', $agent->email) }}" 
                                           placeholder="your-email@example.com" required>
                                    <small class="text-muted">
                                        <i class="las la-info-circle"></i> @lang('This email will be used for your Stripe Connect account')
                                    </small>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="las la-plus-circle"></i> @lang('Create Stripe Connect Account')
                                    </button>
                                </div>
                            </div>
                        </form>
                    @elseif(!$agent->stripe_connected)
                        <!-- Account Created but Onboarding Not Complete -->
                        <div class="alert alert-warning mb-4" style="border-left: 4px solid #ffc107;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-clock"></i> @lang('Onboarding In Progress')
                            </h6>
                            <p class="mb-2 small">
                                @lang('Your Stripe account has been created, but you need to complete the onboarding process.')
                            </p>
                            <p class="mb-2 small">
                                <strong>@lang('Account ID'):</strong> <code>{{ $agent->stripe_account_id }}</code>
                            </p>
                            <a href="{{ route('agent.stripe.onboarding.refresh') }}" class="btn btn-warning btn-lg w-100">
                                <i class="las la-external-link-alt"></i> @lang('Complete Stripe Onboarding')
                            </a>
                        </div>
                    @else
                        <!-- Account Connected - Show Info -->
                        <div class="alert alert-success mb-4" style="border-left: 4px solid #28a745;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-check-circle"></i> @lang('Account Connected Successfully!')
                            </h6>
                            <div class="p-3" style="background: #e7f5ec; border-radius: 6px;">
                                <p class="mb-1 small">
                                    <strong>@lang('Email'):</strong> {{ $agent->stripe_email }}<br>
                                    <strong>@lang('Account ID'):</strong> <code>{{ $agent->stripe_account_id }}</code><br>
                                    <strong>@lang('Connected on'):</strong> {{ $agent->stripe_connected_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <a href="{{ route('agent.stripe.onboarding.refresh') }}" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="las la-sync"></i> @lang('Update Account Details')
                            </a>
                        </div>
                    @endif

                    <!-- How to Create Test Connected Account -->
                    <div class="mt-5 p-4" style="background: #f8f9fa; border-radius: 10px;">
                        <h6 class="fw-bold mb-3">
                            <i class="las la-question-circle text-primary"></i> @lang('How to Create a Test Connected Account in Stripe')
                        </h6>
                        
                        <div class="alert alert-warning mb-3" style="border-left: 4px solid #ffc107;">
                            <strong><i class="las la-exclamation-triangle"></i> @lang('Important'):</strong>
                            <p class="mb-0 small mt-1">
                                @lang('You need to create a CONNECTED ACCOUNT (not your regular account) through Stripe Connect.')
                                @lang('This must be done by the ADMIN who has the Stripe account with API keys.')
                            </p>
                        </div>
                        
                        <h6 class="fw-bold mt-3 mb-2">
                            <i class="las la-list-ol"></i> @lang('Step-by-Step Guide (For Admin)')
                        </h6>
                        <ol class="mb-3 ps-3" style="line-height: 1.8;">
                            <li class="mb-2">
                                <strong>@lang('Go to Stripe Dashboard'):</strong><br>
                                <a href="https://dashboard.stripe.com/test/connect/accounts" target="_blank" class="btn btn-sm btn-primary mt-1">
                                    <i class="las la-external-link-alt"></i> Open Stripe Connect (Test Mode)
                                </a>
                                <small class="d-block text-muted mt-1">@lang('Make sure you are in TEST MODE (toggle in top right)')</small>
                            </li>
                            <li class="mb-2">
                                <strong>@lang('Click "Create account" button')</strong> (top right corner)
                            </li>
                            <li class="mb-2">
                                <strong>@lang('Choose account type'):</strong><br>
                                <span class="badge bg-primary">Express</span> @lang('(Recommended - easier setup)')<br>
                                <span class="badge bg-secondary">Standard</span> @lang('(More control, requires more info)')
                            </li>
                            <li class="mb-2">
                                <strong>@lang('Fill in account details'):</strong><br>
                                <ul class="mt-1 ps-3">
                                    <li>@lang('Email'): <code>ijazkhancrt126@gmail.com</code> (or agent's email)</li>
                                    <li>@lang('Country'): Select the agent's country</li>
                                    <li>@lang('Business type'): Individual or Company</li>
                                </ul>
                            </li>
                            <li class="mb-2">
                                <strong>@lang('Complete the setup'):</strong><br>
                                @lang('Follow the prompts to complete account creation')
                            </li>
                            <li class="mb-2">
                                <strong>@lang('Get the Connected Account ID'):</strong><br>
                                <ol class="mt-1 ps-3" style="list-style-type: lower-alpha;">
                                    <li>@lang('After creation, you\'ll see the account in the list')</li>
                                    <li>@lang('Click on the account to open it')</li>
                                    <li>@lang('The Account ID is shown at the top (starts with') <code>acct_</code>@lang(')')</li>
                                    <li>@lang('Copy this ID and give it to the agent')</li>
                                </ol>
                            </li>
                            <li>
                                <strong>@lang('Agent enters the ID'):</strong><br>
                                @lang('Agent should paste the connected account ID in the field above and save')
                            </li>
                        </ol>
                        
                        <div class="alert alert-success mt-4" style="border-left: 4px solid #28a745;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-check-circle"></i> @lang('Quick Reference')
                            </h6>
                            <ul class="mb-0 small">
                                <li>@lang('Test Mode Dashboard'): <a href="https://dashboard.stripe.com/test/connect/accounts" target="_blank">https://dashboard.stripe.com/test/connect/accounts</a></li>
                                <li>@lang('Connected Account ID format'): <code>acct_xxxxxxxxxxxxxxxx</code></li>
                                <li>@lang('Make sure you are in TEST MODE (not Live mode)')</li>
                                <li>@lang('The account ID will be different for test vs live mode')</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-info mt-3" style="border-left: 4px solid #17a2b8;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-lightbulb"></i> @lang('Alternative: Use Your Regular Account ID')
                            </h6>
                            <p class="mb-2 small">
                                @lang('If you cannot create a connected account, you can temporarily use your regular Stripe account ID:')
                            </p>
                            <ol class="mb-0 small ps-3">
                                <li>@lang('Go to') <a href="https://dashboard.stripe.com/test/settings/account" target="_blank">Stripe Account Settings</a></li>
                                <li>@lang('Find your Account ID at the top (starts with') <code>acct_</code>@lang(')')</li>
                                <li>@lang('Note: This may not work for Stripe Connect - connected accounts are preferred')</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-4 p-4" style="background: linear-gradient(135deg, #025AA3 0%, #0373cc 100%); border-radius: 10px; color: white;">
                        <h6 class="fw-bold mb-3">
                            <i class="las la-shield-alt"></i> @lang('How Commissions Work')
                        </h6>
                        <ul class="mb-0">
                            <li class="mb-2">@lang('When your referred agents purchase packages, you earn') <strong>{{ gs('referral_commission_rate') ?? 10 }}%</strong> @lang('commission')</li>
                            <li class="mb-2">@lang('Commissions are credited to both your system balance and your Stripe account')</li>
                            <li class="mb-2">@lang('You can withdraw your system balance anytime')</li>
                            <li>@lang('Stripe commissions appear in your Stripe dashboard automatically')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

