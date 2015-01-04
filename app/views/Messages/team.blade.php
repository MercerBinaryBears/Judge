@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        <h3>Submit A Message</h3>
        @include('forms.create_message')
    </div>
    <div class="col-md-4">
        <h3>My Messages</h3> 
        @foreach($messages as $message)
            @include('Messages.single_message')
        @endforeach
    </div>
    <div class="col-md-4">
        <h3>Global Messages</h3>
        @foreach($global_messages as $message)
            @include('Messages.single_global_message')
        @endforeach
    </div>
</div>
@stop
