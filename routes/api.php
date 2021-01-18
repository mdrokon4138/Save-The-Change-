<?php

use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\WebProfile;
use PayPal\Api\InputFields;
use PayPal\Api\Transaction;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use DB as DBS;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('create-payment', function () {
    $pay_key = DBS::table('paypal_settings')->first();
    $client_id = $pay_key->client_id;
    $client_secret = $pay_key->client_secret;

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential($client_id, $client_secret)
    );

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $item1 = new Item();
    $item1->setName('Ground Coffee 40 oz')
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setSku("123123") // Similar to `item_number` in Classic API
        ->setPrice(7.5);
    $item2 = new Item();


    $itemList = new ItemList();
    $itemList->setItems(array($item1));

    $details = new Details();
    $details->setShipping(1.2)
        ->setTax(1.3)
        ->setSubtotal(17.50);

    $amount = new Amount();
    $amount->setCurrency("USD")
        ->setTotal(20)
        ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment description")
        ->setInvoiceNumber(uniqid());

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl("http://save.test/")
        ->setCancelUrl("http://save.test/");

    // Add NO SHIPPING OPTION
    $inputFields = new InputFields();
    $inputFields->setNoShipping(1);

    $webProfile = new WebProfile();
    $webProfile->setName('test' . uniqid())->setInputFields($inputFields);

    $webProfileId = $webProfile->create($apiContext)->getId();

    $payment = new Payment();
    $payment->setExperienceProfileId($webProfileId); // no shipping
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

    try {
        $payment->create($apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    return $payment;
});

Route::post('execute-payment', function (Request $request) {
    $pay_key = DBS::table('paypal_settings')->first();
    $client_id = $pay_key->client_id;
    $client_secret = $pay_key->client_secret;

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential($client_id, $client_secret)
    );

    $paymentId = $request->paymentID;
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    $execution->setPayerId($request->payerID);

    // $transaction = new Transaction();
    // $amount = new Amount();
    // $details = new Details();

    // $details->setShipping(2.2)
    //     ->setTax(1.3)
    //     ->setSubtotal(17.50);

    // $amount->setCurrency('USD');
    // $amount->setTotal(21);
    // $amount->setDetails($details);
    // $transaction->setAmount($amount);

    // $execution->addTransaction($transaction);


    // DBS::table('payments')->insert(
    //         [
    //             'payment_id' => Str::random(10),
    //             'payer_id' =>  Auth::id(),
    //             'payer_email'=>Auth::user()->email,
    //             'amount'=> Session::get('total'),
    //             'currency'=>'usd',
    //             'payment_status'=>"Paid",
    //             'package_id'=> Session::get('card_id'),
    //         ]
    //     );

    try {
        $result = $payment->execute($execution, $apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    return $result;
});