@extends('layouts.app')

@can('read surat_masuk')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Surat Masuk
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
                    <!-- Permission Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-3 pb-1 justify-content-between">
                            <div class="float-start">
                                <h4 class="card-title">Daftar Surat Masuk</h4>
                            </div>
                            @can('create surat_masuk')
                                <div class="float-end pb-1">
                                    <button class="btn btn-primary btn-sm" onclick="addData()">
                                        <i class="bi bi-plus-circle"></i>
                                        Tambah
                                    </button>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body table-responsive p-4">
                            {!! $dataTable->table() !!}

                        </div>
                    </div>
                    <!--/ Permission Table -->
                </div>

                @can('create surat_masuk')
                    <!-- Form Create Surat Masuk Modal -->
                    @include('backapp.suratMasuk.create')
                    <!--/ Form Create Surat Masuk Modal -->
                @endcan
                @can('edit surat_masuk')
                    <!-- Form Edit Surat Masuk Modal -->
                    @include('backapp.suratMasuk.edit')
                    <!--/ Form Edit Surat Masuk Modal -->
                @endcan

                <!--Delete Modal -->
                @include('utils.ajaxDelete')
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
            });

            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            function clearForm() {
                $('[name="jenis_surat_id"]').val("").trigger('change');
                $('[name="edit_jenis_surat_id"]').val("").trigger('change');
                $('[name="tujuan"]').val("");
                $('[name="no_surat"]').val("");
                $('[name="alamat"]').val("");
                $('[name="tanggal"]').val("");
                $('[name="keterangan"]').val("");
                $('[name="perihal"]').val("");
                $('[name="file"]').val("");
            }

            $('.btnCancel').on('click', function() {
                clearForm();
                $('#formModal').modal('hide');
                $('#formEditModal').modal('hide');
            });

            function addData() {
                clearForm();
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('#formModal').modal('show');
            }

            function editData(id) {
                $('#formEditModal').modal('show');
                var link = "{{ route('letter-in.edit', ':id') }}";
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
                        $("#editSuratMasuk").attr('action', response.link);
                        var $newOption = $("<option selected></option>").val(response.data.jenis_surat_id).text(
                            response.data.jenis_surat.code + ' - ' + response.data.jenis_surat.name);
                        $('#edit_jenis_surat_id').append($newOption).trigger('change');
                        $('[name="tujuan"]').val(response.data.tujuan);
                        $('[name="no_surat"]').val(response.data.no_surat);
                        $('[name="alamat"]').val(response.data.alamat);
                        $('[name="tanggal"]').val(response.data.tanggal);
                        $('[name="keterangan"]').val(response.data.keterangan);
                        $('[name="perihal"]').val(response.data.perihal);
                        $('#lihat-file').attr('href', response.file_url);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveSuratMasuk').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" Surat Masuk="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('letter-in.store') }}";
                let type = "POST";

                const data = new FormData(this);

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#formModal').modal('hide');
                        $("#saveSuratMasuk").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#suratmasuk-table').DataTable().ajax.reload(null, false);
                        // $('[name="tujuan"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $('#editSuratMasuk').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" Surat Masuk="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = $("#editSuratMasuk").attr('action');
                let type = "POST";

                const data = new FormData(this);

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#formEditModal').modal('hide');
                        $("#editSuratMasuk").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        // window.scrollTo(0, document.body.scrollHeight);
                        $('#suratmasuk-table').DataTable().ajax.reload(null, false);
                        // $('[name="tujuan"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteSuratMasuk', function(e) {
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
                            $('#suratmasuk-table').DataTable().ajax.reload(null, false);
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

            //for select jenis surat form add
            var path = "{{ route('selectjenissurat') }}";
            $('#jenis_surat_id').select2({
                placeholder: 'Pilih Jenis Surat',
                theme: "bootstrap-5",
                dropdownParent: $("#jenis_surat_id").parent(),
                ajax: {
                    url: path,
                    delay: 450,
                    type: "POST",
                    data: function(params) {
                        return {
                            "_token": "{{ csrf_token() }}",
                            q: params.term, // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    },
                    cache: true
                }
            });
            //for select jenis surat form edit
            var path = "{{ route('selectjenissurat') }}";
            $('#edit_jenis_surat_id').select2({
                placeholder: 'Pilih Jenis Surat',
                theme: "bootstrap-5",
                dropdownParent: $("#edit_jenis_surat_id").parent(),
                ajax: {
                    url: path,
                    delay: 450,
                    type: "POST",
                    data: function(params) {
                        return {
                            "_token": "{{ csrf_token() }}",
                            q: params.term, // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    },
                    cache: true
                }
            });
        </script>

        {!! $dataTable->scripts() !!}
    @endpush

@endcan
