@extends('layouts.app')

@can('create packet')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Tambah Paket
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
                @slot('url_sub2')
                    {{ route('packets.index') }}
                @endslot
                @slot('sub2')
                    Paket
                @endslot
            @endcomponent

            <section class="section row">
                <div class="col-md-12">
                    <!-- Table -->
                    <div class="card border-1 border-primary">
                        <div class="card-header border-bottom p-2 pb-2">
                            <a href="{{ route('packets.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-danger" style="margin-top: -15px;">* Wajib diisi</p>
                            <form action="" method="post" id="savePacket" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category_id">Kategori<span class="text-danger">*</span></label>
                                            <select name="category_id" id="category_id" class="form-select mt-2 select2"
                                                required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($category as $row)
                                                    @if (old('category_id') == $row->id)
                                                        <option value="{{ $row->id }}" selected>{{ $row->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="airplane_id">Pesawat<span class="text-danger">*</span></label>
                                            <select name="airplane_id" id="airplane_id" class="form-select mt-2 select2"
                                                required>
                                                <option value="">Pilih Pesawat</option>
                                                @foreach ($airplane as $row)
                                                    @if (old('airplane_id') == $row->id)
                                                        <option value="{{ $row->id }}" selected>{{ $row->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hotel_id">Hotel</label>
                                            <select name="hotel_id" id="hotel_id" class="form-select mt-2 select2">
                                                <option value="">Pilih Hotel</option>
                                                @foreach ($list_hotel as $row)
                                                    @if (old('hotel_id') == $row->id)
                                                        <option value="{{ $row->id }}" selected>
                                                            {{ $row->name . ' [' . $row->room . ' kamar/' . money($row->cost) . ']' }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}">
                                                            {{ $row->name . ' [' . $row->room . ' kamar/' . money($row->cost) . ']' }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="mekkah_hotel_id">Mekkah Hotel<span class="text-danger">*</span></label>
                                            <select name="mekkah_hotel_id" id="mekkah_hotel_id" class="form-select mt-2 select2"
                                                required>
                                                <option value="">Pilih Hotel Makkah</option>
                                                @foreach ($hotel_mekkah as $row)
                                                    @if (old('mekkah_hotel_id') == $row->id)
                                                        <option value="{{ $row->id }}" selected>
                                                            {{ $row->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}">
                                                            {{ $row->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="madinah_hotel_id">Madinah Hotel<span
                                                    class="text-danger">*</span></label>
                                            <select name="madinah_hotel_id" id="madinah_hotel_id"
                                                class="form-select mt-2 select2" required>
                                                <option value="">Pilih Hotel Madinah</option>
                                                @foreach ($hotel_madinah as $row)
                                                    @if (old('madinah_hotel_id') == $row->id)
                                                        <option value="{{ $row->id }}" selected>
                                                            {{ $row->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}">
                                                            {{ $row->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Nama Paket<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control mt-2 text-capitalize" id="title"
                                                name="title" value="{{ old('title') }}" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">Gambar</label>
                                            <img class="img-preview img-fluid mb-3">
                                            <input type="file" class="form-control mt-2" id="image" name="image">
                                            <small>Ukuran maksimal file <strong>2 MB</strong>. Tipe file
                                                <strong>.jpg .jpeg .png</strong></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cost">Harga<span class="text-danger">*</span></label>
                                        <div class="input-group mt-2">
                                            <span class="input-group-text" id="cost-addon">Rp.</span>
                                            <input type="text" class="form-control uang" id="cost" name="cost"
                                                aria-describedby="cost-addon" value="{{ old('cost') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="discount">Diskon</label>
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control angka" id="discount" name="discount"
                                                value="{{ old('discount', 0) }}" aria-describedby="discount-addon">
                                            <span class="input-group-text" id="discount-addon">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="mb-2">Keterangan</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="form-control editorTinyMCE">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between m-0">
                                    <button type="reset" class="btn icon icon-left block" id="btnBatal">
                                        <i class="bi bi-x-circle"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn icon icon-left btn-primary block" id="btnSave"
                                        onclick="tinyMCE.triggerSave();">
                                        <i class="bi bi-save"></i>
                                        Simpan
                                    </button>
                                </div>
                            </form>
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
                $('[name="category_id"]').val("");
                $('[name="airplane_id"]').val("");
                $('[name="mekkah_hotel_id"]').val("");
                $('[name="madinah_hotel_id"]').val("");
                $('[name="title"]').val("");
                $('[name="image"]').val("");
                $('[name="cost"]').val("");
                $('[name="discount"]').val("");
                $('[name="description"]').val("");
                $("#savePacket").attr('action', '');
                $("#btnSave").html('<i class="bi bi-save"></i> Simpan');
                $('[name="title"]').focus();
            }

            $('#btnBatal').on('click', function() {
                clearForm();
            });

            $('#savePacket').on('submit', function(e) {
                e.preventDefault();
                let spin =
                    '<div class="spinner-border spinner-border-sm text-white" role="status"><span class="visually-hidden">Loading...</span></div>';
                $("#btnSave").html(spin + ' Processing...').attr('disabled', 'disabled');

                let link = "{{ route('packets.store') }}";
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
                        $("#savePacket").attr('action', '');
                        $("#btnSave").html('<i class="bi bi-save"></i> Simpan').removeAttr('disabled');
                        //delete field
                        clearForm();
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success'
                        }).then(function($q) {
                            window.location.reload();
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

            //for select
            // var path = "{{ route('packets.index') }}";
            // $('#unitkerja').select2({
            //     ajax: {
            //         url: path,
            //         delay: 450,
            //         type: "POST",
            //         data: function(params) {
            //             return {
            //                 "_token": "{{ csrf_token() }}",
            //                 q: params.term, // search term
            //             };
            //         },
            //         processResults: function({
            //             data
            //         }) {
            //             return {
            //                 results: $.map(data, function(item) {
            //                     return {
            //                         text: `${item.nama_unit} (${item.jabatan_pimpinan})`,
            //                         id: item.id,
            //                     }
            //                 })
            //             };
            //         },
            //         cache: true
            //     }
            // });
        </script>
    @endpush

@endcan
