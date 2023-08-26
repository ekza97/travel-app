@extends('layouts.app')

@can('read file')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Dokumen Jamaah
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
                        <form action="" method="post" enctype="multipart/form-data" id="saveJamaahDocument">
                            @csrf
                            <div class="card-body p-3 mb-0">
                                <div class="row">
                                    <div class="col-md-5 col-12">
                                        <div class="form-group">
                                            <select name="jamaah_id" id="filter-jamaah" class="form-select select2">
                                                <option value="">Pilih Jamaah</option>
                                                @foreach ($jamaahs as $row)
                                                    <option value="{{ $row->id }}">{{ $row->fullname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <select name="document_id" id="filter-document" class="form-select select2">
                                                <option value="">Pilih Dokumen</option>
                                                @foreach ($documents as $row)
                                                    <option value="{{ $row->id }}">
                                                        {{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group mb-0">
                                            <div class="input-group">

                                                <input type="file" class="form-control" name="file" id="inputGroupFile01"
                                                    aria-describedby="inputGroupFileAddon01" aria-label="Unggah" accept=".pdf"
                                                    required>
                                                <button class="btn btn-primary" type="submit" id="btnUpload"><i
                                                        class="bi bi-upload"></i>
                                                    Unggah</button>
                                            </div>
                                            <small class="text-danger">Ukuran maksimal file <strong>2 MB</strong>.
                                                Type file yang diunggah <strong>.pdf</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Table -->
                <div class="col-md-12">
                    <div class="card border-1 border-primary">
                        <div class="card-body table-responsive p-4">
                            <table class="table table-hover" id="jamaah_document-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK
                                            <hr class="p-0 m-0">Nama Jamaah
                                        </th>
                                        <th>Tempat, Tanggal Lahir
                                            <hr class="p-0 m-0">Jenis Kelamin
                                        </th>
                                        <th>Dokumen</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
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
                $('#jamaah_document-table').DataTable({
                    ordering: true,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        'url': "{{ route('jamaah-documents.table') }}",
                        'data': function(q) {
                            q.jamaah = $('#filter-jamaah').val();
                            q.document = $('#filter-document').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10px',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nik_fullname',
                            name: 'nik_fullname'
                        },
                        {
                            data: 'ttl_jk',
                            name: 'ttl_jk'
                        },
                        {
                            data: 'document',
                            name: 'document'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });

                $('#filter-jamaah').change(function() {
                    reloadTable('#jamaah_document-table');
                });

                $('#filter-document').change(function() {
                    reloadTable('#jamaah_document-table');
                });

            });

            function reloadTable(id) {
                var table = $(id).DataTable();
                table.cleaData;
                table.ajax.reload();
            }

            function clearForm() {
                // $('[name="jamaah_id"]').val("").trigger('change');
                // $('[name="document_id"]').val("").trigger('change');
                $('[name="file"]').val("");
                $("#saveJamaahDocument").attr('action', '');
                $("#btnUpload").html('<i class="bi bi-upload"></i> Unggah');
            }


            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            $('#saveJamaahDocument').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnUpload").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('jamaah-documents.store') }}";
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
                        $("#saveJamaahDocument").attr('action', '');
                        $("#btnUpload").html('<i class="bi bi-upload"></i> Unggah').removeAttr('disabled');
                        //delete field
                        clearForm();
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            // window.location.reload();
                            reloadTable('#jamaah_document-table');
                            // window.location = "{{ route('packets.create') }}";
                        });
                        // toastr.success(response.message, 'SUCCESS');
                        // setTimeout(() => {
                        //     window.location.reload();
                        // }, 2500);
                    },
                    error: function(response) {
                        $("#btnUpload").html('<i class="bi bi-upload"></i> Unggah').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteJamaahDocument', function(e) {
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
                            // $('#jamaah_document-table').DataTable().ajax.reload(null, false);
                            reloadTable('#jamaah_document-table');
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
