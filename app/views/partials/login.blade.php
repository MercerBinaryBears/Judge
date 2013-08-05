@if(Sentry::check())
	Logged in as {{ Sentry::getUser()->username }}.
	{{-- TODO: don't use this absolute path! --}}
	@if(Sentry::getUser()->admin)
		<a href="/admin">Admin</a>
		<a href="/judge">Judge</a>
		<a href="/team">Team</a>
	@elseif(Sentry::getUser()->judge)
		<a href="/judge">Judge</a>
	@elseif(Sentry::getUser()->team)
		<a href="/team">Team</a>
	@endif
	<a href="/logout">Logout</a>
@else
	{{ Form::open(array('url'=>'/login')) }}
	{{ Form::label('username','Username') }}
	{{ Form::text('username', Input::old('username')) }}
	{{ Form::label('password', 'Password') }}
	{{ Form::password('password') }}
	{{ Form::submit('Login') }} <br/>
	{{ Session::get('login_message') }}
	{{ Form::close() }}
@endif