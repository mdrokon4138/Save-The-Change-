<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
class CustomRegisterController extends Controller
{
    public function index(Request $request){
        $previlage = DB::table('cms_privileges')->where('is_superadmin', 0)->get();
        return view('auth.register', compact('previlage'));
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
      $info = DB::table('user_info')->insertGetId([
          'user_id'=> $id,
          'first_name'=> $request->first_name,
          'last_name'=> $request->last_name,
          'phone'=> $request->phone,
          'question_answer'=> $request->question_answer,
          'security_questions'=>$request->security_questions,
          'referral'=> uniqid(),
          'secret_code'=> $id.'-' .uniqid(),
          'user_type' => $type,
          'alt_phone' => $request->alt_phone,
      ]);
      
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
}
