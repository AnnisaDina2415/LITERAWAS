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
        <div class="book-card" style="position: relative; overflow: hidden; display: flex; flex-direction: column; min-height: 410px; padding: 0; border: 1px solid var(--gray-200); border-radius: var(--border-radius); background: var(--light);">
            <!-- Blur Cover Background -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 160px; overflow: hidden; z-index: 1;">
                <div style="width: 100%; height: 100%; background-image: url('{{ $book->cover_image ? asset($book->cover_image) : asset('images/logo-bawaslu.png') }}'); background-size: cover; background-position: center; filter: blur(12px) brightness(0.55); transform: scale(1.15);"></div>
            </div>
            
            <!-- Foreground Elements -->
            <div style="position: relative; z-index: 2; padding: 25px 20px 0; display: flex; flex-direction: column; align-items: center; text-align: center; margin-top: 15px;">
                <!-- Foreground Cover Image -->
                <div style="width: 110px; height: 155px; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.35); background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.2);">
                    @if($book->cover_image)
                        <img src="{{ asset($book->cover_image) }}" alt="Sampul {{ $book->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <!-- Elegant Book Icon Placeholder with gradient -->
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--light); padding: 10px;">
                            <i class="fa-solid fa-book" style="font-size: 2.2rem; margin-bottom: 5px;"></i>
                            <span style="font-size: 0.55rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">No Cover</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Card Body Content -->
            <div style="flex-grow: 1; padding: 15px 20px 20px; display: flex; flex-direction: column; justify-content: space-between; z-index: 2; background-color: var(--light);">
                <div style="text-align: center;">
                    <span class="book-category" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700; color: var(--primary); margin-bottom: 5px; display: block;">{{ $book->category }}</span>
                    <h3 class="book-title" style="font-size: 0.95rem; font-weight: 700; color: var(--dark); line-height: 1.3; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">{{ $book->title }}</h3>
                    <p class="book-author" style="font-size: 0.8rem; color: var(--gray-600); margin-bottom: 5px;">Oleh: {{ $book->author }}</p>
                    
                    <div style="font-size: 0.75rem; color: var(--gray-600); display: flex; justify-content: center; gap: 10px; margin-top: 5px;">
                        <span><i class="fa-solid fa-print"></i> {{ $book->publisher }}</span>
                        <span>•</span>
                        <span>{{ $book->year }}</span>
                    </div>
                </div>
                
                <div style="border-top: 1px solid var(--gray-100); padding-top: 12px; margin-top: 12px;">
                    <!-- Stock Ratio Info -->
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; margin-bottom: 8px; align-items: center;">
                        <span style="color: var(--gray-600);"><i class="fa-solid fa-layer-group"></i> Stok Tersedia:</span>
                        <span style="font-weight: 700; color: {{ $book->available_stock > 0 ? 'var(--success)' : 'var(--primary)' }}">
                            {{ $book->available_stock }} / {{ $book->stock }} Buku
                        </span>
                    </div>
                    
                    <div class="book-footer" style="border: none; padding: 0; display: flex; justify-content: space-between; align-items: center;">
                        <span class="book-status">
                            @if($book->available_stock > 0)
                                <span class="badge badge-success" style="background-color: rgba(40,167,69,0.1); color: var(--success);"><i class="fa-solid fa-circle-check"></i> Tersedia</span>
                            @else
                                <span class="badge badge-danger" style="background-color: rgba(214,32,39,0.1); color: var(--primary);"><i class="fa-solid fa-circle-xmark"></i> Kosong</span>
                            @endif
                        </span>
                        <span style="font-size: 0.75rem; color: var(--gray-600); font-family: monospace;">{{ $book->barcode }}</span>
                    </div>
                </div>
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
