@extends('layouts.app')

@can('read document')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Dokumen
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
                    <!-- Form -->
                    <div class="card">
                        <form action="" method="post" id="saveDocument">
                            @csrf
                            <div class="card-header border-bottom p-3 pb-1 mb-4">
                                <h4 class="card-title">Form Dokumen</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-danger" style="margin-top: -15px;">* Wajib diisi</p>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Dokumen<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-capitalize" id="name"
                                                name="name" value="{{ old('name') }}" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Keterangan<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="description" name="description"
                                                value="{{ old('description') }}" minlength="3" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('create document')
                                <div class="card-footer d-flex justify-content-between">
                                    <button type="reset" class="btn btn-default" id="btnBatal"><i class="bi bi-x-circle"></i>
                                        Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btnSave"><i class="bi bi-save"></i>
                                        Simpan</button>
                                </div>
                            @endcan
                        </form>
                    </div>
                    <!--/ Form -->
                </div>
                <div class="col-md-8">
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1">
                            <h4 class="card-title">Daftar Dokumen</h4>
                        </div>
                        <div class="card-body table-responsive p-4">
                            {!! $dataTable->table() !!}

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
                $('[name="name"]').val("");
                $('[name="description"]').val("");
                $("#saveDocument").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('[name="name"]').focus();
            }

            $('#btnBatal').on('click', function() {
                clearForm();
            });

            function editData(id) {
                $('[name="name"]').focus();
                var link = "{{ route('documents.edit', ':id') }}";
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
                        $("#saveDocument").attr('action', response.link);
                        $('[name="name"]').val(response.name);
                        $('[name="description"]').val(response.description);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveDocument').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveDocument").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('documents.store') }}";
                    type = "POST";
                }
                let name = $('[name="name"]').val();
                let description = $('[name="description"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        name: name,
                        description: description
                    },
                    success: function(response) {
                        $("#saveDocument").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="name"]').val('');
                        $('[name="description"]').val('');
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#document-table').DataTable().ajax.reload(null, false);
                        $('[name="name"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteDocument', function(e) {
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
                            $('#document-table').DataTable().ajax.reload(null, false);
                            $('[name="name"]').focus();
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

        {!! $dataTable->scripts() !!}
    @endpush

@endcan
