<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingMortgageApplication extends Model
{
    protected $fillable = [
        'user_id','agent_id','application_id','first_name','last_name','date_of_birth','age','tax_code','email','mobile','city','province','marital_status','family_status','family_members','business_name','vat_number','business_type','business_years','annual_revenue','monthly_net_income','mortgage_amount','mortgage_duration_months','property_type','property_location','mortgage_purpose','current_financing_details','current_financing_remaining','doc_certificate_residency','doc_family_status','doc_marital_status','doc_valid_id','doc_health_card','doc_residence_permit','doc_tax_return_2025','doc_tax_return_2024','doc_electronic_receipt_2025','doc_electronic_receipt_2024','doc_vat_assignment','doc_bank_statement','doc_transactions_30days','doc_loan_agreement','privacy_authorization','status','current_step','admin_notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_net_income' => 'decimal:2',
        'annual_revenue' => 'decimal:2',
        'mortgage_amount' => 'decimal:2',
        'current_financing_remaining' => 'decimal:2',
        'privacy_authorization' => 'boolean',
        'age' => 'integer',
        'family_members' => 'integer',
        'business_years' => 'integer',
        'mortgage_duration_months' => 'integer',
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
                $model->application_id = 'MG' . date('Ymd') . strtoupper(substr(uniqid(), -6));
            }
        });
    }
}
