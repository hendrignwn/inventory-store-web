@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Barang dibawah Stok Minimum') }}</div>

                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Stok Minimum</th>
                            <th>Sisa Stok</th>
                        </tr>
                        @if (count($itemStocks) <= 0)
                            <tr>
                                <td colspan="4">Data tidak ada</td>
                            </tr>
                        @else
                            @foreach ($itemStocks as $key => $stock)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->minimum_stock }}</td>
                                    <td>{{ $stock->stock }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Barang Baru / Barang belum ada stok') }}</div>

                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Stok Minimum</th>
                            <th>Dibuat</th>
                        </tr>
                        @if (count($itemsNew) <= 0)
                            <tr>
                                <td colspan="4">Data tidak ada</td>
                            </tr>
                        @else
                            @foreach ($itemsNew as $key => $stock)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->minimum_stock }}</td>
                                    <td>{{ $stock->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    @section('plugins.Datatables', true)
</div>
@endsection
