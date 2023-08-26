@extends('layouts.app')

@can('create agent')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Tambah Agent
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
                @slot('url_sub2')
                    {{ route('agents.index') }}
                @endslot
                @slot('sub2')
                    Agent
                @endslot
            @endcomponent

            <section class="section row">
                <div class="col-md-12">
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-2 pb-2">
                            <a href="{{ route('agents.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-danger" style="margin-top: -15px;">* Wajib diisi</p>
                            <form action="" method="post" id="saveAgent" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="token">Token<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-uppercase" id="token"
                                                name="token" value="{{ old('token', Str::random(15)) }}" required readonly>
                                            <small class="text-muted">Otomatis dibuat oleh sistem</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="fullname">Nama Agent<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="fullname"
                                                name="fullname" value="{{ old('fullname') }}" placeholder="John Andy" required
                                                autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="pob">Tempat Lahir<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="pob"
                                                name="pob" value="{{ old('pob') }}" placeholder="Bintuni" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dob">Tanggal Lahir<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control flatpicker" id="dob" name="dob"
                                                max="{{ date('Y-m-d') }}" value="{{ old('dob') }}" placeholder="2023-01-23"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Nomor HP<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control phone" id="phone" name="phone"
                                                value="{{ old('phone') }}" placeholder="0812xxxxxxxx" minlength="11"
                                                maxlength="12" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="address">Alamat<span class="text-danger">*</span></label>
                                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control" required>{{ old('address') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="image">Foto</label>
                                                    <input type="file" class="form-control" id="image" name="image"
                                                        accept=".jpg, .jpeg, .png">
                                                    <small>Ukuran maksimal file <strong>2 MB</strong>. Tipe file
                                                        <strong>.jpg .jpeg .png</strong></small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="is_active">Status<span class="text-danger">*</span></label>
                                                    <select class="form-select" name="is_active" id="is_active">
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Non Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between m-0">
                                    <button type="reset" class="btn icon icon-left block" id="btnBatal">
                                        <i class="bi bi-x-circle"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn icon icon-left btn-primary block" id="btnSave">
                                        <i class="bi bi-save"></i>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/ Table -->
                </div>


                <!--Delete Modal -->
                @include('utils.ajaxDelete')
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            function clearForm() {
                $('[name="fullname"]').val("");
                $('[name="pob"]').val("");
                $('[name="dob"]').val("");
                $('[name="phone"]').val("");
                $('[name="address"]').val("");
                $('[name="image"]').val("");
                $("#saveAgent").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('[name="fullname"]').focus();
            }

            $('#btnBatal').on('click', function() {
                clearForm();
            });

            $('#saveAgent').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('agents.store') }}";
                let type = "POST";

                let formData = new FormData(this);

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $("#saveAgent").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            window.location.reload();
                            // window.location = "{{ route('agents.create') }}";
                        });
                        // toastr.success(response.message, 'SUCCESS');
                        // setTimeout(() => {
                        //     window.location.reload();
                        // }, 2500);
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });
        </script>
    @endpush

@endcan
