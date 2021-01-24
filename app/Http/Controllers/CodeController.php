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

        $user_info = DB::table('user_info')->where('user_id', CRUDBooster::myId())->first();
        if($user_info->status == 0){
            return back()->with('msg', 'Your account is not active.');
        }
        $userId = CRUDBooster::myId();
        $number = rand(10,100);
        $t=time();
        $random = $userId.$number.''.$t;
        $plan_id = $request->plan_id;

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
}
