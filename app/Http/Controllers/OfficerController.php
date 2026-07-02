<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    /**
     * Display a listing of officers.
     */
    public function index()
    {
        $officers = User::where('role', 'petugas')->orderBy('created_at', 'desc')->get();
        return view('officers.index', compact('officers'));
    }

    /**
     * Show the form for creating a new officer.
     */
    public function create()
    {
        return view('officers.create');
    }

    /**
     * Store a newly created officer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'email.unique' => 'Email ini sudah digunakan.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return redirect()->route('officers.index')->with('success', 'Akun petugas baru berhasil didaftarkan.');
    }

    /**
     * Show the form for editing the specified officer.
     */
    public function edit(User $officer)
    {
        // Safety check
        if ($officer->role !== 'petugas') {
            return redirect()->route('officers.index')->with('error', 'Akses ditolak. Pengguna bukan merupakan petugas.');
        }

        return view('officers.edit', compact('officer'));
    }

    /**
     * Update the specified officer.
     */
    public function update(Request $request, User $officer)
    {
        if ($officer->role !== 'petugas') {
            return redirect()->route('officers.index')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $officer->id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $officer->update($data);

        return redirect()->route('officers.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Remove the specified officer.
     */
    public function destroy(User $officer)
    {
        if ($officer->role !== 'petugas') {
            return redirect()->route('officers.index')->with('error', 'Akses ditolak.');
        }

        $officer->delete();

        return redirect()->route('officers.index')->with('success', 'Akun petugas berhasil dihapus dari sistem.');
    }
}
