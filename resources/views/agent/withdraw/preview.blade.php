@extends('agent.layouts.app')

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card box-shadow3">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <i class="las la-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="mb-2">@lang('Withdrawal Preview')</h4>
                    <p class="text-muted">@lang('Please review your withdrawal details')</p>
                </div>

                <div class="withdrawal-summary mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="summary-card bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">@lang('Method')</span>
                                    <span class="fw-bold">{{ __($withdraw->method->name) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-card bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">@lang('Amount')</span>
                                    <span class="fw-bold text-primary">{{ showAmount($withdraw->amount) }} {{ gs('cur_text') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-card bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">@lang('Charge')</span>
                                    <span class="fw-bold text-danger">{{ showAmount($withdraw->charge) }} {{ gs('cur_text') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-card bg-primary bg-opacity-10 p-3 rounded border border-primary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">@lang('You Will Get')</span>
                                    <span class="fw-bold text-success fs-5">{{ showAmount($withdraw->after_charge) }} {{ gs('cur_text') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('agent.withdraw.submit') }}" method="POST">
                    @csrf
                    
                    @if(@$withdraw->method->form)
                        <div class="mb-4">
                            <h6 class="mb-3">@lang('Required Information')</h6>
                            <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form->id }}" />
                        </div>
                    @endif

                    @if(auth()->user()->ts)
                        <div class="mb-4">
                            <label class="form-label fw-bold">@lang('Google Authenticator Code')</label>
                            <input type="text" name="authenticator_code" class="form-control form-control-lg" required>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('agent.withdraw') }}" class="btn btn-outline-secondary btn-lg flex-fill">
                            <i class="las la-arrow-left"></i> @lang('Back')
                        </a>
                        <button type="submit" class="btn btn--primary btn-lg flex-fill">
                            <i class="las la-check-circle"></i> @lang('Confirm Withdrawal')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

