<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanInquiry extends Model
{
    protected $fillable = [
        'user_id',
        'agent_id',
        'application_id',
        'loan_type',
        'first_name',
        'last_name',
        'date_of_birth',
        'tax_code',
        'email',
        'mobile',
        'city',
        'province',
        'marital_status',
        'applicant_type',
        'age',
        'family_members',
        'occupation',
        'employment_duration_type',
        'contract_type',
        'monthly_net_income',
        'employment_length_years',
        'application_type',
        'loan_purpose',
        'loan_purpose_other',
        'has_current_financing',
        'current_financing_details',
        'current_financing_remaining',
        'privacy_authorization',
        'status',
        'current_step'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_net_income' => 'decimal:2',
        'current_financing_remaining' => 'decimal:2',
        'has_current_financing' => 'boolean',
        'privacy_authorization' => 'boolean',
        'age' => 'integer',
        'family_members' => 'integer',
        'employment_length_years' => 'integer',
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
    
    // Generate unique application ID
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->application_id)) {
                $model->application_id = 'LN' . date('Ymd') . strtoupper(substr(uniqid(), -6));
            }
        });
    }
}


