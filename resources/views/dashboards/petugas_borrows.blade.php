@extends('layouts.app')

@section('title', 'Transaksi Peminjaman')
@section('header_title', 'Peminjaman & Pengembalian')

@section('content')
<div class="dashboard-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 25px;">
    <!-- Column 1: Checkout Form -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-cart-shopping" style="color: var(--primary); margin-right: 8px;"></i> Peminjaman Buku baru (Checkout)</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('borrows.checkout') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="member_code">Kode Member / Scan Kartu Anggota</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="member_code" id="checkout_member_code" class="form-control" placeholder="Contoh: MEM-100001" required>
                        <button type="button" class="btn btn-secondary" onclick="openScanner('checkout_member_code')" title="Scan Kartu Anggota">
                            <i class="fa-solid fa-camera"></i> Scan
                        </button>
                    </div>
                    <small style="color: var(--gray-600); margin-top: 5px; display: block;">Masukkan kode atau scan barcode kartu digital member.</small>
                </div>

                <div class="form-group">
                    <label for="barcode">Barcode Buku / Scan Kode Buku</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="barcode" id="checkout_book_barcode" class="form-control" placeholder="Contoh: 9786020333175" required>
                        <button type="button" class="btn btn-secondary" onclick="openScanner('checkout_book_barcode')" title="Scan Buku">
                            <i class="fa-solid fa-camera"></i> Scan
                        </button>
                    </div>
                    <small style="color: var(--gray-600); margin-top: 5px; display: block;">Masukkan atau scan barcode buku yang tersedia.</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    <i class="fa-solid fa-hand-holding-hand"></i> Proses Peminjaman
                </button>
            </form>
        </div>
    </div>

    <!-- Column 2: Return Checkin & Barcode Scanner Simulator -->
    <div style="display: flex; flex-direction: column; gap: 25px;">
        <div class="card">
            <div class="card-header">
                <h2><i class="fa-solid fa-rotate-left" style="color: var(--secondary); margin-right: 8px;"></i> Pengembalian Buku (Checkin)</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('borrows.checkin') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="return_barcode">Barcode Buku / Scan Kode Buku Kembali</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" name="barcode" id="checkin_book_barcode" class="form-control" placeholder="Contoh: 9789792238419" required>
                            <button type="button" class="btn btn-secondary" onclick="openScanner('checkin_book_barcode')" title="Scan Buku Kembali">
                                <i class="fa-solid fa-camera"></i> Scan
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 10px;">
                        <i class="fa-solid fa-circle-check"></i> Proses Pengembalian
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Quick Demo Scanner Helper -->
        <div class="card" style="border-color: rgba(241,196,15,0.3); background-color: rgba(241,196,15,0.02);">
            <div class="card-header" style="border-bottom-color: rgba(241,196,15,0.1);">
                <h2 style="color: #b58b00;"><i class="fa-solid fa-laptop-code"></i> Alat Bantu Scan (Demo Simulator)</h2>
            </div>
            <div class="card-body" style="font-size: 0.85rem;">
                <p style="margin-bottom: 15px; color: var(--gray-700);">Pilih data simulasi di bawah ini untuk mensimulasikan scan barcode secara cepat:</p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <div>
                        <strong>Simulasi Scan Kartu Member:</strong>
                        <div style="display: flex; gap: 5px; margin-top: 5px; flex-wrap: wrap;">
                            <button class="btn btn-outline btn-sm" onclick="simulateScan('MEM-100001', 'checkout_member_code')" style="font-size: 0.75rem; padding: 4px 8px;">Ahmad Yani (MEM-100001)</button>
                            <button class="btn btn-outline btn-sm" onclick="simulateScan('MEM-100002', 'checkout_member_code')" style="font-size: 0.75rem; padding: 4px 8px;">Budi S. (MEM-100002)</button>
                        </div>
                    </div>
                    <div style="margin-top: 5px;">
                        <strong>Simulasi Scan Barcode Buku Tersedia (Pinjam):</strong>
                        <div style="display: flex; gap: 5px; margin-top: 5px; flex-wrap: wrap;">
                            <button class="btn btn-outline btn-sm" onclick="simulateScan('9786020333175', 'checkout_book_barcode')" style="font-size: 0.75rem; padding: 4px 8px;">Laskar Pelangi</button>
                            <button class="btn btn-outline btn-sm" onclick="simulateScan('9786020523315', 'checkout_book_barcode')" style="font-size: 0.75rem; padding: 4px 8px;">Bumi Manusia</button>
                        </div>
                    </div>
                    <div style="margin-top: 5px;">
                        <strong>Simulasi Scan Barcode Buku Dipinjam (Kembalikan):</strong>
                        <div style="display: flex; gap: 5px; margin-top: 5px; flex-wrap: wrap;">
                            <button class="btn btn-outline btn-sm" onclick="simulateScan('9789792238419', 'checkin_book_barcode')" style="font-size: 0.75rem; padding: 4px 8px; color: var(--primary); border-color: var(--primary);">Perahu Kertas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Transactions List -->
<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-list-check" style="color: var(--dark); margin-right: 8px;"></i> Daftar Peminjaman Aktif (Sedang Dipinjam)</h2>
        <span class="badge badge-warning">{{ $activeBorrows->count() }} Peminjaman</span>
    </div>
    <div class="card-body">
        @if($activeBorrows->isEmpty())
            <p style="text-align: center; color: var(--gray-600); padding: 30px;">Tidak ada transaksi peminjaman aktif saat ini.</p>
        @else
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status Sisa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeBorrows as $borrow)
                            @php
                                $due = \Carbon\Carbon::parse($borrow->due_date);
                                $now = \Carbon\Carbon::now()->startOfDay();
                                $diff = $now->diffInDays($due, false);
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $borrow->book->title }}</div>
                                    <div style="font-size: 0.8rem; color: var(--gray-600); font-family: monospace;">{{ $borrow->book->barcode }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $borrow->member->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #b58b00; font-weight: 600;">{{ $borrow->member->member_code }}</div>
                                </td>
                                <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                                <td>{{ $borrow->due_date->format('d M Y') }}</td>
                                <td>
                                    @if($diff < 0)
                                        <span class="badge badge-danger">Terlambat {{ abs($diff) }} Hari</span>
                                    @elseif($diff == 0)
                                        <span class="badge badge-warning">Hari Ini!</span>
                                    @else
                                        <span class="badge badge-success">{{ $diff }} Hari</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('borrows.checkin') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="barcode" value="{{ $borrow->book->barcode }}">
                                        <button type="submit" class="btn btn-accent btn-sm" style="font-size: 0.75rem; padding: 6px 12px;">
                                            <i class="fa-solid fa-circle-left"></i> Kembalikan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Real Camera Scanner Modal Overlay -->
<div class="modal-overlay" id="scannerModal">
    <div class="modal-content" style="max-width: 420px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-camera"></i> Pemindai Barcode / QR Kamera</h3>
            <button onclick="closeScanner()" class="close-btn">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 20px;">
            <!-- Real camera view reader -->
            <div id="reader" style="width: 100%; max-width: 360px; margin: 0 auto; border-radius: 12px; overflow: hidden; border: none; background-color: #000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
            
            <p style="margin-top: 15px; font-size: 0.82rem; color: var(--gray-600); line-height: 1.4;">
                Arahkan kamera perangkat Anda ke barcode/QR Code kartu digital member atau buku.
            </p>
            
            <div style="margin-top: 15px; border-top: 1px dashed var(--gray-200); padding-top: 15px;">
                <label style="display: block; text-align: left; font-size: 0.8rem; font-weight: 600; margin-bottom: 5px; color: var(--gray-700);">Input Manual / Fallback:</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="modal_scan_input" class="form-control" placeholder="Ketik manual jika kamera bermasalah..." style="padding: 10px;">
                    <button class="btn btn-primary btn-sm" onclick="modalScanSubmit()">
                        <i class="fa-solid fa-expand"></i> Masukkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let activeTargetInputId = null;
    let html5QrcodeScanner = null;

    function openScanner(targetInputId) {
        activeTargetInputId = targetInputId;
        const modal = document.getElementById('scannerModal');
        modal.classList.add('active');
        document.getElementById('modal_scan_input').value = '';
        
        // Start device camera scanning
        try {
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            
            const config = { 
                fps: 15, 
                qrbox: function(width, height) {
                    // Wide scanning area suitable for barcodes
                    return { width: Math.min(width * 0.85, 320), height: Math.min(height * 0.55, 130) };
                },
                aspectRatio: 1.0
            };
            
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                config,
                (decodedText, decodedResult) => {
                    // Success callback
                    document.getElementById(activeTargetInputId).value = decodedText;
                    closeScanner();
                    showToast('Scan barcode berhasil!', 'success');
                },
                (errorMessage) => {
                    // Fail silently since it scans continuously per frame
                }
            ).catch(err => {
                console.error("Camera scan start error:", err);
                showToast("Kamera tidak dapat diakses atau diblokir. Silakan gunakan manual input.", "warning");
            });
        } catch (e) {
            console.error("Scanner exception:", e);
        }
    }

    function closeScanner() {
        const modal = document.getElementById('scannerModal');
        modal.classList.remove('active');
        
        if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
            html5QrcodeScanner.stop().then(() => {
                console.log("Scanner stopped.");
            }).catch(err => {
                console.error("Failed to stop scanner camera:", err);
            });
        }
        activeTargetInputId = null;
    }

    function modalScanSubmit() {
        const code = document.getElementById('modal_scan_input').value.trim();
        if (code && activeTargetInputId) {
            document.getElementById(activeTargetInputId).value = code;
            closeScanner();
            showToast('Scan manual berhasil dimasukkan!', 'success');
        }
    }

    function simulateScan(code, inputId) {
        document.getElementById(inputId).value = code;
        showToast(`Barcode ${code} disalin!`, 'success');
    }
</script>
@endsection
