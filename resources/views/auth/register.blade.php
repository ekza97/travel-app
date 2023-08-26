@extends('layouts.auth')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo" style="margin-bottom:45px;margin-top:-35px;">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('') }}assets/images/logo/logo.png" alt="Logo"
                            style="width:80%;height:80px;">
                    </a>
                </div>
                <h2>Buat akun baru</h2>
                <p>Silahkan lengkapi form berikut</p>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama lengkap">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" placeholder="Alamat email">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-2">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            required autocomplete="new-password" id="password" placeholder="Password">
                        <div class="form-control-icon" onclick="togglePassword()">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <small class="text-muted" style="margin-top: -15px;">Password
                            minimal 8 karakter</small>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="password" class="form-control" name="password_confirmation" required
                            autocomplete="new-password" id="password-confirm" placeholder="Konfirmasi password">
                        <div class="form-control-icon" onclick="toggleConfirmPassword()">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <small class="text-muted" style="margin-top: -15px;">Klik icon untuk show/hide password.</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block shadow-lg mt-4">Buat akun</button>
                </form>
                <div class="text-center mt-4">
                    <p class="text-gray-600">Sudah punya akun ?
                        <a href="{{ route('login') }}" class="font-bold">Masuk</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">
                <img src="{{ asset('assets/images/auth-bg.png') }}" alt="Auth Background" height="100%" width="100%">
            </div>
        </div>
    </div>
@endsection
