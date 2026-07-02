<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show general reports dashboard.
     */
    public function index()
    {
        // 1. Members count
        $totalMembers = Member::count();
        $membersList = Member::with('user')->orderBy('created_at', 'desc')->get();

        // 2. Books availability stats
        $availableBooks = Book::where('is_available', true)->count();
        $borrowedBooks = Book::where('is_available', false)->count();
        $totalBooks = Book::count();

        // 3. Overdue and Late return logs
        $today = Carbon::today()->toDateString();
        
        // Active loans that are late
        $overdueBorrows = Borrow::where('status', 'borrowed')
            ->where('due_date', '<', $today)
            ->with(['member.user', 'book'])
            ->get();

        // Past loans returned late
        $allBorrows = Borrow::with(['member.user', 'book'])->get();
        $returnedLateBorrows = [];
        foreach ($allBorrows as $b) {
            if ($b->status === 'returned' && $b->return_date->greaterThan(Carbon::parse($b->due_date))) {
                $returnedLateBorrows[] = $b;
            }
        }

        // 4. Most borrowed books
        $popularBooks = Borrow::select('book_id')
            ->selectRaw('count(book_id) as total')
            ->groupBy('book_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->with('book')
            ->get();

        // 5. Monthly loans count (grouping in PHP for database type flexibility)
        $monthlyTrends = [];
        foreach ($allBorrows as $b) {
            // e.g. "June 2026"
            $monthKey = Carbon::parse($b->borrow_date)->format('Y-m (F)');
            if (!isset($monthlyTrends[$monthKey])) {
                $monthlyTrends[$monthKey] = 0;
            }
            $monthlyTrends[$monthKey]++;
        }
        // Sort by key to maintain date ordering
        ksort($monthlyTrends);

        return view('reports.index', compact(
            'totalMembers', 'membersList', 
            'availableBooks', 'borrowedBooks', 'totalBooks',
            'overdueBorrows', 'returnedLateBorrows', 
            'popularBooks', 'monthlyTrends'
        ));
    }
}
