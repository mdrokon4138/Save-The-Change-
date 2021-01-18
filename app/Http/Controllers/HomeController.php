<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class HomeController extends Controller
{
    public function index(Request $request){
        $plans = DB::table('plans')->get();
        // dd($plans);

        return view('welcome', compact('plans'));
    }
    
   
    public function active_account(Request $request){
        $plans = DB::table('plans')->get();
        // dd($plans);

        return view('stripe', compact('plans'));
    }
    
    
    public function user_update(Request $request){
        // dd($request);
        $account_type =$request->account_type;
        $account_type_2nd =$request->account_type_2nd;
        $user_id = $request->user_id;
        $saving_time =$request->saving_time;
        $saving_time_manual =$request->saving_time_manual;

        if($saving_time_manual){
            $time_period = $saving_time_manual;
        }else{
            $time_period = $saving_time;
        }
        $user = DB::table('user_info')->where('user_id',$user_id )->update([
            'account_type' =>$account_type ,
            'account_type_2nd' =>$account_type_2nd ,
            'saving_time' =>$time_period ,
            'bonus' => 500,
            'user_type'=>$account_type
        ]);

        return redirect()->back()->with('msg', 'Welcome to Save-The-Change you have been credited with ₦500 bonus. Want to spend it? Activate your account!');
    }


}
