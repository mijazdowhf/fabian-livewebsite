@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <!-- Filters -->
                        <div class="card-header">
                            <form action="{{ route('admin.activity.logs.agent.activities', $agentId) }}" method="GET">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Agent</label>
                                            <select name="agent_id" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Agents</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}" {{ $agentId == $agent->id ? 'selected' : '' }}>
                                                        {{ $agent->fullname }} ({{ $agent->username }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Action Type</label>
                                            <select name="action_type" class="form-control">
                                                <option value="">All Actions</option>
                                                <option value="document_upload" {{ request('action_type') == 'document_upload' ? 'selected' : '' }}>Document Upload</option>
                                                <option value="application_submit" {{ request('action_type') == 'application_submit' ? 'selected' : '' }}>Application Submit</option>
                                                <option value="status_change" {{ request('action_type') == 'status_change' ? 'selected' : '' }}>Status Change</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date From</label>
                                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date To</label>
                                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn--primary w-100">
                                                <i class="las la-filter"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Time')</th>
                                    <th>@lang('Agent')</th>
                                    <th>@lang('Action')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Application')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <div>{{ showDateTime($log->created_at, 'd M Y') }}</div>
                                            <div><small class="text-muted">{{ $log->created_at->format('h:i A') }}</small></div>
                                            <div><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small></div>
                                        </td>
                                        <td>
                                            <div><strong>{{ $log->actor_name }}</strong></div>
                                            <div><small class="text-muted">ID: {{ $log->actor_id }}</small></div>
                                        </td>
                                        <td>
                                            @if($log->action == 'upload')
                                                <span class="badge badge--primary"><i class="las la-upload"></i> Upload</span>
                                            @elseif($log->action == 'submit')
                                                <span class="badge badge--success"><i class="las la-paper-plane"></i> Submit</span>
                                            @elseif($log->action == 'update')
                                                <span class="badge badge--info"><i class="las la-edit"></i> Update</span>
                                            @else
                                                <span class="badge badge--dark">{{ ucfirst($log->action) }}</span>
                                            @endif
                                            <div><small class="text-muted">{{ str_replace('_', ' ', ucwords($log->action_type, '_')) }}</small></div>
                                        </td>
                                        <td>
                                            {{ Str::limit($log->description, 80) }}
                                            
                                            @if($log->metadata)
                                                <div class="mt-1">
                                                    @if(isset($log->metadata['document_name']))
                                                        <span class="badge badge--dark"><i class="las la-file"></i> {{ $log->metadata['document_name'] }}</span>
                                                    @endif
                                                    @if(isset($log->metadata['file_size']))
                                                        <span class="badge badge--secondary">{{ formatFileSize($log->metadata['file_size'] ?? 0) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->resource_identifier)
                                                <strong>#{{ $log->resource_identifier }}</strong>
                                                <div><small class="text-muted">{{ class_basename($log->resource_type) }}</small></div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.activity.logs.details', $log->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="las la-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="text-muted">@lang('No agent activities found')</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($logs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.activity.logs.index') }}" class="btn btn-sm btn--secondary">
        <i class="las la-arrow-left"></i> @lang('All Logs')
    </a>
    <a href="{{ route('admin.activity.logs.statistics') }}" class="btn btn-sm btn--primary">
        <i class="las la-chart-bar"></i> @lang('Statistics')
    </a>
@endpush

