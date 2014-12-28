<div class='navbar navbar-default navbar-fixed-top' role="navigation">
    <a class="navbar-brand nav-logo-a" style="padding:5px 5px;">
		<img src='/logo.png' alt='Judge Logo' class="logo nav-logo" style="height: 40px;"/>
    </a>
    <a class="navbar-brand"> {{ $contest_name }} </a>
	@if(Auth::check())
		<a class='navbar-brand'>{{ Auth::user()->username }}</a>
		<ul class='nav navbar-nav'>
			<li>{{ link_to('/', 'Scoreboard') }}</li>
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
		{{ Form::open(array('url'=>'/login', 'class'=>'navbar-form navbar-left')) }}
            <div class="form-group">
                <div class='input-group'>
                    <span class='input-group-addon'>
                        <span class='glyphicon glyphicon-user'></span>
                    </span>
                    {{ Form::text('username', Input::old('username'), array('placeholder'=>'Username', 'class' => 'form-control')) }}
                </div>
                <div class='input-group'>
                    <span class='input-group-addon'>
                        <span class='glyphicon glyphicon-lock'></span>
                    </span>
                    {{ Form::password('password', array('placeholder'=>'Password', 'class' => 'form-control')) }}
                </div>
                <span class='input-group'>
                    {{ Form::submit('Login', array('class'=>'btn btn-info')) }}
                </span>
            </div>
		{{ Form::close() }}
	@endif
</div>
