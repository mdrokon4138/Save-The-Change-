<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use CRUDBooster;


class MainBalanceController extends Controller
{
    public function deposit(Request $request){
        return view('balance.deposit');
    }


    public function withdraw(Request $request){
        $user = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();


        $da = \Carbon\Carbon::parse($user->updated_at)->diffInDays(\Carbon\Carbon::parse(\Carbon\Carbon::now()));

        if ($da == '30') {
            # code...
            return view('balance.withdraw');

        }else {
            // return view('balance.withdraw');
            return back()->with('warning', 'Please wait for 30 days to withdraw.');
        }
        // dd($da);
    }

    public function withdraw_now(Request $request){
        $data = $this->validate($request,[
         'bank_name'=>'required',
         'account_number'=>'required',
         'amount'=>'required',
        ]);
        // dd($request);
        \App\Withdraw::create($data + ['user_id' => CRUDBooster::myId()]);
        return back()->with('msg', 'Your withdraw request is submited.');
    }
}
