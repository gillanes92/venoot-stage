<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">

    <link rel=icon href=/favicon.ico>
    <title>
        {{ $poll->name }}
    </title>

    <link href=/poll/css/app.css rel=preload as=style>
    <link href=/poll/js/app.js rel=preload as=script>

    <link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
    <link rel=stylesheet href=https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css>
    <link href=/poll/css/app.css rel=stylesheet>
</head>

<body>
    <noscript>
        <strong>We're sorry but carro-de-compra doesn't work properly without JavaScript enabled. Please enable it to
            continue.</strong>
    </noscript>

    <div id="venoot-poll" data-eventid={{ $event->id }} data-pollid={{ $poll->id }}  data-env={{ env('APP_ENV', 'local') }}></div>
    <script src=/poll/js/app.js> </script>
</body>
</html>
