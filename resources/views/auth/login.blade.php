@extends('layouts.auth')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo" style="margin-bottom:55px;">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('') }}assets/images/logo/logo.png" alt="Logo"
                            style="width:80%;height:80px;">
                    </a>
                </div>
                <h2>Silahkan login</h2>
                <p>Ketikkan username dan password anda</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Username">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            required autocomplete="current-password" id="password" placeholder="Password">
                        <div class="form-control-icon" onclick="togglePassword()">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <small class="text-muted" style="margin-top: -15px;">Klik icon untuk show/hide password</small>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-gray-600" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block shadow-lg mt-4">Masuk</button>
                </form>
                <div class="text-center mt-4">
                    @if (Route::has('register'))
                        <p class="text-gray-600">Belum punya akun ?
                            <a href="{{ route('register') }}" class="font-bold">Buat
                                akun</a>
                        </p>
                    @endif
                    @if (Route::has('password.request'))
                        <p>
                            <a class="font-bold" href="{{ route('password.request') }}">Lupa password ?</a>
                        </p>
                    @endif
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
