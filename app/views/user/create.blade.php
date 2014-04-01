@extends('layouts.master')

@section('body')

<br/>

{{ Form::model($user, array('action' => 'user.store', 'role' => 'form', 'class' => 'form-horizontal')) }}

@if (count($errors))
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-group">
    {{ Form::label('name', "Name", array('class' => 'col-sm-2 control-label')) }}
    <div class="col-sm-10">{{ Form::text('name', null, array('class' => 'form-control')) }}</div>
</div>

<div class="form-group">
    {{ Form::label('email', "Email", array('class' => 'col-sm-2 control-label')) }}
    <div class="col-sm-10">{{ Form::email('email', null, array('class' => 'form-control')) }}</div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button class="btn btn-success" type="submit">Create</button>
    </div>
</div>

{{ Form::close() }}

@stop
