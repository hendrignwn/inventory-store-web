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
    {!! Form::label('phone', 'No. Telp') !!}
    {!! Form::text('phone', null, ['class' => $errors->has('phone') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('phone'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('phone') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    {!! Form::label('address', 'Alamat') !!}
    {!! Form::textarea('address', null, ['class' => $errors->has('address') ? 'form-control is-invalid' : 'form-control']) !!}
    @if($errors->has('address'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('address') }}</strong>
        </div>
    @endif
</div>
<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;{{ 'Simpan' }}</button>
