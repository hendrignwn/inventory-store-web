@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ \App\Models\Customer::count('id') }}</h3>

                    <p>Customer</p>
                </div>
                <a href="{{ route('customer.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ \App\Models\Item::count('id') }}</h3>

                    <p>Barang</p>
                </div>
                <a href="{{ route('item.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ \App\Models\Transaction::count('id') }}</h3>

                    <p>Penjualan</p>
                </div>
                <a href="{{ route('transaction.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ \App\Models\Order::count('id') }}</h3>

                    <p>Pembelian</p>
                </div>
                <a href="{{ route('order.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row justify-content-center">
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
