<style>
    table th {
        text-align: left;
    }
</style>
<h3>Cetak Transaksi Penjualan Barang</h3>
<table>
    <tr>
        <th>Nomor Transaksi</th>
        <td>{!! $model->trx_number !!}</td>
    </tr>
    <tr>
        <th>Tanggal Transaksi</th>
        <td>{!! \Carbon\Carbon::parse($model->created_at)->format('d/M/Y H:i:s') !!}</td>
    </tr>
    <tr>
        <th>Customer</th>
        <td>{!! $model->customer->name !!}</td>
    </tr>
    <tr>
        <th>Total Keseluruhan</th>
        <td>{!! \App\Helpers\FormatConverter::rupiahFormat($model->grand_total) !!}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{!! $model->getStatusLabel() !!}</td>
    </tr>
    <tr>
        <th>User Input</th>
        <td>{!! $model->user->name !!}</td>
    </tr>
</table>
<table width="100%" border="1">
    <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Harga Barang</th>
        <th>Jumlah</th>
        <th>Total Harga</th>
    </tr>
    @foreach ($model->transactionDetails as $key => $orderDetail)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{!! $orderDetail->item->name !!}</td>
            <td>{!! \App\Helpers\FormatConverter::rupiahFormat($orderDetail->price) !!}</td>
            <td>{!! $orderDetail->qty !!}</td>
            <td>{!! \App\Helpers\FormatConverter::rupiahFormat($orderDetail->total_price) !!}</td>
        </tr>
    @endforeach
    <tr>
        <th class="text-right" colspan="4">Total Keseluruhan</th>
        <th>{!! \App\Helpers\FormatConverter::rupiahFormat($model->grand_total) !!}</th>
    </tr>
</table>
