<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Helper::appTitle(config('app.name', 'TravelUmroh')) }}</title>
    <link rel="stylesheet" href="{{ asset('') }}assets/css/main/app.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/css/pages/auth.css">
    {{-- <link rel="shortcut icon" href="{{ asset('') }}assets/images/logo/favicon.svg" type="image/x-icon"> --}}
    <link rel="shortcut icon" href="{{ asset('') }}assets/images/logo/favicon.png" type="image/png">
</head>

<body>
    <div id="auth">

        @yield('content')

    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
            x.focus();
        }

        function toggleConfirmPassword() {
            var x = document.getElementById("password-confirm");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
            x.focus();
        }
    </script>
</body>

</html>
