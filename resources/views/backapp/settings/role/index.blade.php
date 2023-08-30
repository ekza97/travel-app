@extends('layouts.app')

@can('read role')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Role
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
            @endcomponent

            <section class="section">
                <!-- Role Table -->
                <div class="card border-1 border-primary">
                    @can('create role')
                        <div class="card-header border-bottom p-3">
                            <a href="#" class="btn btn-sm btn-primary" onclick="addData()"><i class="bi bi-plus-circle"></i>
                                Tambah</a>
                        </div>
                    @endcan
                    <div class="card-body table-responsive p-4">
                        {!! $dataTable->table() !!}

                    </div>
                </div>
                <!--/ Role Table -->

                <!-- Form Role Modal -->
                <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form id="saveRole" action="" method="post">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title mt-n3">Tambah Role</h4>
                                    <button type="button" class="btn-close btnCancel"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-danger">* Wajib diisi</p>
                                    <div class="row p-1">
                                        <div class="col-12 mb-3">
                                            <label class="form-label" for="name">Role Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Role Name" autofocus required>
                                            <small class="text-danger">Minimal 3 karakter dan bisa huruf, angka, dash,
                                                underscore.</small>
                                        </div>
                                        <div class="form-group row">
                                            <table class="table table-hover">
                                                @foreach ($modules as $key => $item)
                                                    <tr>
                                                        <td>{{ $item }}</td>
                                                        <td>
                                                            @if ($key == 'all_permission')
                                                                <div class="form-check">
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" id="checkAll"
                                                                            class="form-check-input">
                                                                        <label for="checkAll">Pilih semu permission</label>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="d-flex">
                                                                    @foreach (Helper::permissions($key) as $row)
                                                                        <div class="form-check me-2">
                                                                            <div class="checkbox">
                                                                                <input type="checkbox" id="{{ $row->id }}"
                                                                                    class="form-check-input check"
                                                                                    name="permission"
                                                                                    value="{{ $row->name }}">
                                                                                <label for="{{ $row->id }}"
                                                                                    class="{{ strtok($row->name, ' ') == 'delete' ? 'text-danger' : (strtok($row->name, ' ') == 'forcedelete' ? 'text-danger' : '') }}">{{ strtok($row->name, ' ') }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-icon icon-left btnCancel" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-icon icon-left btn-primary" id="btnSave">
                                        <i class="bi bi-save"></i>
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--/ Form Role Modal -->

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
            })

            $('.btnCancel').on('click', function() {
                $('[name="name"]').val("");
                $('input:checkbox').prop('checked', false);
                $('#formModal').modal('hide');
            })

            $(document).ready(function() {
                $('#checkAll').on('click', function() {
                    if (this.checked) {
                        $('.check').each(function() {
                            this.checked = true;
                        });
                    } else {
                        $('.check').each(function() {
                            this.checked = false;
                        });
                    }
                });

                $('.check').on('click', function() {
                    if ($('.check:checked').length == $('.check').length) {
                        $('#checkAll').prop('checked', true);
                    } else {
                        $('#checkAll').prop('checked', false);
                    }
                });

            });

            function addData() {
                $('[name="name"]').val("");
                $('input:checkbox').prop('checked', false);
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('#formModal').modal('show');
            }

            function editData(id) {
                $('#formModal').modal('show');
                $('[name="name"]').focus();
                var link = "{{ route('roles.edit', ':id') }}";
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
                        $("#saveRole").attr('action', response.link);
                        $('[name="name"]').val(response.name);
                        if (response.permission.length == {{ count(Helper::permissions()) }}) {
                            $("#checkAll").prop('checked', true);
                        } else {
                            $("#checkAll").prop('checked', false);
                        }
                        for (let i = 0; i < response.permission.length; i++) {
                            $(`#${response.permission[i].id}`).prop('checked', true);
                        }

                    },
                    error: function(response) {
                        toastr.error('Terjadi kesalahan', 'ERROR');
                    },
                });
            }

            $('#saveRole').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');
                var link = $("#saveRole").attr('action');
                let type = "PUT";
                if (link == "") {
                    link = "{{ route('roles.store') }}";
                    type = "POST";
                }
                let name = $('[name="name"]').val();
                let permission = [];

                // Initializing array with Checkbox checked values
                $('input[name="permission"]:checked').each(function() {
                    permission.push(this.value);
                });

                $.ajax({
                    url: link,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: type,
                    dataType: 'json',
                    data: {
                        name: name,
                        permission: permission
                    },
                    success: function(response) {
                        $("#saveRole").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        $('[name="name"]').val('');
                        $('input[type="checkbox"]').prop('checked', false);
                        $('#formModal').modal('hide');
                        window.scrollTo(0, document.body.scrollHeight);
                        $('#role-table').DataTable().ajax.reload(null, false);
                        // setTimeout(function() {
                        //     location.reload()
                        // }, 2000);
                        $('[name="name"]').focus();
                        toastr.success(response.message, 'SUCCESS');
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deleteRole', function(e) {
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
                            $('#role-table').DataTable().ajax.reload(null, false);
                            $('[name="kode"]').focus();
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
