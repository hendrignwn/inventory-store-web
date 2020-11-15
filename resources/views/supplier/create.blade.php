@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Supplier') }}</div>

                    <div class="card-body">
                        {!! Form::open(['route' => ['supplier.store'], 'method' => 'post']) !!}
                        @include('supplier._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
