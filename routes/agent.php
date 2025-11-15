<?php

use Illuminate\Support\Facades\Route;

// Package purchase routes (without agent.subscribed middleware - needed for initial subscription)
Route::middleware(['auth','agent','check.status','registration.complete'])->name('agent.')->namespace('Agent')->group(function () {
    Route::get('packages', 'PackageController@choose')->name('packages.choose');
    Route::post('packages/select', 'PackageController@select')->name('packages.select');
    Route::get('packages/pay', 'PackageController@pay')->name('packages.pay');
    
    // Payment routes for agents (uses Gateway PaymentController)
    Route::post('deposit/insert', '\\App\\Http\\Controllers\\Gateway\\PaymentController@depositInsert')->name('deposit.insert');
    Route::get('deposit/confirm', '\\App\\Http\\Controllers\\Gateway\\PaymentController@depositConfirm')->name('deposit.confirm');
});

Route::middleware(['auth','agent','check.status','registration.complete','agent.subscribed'])->name('agent.')->namespace('Agent')->group(function () {
    Route::get('dashboard', 'AgentController@dashboard')->name('dashboard');
    Route::get('applications/search', 'AgentController@searchApplication')->name('applications.search');
    Route::get('referrals', 'AgentController@referrals')->name('referrals');
    Route::get('stripe-settings', 'AgentController@stripeSettings')->name('stripe.settings');
    Route::post('stripe-settings/create-account', 'AgentController@createStripeAccount')->name('stripe.create.account');
    Route::get('stripe/onboarding/refresh', 'AgentController@stripeOnboardingRefresh')->name('stripe.onboarding.refresh');
    Route::get('stripe/onboarding/complete', 'AgentController@stripeOnboardingComplete')->name('stripe.onboarding.complete');
    Route::get('commission-history', 'AgentController@commissionHistory')->name('commission.history');
    Route::controller('AccountController')->group(function(){
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'submitProfile')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'submitPassword')->name('password.update');
    });
    
    // Assigned Loans
    Route::controller('LoanController')->prefix('loans')->name('loans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/details/{id}', 'details')->name('details');
    });
    
    // Assigned Employee Loans
    Route::controller('EmployeeLoanController')->prefix('employee-loans')->name('employee.loans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/details/{id}', 'details')->name('details');
    });
    
    // Assigned Home Mortgages
    Route::controller('MortgageController')->prefix('mortgages')->name('mortgages.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/details/{id}', 'details')->name('details');
    });
    
    // Mortgage document upload (separate route for clarity)
    Route::post('mortgage/{id}/upload-document', 'MortgageController@uploadDocument')->name('mortgage.document.upload');
    
    // User Messages
    Route::controller('MessageController')->prefix('messages')->name('messages.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('conversation/{userId}/{type}/{applicationId}', 'conversation')->name('conversation');
        Route::post('reply/{userId}/{type}/{applicationId}', 'sendReply')->name('reply');
    });
    
    // Withdrawals
    Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw.')->group(function () {
        Route::get('/', 'withdrawMoney')->name('index');
        Route::post('/', 'withdrawStore')->name('store');
        Route::get('/preview', 'withdrawPreview')->name('preview');
        Route::post('/preview', 'withdrawSubmit')->name('submit');
        Route::get('/history', 'withdrawHistory')->name('history');
    });
    
    // Alias route for withdraw
    Route::get('withdraw-money', 'WithdrawController@withdrawMoney')->name('withdraw');
});

Route::middleware(['auth','agent'])->name('agent.')->namespace('Agent')->group(function () {
    Route::get('agent-data', 'ProfileController@agentData')->name('data');
    Route::post('agent-data-submit', 'ProfileController@agentDataSubmit')->name('data.submit');
    
    //authorization - for agents (without check.status to prevent loop)
    Route::middleware('registration.complete')->controller('AuthorizationController')->group(function(){
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });
});


