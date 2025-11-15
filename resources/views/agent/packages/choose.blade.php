@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <div class="container section">
    <div class="row gy-4">
        <div class="col-12">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Choose a Package to Continue')</h5>
                    <p class="text-muted">@lang('Select a package and proceed to payment. Your agent dashboard will unlock after payment.')</p>

                    <div class="row g-3 mt-2">
                        @foreach($packages as $pkg)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="mb-1">{{ $pkg->name }}</h5>
                                        <h6 class="text--base mb-2">{{ showAmount($pkg->price) }} {{ __(gs('cur_text')) }}</h6>
                                        <p class="flex-grow-1">{{ $pkg->description }}</p>
                                        <form method="POST" action="{{ route('agent.packages.select') }}">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                                            <button type="submit" class="btn btn--base w-100">@lang('Select & Pay')</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection


