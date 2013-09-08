<div class='navbar'>
	<div class='navbar-inner'>
	@if(Auth::check())
		<a class='brand'>{{ Auth::user()->username }}</a>
		<ul class='nav'>
			@if(Auth::user()->admin)
				<li> {{ link_to_route('admin_dashboard', 'Admin', array(), array()) }} </li>
			@endif
			@if(Auth::user()->judge || Auth::user()->admin)
				<li> {{ link_to_route('judge_index', 'Judge', array(), array())}} </li>
			@endif
			@if(Auth::user()->team || Auth::user()->admin)
				<li> {{ link_to_route('team_index', 'Team', array(), array()) }} </li>
			@endif
			<li>{{ link_to_route('logout', 'Logout') }}</li>
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