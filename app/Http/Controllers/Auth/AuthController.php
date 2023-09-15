<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth'])->only('logout');
    }

    public function v_login()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:8'
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.'
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', '=', $email)->first();

        if ($user) {
            if (Hash::check($password, $user->password)) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }
            return redirect()->route('login')->with(['error' => 'Passowrd Salah.']);
        }
        return redirect()->route('login')->with(['error' => 'Akun tidak terdaftar !']);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login')->with(['logout' => 'Berhasil logout.']);
    }
}
