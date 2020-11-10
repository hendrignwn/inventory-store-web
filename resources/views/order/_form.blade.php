@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div><br>
@endif

@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('order_number', 'Nomor Pembelian') !!}
            {!! Form::text('order_number', \App\Models\Order::generateOrderNumber(), ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'readonly' => true]) !!}
            @if($errors->has('order_number'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('order_number') }}</strong>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('supplier_id', 'Supplier') !!}
            {!! Form::select('supplier_id',  [null => 'Pilih Supplier'] + \App\Models\Supplier::pluck('name', 'id')->toArray(), null, ['class' => $errors->has('phone') ? 'form-control is-invalid' : 'form-control']) !!}
            @if($errors->has('supplier_id'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('supplier_id') }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Pilih Barang
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Harga Beli</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Harga Beli</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><button class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Tambah Barang</button></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ 'Simpan' }}</button>
