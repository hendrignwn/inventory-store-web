@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Edit Barang') }}</div>

                    <div class="card-body">
                        {!! Form::model($model, ['route' => ['item.update', $model->id], 'method' => 'patch']) !!}
                        @include('item._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
