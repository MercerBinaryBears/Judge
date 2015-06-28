@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-4">
        @include('forms.create_solution')
        <hr/>
    </div>
    <div class="col-md-8">
        @foreach($solutions as $solution)
            @include('Solutions.solution', compact('solution'))
        @endforeach
    </div>
</div>

@stop
