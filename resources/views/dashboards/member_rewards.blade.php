@extends('layouts.app')

@section('title', 'Reward & Poin')
@section('header_title', 'Reward & Poin')

@section('content')
<div class="dashboard-grid">
    <!-- Left Column: Points Overview and Redeems -->
    <div>
        <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, #a81014 100%); color: var(--light); border: none; box-shadow: var(--shadow-premium);">
            <div class="card-body" style="padding: 35px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7); font-weight: 600;">Saldo Poin Anda</h3>
                    <p style="font-size: 3.2rem; font-weight: 800; margin-top: 5px; color: var(--secondary);">{{ $member->points }} <span style="font-size: 1.5rem; font-weight: 500; color: var(--light);">Poin</span></p>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 15px; font-size: 0.85rem; background-color: rgba(255,255,255,0.1); padding: 6px 12px; border-radius: 20px; width: fit-content;">
                        <i class="fa-solid fa-medal" style="color: var(--secondary);"></i>
                        Status: 
                        <strong>
                            @if($member->points >= 100)
                                Gold Member
                            @elseif($member->points >= 40)
                                Silver Member
                            @else
                                Bronze Member
                            @endif
                        </strong>
                    </div>
                </div>
                <div style="font-size: 5rem; color: rgba(255,255,255,0.15);">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Penukaran Reward</h2>
            </div>
            <div class="card-body" style="padding: 25px;">
                <div style="border: 1px solid var(--gray-200); border-radius: var(--border-radius); padding: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; background-color: var(--gray-50);">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(241, 196, 15, 0.1); color: #b58b00; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div>
                            <h4 style="font-weight: 600; color: var(--dark); font-size: 1.05rem;">Peningkatan Batas Peminjaman (+1 Buku)</h4>
                            <p style="font-size: 0.85rem; color: var(--gray-600); margin-top: 3px;">Meningkatkan batas maksimal buku yang dapat dipinjam secara bersamaan.</p>
                            <div style="display: flex; gap: 15px; margin-top: 8px; font-size: 0.8rem; font-weight: 500;">
                                <span style="color: var(--primary);"><i class="fa-solid fa-circle-nodes"></i> Biaya: 50 Poin</span>
                                <span style="color: var(--gray-700);"><i class="fa-solid fa-circle-check"></i> Batas Saat Ini: {{ $member->borrow_limit }} Buku</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('member.redeem') }}" method="POST">
                            @csrf
                            @if($member->points >= 50)
                                <button type="submit" class="btn btn-accent btn-sm">
                                    <i class="fa-solid fa-gift"></i> Tukar Sekarang
                                </button>
                            @else
                                <button type="button" class="btn btn-outline btn-sm" disabled style="cursor: not-allowed; opacity: 0.6;">
                                    <i class="fa-solid fa-lock"></i> Butuh {{ 50 - $member->points }} Poin
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Rewards Information -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-circle-question" style="color: var(--primary); margin-right: 8px;"></i> Bagaimana cara mendapatkan poin?</h2>
        </div>
        <div class="card-body" style="padding: 25px;">
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; gap: 15px;">
                    <div style="font-size: 1.25rem; color: var(--primary); margin-top: 2px;"><i class="fa-solid fa-hand-holding-hand"></i></div>
                    <div>
                        <h4 style="font-size: 0.9rem; font-weight: 600;">Meminjam Buku</h4>
                        <p style="font-size: 0.8rem; color: var(--gray-600); margin-top: 3px;">Setiap kali Anda meminjam dan mengembalikan buku tepat waktu, sistem otomatis mencatat dan memberikan <strong>10 Poin Reward</strong>.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div style="font-size: 1.25rem; color: var(--secondary); margin-top: 2px;"><i class="fa-solid fa-user-plus"></i></div>
                    <div>
                        <h4 style="font-size: 0.9rem; font-weight: 600;">Bonus Registrasi</h4>
                        <p style="font-size: 0.8rem; color: var(--gray-600); margin-top: 3px;">Saat pertama kali mendaftar, Anda langsung mendapatkan saldo pembuka sebesar <strong>10 Poin Reward</strong> secara gratis.</p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; border-top: 1px solid var(--gray-200); padding-top: 20px;">
                    <div style="font-size: 1.25rem; color: var(--dark); margin-top: 2px;"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <h4 style="font-size: 0.9rem; font-weight: 600;">Mengapa menaikkan batas pinjaman?</h4>
                        <p style="font-size: 0.8rem; color: var(--gray-600); margin-top: 3px;">Batas peminjaman standar adalah 3 buku. Menukarkan poin Anda akan meningkatkan kapasitas kartu, memampukan Anda meminjam lebih banyak referensi atau buku dalam satu waktu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
