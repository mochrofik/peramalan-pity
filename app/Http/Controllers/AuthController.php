<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{


    public function index(Request $request){
        
        if(Auth::check()){
            return redirect('dashboard');
        }else{
            return view('login');
        }
    }

    public function login(Request $request){
        if((!$request->has('email') && !$request->has('password') ) || ( $request->email == "" ) ){
            Session::flash('error','Periksa Kembali email dan Password');
        }
        
        $user_login = User::where('email', $request->get('email'))->first();
        
        if($user_login == null){
            Session::flash('error','User tidak ditemukan');
        }

        $data = [
            'email'=> $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($data)){ 
            $user =Auth::user();
            Session::flash('success','Berhasil');
            
            return redirect('dashboard');
        } 
        else{ 
            Session::flash('error','Periksa kembali email dan password');
            return redirect('/');
        } 
    }
    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
