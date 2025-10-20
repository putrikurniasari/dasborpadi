<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:100|unique:tb_users,username',
            'password' => 'required|string|min:8|same:confpassword',
            'confpassword' => 'required|string|min:8',
        ], [
            'username.unique' => 'Username sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.same' => 'Konfirmasi password tidak cocok.',
            'confpassword.min' => 'Konfirmasi password minimal 8 karakter.',
        ]);

        // Simpan user baru
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Login otomatis setelah register
        Auth::login($user);
        session()->flash('login_success', true);
        return redirect()->intended('/dashboard');
    }
    public function checkUsername(Request $request)
    {
        $exists = User::where('username', $request->username)->exists();
        return response()->json(['exists' => $exists]);
    }

}
