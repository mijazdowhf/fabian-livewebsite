@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @push('backend-style')
        <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
        <link href="{{ asset($activeTemplateTrue . 'css/user-dashboard-colors.css') }}" rel="stylesheet">
    @endpush
    @if(session('package_deposit'))
        <div class="container section">
            @yield('content')
        </div>
    @else
        <div class="d-flex flex-wrap">
            @include($activeTemplate . 'partials.sidebar')
            <div class="dashboard-wrapper">
                @include($activeTemplate . 'partials.topbar')
                <div class="dashboard-container">
                    @yield('content')
                </div>
            </div>
        </div>
    @endif
    @push('backend-script')
        <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    @endpush
@endsection
