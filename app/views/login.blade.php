@extends('layout')

@section('content')
<form action="/login" role="form" class="form" method="post">
    {{ Form::text('username', Input::old('username'), array('placeholder' => 'Username', 'class' => 'form-control')) }}
    {{ Form::password('password', array('placeholder'=>'Password', 'class' => 'form-control')) }}
    {{ Form::submit('Login', array('class'=>'btn btn-info')) }}
</form>
@stop
