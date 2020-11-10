@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Customer') }}</div>

                    <div class="card-body">
                        {!! Form::open(['route' => ['customer.store'], 'method' => 'post']) !!}
                        @include('customer._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
