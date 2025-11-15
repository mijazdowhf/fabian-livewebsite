@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="loan-application-wrapper"><div class="container section">
<div class="loan-app-header text-center mb-5"><h2 class="loan-app-title">@lang('Selling Mortgage Application')</h2><p class="loan-app-subtitle">@lang('Step 4 of 4: Upload Documents')</p></div>
<div class="loan-steps-wrapper mb-5"><div class="loan-steps">
<div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">01</span><span class="loan-step-title">@lang('Personal')</span></div></div><div class="loan-step-line completed"></div>
<div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">02</span><span class="loan-step-title">@lang('Business')</span></div></div><div class="loan-step-line completed"></div>
<div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">03</span><span class="loan-step-title">@lang('Mortgage')</span></div></div><div class="loan-step-line completed"></div>
<div class="loan-step active"><div class="loan-step-icon"><i class="las la-file-pdf"></i></div><div class="loan-step-content"><span class="loan-step-number">04</span><span class="loan-step-title">@lang('Documents')</span></div></div>
</div></div>
<div class="alert alert-info mb-4"><i class="las la-exclamation-circle"></i> <strong>@lang('Important:')</strong> @lang('All documents must be in PDF format (max 100MB per file). The bank does not accept photos or other formats.')</div>
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>@lang('Please fix the following errors:')</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<form method="POST" action="{{ route('selling.mortgage.step4.store') }}" id="sellingMortgageStep4" class="loan-wizard-form" enctype="multipart/form-data">@csrf
<div class="loan-form-card"><div class="loan-form-card-header"><div class="loan-form-card-icon"><i class="las la-file-pdf"></i></div><div><h4 class="loan-form-card-title">@lang('Required Documents')</h4><p class="loan-form-card-subtitle">@lang('Upload PDF documents')</p></div></div>
<div class="loan-form-card-body">
<div class="alert alert-warning mb-3">
    <i class="las la-info-circle"></i> <strong>@lang('Note:')</strong> @lang('Only Valid ID is required. You can upload other documents now, or your assigned agent can help upload them later.')
</div>
<div class="row g-4">
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Valid ID') <span class="required">*</span></label><input type="file" name="doc_valid_id" class="file-upload-input @error('doc_valid_id') is-invalid @enderror" accept=".pdf">@error('doc_valid_id')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('VAT Assignment')</label><input type="file" name="doc_vat_assignment" class="file-upload-input @error('doc_vat_assignment') is-invalid @enderror" accept=".pdf">@error('doc_vat_assignment')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('2025 Tax Return')</label><input type="file" name="doc_tax_return_2025" class="file-upload-input @error('doc_tax_return_2025') is-invalid @enderror" accept=".pdf">@error('doc_tax_return_2025')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Certificate of Residency')</label><input type="file" name="doc_certificate_residency" class="file-upload-input @error('doc_certificate_residency') is-invalid @enderror" accept=".pdf">@error('doc_certificate_residency')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Family Status')</label><input type="file" name="doc_family_status" class="file-upload-input @error('doc_family_status') is-invalid @enderror" accept=".pdf">@error('doc_family_status')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Marital Status')</label><input type="file" name="doc_marital_status" class="file-upload-input @error('doc_marital_status') is-invalid @enderror" accept=".pdf">@error('doc_marital_status')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Health Card')</label><input type="file" name="doc_health_card" class="file-upload-input @error('doc_health_card') is-invalid @enderror" accept=".pdf">@error('doc_health_card')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Residence Permit')</label><input type="file" name="doc_residence_permit" class="file-upload-input @error('doc_residence_permit') is-invalid @enderror" accept=".pdf">@error('doc_residence_permit')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('2024 Tax Return')</label><input type="file" name="doc_tax_return_2024" class="file-upload-input @error('doc_tax_return_2024') is-invalid @enderror" accept=".pdf">@error('doc_tax_return_2024')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('2025 Electronic Receipt')</label><input type="file" name="doc_electronic_receipt_2025" class="file-upload-input @error('doc_electronic_receipt_2025') is-invalid @enderror" accept=".pdf">@error('doc_electronic_receipt_2025')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('2024 Electronic Receipt')</label><input type="file" name="doc_electronic_receipt_2024" class="file-upload-input @error('doc_electronic_receipt_2024') is-invalid @enderror" accept=".pdf">@error('doc_electronic_receipt_2024')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Bank Statement')</label><input type="file" name="doc_bank_statement" class="file-upload-input @error('doc_bank_statement') is-invalid @enderror" accept=".pdf">@error('doc_bank_statement')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Transactions 30 Days')</label><input type="file" name="doc_transactions_30days" class="file-upload-input @error('doc_transactions_30days') is-invalid @enderror" accept=".pdf">@error('doc_transactions_30days')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
<div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Loan Agreement')</label><input type="file" name="doc_loan_agreement" class="file-upload-input @error('doc_loan_agreement') is-invalid @enderror" accept=".pdf">@error('doc_loan_agreement')<span class="error-message" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.25rem;">{{ $message }}</span>@else<span class="error-message"></span>@enderror</div></div>
</div></div></div>
<div class="loan-form-card"><div class="loan-form-card-body"><div class="privacy-checkbox"><input type="checkbox" name="privacy_authorization" id="privacy" value="1" @error('privacy_authorization') class="is-invalid" @enderror><label for="privacy" class="privacy-label"><i class="las la-shield-alt"></i>@lang('I have read, confirm, and accept the terms and conditions and privacy policy')</label></div>@error('privacy_authorization')<span class="error-message" id="privacy_error" style="display:block;color:#dc3545;font-size:0.875rem;margin-top:0.5rem;">{{ $message }}</span>@else<span class="error-message" id="privacy_error"></span>@enderror</div></div>
<div class="loan-form-actions"><a href="{{ route('selling.mortgage.step3') }}" class="loan-back-btn"><i class="las la-arrow-left"></i>@lang('Back')</a><button type="submit" class="loan-submit-btn"><span class="btn-text">@lang('Submit')</span><i class="las la-check-circle btn-icon"></i></button></div>
</form></div></div>
@endsection
@include('Template::frontend.personal_loan.styles')
@push('style')<style>.file-upload-group{margin-bottom:0}.file-upload-label{display:flex;align-items:center;gap:0.5rem;font-size:0.9375rem;font-weight:600;color:hsl(var(--dark));margin-bottom:0.75rem}.file-upload-label i{color:hsl(var(--base));font-size:1.125rem}.file-upload-label .required{color:#dc3545;font-weight:700}.file-upload-input{width:100%;padding:0.875rem 1.25rem;border:1.5px dashed #d0d7de;border-radius:10px;font-size:0.9375rem;background:#f8f9fa;transition:all 0.3s ease;cursor:pointer}.file-upload-input:hover{border-color:hsl(var(--base));background:#ffffff}.file-upload-input.is-invalid{border-color:#dc3545;border-style:solid;background:#fff5f5}.alert{padding:1.25rem 1.5rem;border-radius:10px;border-left:4px solid #0dcaf0}.error-message{color:#dc3545;font-size:0.875rem;margin-top:0.25rem;display:none}.error-message:not(:empty){display:block}</style>@endpush
@push('script')
<script>
$(document).ready(function() {
    // Real-time validation for privacy checkbox
    $('#privacy').on('change', function() {
        if ($(this).is(':checked')) {
            $('#privacy_error').text('').hide();
        } else {
            $('#privacy_error').text('@lang('You must accept the privacy policy')').show();
        }
    });

    // Form validation on submit
    $('#sellingMortgageStep4').on('submit', function(e) {
        var isValid = true;
        var errorMessage = '';

        // Clear previous errors
        $('.error-message').text('').hide();
        $('#privacy_error').text('').hide();

        // Check privacy checkbox
        if (!$('#privacy').is(':checked')) {
            isValid = false;
            $('#privacy_error').text('@lang('You must accept the privacy policy')').show();
            errorMessage += '@lang('Please accept the privacy policy.')\n';
        }

        // Check required documents (only Valid ID is required)
        var requiredDocs = [
            {name: 'doc_valid_id', label: '@lang('Valid ID')'}
        ];

        requiredDocs.forEach(function(doc) {
            var fileInput = $('[name="' + doc.name + '"]')[0];
            if (!fileInput.files || fileInput.files.length === 0) {
                isValid = false;
                $('[name="' + doc.name + '"]').siblings('.error-message')
                    .text('@lang('This document is required')').show();
                errorMessage += doc.label + ' @lang('is required')\n';
            }
        });

        // If validation fails, show alert and prevent submission
        if (!isValid) {
            e.preventDefault();
            alert('@lang('Please fill in all required fields and upload required documents.')\n\n' + errorMessage);
            // Scroll to first error
            $('html, body').animate({
                scrollTop: $('.error-message:visible:first').offset().top - 100
            }, 500);
            return false;
        }

        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>@lang('Submitting...')');
    });

    // File input change handler to clear errors
    $('.file-upload-input').on('change', function() {
        if (this.files && this.files.length > 0) {
            $(this).siblings('.error-message').text('').hide();
        }
    });
});
</script>
@endpush


