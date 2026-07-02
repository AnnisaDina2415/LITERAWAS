@extends('layouts.app')

@section('title', 'Katalog Buku')
@section('header_title', 'Katalog Buku')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-body">
        <form action="{{ route('catalog') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Cari judul buku, penulis, atau barcode..." value="{{ request('search') }}">
            </div>
            
            <div style="width: 200px; min-width: 150px;">
                <select name="category" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                @if(request()->anyFilled(['search', 'category']))
                    <a href="{{ route('catalog') }}" class="btn btn-outline">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="catalog-grid">
    @forelse($books as $book)
        <div class="book-card">
            <div>
                <span class="book-category">{{ $book->category }}</span>
                <h3 class="book-title">{{ $book->title }}</h3>
                <p class="book-author">Oleh: {{ $book->author }}</p>
                
                <div style="font-size: 0.8rem; color: var(--gray-600); margin-bottom: 5px;">
                    <i class="fa-solid fa-print"></i> Penerbit: {{ $book->publisher }} ({{ $book->year }})
                </div>
                <div style="font-size: 0.8rem; color: var(--gray-600); font-family: monospace;">
                    <i class="fa-solid fa-barcode"></i> Code: {{ $book->barcode }}
                </div>
            </div>
            
            <div class="book-footer">
                <span class="book-status">
                    @if($book->is_available)
                        <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Tersedia</span>
                    @else
                        <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Dipinjam</span>
                    @endif
                </span>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background-color: var(--light); border-radius: var(--border-radius); border: 1px solid var(--gray-200);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 15px;"></i>
            <p style="font-weight: 600; color: var(--gray-700);">Buku tidak ditemukan</p>
            <p style="font-size: 0.85rem; color: var(--gray-600); margin-top: 5px;">Silakan coba kata kunci lain atau periksa kategori filter.</p>
        </div>
    @endforelse
</div>
@endsection
