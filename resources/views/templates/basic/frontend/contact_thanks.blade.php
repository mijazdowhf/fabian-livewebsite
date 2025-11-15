@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container section py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body p-5 text-center">
                        <!-- Success Icon -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" 
                                 style="width: 100px; height: 100px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <i class="las la-check-circle text-white" style="font-size: 64px;"></i>
                            </div>
                        </div>

                        <!-- Thank You Message -->
                        <h2 class="fw-bold mb-3" style="color: #025AA3;">@lang('Thank You!')</h2>
                        <p class="text-muted mb-4" style="font-size: 18px;">
                            @lang('Your message has been successfully submitted.')
                        </p>

                        <!-- Info Card -->
                        <div class="alert" style="background: #f8f9fa; border-left: 4px solid #025AA3; border-radius: 10px;">
                            <div class="d-flex align-items-start text-start">
                                <i class="las la-info-circle text-primary me-3" style="font-size: 24px;"></i>
                                <div>
                                    <p class="mb-2 fw-bold">@lang('What happens next?')</p>
                                    <ul class="mb-0 ps-3">
                                        <li class="text-muted">@lang('Our team will review your message shortly')</li>
                                        <li class="text-muted">@lang('You will receive a response via email within 24-48 hours')</li>
                                        <li class="text-muted">@lang('You can track your ticket status in your dashboard')</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="row mt-4 mb-4">
                            <div class="col-4">
                                <div class="p-3" style="background: #f8f9fa; border-radius: 10px;">
                                    <i class="las la-clock text-primary" style="font-size: 32px;"></i>
                                    <p class="mb-0 mt-2 fw-bold" style="font-size: 14px;">24-48 Hours</p>
                                    <small class="text-muted">Response Time</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3" style="background: #f8f9fa; border-radius: 10px;">
                                    <i class="las la-envelope-open text-success" style="font-size: 32px;"></i>
                                    <p class="mb-0 mt-2 fw-bold" style="font-size: 14px;">Email</p>
                                    <small class="text-muted">Notification</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3" style="background: #f8f9fa; border-radius: 10px;">
                                    <i class="las la-headset text-info" style="font-size: 32px;"></i>
                                    <p class="mb-0 mt-2 fw-bold" style="font-size: 14px;">Support</p>
                                    <small class="text-muted">24/7 Available</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                            @auth
                                <a href="{{ route('ticket.index') }}" class="btn btn--base px-4" style="border-radius: 10px;">
                                    <i class="las la-ticket-alt me-1"></i> @lang('View My Tickets')
                                </a>
                                <a href="{{ route('user.home') }}" class="btn btn-outline-primary px-4" style="border-radius: 10px;">
                                    <i class="las la-home me-1"></i> @lang('Go to Dashboard')
                                </a>
                            @else
                                <a href="{{ route('home') }}" class="btn btn--base px-4" style="border-radius: 10px;">
                                    <i class="las la-home me-1"></i> @lang('Back to Home')
                                </a>
                                <a href="{{ route('contact') }}" class="btn btn-outline-primary px-4" style="border-radius: 10px;">
                                    <i class="las la-envelope me-1"></i> @lang('Contact Again')
                                </a>
                            @endauth
                        </div>

                        <!-- Additional Help -->
                        <div class="mt-5 pt-4 border-top">
                            <p class="text-muted mb-2">@lang('Need urgent assistance?')</p>
                            <div class="d-flex justify-content-center gap-4">
                                @php
                                    $contactContent = getContent('contact.content', true);
                                @endphp
                                @if(@$contactContent->data_values->phone)
                                    <a href="tel:{{ @$contactContent->data_values->phone }}" class="text-decoration-none">
                                        <i class="las la-phone-volume text-primary"></i>
                                        <small class="ms-1">{{ @$contactContent->data_values->phone }}</small>
                                    </a>
                                @endif
                                @if(@$contactContent->data_values->email)
                                    <a href="mailto:{{ @$contactContent->data_values->email }}" class="text-decoration-none">
                                        <i class="las la-envelope text-primary"></i>
                                        <small class="ms-1">{{ @$contactContent->data_values->email }}</small>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="row mt-4 text-center">
                    <div class="col-md-4 mb-3">
                        <i class="las la-shield-alt text-success" style="font-size: 36px;"></i>
                        <p class="mb-0 mt-2 fw-bold">@lang('Secure')</p>
                        <small class="text-muted">@lang('Your data is protected')</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="las la-user-shield text-primary" style="font-size: 36px;"></i>
                        <p class="mb-0 mt-2 fw-bold">@lang('Private')</p>
                        <small class="text-muted">@lang('Confidential communication')</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <i class="las la-comment-dots text-info" style="font-size: 36px;"></i>
                        <p class="mb-0 mt-2 fw-bold">@lang('Responsive')</p>
                        <small class="text-muted">@lang('Quick support team')</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

