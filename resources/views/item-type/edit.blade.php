@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Edit Jenis Barang') }}</div>

                    <div class="card-body">
                        {!! Form::model($model, ['route' => ['item-type.update', $model->id], 'method' => 'patch']) !!}
                        @include('item-type._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
