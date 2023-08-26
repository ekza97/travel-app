@extends('layouts.app')

@can('form registration')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Formulir Pendaftaran
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent
            <section class="section">
                <div class="card">
                    <form action="" method="post" enctype="multipart/form-data" id="saveFormRegistration">
                        @csrf
                        <div class="card-header py-2">
                            <span class="text-danger mt-0 mb-0">* Wajib diisi</span>
                        </div>
                        <hr class="mt-0">
                        <div class="card-body">
                            <div class="row">
                                <h3 class="card-title mb-2">A. DATA JAMAAH</h3>
                                <hr>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category_id">Kategori<span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-select mt-1 select2" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $row)
                                                @if (old('category_id') == $row->id)
                                                    <option value="{{ $row->id }}" selected>
                                                        {{ $row->name }}</option>
                                                @else
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="agent_id">Agent<span class="text-danger">*</span></label>
                                        <select name="agent_id" id="agent_id" class="form-select mt-1 select2" required>
                                            <option value="">Pilih Agent</option>
                                            @foreach ($agents as $row)
                                                @if (old('agent_id') == $row->id)
                                                    <option value="{{ $row->id }}" selected>
                                                        {{ $row->fullname . ' [' . $row->phone . ']' }}</option>
                                                @else
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->fullname . ' [' . $row->phone . ']' }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="schedule_id">Jadwal Berangkat<span class="text-danger">*</span></label>
                                        <select name="schedule_id" id="schedule_id" class="form-select mt-1 select2" required>
                                            <option value="">Pilih Jadwal</option>
                                            @foreach ($schedules as $row)
                                                @if (old('schedule_id') == $row->id)
                                                    <option value="{{ $row->id }}" selected>{{ $row->name }}</option>
                                                @else
                                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nik">Nomor Induk Kependudukan (NIK)<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control mt-1 nik" id="nik" name="nik"
                                            value="{{ old('nik') }}" minlength="16" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="fullname">Nama Lengkap<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control mt-1 text-capitalize" id="fullname"
                                            name="fullname" value="{{ old('fullname') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pob">Tempat Lahir<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control mt-1 text-capitalize" id="pob"
                                            name="pob" value="{{ old('pob') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dob">Tanggal Lahir<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control mt-1 flatpicker" id="dob" name="dob"
                                            value="{{ old('dob') }}" max="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin<span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-select mt-1" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone">Nomor HP<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control phone mt-1" id="phone" name="phone"
                                            value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="martial_status">Status Nikah<span class="text-danger">*</span></label>
                                        <select name="martial_status" id="martial_status" class="form-select mt-1" required>
                                            <option value="">Pilih Status Kawin</option>
                                            <option value="Belum Menikah">Belum Menikah</option>
                                            <option value="Sudah Menikah">Sudah Menikah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="profession">Profesi<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control mt-1 text-capitalize" id="profession"
                                            name="profession" value="{{ old('profession') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address" class="mb-2">Alamat Lengkap<span
                                                class="text-danger">*</span></label>
                                        <textarea name="address" id="address" cols="30" rows="3" class="form-control" required>{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <hr>
                                <h3 class="card-title mt-3 mb-2">B. DATA KELUARGA</h3>
                                <hr>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="heir_name">Nama Ahli Waris</label>
                                        <input type="text" class="form-control mt-1 text-capitalize" id="heir_name"
                                            name="heir_name" value="{{ old('heir_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="heir_relation">Hubungan Ahli Waris</label>
                                        <input type="text" class="form-control mt-1" id="heir_relation"
                                            name="heir_relation" value="{{ old('heir_relation') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="heir_phone">Nomor HP Ahli Waris</label>
                                        <input type="text" class="form-control phone mt-1" id="heir_phone"
                                            name="heir_phone" value="{{ old('heir_phone') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer px-3 py-2">
                            <button type="reset" class="btn btn-default block mb-2"><i class="bi bi-x-circle"></i>
                                Batal</button>
                            <button type="submit" class="btn btn-primary block float-end mb-2"><i class="bi bi-save"></i>
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            function clearForm() {
                $('[name="agent_id"]').val("");
                $('[name="schedule_id"]').val("");
                $('[name="nik"]').val("");
                $('[name="fullname"]').val("");
                $('[name="pob"]').val("");
                $('[name="dob"]').val("");
                $('[name="gender"]').val("");
                $('[name="phone"]').val("");
                $('[name="martial_status"]').val("");
                $('[name="profession"]').val("");
                $('[name="address"]').val("");
                $('[name="heir_name"]').val("");
                $('[name="heir_relation"]').val("");
                $('[name="heir_phone"]').val("");
                $("#saveFormRegistration").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('[name="name"]').focus();
            }

            $('#btnBatal').on('click', function() {
                clearForm();
            });

            $('#saveFormRegistration').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('jamaahs.store') }}";
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
                        $("#saveFormRegistration").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            window.location.reload();
                            // window.location = "{{ route('packets.create') }}";
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
