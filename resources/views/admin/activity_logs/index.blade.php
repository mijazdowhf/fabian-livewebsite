@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <!-- Filters -->
                        <div class="card-header">
                            <form action="{{ route('admin.activity.logs.index') }}" method="GET">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Actor Type</label>
                                            <select name="actor_type" class="form-control">
                                                <option value="">All</option>
                                                <option value="user" {{ request('actor_type') == 'user' ? 'selected' : '' }}>Users</option>
                                                <option value="agent" {{ request('actor_type') == 'agent' ? 'selected' : '' }}>Agents</option>
                                                <option value="admin" {{ request('actor_type') == 'admin' ? 'selected' : '' }}>Admins</option>
                                                <option value="system" {{ request('actor_type') == 'system' ? 'selected' : '' }}>System</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Action Type</label>
                                            <select name="action_type" class="form-control">
                                                <option value="">All</option>
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
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn--primary w-100">
                                                <i class="las la-filter"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search by actor name, description, or application ID..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn--primary w-100">
                                            <i class="las la-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Time')</th>
                                    <th>@lang('Actor')</th>
                                    <th>@lang('Action')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Resource')</th>
                                    <th>@lang('IP Address')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <div>{{ showDateTime($log->created_at, 'd M Y') }}</div>
                                            <div><small class="text-muted">{{ $log->created_at->format('h:i A') }}</small></div>
                                        </td>
                                        <td>
                                            <div>
                                                @if($log->actor_type == 'agent')
                                                    <span class="badge badge--info">Agent</span>
                                                @elseif($log->actor_type == 'user')
                                                    <span class="badge badge--success">User</span>
                                                @elseif($log->actor_type == 'admin')
                                                    <span class="badge badge--warning">Admin</span>
                                                @else
                                                    <span class="badge badge--dark">System</span>
                                                @endif
                                            </div>
                                            <div><strong>{{ $log->actor_name ?? 'N/A' }}</strong></div>
                                        </td>
                                        <td>
                                            @if($log->action == 'upload')
                                                <span class="badge badge--primary"><i class="las la-upload"></i> Upload</span>
                                            @elseif($log->action == 'submit')
                                                <span class="badge badge--success"><i class="las la-paper-plane"></i> Submit</span>
                                            @elseif($log->action == 'update')
                                                <span class="badge badge--info"><i class="las la-edit"></i> Update</span>
                                            @elseif($log->action == 'view')
                                                <span class="badge badge--secondary"><i class="las la-eye"></i> View</span>
                                            @elseif($log->action == 'download')
                                                <span class="badge badge--warning"><i class="las la-download"></i> Download</span>
                                            @else
                                                <span class="badge badge--dark">{{ ucfirst($log->action) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($log->description, 60) }}</small>
                                        </td>
                                        <td>
                                            @if($log->resource_identifier)
                                                <div><strong>{{ $log->resource_identifier }}</strong></div>
                                            @endif
                                            <small class="text-muted">{{ class_basename($log->resource_type) }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->ip_address ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.activity.logs.details', $log->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-info-circle"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="las la-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                                <p class="text-muted">@lang('No activity logs found')</p>
                                            </div>
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
    <a href="{{ route('admin.activity.logs.statistics') }}" class="btn btn-sm btn--primary">
        <i class="las la-chart-bar"></i> @lang('Statistics')
    </a>
    <a href="{{ route('admin.activity.logs.agent.activities') }}" class="btn btn-sm btn--info">
        <i class="las la-user-tie"></i> @lang('Agent Activities')
    </a>
@endpush

