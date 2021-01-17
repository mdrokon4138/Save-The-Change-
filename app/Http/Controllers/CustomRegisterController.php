<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomRegisterController extends Controller
{
    public function index(Request $request){
        return view('auth.register');
    }
   
   
    public function register(Request $request){
        $this->validate($request,[
         'first_name'=>'required',
         'email'=>'required',
         'password'=>'required',
         'security_questions'=>'required',
         'question_answer'=>'required',
         'user_type'=>'required',
      ]);
      
        return redirect('register-now');
    }
}
