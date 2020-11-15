@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Barang') }} <a href="{{ route('item.create') }}" class="btn btn-primary">Tambah Data</a></div>

                    <div class="card-body">
                        {!! $dataTable->table(['class'=>'table table-bordered table-hover'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}" />
@stop
@section('js')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
@stop
