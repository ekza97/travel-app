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
                <h2>Reset password</h2>
                <p>Silahkan ketik alamat email anda</p>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Alamat email">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block shadow-lg mt-4">Kirim Link Reset
                        Password</button>
                </form>
                <div class="text-center mt-4">
                    <p class="text-gray-600">Sudah ingat ?
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
