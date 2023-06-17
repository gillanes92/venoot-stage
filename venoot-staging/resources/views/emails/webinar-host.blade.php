<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Webinar Venoot">
    <meta name="author" content="Venoot">
    <meta name="generator" content="AcelleSystemLayouts">
    <title>Webinar Venoot</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" ;
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <div builder-element="BlockElement" builder-container="" builder-wrapper="" class="container"
        style="padding-right: 0px; padding-left: 0px;">


        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Webinar Venoot">
        <meta name="author" content="Venoot">
        <meta name="generator" content="AcelleSystemLayouts">
        <title>Webinar Venoot</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" ;
            integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">


        <div builder-element="BlockElement" builder-container="" builder-wrapper="" class="container"
            style="text-align: center;">
            <div builder-element="BlockElement" builder-draggable="" class="py-3 px-3"
                style="background-color: rgb(241, 39, 34);">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td class="pl-2">

                            </td>
                            <td class="text-right pr-2">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @if ($event->banner)
            <img id="yVtCa" builder-element="" builder-draggable="" class="image-after-change"
                src="{{ Storage::url($event->banner) }}" ; style="width: 100%;">
            @endif

            <div builder-element="BlockElement" builder-draggable="" class="py-3 px-3">
                <h1 builder-element="" builder-inline-edit="" class="display-4 text-center" id="mce_17"
                    style="position: relative;" spellcheck="false">{{ $event->name }}</h1>


            </div><img id="8e2wU" builder-element="" builder-draggable="" class="image-after-change"
                src="{{ Storage::url($event->logo_event) }}"
                style="width: 400px; margin: auto;">
            <div builder-element="BlockElement" builder-draggable="" class="px-3 my-3" style="">
                <p builder-element="" builder-inline-edit="" class="" id="mce_5" style="position: relative;"
                    spellcheck="false">Su webinar ha sido creado con exito.</p>
            </div>
            <div builder-element="BlockElement" builder-draggable="" class="py-3 px-3 row" style="">
                <div class="col-md-6">

                    <h3 builder-element="" builder-inline-edit="" class="mt-2 mb-2"
                        style="text-align:center; border: solid 2px #ccc; padding:5px; width: 100%; float:right;">{{
                    $event->start_date }}</h3>

                </div>
                <div class="col-md-6">

                    <h3 builder-element="" builder-inline-edit="" class="mt-2 mb-2"
                        style="text-align:center; border: solid 2px #ccc; padding:5px; width: 100%; float:left;">{{
                    $event->start_time }}</h3>

                </div>
            </div>
            <div builder-element="BlockElement" builder-draggable="" class="px-3 my-3" style="">
                <div class="text-center">
                    Para ingresar como host utilize este link:<br>
                    https://venoot-stage.work/venoot-webinar?token={{ $event->host_webinar_link }}&mode=host&eventID={{ $event->id }}<br>
                    No comparta este enlace con otras personas!<br><br>

                    Para que sus presentadores puedan acceder utilize el enlace:<br>
                    https://venoot-stage.work/venoot-webinar?token={{ $event->presenter_webinar_link }}&mode=presenter&eventID={{ $event->id }}<br>
                </div>
            </div>



            <div builder-element="BlockElement" builder-draggable="" class="px-3 my-3" style="">
                <hr>
            </div>
            <div builder-element="BlockElement" builder-draggable="" class="px-3 my-3" style="">

            </div>
            <footer builder-element="" builder-draggable="" class="py-3 px-3 text-muted pt-5">
                <div class="">
                    <div class="row center">
                        <div builder-element="" builder-inline-edit="" class="col-md-12" id="mce_20" spellcheck="false">
                            <p class="mb-0" style="text-align: center;"><br><span style="font-size: 12px;">Este email
                                    fue enviado a nombre de <a href="mailto:{{ $event->email }}">{{ $event->email }}</a>.
                                </span><br><span style="font-size: 12px;">Revisa nuestras <a
                                        href="https://venoot.com/politicas-privacidad/">políticas de
                                        privacidad</a>.</span> | <span style="font-size: 12px;">Revisa nuestras <a
                                        href="https://venoot.com/condiciones-del-servicio/">condiciones del
                                        servicio</a>.</span><br>
                            </p>
                        </div>
                        <div class="col-md-4">

                        </div>
                    </div>
                </div>
            </footer>
        </div>


    </div>
</body>

</html>
