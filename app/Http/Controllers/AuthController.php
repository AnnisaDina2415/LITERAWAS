<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check member status if role is member
            if ($user->role === 'member' && $user->member) {
                if ($user->member->status === 'pending') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return back()->with('warning', 'Akun Anda sedang menunggu verifikasi dari Admin. Silakan hubungi petugas perpustakaan.');
                }
                
                if ($user->member->status === 'rejected') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return back()->with('error', 'Pendaftaran akun Anda ditolak oleh Admin.');
                }
            }
            
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'security_question.required' => 'Pertanyaan keamanan wajib dipilih.',
            'security_answer.required' => 'Jawaban keamanan wajib diisi.',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member', // default role
            'phone' => $request->phone,
            'security_question' => $request->security_question,
            'security_answer' => strtolower(trim($request->security_answer)), // lowercase and trim for easier comparison
        ]);

        // Generate sequential member code starting from MEM-001
        $lastMember = Member::orderBy('id', 'desc')->first();
        $nextNum = $lastMember ? ((int) str_replace('MEM-', '', $lastMember->member_code)) + 1 : 1;
        $code = 'MEM-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        // Create Member Profile
        Member::create([
            'user_id' => $user->id,
            'member_code' => $code,
            'total_loans' => 0,
            'points' => 0,
            'borrow_limit' => 1, // initial limit is strictly 1 book
        ]);

        // Do not auto log in
        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda (' . $code . ') sedang menunggu verifikasi oleh Admin sebelum Anda dapat masuk.');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
