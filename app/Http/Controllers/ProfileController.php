<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:512'],
        ], [
            'profile_image.max' => 'Ukuran foto profil tidak boleh lebih dari 512 KB.',
            'profile_image.image' => 'File harus berupa gambar.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                @unlink(public_path($user->profile_image));
            }
            $imageName = 'profile_' . $user->id . '_' . time() . '.' . $request->profile_image->extension();
            $request->profile_image->move(public_path('images/profiles'), $imageName);
            $data['profile_image'] = 'images/profiles/' . $imageName;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Hapus foto profil.
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_image && file_exists(public_path($user->profile_image))) {
            @unlink(public_path($user->profile_image));
        }

        $user->update(['profile_image' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
