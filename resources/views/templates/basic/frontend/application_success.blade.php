@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="container section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($accountData)
                <!-- Success Card with Login Credentials -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="success-icon mb-3">
                                <i class="las la-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h2 class="text-success mb-2">@lang('Application Submitted Successfully!')</h2>
                            <p class="text-muted">@lang('Your loan application has been received and an account has been created for you.')</p>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="las la-info-circle"></i>
                            <strong>@lang('Your Account Has Been Created')</strong>
                            <p class="mb-0 mt-2">@lang('Use these credentials to login and track your application status.')</p>
                        </div>

                        <div class="login-credentials-box bg-light p-4 rounded mb-4">
                            <h5 class="mb-3 text-center">
                                <i class="las la-key text-primary"></i> @lang('Your Login Credentials')
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="credential-item p-3 bg-white rounded">
                                        <label class="small text-muted mb-1">@lang('Email Address')</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-dark">{{ $accountData['email'] }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyText('{{ $accountData['email'] }}', 'Email')">
                                                <i class="las la-copy"></i> @lang('Copy')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="credential-item p-3 bg-white rounded">
                                        <label class="small text-muted mb-1">@lang('Username')</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-dark">{{ $accountData['username'] }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyText('{{ $accountData['username'] }}', 'Username')">
                                                <i class="las la-copy"></i> @lang('Copy')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="credential-item p-3 bg-white rounded border border-warning">
                                        <label class="small text-muted mb-1">@lang('Temporary Password')</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-danger fs-5">{{ $accountData['password'] }}</strong>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="copyText('{{ $accountData['password'] }}', 'Password')">
                                                <i class="las la-copy"></i> @lang('Copy')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning d-flex align-items-start">
                            <i class="las la-exclamation-triangle me-2" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>@lang('Important Security Notice:')</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    <li>@lang('Please save these credentials securely')</li>
                                    <li>@lang('Login and change your password immediately')</li>
                                    <li>@lang('This page will only be shown once')</li>
                                    <li>@lang('An email has also been sent to your email address')</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('user.login') }}" class="btn btn-primary btn-lg">
                                <i class="las la-sign-in-alt"></i> @lang('Login to Your Dashboard')
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="las la-home"></i> @lang('Go to Homepage')
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                <i class="las la-info-circle"></i> @lang('You can now login anytime to check your application status and manage your account.')
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- General Success Message -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5 text-center">
                        <div class="success-icon mb-3">
                            <i class="las la-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="text-success mb-2">@lang('Application Submitted!')</h2>
                        <p class="text-muted mb-4">@lang('Your application has been submitted successfully. We will contact you soon.')</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="las la-home"></i> @lang('Go to Homepage')
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
    function copyText(text, label) {
        navigator.clipboard.writeText(text).then(() => {
            if (window.iziToast) {
                iziToast.success({
                    message: label + ' copied to clipboard!',
                    position: 'topRight'
                });
            } else {
                alert(label + ' copied!');
            }
        });
    }
</script>
@endpush

@push('style')
<style>
    .success-icon {
        animation: scaleIn 0.5s ease;
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }
    
    .credential-item {
        transition: all 0.3s ease;
    }
    
    .credential-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection

