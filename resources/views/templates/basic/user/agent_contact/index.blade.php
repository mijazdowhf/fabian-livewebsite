@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="las la-comments"></i> @lang('Contact My Agent')
                    </h5>
                    @if($unreadCount > 0)
                        <span class="badge badge--danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            {{ $unreadCount }} @lang('Unread Messages')
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        <i class="las la-info-circle"></i> 
                        @lang('You can contact the agents assigned to your applications here.')
                    </p>

                    <!-- Mortgages Section -->
                    @if($mortgages->count() > 0)
                        <h6 class="mb-3">
                            <i class="las la-home"></i> @lang('Home Mortgage Applications')
                        </h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('Application ID')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Assigned Agent')</th>
                                        <th>@lang('Submitted Date')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mortgages as $mortgage)
                                        <tr>
                                            <td>
                                                <strong>#{{ $mortgage->application_id ?? $mortgage->id }}</strong>
                                            </td>
                                            <td>
                                                @if($mortgage->status == 'pending')
                                                    <span class="badge badge--warning">@lang('Pending')</span>
                                                @elseif($mortgage->status == 'processing')
                                                    <span class="badge badge--info">@lang('Processing')</span>
                                                @elseif($mortgage->status == 'approved')
                                                    <span class="badge badge--success">@lang('Approved')</span>
                                                @else
                                                    <span class="badge badge--danger">@lang('Rejected')</span>
                                                @endif
                                            </td>
                                    <td>
                                        <div>
                                            <i class="las la-user-tie text-primary"></i>
                                            <strong>{{ $mortgage->agent->fullname }}</strong>
                                        </div>
                                        <small class="text-muted">@lang('Agent ID'): {{ $mortgage->agent->username }}</small>
                                    </td>
                                            <td>
                                                {{ showDateTime($mortgage->created_at, 'd M Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('user.contact.agent.conversation', ['mortgage', $mortgage->id]) }}" 
                                                   class="btn btn-sm btn--primary">
                                                    <i class="las la-comment-dots"></i> @lang('Contact Agent')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Loans Section -->
                    @if($loans->count() > 0)
                        <h6 class="mb-3">
                            <i class="las la-hand-holding-usd"></i> @lang('Personal Loan Applications')
                        </h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('Application ID')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Assigned Agent')</th>
                                        <th>@lang('Submitted Date')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr>
                                            <td>
                                                <strong>#{{ $loan->application_id ?? $loan->id }}</strong>
                                            </td>
                                            <td>
                                                @if($loan->status == 'pending')
                                                    <span class="badge badge--warning">@lang('Pending')</span>
                                                @elseif($loan->status == 'processing')
                                                    <span class="badge badge--info">@lang('Processing')</span>
                                                @elseif($loan->status == 'approved')
                                                    <span class="badge badge--success">@lang('Approved')</span>
                                                @else
                                                    <span class="badge badge--danger">@lang('Rejected')</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="las la-user-tie text-primary"></i>
                                                    <strong>{{ $loan->agent->fullname }}</strong>
                                                </div>
                                                <small class="text-muted">@lang('Agent ID'): {{ $loan->agent->username }}</small>
                                            </td>
                                            <td>
                                                {{ showDateTime($loan->created_at, 'd M Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('user.contact.agent.conversation', ['loan', $loan->id]) }}" 
                                                   class="btn btn-sm btn--primary">
                                                    <i class="las la-comment-dots"></i> @lang('Contact Agent')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- No Applications Message -->
                    @if($mortgages->count() == 0 && $loans->count() == 0)
                        <div class="text-center py-5">
                            <i class="las la-user-slash text-muted" style="font-size: 5rem;"></i>
                            <h5 class="mt-3 text-muted">@lang('No applications assigned to an agent yet')</h5>
                            <p class="text-muted">@lang('Once an agent is assigned to your application, you can contact them here.')</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

