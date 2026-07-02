@extends('layouts.app')

@section('title', 'Kelola Member')
@section('header_title', 'Kelola Anggota Perpustakaan')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-body" style="padding: 20px;">
        <form action="{{ route('members.index') }}" method="GET" style="display: flex; gap: 10px; max-width: 500px;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau kode member..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            @if(request('search'))
                <a href="{{ route('members.index') }}" class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Daftar Anggota Terdaftar</h2>
        <span class="badge badge-success">{{ $members->count() }} Anggota</span>
    </div>
    
    <div class="card-body">
        @if($members->isEmpty())
            <p style="text-align: center; color: var(--gray-600); padding: 20px;">Tidak ada data member yang ditemukan.</p>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Kode Member</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Total Peminjaman</th>
                            <th>Reward Poin</th>
                            <th>Batas Pinjam</th>
                            @if(auth()->user()->role === 'super_admin')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                            <tr>
                                <td style="font-family: monospace; font-weight: 700; color: #b58b00;">{{ $member->member_code }}</td>
                                <td><strong>{{ $member->user->name }}</strong></td>
                                <td>{{ $member->user->email }}</td>
                                <td>{{ $member->total_loans }} Kali</td>
                                <td>
                                    <span class="badge badge-warning" style="font-weight: 700;">{{ $member->points }} Pts</span>
                                </td>
                                <td>{{ $member->borrow_limit }} Buku</td>
                                @if(auth()->user()->role === 'super_admin')
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-outline btn-sm" title="Edit Member" style="padding: 6px 10px;">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus member ini dari sistem? Semua data relasi terkait juga akan terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline btn-sm" title="Hapus Member" style="padding: 6px 10px; color: var(--primary); border-color: rgba(227,30,36,0.2);">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
