<?php

Route::group([
            'middleware' => ['web', '\crocodicstudio\crudbooster\middlewares\CBBackend'],
        ], function () {


    Route::get('payment-option', 'StripePaymentController@stripe');
    Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');
    Route::post('update-user-info', 'HomeController@user_update');
    Route::get('active-account', 'HomeController@active_account');
    Route::get('get-subscription-list/', 'StripePaymentController@get_active_plan');
    Route::get('generate-codes/', 'CodeController@codes');
    Route::post('generate-code-now', 'CodeController@generate_code')->name('generate');
    Route::get('/codes/inactive/{id}', 'CodeController@inactive');
    Route::get('/codes/active/{id}', 'CodeController@active');

    Route::get('use-codes', 'CodeController@use_code');
    Route::get('send-change', 'CodeController@send_change');
    Route::post('send-change', 'TransactionController@send_change_code');
    Route::post('used-code', 'CodeController@used_code');
    Route::get('/register-ref={id}', 'CustomRegisterController@reg_refferal');
    Route::get('/get-refferal-link', 'CustomRegisterController@get_ref_link');
    Route::get('bonus-balance', 'CodeController@bonus');
    Route::post('bonus-code-generate', 'CodeController@generate_bonus_code');
    Route::get('sent-users-bonus', 'CodeController@user_bonus');
    Route::post('sent-bonus', 'CodeController@sent_user_bonus');
    Route::get('deposit', 'MainBalanceController@deposit');
    Route::post('subscription','CodeController@subscription');
    Route::get('withdraw-money','MainBalanceController@withdraw');
    Route::post('users-withdraw-request', 'MainBalanceController@withdraw_now');
    Route::post('/pay', 'StripePaymentController@redirectToGateway')->name('pay');
    Route::get('/payment/callback', 'StripePaymentController@handleGatewayCallback');
    Route::get('send-money', 'CodeController@send_money');
    Route::get('send-bonus-money', 'CodeController@bonus_send_money');
    Route::post('send-money', 'CodeController@sent_money');
    Route::post('send-bonus-money', 'TransactionController@bonus_sent_money');
    Route::get('send-bonus-money', 'CodeController@bonus_send_money');
    Route::post('save-settings', 'ContactController@save_setting');
    Route::get('chart-line-ajax', 'ChartController@chartLineAjax');
    Route::get('chart-line-subscription', 'ChartController@subscription');
    Route::get('switch-account', 'ChartController@switch_account');
    Route::post('switch-account', 'ChartController@switch');
    Route::get('emergency-withdraw', 'TransactionController@emergency');
    Route::get('reffer-users', 'TransactionController@reffer_user');
    Route::get('all-reffer-users', 'TransactionController@all_reffer_user');
});
    Route::get('setup-account/{id}', 'CustomRegisterController@setup_account');
    Route::post('setup-account', 'CustomRegisterController@account_setup');

Route::get('faq-page', 'CodeController@faq_page');
Route::get('/', 'HomeController@index');
Route::get('/register-now', 'CustomRegisterController@index');
Route::get('/about-us', 'HomeController@about_us');
Route::get('/contact-us', 'HomeController@contact_us');
Route::get('/terms-condition', 'HomeController@terms');
Route::get('/refund-policy', 'HomeController@refund');
Route::post('/user-registration', 'CustomRegisterController@register');
Route::post('/sendbasicemail','ContactController@basic_email');


Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Config cleared</h1>';
});