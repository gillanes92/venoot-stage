<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Encuentro de Economía Circular</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="description" content="El encuentro organizado por Fundación Empresarial Eurochile buscará dar visibilidad al aporte de la Economía Circular en la mitigación del Cambio Climático así como promoverla a las pymes chilenas y latinoamericanas, difundiendo experiencias y buenas prácticas de Europa y América Latina." >
  <meta name="keywords" content="COP25, eurochile.cl, side event cop25, Fundación Empresarial Eurochile" />
  <meta name="author" content="Fundación Empresarial Eurochile" />

  <!-- Favicons -->
  <link href="{{ url('/images/favicon.png') }}" rel="icon" />
  <link href="{{ url('/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">

  <!-- Font Awesome -->
    <link
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        rel="stylesheet"
    />

  <!-- Main Stylesheet File -->
  <link href="{{ mix('css/static-landing.css') }}" rel="stylesheet" />

  <!-- Main Stylesheet File -->
  <link href="css/euro.css" rel="stylesheet">

  <link rel="stylesheet" href="//unpkg.com/bootstrap-select@1.12.4/dist/css/bootstrap-select.min.css" type="text/css" />
  <link rel="stylesheet" href="//unpkg.com/bootstrap-select-country@4.0.0/dist/css/bootstrap-select-country.min.css" type="text/css" />

  <style>
      /*
        Make bootstrap-select work with bootstrap 4 see:
        https://github.com/silviomoreto/bootstrap-select/issues/1135
        */
        .dropdown-toggle.btn-default {
        color: #292b2c;
        background-color: #fff;
        border-color: #ccc;
        }
        .bootstrap-select.show > .dropdown-menu > .dropdown-menu {
        display: block;
        }
        .bootstrap-select > .dropdown-menu > .dropdown-menu li.hidden {
        display: none;
        }
        .bootstrap-select > .dropdown-menu > .dropdown-menu li a {
        display: block;
        width: 100%;
        padding: 3px 1.5rem;
        clear: both;
        font-weight: 400;
        color: #292b2c;
        text-align: inherit;
        white-space: nowrap;
        background: 0 0;
        border: 0;
        text-decoration: none;
        }
        .bootstrap-select > .dropdown-menu > .dropdown-menu li a:hover {
        background-color: #f4f4f4;
        }
        .bootstrap-select > .dropdown-toggle {
        width: 100%;
        }
        .dropdown-menu > li.active > a {
        color: #fff !important;
        background-color: #337ab7 !important;
        }
        .bootstrap-select .check-mark {
        line-height: 14px;
        }
        .bootstrap-select .check-mark::after {
        font-family: "FontAwesome";
        content: "\f00c";
        }
        .bootstrap-select button {
        overflow: hidden;
        text-overflow: ellipsis;
        }

        /* Make filled out selects be the same size as empty selects */
        .bootstrap-select.btn-group .dropdown-toggle .filter-option {
        display: inline !important;
        }

        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success {
        color: #468847;
        background-color: #DFF0D8;
        border: 1px solid #D6E9C6;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
        color: #B94A48;
        background-color: #F2DEDE;
        border: 1px solid #EED3D7;
        }

        .parsley-errors-list {
        margin: 2px 0 3px;
        padding: 0;
        list-style-type: none;
        font-size: 0.9em;
        line-height: 0.9em;
        opacity: 0;
        color: #B94A48;

        transition: all .3s ease-in;
        -o-transition: all .3s ease-in;
        -moz-transition: all .3s ease-in;
        -webkit-transition: all .3s ease-in;
        }

        .parsley-errors-list.filled {
        opacity: 1;
        }

        #intro .about-btn {
                background: #f82249;
            }
  </style>
</head>

<body>

  <!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">

        <a href="#intro" class="scrollto"></a>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="#intro">Inicio</a></li>
          <li><a href="#about">Sobre el evento</a></li>
          <li><a href="#schedule">Programa</a></li>
          <li><a href="#contact">Contacto</a></li>
          <li class="buy-tickets"><a href="#buy-tickets">Cómo participar</a></li>
           <li class="menu-active"><a id="en-link" href="/economia-circular-en">EN</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->

  <!--==========================
    Intro Section
  ============================-->
  <section id="intro">
    <div class="intro-container wow fadeIn">
      <h2 class="mb-4 pb-0"><span>Encuentro de Economía Circular</span> </h2>
      <h1 class="mb-4 pb-0">Conferencia y Ronda Empresarial sobre Economía Circular</h1>
      <p class="mb-4 pb-0"> <span> </span></p>
      <p class="mb-4 pb-0">9, 10 y 11 de Diciembre 2020 |<a href="#venue"> Santiago, Evento virtual </a></p>

      <a
        id="video-secure-button"
        type="button"
        class="play-btn mb-4"
        href="/venoot-chat?activity=1341"
        target="_blank"
      >
      </a>

      <a id="ex-about-button" href="/venoot-chat?activity=1341" class="about-btn scrollto">Señal en Vivo</a>
    </div>
  </section>

  <main id="main">

    <!--==========================
      about
    ============================-->
    <section id="about" class="section-with-bg wow fadeInUp">
      <div class="container">

        <div class="section-header">
          <h2>De la línea al círculo </h2>
          <p> La economía mundial vive un punto de inflexión. Sus procesos productivos deberán adaptarse para seguir existiendo y se acerca el fin de la era lineal: extraer recursos- transformarlos- producir- consumir - desechar. Factores medioambientales y sociales están empujando al modelo actual a transformarse en un círculo: con procesos eficientes que usan ERNC y materiales reciclados, que gestionan sus desechos, que reducen y reutilizan.
Múltiples oportunidades de negocios para empresas de todo tamaño, aunque las Pymes juegan un rol primordial por su flexibilidad, se abren en este nuevo escenario, a través de trabajos colaborativos, sinergias y traspaso de buenas prácticas.

Eurochile invita a este encuentro a empresas, ONG, instituciones, autoridades y actores sociales, para conocer el camino avanzado en Economía Circular y lo mucho que queda por hacer, con expertos europeos y nacionales; además de abrir un espacio de conexión entre empresas nacionales y europeas que ya son parte de este ecosistema o que quieren serlo. </p>
          <p><a href="#buy-tickets" class="about-btn scrollto">Inscríbete</a></p>
        </div>

      </div>

    </section>

    <!--==========================
      Buy Ticket Section
    ============================-->
    <section id="buy-tickets" class="section-with-bg wow fadeInUp">
      <div class="container">


        <div class="section-header">
          <h2>Cómo Participar</h2>

          <p>Si quieres participar del Evento, debes inscribirte en 1 <b>Conferencia sobre Economía Circular</b>. </p>
          <p>Adicionalmente, si quieres participar de la Encuentros Bilaterales, debes registrarte y completar tu perfil en 2 <b> Encuentros Bilaterales</b>.</p>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="card mb-5 mb-lg-0">
              <div class="card-body text-center">

                <h2>1</h2>
                <h2>Conferencia Economia Circular</h2>
                <hr>
                <div id="confirmation-space" class="text-center">
                    <button type="button" class="btn" data-toggle="modal" data-target="#confirmation-modal">
                        Inscríbete
                    </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card mb-5 mb-lg-0">
              <div class="card-body text-center">
				<h2>2</h2>
                <h2>Encuentros Bilaterales</h2>
                <hr>

                <div class="text-center">
                  <button type="button" class="btn"><a class="about-btn" href="https://circular-economy-conference-brokerage-event.b2match.io/" target="_blank">Completa tu perfil</a></button>
                </div>
              </div>
            </div>
          </div>
          <!-- Pro Tier -->

        </div>

      </div>

    </section>

    <!--==========================
      Speakers Section
    ============================-->
                            {{-- <section id="speakers" class="wow fadeInUp">
                    <div class="container">

                                                <div class="section-header subheader">
                            <h2>Participan</h2>
                        </div>

                        <div class="row justify-content-md-center">
                                                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/zK2FyudKLTUBGANitop6RE2Q3x6Ll3Rz0QURgcrI.jpeg"
                                                alt="Speaker 1"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Vicente Caruz
                                                    </a>
                                                </h3>
                                                <p>Presidente Eurochile</p>
                                                <div class="social">
                                                                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/qvaI3ueqSGEfOU8BRYfqHsnkUWi0JetftAChtSFz.jpeg"
                                                alt="Speaker 2"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        José Aravena
                                                    </a>
                                                </h3>
                                                <p>Director Ejecutivo Eurochile</p>
                                                <div class="social">
                                                                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/qZ6vvgP0sOE6fnxStq1w4rxxScySJUBe4pmjBBsH.jpeg"
                                                alt="Speaker 3"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Carolina Schmidt
                                                    </a>
                                                </h3>
                                                <p>Ministra de Medio Ambiente</p>
                                                <div class="social">
                                                                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/ynE4COFNs1X8wrEKeNBh9lTSn0gq8KjDJBEwWcQx.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Ladeja Godina
                                                    </a>
                                                </h3>
                                                <p>Fundadora y directora ejecutiva de Circular Change y presidenta del grupo de coordinación de la European Circular Economy Stakeholder Platform (ECESP)</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/zK2FyudKLTUBGANitop6RE2Q3x6Ll3Rz0QURgcrI.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Miguel de Porras
                                                    </a>
                                                </h3>
                                                <p>Co-Director FiBL</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="speaker">
                                            <img
                                                src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                                                alt="Speaker 4"
                                                class="venoot-img-fluid img-fluid"
                                            />
                                            <div class="details">
                                                <h3>
                                                    <a href="speaker-details.html">
                                                        Sebastián Sichel
                                                    </a>
                                                </h3>
                                                <p>Presidente Banco Estado</p>
                                                <div class="social">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </section> --}}


    <!--==========================
      Schedule Section
    ============================-->
    <section id="schedule" class="section-with-bg">
      <div class="container wow fadeInUp">
        <div class="section-header">
          <h2>Programa del evento</h2>
          <p>Descarga el <a class="about-btn" href="/uploads/PROGRAMA EVENTO ECONOMIA CIRCULAR 04.12.2020 ESPAÑOL.pdf">programa</a></p>
        </div>

        <ul class="nav nav-tabs" role="tablist">

          <li class="nav-item">
            <a class="nav-link active show" href="#day-1" role="tab" data-toggle="tab">9 de Diciembre</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#day-2" role="tab" data-toggle="tab">10 de Diciembre</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#day-3" role="tab" data-toggle="tab">11 de Diciembre</a>
          </li>

        </ul>

        <h3 class="sub-heading"> </h3>

        <div class="tab-content row justify-content-center">

          <!-- Schdule Day 1 -->
          <div role="tabpanel" class="col-lg-9 tab-pane fade show active" id="day-1">

            <div class="row schedule-item">
              <div class="col-md-2"><time>09:00 - 10:30</time></div>
              <div class="col-md-10">
                <h4>Panel de inauguración -
                    Economía Circular: una herramienta para el cambio climático que funcione para la economía, la sociedad y el medioambiente
                </h4>
                <p>
                    En esta sesión, se discutirá el potencial de la economía circular para mitigar el cambio climático, se presentará el alcance del “Green Deal”, plan de la Comisión para asegurar el desarrollo sostenible de Europa, y se presentarán los avances alcanzados en la construcción de una Hoja de Ruta de EC para Chile.
                </p>
                <br />
                <p>
                    <strong>Bienvenida y palabras de autoridades </strong>
                </p>
                    <ul>
                        <li>Vicente Caruz, Presidente Eurochile</li>
                        <li>Kestutis Sadauskas, Director de la Unidad de Crecimiento Verde y Economía Circular de la Unión Europea</li>
                        <li>Javier Naranjo, Subsecretario del Ministerio de Medio Ambiente</li>
                        <li>Pablo Terrazas, Vicepresidente de Corfo</li>
                        <li>Sebastián Sichel, Presidente Banco Estado </li>
                        <li>Ladeja Godina, Fundadora y directora ejecutiva de Circular Change y presidenta del grupo de coordinación de la European Circular Economy Stakeholder Platform (ECESP), Eslovenia</li>
                    </ul>
                    <br>
                </p>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/yRatWsNPOXAatJP1DfhpVeBOMfJkVD8OOqlqTclJ.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/VWcAUvYH9xRGfzny6R2AoJVe26uS6uvZGL42pYDI.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>

                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/jKNS6hvGDQoQIn0W4taNtLraB9YdcHgj5vDU51v7.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/leIcYZYFGdFvgZsBelb80wLg3zZtbPn3e6NY0z6x.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>


                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/PtiNtT1jpvwh8xYlu8A3E91MIth9m2zU5RAnSaB0.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/ynE4COFNs1X8wrEKeNBh9lTSn0gq8KjDJBEwWcQx.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
              </div>
            </div>

            <div class="row schedule-item">
              <div class="col-md-2"><time>11:30 - 13:00</time></div>
              <div class="col-md-10">
                <h4>Panel 1 - La Economía Circular vista desde distintos sectores productivos</h4>
                <p>
                    En esta sesión se discutirá el potencial que tiene para diferentes sectores económicos la adopción de modelos circulares de producción.
                </p>
                <br />
                <p>
                    <ul>
                        <li>Miguel de Porras, Co- Director FiBL, Bruselas</li>
                        <li>Daniela Acuña, Encargada de Sistemas Productivos, Departamento de Sustentabilidad y Cambio Climático, ODEPA </li>
                        <li>Juan Carlos León, Gerente General Corporación de Desarrollo Tecnológico, CChC</li>
                        <li>Darío Morales, Director de Estudios, ACERA</li>
                        <li>Carlos Romero, Director SEGITTUR</li>
                    </ul>
                    <br>
                </p>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/UUegMRjrVRHsLMIS05v48DIZi1eSiVmDcBqYEsLE.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/TMG9Wh1bUJlclwxaXpKkNpp7LkMVZRAAhY7D9ANL.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/5b8Cg4NHYvofj0R8EYacSSGsw8UVoXmB6zcR8j5z.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/YL6WmA7QU0saPvmSQ31a07dlBk4644VB9Lwn3iYH.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/u8Xfc5xVzXXKZWsB44WfHrHzEwgacX96vrJOHgx8.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
              </div>
            </div>
          </div>
          <!-- End Schdule Day 1 -->

          <!-- Schdule Day 2 -->
           <div role="tabpanel" class="col-lg-9 tab-pane fade show" id="day-2">

            <div class="row schedule-item">
              <div class="col-md-2"><time>09:00 - 10:30</time></div>
              <div class="col-md-10">
                <h4>Panel 2 - Habilitadores para la adopción de una Economía Circular</h4>
                <p>
                    En esta sesión, se discutirá de herramientas, programas, tecnologías, incentivos y reglamentaciones que facilitan la adopción de la economía circular a nivel de empresas.
                </p>
                <br />
                <p>
                    <ul>
                        <li>Marieke van der Werf,  Socia y Directora DR2 Economy</li>
                        <li>Jocelyn Blériot, Líder ejecutivo de Instituciones, Gobiernos & Ciudades, Ellen MacArthur Foundation</li>
                        <li>Guillermo González, Jefe de la oficina de Economía Circular  del Ministerio de Medio Ambiente</li>
                        <li>Juan Pablo Basso Abas, Coordinador de I+D+i con la Industria, Centro de Innovación UC - Anacleto Angelini</li>
                        <li>Gloria Moya, Subdirectora de Corfo RM</li>
                    </ul>
                    <br>
                </p>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/MqgGtEuKuiIHrcDDyIlJvmQPQdIjovd3pfoOfO7r.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/RtfP9yZaWCxpuyOSr1UfhSTBpu5Fy9VkRH9vFZ3h.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/B0KjnF8g7z0kql6WYX3n8dgwPcfB0YXvB7IpkfBH.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                 <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/x9Tfstq89cAs43fFsk3SlAW4xle7M52kRXo5Wf76.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/QyzBM2borVumBMKxfcVPXUk0zyb7OdTBPuperIJA.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
              </div>
            </div>

            <div class="row schedule-item">
              <div class="col-md-2"><time>11:30 - 13:00</time></div>
              <div class="col-md-10">
                <h4>Panel de cierre - ¿Cómo avanzamos en la transición a una Economía Circular en Chile y el mundo?</h4>
                <p>
                    En esta sesión se reflexionará sobre las experiencias chilenas y europeas y las distintas formas de cooperación (chileno-europea; público privada; regional; asociatividad privada) que pueden ayudar a empresas, territorios y sectores de Chile a ser más circulares.
                </p>
                <br>
                <p>
                    <ul>
                        <li>Luca Meini, Gerente de Economía Circular ENEL Holding</li>
                        <li>Raffaele Cattaneo, Concejal de Medio Ambiente y Clima de la Región de la Lombardía</li>
                        <li>Claudia Pizarro, Alcaldesa de La Pintana</li>
                        <li>Cristián Rojas, Presidente de AEPA y Gerente General de PTHGA</li>
                        <li>José Aravena, Director Ejecutivo de Eurochile</li>
                    </ul>
                </p>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/G5iJRpeoDhED4ZdE1kTI37O00Dp2y5VmZuExE7ET.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/71WtUrttdAcxBVCESsPTi8c2xNoACLSl4vGgKNuR.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/S0vktJW61kpY2TX4mwVpAOMNJuzgxta0arhlBUex.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/iayTQucot4i1tkMkSQflKaq1kj1GGeB3Ta9dLVvu.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
                <div class="speaker">
                    <img
                        src="https://venoot-stage.work/public/images/qvaI3ueqSGEfOU8BRYfqHsnkUWi0JetftAChtSFz.jpeg"
                        alt="Speaker 1"
                        class="venoot-img-fluid img-fluid"
                    />
                </div>
              </div>
            </div>

            <div class="row schedule-item">
              <div class="col-md-2"><time>15:00 - 17:00</time></div>
              <div class="col-md-10">
                <h4>Talleres</h4>
                <p>
                    En esta instancia se llevarán a cabo 3 talleres de distintas temáticas: <br>
                    <br>
                    <ul>
                        <li>Taller de introducción al Ecodiseño que tiene como objetivo conocer los conceptos del Ecodiseño para luego aplicar la metodología e identificar mejoras de un productos para agregarle valor de sustentabilidad.</li>
                        <li>Taller Generación de Redes para la Economía Circular cuyo objetivo es conocer el trabajo de recuperación de empresas en Europa post COVID a través de la red europea Enterprise Europe Network  existente en 60 países.  </li>
                        <li>Taller Nuevos Modelos de Negocios y Estrategias Circulares en Turismo que tiene como objetivo dar a conocer mediante casos prácticos cómo la Economía Circular permite desarrollar y/o mejorar los modelos de negocios de empresas turísticas con estrategias que conduzcan a un uso más eficiente de los recursos y por ende a una mayor competitividad. </li>
                    </ul>
                    <p>
                    Para participar en uno de ellos debe indicarlo en su formulario de registro. Estos talleres se dictarán de forma remota y los cupos son limitados.
                    </p>
                </p>
              </div>
            </div>
          </div>
          <!-- End Schdule Day 2 -->

          <!-- Schdule Day 3 -->
          <div role="tabpanel" class="col-lg-9 tab-pane fade show" id="day-3">

            <div class="row schedule-item">
              <div class="col-md-2"><time>08:00 - 13:30</time></div>
              <div class="col-md-10">
                <h4>Ronda Empresarial</h4>
                <p>
                    Instancia para que los empresarios y representantes de instituciones participantes puedan conocer de manera bilateral contrapartes, chilenas y europeas, buscando  la concreción de negocios e intercambio de ideas y conocimientos, transferencias tecnológicas y networking para futuras colaboraciones.
                </p>
              </div>
            </div>
          </div>
          <!-- End Schdule Day 2 -->

        </div>

      </div>

    </section>

    <!--==========================
      Subscribe Section
    ============================-->
    <section id="about"  class="section-with-bg wow fadeInUp">
      <div class="container">

        <div class="section-header">
          <h2>Inscríbete al evento</h2>

          <p><a href="#buy-tickets" class="about-btn scrollto">Formulario de inscripción</a></p>
        </div>

      </div>

      <!-- Modal Order Form -->

    </section>

    <!--==========================
      Hotels Section
    ============================-->
    <!--==========================
      Gallery Section
    ============================-->
    <!--==========================
      Sponsors Section
    ============================-->
    <section id="supporters" class="section-with-bg wow fadeInUp">

      <div class="container">
        <div class="section-header">
          <h2>organiza</h2>
        </div>

        <div class="row no-gutters supporters-wrap clearfix ">
          <div class="col-lg-3 col-md-4 col-xs-6">
            <div class="supporter-logo">
              <img src="img/supporters/eurochile.png" class="img-fluid" alt="">
            </div>
          </div>

          <div class="col-lg-3 col-md-4 col-xs-6">
            <div class="supporter-logo">
              <img src="img/supporters/een.png" class="img-fluid" alt="">
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="section-header">
          <h2>PROYECTO APOYADO POR </h2>
        </div>

        <div class="row no-gutters supporters-wrap clearfix">

          <div class="col-lg-3 col-md-4 col-xs-6">
            <div class="supporter-logo">
              <img src="img/supporters/corfo.png" class="img-fluid" alt="">
            </div>
          </div>
        </div>
      </div>

      </div>

    </section>

    <!--==========================
      Venue Section
    ============================-->
    {{-- <section id="venue" class="wow fadeInUp">

      <div class="container-fluid">

        <div class="section-header">
          <h2>Lugar del evento</h2>
          <p> </p>
        </div>

        <div class="row no-gutters">
          <div class="col-lg-6 venue-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.5732293878327!2d-3.6987219846040293!3d40.41830417936495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42288472c293df%3A0xa921f67f134d1c1a!2sC%C3%ADrculo%20de%20Bellas%20Artes!5e0!3m2!1ses!2scl!4v1573852432594!5m2!1ses!2scl" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
          </div>

          <div class="col-lg-6 venue-info">
            <div class="row justify-content-center">
              <div class="col-11 col-lg-8">
                <h3>Círculo de Bellas Artes</h3>
                <p>Calle de Alcalá, 42, 28014 Madrid, España</p>
              </div>
            </div>
          </div>
        </div>

      </div>



    </section> --}}

    <!--==========================
      Hotels Section
    ============================-->


    <!--==========================
      F.A.Q Section
    ============================-->


    <!--==========================
      Contact Section
    ============================-->
    <section id="contact" class="section-bg wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2>Contacto</h2>
          {{-- <p>Evento Paralelo COP25 Madrid</p> --}}
        </div>

        <div class="row contact-info">

          <div class="col-md-12">
            <div class="contact-address">
              <i class="ion-ios-location-outline"></i>
              {{-- <h3>Madrid</h3>
              <p>Jesús Rojo</p> --}}
              <p><a href="mailto:cop25@madrimasd.org">economiacircular@eurochile.cl</a></p>
            </div>
            {{-- <div class="contact-address">
              <i class="ion-ios-location-outline"></i>
              <h3>Chile</h3>
              <p>Linnet Solway</p>
              <p><a href="mailto:sideevent@eurochile.cl">sideevent@eurochile.cl</a></p>

            </div> --}}
          </div>

        </div>

      </div>
    </section><!-- #contact -->

  </main>

  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-12 col-md-6 footer-info">
            <h4>Sobre los organizadores</h4>

            <p><strong>Fundación Empresarial Eurochile</strong> promueve la cooperación económica, comercial y tecnológica entre empresarios e instituciones de Chile y la UE mediante la promoción de negocios, transferencia de tecnología y saber hacer, así como a través de proyectos de mejoramiento del entorno para la competitividad empresarial.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong> 2019</strong>
      </div>

    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

  <!--==========================
    Formulario
    ============================-->
    <form id="confirmation-form">
        <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Formulario de Confirmación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="event_id" value="133">
                        <div class="form-group">
                            <label>Nombres</label>
                            <input type="text" id="name" name="name" class="form-control" data-parsley-pattern="^[a-zA-Z ]+$" required>
                        </div>

                        <div class="form-group">
                            <label>Apellidos</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" data-parsley-pattern="^[a-zA-Z ]+$" required>
                        </div>

                        <div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Empresa/Organización</label>
                            <input type="text" id="company" name="company" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Cargo</label>
                            <input type="text" id="job" name="job" class="form-control" data-parsley-pattern="^[a-zA-Z ]+$" required>
                        </div>

                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" id="phone" name="phone" class="form-control" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" required>
                        </div>

                        <div class="form-group">
                            <label>País</label>
                            <select id="country" name="country" class="selectpicker countrypicker" data-default="cl" data-flag="true" ></select>
                        </div>

                        <div class="form-group">
                            <label>Participación Talleres</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="generacion" name="generacion" data-parsley-multiple="talleres"data-parsley-errors-container="#error-container" >
                                <label class="form-check-label" for="generacion">Generación de redes para la EC</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="introduccion" name="introduccion" data-parsley-errors-container="#error-container" data-parsley-multiple="talleres">
                                <label class="form-check-label" for="introduccion">Introducción al Ecodiseño</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="nuevos" name="nuevos" data-parsley-multiple="talleres" data-parsley-errors-container="#error-container">
                                <label class="form-check-label" for="nuevos">Nuevos modelos de negocios y estrategias circulares en Turismo</label>
                            </div>
                            <div id="error-container"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar asistencia</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

        <!-- Template Main Javascript File -->
        <script type="text/javascript" src="{{ mix('js/landing.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js" integrity="sha256-pEdn/pJ2tyT37axbEIPkyUUfuG1yXR0+YV+h+jphem4=" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/i18n/es.js" integrity="sha256-dcJkxln8t7jxoFFA4jP3/ru8rFOlKpt478JM/wsMsgU=" crossorigin="anonymous"></script>

        <script src="//unpkg.com/bootstrap-select@1.12.4/dist/js/bootstrap-select.min.js"></script>
        <script src="//unpkg.com/bootstrap-select-country@4.0.0/dist/js/bootstrap-select-country.min.js"></script>

        <!-- Custom Scripts Javascript -->
        <script>
            $(function () {

                // Get user if it exists
                let qr_verificado = false
                const queryString = window.location.search
                const urlParams = new URLSearchParams(queryString)
                const qr = urlParams.get('qr')

                if (qr) {
                    $('#video-secure-button').attr('href', window.location.protocol + '//' + window.location.host +  '/venoot-chat?activity=1341&uuid=' + qr)

                    $('#ex-about-button').attr('href', window.location.protocol + '//' + window.location.host +  '/venoot-chat?activity=1341&uuid=' + qr)

                    $('.video-secure-activity').each((index, element) => {
                        element.setAttribute('href', element.getAttribute('href') + '&uuid=' + qr)
                    })

                    $('#en-link').attr('href', '/economia-circular-en?qr=' + qr)

                    var url = "/api/events/133/landing/qr";
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: {
                            uuid: qr
                        },
                        success: function (data)
                        {
                            qr_verificado = true
                            for (let [key, value] of Object.entries(data.participant)) {
                                $("#" + key).attr("value", value)
                            }

                        }
                    });
                }

                // Form handling
                $('#contact-form').on('submit', function (e) {
                    if (!e.isDefaultPrevented()) {
                        var url = "/api/events/133/contact";
                        // POST values in the background the the script URL
                        $.ajax({
                                type: "POST",
                                url: url,
                                data: $(this).serialize(),
                                success: function (data)
                                {
                                    alert("Mensaje Enviado!")
                                }
                            });
                            return false;
                    }
                })

                var confirmationForm = $('#confirmation-form').parsley();
                $('#country').countrypicker();

                // when the form is submitted
                $('#confirmation-form').on('submit', function (e) {

                    e.preventDefault();
                    if (!confirmationForm.isValid()) return;

                    // if the validator does not prevent form submit
                    var url = "/api/participants/confirmFromForm";

                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function (data)
                        {
                            // data = JSON object that contact.php returns

                            // we recieve the type of the message: success x danger and apply it to the
                            var messageAlert = 'alert-' + data.type;
                            var messageText = data.message;

                            // let's compose Bootstrap alert box HTML
                            var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable">' + messageText + '</div>';

                            // If we have messageAlert and messageText
                            if (messageAlert && messageText) {
                                // inject the alert to .messages div in our form
                                $('#confirmation-space').html(alertBox);
                                // empty the form
                                $('#confirmation-form')[0].reset();

                                $('#confirmation-modal').modal('hide')
                            }
                        }
                    });
                    return false;
                })

                // Initialize Venobox
                $('#video-box').venobox(
                    {
                        bgcolor: '',
                        overlayColor: 'rgba(6, 12, 34, 0.85)',
                        closeBackground: '',
                        closeColor: '#fff',
                    }
                )

                $('#video-box-secure').venobox(
                    {
                        bgcolor: '',
                        overlayColor: 'rgba(6, 12, 34, 0.85)',
                        closeBackground: '',
                        closeColor: '#fff',

                        cb_pre_close  : function(obj, gallIndex, thenext, theprev){
                            const uuid = $("#qr-code"). val()
                            console.log(uuid)
                            $.ajax({
                                type: "POST",
                                url: "/api/events/133/participants/doneVideo",
                                data: {
                                    uuid
                                },
                                success: function (data)
                                {
                                    console.log(data)
                                },
                                error: function (error)
                                {
                                    console.log(error)
                                }
                            });
                        },
                    }
                )

                // when the form is submitted
                $('#video-form').on('submit', function (e) {

                    // if the validator does not prevent form submit
                    if (!e.isDefaultPrevented()) {
                        var url = "/api/events/133/participants/secureVideo";

                        // POST values in the background the the script URL
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: $(this).serialize(),
                            success: function (data)
                            {
                                if(data.success)
                                {
                                    $('#video-modal').modal('hide')
                                    $('#video-box-secure').attr("href", data.url + "&modestbranding=1&rel=0")
                                    $('#video-box-secure').click();
                                }
                                else
                                {
                                    $('#qr-code-error').text('Número no reconocido.')
                                }
                            },
                            error: function (error)
                            {
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
