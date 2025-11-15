@extends('agent.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <!-- Application Info Card -->
        <div class="card b-radius--10 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 text-dark">
                            <i class="las la-user text-primary"></i> {{ $application->user->fullname }}
                        </h5>
                        <p class="mb-0 text-muted">
                            @if($type === 'mortgage')
                                <span class="badge badge--primary">
                                    <i class="las la-home"></i> @lang('Home Mortgage')
                                </span>
                            @else
                                <span class="badge badge--info">
                                    <i class="las la-hand-holding-usd"></i> @lang('Personal Loan')
                                </span>
                            @endif
                            @lang('Application') #{{ $application->id }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('agent.messages.index') }}" class="btn btn-sm btn-outline--dark">
                            <i class="las la-arrow-left"></i> @lang('Back to Inbox')
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="card b-radius--10">
            <div class="card-header bg--primary">
                <h5 class="text-white mb-0">
                    <i class="las la-comments"></i> @lang('Conversation')
                </h5>
            </div>
            <div class="card-body" style="min-height: 400px; max-height: 600px; overflow-y: auto;" id="messageContainer">
                @forelse($messages as $message)
                    <div class="mb-3 {{ $message->isSentByAgent() ? 'text-end' : '' }}">
                        <div class="d-inline-block" style="max-width: 70%;">
                            <div class="p-3 rounded {{ $message->isSentByAgent() ? 'bg--success text-white' : 'bg-light text-dark' }}" 
                                 style="box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                <div class="mb-2">
                                    <strong class="{{ $message->isSentByAgent() ? 'text-white' : 'text-primary' }}">
                                        @if($message->isSentByAgent())
                                            <i class="las la-user-tie"></i> @lang('You')
                                        @else
                                            <i class="las la-user"></i> {{ $message->sender->fullname }}
                                        @endif
                                    </strong>
                                </div>
                                <div class="{{ $message->isSentByAgent() ? 'text-white' : 'text-dark' }}" style="white-space: pre-wrap;">{{ $message->message }}</div>
                                
                                @if($message->attachment)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $message->attachment) }}" 
                                           target="_blank" 
                                           class="btn btn-sm {{ $message->isSentByAgent() ? 'btn-outline-light' : 'btn-outline-primary' }}"
                                           download="{{ $message->attachment_original_name }}">
                                            <i class="las la-paperclip"></i> {{ $message->attachment_original_name }}
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="mt-2 {{ $message->isSentByAgent() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.75rem;">
                                    {{ $message->created_at->format('d M Y, h:i A') }}
                                    @if($message->is_read && $message->isSentByAgent())
                                        <i class="las la-check-double"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="las la-comment-slash" style="font-size: 4rem;"></i>
                        <p class="mt-3">@lang('No messages yet')</p>
                    </div>
                @endforelse
            </div>
            <div class="card-footer bg-light">
                <form action="{{ route('agent.messages.reply', [$userId, $type, $application->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <textarea name="message" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="@lang('Type your message here...')" 
                                      required></textarea>
                        </div>
                        <div class="col-md-8">
                            <input type="file" name="attachment" id="agentAttachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">
                                <i class="las la-paperclip"></i> @lang('Attach file (PDF, JPG, PNG - max 10MB)')
                            </small>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn--primary w-100" style="padding: 0.75rem;">
                                <i class="las la-paper-plane"></i>
                                @lang('Send Reply')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    // Auto-scroll to bottom of messages
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.getElementById('messageContainer');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    });
</script>
@endpush
@endsection

