<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" name="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

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

    <!-- Favicons -->
    <link href="{{ url('/images/favicon.png') }}" rel="icon" />
    <link href="{{ url('/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Filepond stylesheet -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="{{ mix('css/landing.css') }}" rel="stylesheet" />

    @if ($event->estimate->ticket_sale)
        <link href=/shopping-cart/css/app.css rel=preload as=style>
        <link href=/shopping-cart/js/app.js rel=preload as=script>
        <link href=/shopping-cart/css/app.css rel="stylesheet" />
    @endif

    <style>
        #subscribe {
            padding-bottom: 60px;
            padding-top: 60px;
            padding-right: 0px;
            padding-left: 0px;
        }

        .venoot-shopping-cart {
            justify-content: center;
            background-color: white;
            position: relative;
        }

        .venoot-shopping-cart div {
            width: 100%;
        }

        .venoot-pill-button {
            width: 32px;
        }

        #venue .venue-info {
            background-image: url({{ Storage::url($event->landing->location_photo) }});
        }

        #intro {
            background-image: url({{ Storage::url($event->banner) }});
        }

        .venoot-img-fluid.img-fluid {
            min-width: 100%;
        }

        .section-header.subheader {
            padding-bottom: 5px;
            margin-bottom: 30px;
        }

        .subheader h2 {
            font-size: 28px;
        }

        .activity-secundario {
            background-color: #DBE5F1;
        }

        .activity-acentuado {
            background-color: #F9EBEA;
        }

        .activity-desacentuado {
            background-color: #D9D9D9;
        }
    </style>

    @if ($event->id == 147)
        <style>
            #intro::before {
                background: rgba(6, 6, 6, .1);
            }

            .nav-menu>li>a::before {
                background-color: #B5BF02;
            }

            #header.header-scrolled {
                background-color: #18848E !important;
            }

            #logo {
                margin-top: -20px;
            }

            #header #logo img {
                max-height: 100px;
            }

            .nav-menu a {
                color: #999;
            }


            .nav-menu li.buy-tickets a {
                background: #B5BF02 !important;
                border: 2px solid #B5BF02;
                color: white !important;
            }

            #intro a {
                color: #3E3D40 !important;
            }

            #intro a.about-btn {
                color: #FFF !important;
                border: 2px solid #B5BF02 !important;
                background-color: #B5BF02 !important;
            }

            #intro a.about-btn:hover {
                background-color: #14818D;
            }

            #intro p {
                color: #3E3D40;
            }

            #intro h1 {
                color: #3E3D40;
            }

            #intro h1 span {
                color: #3E3D40;
            }

            .section-header::before {
                background-color: #B5BF02;
            }

            #about {
                background-image: none;
            }

            #about::before {
                background: #18848E;
            }

            #subscribe::before {
                background: #14818D;
            }

            #subscribe button {
                background-color: #B5BF02 !important;
            }

            h2 {
                color: #000;
            }

            .rounded-pill {
                background-color: #175590 !important;
            }

            #contact h2 {
                color: #000 !important;
            }

            #contact .contact-info h3 {
                color: #000;
            }

            #contact .form button[type="submit"] {
                background-color: #B5BF02;
                border: 2px solid #B5BF02 !important;
            }

            #contact .form button[type="submit"]:hover {
                background: 0 0;
                color: #B5BF02;
            }

            .back-to-top {
                background-color: #175590 !important;
            }

            .nav-menu li.buy-tickets a:hover {
                color: #FFF !important;
            }

            #schedule .nav-tabs a {
                background-color: #175590 !important;
            }

            #schedule .nav-tabs a.active {
                background-color: #468296 !important;
            }

            #schedule .schedule-item h4 {
                color: #175590 !important;
            }

            #footer .footer-top {
                background: #14818D;
            }
        </style>
    @endif

    @if ($event->id == 152)
        <style>
            #intro::before {
                background: rgba(6, 6, 6, .1);
            }

            .nav-menu>li>a::before {
                background-color: #844A97;
            }

            #header.header-scrolled {
                background-color: #26647B !important;
            }

            #logo {
                margin-top: -5px;
            }

            #header #logo img {
                max-height: 50px;
            }

            .nav-menu a {
                color: #fff;
            }

            .nav-menu li.buy-tickets a {
                background: #844A97 !important;
                border: 2px solid #844A97;
                color: white !important;
            }

            #intro a {
                color: #FFF !important;
                text-decoration: underline;
                text-decoration-thickness: 1px;
            }

            #intro a.about-btn {
                color: #FFF !important;
                border: 2px solid #844A97 !important;
                background-color: #844A97 !important;
                text-decoration: none;
            }

            #intro a.about-btn:hover {
                background-color: #14818D;
            }

            #intro p {
                color: #FFF;
            }

            #intro h1 {
                color: #FFF;
            }

            #intro h1 span {
                color: #FFF;
            }

            .section-header::before {
                background-color: #844A97;
            }

            #about {
                background-image: none;
            }

            #about::before {
                background: #26647B;
            }

            #subscribe::before {
                background: #14818D;
            }

            #subscribe button {
                background-color: #844A97 !important;
            }

            h2 {
                color: #000;
            }

            .rounded-pill {
                background-color: #14818D !important;
            }

            #contact h2 {
                color: #000 !important;
            }

            #contact .contact-info h3 {
                color: #000;
            }

            #contact .form button[type="submit"] {
                background-color: #844A97;
                border: 2px solid #844A97 !important;
            }

            #contact .form button[type="submit"]:hover {
                background: 0 0;
                color: #844A97;
            }

            .back-to-top {
                background-color: #844A97 !important;
            }

            .nav-menu li.buy-tickets a:hover {
                color: #FFF !important;
            }

            .section-header h2 {
                color: #17041d;
            }

            #schedule .nav-tabs a {
                background-color: #14818D !important;
            }

            #schedule .nav-tabs a.active {
                background-color: #844A97 !important;
            }

            #schedule .schedule-item h4 {
                color: #4F3069 !important;
            }

            #footer .footer-top {
                background: #14818D;
            }
        </style>
    @endif

    @if ($event->id == 158)
        <style>
            #intro::before {
                background: rgba(6, 6, 6, .1);
            }

            .nav-menu>li>a::before {
                background-color: #C90F34;
            }

            #header.header-scrolled {
                background-color: #f77847 !important;
            }

            #logo {
                margin-top: -5px;
            }

            #header #logo img {
                max-height: 50px;
            }

            .nav-menu a {
                color: #fff;
            }

            .nav-menu li.buy-tickets a {
                background: #C90F34 !important;
                border: 2px solid #C90F34;
                color: white !important;
            }

            #intro a {
                color: #FFF !important;
                text-decoration: underline;
                text-decoration-thickness: 1px;
            }

            #intro a.about-btn {
                color: #FFF !important;
                border: 2px solid #C90F34 !important;
                background-color: #C90F34 !important;
                text-decoration: none;
            }

            #intro a.about-btn:hover {
                background-color: #FDD9CB;
            }

            #intro p {
                color: #FFF;
            }

            #intro h1 {
                color: #FFF;
            }

            #intro h1 span {
                color: #FFF;
            }

            .section-header::before {
                background-color: #C90F34;
            }

            #about {
                background-image: none;
            }

            #about::before {
                background: #f77847;
            }

            #subscribe::before {
                background: #FDD9CB;
            }

            #subscribe button {
                background-color: #C90F34 !important;
            }

            h2 {
                color: #000;
            }

            .rounded-pill {
                background-color: #FDD9CB !important;
            }

            #contact h2 {
                color: #000 !important;
            }

            #contact .contact-info h3 {
                color: #000;
            }

            #contact .form button[type="submit"] {
                background-color: #C90F34;
                border: 2px solid #C90F34 !important;
            }

            #contact .form button[type="submit"]:hover {
                background: 0 0;
                color: #C90F34;
            }

            .back-to-top {
                background-color: #C90F34 !important;
            }

            .nav-menu li.buy-tickets a:hover {
                color: #FFF !important;
            }

            .section-header h2 {
                color: #17041d;
            }

            #schedule .nav-tabs a {
                background-color: #FDD9CB !important;
            }

            #schedule .nav-tabs a.active {
                background-color: #C90F34 !important;
            }

            #schedule .schedule-item h4 {
                color: #8c0a24 !important;
            }

            #footer .footer-top {
                background: #FDD9CB;
            }
        </style>
    @endif

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
                <a href="#intro" class="scrollto"><img src="{{ Storage::url($event->logo_event) }}" alt=""
                        title="" /></a>
            </div>

            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li class="menu-active"><a href="#intro">Inicio</a></li>

                    @if ($event->landing->has_description && $event->id != 152)
                        <li><a href="#about">{{ $event->landing->description_title }}</a></li>
                    @endif

                    @if ($event->landing->has_speakers && !$actors->isEmpty())
                        <li><a href="#speakers">{{ $event->landing->speakers_title }}</a></li>
                    @endif

                    @if ($event->landing->has_activities && !$event->activities->isEmpty())
                        <li><a href="#schedule">{{ ucfirst($event->landing->activities_title) }}</a></li>
                    @endif

                    @if ($event->landing->has_location)
                        <li><a href="#venue">{{ $event->landing->location_title }}</a></li>
                    @endif

                    @if (!empty($event->landing->images))
                        <li><a href="#gallery">{{ $event->landing->images_title }}</a></li>
                    @endif

                    @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
                        <li><a href="#supporters">{{ $event->landing->sponsors_title }}</a></li>
                    @endif

                    @if ($event->landing->has_contact && false)
                        <li><a href="#contact">{{ $event->landing->contact_title }}</a></li>
                    @endif

                    @if ($event->landing->has_ssnn && false)
                        <li><a href="#footer">{{ $event->landing->ssnn_title }}</a></li>
                    @endif

                    @if ($event->estimate->ticket_sale && $event->landing->show_tickets)
                        <li class="buy-tickets">
                            <a href="#subscribe">
                                Comprar
                            </a>
                        </li>
                    @endif

                    @if ($event->estimate->confirmation_form)
                        <li class="buy-tickets">
                            <a href="#subscribe">
                                @if ($event->id == 158)
                                <strong>Registro</strong>
                                @else
                                <strong>Confirmar</strong>
                                @endif
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- #nav-menu-container -->
        </div>
    </header>
    <!-- #header -->

    <!--==========================
    Intro Section
  ============================-->
    <section id="intro">
        <div class="intro-container wow fadeIn">
            <h1 class="mb-4 pb-0">
                {{ $event->name }}<br /><span>{{ $event->landing->highlight }}</span>
                {{ $event->landing->second_title }}
            </h1>
            <p class="mb-4 pb-0">
                @if ($event->landing->has_date)
                    <span class="date">
                        @if ($event->id == 152)
                            27 y 28 de octubre
                            @if ($event->landing->has_location)
                            <a href="#venue" style="margin-left: 10px;">{{ $event->location }}</a>
                            @else
                            {{ $event->location }}
                            @endif
                        @else
                            {{ $event->start_date->format('d') }}{{ $event->start_date->day != $event->end_date->day ? ' a ' . $event->end_date->format('d') : '' }}
                            {{ $event->start_date->locale('es')->monthName }}{{ $event->start_date->month != $event->end_date->month ? ' a ' . $event->end_date->locale('es')->monthName : '' }},
                    </span><span class="location">
                        @if ($event->landing->has_location)
                            <a href="#">{{ $event->location }}</a>
                            @else
                            {{ $event->location }}
                            @endif
                            ,</span><span
                        class="time"> {{ $event->start_time->format('H:i') }}</span>
                @endif
                </span>
            @else
                @if ($event->landing->has_location)
                <a href="#">{{ $event->location }}</a>
                @else
                {{ $event->location }}
                @endif
                , {{ $event->start_time->format('H:i') }}
                @endif
            </p>
            @if ($event->landing->has_add_to_calendar)
                <p>
                    <a href={{ $link->google() }}>Agregar al Calendario Google</a> | <a
                        href={{ $link->ics() }}>Agregar al Calendario Local</a>
                </p>
            @endif
            <div style="width:94px; height:94px;">
                @if ($event->landing->video_url && $event->id == 1)
                    @if ($event->secure_video)
                        @if ($event->secure_video_extras)
                            <a id="video-secure-button" type="button" class="play-btn mb-4" href="/venoot-chat"
                                target="_blank">
                            </a>
                        @else
                            <a type="button" data-toggle="modal" data-target="#video-modal" class="play-btn mb-4">
                            </a>
                            <a id="video-box-secure" class="venobox" data-vbtype="video" data-autoplay="true"></a>
                        @endif
                    @else
                        <a id="video-box" href={{ $event->landing->video_url }}&modestbranding=1&rel=0
                            class="venobox play-btn mb-4" data-vbtype="video" data-autoplay="true"></a>
                    @endif
                @endif
            </div>
            @if ($event->id == 1)
                <div id="subscribe" style="background-color: transparent !important">
                    <button type="button" data-toggle="modal" data-target="#confirmation-modal">
                        Incribirse
                    </button>
                </div>
            @endif
            @if ($event->landing->has_description)
                <a href="#about" class="about-btn scrollto">Sobre el Evento</a>
            @endif
        </div>
    </section>

    <!--==========================
            Secure Video Form
            ============================-->
    @if ($event->secure_video)
        <form id="video-form" method="post">
            <div class="modal fade" id="video-modal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ingrese su numero de participante</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input id="qr-code" type="text" name="qr_code" class="form-control" required>
                            <small id="qr-code-error" class="form-text text-danger"></small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

    <main id="main">
        <!--==========================
      About Section
    ============================-->
        @if ($event->landing->has_description)
            <section id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2>{{ $event->landing->description_title }}</h2>
                            <p>
                                {!! $event->description !!}
                            </p>
                        </div>
                        <div class="col-lg">
                            <h3>{{ $event->landing->where_title }}</h3>
                            <p>{{ $event->location }}</p>
                        </div>
                        <div class="col-lg">
                            <h3>{{ $event->landing->when_title }}</h3>
                            <p>
                                @if ($event->id == 152)
                                    27 y 28 de {{ $event->start_date->locale('es')->monthName }}
                                @elseif ($event->start_date->locale('es')->monthName != $event->end_date->locale('es')->monthName)
                                    {{ $event->start_date->day }} de {{ $event->start_date->locale('es')->monthName }}
                                    al {{ $event->end_date->day }} de {{ $event->end_date->locale('es')->monthName }}
                                @else
                                    {{ $event->start_date->day }} - {{ $event->end_date->day }} de
                                    {{ $event->start_date->locale('es')->monthName }}
                                @endif
                            </p>
                        </div>
                        @if ($event->id != 152)
                        <div class="col-lg">
                            <h3>{{ $event->landing->hour_title }}</h3>
                            <p>{{ $event->start_time->format('H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!--==========================
      Speakers Section
    ============================-->
        @if ($event->landing->has_speakers && !$actors->isEmpty())
            <section id="speakers" class="wow fadeInUp">
                <div class="container">
                    @if ($event->landing->show_speaker_title)
                        <div class="section-header">
                            <h2>{{ $event->landing->speakers_title }}</h2>
                        </div>
                    @endif

                    @foreach ($event->actors->groupBy('category') as $key => $actors)
                        <div class="section-header subheader">
                            <h2>{{ $key }}</h2>
                        </div>

                        <div class="row justify-content-md-center">
                            @foreach ($actors as $actor)
                                <div class="col-lg-4 col-md-6">
                                    <div class="speaker">
                                        <img src="{{ Storage::url($actor->photo) }}"
                                            alt="Speaker {{ $loop->index + 1 }}"
                                            class="venoot-img-fluid img-fluid" />
                                        <div class="details">
                                            <h3>
                                                {{ $actor->name }}
                                            </h3>
                                            <p>{{ $actor->job }}</p>
                                            <div class="social">
                                                @foreach ($actor->links as $link)
                                                    <a href="{{ $link['http'] }}" target="_blank">
                                                        <i class="fa fa-{{ $link['name'] }}"></i>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!--==========================
      Schedule Section
    ============================-->
        @if ($event->landing->has_activities && !$activitiesByDate->isEmpty())
            <section id="schedule" class="section-with-bg">
                <div class="container wow fadeInUp">
                    <div class="section-header">
                        <h2>{{ $event->landing->activities_title }}</h2>
                        {{-- <p>Participantes y Actividades</p> --}}
                    </div>

                    @if ($event->id != 113)
                        <ul class="nav nav-tabs" role="tablist">
                            @for ($i = 0; $i < $activitiesByDate->count(); $i++)
                                <li class="nav-item">
                                    <a class="nav-link{{ $i == 0 ? ' active' : '' }}"
                                        href="#day-{{ $i + 1 }}" role="tab"
                                        data-toggle="tab">{{ \Carbon\Carbon::parse($activitiesByDate->sortKeys()->keys()[$i])->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</a>
                                </li>
                            @endfor
                        </ul>
                    @endif

                    <div class="tab-content row justify-content-center">
                        <!-- Schdule Day -->

                        @foreach ($activitiesByDate as $key => $activities)
                            <div role="tabpanel"
                                class="col-lg-9 tab-pane fade show{{ $loop->iteration == 1 ? ' active' : '' }}"
                                id="day-{{ $loop->iteration }}">
                                @foreach ($activities->sortBy(function ($activity) {
        return $activity->start_time . '#' . (20 - $activity->order);
    }) as $activity)
                                    <div class="row schedule-item  activity-{{ $activity->style }}">
                                        <div class="col-md-2">
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    @if ($event->id != 113)
                                                        <time>{{ $activity->start_time }}</time>
                                                        -
                                                        <time>{{ $activity->end_time }}</time>
                                                    @endif
                                                </div>
                                                <div class="col-md-11 col-offset-1">
                                                    @if ($activity->video_url)
                                                        <a class="video-secure-activity" type="button"
                                                            class="play-btn"
                                                            href="/venoot-chat?activity={{ $activity->id }}"
                                                            target="_blank">
                                                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    @if ($activity->extra_link)
                                                        <a type="button" class="play-btn pl-1"
                                                            href="{{ $activity->extra_link }}" target="_blank">
                                                            <i class="fa fa-external-link" aria-hidden="true"></i>

                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <h4>{{ $activity->name }} <span> {{ $activity->location }}</span></h4>
                                            <span>
                                                {!! $activity->description !!}

                                            </span>
                                            <br />
                                            @if (!$activity->actors->isEmpty())
                                                <br />
                                            @endif
                                            @foreach ($activity->actors as $actor)
                                                <div class="speaker">
                                                    <img src="{{ Storage::url($actor->photo) }}"
                                                        alt="{{ $actor->name }}" />

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <!-- End Schdule Day -->
                    </div>
                </div>
            </section>
        @endif

        <!--==========================
      Venue Section
    ============================-->
        @if ($event->landing->has_location)
            <section id="venue" class="wow fadeInUp">
                <div class="container-fluid">
                    <div class="section-header">
                        <h2>{{ $event->landing->location_title }}</h2>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6 venue-map">
                            <iframe
                                src="https://www.google.com/maps/embed/v1/place?q={{ $event->location }}&key=AIzaSyBxSQE5934vLjUqcNlXRJipeEZUoNBgRes"
                                frameborder="0" style="border:0" allowfullscreen>
                            </iframe>
                        </div>

                        <div class="col-lg-6 venue-info">
                            <div class="row justify-content-center">
                                <div class="col-11 col-lg-8">
                                    <p>
                                        {{ $event->landing->location_description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!--==========================
            Confirmar Asistencia
            ============================-->
        @if ($event->estimate->confirmation_form)
            <section id="subscribe">
                <div class="container wow fadeInUp">
                    <div class="section-header">
                        <h2>{{ $event->landing->confirm_title }}</h2>
                        <p>
                            {{ $event->landing->confirm_subtitle }}
                        </p>
                    </div>

                    <div class="form-row justify-content-center">
                        <div id="confirmation-space" class="col-auto">
                            <button type="button" data-toggle="modal" data-target="#confirmation-modal">
                                @if ($event->id == 158)
                                Registro
                                @else
                                Confirmar
                                @endif
                            </button>
                        </div>                        
                    </div>

                    <div id="confirmed-space" class="justify-content-center" style="display: none">
                        <div class="row">
                            <div class="col-lg" style="text-align: center">
                                <p>
                                    Su confirmación ha sido recibida con éxito.<br>
                                    Un correo le sera enviado con los datos para asistir al evento.<br>
                                    Si el correo no llega en unos minutos, por favor, revise su Spam.<br>
                                    Puede presionar el siguiente boton para copiar directamente su enlace personal de
                                    asistencia.
                                </p>
                                <p>
                                    <button type="button" id="copy-link-button">
                                        Copiar Link
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif


        <!--==========================
      Sponsors Section
    ============================-->
        @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
            <section id="supporters" class="section-with-bg wow fadeInUp">
                <div class="container">
                    @if ($event->landing->show_sponsor_title)
                        <div class="section-header">
                            <h2>{{ $event->landing->sponsors_title }}</h2>
                        </div>
                    @endif

                    @foreach ($event->sponsors->groupBy('category') as $key => $sponsors)
                        <div class="section-header subheader">
                            <h2>
                                {{ $key }}
                                <br>
                            </h2>
                        </div>


                        <div class="row no-gutters supporters-wrap clearfix justify-content-md-center">
                            @foreach ($sponsors as $sponsor)
                                <div class="col-lg-3 col-md-4 col-xs-6">
                                    <div class="supporter-logo">
                                        @if ($sponsor->url)
                                            <a href="{{ $sponsor->url }}" target="_blank">
                                        @endif
                                        <img src="{{ Storage::url($sponsor->logo) }}" class="img-fluid"
                                            alt="" />
                                        @if ($sponsor->url)
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!--==========================
            Formulario
            ============================-->
        @if ($event->estimate->confirmation_form)
            <form id="confirmation-form" method="post" action="/api/participants/confirmFromForm">
                <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Formulario de Confirmación
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                @foreach ($event->profile->database->fields as $field)
                                    @if ($field['in_form'])
                                        <div class="form-group">
                                            <label>{{ $field['name'] }}</label>

                                            @if ($field['type'] == 'string')
                                                <input type="text" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="form-control"
                                                    {{ $field['required'] ? 'required' : '' }}>
                                            @elseif ($field['type'] == 'email')
                                                <input type="email" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="form-control email"
                                                    data-parsley-type="email"
                                                    {{ $field['required'] ? 'required' : '' }}>
                                            @elseif ($field['type'] == 'number')
                                                <input type="number" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="form-control"
                                                    {{ $field['required'] ? 'required' : '' }}>
                                            @elseif ($field['type'] == 'boolean')
                                                <input type="checkbox" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="form-control"
                                                    {{ $field['required'] ? 'required' : '' }}>
                                            @elseif ($field['type'] == 'date')
                                                <input type="date" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="form-control"
                                                    {{ $field['required'] ? 'required' : '' }}>
                                            @elseif ($field['type'] == 'datetime')
                                                <input type="datetime-local" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="form-contro"
                                                    {{ $field['required'] ? 'required' : '' }}>
                                            @elseif ($field['type'] == 'image')
                                                <input type="file" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="filepond-image" }}>
                                            @elseif ($field['type'] == 'pdf')
                                                <input type="file" id="{{ $field['key'] }}"
                                                    name="{{ $field['key'] }}" class="filepond-pdf" }}>
                                            @elseif ($field['type'] == 'choice')
                                                <select id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                    class="form-control" {{ $field['required'] ? 'required' : '' }}>
                                                    @foreach ($field['choices'] as $choice)
                                                        <option>{{ $choice }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($field['type'] == 'email')
                                        <label>Confirmación {{ $field['name'] }}</label>
                                        <div class="form-group">
                                            <input type="email" id="{{ $field['key'] }}_confirmation"
                                                name="{{ $field['key'] }}_confirmation" class="form-control email"
                                                data-parsley-equalto="#{{ $field['key'] }}"
                                                {{ $field['required'] ? 'required' : '' }}>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif

        <!--==========================
      Buy Ticket Section
    ============================-->
        @if ($event->estimate->ticket_sale && $event->landing->show_tickets)
            <section id="subscribe" class="section-with-bg wow fadeInUp">
                <div class="container">
                    <div class="section-header">
                        <h2>{{ $event->landing->tickets_title }}</h2>
                        <p>
                            {{ $event->landing->tickets_subtitle }}
                        </p>
                    </div>

                    <div class="row venoot-shopping-cart">
                        <div id="venoot-shopping-cart" data-id={{ $event->id }} data-env={{ $env }}>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!--==========================
      Contact Section
    ============================-->
        @if ($event->landing->has_contact)
            <section id="contact" class="section-bg wow fadeInUp">
                <div class="container">
                    <div class="section-header">
                        <h2>{{ $event->landing->contact_title }}</h2>
                        <p>
                            {{ $event->landing->contact_subtitle }}
                        </p>
                    </div>

                    <div class="row contact-info">
                        @if ($event->landing->address)
                            <div class="col">
                                <div class="contact-address">
                                    <i class="ion-ios-location-outline"></i>
                                    <h3>{{ $event->landing->address_title }}</h3>
                                    <address>
                                        {{ $event->landing->address }}
                                    </address>
                                </div>
                            </div>
                        @endif

                        @if ($event->landing->phone)
                            <div class="col">
                                <div class="contact-phone">
                                    <i class="ion-ios-telephone-outline"></i>
                                    <h3>{{ $event->landing->phone_title }}</h3>
                                    <p>
                                        <a href="tel:+155895548855">{{ $event->landing->phone }}</a>
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($event->landing->show_email)
                            <div class="col">
                                <div class="contact-email">
                                    <i class="ion-ios-email-outline"></i>
                                    <h3>Email</h3>
                                    <p>
                                        <a href="mailto:{{ $event->email }}">{{ $event->email }}</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($event->landing->show_contact_form)
                        <div class="form">
                            <div id="sendmessage">
                                Tu mensaje fue enviado con éxito!
                            </div>
                            <div id="errormessage"></div>
                            <form id="contact-form" class="contactForm" method="post"
                                action="api/events/{{ $event->id }}/contact">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Nombre" data-rule="minlen:4"
                                            data-msg="Please enter at least 4 chars" />
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Email" data-rule="email"
                                            data-msg="Please enter a valid email" />
                                        <div class="validation"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Asunto" data-rule="minlen:4"
                                        data-msg="Please enter at least 8 chars of subject" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="body" rows="5" data-rule="required"
                                        data-msg="Please write something for us" placeholder="Mensaje"></textarea>
                                    <div class="validation"></div>
                                </div>
                                <div class="text-center">
                                    <button type="submit">Enviar</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </section>
        @endif
        <!-- #contact -->

        <!--==========================
            Gallery Section
            ============================-->
        @if (!empty($event->landing->images))
            <section id="gallery" class="wow fadeInUp">
                <div class="container">
                    <div class="section-header">
                        <h2>{{ $event->landing->images_title }}</h2>
                    </div>
                </div>

                <div class="owl-carousel gallery-carousel">
                    @foreach ($event->landing->images as $image)
                        <a href="{{ Storage::url($image) }}" class="venobox" data-gall="gallery-carousel"><img
                                src="{{ Storage::url($image) }}" alt="" /></a>
                    @endforeach
                </div>
            </section>
        @endif

        <!--==========================
            Compartir en RRSS
            ============================-->
        <footer id="footer">
            @if ($event->landing->has_ssnn)
                <div class="footer-top">
                    <div class="container">
                        <div class="section-header">
                            <h2 style="color:#FFF">{{ $event->landing->ssnn_title }}</h2>
                            <p>

                            </p>
                        </div>

                        @if ($event->twitter || $event->facebook || $event->instagram || $event->google_plus || $event->linkedin)
                            <div class="col-lg-0 col-md-0 text-center">
                                Nuestros sitios en Redes Sociales
                                <div class="social-links text-center" style="margin-top: 10px">
                                    @if ($event->twitter)
                                        <a href="{{ $event->twitter }}" class="twitter"><i
                                                class="fa fa-twitter"></i></a>
                                    @endif
                                    @if ($event->facebook)
                                        <a href="{{ $event->facebook }}" class="facebook"><i
                                                class="fa fa-facebook"></i></a>
                                    @endif
                                    @if ($event->instagram)
                                        <a href="{{ $event->instagram }}" class="instagram"><i
                                                class="fa fa-instagram"></i></a>
                                    @endif
                                    @if ($event->google_plus)
                                        <a href="{{ $event->google_plus }}" class="google-plus"><i
                                                class="fa fa-google-plus"></i></a>
                                    @endif
                                    @if ($event->linkedin)
                                        <a href="{{ $event->linkedin }}" class="linkedin"><i
                                                class="fa fa-linkedin"></i></a>
                                    @endif
                                </div>
                            </div>
                            <br />
                        @endif
                        <div class="col-lg-0 col-md-0 text-center">
                            Compartir en Redes Sociales
                            <div class="social-links text-center" style="margin-top: 10px">
                                @foreach (['twitter', 'facebook', 'linkedin'] as $service)
                                    <a href="{{ url("/events/{$event->id}/share/{$service}") }}"
                                        class="{{ $service }}" target="_blank">
                                        <i class="fa fa-{{ $service }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="container">
                <div id="stuff" class="copyright">
                    &copy; Copyright <strong>{{ $event->company->social_reason }}</strong>. Todos
                    los derechos reservados.
                </div>
            </div>
        </footer>
        <!-- #footer -->
    </main>

    <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- Template Main Javascript File -->
    <script type="text/javascript" src="{{ mix('js/landing.js') }}"></script>

    @if ($event->estimate->ticket_sale)
        <script src=/shopping-cart/js/app.js?v=2></script>
    @endif

    <!-- Load FilePond library -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js">
    </script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <!-- <script src="/tpl_editor/editor.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"
        integrity="sha256-pEdn/pJ2tyT37axbEIPkyUUfuG1yXR0+YV+h+jphem4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/i18n/es.js"
        integrity="sha256-dcJkxln8t7jxoFFA4jP3/ru8rFOlKpt478JM/wsMsgU=" crossorigin="anonymous"></script>

    <!-- Custom Scripts Javascript -->
    <script>
        // Editor
        // $(document).ready(function(){Editor('1', {{ $event->id }}, {{ $editor ? 'true' : 'false' }});})


        $(function() {

            // Get user if it exists
            let qr_verificado = false
            const queryString = window.location.search
            const urlParams = new URLSearchParams(queryString)
            const qr = urlParams.get('qr')

            if (qr) {
                $('#video-secure-button').attr('href', window.location.protocol + '//' + window.location.host +
                    '/venoot-chat?uuid=' + qr)

                $('.video-secure-activity').each((index, element) => {
                    element.setAttribute('href', element.getAttribute('href') + '&uuid=' + qr)
                })

                var url = "/api/events/{{ $event->id }}/landing/qr";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        uuid: qr
                    },
                    success: function(data) {
                        qr_verificado = true
                        for (let [key, value] of Object.entries(data.participant)) {
                            $("#" + key).attr("value", value)
                        }

                    }
                });
            }

            // Gallery carousel (uses the Owl Carousel library)
            $('.gallery-carousel').owlCarousel({
                autoplay: true,
                dots: true,
                loop: {{ $event->landing->loop ? 'true' : 'false' }},
                center: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 4
                    },
                    1200: {
                        items: 5
                    }
                }
            })

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

            // Form handling
            $('#contact-form').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    var url = "/api/events/{{ $event->id }}/contact";
                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function(data) {
                            alert("Mensaje Enviado!")
                        }
                    });
                    return false;
                }
            })

            // when the form is submitted
            var uuid = null;
            $('#confirmation-form').parsley();
            $('#confirmation-form').on('submit', function(e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = "/api/participants/confirmFromForm";

                    // POST values in the background the the script URL
                    console.log("WELP")
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function(data) {
                            // data = JSON object that contact.php returns

                            // we recieve the type of the message: success x danger and apply it to the
                            var messageAlert = 'alert-' + data.type;
                            var messageText = data.message;
                            uuid = data.uuid;

                            // let's compose Bootstrap alert box HTML
                            //var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable">' + messageText + '</div>';

                            // If we have messageAlert and messageText
                            if (messageAlert && messageText) {
                                // inject the alert to .messages div in our form
                                //$('#confirmation-space').html(alertBox);
                                $('#confirmed-space').show();
                                $('#confirmation-space').hide();
                                // empty the form
                                $('#confirmation-form')[0].reset();

                                $('#confirmation-modal').modal('hide')
                            }
                        }
                    });
                    return false;
                }
            })

            $('#copy-link-button').click(function(e) {
                var dummy = document.createElement("textarea");
                document.body.appendChild(dummy);
                dummy.value = "https://venoot-pro.work/venoot-chat?uuid=" + uuid;
                dummy.select();
                document.execCommand("copy");
                document.body.removeChild(dummy);
            })

            // Initialize Venobox
            $('#video-box').venobox({
                bgcolor: '',
                overlayColor: 'rgba(6, 12, 34, 0.85)',
                closeBackground: '',
                closeColor: '#fff',
            })

            $('#video-box-secure').venobox({
                bgcolor: '',
                overlayColor: 'rgba(6, 12, 34, 0.85)',
                closeBackground: '',
                closeColor: '#fff',

                cb_pre_close: function(obj, gallIndex, thenext, theprev) {
                    const uuid = $("#qr-code").val()
                    console.log(uuid)
                    $.ajax({
                        type: "POST",
                        url: "/api/events/{{ $event->id }}/participants/doneVideo",
                        data: {
                            uuid
                        },
                        success: function(data) {
                            console.log(data)
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    });
                },
            })

            // when the form is submitted
            $('#video-form').on('submit', function(e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = "/api/events/{{ $event->id }}/participants/secureVideo";

                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function(data) {
                            if (data.success) {
                                $('#video-modal').modal('hide')
                                $('#video-box-secure').attr("href", data.url +
                                    "&modestbranding=1&rel=0")
                                $('#video-box-secure').click();
                            } else {
                                $('#qr-code-error').text('Número no reconocido.')
                            }
                        },
                        error: function(error) {
                            if (error.status === 422) {
                                $('#qr-code-error').text('Formato no es valido.')
                            }
                        }
                    });
                    return false;
                }
            })
        });
    </script>
</body>

</html>
