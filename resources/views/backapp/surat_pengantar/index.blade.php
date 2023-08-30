@extends('layouts.app')

@can('read cover-letter')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Surat Pengantar
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <form action="{{ route('cover-letters.store') }}" method="post" id="saveCoverLetter">
                            @csrf
                            <div class="card-header border-bottom px-3 py-2 d-flex justify-content-between">
                                <h4 class="card-title mt-2">Buat Surat Pengantar</h4>
                                <button type="submit" class="btn btn-primary pt-1" id="btnSave"><i class="bi bi-save"></i>
                                    Buat
                                    Surat</button>
                            </div>
                            <div class="card-body py-2">
                                <span class="text-danger" style="margin-top:-15px;margin-bottom:10px;">* Wajib diisi</span>
                                <div class="row mt-2">
                                    <div class="col-md-7 col-12">
                                        <div class="form-group">
                                            <label for="jamaah_id">Data Jamaah<span class="text-danger">*</span></label>
                                            <select name="jamaah_id" id="jamaah_id" class="form-select select2" required>
                                                <option value="">Pilih Jamaah</option>
                                                @foreach ($jamaahs as $row)
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->nik . '-' . $row->fullname . '-[' . $row->schedules->name . ']' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="form-group">
                                            <label for="number">Nomor Surat<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control angka" id="number"
                                                            name="number" value="{{ old('number', $last_no + 1) }}" required
                                                            autofocus>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="fullnumber"
                                                            name="fullnumber"
                                                            value="/PUG-REK/{{ Helper::monthRomawi(date('n')) }}/{{ date('Y') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Keterangan</label>
                                            <textarea name="description" id="description" cols="30" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-body table-responsive p-4">
                            <table class="table table-hover" id="cover_letter-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama Jamaah</th>
                                        <th>Nomor Surat</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>

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
            $(document).ready(function() {
                $('#cover_letter-table').DataTable({
                    ordering: true,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        'url': "{{ route('cover-letters.table') }}",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10px',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nik',
                            name: 'nik'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
                        },
                        {
                            data: 'fullnumber',
                            name: 'fullnumber'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });

            });

            function reloadTable(id) {
                var table = $(id).DataTable();
                table.cleaData;
                table.ajax.reload();
            }


            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            $('#saveCoverLetter').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = $("#saveCoverLetter").attr('action');
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
                        $("#saveCoverLetter").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Buat Surat').removeAttr('disabled');
                        //delete field
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            window.location.reload();
                        });
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Buat Surat').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteCoverLetter', function(e) {
                e.preventDefault();
                let allData = new FormData(this);
                var linkDel = $(this).attr('action');
                $('#deleteModal').modal('show');
                $("#btnYes").click(function() {
                    $.ajax({
                        url: linkDel,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        data: allData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);
                            linkDel = '';
                            $('#deleteModal').modal('hide');
                            // $('#cover_letter-table').DataTable().ajax.reload(null, false);
                            reloadTable('#cover_letter-table');
                            if (response.code == 200) {
                                toastr.success(response.message, 'SUCCESS');
                            } else {
                                toastr.warning(response.message, 'WARNING');
                            }
                        },

                    });
                });
                $(".btnBatal").click(function() {
                    linkDel = '';
                    $('#deleteModal').modal('hide');
                });
            });
        </script>
    @endpush

@endcan
