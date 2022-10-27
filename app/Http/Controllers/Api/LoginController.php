<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request){
    	
    	$this->validateLogin($request);
    	
    	if (Auth::attempt($request->only('email','password'))){
    	/*if(($request->email=='jlscbustamante@gmail.com')
    		&& ($request->password=='1346789')){*/
    			
    			$user = new User();
    		return response()->json([
    			'token'=>$request->user()->createToken($request->name)->plainTextToken,
    			'message'=>'Success'
    			]);
    		
    	}
    	
    	return response()->json([
			'message'=>'Unauthenticated'
		],401);
    }
    
    public function validateLogin(Request $request){
    	
    	
    	return $request->validate([
    		'email'=>'required|email',
    		'password'=>'required',
    		'name'=>'required',
    		]);
    }
}
