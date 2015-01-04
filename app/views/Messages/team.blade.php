@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        <h3>Submit A Message</h3>
        @include('forms.create_message')
    </div>
    <div class="col-md-4">
        <h3>My Messages</h3> 
    </div>
    <div class="col-md-4">
        <h3>Global Messages</h3>
    </div>
</div>
@stop
