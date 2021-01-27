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
        ->where('codes.user_id', CRUDBooster::myId())->get();

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

                    if ($user_exist && $rec_user) {
                        
                        $total_bal = $rec_user->code_balance + $exist->amount;
                        DB::table('generated_codes')->where('user_id', $user_exist->user_id)->update([
                        'code_balance'=> $total_bal,
                        ]);

                        DB::table('generated_codes')->where('user_id', CRUDBooster::myId())->update([
                            'used_code'=> $total,
                        ]);

                        DB::table('used_codes')->insert([
                            'code' => $request->code,
                            'receiver_code' => $request->secret,
                            'created_at' => \Carbon\Carbon::now(),
                        ]);
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
            $bonus = DB::table('refferal_bonus')->where('user_id', $user_in->user_id)->first();
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
                return back()->with('warning', 'You have reached your daily limits.');
            }

        }else {
            # code...
            return back()->with('warning', 'You dont have any active plan.');
        }
    }
}
