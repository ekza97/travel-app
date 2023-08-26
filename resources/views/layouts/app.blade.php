@include('layouts.partials.header')

<body>
    <div id="app">
        @include('layouts.partials.sidebar')
        <div id="main" class='layout-navbar'>
            @include('layouts.partials.navbar')
            <div id="main-content" style="margin-top:-15px;">

                @yield('content')

                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('layouts.partials.script')
    @stack('scriptjs')
</body>

</html>
