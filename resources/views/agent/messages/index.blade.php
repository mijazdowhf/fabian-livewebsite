@extends('agent.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="las la-comments"></i> @lang('User Messages')
                    </h5>
                    @if($totalUnread > 0)
                        <span class="badge badge--danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            {{ $totalUnread }} @lang('Unread')
                        </span>
                    @endif
                </div>

                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Application')</th>
                                <th>@lang('Last Message')</th>
                                <th>@lang('Time')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($conversations as $conversation)
                                <tr>
                                    <td>
                                        <div>
                                            <i class="las la-user text-info"></i>
                                            <strong>{{ $conversation['user']->fullname }}</strong>
                                        </div>
                                        <small class="text-muted">@lang('User ID'): {{ $conversation['user']->username }}</small>
                                    </td>
                                    <td>
                                        @if($conversation['application_type'] === 'mortgage')
                                            <span class="badge badge--primary">
                                                <i class="las la-home"></i> @lang('Home Mortgage')
                                            </span>
                                        @else
                                            <span class="badge badge--info">
                                                <i class="las la-hand-holding-usd"></i> @lang('Personal Loan')
                                            </span>
                                        @endif
                                        <br>
                                        <small class="text-muted">#{{ $conversation['application_id'] }}</small>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            @if($conversation['last_sender_type'] === 'user')
                                                <i class="las la-user text-primary"></i>
                                            @else
                                                <i class="las la-user-tie text-success"></i>
                                            @endif
                                            {{ Str::limit($conversation['last_message'], 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $conversation['last_message_time']->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $conversation['last_message_time']->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($conversation['unread_count'] > 0)
                                            <span class="badge badge--danger">
                                                {{ $conversation['unread_count'] }} @lang('New')
                                            </span>
                                        @elseif(!$conversation['is_read'])
                                            <span class="badge badge--warning">
                                                <i class="las la-check"></i> @lang('Unread')
                                            </span>
                                        @else
                                            <span class="badge badge--success">
                                                <i class="las la-check-double"></i> @lang('Read')
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('agent.messages.conversation', [$conversation['user']->id, $conversation['application_type'], $conversation['application_id']]) }}" 
                                           class="btn btn-sm btn-outline--primary">
                                            <i class="las la-comment-dots"></i> @lang('View Conversation')
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="las la-inbox" style="font-size: 4rem;"></i>
                                            <p class="mt-3">@lang('No messages yet')</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

