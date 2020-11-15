@extends('adminlte::page')

@section('content')
    <div class="container" id="OrderForm">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Pembelian Barang') }}</div>

                    <div class="card-body">
                        {!! Form::open(['route' => ['order.store'], 'method' => 'post']) !!}
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
                                    {!! Form::text('order_number', \App\Models\Order::generateOrderNumber(), ['class' => $errors->has('order_number') ? 'form-control is-invalid' : 'form-control', 'readonly' => true, 'required'=>true]) !!}
                                    @if($errors->has('order_number'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('order_number') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('order_date', 'Tanggal Pembelian') !!}
                                    {!! Form::date('order_date', null, ['class' => $errors->has('order_number') ? 'form-control is-invalid' : 'form-control', 'required'=>true]) !!}
                                    @if($errors->has('order_date'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('order_date') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('supplier_id', 'Supplier') !!}
                                    {!! Form::select('supplier_id',  [null => 'Pilih Supplier'] + \App\Models\Supplier::pluck('name', 'id')->toArray(), null, ['class' => $errors->has('phone') ? 'form-control is-invalid' : 'form-control', 'required'=>true]) !!}
                                    @if($errors->has('supplier_id'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('supplier_id') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('status', 'Status') !!}
                                    {!! Form::select('status', [null=>'Please Select'] + \App\Models\Order::statusLabels(), null, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control']) !!}
                                    @if($errors->has('status'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('note', 'Catatan') !!}
                                    {!! Form::textarea('note', null, ['class' => $errors->has('note') ? 'form-control is-invalid' : 'form-control']) !!}
                                    @if($errors->has('note'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('note') }}</strong>
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
                                        <th width="10">No</th>
                                        <th>Barang</th>
                                        <th>Harga Beli</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                    </tr>
                                    </thead>
                                    <tbody id="items">
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4"><a href="javascript:;;" id="add-item" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Tambah Barang</a></td>
                                        <td>
                                            {!! Form::label('total', 'Total Keseluruhan') !!}
                                            <input type="number" name="grand_total" id="total" class="form-control" readonly />
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ 'Simpan' }}</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        let number = 0
        let totalArray = []
        $('#add-item').click(function() {
            const itemsJson = @json($items);
            let itemSelect = '';
            if (itemsJson.length > 0) {
                itemsJson.map(result => {
                    itemSelect += "<option value='"+result.id+"'>"+result.name+"</option>"
                })
            }
            let itemsHtml = "<tr>\n" +
                "<th>"+ (number + 1) +"</th>\n" +
                "<th><select name='item["+number+"]' class='form-control' required>"+itemSelect+"</select></th>\n" +
                "<th><input name='purchase_total["+number+"]' type='number' class='form-control' onkeyup='onChange("+number+")' required></th>\n" +
                "<th><input name='qty["+number+"]' type='number' class='form-control' onkeyup='onChange("+number+")' required></th>\n" +
                "<th><input name='total["+number+"]' type='number' readonly class='form-control' /></th>\n" +
                "</tr>"
            $('#items').append(itemsHtml)
            number++
        })
        function onChange(number) {
            let price = $('#items').find('input[name="purchase_total['+number+']"]').val()
            let total = $('#items').find('input[name="total['+number+']"]')
            let qty = $('#items').find('input[name="qty['+number+']"]').val()
            if (price == '' || price == undefined) price = 0
            if (qty == '' || qty == undefined) qty = 0
            total.val(parseFloat(price) * parseFloat(qty))
            totalArray[number] = total.val()

            $('#total').val(totalArray.reduce(sum))
        }
        function sum(total, num) {
            return parseFloat(total) + parseFloat(num)
        }
    </script>
@endpush
