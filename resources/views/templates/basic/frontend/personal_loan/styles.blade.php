@push('style')
<style>
    /* ===================== Professional Loan Application Styling ===================== */
    
    /* Application Wrapper */
    .loan-application-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 40px 0;
    }
    
    /* Page Header */
    .loan-app-header {
        margin-bottom: 3rem;
    }
    
    .loan-app-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: hsl(var(--dark));
        margin-bottom: 0.75rem;
        position: relative;
        display: inline-block;
    }
    
    .loan-app-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, hsl(var(--base)), hsl(var(--base) / 0.6));
        border-radius: 2px;
    }
    
    .loan-app-subtitle {
        font-size: 1.125rem;
        color: hsl(var(--dark) / 0.7);
        margin-top: 1.5rem;
        font-weight: 400;
    }
    
    /* Progress Steps */
    .loan-steps-wrapper {
        max-width: 800px;
        margin: 0 auto 4rem;
    }
    
    .loan-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }
    
    .loan-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .loan-step-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: hsl(var(--white));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: hsl(var(--dark) / 0.4);
        border: 3px solid hsl(var(--border-color));
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .loan-step.active .loan-step-icon {
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.8));
        color: hsl(var(--white));
        border-color: hsl(var(--base));
        box-shadow: 0 8px 20px hsl(var(--base) / 0.3);
    }
    
    .loan-step.completed .loan-step-icon {
        background: linear-gradient(135deg, #28a745, #218838);
        color: hsl(var(--white));
        border-color: #28a745;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }
    
    .loan-step-content {
        text-align: center;
    }
    
    .loan-step-number {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: hsl(var(--base));
        margin-bottom: 0.25rem;
    }
    
    .loan-step-title {
        font-size: 0.9375rem;
        color: hsl(var(--dark) / 0.7);
        font-weight: 500;
    }
    
    .loan-step.active .loan-step-title,
    .loan-step.completed .loan-step-title {
        color: hsl(var(--dark));
        font-weight: 600;
    }
    
    .loan-step-line {
        flex: 1;
        height: 3px;
        background: hsl(var(--border-color));
        margin: 0 1rem;
        margin-bottom: 4.5rem;
        transition: all 0.3s ease;
    }
    
    .loan-step-line.completed {
        background: #28a745;
    }
    
    /* Form Card */
    .loan-form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 3rem;
        overflow: visible;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    .loan-form-card:has(.nice-select.open) {
        z-index: 9999 !important;
    }
    
    .loan-form-card:hover {
        border-color: #d0d7de;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .loan-form-card-header {
        background: linear-gradient(135deg, hsl(var(--base) / 0.06), hsl(var(--base) / 0.03));
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .loan-form-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.8));
        color: hsl(var(--white));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px hsl(var(--base) / 0.25);
    }
    
    .loan-form-card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: hsl(var(--dark));
        margin-bottom: 0.25rem;
    }
    
    .loan-form-card-subtitle {
        font-size: 0.9375rem;
        color: hsl(var(--dark) / 0.6);
        margin-bottom: 0;
    }
    
    .loan-form-card-body {
        padding: 2.5rem 2rem;
        padding-bottom: 3rem;
    }
    
    /* Form Groups */
    .loan-form-group {
        margin-bottom: 0;
        position: relative;
    }
    
    .loan-form-group:has(.nice-select.open) {
        z-index: 10000;
    }
    
    .loan-form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        font-weight: 600;
        color: hsl(var(--dark));
        margin-bottom: 0.75rem;
    }
    
    .label-icon {
        font-size: 1.125rem;
        color: hsl(var(--base));
    }
    
    /* Form Controls */
    .loan-form-control {
        width: 100%;
        height: 52px;
        padding: 0 1.25rem;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        font-size: 1rem;
        color: #333333;
        background: #ffffff;
        transition: all 0.3s ease;
        font-family: inherit;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .loan-form-control:hover {
        border-color: #b3bac1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .loan-form-control:focus {
        outline: none;
        border-color: hsl(var(--base));
        box-shadow: 0 0 0 4px hsl(var(--base) / 0.1);
        background: #ffffff;
    }
    
    .loan-form-control::placeholder {
        color: #6c757d;
        font-weight: 400;
    }
    
    .loan-form-control.is-invalid,
    .nice-select.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
    }
    
    .error-message {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    .error-message:not(:empty) {
        display: block;
    }
    
    /* Textarea */
    .loan-form-textarea {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        font-size: 1rem;
        color: #333333;
        background: #ffffff;
        transition: all 0.3s ease;
        font-family: inherit;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        resize: vertical;
    }
    
    .loan-form-textarea:hover {
        border-color: #b3bac1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .loan-form-textarea:focus {
        outline: none;
        border-color: hsl(var(--base));
        box-shadow: 0 0 0 4px hsl(var(--base) / 0.1);
    }
    
    .loan-form-textarea.is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
    }
    
    /* Input with Icon */
    .input-with-icon {
        position: relative;
    }
    
    .input-icon-left {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.125rem;
        font-weight: 600;
        color: hsl(var(--base));
        z-index: 1;
    }
    
    .loan-form-control.with-icon {
        padding-left: 3rem;
    }
    
    /* Readonly Field */
    .loan-readonly-field {
        width: 100%;
        height: 52px;
        padding: 0 1.25rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
        color: #333333;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
    }
    
    .loan-readonly-field i {
        font-size: 1.5rem;
        color: hsl(var(--base));
    }
    
    /* Privacy Checkbox */
    .privacy-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .privacy-checkbox:hover {
        background: #ffffff;
        border-color: hsl(var(--base));
    }
    
    .privacy-checkbox input[type="checkbox"] {
        width: 24px;
        height: 24px;
        cursor: pointer;
        margin-top: 2px;
        flex-shrink: 0;
    }
    
    .privacy-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        font-size: 0.9375rem;
        color: #333333;
        cursor: pointer;
        line-height: 1.6;
    }
    
    .privacy-label i {
        font-size: 1.25rem;
        color: hsl(var(--base));
    }
    
    /* Select Styling */
    .loan-form-group .custom--nice-select {
        position: relative;
    }
    
    .loan-form-group .custom--nice-select .nice-select {
        width: 100%;
        height: 52px;
        line-height: 52px;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        padding: 0 1.25rem;
        padding-right: 3rem;
        font-size: 1rem;
        background-color: #ffffff;
        color: #333333;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .loan-form-group .custom--nice-select .nice-select .current {
        color: #333333;
        font-weight: 500;
        opacity: 1;
    }
    
    .loan-form-group .custom--nice-select .nice-select.disabled .current,
    .loan-form-group .custom--nice-select .nice-select .current:empty {
        color: #6c757d;
    }
    
    .loan-form-group .custom--nice-select .nice-select:hover {
        border-color: #b3bac1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    
    .loan-form-group .custom--nice-select .nice-select:focus,
    .loan-form-group .custom--nice-select .nice-select.open {
        border-color: hsl(var(--base));
        box-shadow: 0 0 0 4px hsl(var(--base) / 0.1);
        z-index: 10000;
    }
    
    .loan-form-group .custom--nice-select .nice-select::after {
        border-bottom: 2.5px solid #6c757d;
        border-right: 2.5px solid #6c757d;
        height: 11px;
        width: 11px;
        right: 1.25rem;
        margin-top: -7px;
        transition: all 0.3s ease;
    }
    
    .loan-form-group .custom--nice-select .nice-select:hover::after {
        border-color: #495057;
    }
    
    .loan-form-group .custom--nice-select .nice-select.open::after {
        border-color: hsl(var(--base));
        transform: rotate(-135deg);
        margin-top: -3px;
    }
    
    .loan-form-group .custom--nice-select .nice-select .list {
        width: 100%;
        max-height: 280px;
        border: 1.5px solid #d0d7de;
        border-radius: 10px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25);
        background: #ffffff;
        z-index: 99999;
        margin-top: 8px;
    }
    
    .loan-form-group .custom--nice-select .nice-select .option {
        line-height: 1.5;
        min-height: auto;
        padding: 0.875rem 1.25rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        color: #333333;
        background: #ffffff;
    }
    
    .loan-form-group .custom--nice-select .nice-select .option:hover,
    .loan-form-group .custom--nice-select .nice-select .option.focus,
    .loan-form-group .custom--nice-select .nice-select .option.selected.focus {
        background-color: hsl(var(--base) / 0.08);
        color: #222222;
    }
    
    .loan-form-group .custom--nice-select .nice-select .option.selected {
        font-weight: 600;
        color: hsl(var(--base));
        background-color: hsl(var(--base) / 0.05);
    }
    
    .loan-form-group .custom--nice-select .nice-select .option.disabled {
        color: #999999;
    }
    
    /* Submit Button */
    .loan-form-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 3rem;
    }
    
    .loan-submit-btn {
        background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base) / 0.85));
        color: hsl(var(--white));
        border: none;
        padding: 1rem 3.5rem;
        border-radius: 50px;
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px hsl(var(--base) / 0.3);
    }
    
    .loan-submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px hsl(var(--base) / 0.4);
    }
    
    .loan-submit-btn .btn-icon {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }
    
    .loan-submit-btn:hover .btn-icon {
        transform: translateX(5px);
    }
    
    .loan-back-btn {
        background: #ffffff;
        color: #6c757d;
        border: 1.5px solid #d0d7de;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .loan-back-btn:hover {
        background: #f8f9fa;
        border-color: #b3bac1;
        color: #495057;
        transform: translateY(-2px);
    }
    
    .loan-form-note {
        margin-top: 1.5rem;
        font-size: 0.9375rem;
        color: hsl(var(--dark) / 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .loan-form-note i {
        color: hsl(var(--base));
        font-size: 1.125rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .loan-app-title {
            font-size: 2rem;
        }
        
        .loan-steps {
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .loan-step-line {
            width: 3px;
            height: 40px;
            margin: 0;
            margin-bottom: 0;
        }
        
        .loan-form-card-header {
            flex-direction: column;
            text-align: center;
        }
        
        .loan-form-card-body {
            padding: 2rem 1.5rem;
        }
        
        .loan-form-actions {
            flex-direction: column-reverse;
            gap: 1rem;
        }
        
        .loan-submit-btn,
        .loan-back-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

