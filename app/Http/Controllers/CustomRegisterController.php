<?php

namespace App\Http\Controllers;

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

      $id= DB::table('cms_users')->insertGetId([
          'name'=> $request->first_name,
          'email'=> $request->email,
          'password'=> md5($request->password),
          'status'=> 'Active',
          'id_cms_privileges'=>$request->user_type
      ]);
      DB::table('user_info')->insert([
          'user_id'=> $id,
          'first_name'=> $request->first_name,
          'last_name'=> $request->last_name,
          'phone'=> $request->phone,
          'question_answer'=> $request->question_answer,
          'security_questions'=>$request->security_questions
      ]);
      
        return redirect('register-now');
    }
}
