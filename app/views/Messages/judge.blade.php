@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        <h3>Post Global Message</h3>
        @include('forms.create_message')

    </div>
</div>
@stop
