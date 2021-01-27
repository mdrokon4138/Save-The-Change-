<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use Session;
use Stripe;
use DB;
use CRUDBooster;
use Carbon\Carbon;
use App\Subscription;

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
                "amount" => 100 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Save the Change" 
        ]);
        $user = CRUDBooster::myId();
        $name = CRUDBooster::myName();

        $data = DB::table('subscriptions')->insert([
            'user_id'=>  $user,
            'name'=> $name,
            'stripe_id'=> 1,
            'stripe_status'=> 1,
            'stripe_plan'=> $request->id,
            'quantity'=> 1,
            'trial_ends_at'=> Carbon::now(),
            'ends_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
        $data = DB::table('user_info')->where('user_id', $user)->update([
            'status'=>  1,
            'updated_at'=> Carbon::now(),
           
        ]);
  
        Session::flash('success', 'Payment successful!');
          
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
}