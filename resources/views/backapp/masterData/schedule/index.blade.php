@extends('layouts.app')

@can('read schedule')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Jadwal
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
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        @can('create schedule')
                            <div class="card-header border-bottom p-3 pb-3">
                                <a href="{{ route('schedules.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i>
                                    Tambah
                                </a>
                            </div>
                        @endcan
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

            $(document).on('click', '#deleteSchedule', function(e) {
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
                            $('#schedule-table').DataTable().ajax.reload(null, false);
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
