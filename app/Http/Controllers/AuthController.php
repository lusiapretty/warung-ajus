<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Log::info('Credentials received:', $credentials); // Debugging info
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Log::info('Authentication successful for user:', ['user' => $user]); // Debugging info
    
            if ($user->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } else if ($user->role == 'pelanggan') { 
                return redirect()->intended('home');
            }
        }
    
        Log::warning('Authentication failed for:', ['email' => $credentials['email']]); // Debugging warning
        return redirect()->back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    
    // Menangani proses logout (optional, bisa diaktifkan jika diperlukan)
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
