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
        ->select('codes.id as cid', 'plans.*', 'user_info.*', 'codes.codes as code', 'codes.amount as am')
        ->where('codes.user_id', CRUDBooster::myId())->get();

        $userId = CRUDBooster::myId();
        $plan = DB::table('subscriptions')
        ->join('plans', 'plans.id', '=', 'subscriptions.stripe_plan')
        ->select('plans.id as pid')
        ->where('user_id', $userId)
        ->first();
        // dd($codes);

        return view('code.list', compact('codes', 'plan'));
    }

    public function generate_code(Request $request){
        $userId = CRUDBooster::myId();
        $number = rand(10,100);
        $t=time();
        $random = $userId.$number.''.$t;
        
        $plan_id = $request->plan_id;
        DB::table('codes')->insert([
            'codes'=> $random,
            'user_id' => $userId,
            'plan_id' => $plan_id,
            'status' => 1,
            'created_at' => \Carbon\Carbon::now(),
            'amount' =>$request->code_for,
        ]);
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
