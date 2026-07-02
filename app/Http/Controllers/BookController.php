<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
        }

        $books = $query->orderBy('created_at', 'desc')->get();
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created book in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|unique:books,barcode',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:' . date('Y'),
            'category' => 'required|string|max:100',
        ], [
            'barcode.unique' => 'Barcode ini sudah terdaftar pada buku lain.',
            'year.integer' => 'Tahun terbit harus berupa angka.',
            'year.max' => 'Tahun terbit tidak boleh melebihi tahun saat ini.'
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Buku baru berhasil ditambahkan ke dalam sistem.');
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified book in database.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'barcode' => 'required|string|unique:books,barcode,' . $book->id,
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:' . date('Y'),
            'category' => 'required|string|max:100',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Remove the specified book from database.
     */
    public function destroy(Book $book)
    {
        // Check if book is currently borrowed
        if (!$book->is_available) {
            return back()->with('error', 'Gagal menghapus buku. Buku sedang dalam status dipinjam.');
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus dari sistem.');
    }
}
