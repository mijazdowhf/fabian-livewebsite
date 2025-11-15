@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card b-radius--10">
                <div class="card-body">
                    <h5 class="card-title mb-4 border-bottom pb-2">
                        <i class="las la-info-circle"></i> @lang('Activity Log Details')
                    </h5>

                    <div class="row">
                        <!-- Actor Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3"><i class="las la-user"></i> Actor Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="fw-bold">Type:</td>
                                            <td>
                                                @if($log->actor_type == 'agent')
                                                    <span class="badge badge--info">Agent</span>
                                                @elseif($log->actor_type == 'user')
                                                    <span class="badge badge--success">User</span>
                                                @elseif($log->actor_type == 'admin')
                                                    <span class="badge badge--warning">Admin</span>
                                                @else
                                                    <span class="badge badge--dark">System</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Name:</td>
                                            <td>{{ $log->actor_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">ID:</td>
                                            <td>{{ $log->actor_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">IP Address:</td>
                                            <td><code>{{ $log->ip_address }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">User Agent:</td>
                                            <td><small class="text-muted">{{ Str::limit($log->user_agent, 50) }}</small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Action Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3"><i class="las la-bolt"></i> Action Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="fw-bold">Action:</td>
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Type:</td>
                                            <td>{{ str_replace('_', ' ', ucwords($log->action_type, '_')) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Description:</td>
                                            <td>{{ $log->description }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Timestamp:</td>
                                            <td>
                                                <div>{{ showDateTime($log->created_at, 'd M Y h:i A') }}</div>
                                                <small class="text-muted">({{ $log->created_at->diffForHumans() }})</small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Resource Information -->
                        <div class="col-md-12 mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3"><i class="las la-folder-open"></i> Resource Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td class="fw-bold" style="width: 200px;">Resource Type:</td>
                                            <td><code>{{ $log->resource_type }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Resource ID:</td>
                                            <td>{{ $log->resource_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Identifier:</td>
                                            <td><strong>{{ $log->resource_identifier ?? 'N/A' }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        @if($log->metadata)
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3"><i class="las la-database"></i> Additional Metadata</h6>
                                        <pre class="bg-white p-3 rounded border">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn--secondary">
                            <i class="las la-arrow-left"></i> @lang('Back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

