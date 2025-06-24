<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[A-Z]/' // wajib 1 huruf besar
            ],
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login kembali.');
    }
}
