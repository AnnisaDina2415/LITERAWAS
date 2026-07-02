<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;

class MemberAdminController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request)
    {
        $query = Member::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('member_code', 'like', "%{$search}%");
        }

        $members = $query->orderBy('created_at', 'desc')->get();
        return view('members.index', compact('members'));
    }

    /**
     * Show edit form for member.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update member details.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
            'borrow_limit' => 'required|integer|min:1|max:10',
        ]);

        // Update User Name
        $member->user->update([
            'name' => $request->name,
        ]);

        // Update Member Specific Data
        $member->update([
            'points' => $request->points,
            'borrow_limit' => $request->borrow_limit,
        ]);

        return redirect()->route('members.index')->with('success', "Informasi member '{$member->user->name}' berhasil diperbarui.");
    }

    /**
     * Remove member.
     */
    public function destroy(Member $member)
    {
        $user = $member->user;
        
        // Check if member has active loans
        $activeLoansCount = $member->borrows()->where('status', 'borrowed')->count();
        if ($activeLoansCount > 0) {
            return back()->with('error', "Gagal menghapus member. Member '{$user->name}' sedang memiliki peminjaman aktif.");
        }

        $member->delete();
        $user->delete();

        return redirect()->route('members.index')->with('success', 'Data member berhasil dihapus dari sistem.');
    }
}
