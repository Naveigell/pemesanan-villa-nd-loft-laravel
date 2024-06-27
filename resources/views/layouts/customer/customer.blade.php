<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('page-title', 'Sistem Informasi')</title>

    @include('layouts.customer.style')
    @stack('stack-style')
</head>
<body>

@include('layouts.customer.header')
<!-- END head -->

@yield('content-body')

@include('layouts.customer.footer')

@include('layouts.customer.script')
@stack('stack-script')

</body>
</html>
