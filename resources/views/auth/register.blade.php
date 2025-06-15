@extends('layouts.auth')

@section('main-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Register') }}</h1>
                                </div>

                                {{-- Validasi error --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger border-left-danger" role="alert">
                                        <ul class="pl-4 my-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register') }}" class="user">
                                    @csrf

                                    {{-- Nama --}}
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>
                                    </div>

                                    {{-- Nama Belakang --}}
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="last_name" placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}" required>
                                    </div>

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email" placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required>
                                    </div>

                                    {{-- Nomor HP --}}
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="no_hp" placeholder="Nomor HP" value="{{ old('no_hp') }}" required pattern="[0-9]{10,}" title="Nomor HP minimal 10 digit">
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="form-group">
                                        <textarea name="alamat" class="form-control form-control-user" placeholder="Alamat" required>{{ old('alamat') }}</textarea>
                                    </div>

                                    {{-- Password --}}
                                    <div class="form-group position-relative">
                                        <input type="password" id="password" class="form-control form-control-user" name="password" placeholder="{{ __('Password') }}" required>
                                        <span toggle="#password" class="fa fa-fw fa-eye toggle-password" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></span>
                                    </div>

                                    {{-- Konfirmasi Password --}}
                                    <div class="form-group position-relative">
                                        <input type="password" id="password_confirmation" class="form-control form-control-user" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                                        <span toggle="#password_confirmation" class="fa fa-fw fa-eye toggle-password" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></span>
                                    </div>

                                    {{-- Tombol Register --}}
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </form>

                                <hr>

                                {{-- Sudah punya akun --}}
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">
                                        {{ __('Already have an account? Login!') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
    </div>
</div>

{{-- Font Awesome jika belum ada --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- Script Show/Hide Password --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggles = document.querySelectorAll(".toggle-password");

        toggles.forEach(toggle => {
            const target = document.querySelector(toggle.getAttribute("toggle"));

            toggle.addEventListener("click", function () {
                const type = target.getAttribute("type") === "password" ? "text" : "password";
                target.setAttribute("type", type);
                toggle.classList.toggle("fa-eye");
                toggle.classList.toggle("fa-eye-slash");
            });
        });
    });
</script>
@endsection
