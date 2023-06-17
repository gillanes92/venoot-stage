<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Venoot</title>

        <!-- Fonts -->
        <link href='https://fonts.googleapis.com/css?family=Barlow+Condensed:100,300,400,500,700,900|Material+Icons' rel="stylesheet">

        <!-- Icons -->
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="{{mix('css/app.css')}}">
        <link rel="stylesheet" type="text/css" href="{{mix('css/main.css')}}">
    </head>
    <body>
        <script>
            const BASE_URL = '{{ URL::to('/') }}'
        </script>
        <div id="app"></div>
        {{-- @if(config('app.env') == 'local')
            <script src="http://localhost:35729/livereload.js"></script>
        @endif --}}
    </body>
    <script type="text/javascript" src="{{mix('js/app.js')}}"></script>
</html>
