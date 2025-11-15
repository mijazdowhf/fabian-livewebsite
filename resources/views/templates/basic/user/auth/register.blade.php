    @php
        $loginContent = getContent('login.content', true);
    @endphp

    @extends($activeTemplate . 'layouts.frontend')
    @section('content')
        <div class="section container">
            <div class="row g-lg-0">
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="h-100 auth-form__bg" style="background-image: url({{ frontendImage('login', @$loginContent->data_values->image, '800x1100') }});">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="auth-form__content">
                        <h3 class="text-capitalize text-center mt-0 mb-4">
                            @lang('Welcome To') {{ __(gs('site_name')) }}
                        </h3>
                        <form method="POST" action="{{ route('user.register') }}" class="row g-4 verify-gcaptcha">
                            @csrf

                            <div class="col-sm-6">
                                <div class="auth-form__input-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-user"></i>
                                    </span>
                                    <input type="text" name="firstname" class="auth-form__input checkUser" value="{{ old('firstname') }}" placeholder="@lang('Firstname')" required autofocus="off" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="auth-form__input-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-user"></i>
                                    </span>
                                    <input type="text" name="lastname" class="auth-form__input checkUser" value="{{ old('lastname') }}" placeholder="@lang('Lastname')" required autofocus="off" />
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="auth-form__input-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-envelope-open-text"></i>
                                    </span>
                                    <input type="text" name="email" class="auth-form__input checkUser" value="{{ old('email') }}" placeholder="@lang('Email Address')" required autofocus="off" />
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="custom--nice-select auth-form__input-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-user-tag"></i>
                                    </span>
                                    <select name="role" id="userRole" required>
                                        <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>@lang('User')</option>
                                        <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>@lang('Agent')</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Agent-specific fields -->
                            <div id="agentFields" style="display: none;" class="col-12">
                                <div class="card border-primary" style="background: #f0f8ff;">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">
                                            <i class="las la-user-tie"></i> @lang('Agent Information')
                                        </h6>
                                        <div class="row g-4">
                                    <!-- VAT Number Section -->
                                    <div class="col-12">
                                        <label class="form-label text-dark fw-bold">@lang('Do you have a VAT number?') <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3 mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="has_vat" id="hasVatYes" value="1" {{ old('has_vat') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label text-dark" for="hasVatYes">@lang('Yes, with VAT')</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="has_vat" id="hasVatNo" value="0" {{ old('has_vat', '0') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label text-dark" for="hasVatNo">@lang('No, without VAT')</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- VAT Number Input (conditional) -->
                                    <div class="col-12" id="vatNumberField" style="display: none;">
                                        <div class="auth-form__input-group">
                                            <span class="auth-form__input-icon">
                                                <i class="las la-file-invoice"></i>
                                            </span>
                                            <input type="text" name="vat_number" class="auth-form__input" value="{{ old('vat_number') }}" placeholder="@lang('VAT Number')" autofocus="off" />
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-12">
                                        <div class="auth-form__input-group">
                                            <span class="auth-form__input-icon">
                                                <i class="las la-map-marker-alt"></i>
                                            </span>
                                            <input type="text" name="agent_address" class="auth-form__input" value="{{ old('agent_address') }}" placeholder="@lang('Address')" autofocus="off" />
                                        </div>
                                    </div>

                                    <!-- City and Country -->
                                    <div class="col-sm-6">
                                        <div class="auth-form__input-group">
                                            <span class="auth-form__input-icon">
                                                <i class="las la-city"></i>
                                            </span>
                                            <input type="text" name="agent_city" class="auth-form__input" value="{{ old('agent_city') }}" placeholder="@lang('City')" autofocus="off" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="auth-form__input-group">
                                            <span class="auth-form__input-icon">
                                                <i class="las la-globe"></i>
                                            </span>
                                            <input type="text" name="agent_country" class="auth-form__input" value="{{ old('agent_country') }}" placeholder="@lang('Country')" autofocus="off" />
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-12">
                                        <div class="auth-form__input-group">
                                            <span class="auth-form__input-icon">
                                                <i class="las la-phone"></i>
                                            </span>
                                            <input type="text" name="agent_phone" class="auth-form__input" value="{{ old('agent_phone') }}" placeholder="@lang('Phone Number')" autofocus="off" />
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="auth-form__input-group form-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="auth-form__input @if (gs('secure_password')) secure-password @endif" placeholder="@lang('Your password')" required />

                                    <span class="auth-form__input-icon auth-form__toggle-pass">
                                        <i class="bx bxs-hide"></i>
                                    </span>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="auth-form__input-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-lock"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" class="auth-form__input" placeholder="@lang('Confirm password')" required />
                                    <span class="auth-form__input-icon auth-form__toggle-pass">
                                        <i class="bx bxs-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="auth-form__input-group">
                                    <span class="auth-form__input-icon">
                                        <i class="las la-gift"></i>
                                    </span>
                                    <input type="text" 
                                           name="referral_code" 
                                           class="auth-form__input" 
                                           value="{{ old('referral_code', request()->ref) }}" 
                                           placeholder="@lang('Referral Code (Optional)')" 
                                           {{ request()->ref ? 'readonly' : '' }}
                                           autofocus="off" />
                                </div>
                                @if(request()->ref)
                                    <small class="text-success">
                                        <i class="las la-check-circle"></i> @lang('Referral code applied successfully!')
                                    </small>
                                @else
                                    <small class="text-muted">@lang('Enter agent referral code if you have one')</small>
                                @endif
                            </div>

                            <x-captcha />

                            @if (gs('agree'))
                                @php
                                    $policyPages = getContent('policy_pages.element', false, null, true);
                                @endphp
                                <div class="form-group">
                                    <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                                    <label for="agree">@lang('I agree with')</label> <span>
                                        @foreach ($policyPages as $policy)
                                            <a class="text-decoration-none" href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ __(@$policy->data_values->title) }}</a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </span>
                                </div>
                            @endif

                            <div class="col-12">
                                <button type="submit" id="recaptcha" class="btn btn--base btn--xxl w-100 text-capitalize xl-text">
                                    @lang('Register')
                                </button>
                            </div>
                            <div class="col-12">
                                <p class="mb-0 text-capitalize text-center">
                                    @lang('Already have an account yet')?
                                    <a href="{{ route('user.login') }}" class="t-link border-0 bg--light btn-link t-link--primary">
                                        @lang('Login Now')
                                    </a>
                                </p>
                            </div>

                            @php
                                $credentials = gs('socialite_credentials');
                            @endphp
                            @if ($credentials->google->status == Status::ENABLE || $credentials->facebook->status == Status::ENABLE || $credentials->linkedin->status == Status::ENABLE)
                                <div class="col-12">
                                    <div class="auth-form__divider">
                                        <span class="d-block text-center text-capitalize auth-form__divider-text">
                                            @lang(' or')
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p class="mb-0 text-capitalize text-center">
                                        @lang('Continue with social media')
                                    </p>
                                </div>

                                <div class="col-12">
                                    <ul class="list list--row justify-content-center">
                                        @if ($credentials->facebook->status == Status::ENABLE)
                                            <li class="list--row__item">
                                                <a href="{{ route('user.social.login', 'facebook') }}" class="t-link icon icon--circle icon--md bg--primary t-text-white t-link--light">
                                                    <i class="bx bxl-facebook"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($credentials->google->status == Status::ENABLE)
                                            <li class="list--row__item">
                                                <a href="{{ route('user.social.login', 'google') }}" class="t-link icon icon--circle icon--md bg--danger t-text-white t-link--light">
                                                    <i class="bx bxl-google"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($credentials->linkedin->status == Status::ENABLE)
                                            <li class="list--row__item">
                                                <a href="{{ route('user.social.login', 'linkedin') }}" class="t-link icon icon--circle icon--md bg--info t-text-white t-link--light">
                                                    <i class="bx bxl-linkedin"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @if (gs('secure_password'))
        @push('script-lib')
            <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        @endpush
    @endif

    @push('script')
        <script>
            "use strict";
            (function($) {

                $('.checkUser').on('focusout', function(e) {
                    var url = '{{ route('user.checkUser') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });

                // Show/hide agent fields based on role selection
                function toggleAgentFields() {
                    var role = $('select[name="role"]').val();
                    console.log('Role selected:', role);
                    if (role === 'agent') {
                        $('#agentFields').show();
                        // Set all agent fields as required except vat_number (it's conditional)
                        $('#agentFields input').not('input[name="vat_number"]').attr('required', true);
                        // Default to "No VAT" if not already set
                        if (!$('input[name="has_vat"]:checked').length) {
                            $('#hasVatNo').prop('checked', true);
                        }
                        // Initialize VAT field visibility
                        toggleVatNumberField();
                    } else {
                        $('#agentFields').hide();
                        $('#agentFields input').attr('required', false);
                    }
                }

                // Show/hide VAT number field based on has_vat selection
                function toggleVatNumberField() {
                    if ($('#hasVatYes').is(':checked')) {
                        $('#vatNumberField').show();
                        $('input[name="vat_number"]').attr('required', true);
                    } else {
                        $('#vatNumberField').hide();
                        $('input[name="vat_number"]').attr('required', false).val('');
                    }
                }

                // Wait for nice-select to initialize, then set up handlers
                setTimeout(function() {
                    // Initialize on page load
                    toggleAgentFields();
                    toggleVatNumberField();

                    // Handle role change - works with nice-select
                    $('select[name="role"]').on('change', function() {
                        toggleAgentFields();
                    });

                    // Also listen to clicks on nice-select options
                    $('.nice-select .option').on('click', function() {
                        setTimeout(toggleAgentFields, 100);
                    });

                    // Handle VAT radio change
                    $('input[name="has_vat"]').on('change', function() {
                        toggleVatNumberField();
                    });
                }, 500);

            })(jQuery);
        </script>
    @endpush
