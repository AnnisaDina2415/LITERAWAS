@extends('layouts.app')

@section('title', 'Edit Buku')
@section('header_title', 'Edit Data Buku')

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h2><i class="fa-solid fa-edit" style="color: var(--primary); margin-right: 8px;"></i> Ubah Informasi Buku</h2>
        <a href="{{ route('books.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card-body">
        @if($errors->any())
            <div style="background-color: rgba(var(--primary-rgb), 0.1); border: 1px solid var(--primary); color: var(--primary); padding: 12px; border-radius: var(--border-radius); font-size: 0.85rem; margin-bottom: 20px; font-weight: 500;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('books.update', $book->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="barcode">Kode Buku / Barcode Unik</label>
                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Contoh: 9786020523315" value="{{ old('barcode', $book->barcode) }}" required>
            </div>

            <div class="form-group">
                <label for="title">Judul Buku</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul lengkap buku..." value="{{ old('title', $book->title) }}" required>
            </div>

            <div class="form-group">
                <label for="author">Penulis / Pengarang</label>
                <input type="text" name="author" id="author" class="form-control" placeholder="Masukkan nama penulis..." value="{{ old('author', $book->author) }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="publisher">Penerbit</label>
                    <input type="text" name="publisher" id="publisher" class="form-control" placeholder="Bentang Pustaka" value="{{ old('publisher', $book->publisher) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="year">Tahun Terbit</label>
                    <input type="number" name="year" id="year" class="form-control" placeholder="2020" value="{{ old('year', $book->year) }}" required min="1000" max="{{ date('Y') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="category">Kategori Buku</label>
                <input type="text" name="category" id="category" class="form-control" placeholder="Fiksi, Sejarah, Sains, Komputer, Dsb." value="{{ old('category', $book->category) }}" required list="category_suggestions">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">
                <i class="fa-solid fa-save"></i> Perbarui Data
            </button>
        </form>
    </div>
</div>
@endsection
