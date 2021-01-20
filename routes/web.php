<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('/register-now', 'CustomRegisterController@index');
Route::post('/user-registration', 'CustomRegisterController@register');
Route::get('payment-option', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');
Route::post('update-user-info', 'HomeController@user_update');
Route::get('active-account', 'HomeController@active_account');
Route::get('get-subscription-list/', 'StripePaymentController@get_active_plan');

Route::get('/make', function () {
    $plan = app('rinvex.subscriptions.plan')->create([
    'name' => 'Pro',
    'description' => 'Pro plan',
    'price' => 9.99,
    'signup_fee' => 1.99,
    'invoice_period' => 1,
    'invoice_interval' => 'month',
    'trial_period' => 15,
    'trial_interval' => 'day',
    'sort_order' => 1,
    'currency' => 'USD',
]);
});
