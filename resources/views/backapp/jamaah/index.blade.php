@extends('layouts.app')

@can('read jamaah')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Jamaah
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
                        <div class="card-body">
                            <div class="row">
                                <h4 class="card-title">Filter Jamaah</h4>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <select name="category" id="filter-category" class="form-select select2">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <select name="agent" id="filter-agent" class="form-select select2">
                                            <option value="">Pilih Agent</option>
                                            @foreach ($agents as $row)
                                                <option value="{{ $row->id }}">
                                                    {{ $row->fullname . ' [' . $row->phone . ']' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <select name="schedule" id="filter-schedule" class="form-select select2">
                                            <option value="">Pilih Jadwal</option>
                                            @foreach ($schedules as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        @can('form registration')
                            <div class="card-header border-bottom p-3 pb-3">
                                <a href="{{ route('form-registration') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i>
                                    Tambah
                                </a>
                            </div>
                        @endcan
                        <div class="card-body table-responsive p-4">
                            <table class="table table-hover" id="jamaah-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Agent
                                            <hr class="p-0 m-0">Jadwal
                                        </th>
                                        <th>NIK
                                            <hr class="p-0 m-0">Nama Jamaah
                                        </th>
                                        <th>Tempat, Tanggal Lahir
                                            <hr class="p-0 m-0">Jenis Kelamin
                                        </th>
                                        <th>Nomor HP
                                            <hr class="p-0 m-0">Status Nikah
                                        </th>
                                        <th>Profesi</th>
                                        <th>Alamat</th>
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
                $('#jamaah-table').DataTable({
                    ordering: true,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        'url': "{{ route('jamaahs.table') }}",
                        'data': function(q) {
                            q.category = $('#filter-category').val();
                            q.agent = $('#filter-agent').val();
                            q.schedule = $('#filter-schedule').val();
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
                            data: 'agent_jadwal',
                            name: 'agent_jadwal'
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
                            data: 'hp_status',
                            name: 'hp_status'
                        },
                        {
                            data: 'profession',
                            name: 'profession'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });

                $('#filter-category').change(function() {
                    reloadTable('#jamaah-table');
                });

                $('#filter-agent').change(function() {
                    reloadTable('#jamaah-table');
                });

                $('#filter-schedule').change(function() {
                    reloadTable('#jamaah-table');
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

            $(document).on('click', '#deleteJamaah', function(e) {
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
                            // $('#jamaah-table').DataTable().ajax.reload(null, false);
                            reloadTable('#jamaah-table');
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
