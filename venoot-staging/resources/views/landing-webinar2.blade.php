<!doctype html>
<html lang="en">

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/webinar2/css/style.css">

    @if ($event->banner)
        <style>
            body {
                background-image: url('{{ Storage::url($event->banner) }}') !important;
            }
        </style>
    @endif

    <style>
        .btn.btn-primary {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        .btn.btn-primary:hover {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        .btn.btn-primary:focus {
            background-color: {{ $event->primary }} !important;
            border-color: {{ $event->primary }} !important;
        }

        .bg-primary {
            background-color: {{ $event->primary }} !important;
        }

        .info-wrap h3 {
            color: {{ $event->secondary }};
        }

        .contact-wrap {
            background: {{ $event->terciary }};
        }
    </style>
</head>

<body
    style="background-image: url('/webinar2/images/bg02.jpg'); background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover;">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="wrapper">
                        <div class="row no-gutters">
                            <div class="col-md-7 d-flex align-items-stretch">
                                <div class="contact-wrap w-100 p-md-5 p-4">
                                    <div>
                                        {{-- SHOPPING CART --}}
                                        @if ($event->estimate->ticket_sale)
                                            <div id="venoot-shopping-cart" data-id={{ $event->id }}
                                                data-env={{ $env }}></div>
                                        @endif

                                        @if ($event->estimate->confirmation_form)
                                            <form id="confirmation-form" method="post" action="/api/participants/confirmFromForm">
                                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                <h3 class="mb-4">Ingresa tus datos</h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="name"
                                                                id="name" placeholder="Nombre">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="lastname" class="form-control" name="lastname"
                                                                id="lastname" placeholder="Lastname">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="email" class="form-control" name="email"
                                                                id="email" placeholder="Correo Electronico">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="email" class="form-control"
                                                                name="email_confirmation" id="email"
                                                                placeholder="Confirmacion Correo Electronico">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">
                                                                @if ($event->id != 107)
                                                                    Confirmar
                                                                @else
                                                                    Confirmar Envío
                                                                @endif
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <div id="confirmation-message" style="display: none; color: {{ $event->secondary }};">
                                                <p>
                                                    Hemos enviado un correo electrónico con la confirmación. Si no lo
                                                    recibe dentro de los siguientes
                                                    minutos por favor revisar su carpeta de SPAM.
                                                </p>

                                                <p>
                                                    Le recomendamos agendar su webinar presionando <a
                                                        id="confirmation-link" target="_blank"
                                                        href={{ $link->google() }}
                                                        style="color: {{ $event->primary }}">aquí</a>.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 d-flex align-items-stretch">
                                <div class="info-wrap bg-primary w-100 p-lg-5 p-4">
                                    <img src="{{ Storage::url($event->logo_event) }}" class="w-100"
                                        style="object-fit: contain;" />
                                    <h3 class="mb-4 mt-md-4">{{ $event->name }}</h3>
                                    <div class="dbox w-100 d-flex align-items-start">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p>Desde las {{ $event->start_time->format('H:i') }} hrs</p>
                                            <p>{{ $event->start_date->format('d') }} de
                                                {{ $event->start_date->locale('es')->monthName }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    @if ($event->estimate->ticket_sale)
        <script src=/shopping-cart/js/app.js></script>
    @endif

    <script src="/webinar2/js/jquery.min.js"></script>
    <script src="/webinar2/js/popper.js"></script>
    <script src="/webinar2/js/bootstrap.min.js"></script>
    <script src="/webinar2/js/jquery.validate.min.js"></script>
    <script src="/webinar2/js/main.js"></script>

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
                                $('#confirmation-link').attr("href", $('#confirmation-link')
                                    .attr('href').replace('%21%21replace%21%21', data.uuid));
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
