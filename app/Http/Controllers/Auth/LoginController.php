<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // ✅ Tambahkan method ini
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        Log::info('Attempt login with', ['email' => $credentials['email']]);

        // 🔍 Cek apakah email ada di database
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar. Silakan lakukan pendaftaran terlebih dahulu.'
            ])->withInput();
        }

        // ✅ Coba login
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
}
