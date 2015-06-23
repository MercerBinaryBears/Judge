<div class="sidebar">
    <a class="admin-link" href="/">
        <img src='/logo.png' alt='Judge Logo' class="logo nav-logo" style="height: 40px;"/>
    </a>
    @if(Auth::check())
        @include('partials.nav_links')
    @else
        @include('forms.login')
    @endif
</div>
