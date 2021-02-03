<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class TransactionController extends Controller
{
    public function bonus_sent_money(Request $request){

        // dd($request);

        $this->validate($request,[
         'amount'=>'required',
         'secret'=>'required'
        ]);
        $exist = DB::table('user_info')->where('secret_code', $request->secret)->first();
        $daily = DB::table('daily_limits')->where('user_id', CRUDBooster::myId())->whereDate('created_at', \Carbon\Carbon::today())->get()->sum('amount');
        $bonus = DB::table('refferal_bonus')->where('user_id', CRUDBooster::myId())->first();

        if($exist){
                
            if ($daily<1000) {
                
                $prev_balance = DB::table('main_balances')->where('user_id', $exist->user_id)->first();
                $user_balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();

                $update = $prev_balance->balance + $request->amount;

                $minus = $user_balance->balance - $request->amount;
                if ($user_balance->balance > $request->amount) {
                    DB::table('main_balances')->where('user_id', $exist->user_id)->update([
                        'balance'=> $update
                    ]);
                    
                    DB::table('main_balances')->where('user_id', CRUDBooster::myId())->update([
                        'balance'=> $minus
                    ]);
                    
                    DB::table('transactions')->insert([
                        'amount'=> $request->amount,
                        'sender_id'=> CRUDBooster::myId(),
                        'receiver_id'=> $exist->user_id,
                        'updated_at'=> \Carbon\Carbon::now(),
                    ]);

                    DB::table('daily_limits')->insert([
                        'user_id'=>CRUDBooster::myId(),
                        'amount'=>$request->amount,
                        'created_at'=> \Carbon\Carbon::today()
                    ]);
                    DB::table('refferal_bonus')->where('user_id', CRUDBooster::myId())->update([
                        'bonus' => $bonus->bonus - $request->amount,
                    
                    ]);
                    
                    return back()->with('status', 'Fund transfer successfull.');
                }else {
                    return back()->with('warning', 'You don\'t have sufficient balance');
                }
            }else {
                # code...
                return back()->with('warning', 'You have reach your daily spending limit');
            }
                
                
        }else {
            return back()->with('warning', 'Receiver User does not exist.');
        }
    }

    public function send_change_code(Request $request){

        // dd($request);

        $this->validate($request,[
         'code'=>'required',
         'secret'=>'required'
        ]);
        $code_used = DB::table('user_changes')->where('code', $request->code)->first();

        if ($code_used) {
            # code...
            return back()->with('warning', 'Code Already used. Try another code.');
        }

        $exist = DB::table('user_info')->where('secret_code', $request->secret)->first();
        $amount = DB::table('codes')->where('codes', $request->code)->first();
       
        if($exist && $amount){
                
             $prev_balance = DB::table('main_balances')->where('user_id', $exist->user_id)->first();
                $user_balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();

                $update = $prev_balance->balance + $amount->amount;

                $minus = $user_balance->balance - $amount->amount;
                if ($user_balance->balance > $amount->amount) {
                    DB::table('main_balances')->where('user_id', $exist->user_id)->update([
                        'balance'=> $update
                    ]);
                    
                    DB::table('main_balances')->where('user_id', CRUDBooster::myId())->update([
                        'balance'=> $minus
                    ]);
                    
                    DB::table('codes')->where('codes',$request->code)->update([
                        'status'=> 0
                    ]);
                    
                    DB::table('transactions')->insert([
                        'amount'=> $amount->amount,
                        'sender_id'=> CRUDBooster::myId(),
                        'receiver_id'=> $exist->user_id,
                        'updated_at'=> \Carbon\Carbon::now(),
                    ]);
                    
                    DB::table('user_changes')->insert([
                        'code'=> $request->code,
                        'updated_at'=> \Carbon\Carbon::now(),
                    ]);

                    return back()->with('status', 'Fund transfer successfull.');
                }else {
                    return back()->with('warning', 'You don\'t have sufficient balance');
                }
                
        }else {
            return back()->with('warning', 'Receiver User  or Code does not exist.');
        }
    }
}
