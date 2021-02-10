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
        $balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();
        $percentage = DB::table('percentage')->first();
        $defualt_percent = $percentage->amount;
        $default_providents = ($defualt_percent/100)*$request->amount;

        // dd($default_providents);
        
        $data_balance = $request->amount + $default_providents;
        $update = $balance->balance - $data_balance;

        // dd($update);

        if ($balance->balance >= $data_balance) {
            \App\Withdraw::create($data + ['user_id' => CRUDBooster::myId()]);

            $balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->update([
                'balance' => $update
            ]);
            return back()->with('msg', 'Your withdraw request is submited.');


        }else {
            return back()->with('warning', 'You dont have sufficient balance.');
        }
        
    }
}
