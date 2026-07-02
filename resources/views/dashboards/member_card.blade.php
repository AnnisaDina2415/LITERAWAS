@extends('layouts.app')

@section('title', 'Kartu Anggota Digital')
@section('header_title', 'Kartu Anggota')

@section('content')
<div class="card" style="max-width: 550px; margin: 0 auto;">
    <div class="card-header" style="flex-wrap: wrap; gap: 10px;">
        <h2><i class="fa-solid fa-id-card" style="color: var(--primary); margin-right: 8px;"></i> Kartu Anggota Perpustakaan</h2>
        <div style="display: flex; gap: 8px;">
            <button onclick="downloadCard()" class="btn btn-primary btn-sm" style="background-color: var(--secondary); border-color: var(--secondary); color: var(--dark);">
                <i class="fa-solid fa-download"></i> Unduh Kartu (PNG)
            </button>
            <button onclick="window.print()" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-print"></i> Cetak Kartu
            </button>
        </div>
    </div>
    
    <div class="card-body" style="padding: 30px; display: flex; flex-direction: column; align-items: center; gap: 30px;">
        <!-- The Membership Card -->
        <div class="digital-card" style="width: 100%; max-width: 450px; min-height: 260px; padding: 25px; background: {{ $member->membership_details['card_bg'] }} !important; color: #FFFFFF !important; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4); border-radius: 16px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
            <!-- Shiny Reflection Effect -->
            <div style="position: absolute; top: -50%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(var(--primary-rgb), 0.15) 0%, transparent 60%); border-radius: 50%; pointer-events: none;"></div>
            
            <div class="digital-card-header" style="display: flex; justify-content: space-between; align-items: flex-start; z-index: 5;">
                <div class="card-logo" style="display: flex; align-items: center; gap: 8px;">
                    <img src="{{ asset('images/logo-bawaslu.png') }}" alt="Logo Bawaslu" style="height: 28px; width: auto; object-fit: contain;">
                    <div style="font-size: 1.2rem; font-weight: 700; color: #FFFFFF; line-height: 1;">
                        Litera<span style="color: var(--primary);">was</span>
                    </div>
                </div>
                <div class="card-type" style="padding: 4px 10px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: {{ $member->membership_details['badge_bg'] }}; color: {{ $member->membership_details['badge_color'] }}; border-radius: 20px;">
                    {{ $member->membership_details['name'] }}
                </div>
            </div>
            
            <div class="digital-card-body" style="margin-top: 25px; z-index: 5;">
                <div class="member-name" style="font-size: 1.35rem; font-weight: 600; color: #FFFFFF;">{{ auth()->user()->name }}</div>
                <div class="member-id" style="font-size: 1.1rem; color: var(--secondary); margin-top: 5px; font-family: monospace; letter-spacing: 2px;">
                    {{ $member->member_code }}
                </div>
            </div>
            
            <div class="digital-card-footer" style="margin-top: 25px; display: flex; justify-content: space-between; align-items: flex-end; z-index: 5;">
                <div class="card-info-item">
                    <label style="font-size: 0.65rem; text-transform: uppercase; color: rgba(255,255,255,0.4); display: block;">Tanggal Terdaftar</label>
                    <span style="font-size: 0.85rem; font-weight: 500; color: #FFFFFF;">{{ $member->created_at->format('d F Y') }}</span>
                </div>
                
                <div class="card-barcode" style="padding: 8px 12px; background-color: #FFFFFF; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 145px; height: 65px; overflow: hidden; box-shadow: inset 0 0 4px rgba(0,0,0,0.1);">
                    <svg id="barcode" style="width: 100%; height: 35px;"></svg>
                    <div class="barcode-text" style="text-align: center; font-size: 0.62rem; color: #1A1A1A; font-weight: 700; margin-top: 2px; letter-spacing: 0.5px; font-family: monospace;">
                        {{ $member->member_code }}
                    </div>
                </div>
            </div>
        </div>

        <div style="background-color: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--border-radius); padding: 20px; width: 100%;">
            <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--dark); margin-bottom: 10px;">
                <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i> Panduan Penggunaan Kartu Digital:
            </h4>
            <ul style="padding-left: 20px; font-size: 0.85rem; color: var(--gray-700); display: flex; flex-direction: column; gap: 8px;">
                <li>Tunjukkan kartu digital ini kepada **Petugas Perpustakaan** saat ingin melakukan transaksi peminjaman maupun pengembalian.</li>
                <li>Petugas akan memindai (scan) barcode di atas menggunakan scanner atau kamera sistem.</li>
                <li>Kartu ini bersifat permanen dan tidak dapat dipindahtangankan.</li>
                <li>Redam poin reward Anda di menu **Reward & Poin** untuk menaikkan kapasitas pinjaman kartu Anda.</li>
            </ul>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .digital-card, .digital-card * {
            visibility: visible;
        }
        .digital-card {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: none !important;
            background: {{ $member->membership_details['card_bg'] }} !important;
            color: #FFFFFF !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .card-logo span {
            color: var(--primary) !important;
        }
        .member-id {
            color: var(--secondary) !important;
        }
        .barcode-text {
            color: #1A1A1A !important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Generate real Code128 barcode
        JsBarcode("#barcode", "{{ $member->member_code }}", {
            format: "CODE128",
            width: 2,
            height: 35,
            displayValue: false,
            margin: 0,
            lineColor: "#1A1A1A"
        });
    });

    function downloadCard() {
        const card = document.querySelector('.digital-card');
        showToast('Memproses unduhan kartu anggota...', 'warning');
        
        // Wait briefly for barcode rendering to settle
        setTimeout(() => {
            html2canvas(card, {
                scale: 3, // Very high definition
                backgroundColor: null, // transparent corners
                useCORS: true,
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Kartu-Member-{{ $member->member_code }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                showToast('Kartu anggota berhasil diunduh!', 'success');
            }).catch(err => {
                showToast('Gagal mengunduh kartu: ' + err.message, 'danger');
            });
        }, 100);
    }
</script>
@endsection
