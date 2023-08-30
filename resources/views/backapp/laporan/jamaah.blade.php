@extends('layouts.app')

@can('read jamaah')

    @section('content')
        <div class="page-heading" style="margin-top: -20px;">
            @component('components.breadcrumb')
                @slot('menu')
                    Laporan Jamaah
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
                        <form action="{{ route('jamaahs.export_excel') }}" method="post" target="_blank">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <h4 class="card-title">Filter Jamaah</h4>
                                    <div class="col-md-3 col-12">
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
                                    <div class="col-md-3 col-12">
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
                                    <div class="col-md-3 col-12">
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
                                    <div class="col-md-3 col-12">
                                        <button type="submit" class="btn btn-block btn-primary">
                                            <i class="bi bi-file-excel"></i>
                                            Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    @endsection

@endcan
