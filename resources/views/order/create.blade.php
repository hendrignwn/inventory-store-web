@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Pembelian Barang') }}</div>

                    <div class="card-body">
                        {!! Form::open(['route' => ['order.store'], 'method' => 'post']) !!}
                        @include('order._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
