<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required','string','min:6','confirmed', 'regex:/^(?=.*[A-Z]).*$/'
            ], 
        ],[
            'password.confirmed' => 'Ulangi kata sandi tidak cocok.',
            'password.regex' => 'Kata sandi harus mengandung minimal 1 huruf kapital.',
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan', 
        ]);

        Auth::login($user); // login otomatis setelah registrasi

        return redirect()->route('home')->with('success', 'Berhasil Terdaftar. Selamat datang, ' . $user->nama . '!');
    }
}