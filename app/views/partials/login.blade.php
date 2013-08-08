@if(Sentry::check())
	Logged in as {{ Sentry::getUser()->username }}.
	@if(Sentry::getUser()->admin)
		{{ link_to_route('admin_dashboard', 'Admin', array(), array()) }}
	@endif
	@if(Sentry::getUser()->judge || Sentry::getUser()->admin)
		{{ link_to_route('judge_index', 'Judge', array(), array())}}
	@endif
	@if(Sentry::getUser()->team || Sentry::getUser()->admin)
		{{ link_to_route('team_index', 'Team', array(), array()) }}
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