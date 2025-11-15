@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Packages')</h5>

                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td><strong>{{ $p->name }}</strong></td>
                                    <td><span class="text-success fw-bold">€{{ number_format($p->price, 2) }}</span></td>
                                    <td>
                                        <span class="badge bg-{{ $p->status ? 'success' : 'secondary' }}">{{ $p->status ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn--base btn-sm edit-package" 
                                            data-id="{{ $p->id }}" 
                                            data-name="{{ $p->name }}" 
                                            data-price="{{ $p->price }}" 
                                            data-description="{{ $p->description }}" 
                                            data-status="{{ (int)$p->status }}">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Referral Settings Card -->
        <div class="col-12">
            <div class="card box-shadow3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="las la-gift text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">@lang('Referral System Settings')</h5>
                            <p class="text-muted mb-0 small">@lang('Manage agent referral bonus configuration')</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.packages.update.referral.bonus') }}">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8">
                                <div class="referral-bonus-input-wrapper">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="las la-dollar-sign text-success"></i> @lang('Referral Bonus Amount (€)')
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="las la-euro-sign"></i>
                                        </span>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control form-control-lg" 
                                               name="referral_bonus" 
                                               value="{{ gs('referral_bonus') ?? 10.00 }}" 
                                               placeholder="10.00"
                                               required>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="las la-info-circle"></i> @lang('This bonus is credited to agents when their referred users register and complete payment')
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn--primary btn-lg w-100" style="height: 48px; margin-bottom: 28px;">
                                    <i class="las la-save"></i> @lang('Update Referral Bonus')
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="las la-lightbulb" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-2">@lang('How Referral System Works:')</h6>
                                <ul class="mb-0 ps-3">
                                    <li class="mb-1">@lang('Each agent gets a unique referral code automatically')</li>
                                    <li class="mb-1">@lang('Agents share their referral code or link with potential users')</li>
                                    <li class="mb-1">@lang('When a new user registers using the referral code')</li>
                                    <li class="mb-0">@lang('The referring agent receives the configured bonus amount in their wallet')</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="packageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit Package')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="packageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">@lang('Package Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">@lang('Package Price (€)')</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">@lang('Description')</label>
                            <textarea class="form-control" rows="4" name="description"></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="status" id="pkg_status">
                            <label class="form-check-label" for="pkg_status">@lang('Active')</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--base">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    (function(){
        const modal = document.getElementById('packageModal');
        const form = document.getElementById('packageForm');
        document.querySelectorAll('.edit-package').forEach(function(btn){
            btn.addEventListener('click', function(){
                form.action = '{{ route('admin.packages.update', 0) }}'.replace('/0', '/' + this.dataset.id);
                form.querySelector('[name=name]').value = this.dataset.name;
                form.querySelector('[name=price]').value = this.dataset.price;
                form.querySelector('[name=description]').value = this.dataset.description || '';
                const status = this.dataset.status === '1';
                form.querySelector('#pkg_status').checked = status;
                new bootstrap.Modal(modal).show();
            });
        });
    })();
</script>
@endpush


