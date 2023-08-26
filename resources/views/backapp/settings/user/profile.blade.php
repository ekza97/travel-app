@extends('layouts.app')

@can('read user')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Profile
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <form id="saveProfile" action="{{ route('users.profile.update', Crypt::encrypt($user->id)) }}"
                                method="post">
                                @csrf
                                <div class="card-header border-bottom pb-2">
                                    <h4 class="card-title">Profile Detail</h4>
                                </div>
                                <div class="card-body py-2">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input type="text" class="form-control text-capitalize mt-2" id="name"
                                            placeholder="Nama lengkap" name="name" value="{{ $user->name }}" required
                                            autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control mt-2" id="email"
                                            placeholder="Alamat email" name="email" value="{{ $user->email }}" required>
                                        <small class="text-danger">Wajib format penulisan email. <strong>ex:
                                                user@gmail.com</strong></small>
                                    </div>
                                </div>
                                <div class="card-footer p-2 px-2 text-end">
                                    <button type="submit" class="btn btn-icon icon-left btn-primary" id="btnSave">
                                        <i class="bi bi-save"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <form action="{{ route('users.password.update', Crypt::encrypt($user->id)) }}" method="post"
                                id="savePassword">
                                @csrf
                                <div class="card-header border-bottom pb-2">
                                    <h4 class="card-title">Ganti Password</h4>
                                </div>
                                <div class="card-body py-2">
                                    <div class="mb-3 col-12 mb-0">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading fw-bold mb-1">Password requirement:</h6>
                                            <ul class="pl-1 ml-25 mb-0">
                                                <li>Minimal 8 karakter, lebih panjang lebih bagus</li>
                                                <li>Ada 1 karakter huruf kecil</li>
                                                <li>Ada 1 karakter huruf kapital</li>
                                                <li>Ada 1 angka</li>
                                                <li>Harus ada 1 karakter khusus</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- form -->
                                    <label class="form-label" for="accountPassword">Password</label>
                                    <div class="form-group position-relative has-icon-right mb-4">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" id="accountPassword"
                                            minlength="8" placeholder="Password" onkeyup="changePass()">
                                        <div class="form-control-icon" onclick="togglePassword()">
                                            <i class="bi bi-eye"></i>
                                        </div>
                                        <small class="text-muted" style="margin-top: -15px;">Klik icon untuk show/hide
                                            password</small>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="card-footer text-end p-2 px-0">
                                        <button type="submit" class="btn btn-primary btn-icon icon-left" id="btnSavePass"><i
                                                class="bi bi-key"></i> Ubah Password</button>
                                    </div>
                                </div>
                            </form>
                            <!--/ form -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            function togglePassword() {
                var x = document.getElementById("accountPassword");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
                x.focus();
            }

            $("#btnSavePass").attr('disabled', 'disabled');

            function changePass() {
                if ($('[name="password"]').val() == '') {
                    $("#btnSavePass").attr('disabled', 'disabled');
                } else {
                    $("#btnSavePass").removeAttr('disabled');
                }
            }

            //update profile
            $('#saveProfile').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = $("#saveProfile").attr('action');
                let type = "PUT";

                let name = $('[name="name"]').val();
                let email = $('[name="email"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        name: name,
                        email: email
                    },
                    success: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan Perubahan').removeAttr(
                            'disabled');
                        $('[name="name"]').focus();
                        setTimeout(() => {
                            window.location.reload();
                        }, 2500);
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan Perubahan').removeAttr(
                            'disabled');
                        toastr.error('Proses menyimpan error', 'ERROR');
                    },
                });
            });

            //update password
            $('#savePassword').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSavePass").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = $("#savePassword").attr('action');
                let type = "PUT";

                let password = $('[name="password"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        password: password
                    },
                    success: function(response) {
                        $("#btnSavePass").html('<i class="bi bi-key"></i> Ubah Password').removeAttr(
                            'disabled');
                        $('[name="password"]').val('');
                        $('[name="password"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSavePass").html('<i class="bi bi-key"></i> Ubah Password').removeAttr(
                            'disabled');
                        toastr.error('Gagal update: <br> ' + response.responseText, 'ERROR');
                    },
                });
            });
        </script>
    @endpush

@endcan
