<div class="sidebar">
    <a class="admin-link" href="/">
        <img src='/logo.png' alt='Judge Logo'/>
    </a>
    @if(Auth::check())
        @include('partials.nav_links')
    @else
        @include('forms.login')
    @endif
</div>
