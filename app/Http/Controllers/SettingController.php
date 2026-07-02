<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Exception;

class SettingController extends Controller
{
    /**
     * Get settings storage path.
     */
    private function getSettingsPath()
    {
        return 'settings.json';
    }

    /**
     * Read settings from storage.
     */
    private function readSettings()
    {
        if (Storage::exists($this->getSettingsPath())) {
            return json_decode(Storage::get($this->getSettingsPath()), true) ?: [];
        }
        return [];
    }

    /**
     * Write settings to storage.
     */
    private function writeSettings(array $settings)
    {
        Storage::put($this->getSettingsPath(), json_encode($settings, JSON_PRETTY_PRINT));
    }

    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = $this->readSettings();
        $googleSheetsUrl = $settings['google_sheets_url'] ?? '';

        return view('settings.index', compact('googleSheetsUrl'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'google_sheets_url' => 'nullable|url',
        ], [
            'google_sheets_url.url' => 'Format URL tidak valid. Pastikan diawali dengan http:// atau https://',
        ]);

        $settings = $this->readSettings();
        $settings['google_sheets_url'] = $request->google_sheets_url;
        $this->writeSettings($settings);

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Synchronize data with Google Sheets.
     */
    public function syncSheets()
    {
        $settings = $this->readSettings();
        $googleSheetsUrl = $settings['google_sheets_url'] ?? '';

        if (empty($googleSheetsUrl)) {
            return redirect()->route('settings.index')->with('error', 'URL Google Sheets Web App belum dikonfigurasi. Harap isi URL terlebih dahulu.');
        }

        try {
            // 1. Collect Books Data
            $books = Book::orderBy('title', 'asc')->get()->map(function ($book) {
                return [
                    'barcode' => $book->barcode,
                    'title' => $book->title,
                    'author' => $book->author,
                    'publisher' => $book->publisher,
                    'year' => $book->year,
                    'category' => $book->category,
                    'is_available' => (bool)$book->is_available,
                ];
            });

            // 2. Collect Members Data
            $members = Member::with('user')->get()->map(function ($member) {
                return [
                    'member_code' => $member->member_code,
                    'name' => $member->user->name ?? 'N/A',
                    'email' => $member->user->email ?? 'N/A',
                    'total_loans' => (int)$member->total_loans,
                    'points' => (int)$member->points,
                    'borrow_limit' => (int)$member->borrow_limit,
                    'joined_at' => $member->created_at ? $member->created_at->format('Y-m-d H:i:s') : 'N/A',
                ];
            });

            // 3. Collect Borrows/Transactions Data
            $borrows = Borrow::with(['member.user', 'book'])->orderBy('borrow_date', 'desc')->get()->map(function ($borrow) {
                return [
                    'member_name' => $borrow->member->user->name ?? 'N/A',
                    'book_title' => $borrow->book->title ?? 'N/A',
                    'barcode' => $borrow->book->barcode ?? 'N/A',
                    'borrow_date' => $borrow->borrow_date ? $borrow->borrow_date->format('Y-m-d') : 'N/A',
                    'due_date' => $borrow->due_date ? $borrow->due_date->format('Y-m-d') : 'N/A',
                    'return_date' => $borrow->return_date ? $borrow->return_date->format('Y-m-d') : null,
                    'status' => $borrow->status,
                ];
            });

            // 4. Send POST request to Google Apps Script Web App
            $response = Http::timeout(25)->post($googleSheetsUrl, [
                'books' => $books,
                'members' => $members,
                'borrows' => $borrows,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['status']) && $result['status'] === 'success') {
                    return redirect()->route('settings.index')->with('success', 'Sinkronisasi dengan Google Sheets berhasil diselesaikan!');
                } else {
                    $msg = $result['message'] ?? 'Response status tidak sukses.';
                    return redirect()->route('settings.index')->with('error', 'Google Sheets merespon dengan kegagalan: ' . $msg);
                }
            } else {
                return redirect()->route('settings.index')->with('error', 'Koneksi ke Google Web App gagal. HTTP Status: ' . $response->status());
            }

        } catch (Exception $e) {
            return redirect()->route('settings.index')->with('error', 'Terjadi error saat sinkronisasi: ' . $e->getMessage());
        }
    }
}
