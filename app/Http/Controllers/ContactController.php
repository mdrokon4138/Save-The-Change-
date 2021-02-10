<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use Redirect,Response;
use DB;

class ContactController extends Controller
{
    public function basic_email(Request $request){
        $data = $request->all();
        $result = Contact::create($data);
        if($result){ 
        	$arr = array('msg' => 'Message Sent Successfully!', 'status' => true);
        }
        return Response()->json($arr);
    }

    public function save_setting(Request $request){

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        $logoName = time().'.'.$request->logo->extension();  
        $bannerName = time().'.'.$request->banner->extension();  
   
        $request->logo->move(public_path('images'), $logoName);
        $request->banner->move(public_path('images'), $bannerName);
        $old = DB::table('settings')->first();

        if ($old) {
            DB::table('settings')->update([
                'logo'=> $logoName,
                'banner'=> $bannerName,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'address'=>$request->address,
            ]);
        }else {
            DB::table('settings')->insert([
                'logo'=> $logoName,
                'banner'=> $bannerName,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'address'=>$request->address,
            ]);
        }
        
   
        return back()
            ->with('success','Setting Saved.');
   
        
	}
}
