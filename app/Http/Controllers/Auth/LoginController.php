<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    Log::info('Attempt login with', ['email' => $credentials['email']]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate(); 
        $user = Auth::user();

        Log::info('Login success: user role = ' . $user->role);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pelanggan') {
            return redirect()->route('home');
        } else {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Role tidak dikenali.']);
        }
    }

    Log::warning('Login failed for email: ' . $request->email);

    return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
}

        public function logout(Request $request)
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('home');
        }
}


