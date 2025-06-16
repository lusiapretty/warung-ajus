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
    'name'     => 'required|string|max:255',
    'email'    => 'required|string|email|max:255|unique:users',
    'password' => [
        'required',
        'string',
        'min:6',
        'regex:/[A-Z]/',
        'confirmed'
    ],
], [
    'password.min' => 'Password minimal 6 karakter.',
    'password.regex' => 'Password harus mengandung minimal 1 huruf besar.',
    'password.confirmed' => 'Ulangi kata sandi tidak cocok.',
]);


        $user = User::create([
            'nama'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan', // role default
        ]);

        Auth::login($user); // login otomatis setelah registrasi

        return redirect()->route('home'); // redirect ke halaman home
    }
}