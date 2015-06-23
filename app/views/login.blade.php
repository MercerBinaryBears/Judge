@extends('layout')

@section('content')
<form action="/login" role="form" class="form login-form" method="post">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="text-center">
                <img src="/logo.png" style="height:5em;" />
            </div>
            {{ Form::text('username', Input::old('username'), array('placeholder' => 'Username', 'class' => 'form-control')) }}
            {{ Form::password('password', array('placeholder'=>'Password', 'class' => 'form-control')) }}
            {{ Form::submit('Login', array('class'=>'btn btn-info')) }}
        </div>
    </div>
</form>
@stop
