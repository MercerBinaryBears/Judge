<ul class='nav navbar-nav'>
    <li><a>{{ Auth::user()->username }}</a></li>
    @if(Auth::user()->admin)
        <li> <a href="/admin"><i class="fa fa-database"></i></a></li>
    @endif
    @if(Auth::user()->judge || Auth::user()->admin)
        <li> <a href="/solutions"><i class="fa fa-gavel"></i></a></li>
    @endif
    @if(Auth::user()->team)
        <li> <a href="/solutions"><i class="fa fa-code"></i></a></li>
    @endif
    <li><a href="/messages"><i class="fa fa-envelope-o"></i></a></li>
    <li><a href="/logout"><i class="fa fa-sign-out"></i></a></li>
</ul>
