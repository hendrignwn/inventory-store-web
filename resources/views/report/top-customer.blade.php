@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Laporan Top Customer') }}</div>

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
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}" />
@stop
@section('js')
    <script src="{{ asset('js/jquery.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/jszip.min.js') }}"></script>
    <script src="{{ asset('js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/buttons.print.min.js') }}"></script>
    {!! $dataTable->scripts() !!}
@stop
