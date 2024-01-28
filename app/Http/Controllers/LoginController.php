<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function Login()
    {
        $prods = Login::get();
        if (Auth::check()) {
            session_start();
            return redirect('/beranda');
        } else {
            return redirect('/');
        };
    }

    public function LoginCheck(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = Login::where('email', $email)->first();
        if ($user && password_verify($password, $user->password)) {
            session(['name' => $user->name]);
            return redirect('/beranda');
        } else {
            return redirect('/')->with('error', 'Email atau password salah');
        }
    }


    public function create()
    {
        return view('Login', [
            'methodSI' => 'POST',
            'actionSI' => '/loginCheck',
            'methodSU' => 'POST',
            'actionSU' => '/store'
        ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = new Login;
        $data->name = $request->name;
        $data->password = Hash::make($request->password);
        $data->email = $request->email;
        $data->save();
        return redirect('/')->with('msg', 'Akun Berhasil dibuat');
    }

    public function logout()
    {
        session()->flush();
        $prods = Login::get();
        return redirect('/');
    }
}
