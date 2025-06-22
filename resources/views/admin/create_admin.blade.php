@extends('layouts.admin')

@section('main-content')
@php
    $userRole = auth()->user()->role ?? '';
@endphp
@if ($userRole !== 'super_admin')
    <div class="alert alert-danger mt-3">
        Anda tidak memiliki izin untuk mengakses halaman ini.
    </div>
@else
<h1 class="h3 mb-4 text-gray-800">Tambah Admin Baru</h1>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mt-2 mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label>Nama Belakang</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>
            </div>
            {{-- Password + Toggle --}}
            <div class="form-group position-relative">
                <label>Password</label>
                <input type="password" name="password" id="password" class="form-control" required minlength="6">
                <span toggle="#password" class="fa fa-fw fa-eye toggle-password" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></span>
            </div>
            <input type="hidden" name="role" value="admin">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
{{-- Font Awesome (jika belum dipasang di layout) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
{{-- Toggle Show/Hide Password --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.querySelector(".toggle-password");
        const passwordField = document.querySelector(toggle.getAttribute("toggle"));

        toggle.addEventListener("click", function () {
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    });
</script>

@endif

@endsection
