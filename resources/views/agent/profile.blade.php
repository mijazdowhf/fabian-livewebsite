@extends('agent.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Profile Setting')</h5>

                    <form method="POST" action="{{ route('agent.profile.update') }}" class="row g-3 mt-3">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">@lang('First Name')</label>
                            <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('Last Name')</label>
                            <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
                        </div>

                        <!-- VAT Information -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <h6 class="mb-3">@lang('VAT Information')</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('Do you have a VAT number?')</label>
                                            <select name="has_vat" id="hasVatSelect" class="form-control">
                                                <option value="0" {{ old('has_vat', $user->has_vat) == 0 ? 'selected' : '' }}>@lang('No, without VAT')</option>
                                                <option value="1" {{ old('has_vat', $user->has_vat) == 1 ? 'selected' : '' }}>@lang('Yes, with VAT')</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="vatNumberInput" style="display: {{ old('has_vat', $user->has_vat) ? 'block' : 'none' }};">
                                            <label class="form-label">@lang('VAT Number')</label>
                                            <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $user->vat_number) }}" placeholder="@lang('Enter VAT Number')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">@lang('Address') <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('City') <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">@lang('State')</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $user->state) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('Zip')</label>
                            <input type="text" name="zip" class="form-control" value="{{ old('zip', $user->zip) }}">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn--base">@lang('Update Profile')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-2">
        <div class="col-12">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Change Password')</h5>
                    <form method="POST" action="{{ route('agent.password.update') }}" class="row g-3 mt-2">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">@lang('Current Password')</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-6"></div>

                        <div class="col-md-6">
                            <label class="form-label">@lang('New Password')</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('Confirm Password')</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn--base">@lang('Update Password')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    "use strict";
    (function($) {
        // Toggle VAT number field based on has_vat selection
        $('#hasVatSelect').on('change', function() {
            if ($(this).val() == '1') {
                $('#vatNumberInput').show();
                $('input[name="vat_number"]').attr('required', true);
            } else {
                $('#vatNumberInput').hide();
                $('input[name="vat_number"]').attr('required', false);
            }
        });
    })(jQuery);
</script>
@endpush


