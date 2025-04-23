<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function loginView()
    {
        // Check if the user is already logged in
        if (Auth::check() && Auth::user()->role != 'student') {
            return redirect()->route('index');
        }

        return view('pages.login.index');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 'student') {
                // Clear the session
                $request->session()->flush();

                return back()->withErrors(['errorMessage' => 'Student tidak memiliki akses ke halaman ini']);
            }
            $request->session()->regenerate();
            return redirect()->route('index')->with('success', 'Login berhasil');
        }

        return back()->withErrors([
            'errorMessage' => 'Email atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        // Clear the session
        $request->session()->flush();

        return redirect()->route('login')->with('success', 'Logout berhasil');
    }
}
