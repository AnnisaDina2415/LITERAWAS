@extends('layouts.app')

@section('title', 'Verifikasi')
@section('header_title', 'Verifikasi Member & Peminjaman')

@section('content')
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header" style="background-color: var(--primary); color: white;">
        <h2 style="color: white; margin: 0;">Verifikasi Pendaftaran Member Baru</h2>
    </div>
    
    <div class="card-body">
        @if($pendingMembers->isEmpty())
            <p style="text-align: center; color: var(--gray-600); padding: 20px;">Tidak ada pendaftaran member baru yang perlu diverifikasi.</p>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Kode Member</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tgl Mendaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingMembers as $member)
                            <tr>
                                <td style="font-weight: bold; color: var(--primary);">{{ $member->member_code }}</td>
                                <td>{{ $member->user->name }}</td>
                                <td>{{ $member->user->email }}</td>
                                <td>{{ $member->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <form action="{{ route('verifications.member.approve', $member->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: #22c55e; color: white;">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('verifications.member.reject', $member->id) }}" method="POST" onsubmit="return confirm('Tolak pendaftaran member ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                                                <i class="fa-solid fa-xmark"></i> Tolak
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

<div class="card">
    <div class="card-header" style="background-color: var(--secondary); color: white;">
        <h2 style="color: white; margin: 0;">Verifikasi Peminjaman Online</h2>
    </div>
    
    <div class="card-body">
        @if($pendingBorrows->isEmpty())
            <p style="text-align: center; color: var(--gray-600); padding: 20px;">Tidak ada permintaan peminjaman buku yang perlu diverifikasi.</p>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam (Request)</th>
                            <th>Rencana Tgl Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingBorrows as $borrow)
                            <tr>
                                <td>
                                    <div style="font-weight: bold;">{{ $borrow->member->user->name }}</div>
                                    <small style="color: var(--gray-600);">{{ $borrow->member->member_code }}</small>
                                </td>
                                <td>
                                    <div style="font-weight: bold;">{{ $borrow->book->title }}</div>
                                    <small style="color: var(--gray-600);">Stok: {{ $borrow->book->available_stock }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($borrow->due_date)->format('d M Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <form action="{{ route('verifications.borrow.approve', $borrow->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: #22c55e; color: white;">
                                                <i class="fa-solid fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('verifications.borrow.reject', $borrow->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan pinjaman ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                                                <i class="fa-solid fa-xmark"></i> Tolak
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

@if(session('simulated_link'))
    <div class="card" style="margin-bottom: 25px; border: 1px solid #bbf7d0; background-color: #f0fdf4;">
        <div class="card-body" style="color: #166534; font-weight: 500;">
            <i class="fa-solid fa-circle-check" style="margin-right: 5px; color: #16a34a;"></i>
            Link Reset Password berhasil dibuat! Salin link berikut dan kirimkan ke Member: 
            <br><br>
            <a href="{{ session('simulated_link') }}" target="_blank" style="word-break: break-all; color: var(--primary); font-weight: 700; text-decoration: underline;">
                {{ session('simulated_link') }}
            </a>
        </div>
    </div>
@endif

<div class="card" style="margin-top: 25px;">
    <div class="card-header" style="background-color: var(--dark); color: white;">
        <h2 style="color: white; margin: 0;">Verifikasi Reset Password Member</h2>
    </div>
    
    <div class="card-body">
        @if($pendingResets->isEmpty())
            <p style="text-align: center; color: var(--gray-600); padding: 20px;">Tidak ada permintaan reset password yang perlu diverifikasi.</p>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Nama Member</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Tgl Request</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingResets as $req)
                            <tr>
                                <td>
                                    <div style="font-weight: bold;">{{ $req->user->name }}</div>
                                    <small style="color: var(--gray-600);">{{ $req->user->member ? $req->user->member->member_code : 'N/A' }}</small>
                                </td>
                                <td>{{ $req->user->email }}</td>
                                <td>{{ $req->user->phone ?? '-' }}</td>
                                <td>{{ $req->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <form action="{{ route('verifications.reset.approve', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: #22c55e; color: white;">
                                                <i class="fa-solid fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('verifications.reset.reject', $req->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan reset password ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background-color: var(--primary); color: white;">
                                                <i class="fa-solid fa-xmark"></i> Tolak
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
