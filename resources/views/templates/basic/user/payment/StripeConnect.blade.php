@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5>@lang('Complete Payment')</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($data->error) && $data->error)
                            <div class="alert alert-danger">
                                <strong>@lang('Error'):</strong> {{ $data->message ?? 'Payment processing error' }}
                            </div>
                        @else
                            <div id="payment-element">
                                <!-- Stripe Elements will create form elements here -->
                            </div>
                            
                            <form id="payment-form">
                                @csrf
                                <input type="hidden" name="track" value="{{ $data->track }}">
                                
                                <div id="card-element" class="mb-3 p-3" style="border: 1px solid #e0e0e0; border-radius: 4px;">
                                    <!-- Stripe Elements will create form elements here -->
                                </div>
                                
                                <div id="card-errors" role="alert" class="text-danger mb-3"></div>
                                
                                <button type="submit" id="submit-button" class="btn btn--base w-100">
                                    <span id="button-text">@lang('Pay Now')</span>
                                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @if(!isset($data->error) || !$data->error)
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function($) {
            "use strict";
            
            const stripe = Stripe('{{ $data->publishable_key }}');
            const clientSecret = '{{ $data->payment_intent_client_secret }}';
            
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                    invalid: {
                        color: '#9e2146',
                    },
                },
            });
            
            cardElement.mount('#card-element');
            
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            
            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const spinner = document.getElementById('spinner');
            
            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                
                submitButton.disabled = true;
                buttonText.textContent = '@lang("Processing...")';
                spinner.classList.remove('d-none');
                
                const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                    }
                });
                
                if (error) {
                    // Show error to customer
                    const displayError = document.getElementById('card-errors');
                    displayError.textContent = error.message;
                    submitButton.disabled = false;
                    buttonText.textContent = '@lang("Pay Now")';
                    spinner.classList.add('d-none');
                } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                    // Payment succeeded, redirect to IPN handler
                    window.location.href = '{{ $data->url }}?payment_intent=' + paymentIntent.id + '&payment_intent_client_secret={{ $data->payment_intent_client_secret }}';
                }
            });
            
        })(jQuery);
    </script>
    @endif
@endpush

