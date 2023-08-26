@extends('layouts.app')

@can('read permission')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Permission
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
                        <form action="" method="post" id="savePermission">
                            @csrf
                            <div class="card-header border-bottom p-3 pb-1 mb-4">
                                <h4 class="card-title">Form Permission</h4>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">* Wajib diisi</p>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Permission Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" tabindex="1" minlength="3" required autofocus>
                                            <small class="text-danger">Minimal 3 karakter dan bisa huruf, space.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button type="reset" class="btn btn-default" id="cancelPermission" tabindex="3"><i
                                        class="bi bi-x-circle"></i>
                                    Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnSave" tabindex="2"><i
                                        class="bi bi-save"></i>
                                    Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!--/ Form Permission Modal -->
                </div>
                <div class="col-md-8">
                    <!-- Permission Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1">
                            <h4 class="card-title">Daftar Permission</h4>
                            {{-- <a href="#" class="btn btn-sm btn-primary" onclick="addData()"><i class="bi bi-plus-circle"></i>
                            Tambah</a> --}}
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

            $('#savePermission').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#savePermission").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('permissions.store') }}";
                    type = "POST";
                }
                let name = $('[name="name"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        name: name,
                    },
                    success: function(response) {
                        $("#savePermission").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="name"]').val('');
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#permission-table').DataTable().ajax.reload(null, false);
                        $('[name="name"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deletePermission', function(e) {
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
                            $('#permission-table').DataTable().ajax.reload(null, false);
                            $('[name="name"]').focus();
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
