@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('header_title', 'Riwayat Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary); margin-right: 8px;"></i> Seluruh Transaksi Peminjaman & Pengembalian</h2>
        <span class="badge badge-success" style="font-size: 0.85rem; padding: 6px 12px;">Total Transaksi: {{ $borrows->count() }}</span>
    </div>
    
    <div class="card-body">
        @if($borrows->isEmpty())
            <div style="text-align: center; padding: 60px 20px; color: var(--gray-600);">
                <i class="fa-solid fa-book-bookmark" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 15px;"></i>
                <p style="font-weight: 600; color: var(--gray-700);">Belum Ada Riwayat Transaksi</p>
                <p style="font-size: 0.85rem; margin-top: 5px;">Transaksi peminjaman buku akan tercatat secara otomatis di sini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Member</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrows as $borrow)
                            @php
                                $due = \Carbon\Carbon::parse($borrow->due_date);
                                $now = \Carbon\Carbon::now()->startOfDay();
                                $diff = $now->diffInDays($due, false);
                            @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 30px; height: 42px; border-radius: 4px; overflow: hidden; background-color: #f0f0f0; border: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            @if($borrow->book->cover_image)
                                                <img src="{{ asset($borrow->book->cover_image) }}" alt="Cover" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center; color: var(--light);">
                                                    <i class="fa-solid fa-book" style="font-size: 0.7rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--dark);">{{ $borrow->book->title }}</div>
                                            <small style="color: var(--gray-600); font-family: monospace;">{{ $borrow->book->barcode }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--dark);">{{ $borrow->member->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #b58b00; font-weight: 600;">{{ $borrow->member->member_code }}</div>
                                </td>
                                <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                                <td>{{ $borrow->due_date->format('d M Y') }}</td>
                                <td>
                                    @if($borrow->return_date)
                                        {{ $borrow->return_date->format('d M Y') }}
                                    @else
                                        <span style="color: var(--gray-600); font-style: italic;">Belum kembali</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrow->status === 'returned')
                                        @if($borrow->return_date->greaterThan($borrow->due_date))
                                            <span class="badge badge-warning" title="Dikembalikan terlambat"><i class="fa-solid fa-circle-exclamation"></i> Kembali (Terlambat)</span>
                                        @else
                                            <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                        @endif
                                    @elseif($borrow->status === 'borrowed')
                                        @if($diff < 0)
                                            <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Terlambat {{ abs($diff) }} Hari</span>
                                        @else
                                            <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Dipinjam</span>
                                        @endif
                                    @else
                                        <span class="badge badge-danger"><i class="fa-solid fa-circle-exclamation"></i> Terlambat</span>
                                    @endif
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
