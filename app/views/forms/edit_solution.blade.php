@extends('layout')

@section('content')
Solution for {{ $solution->problem->name }} <br/>
Submitted by {{ $solution->user->username }} at {{ $solution->created_at }} <br/>

Submitted in {{$solution->solution_language}} <br/>

{{-- Get url for this --}}
{{ Form::open(array('url'=>"/judge/solutions/$solution->id/edit")) }}
{{ Form::label('solution_state_id', 'Solution State: ') }}
{{ Form::select('solution_state_id', $solution_states)}}
{{ Form::submit('Save')}}
{{ Form::close() }}
@stop