<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use Session;
use Stripe;
use DB;
use CRUDBooster;
use Carbon\Carbon;
use App\Subscription;
use Paystack;
use Illuminate\Support\Facades\Redirect;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        $plans = DB::table('plans')->get();
        return view('stripe', compact('plans'));
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
       
        $user = CRUDBooster::myId();
        $name = CRUDBooster::myName();
        // dd($user);
        // dd($request->id);

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => $request->deposit_amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Save the Change" 
        ]);
        $user = CRUDBooster::myId();
        $name = CRUDBooster::myName();

        $chk = DB::table('main_balances')->where('user_id', $user)->first();

        if ($chk) {
            $total = $chk->balance + $request->deposit_amount;
            $balance = DB::table('main_balances')->where('user_id', $user)->update([
            'balance'=> $total,
            ]);
        }else {
            $balance = DB::table('main_balances')->insert([
            'user_id'=>  $user,
            'balance'=> $request->deposit_amount,
            'created_at'=> Carbon::now(),
            ]);
        }

        
        $data = DB::table('user_info')->where('user_id', $user)->update([
            'status'=>  1,
            'updated_at'=> Carbon::now(),
           
        ]);
  
        Session::flash('success', 'Welcome your account is now active..!');
          
        return back();
    }

    public function get_active_plan(Request $request){

        // $data = App\User::with('active_plans')->get();

        $user = CRUDBooster::myId();
        $user_plan = DB::table('subscriptions')
        ->join('user_info', 'user_info.user_id', '=', 'subscriptions.user_id')
        ->join('plans', 'plans.id', '=', 'subscriptions.stripe_plan')
        ->where('subscriptions.user_id', $user)
        ->select('subscriptions.*', 'user_info.*', 'plans.*')
        ->get();
        // dd($user_plan);
        return view('user.active_plan', compact('user_plan'));
    }

    public function redirectToGateway()
    {
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }        
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        $amount = $paymentDetails['data']['amount'];
        $user = CRUDBooster::myId();

        $chk = DB::table('main_balances')->where('user_id', $user)->first();

        $de = $amount / 100 ;

        // dd($de);

        $depo = $chk->balance + $de;

        if ($chk) {
            $balance = DB::table('main_balances')->where('user_id', $user)->update([
            'balance'=> $depo,
            ]);
        }else {
            # code...
            $balance = DB::table('main_balances')->insert([
            'balance'=> $depo,
            'user_id'=>$user,
            'created_at'=> Carbon::now(),
            ]);
        }

        $data = DB::table('user_info')->where('user_id', $user)->update([
            'status'=>  1,
            'updated_at'=> Carbon::now(),
           
        ]);

        Session::flash('success', 'Deposit successful!');
          
        return back();

        // dd($paymentDetails);
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }
}