
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboards.index') }}">Villa</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboards.index') }}">Villa</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Home</li>
            <li class="@if (request()->routeIs('admin.dashboards.*')) active @endif"><a class="nav-link" href="{{ route('admin.dashboards.index') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        </ul>
    </aside>
</div>
