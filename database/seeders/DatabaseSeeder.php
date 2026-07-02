<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@literawas.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Create Regular Admin (Petugas)
        User::create([
            'name' => 'Petugas Perpus',
            'email' => 'petugas@literawas.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // 3. Create Members
        $user1 = User::create([
            'name' => 'Ahmad Yani',
            'email' => 'ahmad@literawas.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);
        $member1 = Member::create([
            'user_id' => $user1->id,
            'member_code' => 'MEM-100001',
            'total_loans' => 4,
            'points' => 40,
            'borrow_limit' => 3,
        ]);

        $user2 = User::create([
            'name' => 'Budi Sudarsono',
            'email' => 'budi@literawas.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);
        $member2 = Member::create([
            'user_id' => $user2->id,
            'member_code' => 'MEM-100002',
            'total_loans' => 1,
            'points' => 10,
            'borrow_limit' => 3,
        ]);

        $user3 = User::create([
            'name' => 'Citra Lestari',
            'email' => 'citra@literawas.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);
        $member3 = Member::create([
            'user_id' => $user3->id,
            'member_code' => 'MEM-100003',
            'total_loans' => 0,
            'points' => 0,
            'borrow_limit' => 3,
        ]);

        // 4. Create Books
        $book1 = Book::create([
            'barcode' => '9786020333175',
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'year' => 2005,
            'category' => 'Fiksi',
            'stock' => 5,
            'available_stock' => 5,
            'is_available' => true,
        ]);

        $book2 = Book::create([
            'barcode' => '9786020523315',
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Lentera Dipantara',
            'year' => 1980,
            'category' => 'Sejarah',
            'stock' => 3,
            'available_stock' => 3,
            'is_available' => true,
        ]);

        $book3 = Book::create([
            'barcode' => '9789792238419',
            'title' => 'Perahu Kertas',
            'author' => 'Dee Lestari',
            'publisher' => 'Bentang Pustaka',
            'year' => 2009,
            'category' => 'Romantis',
            'stock' => 4,
            'available_stock' => 3, // Ahmad is borrowing 1 copy right now
            'is_available' => true,
        ]);

        $book4 = Book::create([
            'barcode' => '9786020822341',
            'title' => 'Pulang',
            'author' => 'Tere Liye',
            'publisher' => 'Republika',
            'year' => 2015,
            'category' => 'Fiksi',
            'stock' => 5,
            'available_stock' => 5,
            'is_available' => true,
        ]);

        $book5 = Book::create([
            'barcode' => '9786022207321',
            'title' => 'Negeri 5 Menara',
            'author' => 'Ahmad Fuadi',
            'publisher' => 'Gramedia Pustaka Utama',
            'year' => 2009,
            'category' => 'Inspiratif',
            'stock' => 2,
            'available_stock' => 2,
            'is_available' => true,
        ]);

        $book6 = Book::create([
            'barcode' => '9786020633123',
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'publisher' => 'Kompas',
            'year' => 2018,
            'category' => 'Self-Help',
            'stock' => 3,
            'available_stock' => 3,
            'is_available' => true,
        ]);

        // 5. Create Borrow Transactions
        
        // Active Borrow (Ahmad - Perahu Kertas)
        Borrow::create([
            'member_id' => $member1->id,
            'book_id' => $book3->id,
            'borrow_date' => '2026-06-25',
            'due_date' => '2026-07-02',
            'return_date' => null,
            'status' => 'borrowed',
        ]);

        // Past Borrow (Ahmad - Laskar Pelangi, returned)
        Borrow::create([
            'member_id' => $member1->id,
            'book_id' => $book1->id,
            'borrow_date' => '2026-06-10',
            'due_date' => '2026-06-17',
            'return_date' => '2026-06-15',
            'status' => 'returned',
        ]);

        // Past Borrow (Budi - Bumi Manusia, returned late)
        Borrow::create([
            'member_id' => $member2->id,
            'book_id' => $book2->id,
            'borrow_date' => '2026-06-18',
            'due_date' => '2026-06-25',
            'return_date' => '2026-06-26',
            'status' => 'returned',
        ]);

        // Past Borrow (Ahmad - Pulang, returned)
        Borrow::create([
            'member_id' => $member1->id,
            'book_id' => $book4->id,
            'borrow_date' => '2026-05-01',
            'due_date' => '2026-05-08',
            'return_date' => '2026-05-07',
            'status' => 'returned',
        ]);

        // Past Borrow (Ahmad - Negeri 5 Menara, returned)
        Borrow::create([
            'member_id' => $member1->id,
            'book_id' => $book5->id,
            'borrow_date' => '2026-05-15',
            'due_date' => '2026-05-22',
            'return_date' => '2026-05-20',
            'status' => 'returned',
        ]);
    }
}
