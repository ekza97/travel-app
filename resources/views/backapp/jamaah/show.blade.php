@extends('layouts.app')

@can('show jamaah')

    @section('content')
        <div class="page-heading">
            @component('components.breadcrumb')
                @slot('menu')
                    Detail Jamaah
                @endslot
                @slot('url_sub1')
                    {{ route('home') }}
                @endslot
                @slot('sub1')
                    Dashboard
                @endslot
                @slot('url_sub2')
                    {{ route('jamaahs.index') }}
                @endslot
                @slot('sub2')
                    Jamaah
                @endslot
            @endcomponent
            <section class="section">
                <div class="card">
                    <div class="card-header py-2">
                        <a href="{{ route('jamaahs.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                    <hr class="mt-0">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="card-title mb-2">A. DATA JAMAAH</h3>
                            <hr>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">Kategori</label>
                                    <select name="category_id" id="category_id" class="form-select mt-1 select2" disabled>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $row)
                                            @if (old('category_id', $data->category_id) == $row->id)
                                                <option value="{{ $row->id }}" selected>
                                                    {{ $row->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="agent_id">Agent</label>
                                    <select name="agent_id" id="agent_id" class="form-select mt-1 select2" disabled>
                                        <option value="">Pilih Agent</option>
                                        @foreach ($agents as $row)
                                            @if (old('agent_id', $data->agent_id) == $row->id)
                                                <option value="{{ $row->id }}" selected>
                                                    {{ $row->fullname . ' [' . $row->phone . ']' }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="schedule_id">Jadwal Berangkat</label>
                                    <select name="schedule_id" id="schedule_id" class="form-select mt-1 select2" disabled>
                                        <option value="">Pilih Jadwal</option>
                                        @foreach ($schedules as $row)
                                            @if (old('schedule_id', $data->schedule_id) == $row->id)
                                                <option value="{{ $row->id }}" selected>{{ $row->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nik">Nomor Induk Kependudukan (NIK)</label>
                                    <input type="text" class="form-control mt-1 nik" id="nik" name="nik"
                                        value="{{ old('nik', $data->nik) }}" minlength="16" disabled>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="fullname">Nama Lengkap</label>
                                    <input type="text" class="form-control mt-1 text-capitalize" id="fullname"
                                        name="fullname" value="{{ old('fullname', $data->fullname) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pob">Tempat Lahir</label>
                                    <input type="text" class="form-control mt-1 text-capitalize" id="pob"
                                        name="pob" value="{{ old('pob', $data->pob) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dob">Tanggal Lahir</label>
                                    <input type="date" class="form-control mt-1 flatpicker" id="dob" name="dob"
                                        value="{{ old('dob', $data->dob) }}" max="{{ date('Y-m-d') }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="form-select mt-1" disabled>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ $data->gender == 'L' ? 'selected' : '' }}>Laki-Laki
                                        </option>
                                        <option value="P" {{ $data->gender == 'P' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">Nomor HP</label>
                                    <input type="text" class="form-control phone mt-1" id="phone" name="phone"
                                        value="{{ old('phone', $data->phone) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="martial_status">Status Nikah</label>
                                    <select name="martial_status" id="martial_status" class="form-select mt-1" disabled>
                                        <option value="">Pilih Status Kawin</option>
                                        <option value="Belum Menikah"
                                            {{ $data->martial_status == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah
                                        </option>
                                        <option value="Sudah Menikah"
                                            {{ $data->martial_status == 'Sudah Menikah' ? 'selected' : '' }}>Sudah Menikah
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="profession">Profesi</label>
                                    <input type="text" class="form-control mt-1 text-capitalize" id="profession"
                                        name="profession" value="{{ old('profession', $data->profession) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address" class="mb-2">Alamat Lengkap</label>
                                    <textarea name="address" id="address" cols="30" rows="3" class="form-control" disabled>{{ old('address', $data->address) }}</textarea>
                                </div>
                            </div>
                            <hr>
                            <h3 class="card-title mt-3 mb-2">B. DATA KELUARGA</h3>
                            <hr>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="heir_name">Nama Ahli Waris</label>
                                    <input type="text" class="form-control mt-1 text-capitalize" id="heir_name"
                                        name="heir_name" value="{{ old('heir_name', $data->heir_name) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="heir_relation">Hubungan Ahli Waris</label>
                                    <input type="text" class="form-control mt-1" id="heir_relation" name="heir_relation"
                                        value="{{ old('heir_relation', $data->heir_relation) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="heir_phone">Nomor HP Ahli Waris</label>
                                    <input type="text" class="form-control phone mt-1" id="heir_phone" name="heir_phone"
                                        value="{{ old('heir_phone', $data->heir_phone) }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a href="{{ route('jamaahs.edit', Crypt::encrypt($data->id)) }}"
                            class="btn btn-primary block float-end mb-2"><i class="bi bi-pencil-square"></i>
                            Edit Data</a>
                    </div>
                </div>
            </section>
        </div>
    @endsection
@endcan
