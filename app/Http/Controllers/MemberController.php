<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Show book catalog.
     */
    public function catalog(Request $request)
    {
        $query = Book::query();

        // Search by title, author, or barcode
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $books = $query->orderBy('title', 'asc')->get();
        $categories = Book::select('category')->distinct()->pluck('category');

        return view('dashboards.member_catalog', compact('books', 'categories'));
    }

    /**
     * Show digital membership card.
     */
    public function card()
    {
        $member = Auth::user()->member;
        return view('dashboards.member_card', compact('member'));
    }

    /**
     * Show borrowing history.
     */
    public function history()
    {
        $member = Auth::user()->member;
        
        $borrows = Borrow::where('member_id', $member->id)
            ->with('book')
            ->orderBy('borrow_date', 'desc')
            ->get();
            
        $totalLoans = $member->total_loans;

        return view('dashboards.member_history', compact('borrows', 'totalLoans'));
    }

    /**
     * Show rewards page.
     */
    public function rewards()
    {
        $member = Auth::user()->member;
        return view('dashboards.member_rewards', compact('member'));
    }

    /**
     * Redeem rewards (exchange points to increase borrowing limit).
     */
    public function redeem(Request $request)
    {
        $member = Auth::user()->member;
        
        // Cost of +1 limit is 50 points
        $cost = 50;
        
        if ($member->points < $cost) {
            return back()->with('error', "Poin Anda tidak mencukupi untuk melakukan penukaran. Butuh {$cost} poin, poin saat ini: {$member->points}.");
        }

        // Deduct points, increase limit
        $member->points -= $cost;
        $member->borrow_limit += 1;
        $member->save();

        return redirect()->route('member.rewards')->with('success', "Penukaran berhasil! Batas maksimal peminjaman Anda bertambah menjadi {$member->borrow_limit} buku.");
    }
}
