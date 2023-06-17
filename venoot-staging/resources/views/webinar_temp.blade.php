<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Venoot - Webinar</title>

    <script type="module" crossorigin src="vonage/assets/app.js"></script>
    <link href=vonage/assets/app.css rel=stylesheet>

</head>

<body>
    <h1>Webinar - Pagina de Prueba</h1>
    <br>
    <h3>Webinar</h3>
    @if ($participation)
        <p>Nombre: {{ $event->name }}</p>
        <p>ID: {{ $event->id }}</p>
    @else
        <p>No Encontado</p>
    @endif

    <h3>Participante</h3>
    @if ($participation)
        <p>Nombre: {{ $participant->data['name'] }} {{ $participant->data['lastname'] }}</p>
        <p>Email: {{ $participant->data['email'] }}</p>
        <p>VENOOT ID: {{ $participation->uuid }}</p>
        <p>ZOHO ID: {{ $participation->zoho_id }}</p>
    @else
        <p>No Encontado</p>
    @endif

</body>

</html>
