@extends('layouts.app')

@can('edit schedule')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Edit Jadwal
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
                @slot('url_sub2')
                    {{ route('packets.index') }}
                @endslot
                @slot('sub2')
                    Jadwal
                @endslot
            @endcomponent

            <section class="section row">
                <div class="col-md-12">
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-2 pb-2">
                            <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-danger" style="margin-top: -15px;">* Wajib diisi</p>
                            <form action="{{ route('schedules.update', Crypt::encrypt($edit->id)) }}" method="post"
                                id="saveSchedule" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="packet_id">Paket<span class="text-danger">*</span></label>
                                            <select name="packet_id" id="packet_id" class="form-select mt-2 select2" required>
                                                <option value="">Pilih Paket</option>
                                                @foreach ($packet as $row)
                                                    @if (old('packet_id', $edit->packet_id) == $row->id)
                                                        <option value="{{ $row->id }}" selected>{{ $row->title }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}">{{ $row->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="day">Tanggal Berangkat<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control flatpicker" id="start_date"
                                                name="start_date" value="{{ old('start_date', $edit->start_date) }}"
                                                aria-describedby="start_date-addon">
                                            <span class="input-group-text" id="start_date-addon"><i
                                                    class="bi bi-calendar mb-2"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="day">Jumlah Hari<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control angka" id="day" name="day"
                                                value="{{ old('day', $edit->day) }}" aria-describedby="day-addon"
                                                min="1">
                                            <span class="input-group-text" id="day-addon">Hari</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Nama Jadwal<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control mt-2 text-capitalize" id="name"
                                                name="name" value="{{ old('name', $edit->name) }}" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="mb-2">Keterangan</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="form-control editorTinyMCE">{{ old('description', $edit->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between m-0">
                                    <button type="reset" class="btn icon icon-left block" id="btnBatal">
                                        <i class="bi bi-x-circle"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn icon icon-left btn-primary block" id="btnSave"
                                        onclick="tinyMCE.triggerSave();">
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
                $('[name="packet_id"]').val("");
                $('[name="start_date"]').val("");
                $('[name="day"]').val("");
                $('[name="name"]').val("");
                $('[name="description"]').val("");
                $("#saveSchedule").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('[name="name"]').focus();
            }

            $('#btnBatal').on('click', function() {
                clearForm();
            });

            $('#saveSchedule').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = $("#saveSchedule").attr('action');
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
                        $("#saveSchedule").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            window.location = "{{ route('schedules.index') }}";
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

            //for select
            // var path = "{{ route('packets.index') }}";
            // $('#unitkerja').select2({
            //     ajax: {
            //         url: path,
            //         delay: 450,
            //         type: "POST",
            //         data: function(params) {
            //             return {
            //                 "_token": "{{ csrf_token() }}",
            //                 q: params.term, // search term
            //             };
            //         },
            //         processResults: function({
            //             data
            //         }) {
            //             return {
            //                 results: $.map(data, function(item) {
            //                     return {
            //                         text: `${item.nama_unit} (${item.jabatan_pimpinan})`,
            //                         id: item.id,
            //                     }
            //                 })
            //             };
            //         },
            //         cache: true
            //     }
            // });
        </script>
    @endpush

@endcan
