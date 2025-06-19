<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file resources/views/auth/login.blade.php ada
    }

    /**
     * Proses login pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        Log::info('Attempt login with', ['email' => $credentials['email']]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar. Silakan lakukan pendaftaran terlebih dahulu.'
            ])->withInput();
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            $user = Auth::user();

            /** @var \App\Models\User $user */
            $user->last_login_at = now();
            $user->save();

            Log::info('Login success: user role = ' . $user->role);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'pelanggan') {
                return redirect()->route('home');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Role tidak dikenali.'
                ]);
            }
        }

        Log::warning('Login failed for email: ' . $request->email);

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput();
    }
}
