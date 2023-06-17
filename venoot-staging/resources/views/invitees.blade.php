<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="keywords" />
        <meta content="" name="description" />
        <title>{{ $event->name }}</title>

        <!-- Favicons -->
        <link href="{{ url('/images/favicon.png') }}" rel="icon" />
        <link href="{{ url('/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800"
            rel="stylesheet"
        />

        <!-- Font Awesome -->
        <link
            href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
            rel="stylesheet"
        />

        <!-- Main Stylesheet File -->
        <link href="{{ mix('css/landing.css') }}" rel="stylesheet" />
    </head>

    <body>
        <!--==========================
        Header
        ============================-->
        <header id="header">
            <div class="container">
                <div id="logo" class="pull-left">
                    <!-- Uncomment below if you prefer to use a text logo -->
                    <!-- <h1><a href="#main">C<span>o</span>nf</a></h1>-->
                    <a href="#intro" class="scrollto"
                        ><img src="{{ url('/storage/'.$event->logo_event ) }}" alt="" title=""
                    /></a>
                </div>
            </div>
        </header>
        <!-- #header -->

        <!--==========================
    Intro Section
  ============================-->
        <section id="intro">
            <div class="intro-container wow fadeIn">
                <h1 class="mb-4 pb-0">
                    Confirmación de Invitados
                </h1>
            </div>
            <div>
            </div>
        </section>

        <section>
            <div class="container mt-4">
                <form id="invitees-form" method="post">
                    <input type="hidden" name="uuid" value="{{ $participation->uuid }}">
                    @for($i = 0; $i < $event->invitees; ++$i)
                        <div class="form-group">
                            <label class="text-secondary">Correo Invitado #{{ $i + 1 }}</label>
                            <input type="text" name="emails[]" class="form-control">
                        </div>
                    @endfor
                    <div class="modal-footer">
                        <div id="form-message">
                        </div>
                        <button type="submit" class="btn btn-primary">Confirmar invitados</button>
                    </div>
                </form>
            </div>
        </section>


        <footer id="footer">
            <div class="container">
                <div class="copyright">
                    &copy; Copyright <strong>{{ $event->company->social_reason }}</strong>. Todos
                    los derechos reservados.
                </div>
            </div>
        </footer>

        <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

        <!-- Template Main Javascript File -->
        <script type="text/javascript" src="{{ mix('js/landing.js') }}"></script>

        <!-- Form processing Javascript -->
        <script>
            $(function () {

                // init the validator
                // validator files are included in the download package
                // otherwise download from http://1000hz.github.io/bootstrap-validator

                // $('#contact-form').validator();


                // when the form is submitted
                $('#invitees-form').on('submit', function (e) {

                    // if the validator does not prevent form submit
                    if (!e.isDefaultPrevented()) {
                        var url = "/api/participants/invitees/"

                        // POST values in the background the the script URL
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: $(this).serialize(),
                            success: function (data)
                            {
                                // data = JSON object that contact.php returns

                                // we recieve the type of the message: success x danger and apply it to the
                                var messageAlert = 'alert-' + data.type
                                var messageText = data.message

                                // let's compose Bootstrap alert box HTML
                                var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable">' + messageText + '</div>'

                                // If we have messageAlert and messageText
                                if (messageAlert && messageText) {
                                    switch (messageAlert) {
                                        case 'success':
                                            // inject the alert to .messages div in our form
                                            $('#confirmation-space').html(alertBox)
                                            break

                                        case 'danger':
                                            $('#form-message').html(alertBox)
                                            break
                                    }
                                }
                            },
                            error: function (error) {
                                const errors = error.responseJSON.errors
                                const messageAlert = 'danger'
                                var messageText = errors[Object.keys(errors)[0]]

                                // let's compose Bootstrap alert box HTML
                                var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable">' + messageText + '</div>'

                                $('#form-message').html(alertBox)
                            }
                        });
                        return false;
                    }
                })
            });
        </script>
    </body>
</html>
