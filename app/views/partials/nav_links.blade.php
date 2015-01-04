<a class='navbar-brand'>{{ Auth::user()->username }}</a>
<ul class='nav navbar-nav'>
    <li>{{ link_to('/', 'Scoreboard') }}</li>
    @if(Auth::user()->admin)
        <li> {{ link_to_route('admin_dashboard', 'Admin', array(), array()) }} </li>
    @endif
    @if(Auth::user()->judge || Auth::user()->admin)
        <li> {{ link_to_route('solutions.index', 'Judge Problems', array(), array())}} </li>
    @endif
    @if(Auth::user()->team)
        <li> {{ link_to_route('solutions.index', 'Submit Problems', array(), array()) }} </li>
    @endif
    <li>{{ link_to_route('logout', 'Logout') }}</li>
</ul>
