@extends('layouts.app')

@section('title', 'Edit Petugas')
@section('header_title', 'Edit Akun Petugas')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2><i class="fa-solid fa-user-gear" style="color: var(--primary); margin-right: 8px;"></i> Ubah Kredensial Petugas</h2>
        <a href="{{ route('officers.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card-body">
        @if($errors->any())
            <div style="background-color: rgba(var(--primary-rgb), 0.1); border: 1px solid var(--primary); color: var(--primary); padding: 12px; border-radius: var(--border-radius); font-size: 0.85rem; margin-bottom: 20px; font-weight: 500;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('officers.update', $officer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $officer->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $officer->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Ganti Password (Opsional)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password...">
                <small style="color: var(--gray-600); margin-top: 5px; display: block;">Hanya isi kolom ini jika ingin menyetel ulang kata sandi petugas.</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">
                <i class="fa-solid fa-save"></i> Perbarui Akun
            </button>
        </form>
    </div>
</div>
@endsection
