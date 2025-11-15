@extends('agent.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Change Password')</h5>

                    <form method="POST" action="{{ route('agent.password.update') }}" class="row g-3 mt-3">
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


