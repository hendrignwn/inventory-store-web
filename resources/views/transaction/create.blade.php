@extends('adminlte::page')

@section('content')
    <div class="container" id="OrderForm">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Tambah Transaksi Barang') }}</div>

                    <div class="card-body">
                        {!! Form::open(['route' => ['transaction.store'], 'method' => 'post']) !!}
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
                                    {!! Form::label('trx_number', 'Nomor Transaksi') !!}
                                    {!! Form::text('trx_number', \App\Models\Transaction::generateTrxNumber(), ['class' => $errors->has('trx_number') ? 'form-control is-invalid' : 'form-control', 'readonly' => true, 'required'=>true]) !!}
                                    @if($errors->has('trx_number'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('trx_number') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('customer_id', 'Customer') !!}
                                    {!! Form::select('customer_id',  [null => 'Pilih'] + \App\Models\Customer::pluck('name', 'id')->toArray(), null, ['class' => $errors->has('customer_id') ? 'form-control is-invalid' : 'form-control', 'required'=>true]) !!}
                                    @if($errors->has('customer_id'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('customer_id') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('status', 'Status') !!}
                                    {!! Form::select('status', [null=>'Please Select'] + \App\Models\Transaction::statusLabels(), null, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control']) !!}
                                    @if($errors->has('status'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('status') }}</strong>
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
                                        <th>Harga Jual</th>
                                        <th>Sisa Stok</th>
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
                itemSelect += "<option value=''>Pilih</option>"
                itemsJson.map(result => {
                    itemSelect += "<option value='"+result.id+"'>"+result.name+"</option>"
                })
            }
            let itemsHtml = "<tr>\n" +
                "<th>"+ (number + 1) +"</th>\n" +
                "<th><select name='item["+number+"]' class='form-control' required onchange='onChangeItem("+number+", this)'>"+itemSelect+"</select></th>\n" +
                "<th><input name='price["+number+"]' type='number' class='form-control' required readonly></th>\n" +
                "<th><div id='stock_"+number+"'></div></th>\n" +
                "<th><input name='qty["+number+"]' type='number' class='form-control' onkeyup='onChangeQty("+number+")' required></th>\n" +
                "<th><input name='total_price["+number+"]' type='number' readonly class='form-control' /></th>\n" +
                "</tr>"
            $('#items').append(itemsHtml)
            number++
        })
        function onChangeQty(number) {
            let price = $('#items').find('input[name="price['+number+']"]').val()
            let total = $('#items').find('input[name="total_price['+number+']"]')
            let stock = $('#stock_' + number).html()
            let qty = $('#items').find('input[name="qty['+number+']"]')
            if (parseInt(qty.val()) > parseInt(stock)) {
                alert('Jumlah melebihi stock')
                qty.val(stock)
                return
            }
            total.val(parseFloat(price) * parseFloat(qty.val()))
            totalArray[number] = total.val()
            $('#total').val(totalArray.reduce(sum))
        }
        function sum(total, num) {
            return parseFloat(total) + parseFloat(num)
        }
        function onChangeItem(number, itemId) {
            console.log(number)
            if (itemId.value === '') {
                alert('Pilih Barang')
                return
            }
            $.ajax({
                url: '/transaction/ajax-get-item/' + itemId.value,
                success: function(result) {
                    console.log(result)
                    if (result.success) {
                        $('#items').find('input[name="price['+number+']"]').val(result.data.sell_price)
                        $('#stock_' + number).html(result.data.stock)
                    }
                }
            })
        }

    </script>
@endpush
