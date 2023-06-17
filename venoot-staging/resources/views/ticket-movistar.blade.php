<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <!-- HTML Meta Tags -->
    <title>{{ $event->name }}</title>
    <meta name="description" content="{{ $event->description }}">

    <!-- Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $event->name }}">
    <meta property="og:description" content="{{ $event->description }}">
    <meta property="og:image" content="{{ Storage::url($event->banner) }}">
    <meta property="og:url" content="{{ Request::url() }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="">
    <meta property="twitter:url" content="{{ Request::url() }}">
    <meta name="twitter:title" content="{{ $event->name }}">
    <meta name="twitter:description" content="{{ $event->description }}">
    <meta name="twitter:image" content="{{ Storage::url($event->banner) }}">
</head>

<body class="is-preload">
    <center>
        <div style="position: relative; width: 800px; height: 1000px">
            <img src="/public/images/uZu4GGE4hGwsOLxVbfhotkj4dhsRoIPyG56J5w7l.png" width="800"
                height="1000">

            <div
                style="color: #EE8E1A; position: absolute; top: 540px; width: 100%; font-size: 28px; font-weight: bold; text-transform: uppercase;">
                {{ $participant->data['name'] }}
            </div>

            <div style="width: 195px; height: 195px; position: absolute; top: 170px; left: 487px">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(195)->generate($uuid)) !!} " width="195" alt="CodigoQR" border="0" style="width: 100%; max-width: 195px; height: auto; margin: 5px;" title="CodigoQR">
            </div>
        </div>
        <footer>
            <div class="">
                <div class="row">
                    <div class="col-md-1" style="color: rgb(146, 146, 146); font-size:11px; font-family: arial;">
                        <p class="mb-1">Copyright © Todos los derechos reservados.</p>
                        <p class="mb-0">
                            Nuestro email de contacto es <a href="mailto:{{ $event->email }}">{{ $event->email }}</a>.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </center>
</body>
