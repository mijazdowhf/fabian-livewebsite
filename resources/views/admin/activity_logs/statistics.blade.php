@extends('admin.layouts.app')
@section('panel')
    <!-- Statistics Cards -->
    <div class="row gy-4 mb-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">@lang('Total Activities')</h6>
                            <h3 class="mb-0">{{ $stats['total_activities'] }}</h3>
                            <small class="text-muted">@lang('Last') {{ $days }} @lang('days')</small>
                        </div>
                        <div class="dashboard-icon">
                            <i class="las la-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">@lang('Agent Activities')</h6>
                            <h3 class="mb-0 text-info">{{ $stats['agent_activities'] }}</h3>
                            <small class="text-muted">@lang('Last') {{ $days }} @lang('days')</small>
                        </div>
                        <div class="dashboard-icon bg--info">
                            <i class="las la-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">@lang('Document Uploads')</h6>
                            <h3 class="mb-0 text-primary">{{ $stats['document_uploads'] }}</h3>
                            <small class="text-muted">@lang('Last') {{ $days }} @lang('days')</small>
                        </div>
                        <div class="dashboard-icon bg--primary">
                            <i class="las la-upload"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">@lang('Applications Submitted')</h6>
                            <h3 class="mb-0 text-success">{{ $stats['applications_submitted'] }}</h3>
                            <small class="text-muted">@lang('Last') {{ $days }} @lang('days')</small>
                        </div>
                        <div class="dashboard-icon bg--success">
                            <i class="las la-paper-plane"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Active Agents -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="las la-trophy"></i> @lang('Most Active Agents')</h5>
                    <small class="text-muted">@lang('Last') {{ $days }} @lang('days')</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Rank')</th>
                                    <th>@lang('Agent Name')</th>
                                    <th>@lang('Total Activities')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeAgents as $index => $agent)
                                    <tr>
                                        <td>
                                            @if($index == 0)
                                                <span class="badge badge--warning">🥇 #1</span>
                                            @elseif($index == 1)
                                                <span class="badge badge--secondary">🥈 #2</span>
                                            @elseif($index == 2)
                                                <span class="badge badge--bronze">🥉 #3</span>
                                            @else
                                                <span class="badge badge--dark">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $agent->actor_name }}</strong></td>
                                        <td><span class="badge badge--primary">{{ $agent->activity_count }} activities</span></td>
                                        <td>
                                            <a href="{{ route('admin.activity.logs.agent.activities') }}?agent_id={{ $agent->actor_id ?? '' }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-eye"></i> @lang('View Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-muted">@lang('No agent activities found')</p>
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

    <!-- Activity Chart -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="las la-chart-area"></i> @lang('Activity Over Time')</h5>
                    <form action="{{ route('admin.activity.logs.statistics') }}" method="GET" class="d-inline-block">
                        <select name="days" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 Days</option>
                            <option value="365" {{ $days == 365 ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <div id="activity-chart" style="height: 300px;">
                        @if($activityByDay->isEmpty())
                            <div class="text-center py-5">
                                <i class="las la-chart-bar" style="font-size: 64px; opacity: 0.2;"></i>
                                <p class="text-muted">@lang('No activity data available')</p>
                            </div>
                        @else
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Activities')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityByDay as $activity)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ min(($activity->count / $activityByDay->max('count')) * 100, 100) }}%">
                                                        {{ $activity->count }}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.activity.logs.index') }}" class="btn btn-sm btn--secondary">
        <i class="las la-arrow-left"></i> @lang('All Logs')
    </a>
    <a href="{{ route('admin.activity.logs.agent.activities') }}" class="btn btn-sm btn--info">
        <i class="las la-user-tie"></i> @lang('Agent Activities')
    </a>
@endpush

