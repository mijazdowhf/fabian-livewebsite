@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5>@lang('Stripe Hosted')</h5>
                    </div>
                    <div class="card-body">
                        <!-- Testing Information -->
                        <div class="alert alert-success mb-4" style="border-left: 4px solid #28a745;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-check-circle"></i> @lang('Test Cards - Ready to Use!')
                            </h6>
                            <p class="mb-2 small">
                                <strong>@lang('These test cards work instantly without any Stripe configuration'):</strong>
                            </p>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <div class="p-3" style="background: #f8f9fa; border-radius: 6px; border: 2px solid #28a745;">
                                        <strong class="text-success">✅ @lang('Success'):</strong><br>
                                        <code style="font-size: 1.1em;">4242 4242 4242 4242</code>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3" style="background: #f8f9fa; border-radius: 6px; border: 2px solid #dc3545;">
                                        <strong class="text-danger">❌ @lang('Declined'):</strong><br>
                                        <code style="font-size: 1.1em;">4000 0000 0000 0002</code>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2" style="background: #e7f5ec; border-radius: 6px;">
                                <small class="text-dark">
                                    <strong><i class="las la-lightbulb"></i> @lang('How to test'):</strong><br>
                                    • @lang('Expiry'): 12/31 (any future date)<br>
                                    • @lang('CVC'): 123 (any 3 digits)<br>
                                    • @lang('Name'): Test User (any name)<br>
                                    <em class="text-success">@lang('No Stripe configuration needed - works immediately!')  🎉</em>
                                </small>
                            </div>
                        </div>
                        
                        <!-- Real Cards Notice -->
                        <div class="alert alert-info mb-4" style="border-left: 4px solid #17a2b8;">
                            <h6 class="fw-bold mb-2">
                                <i class="las la-credit-card"></i> @lang('Real Card Payments')
                            </h6>
                            <p class="small mb-2">
                                @lang('To accept real credit cards (not test cards), enable "Raw card data API" in your Stripe account'):
                            </p>
                            <ol class="small mb-0 ps-3">
                                <li>@lang('Visit') <a href="https://dashboard.stripe.com/settings/integration" target="_blank">Stripe Integration Settings</a></li>
                                <li>@lang('Find "Card payments" section')</li>
                                <li>@lang('Enable "Raw card data API"')</li>
                                <li>@lang('Complete verification if required')</li>
                            </ol>
                            <small class="text-muted mt-2 d-block">
                                <i class="las la-info-circle"></i> @lang('Test cards listed above work without this configuration')
                            </small>
                        </div>
                        
                        <div class="card-wrapper mb-3"></div>
                        
                        <form role="form" class="disableSubmission payment appPayment" id="payment-form" method="{{ $data->method }}" action="{{ $data->url }}">
                            @csrf
                            <input type="hidden" value="{{ $data->track }}" name="track">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('Name on Card')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form--control" id="card-name" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus />
                                        <span class="input-group-text"><i class="fa fa-font"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">@lang('Card Number')</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control form--control" id="card-number" name="cardNumber" autocomplete="off" value="{{ old('cardNumber') }}" required autofocus />
                                        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label class="form-label">@lang('Expiration Date')</label>
                                    <input type="tel" class="form-control form--control" id="card-expiry" name="cardExpiry" value="{{ old('cardExpiry') }}" placeholder="MM / YY" autocomplete="off" required />
                                </div>
                                <div class="col-md-6 ">
                                    <label class="form-label">@lang('CVC Code')</label>
                                    <input type="tel" class="form-control form--control" id="card-cvc" name="cardCVC" value="{{ old('cardCVC') }}" autocomplete="off" required />
                                </div>
                            </div>
                            <br>
                            <button class="btn btn--base w-100" type="submit" id="submit-button"> 
                                @lang('Submit')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            
            // Initialize card display
            var card = new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });

            @if ($deposit->from_api)
                $('.appPayment').on('submit', function() {
                    $(this).find('[type=submit]').html('<i class="las la-spinner fa-spin"></i> @lang("Processing...")');
                })
            @endif

        })(jQuery);
    </script>
@endpush
