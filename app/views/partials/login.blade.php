@if(Sentry::check())
	Logged in as {{ Sentry::getUser()->username }}.
	{{-- TODO: don't use this absolute path! --}}
	@if(Sentry::getUser()->admin)
		<a href="/admin">Admin</a>
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