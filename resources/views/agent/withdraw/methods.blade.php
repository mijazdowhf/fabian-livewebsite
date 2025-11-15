@extends('agent.layouts.app')

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card box-shadow3">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <i class="las la-money-bill-wave text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="mb-2">@lang('Withdraw Funds')</h4>
                    <p class="text-muted">@lang('Choose your preferred withdrawal method')</p>
                </div>

                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="las la-info-circle me-2" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>@lang('Available Balance:')</strong> <span class="text-primary fw-bold">{{ showAmount(auth()->user()->balance) }} {{ gs('cur_text') }}</span>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($withdrawMethod as $method)
                        <div class="col-md-6">
                            <div class="withdraw-method-card h-100">
                                <div class="card border-0 shadow-sm h-100 hover-effect">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="method-icon me-3">
                                                <img src="{{ getImage(getFilePath('withdrawMethod') .'/'. $method->image,getFileSize('withdrawMethod')) }}" alt="{{ $method->name }}" class="img-fluid" style="max-width: 60px;">
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ __($method->name) }}</h5>
                                                <p class="text-muted small mb-0">@lang('Processing time:') 1-3 @lang('days')</p>
                                            </div>
                                        </div>
                                        
                                        <div class="method-details mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">@lang('Limit')</span>
                                                <span class="fw-bold">{{ showAmount($method->min_limit) }} - {{ showAmount($method->max_limit) }} {{ gs('cur_text') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">@lang('Charge')</span>
                                                <span class="fw-bold text-danger">{{ showAmount($method->fixed_charge) }} {{ gs('cur_text') }} + {{ showAmount($method->percent_charge) }}%</span>
                                            </div>
                                        </div>

                                        <form action="{{ route('agent.withdraw.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="method_code" value="{{ $method->id }}">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">@lang('Enter Amount')</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                                    <input type="number" step="any" name="amount" class="form-control form-control-lg" placeholder="0.00" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn--primary w-100">
                                                <i class="las la-arrow-right"></i> @lang('Proceed with') {{ $method->name }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="las la-frown text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted">@lang('No withdrawal method available')</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    .hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
@endsection

