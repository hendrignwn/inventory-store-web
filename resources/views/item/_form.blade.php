@csrf
<div class="form-group">
    {!! Form::label('name', 'Nama') !!}
    {!! Form::text('name', null, ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('name'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('name') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    {!! Form::label('description', 'Deskripsi') !!}
    {!! Form::textarea('description', null, ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('description'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('description') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    {!! Form::label('sell_price', 'Harga Jual') !!}
    {!! Form::number('sell_price', null, ['class' => $errors->has('sell_price') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('sell_price'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('sell_price') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    {!! Form::label('minimum_stock', 'Stok Minimal') !!}
    {!! Form::number('minimum_stock', null, ['class' => $errors->has('minimum_stock') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('minimum_stock'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('minimum_stock') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    {!! Form::label('purchase_price', 'Harga Beli') !!}
    {!! Form::number('purchase_price', null, ['class' => $errors->has('purchase_price') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('purchase_price'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('purchase_price') }}</strong>
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('item_type_id', 'Jenis Barang') !!}
    {!! Form::select('item_type_id', [null=>'Please Select'] + \App\Models\ItemType::pluck('name', 'id')->toArray(), null, ['class' => $errors->has('item_type_id') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('purchase_price'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('purchase_price') }}</strong>
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('status', 'Status') !!}
    {!! Form::select('status', [null=>'Please Select'] + \App\Models\Item::statusLabels(), null, ['class' => $errors->has('status') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('purchase_price'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('purchase_price') }}</strong>
        </div>
    @endif
</div>

<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ 'Simpan' }}</button>
