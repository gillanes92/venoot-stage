<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="{{ url('webinar/css/main.css') }}" />

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

    @if ($event->estimate->ticket_sale)
        <link href=/shopping-cart/css/app.css rel=preload as=style>
        <link href=/shopping-cart/js/app.js rel=preload as=script>
        <link href=/shopping-cart/css/app.css rel="stylesheet" />
    @endif

    @if ($event->banner)
        <style>
            body {
                background-image: url('{{ Storage::url($event->banner) }}');
                background-size: cover;
            }

            label {
                color: #2B2B2B;
                display: unset;
            }
        </style>
    @endif

    <style>
        #confirmation-message p {
            line-height: 1.5;
            font-size: 20px;
            text-align: justify;
            padding: 20px;
            color: {{ $event->secondary }};
        }

        .btn-primary {
            background-color: {{ $event->primary }}
        }

        .secondary {
            color: {{ $event->secondary }}
        }

        .alineacion {
            background-color: {{ $event->terciary }}
        }
    </style>
</head>

<body class="is-preload">
    <section>
        <!-- LOGOTIPO -->
        <div class="logotipo">
            <img src="{{ Storage::url($event->logo_event) }}">
        </div>
        <div class="alineacion">
            <!-- Header -->
            <header id="header">
                <h1 class="secondary">{{ $event->name }}</h1>
                <p>
                    <a href="#" class="fas fa-clock"></a> Desde las {{ $event->start_time->format('H:i') }}
                    hrs<br>
                    <a href="#" class="fas fa-calendar-alt"></a> {{ $event->start_date->format('d') }} de
                    {{ $event->start_date->locale('es')->monthName }}
                    {{-- <p>Bajada de datos lugar horario etc...<br />
                    y acceso al landing del evento? <a href="https://venoot.com">Click Acá</a>.</p> --}}
            </header>

            <!-- Formulario -->
            @if ($event->estimate->confirmation_form)
                <div id="confirmation-space">
                    <form id="confirmation-form" method="post" action="/api/participants/confirmFromForm"
                        class="formulario">
                        <h5>
                            Formulario de Confirmación
                        </h5>
                        <ul class="modal-body">
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            @foreach ($event->profile->database->fields as $field)
                                @if ($field['in_form'])
                                    <li class="form-group">
                                        <label>{{ $field['name'] }}</label>

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
                                            <input type="datetime-local" id="{{ $field['key'] }}"
                                                name="{{ $field['key'] }}" class="form-contro"
                                                {{ $field['required'] ? 'required' : '' }}>
                                        @elseif ($field['type'] == 'image')
                                            <input type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="filepond-image" }}>
                                        @elseif ($field['type'] == 'pdf')
                                            <input type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="filepond-pdf" }}>
                                        @elseif ($field['type'] == 'choice')
                                            <select id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="form-control" {{ $field['required'] ? 'required' : '' }}>
                                                @foreach ($field['choices'] as $choice)
                                                    <option>{{ $choice }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                @if ($event->id != 107)
                                    Confirmar
                                @else
                                    Confirmar Envío
                                @endif
                            </button>
                        </div>
                    </form>
                    
                    <div id="confirmation-message" style="display: none;">
                        <p>
                        Hemos enviado un correo electrónico con la confirmación. Si no lo recibe dentro de los siguientes
                        minutos por favor revisar su carpeta de SPAM.
                        </p>

                        <p>
                        Le recomendamos agendar su webinar presionando <a id="confirmation-link" target="_blank" href={{ $link->google() }} style="color: {{ $event->primary }}">aquí</a>.
                        </p>
                    </div>
            @endif

            {{-- SHOPPING CART --}}
            @if ($event->estimate->ticket_sale)
                <div id="venoot-shopping-cart" data-id={{ $event->id }} data-env={{ $env }}></div>
            @endif

        </div>
    </section>
    <!-- Footer -->
    <footer id="footer">
        <ul class="icons">
            <li><a href="#" class="fas fa-map-marker-alt"></a> Online</li>
            <li><a href="#" class="fas fa-clock"></a> Desde las {{ $event->start_time->format('H:i') }} hrs</li>
            <li><a href="#" class="fas fa-calendar-alt"></a> {{ $event->start_date->format('d') }} de
                {{ $event->start_date->locale('es')->monthName }}</li>
            {{-- <li><a href="#" class="icon fa-envelope"><span class="label">{{ $event->email }}</span></a></li> --}}
        </ul>
        <ul class="copyright">
            <li>&copy; Venoot.</li>
            <li>Créditos: <a href="https://venoot.com">Venoot.com</a></li>
        </ul>
    </footer>

    <!-- Scripts -->
    @if ($event->estimate->ticket_sale)
        <script src=/shopping-cart/js/app.js></script>
    @endif

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
