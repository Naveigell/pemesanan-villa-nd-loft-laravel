
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
            <li class="menu-header">Additional</li>
            <li class="@if (request()->routeIs('admin.rooms.*')) active @endif"><a class="nav-link" href="{{ route('admin.rooms.index') }}"><i class="fas fa-bed"></i> <span>Kamar</span></a></li>
            <li class="@if (request()->routeIs('admin.facilities.*')) active @endif"><a class="nav-link" href="{{ route('admin.facilities.index') }}"><i class="fas fa-broom"></i> <span>Facilitas</span></a></li>
            <li class="menu-header">Pemesanan</li>
            <li class="@if (request()->routeIs('admin.bookings.*')) active @endif"><a class="nav-link" href="{{ route('admin.bookings.index') }}"><i class="fas fa-book"></i> <span>Booking</span></a></li>
            <li class="@if (request()->routeIs('admin.calendars.*')) active @endif"><a class="nav-link" href="{{ route('admin.calendars.index') }}"><i class="fas fa-calendar-plus"></i> <span>Kalender</span></a></li>
            <li class="menu-header">Report</li>
            <li class="@if (request()->routeIs('admin.reports.*')) active @endif"><a class="nav-link" href="{{ route('admin.reports.index') }}"><i class="fas fa-print"></i> <span>Laporan</span></a></li>
        </ul>
    </aside>
</div>
