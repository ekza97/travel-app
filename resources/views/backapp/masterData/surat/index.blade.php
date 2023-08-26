@extends('layouts.app')

@can('read jenis_surat')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Jenis Surat
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section row">
                <div class="col-md-4">
                    <!-- Form Permission Modal -->
                    <div class="card">
                        <form action="" method="post" id="saveMasterSurat">
                            @csrf
                            <div class="card-header border-bottom p-3 pb-1 mb-4">
                                <h4 class="card-title">Form Jenis Surat</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">* Wajib diisi</p>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="code" class="form-label">Kode<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-uppercase" id="code"
                                                name="code" value="{{ old('code') }}" tabindex="1" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Jenis Surat<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="name"
                                                name="name" value="{{ old('name') }}" tabindex="2" minlength="3"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('create jenis_surat')
                                <div class="card-footer d-flex justify-content-between">
                                    <button type="reset" class="btn btn-default" id="cancelPermission" tabindex="4"><i
                                            class="bi bi-x-circle"></i>
                                        Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btnSave" tabindex="3"><i
                                            class="bi bi-save"></i>
                                        Simpan</button>
                                </div>
                            @endcan
                        </form>
                    </div>
                    <!--/ Form Permission Modal -->
                </div>
                <div class="col-md-8">
                    <!-- Permission Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1">
                            <h4 class="card-title">Daftar Jenis Surat</h4>
                        </div>
                        <div class="card-body table-responsive p-4">
                            {!! $dataTable->table() !!}

                        </div>
                    </div>
                    <!--/ Permission Table -->
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
            })

            function editData(id) {
                $('[name="name"]').focus();
                var link = "{{ route('letters.edit', ':id') }}";
                link = link.replace(':id', id);

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    data: {
                        id: id
                    },

                    success: function(response) {
                        window.scrollTo(0, 0);
                        $("#btnSave").html('<i class="bi bi-save"></i> Update');
                        $("#saveMasterSurat").attr('action', response.link);
                        $('[name="code"]').val(response.code);
                        $('[name="name"]').val(response.name);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveMasterSurat').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveMasterSurat").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('letters.store') }}";
                    type = "POST";
                }
                let code = $('[name="code"]').val();
                let name = $('[name="name"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        code: code,
                        name: name
                    },
                    success: function(response) {
                        $("#saveMasterSurat").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="code"]').val('');
                        $('[name="name"]').val('');
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#mastersurat-table').DataTable().ajax.reload(null, false);
                        $('[name="code"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteMasterSurat', function(e) {
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
                            $('#mastersurat-table').DataTable().ajax.reload(null, false);
                            $('[name="code"]').focus();
                            if (response) {
                                toastr.success('Berhasil hapus', 'SUCCESS');
                            }
                            if (!response) {
                                toastr.warning('Tidak Bisa Dihapus', 'WARNING');
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

        {!! $dataTable->scripts() !!}
    @endpush

@endcan
