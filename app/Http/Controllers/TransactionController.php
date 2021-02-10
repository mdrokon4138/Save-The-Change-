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
         'code'=>'required'
        ]);
        $code_used = DB::table('user_changes')->where('code', $request->code)->first();

        if ($code_used) {
            # code...
            return back()->with('warning', 'Code Already used. Try another code.');
        }
        $amount = DB::table('codes')->where('codes', $request->code)->first();
       
        if($amount){
                
                // $prev_balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();
                $user_balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();

                

                $update = $user_balance->balance + $amount->amount;

                if ($user_balance->balance > 0) {
                    
                    DB::table('main_balances')->where('user_id', CRUDBooster::myId())->update([
                        'balance'=> $update
                    ]);

                    DB::table('codes')->where('codes', $request->code)->update(['status' => 0]);
                    
                    return back()->with('status', 'Fund added successfull.');
                }else {
                    DB::table('main_balances')
                    ->insert([
                        'balance'=> $update,
                        'user_id' => CRUDBooster::myId(),
                        'created_at' => \Carbon\Carbon::now()

                    ]);
                    DB::table('codes')->where('codes', $request->code)->update(['status' => 0]);
                    return back()->with('status', 'Fund added successfull.');
                }
                
        }else {
            return back()->with('warning', 'Code does not exist.');
        }
    }

    public function emergency(Request $request){
        $percentage = DB::table('percentage')->first();
        $balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();
        return view('emergency', compact('balance', 'percentage'));
    }

    public function reffer_user(Request $request){
        $user = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();

        $reffers = DB::table('referral_relationships')
        ->join('cms_users', 'cms_users.id', '=', 'referral_relationships.user_id')
        ->select('cms_users.name as uname', 'referral_relationships.*', 'cms_users.id')
        ->where('referral_relationships.referral_link_id', $user->referral)
        ->get();

        return view('user.reffered_user', compact('reffers'));
    }
    public function all_reffer_user(Request $request){
        $user = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();

        $user_info = DB::table('referral_relationships')
        ->select('referral_link_id', DB::raw('count(*) as total'))
        ->groupBy('referral_relationships.referral_link_id')
        ->get();

        // dd($user_info);
        
        return view('user.all_reffered_user', compact('user_info'));
    }
}
