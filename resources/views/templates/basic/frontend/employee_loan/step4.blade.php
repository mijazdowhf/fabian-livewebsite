@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="loan-application-wrapper">
    <div class="container section">
        <div class="loan-app-header text-center mb-5">
            <h2 class="loan-app-title">@lang('Employee Loan Application')</h2>
            <p class="loan-app-subtitle">@lang('Step 4 of 4: Upload Documents')</p>
        </div>

        <div class="loan-steps-wrapper mb-5">
            <div class="loan-steps">
                <div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">01</span><span class="loan-step-title">@lang('Personal')</span></div></div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">02</span><span class="loan-step-title">@lang('Employment')</span></div></div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step completed"><div class="loan-step-icon"><i class="las la-check"></i></div><div class="loan-step-content"><span class="loan-step-number">03</span><span class="loan-step-title">@lang('Loan Details')</span></div></div>
                <div class="loan-step-line completed"></div>
                <div class="loan-step active"><div class="loan-step-icon"><i class="las la-file-pdf"></i></div><div class="loan-step-content"><span class="loan-step-number">04</span><span class="loan-step-title">@lang('Documents')</span></div></div>
            </div>
        </div>

        <form method="POST" action="{{ route('employee.loan.step4.store') }}" id="employeeLoanStep4" class="loan-wizard-form" enctype="multipart/form-data">
            @csrf
            
            <div class="alert alert-info mb-4">
                <i class="las la-exclamation-circle"></i>
                <strong>@lang('Important:')</strong> @lang('All documents must be in PDF format (max 100MB per file). The bank does not accept photos or other formats.')
            </div>

            <div class="loan-form-card">
                <div class="loan-form-card-header">
                    <div class="loan-form-card-icon"><i class="las la-file-pdf"></i></div>
                    <div><h4 class="loan-form-card-title">@lang('Required Documents')</h4><p class="loan-form-card-subtitle">@lang('Upload required PDF documents')</p></div>
                </div>
                <div class="loan-form-card-body">
                    <div class="row g-4">
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Valid ID') <span class="required">*</span></label><input type="file" name="doc_valid_id" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Last 5 Payslips') <span class="required">*</span></label><input type="file" name="doc_payslips" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Employment Contract') <span class="required">*</span></label><input type="file" name="doc_employment_contract" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Certificate of Residency')</label><input type="file" name="doc_certificate_residency" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Family Status Certificate')</label><input type="file" name="doc_family_status" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Marital Status Certificate')</label><input type="file" name="doc_marital_status" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Health Card')</label><input type="file" name="doc_health_card" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Residence Permit (if foreign)')</label><input type="file" name="doc_residence_permit" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Passport')</label><input type="file" name="doc_passport" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('CU 2025')</label><input type="file" name="doc_cu_2025" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Bank Statement')</label><input type="file" name="doc_bank_statement" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Transactions (30 days)')</label><input type="file" name="doc_transactions_30days" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('INPS Statement')</label><input type="file" name="doc_inps_statement" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('Loan Agreement (if existing)')</label><input type="file" name="doc_loan_agreement" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                        <div class="col-md-6"><div class="file-upload-group"><label class="file-upload-label"><i class="las la-file-pdf"></i>@lang('ISEE (if under 36)')</label><input type="file" name="doc_isee" class="file-upload-input" accept=".pdf"><span class="error-message"></span></div></div>
                    </div>
                </div>
            </div>

            <div class="loan-form-card">
                <div class="loan-form-card-body">
                    <div class="privacy-checkbox">
                        <input type="checkbox" name="privacy_authorization" id="privacy" value="1">
                        <label for="privacy" class="privacy-label"><i class="las la-shield-alt"></i>@lang('I have read, confirm, and accept the terms and conditions and privacy policy')</label>
                    </div>
                    <span class="error-message" id="privacy_error"></span>
                </div>
            </div>

            <div class="loan-form-actions">
                <a href="{{ route('employee.loan.step3') }}" class="loan-back-btn"><i class="las la-arrow-left"></i>@lang('Back')</a>
                <button type="submit" class="loan-submit-btn"><span class="btn-text">@lang('Submit Application')</span><i class="las la-check-circle btn-icon"></i></button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('Template::frontend.personal_loan.styles')

@push('style')
<style>
.file-upload-group{margin-bottom:0}.file-upload-label{display:flex;align-items:center;gap:0.5rem;font-size:0.9375rem;font-weight:600;color:hsl(var(--dark));margin-bottom:0.75rem}.file-upload-label i{color:hsl(var(--base));font-size:1.125rem}.file-upload-label .required{color:#dc3545;font-weight:700}.file-upload-input{width:100%;padding:0.875rem 1.25rem;border:1.5px dashed #d0d7de;border-radius:10px;font-size:0.9375rem;background:#f8f9fa;transition:all 0.3s ease;cursor:pointer}.file-upload-input:hover{border-color:hsl(var(--base));background:#ffffff}.file-upload-input:focus{outline:none;border-color:hsl(var(--base));box-shadow:0 0 0 4px hsl(var(--base)/0.1)}.alert{padding:1.25rem 1.5rem;border-radius:10px;border-left:4px solid #0dcaf0}
</style>
@endpush

@push('script')
<script>
$('#employeeLoanStep4').on('submit',function(e){var v=true;$('.error-message').text('').hide();if(!$('#privacy').is(':checked')){v=false;$('#privacy_error').text('Required').show()}if(['doc_valid_id','doc_payslips','doc_employment_contract'].some(f=>!$('[name="'+f+'"]')[0].files.length)){v=false;alert('Please upload all required documents (Valid ID, Payslips, Employment Contract)')}if(!v){e.preventDefault();return false}});
$('#privacy').on('change',function(){if($(this).is(':checked'))$('#privacy_error').text('').hide()});
$('#loan_purpose').on('change',function(){this.value==='other'?$('#purposeOther').slideDown(300):$('#purposeOther').slideUp(300)});
if($('#loan_purpose').val()==='other')$('#purposeOther').show();
</script>
@endpush

