<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Venoot</title>

        <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
        <link rel=stylesheet href=https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css>
        <link href=participant/css/app.css rel=preload as=style>
        <link href=participant/css/chunk-vendors.css rel=preload as=style>
        <link href=participant/js/app.js rel=preload as=script>
        <link href=participant/js/chunk-vendors.js rel=preload as=script>
        <link href=participant/css/chunk-vendors.css rel=stylesheet>
        <link href=participant/css/app.css rel=stylesheet>

    </head>
    <body>
        <script>
            const BASE_URL = '{{ URL::to('/') }}'
        </script>
        <div id="app"></div>
    </body>
    <script src="participant/js/chunk-vendors.js"></script>
    <script src="participant/js/app.js"></script>
</html>
