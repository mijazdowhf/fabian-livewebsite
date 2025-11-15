<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


use Illuminate\Support\Facades\Mail;

Route::get('/send-test-mail', function () {
    $details = [
        'title' => 'Test Email from DOWHF Technologies',
        'body' => 'This is a test email sent using Laravel mail configuration.'
    ];

    Mail::raw($details['body'], function ($message) use ($details) {
        $message->to('ijazkhandowhf@gmail.com')
                ->subject($details['title']);
    });

    return 'Email has been sent successfully!';
});

// Test route to check user role
Route::get('/check-role', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'authenticated' => true,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'is_agent' => $user->role === 'agent',
            'message' => 'Role: ' . ($user->role ?? 'NULL (regular user)')
        ]);
    }
    return response()->json([
        'authenticated' => false,
        'message' => 'Not logged in'
    ]);
})->name('check.role');

// Cron Route
Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/contact/thanks', 'contactThanks')->name('contact.thanks');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('/', 'index')->name('home');
    Route::get('page/{slug}', 'pages')->name('pages');
    Route::get('blogs', 'blogs')->name('blog');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    
    Route::post('subscribe', 'subscribe')->name('subscribe');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode','maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    //loans
    Route::get('/loan', 'loans')->name('loan');
});

// Loan Application Routes (Personal Loan)
Route::controller('LeadController')->group(function () {
    Route::get('/loan-application', 'loanTypeSelector')->name('loan.type.selector');
    Route::get('/loan/application/success', 'applicationSuccess')->name('loan.application.success');
    Route::get('/personal-loan/apply', 'wizard')->name('lead.wizard');
    Route::post('/personal-loan/step1', 'storeStep1')->name('lead.step1.store');
    Route::get('/personal-loan/step2', 'step2')->name('lead.step2');
    Route::post('/personal-loan/step2', 'storeStep2')->name('lead.step2.store');
    Route::get('/personal-loan/step3', 'step3')->name('lead.step3');
    Route::post('/personal-loan/step3', 'storeStep3')->name('lead.step3.store');
});

// Selling Mortgage Application Routes (Home Mortgage)
Route::controller('SellingMortgageController')->group(function () {
    Route::get('/selling-mortgage/apply', 'wizard')->name('selling.mortgage.wizard');
    Route::post('/selling-mortgage/step1', 'storeStep1')->name('selling.mortgage.step1.store');
    Route::get('/selling-mortgage/step2', 'step2')->name('selling.mortgage.step2');
    Route::post('/selling-mortgage/step2', 'storeStep2')->name('selling.mortgage.step2.store');
    Route::get('/selling-mortgage/step3', 'step3')->name('selling.mortgage.step3');
    Route::post('/selling-mortgage/step3', 'storeStep3')->name('selling.mortgage.step3.store');
    Route::get('/selling-mortgage/step4', 'step4')->name('selling.mortgage.step4');
    Route::post('/selling-mortgage/step4', 'storeStep4')->name('selling.mortgage.step4.store');
});
