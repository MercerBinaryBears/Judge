<span class="admin-link">{{ Auth::user()->username }}</span>

@if(Auth::user()->admin)
 <a class="admin-link" href="/admin"><i data-toggle="tooltip" data-placement="right" title="Admin" class="fa fa-database"></i></a>
@endif

@if(Auth::user()->judge || Auth::user()->admin)
<a class="admin-link" href="/solutions"><i data-toggle="tooltip" data-placement="right" title="Judge"class="fa fa-gavel"></i></a>
@endif

@if(Auth::user()->team)
<a class="admin-link" href="/solutions"><i data-toggle="tooltip" data-placement="right" title="Submit" class="fa fa-code"></i></a>
@endif

<a class="admin-link" href="/messages"><i data-toggle="tooltip" data-placement="right" title="Messages" class="fa fa-envelope-o"></i></a>
<a class="admin-link" href="/logout"><i data-toggle="tooltip" data-placement="right" title="Logout" class="fa fa-sign-out"></i></a>
