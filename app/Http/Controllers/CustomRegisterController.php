<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class CustomRegisterController extends Controller
{
    public function index(Request $request){
        $id = CRUDBooster::myId();
        if ($id) {
            # code...
            return redirect('admin');
        }else {
            # code...
            
            $previlage = DB::table('cms_privileges')->where('is_superadmin', 0)->get();
            return view('auth.register', compact('previlage'));
        }
    }
   
   
    public function register(Request $request){
        $this->validate($request,[
         'first_name'=>'required',
         'email'=>'required',
         'security_questions'=>'required',
         'question_answer'=>'required', 
         'user_type'=>'required',
         'password' => 'required|min:3|confirmed',
         'password_confirmation' => 'required|min:3'
      ]);
      $password = $request->password_confirmation; 
      $hashed = Hash::make($password);
      
      $user = $request->user_type;
    //   dd($user);

      if($user == 2){
          $type = 'BOA';
      }else{
          $type = 'RA';
      }

      $id= DB::table('cms_users')->insertGetId([
          'name'=> $request->first_name,
          'email'=> $request->email,
          'password'=> $hashed,
          'status'=> 'Active',
          'id_cms_privileges'=>$request->user_type
      ]);

      $rand = $id.'-'.substr(uniqid('', true), -6);

    //   dd($rand);

      $info = DB::table('user_info')->insertGetId([
          'user_id'=> $id,
          'first_name'=> $request->first_name,
          'last_name'=> $request->last_name,
          'phone'=> $request->phone,
          'question_answer'=> $request->question_answer,
          'security_questions'=>$request->security_questions,
          'referral'=> $id.'-' .uniqid(),
          'secret_code'=> $rand,
          'user_type' => $type,
          'alt_phone' => $request->alt_phone,
      ]);

        if ($request->ref_code) {
          # code...
           DB::table('referral_relationships')->insert([
          'referral_link_id' => $request->ref_code,
          'user_id' => $id
        ]);

        $user_in= DB::table('user_info')->where('referral', $request->ref_code)->first();
        // dd($user_in);

        $bonus = DB::table('refferal_bonus')->where('user_id', $user_in->user_id)->first();

        if ($bonus) {
            DB::table('refferal_bonus')->where('user_id', $user_in->user_id)->update([
                'bonus' => 500 + $bonus->bonus,
            
            ]);
        }else {
            DB::table('refferal_bonus')->insert([
                'bonus' => 500,
                'user_id' => $user_in->user_id,
                'used'=> 0,
                'created_at' => \Carbon\Carbon::now(),
            ]);
            DB::table('refferal_bonus')->insert([
                'bonus' => 500,
                'user_id' => $id,
                'used'=> 0,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }
      
    }

     

      $user_in = DB::table('user_info')->where('referral', $request->ref_code)->first();
      $user_active_check = DB::table('subscriptions')->where('deletion_status', 0)->where('user_id', $user_in->user_id)->first();
      
        return redirect('setup-account/'.$info);
    }

    public function setup_account($id){
        $info = DB::table('user_info')->where('id', $id)->first();
        return view('setup_account', compact('info'));
    }
  
    public function account_setup(Request $request){
        // dd($request);
        $info = DB::table('user_info')->where('id', $request->user_id)->update([
            'account_type' => $request->account_type,
            'account_type_2nd'=> $request->account_type,
            'saving_time' => $request->saving_time,
        ]);
        return redirect('admin/login');
    }

    public function reg_refferal($id){

        $previlage = DB::table('cms_privileges')->where('is_superadmin', 0)->get();
        $ref_code = $id;
        return view('auth.register', compact('previlage', 'ref_code'));

    }

    public function get_ref_link(Request $request){
        $id = CRUDBooster::myId();
        $user = DB::table('user_info')->where('user_id', $id)->first();
        // $ref_code = $id;
        return view('code.ref_link', compact('user'));

    }
}
