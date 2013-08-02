Solution for {{ $solution->problem->name }} <br/>
Submitted by {{ $solution->user->username }} at {{ $solution->created_at }} <br/>

Submitted in (SOME LANGUAGE) <br/>{{-- TODO: Add language listing --}}

{{-- Get url for this --}}
{{ Form::model($solution) }}
{{-- TODO: add drop down for solution state --}}
{{ Form::close() }}