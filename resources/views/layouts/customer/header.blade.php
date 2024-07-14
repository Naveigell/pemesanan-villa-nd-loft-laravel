<header class="site-header js-site-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6 col-lg-4 site-logo" data-aos="fade"><a href="{{ route('index') }}">Villa Nd Loft</a></div>
            <div class="col-6 col-lg-8">

                <style>
                    .menu-icon {
                        font-size: 30px;
                    }
                </style>

                <div class="site-menu-toggle js-site-menu-toggle"  data-aos="fade">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <!-- END menu-toggle -->

                <div class="site-navbar js-site-navbar">
                    <nav role="navigation">
                        <div class="container">
                            <div class="row full-height align-items-center">
                                <div class="col-md-6 mx-auto">
                                    <ul class="list-unstyled menu">
                                        <li @if(request()->routeIs('index')) class="active" @endif><a href="{{ route('index') }}">Home</a></li>
                                        <li @if(request()->routeIs('rooms.*')) class="active" @endif><a href="{{ route('rooms.index') }}">Kamar</a></li>
                                        @if(!auth()->check())
                                            <li @if(request()->routeIs('login.*')) class="active" @endif><a href="{{ route('login.index') }}">Login</a></li>
                                        @elseif(auth()->check() && auth()->user()->isCustomer())
                                            <li @if(request()->routeIs('customer.bookings.*')) class="active" @endif><a href="{{ route('customer.bookings.index') }}">Booking &nbsp; <i class="fa fa-calendar menu-icon"></i></a></li>
                                            <li @if(request()->routeIs('logout')) class="active" @endif><a href="{{ route('logout.store') }}">Logout &nbsp; <i class="fa fa-sign-out menu-icon"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
