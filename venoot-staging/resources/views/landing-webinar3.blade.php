<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/webinar3/assets/css/main.css" />
    <noscript>
        <link rel="stylesheet" href="/webinar3/assets/css/noscript.css" />
    </noscript>

    @if ($event->estimate->ticket_sale)
        <link href=/shopping-cart/css/app.css rel=preload as=style>
        <link href=/shopping-cart/js/app.js rel=preload as=script>
        <link href=/shopping-cart/css/app.css rel="stylesheet" />
    @endif

    @if ($event->banner)
        <style>
            #header {
                background-image: url('{{ Storage::url($event->banner) }}') !important;
                background-size: cover;
            }
        </style>
    @endif

    <style>
        .#header input[type="submit"]:active {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        .#header input[type="submit"]:hover {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        .#header input[type="submit"]:focus {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        .#header input[type="submit"] {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        #header a {
            color: {{ $event->secondary }};
        }

        .contact-wrap {
            background: {{ $event->terciary }};
        }
    </style>
</head>

<body class="is-preload">

    <!-- Header -->
    <header id="header">
        <div class="content">
            <h1><a href="#">{{ $event->name }}</a></h1>
            <p>Desde las {{ $event->start_time->format('H:i') }} hrs. / {{ $event->start_date->format('d') }} de
                {{ $event->start_date->locale('es')->monthName }}</p>
            {{-- SHOPPING CART --}}
            @if ($event->estimate->ticket_sale)
                <div id="venoot-shopping-cart" data-id={{ $event->id }} data-env={{ $env }}></div>
            @endif

            @if ($event->estimate->confirmation_form)
                <form id="confirmation-form" method="post" action="/api/participants/confirmFromForm">
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    @foreach ($event->profile->database->fields as $field)
                        @if ($field['in_form'])
                            <label style="margin: 1.5em 0 0 0;">{{ $field['name'] }}</label>

                            @if ($field['type'] == 'string')
                                <input type="text" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="form-control" {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] == 'email')
                                <input type="email" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="form-control email" data-parsley-type="email"
                                    {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] == 'number')
                                <input type="number" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="form-control" {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] == 'boolean')
                                <input type="checkbox" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="form-control" {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] == 'date')
                                <input type="date" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="form-control" {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] == 'datetime')
                                <input type="datetime-local" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="form-contro" {{ $field['required'] ? 'required' : '' }}>
                            @elseif ($field['type'] == 'image')
                                <input type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="filepond-image" }}>
                            @elseif ($field['type'] == 'pdf')
                                <input type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    class="filepond-pdf" }}>
                            @elseif ($field['type'] == 'choice')
                                <select id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control"
                                    {{ $field['required'] ? 'required' : '' }}>
                                    @foreach ($field['choices'] as $choice)
                                        <option>{{ $choice }}</option>
                                    @endforeach
                                </select>
                            @else
                            @endif
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-primary">
                        Registrate
                    </button>
                </form>
                <div id="confirmation-message" style="display:none; color: {{ $event->secondary }}; font-size: 18px; max-width:600px">
                    <p>
                        Hemos enviado un correo electrónico con la confirmación. Si no lo
                        recibe dentro de los siguientes
                        minutos por favor revisar su carpeta de SPAM.
                    </p>

                    <p>
                        Le recomendamos agendar su webinar presionando <a id="confirmation-link" target="_blank"
                            href={{ $link->google() }} style="color: {{ $event->primary }}">aquí</a>.
                    </p>
                </div>
            @endif
        </div>
        <div class="image phone">
            <div class="inner"><img src="/webinar3/images/ejecutiva.png" alt="" /></div>
        </div>
    </header>

    <!-- Footer -->
    <footer id="footer">
        <ul class="icons">
            <li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
            <li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
            <li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
        </ul>
        <p class="copyright">&copy; Derechos Reservados <a href="https://venoot.com">Venoot.com</a></p>
    </footer>

    <!-- Scripts -->
    @if ($event->estimate->ticket_sale)
        <script src=/shopping-cart/js/app.js></script>
    @endif

    <!-- Scripts -->
    <script src="/webinar3/assets/js/jquery.min.js"></script>
    <script src="/webinar3/assets/js/jquery.scrolly.min.js"></script>
    <script src="/webinar3/assets/js/browser.min.js"></script>
    <script src="/webinar3/assets/js/breakpoints.min.js"></script>
    <script src="/webinar3/assets/js/util.js"></script>
    <script src="/webinar3/assets/js/main.js"></script>

	<!-- Load libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js">
    </script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"
        integrity="sha256-pEdn/pJ2tyT37axbEIPkyUUfuG1yXR0+YV+h+jphem4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/i18n/es.js"
        integrity="sha256-dcJkxln8t7jxoFFA4jP3/ru8rFOlKpt478JM/wsMsgU=" crossorigin="anonymous"></script>

    <script>
        $(function() {
            //FilePond
            FilePond.registerPlugin(FilePondPluginFileValidateType)
            const imageElements = document.querySelectorAll("input.filepond-image")
            imageElements.forEach((imageElement) => {
                const pond = FilePond.create(imageElement, {
                    acceptedFileTypes: ['image/*'],
                });
                pond.setOptions({
                    server: {
                        process: {
                            url: `${window.location.origin}/api/uploadFormImage`,
                        },
                        load: './public/image/',
                    }
                });
            })

            const pdfElements = document.querySelectorAll("input.filepond-pdf")
            pdfElements.forEach((pdfElement) => {
                const pond = FilePond.create(pdfElement, {
                    acceptedFileTypes: ['application/pdf']
                });
                pond.setOptions({
                    server: {
                        process: {
                            url: `${window.location.origin}/api/uploadFormImage`,
                        },
                        load: './public/image/',
                    }
                });
            })

            // when the form is submitted
            $('#confirmation-form').on('submit', function(e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = "/api/participants/confirmFromForm";

                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function(data) {

                            // If we have messageAlert
                            if (data.type === 'success') {
                                // inject the alert to .messages div in our form
                                $('#confirmation-form').hide();
                                $('#confirmation-link').attr("href", $('#confirmation-link').attr('href').replace('%21%21replace%21%21', data.uuid));
                                $('#confirmation-message').show();
                            }
                        }
                    });
                    return false;
                }
            })
        })
    </script>

</body>

</html>
