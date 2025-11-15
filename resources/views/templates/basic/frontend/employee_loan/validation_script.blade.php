@push('script')
<script>
(function($){$('.loan-form-control').on('input',function(){$(this).removeClass('is-invalid');$(this).closest('.loan-form-group').find('.error-message').text('').hide()});$('select').on('change',function(){$(this).closest('.custom--nice-select').find('.nice-select').removeClass('is-invalid');$(this).closest('.loan-form-group').find('.error-message').text('').hide()})})(jQuery);
</script>
@endpush

