@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <select class="select2">
                                <option id="Ok">ASd</option>
                        </select>

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
    @section('plugins.Datatables', true)
</div>
@endsection
