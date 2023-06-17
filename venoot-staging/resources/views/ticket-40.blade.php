
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
        <div style="position: relative; width: 800px; height: 1222px">
            <img src="{{ url('/public/images/9D1SQmhf75PC977txzFqDqGiOM5S1M9GP8jtxK4B.png') }}" width="800"
                height="1222">

            <div style="width: 150px; height: 150px; position: absolute; top: 880px; left: 100px">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate($uuid)) !!} " width="150" alt="CodigoQR" border="0" style="width: 100%; max-width: 150px; height: auto; margin: 5px;" title="CodigoQR">
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