<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Literawas') - Sistem Informasi Perpustakaan</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand" style="display: flex; flex-direction: column; align-items: flex-start; gap: 6px; padding: 20px 24px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <img src="{{ asset('images/logo-bawaslu.png') }}" alt="Logo Bawaslu" style="height: 38px; width: auto; object-fit: contain;">
                    <div style="font-size: 1.35rem; font-weight: 700; color: var(--dark); line-height: 1;">
                        Litera<span style="color: var(--primary);">was</span>
                    </div>
                </div>
                <div style="font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--gray-600); font-weight: 700; line-height: 1; margin-left: 2px;">
                    Bawaslu Prov. Lampung
                </div>
            </div>
            
            <ul class="sidebar-menu">
                @auth
                    <!-- Common for all authenticated users -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Super Admin Menus -->
                    @if(auth()->user()->role === 'super_admin')
                        <li>
                            <a href="{{ route('books.index') }}" class="sidebar-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-book"></i> Kelola Buku
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('members.index') }}" class="sidebar-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> Kelola Member
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('officers.index') }}" class="sidebar-link {{ request()->routeIs('officers.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-user-shield"></i> Kelola Petugas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('borrows.history') }}" class="sidebar-link {{ request()->routeIs('borrows.history') ? 'active' : '' }}">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-file-invoice-dollar"></i> Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-sliders"></i> Pengaturan
                            </a>
                        </li>
                    @endif
                    
                    <!-- Regular Admin (Petugas) Menus -->
                    @if(auth()->user()->role === 'petugas')
                        <li>
                            <a href="{{ route('borrows.index') }}" class="sidebar-link {{ request()->routeIs('borrows.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-hand-holding-hand"></i> Peminjaman Buku
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('members.index') }}" class="sidebar-link {{ request()->routeIs('members.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> Daftar Member
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('books.index') }}" class="sidebar-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-book"></i> Kelola Buku
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                                <i class="fa-solid fa-calendar-days"></i> Laporan Bulanan
                            </a>
                        </li>
                    @endif
                    
                    <!-- Member Menus -->
                    @if(auth()->user()->role === 'member')
                        <li>
                            <a href="{{ route('catalog') }}" class="sidebar-link {{ request()->routeIs('catalog') ? 'active' : '' }}">
                                <i class="fa-solid fa-magnifying-glass"></i> Katalog Buku
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('member.card') }}" class="sidebar-link {{ request()->routeIs('member.card') ? 'active' : '' }}">
                                <i class="fa-solid fa-id-card"></i> Kartu Digital
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('member.history') }}" class="sidebar-link {{ request()->routeIs('member.history') ? 'active' : '' }}">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('member.rewards') }}" class="sidebar-link {{ request()->routeIs('member.rewards') ? 'active' : '' }}">
                                <i class="fa-solid fa-award"></i> Reward & Poin
                            </a>
                        </li>
                    @endif
                @else
                    <li>
                        <a href="{{ route('login') }}" class="sidebar-link {{ request()->routeIs('login') ? 'active' : '' }}">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="sidebar-link {{ request()->routeIs('register') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-plus"></i> Daftar Member
                        </a>
                    </li>
                @endauth
            </ul>
            
            <div class="sidebar-footer">
                @auth
                    <div class="user-badge">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>
                                @if(auth()->user()->role === 'super_admin')
                                    Super Admin
                                @elseif(auth()->user()->role === 'petugas')
                                    Petugas
                                @else
                                    Member
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <p style="font-size: 0.8rem; text-align: center; color: rgba(255,255,255,0.4)">Sistem Perpustakaan</p>
                @endauth
            </div>
        </aside>
        
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <header class="header-nav">
                <div class="page-title">
                    @yield('header_title', 'Dashboard')
                </div>
                
                <div class="header-actions">
                    @auth
                        @if(auth()->user()->role === 'super_admin')
                            <span class="role-badge role-super">Super Admin</span>
                        @elseif(auth()->user()->role === 'petugas')
                            <span class="role-badge role-petugas">Petugas</span>
                        @else
                            <span class="role-badge role-member">Member</span>
                        @endif
                        
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" title="Log Out">
                                <i class="fa-solid fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Masuk</a>
                    @endauth
                </div>
            </header>
            
            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Toast Notifications -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast toast-success">
                <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="toast">
                <i class="fa-solid fa-circle-xmark" style="color: var(--primary);"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="toast toast-warning">
                <i class="fa-solid fa-circle-exclamation" style="color: var(--secondary);"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
    </div>

    <!-- JS Scripts -->
    <script>
        // Simple Toast Auto Hide
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.3s forwards cubic-bezier(0.4, 0, 0.2, 1)';
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 4000);
            });
        });

        // Dynamic Toast Helper
        function showToast(message, type = 'danger') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type === 'success' ? 'toast-success' : (type === 'warning' ? 'toast-warning' : '')}`;
            
            let icon = '<i class="fa-solid fa-circle-xmark" style="color: var(--primary);"></i>';
            if (type === 'success') {
                icon = '<i class="fa-solid fa-circle-check" style="color: #22c55e;"></i>';
            } else if (type === 'warning') {
                icon = '<i class="fa-solid fa-circle-exclamation" style="color: var(--secondary);"></i>';
            }

            toast.innerHTML = `
                ${icon}
                <span>${message}</span>
            `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s forwards cubic-bezier(0.4, 0, 0.2, 1)';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        }
    </script>
    <style>
        @keyframes slideOut {
            to {
                transform: translateY(100px);
                opacity: 0;
            }
        }
    </style>
    @yield('scripts')
</body>
</html>
