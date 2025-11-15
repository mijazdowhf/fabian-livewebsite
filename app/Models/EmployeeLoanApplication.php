<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLoanApplication extends Model
{
    protected $fillable = [
        'user_id',
        'agent_id',
        'application_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'age',
        'tax_code',
        'email',
        'mobile',
        'city',
        'province',
        'marital_status',
        'family_status',
        'family_members',
        'employer_name',
        'contract_type',
        'monthly_net_income',
        'employment_length_years',
        'employment_start_date',
        'loan_amount',
        'loan_duration_months',
        'loan_purpose',
        'loan_purpose_other',
        'current_financing_details',
        'current_financing_remaining',
        'doc_certificate_residency',
        'doc_family_status',
        'doc_marital_status',
        'doc_valid_id',
        'doc_health_card',
        'doc_residence_permit',
        'doc_passport',
        'doc_cu_2025',
        'doc_payslips',
        'doc_bank_statement',
        'doc_transactions_30days',
        'doc_employment_contract',
        'doc_inps_statement',
        'doc_loan_agreement',
        'doc_isee',
        'privacy_authorization',
        'status',
        'current_step',
        'admin_notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'employment_start_date' => 'date',
        'monthly_net_income' => 'decimal:2',
        'loan_amount' => 'decimal:2',
        'current_financing_remaining' => 'decimal:2',
        'privacy_authorization' => 'boolean',
        'age' => 'integer',
        'family_members' => 'integer',
        'employment_length_years' => 'integer',
        'loan_duration_months' => 'integer',
        'current_step' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function getPayslipsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPayslipsAttribute($value)
    {
        $this->attributes['doc_payslips'] = json_encode($value);
    }
    
    // Generate unique application ID
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->application_id)) {
                $model->application_id = 'EL' . date('Ymd') . strtoupper(substr(uniqid(), -6));
            }
        });
    }
}
