<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use CRUDBooster;


class CodeController extends Controller
{
    public function codes(Request $request){
        $codes =  DB::table('codes')
        ->join('plans', 'plans.id', '=', 'codes.plan_id')
        ->join('user_info', 'user_info.user_id', '=', 'codes.user_id')
        ->select('codes.id as cid', 'plans.*', 'user_info.*', 'codes.codes as code', 'codes.amount as am', 'codes.status as status')
        ->where('codes.user_id', CRUDBooster::myId())
        ->get();

        $userId = CRUDBooster::myId();
        $plan = DB::table('subscriptions')
        ->join('plans', 'plans.id', '=', 'subscriptions.stripe_plan')
        ->select('plans.id as pid')
        ->where('user_id', $userId)
        ->first();
        // dd($codes);
        $user_info = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();

        // dd($user_info);

        return view('code.list', compact('codes', 'plan', 'user_info'));
    }

    public function generate_code(Request $request){

        $chck = DB::table('subscriptions')
        ->join('plans', 'plans.id', '=', 'subscriptions.stripe_plan')
        ->where('subscriptions.user_id', CRUDBooster::myId())
        ->where('deletion_status', 0)
        ->first();

        // dd($chck);

        $data = DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->first();
        // dd($data);
        $user_active_check = DB::table('subscriptions')->where('deletion_status', 0)->where('user_id', CRUDBooster::myId())->first();
        $user_info = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();
        if($user_info->status == 0){
            return back()->with('msg', 'Your account is not active.');
        }
        $userId = CRUDBooster::myId();
        $number = rand(10,100);
        $t=time();
        $random = $userId.$number.''.$t;
        $plan_id = $request->plan_id;

        if ($user_active_check) {

        if($chck->signup_fee >= $data->generated_code && $data->code_balance >= $request->code_for ){
            DB::table('codes')->insert([
                'codes'=> $random,
                'user_id' => $userId,
                'plan_id' => $plan_id,
                'status' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'amount' =>$request->code_for,
            ]);
            
            if ($data) {
                $prev = $data->generated_code;
                $total = $request->code_for + $prev;
                // dd($data->code_balance);

                $balance = $data->code_balance - $request->code_for;
                DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->update([
                    'user_id' => $userId,
                    'code_balance' => $balance,
                    'used_code' => '0',
                    'generated_code'=> $total
                ]);
                
            }else {
                DB::table('generated_codes')->insert([
                    'user_id' => $userId,
                    'code_balance' => $chck->signup_fee,
                    'used_code' => '0',
                    'generated_code'=> $request->code_for
                ]);
            }
        }else if(!$data){
            DB::table('codes')->insert([
                'codes'=> $random,
                'user_id' => $userId,
                'plan_id' => $plan_id,
                'status' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'amount' =>$request->code_for,
            ]);
            DB::table('generated_codes')->insert([
                'user_id' => $userId,
                'code_balance' => $chck->signup_fee - $request->code_for,
                'used_code' => '0',
                'generated_code'=> $request->code_for
            ]);
            
        }
        else{
            return back()->with('msg', 'Please buy a bundle to generate more codes.');
        }
        }else {
           return back()->with('msg', 'You dont have any active plan. ');
        }

        return back()->with('msg', 'Code generated.');
    }

    public function inactive($id){
        DB::table('codes')->where('id', $id)->update([
            'status' => 0,
        ]);
        return back()->with('msg', 'Code InActivated.');
    }
    public function active($id){
        DB::table('codes')->where('id', $id)->update([
            'status' => 1,
        ]);
        return back()->with('msg', 'Code Activated.');
    }


    public function use_code(Request $request){
        return view('code.use_code');
    }

    public function used_code(Request $request){
        // dd($request);
        $this->validate($request,[
         'code'=>'required',
         'secret'=>'required'
        ]);

        $data = DB::table('used_codes')->where('code', $request->code)->first();
       
        if ($data) {
            return back()->with('warning', 'Code already used.');
        }else{
            $exist = DB::table('codes')->where('codes', $request->code)->first();
            $used = DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->first();
            $total = $used->used_code + $exist->amount;
            if($exist) {
                if ($total <= $used->generated_code) {

                    $user_exist = DB::table('user_info')->where('secret_code', $request->secret)->first();
                    $rec_user = DB::table('generated_codes')->where('user_id', $user_exist->user_id)->first();

                    if ($user_exist && $user_exist->status == 1) {
                        
                        $total_bal = $rec_user->code_balance + $exist->amount;
                        

                        DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->update([
                            'used_code'=> $total,
                        ]);

                        DB::table('generated_codes')->insert([
                            'code_balance'=> $total_bal,
                            'user_id' => $user_exist->user_id
                        ]);

                        $code_ex = DB::table('codes')->where('codes', $request->code)->first();
                        
                        DB::table('codes')->insert([
                            'codes'=> $request->code,
                            'user_id' => $user_exist->user_id,
                            'plan_id' => $code_ex->plan_id,
                            'status' => 1,
                            'created_at' => \Carbon\Carbon::now(),
                            'amount' =>$code_ex->amount,
                        ]);

                        DB::table('used_codes')->insert([
                            'code' => $request->code,
                            'receiver_code' => $request->secret,
                            'created_at' => \Carbon\Carbon::now(),
                        ]);

                        // dd();

                    }else{
                        return back()->with('warning', 'User does not exist or not active..');
                    }
                    
                }else{
                   return back()->with('warning', 'You already used all generated code.');
                }
            }else{
                return back()->with('warning', 'This code is not valid.');
            }
        }
        return back()->with('status', 'Transaction successfull.');
    }

    public function bonus(Request $request){
        $codes =  DB::table('codes')
        ->join('plans', 'plans.id', '=', 'codes.plan_id')
        ->join('user_info', 'user_info.user_id', '=', 'codes.user_id')
        ->select('codes.id as cid', 'plans.*', 'user_info.*', 'codes.codes as code', 'codes.amount as am', 'codes.status as status')
        ->where('codes.user_id', CRUDBooster::myId())->get();
        $user_info = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();
        $bonus = DB::table('refferal_bonus')->where('user_id', CRUDBooster::myId())->first();
        return view('code.bonus', compact('codes', 'user_info', 'bonus'));
    }

    public function generate_bonus_code(Request $request){
        $user_active_check = DB::table('subscriptions')->where('deletion_status', 0)->where('user_id', CRUDBooster::myId())->first();
        if ($user_active_check) {
            # code...
            $daily = DB::table('daily_limits')->where('user_id', CRUDBooster::myId())
            ->whereDate('created_at', \Carbon\Carbon::today())->get()->sum('amount');

            $data = DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->first();
            $bonus = DB::table('refferal_bonus')->where('user_id', CRUDBooster::myId())->first();
            // dd($bonus);

            $chck = DB::table('subscriptions')
            ->join('plans', 'plans.id', '=', 'subscriptions.stripe_plan')
            ->where('subscriptions.user_id', CRUDBooster::myId())
            ->where('deletion_status', 0)
            ->first();


            if ($daily<1000) {
                DB::table('daily_limits')->insert([
                   'user_id'=>CRUDBooster::myId(),
                   'amount'=>$request->code_for,
                   'created_at'=> \Carbon\Carbon::today()
                ]);
                 DB::table('refferal_bonus')->where('user_id', CRUDBooster::myId())->update([
                    'bonus' => $bonus->bonus - $request->code_for,
                
                ]);

                $prev = $data->generated_code;
                $total = $request->code_for + $prev;
                // dd($data->code_balance);
                $userId = CRUDBooster::myId();
                $number = rand(10,100);
                $t=time();
                $random = $userId.$number.''.$t;
                $plan_id = $request->plan_id;

                DB::table('codes')->insert([
                'codes'=> $random,
                'user_id' => $userId,
                'plan_id' => $user_active_check->stripe_plan,
                'status' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'amount' =>$request->code_for,
                ]);

                if ($data) {
                $prev = $data->generated_code;
                $total = $request->code_for + $prev;
                // dd($data->code_balance);

                $balance = $data->code_balance - $request->code_for;
                DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->update([
                    'user_id' => $userId,
                    'code_balance' => $balance,
                    'used_code' => '0',
                    'generated_code'=> $total
                ]);
                
                
                }else {
                    DB::table('generated_codes')->insert([
                        'user_id' => $userId,
                        'code_balance' => $chck->signup_fee,
                        'used_code' => '0',
                        'generated_code'=> $request->code_for
                    ]);
                }

                return back()->with('msg', 'Code Generated successfull.');
            }else {
                # code...
                return back()->with('warning', 'You have reach your daily spending limit');
            }

        }else {
            # code...
            return back()->with('warning', 'You dont have any active plan.');
        }
    }

    public function user_bonus(Request $request){
        $users = DB::table('user_info')->where('user_type', 'BOA')->where('status', 1)->get();
        // dd($users);
        return view('user.bonus', compact('users'));
    }

    public function sent_user_bonus(Request $request){

        // dd($request);
         $this->validate($request,[
         'bonus'=>'required',
        ]);


        for ($i = 0; $i < count($request->users); $i++) {
            $old = DB::table('generated_codes')->where('user_id',$request->users[$i])->first();

                DB::table('generated_codes')->where('user_id',$request->users[$i])
                ->update([
                    'code_balance' => $request->bonus_amount + $old->code_balance,
                ]);
            }
        return back()->with('msg', 'Bonus sent to users.');
    }

    public function subscription(Request $request){
        $plan = DB::table('plans')->where('id', $request->plan_id)->first();
        $balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->get()->sum('balance');

        // dd($balance);

        if ($balance >=  $plan->price) {
            $data = DB::table('subscriptions')->insert([
            'user_id'=>  CRUDBooster::myId(),
            'name'=> $plan->name,
            'stripe_id'=> $request->plan_id,
            'stripe_status'=> 1,
            'stripe_plan'=> $request->plan_id,
            'quantity'=> 1,
            'trial_ends_at'=> \Carbon\Carbon::now(),
            'ends_at'=> \Carbon\Carbon::now(),
            'updated_at'=> \Carbon\Carbon::now(),
        ]);


        $balance = DB::table('main_balances')
        ->where('user_id', CRUDBooster::myId())
        ->where('balance', '>=', $plan->price)->first();

        $total = $balance->balance - $plan->price;

        DB::table('main_balances')
        ->where('user_id', CRUDBooster::myId())
        ->where('balance', '>=', $plan->price)
        ->update( 
            [
                'balance' => $total
            ]
        );

        }else {
            return back()->with('warning', 'You don\'t have sufficient balance to subscribe this plan.');
        }

        

        return back()->with('msg', 'Thanks for subscribing '. $plan->name);
    }

    public function faq_page(Request $request){
        $page = DB::table('faqs')->first();

        return view('faq_add', compact('page'));
    }


    public function send_money(Request $request){

        return view('code.money');
    }
   
    public function bonus_send_money(Request $request){

        return view('code.bouns_money');
    }
    
    public function sent_money(Request $request){
        $this->validate($request,[
         'amount'=>'required',
         'secret'=>'required'
        ]);

        $exist = DB::table('user_info')->where('secret_code', $request->secret)->first();
        $prev_balance = DB::table('main_balances')->where('user_id', $exist->user_id)->first();
        $user_balance = DB::table('main_balances')->where('user_id', CRUDBooster::myId())->first();

        $update = $prev_balance->balance + $request->amount;

        $minus = $user_balance->balance - $request->amount;

        if($exist){

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

                return back()->with('status', 'Fund transfer successfull.');
            }else {
               return back()->with('warning', 'You don\'t have sufficient balance');
            }
            
        }else {
            return back()->with('warning', 'Receiver User does not exist.');
        }

        // dd($exist);
    }
}
