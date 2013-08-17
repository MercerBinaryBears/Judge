<div class='navbar'>
	<div class='navbar-inner'>
	@if(Sentry::check())
		<a class='brand'>{{ Sentry::getUser()->username }}</a>
		<ul class='nav'>
			@if(Sentry::getUser()->admin)
				<li> {{ link_to_route('admin_dashboard', 'Admin', array(), array()) }} </li>
			@endif
			@if(Sentry::getUser()->judge || Sentry::getUser()->admin)
				<li> {{ link_to_route('judge_index', 'Judge', array(), array())}} </li>
			@endif
			@if(Sentry::getUser()->team || Sentry::getUser()->admin)
				<li> {{ link_to_route('team_index', 'Team', array(), array()) }} </li>
			@endif
			<li><a href="/logout">Logout</a></li>
		</ul>
	@else
		{{ Form::open(array('url'=>'/login', 'class'=>'navbar-form pull-left')) }}
			<span class='input-prepend'>
				<span class='add-on'><i class='icon-user'></i></span>
				{{ Form::text('username', Input::old('username'), array('placeholder'=>'Username')) }}
			</span>
			<span class='input-prepend'>
				<span class='add-on'><i class='icon-lock'></i></span>
				{{ Form::password('password', array('placeholder'=>'Password')) }}
			</span>
			<span class='input-prepend'>
				{{ Form::submit('Login', array('class'=>'btn')) }}
			</span>
		{{ Form::close() }}
	@endif
	</div>
</div>