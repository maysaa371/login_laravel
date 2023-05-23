<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManagerController extends Controller
{
    function login(){
        if (Auth::check()){
            return redirect(route('home'));

        }
        return view ('login');
    }
    function registration (){
        if (Auth::check()){
            return redirect(route('home'));

        }
        return view ('registration');
    }
    function loginPost(request $request){
$request->validate([
    'email'=>'required',
    'password'=>'required'
]);
$credentials = $request->only('email','password');
if(Auth::attempt($credentials)){
    return redirect()->intended(route('home'));
}
return redirect(route('login'))->with("error","login not valid");
}

function registrationPost(Request $request){
    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',

        'password'=>'required'
    ]);

    $data['name']=$request->name;
    $data['email']=$request->email;
    $data['password']=Hash::make($request->password);
    $user = User::create($data);

    if(!$user){
        return redirect(route('registration'))->with("error","registration faild,try again");

    }
    return redirect(route('login'))->with("success","registration success");

}
function logout(){
    Session::flush();
    Auth::logout();
    return redirect(route('login'));
}
}
