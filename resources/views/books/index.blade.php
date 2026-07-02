@extends('layouts.app')

@section('title', 'Kelola Buku')
@section('header_title', 'Kelola Koleksi Buku')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-body" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; padding: 20px;">
        <form action="{{ route('books.index') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 500px;">
            <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, atau barcode..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            @if(request('search'))
                <a href="{{ route('books.index') }}" class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset</a>
            @endif
        </form>
        
        <a href="{{ route('books.create') }}" class="btn btn-secondary">
            <i class="fa-solid fa-plus"></i> Tambah Buku Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Daftar Koleksi Buku</h2>
        <span class="badge badge-success">{{ $books->count() }} Total Buku</span>
    </div>
    
    <div class="card-body">
        @if($books->isEmpty())
            <p style="text-align: center; color: var(--gray-600); padding: 20px;">Tidak ada koleksi buku yang ditemukan.</p>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit/Tahun</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td style="font-family: monospace; font-weight: 600;">{{ $book->barcode }}</td>
                                <td><strong>{{ $book->title }}</strong></td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->publisher }} ({{ $book->year }})</td>
                                <td>{{ $book->category }}</td>
                                <td>
                                    @if($book->is_available)
                                        <span class="badge badge-success">Tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-outline btn-sm" title="Edit Buku" style="padding: 6px 10px;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini dari sistem?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline btn-sm" title="Hapus Buku" style="padding: 6px 10px; color: var(--primary); border-color: rgba(227,30,36,0.2);">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
