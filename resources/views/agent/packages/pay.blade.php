@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="container section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <h4 class="mb-3">@lang('Package Payment')</h4>

                        @if(session('package_deposit.has_referrer'))
                            <div class="alert alert-info mb-3" style="border-left: 4px solid #025AA3;">
                                <h6 class="fw-bold mb-2">
                                    <i class="las la-users"></i> @lang('Referral Bonus Active')
                                </h6>
                                <p class="mb-1 small">
                                    @lang('Your referrer will automatically receive their commission when you complete this payment.')
                                    @lang('Payment is automatically split at Stripe level.')
                                </p>
                                <div class="mt-2 p-2" style="background: #f8f9fa; border-radius: 6px;">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small">@lang('Total Payment'):</span>
                                        <strong>${{ number_format(session('package_deposit.amount'), 2) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small text-muted">@lang('Platform receives'):</span>
                                        <span class="small text-muted">{{ 100 - (gs('referral_commission_rate') ?? 10) }}%</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-success">@lang('Referrer receives'):</span>
                                        <span class="small text-success">{{ gs('referral_commission_rate') ?? 10 }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger mb-3">
                                <h6 class="fw-bold mb-2">@lang('Please fix the following errors:')</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(!session('package_deposit'))
                            <div class="alert alert-warning mb-3">
                                <i class="las la-exclamation-triangle"></i> @lang('No package selected. Please select a package first.')
                                <a href="{{ route('agent.packages.choose') }}" class="btn btn-sm btn-primary ms-2">@lang('Choose Package')</a>
                            </div>
                        @endif

                        <form action="{{ route('agent.deposit.insert') }}" method="post" class="deposit-form" id="depositForm">
                            @csrf
                            <input type="hidden" name="currency" id="currencyInput">
                            @if(session('package_deposit.package_id'))
                                <input type="hidden" name="package_id" value="{{ session('package_deposit.package_id') }}">
                            @endif

                            <div class="row gy-4">
                                <div class="col-lg-6">
                                    <div class="payment-system-list is-scrollable gateway-option-list">
                                        @foreach ($gatewayCurrency as $data)
                                            <label for="{{ titleToKey($data->name) }}"
                                                class="payment-item @if ($loop->index > 4) d-none @endif gateway-option">
                                                <div class="payment-item__info">
                                                    <span class="payment-item__check"></span>
                                                    <span class="payment-item__name">{{ __($data->name) }}</span>
                                                </div>
                                                <div class="payment-item__thumb">
                                                    <img class="payment-item__thumb-img"
                                                        src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}"
                                                        alt="@lang('payment-thumb')">
                                                </div>
                                                <input class="payment-item__radio gateway-input" id="{{ titleToKey($data->name) }}" hidden
                                                    data-gateway='@json($data)' type="radio" name="gateway" value="{{ $data->method_code }}"
                                                    @if ($loop->first) checked @endif
                                                    data-min-amount="{{ showAmount($data->min_amount) }}"
                                                    data-max-amount="{{ showAmount($data->max_amount) }}">
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="payment-system-list p-3">
                                        <div class="deposit-info">
                                            <div class="deposit-info__title">
                                                <p class="text mb-0">@lang('Amount to Pay')</p>
                                            </div>
                                            <div class="deposit-info__input">
                                                <div class="deposit-info__input-group input-group">
                                                    <span class="deposit-info__input-group-text">{{ gs('cur_sym') }}</span>
                                                    <input type="text" class="form-control form--control amount" name="amount"
                                                        placeholder="@lang('00.00')"
                                                        value="{{ session('package_deposit.amount') ? showAmount(session('package_deposit.amount'), currencyFormat:false) : old('amount') }}"
                                                        @if(session('package_deposit.lock')) readonly @endif autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="deposit-info">
                                            <div class="deposit-info__title"><p class="text has-icon"> @lang('Limit') <span></span></p></div>
                                            <div class="deposit-info__input"><p class="text"><span class="gateway-limit">@lang('0.00')</span></p></div>
                                        </div>
                                        <div class="deposit-info">
                                            <div class="deposit-info__title"><p class="text has-icon">@lang('Processing Charge')</p></div>
                                            <div class="deposit-info__input"><p class="text"><span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</p></div>
                                        </div>
                                        <div class="deposit-info total-amount pt-3">
                                            <div class="deposit-info__title"><p class="text">@lang('Total')</p></div>
                                            <div class="deposit-info__input"><p class="text"><span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</p></div>
                                        </div>
                                        <button type="submit" class="btn btn--base w-100" disabled>@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
"use strict";
(function($){
    var amount = parseFloat($('.amount').val() || 0);
    var gateway, minAmount, maxAmount;
    
    $('.amount').on('input', function(){ 
        amount = parseFloat($(this).val()); 
        calculation(); 
    });
    
    $('.gateway-input').on('change', function(){ 
        gatewayChange(); 
    });
    
    function gatewayChange(){
        let gatewayElement = $('.gateway-input:checked');
        gateway = gatewayElement.data('gateway');
        minAmount = gatewayElement.data('min-amount');
        maxAmount = gatewayElement.data('max-amount');
        calculation();
    }
    
    gatewayChange();
    
    function calculation(){
        if(!gateway) return;
        $(".gateway-limit").text(minAmount + " - " + maxAmount);
        if(!amount) return;
        let percentCharge = parseFloat(gateway.percent_charge);
        let fixedCharge = parseFloat(gateway.fixed_charge);
        let totalPercentCharge = parseFloat(amount/100*percentCharge);
        let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
        let totalAmount = parseFloat((amount||0) + totalPercentCharge + fixedCharge);
        $(".final-amount").text(totalAmount.toFixed(2));
        $(".processing-fee").text(totalCharge.toFixed(2));
        $("input[name=currency]").val(gateway.currency);
        $("#currencyInput").val(gateway.currency);
        
        if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
            $(".deposit-form button[type=submit]").attr('disabled', true);
        } else {
            $(".deposit-form button[type=submit]").removeAttr('disabled');
        }
    }
    
    // Form submission validation
    $('.deposit-form').on('submit', function(e) {
        let currency = $("input[name=currency]").val();
        let selectedGateway = $('.gateway-input:checked').val();
        
        if (!currency || !selectedGateway) {
            e.preventDefault();
            if (window.iziToast) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please select a payment gateway',
                    position: 'topRight'
                });
            } else {
                alert('Please select a payment gateway');
            }
            return false;
        }
        
        if (!amount || amount <= 0) {
            e.preventDefault();
            if (window.iziToast) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please enter a valid amount',
                    position: 'topRight'
                });
            } else {
                alert('Please enter a valid amount');
            }
            return false;
        }
        
        // Show loading state
        $(this).find('button[type=submit]').prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Processing...');
    });
})(jQuery);
</script>
@endpush


