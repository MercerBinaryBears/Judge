<div class="sidebar">
    <a class="admin-link" href="/">
        <img src='/logo.png' alt='Judge Logo'/>
    </a>
    @if(Auth::check())
        @include('partials.nav_links')
    @else
        <a class="admin-link" href="/login">
            <i data-toggle="tooltip" data-placement="right" title="Login" class="fa fa-sign-in"></i> 
        </a>
    @endif
</div>
