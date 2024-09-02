<?php

namespace App\Http\Controllers;

use App\Mail\MailSend;
use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;
use Str;

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
        // $email = $request->email;
        // $password = $request->password;

        // $user = Login::where('email', $email)->first();
        // if ($user && password_verify($password, $user->password)) {
        //     session(['name' => $user->name]);
        //     return redirect('/beranda');
        // } else {
        //     return redirect('/')->with('error', 'Email atau password salah');
        // }

        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::Attempt($data)) {
            $user = Login::where('email', $data['email'])->first();
            session(['name' => $user->name]);
            return redirect('/beranda');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
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

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|min:3',
        //     'email' => 'required|email',
        //     'password' => 'required|min:6',
        // ]);
    
        // if ($validator->fails()) {
        //     return redirect()->back()
        //                 ->withErrors($validator)
        //                 ->withInput();
        // }

        // $data = new Login;
        // $data->name = $request->name;
        // $data->password = Hash::make($request->password);
        // $data->email = $request->email;
        // $data->save();

        $str = Str::random(100);

        $user = Login::create([
            'email' => $request->email,
            'name' => $request->username,
            'password' => Hash::make($request->password),
            'verify_key' => $str
        ]);

        $details = [
            'username' => $request->username,
            'website' => 'www.contax.com',
            'datetime' => date('Y-m-d H:i:s'),
            'url' => request()->getHttpHost().'/register/verify/'.$str
        ];

        Mail::to($request->email)->send(new MailSend($details));

        Session::flash('message', 'Link verifikasi telah dikrim ke Email Anda. Silahkan Cek Email Anda untuk Mengaktifkan Akun');

        return redirect('/')->with('msg', 'Akun Berhasil dibuat');
    }

    public function verify($verify_key)
    {
        $keyCheck = Login::select('verify_key')
            ->where('verify_key', $verify_key)
            ->exists();

        if ($keyCheck) {
            $user = Login::where('verify_key', $verify_key)
                ->update([
                    'active' => 1
                ]);

            return "Verifikasi Berhasil. Akun Anda sudah aktif.";
        } else {
            return "Key tidak valid!";
        }
    }

    public function logout()
    {
        session()->flush();
        $prods = Login::get();
        return redirect('/');
    }
    
}
