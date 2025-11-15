<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert email templates for loan and mortgage application account creation
        DB::table('notification_templates')->insert([
            [
                'act' => 'LOAN_APPLICATION_ACCOUNT_CREATED',
                'name' => 'Loan Application - Account Created',
                'subject' => 'Your Account Has Been Created - {{name}}',
                'email_body' => '<div style="font-family: Arial, sans-serif;">
                    <h2 style="color: #333;">Welcome to {{site_name}}!</h2>
                    <p>Dear {{name}},</p>
                    <p>Thank you for submitting your loan application. We have created an account for you to track your application status.</p>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h3 style="margin-top: 0;">Your Login Credentials</h3>
                        <p><strong>Username:</strong> {{username}}</p>
                        <p><strong>Temporary Password:</strong> {{password}}</p>
                        <p><strong>Login URL:</strong> <a href="{{login_url}}">{{login_url}}</a></p>
                    </div>
                    <p style="color: #dc3545;"><strong>Important:</strong> Please login and change your password immediately for security.</p>
                    <p>You can now track your loan application status from your dashboard.</p>
                    <p>Best regards,<br>{{site_name}} Team</p>
                </div>',
                'sms_body' => 'Account created for your loan application. Username: {{username}}, Password: {{password}}. Login at {{login_url}}',
                'shortcodes' => '{"username":"User login username","password":"Temporary password","email":"User email address","name":"User full name","login_url":"Login page URL"}',
                'email_status' => 1,
                'sms_status' => 1,
                'push_status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'act' => 'MORTGAGE_APPLICATION_ACCOUNT_CREATED',
                'name' => 'Mortgage Application - Account Created',
                'subject' => 'Your Account Has Been Created - {{name}}',
                'email_body' => '<div style="font-family: Arial, sans-serif;">
                    <h2 style="color: #333;">Welcome to {{site_name}}!</h2>
                    <p>Dear {{name}},</p>
                    <p>Thank you for submitting your mortgage application. We have created an account for you to track your application status.</p>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h3 style="margin-top: 0;">Your Login Credentials</h3>
                        <p><strong>Username:</strong> {{username}}</p>
                        <p><strong>Temporary Password:</strong> {{password}}</p>
                        <p><strong>Login URL:</strong> <a href="{{login_url}}">{{login_url}}</a></p>
                    </div>
                    <p style="color: #dc3545;"><strong>Important:</strong> Please login and change your password immediately for security.</p>
                    <p>You can now track your mortgage application status from your dashboard.</p>
                    <p>Best regards,<br>{{site_name}} Team</p>
                </div>',
                'sms_body' => 'Account created for your mortgage application. Username: {{username}}, Password: {{password}}. Login at {{login_url}}',
                'shortcodes' => '{"username":"User login username","password":"Temporary password","email":"User email address","name":"User full name","login_url":"Login page URL"}',
                'email_status' => 1,
                'sms_status' => 1,
                'push_status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('notification_templates')->whereIn('act', [
            'LOAN_APPLICATION_ACCOUNT_CREATED',
            'MORTGAGE_APPLICATION_ACCOUNT_CREATED'
        ])->delete();
    }
};
