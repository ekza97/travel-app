<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.meta')
    <title>{{ Helper::appTitle(config('app.name', 'TravelUmroh')) }}</title>
    @include('layouts.partials.style')
    @stack('scriptcss')
</head>
