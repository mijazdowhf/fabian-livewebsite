@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <!-- Page Header -->
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Choose the type of loan you want to apply for')</p>
        </div>

        <!-- Loan Type Cards -->
        <div class="row justify-content-center g-4">
            <!-- Loans Card -->
            <div class="col-md-6 col-lg-5">
                <div class="loan-type-card">
                    <div class="loan-type-icon">
                        <i class="las la-hand-holding-usd"></i>
                    </div>
                    <h3 class="loan-type-title">@lang('Loans')</h3>
                    <p class="loan-type-description">
                        @lang('Various loan types: Personal, Microcredit, Leasing, Salary-secured')
                    </p>
                    <ul class="loan-type-features">
                        <li><i class="las la-check-circle"></i> @lang('Multiple loan options')</li>
                        <li><i class="las la-check-circle"></i> @lang('Flexible purposes')</li>
                        <li><i class="las la-check-circle"></i> @lang('Quick approval process')</li>
                        <li><i class="las la-check-circle"></i> @lang('Simple documentation')</li>
                    </ul>
                    <a href="{{ route('lead.wizard') }}" class="loan-type-btn btn-primary">
                        <span>@lang('Apply for Loan')</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Mortgage Card -->
            <div class="col-md-6 col-lg-5">
                <div class="loan-type-card featured">
                    <div class="featured-badge">@lang('Best Rate')</div>
                    <div class="loan-type-icon">
                        <i class="las la-home"></i>
                    </div>
                    <h3 class="loan-type-title">@lang('Home Mortgage')</h3>
                    <p class="loan-type-description">
                        @lang('Dedicated mortgage for home purchase and property financing.')
                    </p>
                    <ul class="loan-type-features">
                        <li><i class="las la-check-circle"></i> @lang('Large loan amounts')</li>
                        <li><i class="las la-check-circle"></i> @lang('Long repayment terms')</li>
                        <li><i class="las la-check-circle"></i> @lang('Competitive rates')</li>
                        <li><i class="las la-check-circle"></i> @lang('For property purchase')</li>
                    </ul>
                    <a href="{{ route('selling.mortgage.wizard') }}" class="loan-type-btn btn-featured">
                        <span>@lang('Apply for Mortgage')</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Text -->
        <div class="text-center mt-5">
            <p class="text-muted">
                <i class="las la-info-circle"></i>
                @lang('Not sure which loan is right for you?') 
                <a href="{{ route('contact') }}" class="text-primary">@lang('Contact us for assistance')</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .loan-application-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 60px 0;
    }
    
    .loan-app-header {
        margin-bottom: 4rem;
    }
    
    .loan-app-title {
        font-size: 2.75rem;
        font-weight: 700;
        color: hsl(var(--dark));
        margin-bottom: 1rem;
    }
    
    .loan-app-subtitle {
        font-size: 1.25rem;
        color: hsl(var(--dark) / 0.7);
        font-weight: 400;
    }
    
    .loan-type-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 3rem 2.5rem;
        border: 2px solid #e5e7eb;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .loan-type-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        border-color: hsl(var(--base));
    }
    
    .loan-type-card.featured {
        border-color: hsl(var(--base));
        background: linear-gradient(135deg, #ffffff 0%, hsl(var(--base) / 0.02) 100%);
    }
    
    .featured-badge {
        position: absolute;
        top: -12px;
        right: 30px;
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.8));
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 4px 15px hsl(var(--base) / 0.3);
    }
    
    .loan-type-icon {
        width: 90px;
        height: 90px;
        border-radius: 20px;
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.8));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 2rem;
        box-shadow: 0 8px 25px hsl(var(--base) / 0.3);
    }
    
    .loan-type-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: hsl(var(--dark));
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .loan-type-description {
        font-size: 1rem;
        color: hsl(var(--dark) / 0.7);
        text-align: center;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .loan-type-features {
        list-style: none;
        padding: 0;
        margin-bottom: 2.5rem;
        flex-grow: 1;
    }
    
    .loan-type-features li {
        padding: 0.75rem 0;
        font-size: 0.9375rem;
        color: #333333;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .loan-type-features li i {
        font-size: 1.25rem;
        color: #28a745;
    }
    
    .loan-type-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.125rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
    }
    
    .loan-type-btn.btn-primary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .loan-type-btn.btn-primary:hover {
        background: linear-gradient(135deg, #5a6268, #4e555b);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
    }
    
    .loan-type-btn.btn-featured {
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.85));
        color: white;
        box-shadow: 0 8px 25px hsl(var(--base) / 0.3);
    }
    
    .loan-type-btn.btn-featured:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px hsl(var(--base) / 0.4);
    }
    
    .loan-type-btn i {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }
    
    .loan-type-btn:hover i {
        transform: translateX(5px);
    }
    
    @media (max-width: 768px) {
        .loan-app-title {
            font-size: 2rem;
        }
        
        .loan-type-card {
            padding: 2rem 1.5rem;
        }
    }
</style>
@endpush

