<div class='navbar navbar-default navbar-fixed-top' role="navigation">
    <a class="navbar-brand nav-logo-a" style="padding:5px 5px;">
		<img src='/logo.png' alt='Judge Logo' class="logo nav-logo" style="height: 40px;"/>
    </a>
    <a class="navbar-brand"> {{ $contest_name }} </a>
	@if(Auth::check())
        @include('partials.nav_links')
	@else
        @include('forms.login')
	@endif
</div>
