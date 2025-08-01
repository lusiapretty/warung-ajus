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
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[A-Z]).*$/'
            ], 
        ], [
            // Custom error messages untuk nama
            'nama.required' => 'Nama lengkap harus diisi.',
            'nama.string' => 'Nama lengkap harus berupa teks.',
            'nama.max' => 'Nama lengkap maksimal 255 karakter.',
            
            // Custom error messages untuk email
            'email.required' => 'Email harus diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            
            // Custom error messages untuk password
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Ulangi kata sandi tidak cocok.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf kapital.',
        ]);

        try {
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pelanggan', 
            ]);

            Auth::login($user); // login otomatis setelah registrasi

            return redirect()->route('home')->with('success', 'Berhasil Terdaftar. Selamat datang, ' . $user->nama . '!');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }
}