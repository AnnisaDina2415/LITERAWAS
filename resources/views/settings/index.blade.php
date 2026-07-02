@extends('layouts.app')

@section('title', 'Pengaturan')
@section('header_title', 'Pengaturan Integrasi')

@section('content')
<div class="welcome-banner" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; width: 100%;">
        <div>
            <h1>Integrasi Google Sheets</h1>
            <p>Sinkronisasikan seluruh data buku, member, dan riwayat peminjaman ke Google Sheets secara real-time.</p>
        </div>
        <div>
            <i class="fa-solid fa-file-excel" style="font-size: 3rem; color: var(--light); opacity: 0.9;"></i>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Left Column: Settings Form & Sync Execution -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-gear" style="color: var(--primary); margin-right: 8px;"></i> Konfigurasi Koneksi</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" style="margin-bottom: 30px;">
                @csrf
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="google_sheets_url" style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem; color: var(--dark);">
                        Google Sheets Web App URL:
                    </label>
                    <input type="url" name="google_sheets_url" id="google_sheets_url" class="form-control" 
                           placeholder="https://script.google.com/macros/s/.../exec" 
                           value="{{ $googleSheetsUrl }}" 
                           style="width: 100%; padding: 12px; border: 1px solid var(--gray-300); border-radius: var(--border-radius); font-size: 0.9rem;"
                           required>
                    <small style="color: var(--gray-600); display: block; margin-top: 5px; font-size: 0.8rem;">
                        Masukkan URL Deployment Web App dari Google Apps Script Anda.
                    </small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan URL
                </button>
            </form>

            <div style="border-top: 1px solid var(--gray-200); padding-top: 25px;">
                <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--dark); margin-bottom: 15px;">Aksi Sinkronisasi</h3>
                <p style="font-size: 0.85rem; color: var(--gray-600); margin-bottom: 20px; line-height: 1.5;">
                    Setelah menyimpan URL, Anda dapat langsung melakukan sinkronisasi data database lokal (Buku, Anggota, Transaksi Peminjaman) ke Google Sheet tujuan Anda dengan menekan tombol di bawah.
                </p>

                @if(!empty($googleSheetsUrl))
                    <form action="{{ route('settings.sync_sheets') }}" method="POST" id="syncForm">
                        @csrf
                        <button type="submit" class="btn btn-secondary" id="syncBtn" style="background-color: var(--secondary); border-color: var(--secondary); color: var(--light);">
                            <i class="fa-solid fa-rotate" id="syncIcon"></i> Sinkronisasi Sekarang
                        </button>
                    </form>
                @else
                    <div style="background-color: rgba(var(--primary-rgb), 0.05); color: var(--primary); padding: 15px; border-radius: var(--border-radius); font-size: 0.85rem; border: 1px dashed rgba(var(--primary-rgb), 0.2);">
                        <i class="fa-solid fa-circle-info"></i> Silakan simpan URL Web App terlebih dahulu untuk mengaktifkan fitur sinkronisasi data.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Instructions & Script Template -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-circle-question" style="color: var(--secondary); margin-right: 8px;"></i> Panduan Setup</h2>
        </div>
        <div class="card-body" style="font-size: 0.85rem; line-height: 1.6; color: var(--gray-700);">
            <ol style="padding-left: 20px; display: flex; flex-direction: column; gap: 10px;">
                <li>Buat sebuah <strong>Google Spreadsheet</strong> baru di Google Drive Anda.</li>
                <li>Buka menu <strong>Ekstensi (Extensions)</strong> &gt; <strong>Apps Script</strong>.</li>
                <li>Hapus kode bawaan, lalu salin dan tempelkan kode skrip di bawah ini:</li>
            </ol>
            
            <div style="margin-top: 15px; margin-bottom: 15px; position: relative;">
                <textarea readonly style="width: 100%; height: 160px; font-family: monospace; font-size: 0.75rem; padding: 10px; border-radius: 8px; border: 1px solid var(--gray-300); background-color: var(--gray-50); resize: none;" id="scriptCode">function doPost(e) {
  try {
    var data = JSON.parse(e.postData.contents);
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    
    // Sinkronisasi Sheet Buku
    var sheetBooks = ss.getSheetByName("Buku") || ss.insertSheet("Buku");
    sheetBooks.clear();
    sheetBooks.appendRow(["Barcode", "Judul Buku", "Penulis", "Penerbit", "Tahun Terbit", "Kategori", "Tersedia"]);
    data.books.forEach(function(b) {
      sheetBooks.appendRow([b.barcode, b.title, b.author, b.publisher, b.year, b.category, b.is_available ? "Ya" : "Tidak"]);
    });
    
    // Sinkronisasi Sheet Member
    var sheetMembers = ss.getSheetByName("Member") || ss.insertSheet("Member");
    sheetMembers.clear();
    sheetMembers.appendRow(["Kode Member", "Nama", "Email", "Total Peminjaman", "Poin Reward", "Batas Peminjaman", "Tanggal Gabung"]);
    data.members.forEach(function(m) {
      sheetMembers.appendRow([m.member_code, m.name, m.email, m.total_loans, m.points, m.borrow_limit, m.joined_at]);
    });
    
    // Sinkronisasi Sheet Peminjaman
    var sheetBorrows = ss.getSheetByName("Peminjaman") || ss.insertSheet("Peminjaman");
    sheetBorrows.clear();
    sheetBorrows.appendRow(["Nama Member", "Judul Buku", "Barcode", "Tanggal Pinjam", "Jatuh Tempo", "Tanggal Kembali", "Status"]);
    data.borrows.forEach(function(tr) {
      sheetBorrows.appendRow([tr.member_name, tr.book_title, tr.barcode, tr.borrow_date, tr.due_date, tr.return_date || "-", tr.status === 'borrowed' ? 'Sedang Dipinjam' : 'Dikembalikan']);
    });
    
    return ContentService.createTextOutput(JSON.stringify({status: "success", message: "Data synced successfully"}))
      .setMimeType(ContentService.MimeType.JSON);
  } catch(err) {
    return ContentService.createTextOutput(JSON.stringify({status: "error", message: err.toString()}))
      .setMimeType(ContentService.MimeType.JSON);
  }
}</textarea>
                <button onclick="copyScriptCode()" class="btn btn-outline btn-sm" style="margin-top: 5px; width: 100%; font-size: 0.75rem; padding: 6px;">
                    <i class="fa-solid fa-copy"></i> Salin Skrip
                </button>
            </div>

            <ol start="4" style="padding-left: 20px; display: flex; flex-direction: column; gap: 10px;">
                <li>Klik ikon <strong>Simpan (Save)</strong> proyek.</li>
                <li>Klik tombol <strong>Terapkan (Deploy)</strong> &gt; <strong>Penerapan baru (New deployment)</strong>.</li>
                <li>Klik ikon gir di sebelah kiri "Pilih tipe", pilih <strong>Aplikasi web (Web app)</strong>.</li>
                <li>Konfigurasikan:
                    <ul style="padding-left: 20px; margin-top: 5px; list-style-type: circle;">
                        <li><strong>Jalankan sebagai (Execute as):</strong> Diri Anda sendiri (Email Anda)</li>
                        <li><strong>Yang memiliki akses (Who has access):</strong> Siapa saja (Anyone)</li>
                    </ul>
                </li>
                <li>Klik <strong>Terapkan (Deploy)</strong>. Berikan izin otorisasi yang diminta (klik Advanced &gt; Go to Untitled project).</li>
                <li>Salin <strong>URL Aplikasi Web (Web app URL)</strong> yang ditampilkan, lalu tempelkan ke kolom isian konfigurasi di sebelah kiri.</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyScriptCode() {
        const copyText = document.getElementById("scriptCode");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        showToast("Kode skrip berhasil disalin!", "success");
    }

    const syncForm = document.getElementById('syncForm');
    if (syncForm) {
        syncForm.addEventListener('submit', () => {
            const btn = document.getElementById('syncBtn');
            const icon = document.getElementById('syncIcon');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyingkronkan Data...';
            showToast("Memulai proses sinkronisasi ke Google Sheets. Harap tunggu...", "warning");
        });
    }
</script>
@endsection
