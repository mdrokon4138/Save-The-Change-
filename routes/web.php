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
Route::get('generate-codes/', 'CodeController@codes');
Route::post('generate-code-now', 'CodeController@generate_code')->name('generate');
Route::get('/codes/inactive/{id}', 'CodeController@inactive');
Route::get('/codes/active/{id}', 'CodeController@active');
Route::get('setup-account/{id}', 'CustomRegisterController@setup_account');
Route::post('setup-account', 'CustomRegisterController@account_setup');
Route::get('use-codes', 'CodeController@use_code');
Route::post('used-code', 'CodeController@used_code');
Route::get('/register-ref={id}', 'CustomRegisterController@reg_refferal');
Route::get('/get-refferal-link', 'CustomRegisterController@get_ref_link');
Route::get('bonus-balance', 'CodeController@bonus');
Route::post('bonus-code-generate', 'CodeController@generate_bonus_code');

