@extends('layouts.app')

@can('read payment')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Pembayaran Jamaah
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
                        <form action="" method="post" enctype="multipart/form-data" id="savePayment">
                            @csrf
                            <h4 class="card-title px-3 py-2 mb-0 border-bottom">Form Pembayaran</h4>
                            <div class="card-body p-3 mb-0">
                                <div class="row">
                                    <div class="col-md-5 col-12">
                                        <div class="form-group">
                                            <label for="jamaah_id">Jamaah<span class="text-danger">*</span></label>
                                            <select name="jamaah_id" id="filter-jamaah" class="form-select select2" required>
                                                <option value="">Pilih Jamaah</option>
                                                @foreach ($jamaahs as $row)
                                                    <option value="{{ $row->id }}">{{ $row->fullname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date">Tanggal<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control flatpicker" id="date" name="date"
                                                value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pay">Jumlah<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="pay-addon">Rp.</span>
                                            <input type="text" class="form-control uang" id="pay" name="pay"
                                                aria-describedby="pay-addon" value="{{ old('pay') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="form-group mb-0">
                                            <label for="file">Bukti Bayar</label>
                                            <div class="input-group mb-1">
                                                <input type="file" class="form-control" name="file" id="inputGroupFile01"
                                                    aria-describedby="inputGroupFileAddon01" aria-label="Unggah"
                                                    accept=".png, .jpg, .jpeg, .pdf">
                                            </div>
                                            <small class="text-danger">Ukuran maksimal file <strong>2 MB</strong>.
                                                Type file yang diunggah <strong>.png .jpg .jpeg .pdf</strong></small>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="description">Keterangan</label>
                                            <textarea name="description" id="description" cols="30" rows="3" class="form-control text-capitalize">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer px-3 py-2 text-end">
                                <button type="submit" class="btn btn-primary" id="btnSave"><i class="bi bi-save"></i>
                                    Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Table -->
                <div class="col-md-12">
                    <div class="card border-1 border-primary">
                        <div class="card-body table-responsive p-4">
                            <table class="table table-hover" id="payment-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            NIK
                                            <hr class="p-0 m-0">
                                            Nama Jamaah
                                        </th>
                                        <th>
                                            Tempat, Tanggal Lahir
                                            <hr class="p-0 m-0">
                                            Jenis Kelamin
                                        </th>
                                        <th>Tanggal</th>
                                        <th>Angsuran</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
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
                $('#payment-table').DataTable({
                    ordering: true,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        'url': "{{ route('payments.table') }}",
                        'data': function(q) {
                            q.jamaah = $('#filter-jamaah').val();
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
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'pay',
                            name: 'pay'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'description',
                            name: 'description'
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
                    reloadTable('#payment-table');
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
                $('[name="pay"]').val("");
                $('[name="file"]').val("");
                $('[name="description"]').val("");
                $("#savePayment").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
            }

            $('table').on('draw.dt', function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            $('#savePayment').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('payments.store') }}";
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
                        $("#savePayment").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            // window.location.reload();
                            reloadTable('#payment-table');
                            // window.location = "{{ route('packets.create') }}";
                        });
                        // toastr.success(response.message, 'SUCCESS');
                        // setTimeout(() => {
                        //     window.location.reload();
                        // }, 2500);
                    },
                    error: function(response) {
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        toastr.error('Proses menyimpan error: ' + response.responseText, 'ERROR');
                    },
                });
            });

            $(document).on('click', '#deletePayment', function(e) {
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
                            // $('#payment-table').DataTable().ajax.reload(null, false);
                            reloadTable('#payment-table');
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
