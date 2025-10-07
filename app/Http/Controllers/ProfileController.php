<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        // Ambil data user yang sedang login dari tabel tb_users
        $user = DB::table('tb_users')->where('id', Auth::id())->first();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tb_users,username,' . Auth::id(),
            'old_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $user = DB::table('tb_users')->where('id', Auth::id())->first();

        if (!$user) {
            return back()->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Siapkan data yang akan diupdate
        $updateData = [
            'username' => $request->username,
        ];

        // Jika user ingin mengubah password
        if ($request->filled('old_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai.');
            }
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('tb_users')->where('id', Auth::id())->update($updateData);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
