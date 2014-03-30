@extends('layouts.master')

@section('body')

<br/>

{{ Form::model($match, array('action' => 'match.store', 'role' => 'form')) }}

@if (count($errors))
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row form-group">
    <div class="col-lg-2 col-lg-offset-3 col-xs-5">
        {{ Form::selectRange('score_player1', 0, 10, null, array('class' => 'form-control')) }}
    </div>
    <div class="col-lg-2 col-xs-2">
        <p class="text-center lead">vs</p>
    </div>
    <div class="col-lg-2 col-xs-5">
        {{ Form::selectRange('score_player2', 0, 10, null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="row form-group">
    <div class="col-lg-4 col-lg-offset-2 col-xs-6 text-right">
        {{ Form::select('player1', $players, null, array('class' => 'form-control')) }}<br/>
        {{ Form::select('player3', $players, null, array('class' => 'form-control doublesPlayers')) }}
    </div>

    <div class="col-lg-4 col-xs-6">          
        {{ Form::select('player2', $players, null, array('class' => 'form-control')) }}<br/>
        {{ Form::select('player4', $players, null, array('class' => 'form-control doublesPlayers')) }}
    </div>
</div>
  
<div class="row form-group">
    <div class="col-lg-4 col-lg-offset-4">
        <button type="button" class="btn btn-info form-control" id="playDoubles">Doubles</button>
    </div>
</div>

<div class="row form-group">
    <div class="col-lg-4 col-lg-offset-4">
        <input class="btn btn-success form-control" type="submit" value="Submit">
    </div>
</div>

{{ Form::close() }}
<script type="text/javascript">
$(function() {
    $(".doublesPlayers").prop("disabled", true).hide();

    $("#playDoubles").bind('click', function(){
        $(".doublesPlayers").prop("disabled", false).show();
        $(this).hide();
    });
})
</script>
<br/>

@stop
