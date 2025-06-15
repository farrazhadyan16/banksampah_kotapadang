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
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Login') }}</h1>
                                </div>

                                {{-- Tampilkan error validasi --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger border-left-danger" role="alert">
                                        <ul class="pl-4 my-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="user">
                                    @csrf

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                               name="email"
                                               placeholder="{{ __('E-Mail Address') }}"
                                               value="{{ old('email') }}"
                                               required autofocus>
                                    </div>

                                    {{-- Password + toggle --}}
                                    <div class="form-group position-relative">
                                        <input type="password"
                                               class="form-control form-control-user"
                                               id="password"
                                               name="password"
                                               placeholder="{{ __('Password') }}"
                                               required>
                                        <span toggle="#password"
                                              class="fa fa-fw fa-eye toggle-password"
                                              style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></span>
                                    </div>

                                    {{-- Remember Me --}}
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input"
                                                   name="remember" id="remember"
                                                   {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                </form>

                                <hr>

                                {{-- Forgot Password & Register --}}
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="small" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>
                                @endif

                                @if (Route::has('register'))
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">
                                            {{ __('Create an Account!') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Font Awesome (SB Admin 2 sudah menyertakan, ini cadangan jika belum) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- Toggle Password Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.querySelector(".toggle-password");
        const passwordField = document.querySelector("#password");

        toggle.addEventListener("click", function () {
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    });
</script>
@endsection
