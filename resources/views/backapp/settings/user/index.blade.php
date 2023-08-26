@extends('layouts.app')

@can('read user')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Data Pengguna
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section">
                <!-- Users Table -->
                <div class="card border-1 border-primary">
                    <div class="card-header border-bottom p-3">
                        {{-- <h4 class="card-title text-white">Daftar Pengguna</h4> --}}
                        <a href="#" class="btn btn-sm btn-primary" onclick="addData()"><i class="bi bi-plus-circle"></i>
                            Tambah</a>
                    </div>
                    <div class="card-body table-responsive p-4">
                        {!! $dataTable->table() !!}

                    </div>
                </div>
                <!--/ Users Table -->

                <!-- Form User Modal -->
                <div class="modal fade text-left" id="formModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <form id="saveUsers" action="" method="post">
                                @csrf <div class="modal-header">
                                    <h4 class="modal-title mt-n3 pb-0">Tambah Pengguna</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Fullname</label>
                                        <input type="text" class="form-control text-capitalize mt-2" id="name"
                                            placeholder="Nama lengkap" name="name" value="" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control mt-2" id="email"
                                            placeholder="Alamat email" name="email" value="" required>
                                        <small class="text-danger">Wajib format penulisan email. <strong>ex:
                                                user@gmail.com</strong></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control mt-2" id="password"
                                            placeholder="Password baru" name="password" required>
                                        <small class="text-danger">Minimal 8 karakter dan pastikan mengandung huruf kapital,
                                            angka,
                                            simbol demi keamanan.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Role</label>
                                        <select class="form-select mt-2" name="role">
                                            <option value="">Pilih Hak Akses</option>
                                            @foreach (Helper::roles() as $row)
                                                <option value="{{ $row->name }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-icon icon-left" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-icon icon-left btn-primary">
                                        <i class="bi bi-save"></i>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--/ Form User Modal -->

                <!--Delete Modal -->
                @include('utils.ajaxDelete')
            </section>
        </div>
    @endsection

    @push('scriptjs')
        <script>
            // $('#users-table').DataTable();
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
            });
            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            })

            function addData() {
                $('input').val("");
                $('select').val("");
                $('#unitkerja').val('').trigger('change');
                $('input:checkbox').prop('checked', false);
                $('[name="password"]').attr('required');
                $('#password-required').show();
                $("#saveUsers").attr('action', '');
                $("#btnSave").html('<i class="fa fa-save"></i> Simpan');
                $('#formModal').modal('show');
            }

            function editData(id) {
                $('#formModal').modal('show');
                $('#password-required').hide();
                $('[name="password"]').removeAttr('required');
                $('[name="name"]').focus();
                var link = "{{ route('users.edit', ':id') }}";
                link = link.replace(':id', id);

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    dataType: 'json',
                    data: {
                        id: id
                    },

                    success: function(response) {
                        window.scrollTo(0, 0);
                        $("#btnSave").html('<i class="fa fa-save"></i> Update');
                        let data = response.data;
                        $("#saveUsers").attr('action', response.link);
                        $('[name="name"]').val(data.name);
                        $('[name="email"]').val(data.email);
                        $('[name="role"]').val(data.roles[0].name);
                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan' + response.responseText, 'ERROR');
                    },
                }).then(function(data) {
                    let item = data.data.unit_kerja;
                    var newOption = new Option(item.nama_unit + ' (' + item.jabatan + ')', item.id, true, true);
                    $('#unitkerja').append(newOption).trigger('change');
                });
            }

            $('#saveUsers').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveUsers").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('users.store') }}";
                    type = "POST";
                }
                let name = $('[name="name"]').val();
                let email = $('[name="email"]').val();
                let role = $('[name="role"]').val();
                let password = $('[name="password"]').val();

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        name: name,
                        email: email,
                        role: role,
                        password: password
                    },
                    success: function(response) {
                        $("#saveUsers").attr('action', '');
                        $("#btnSave").html('<i class="fa fa-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('input').val('');
                        $('select').val('');
                        $('[name="unit_kerja_id"]').val('').trigger('change');
                        $('#formModal').modal('hide');
                        window.scrollTo(0, document.body.scrollHeight);
                        $('#user-table').DataTable().ajax.reload(null, false);
                        $('[name="name"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="fa fa-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: <br>' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteUsers', function(e) {
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
                            linkDel = '';
                            $('#deleteModal').modal('hide');
                            $('#user-table').DataTable().ajax.reload(null, false);
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
