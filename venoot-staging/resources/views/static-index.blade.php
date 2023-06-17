

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Venoot.com</title>
    <meta name="description" content="Plataforma de gestión, tracking, y gestión para marketing de clientes.">
    <meta name="author" content="Venoot.com">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/vendor.css">
    <link rel="stylesheet" href="/css/main_static.css">

    <!-- script
    ================================================== -->
    <script src="/js/modernizr.js"></script>
    <script src="/js/pace.min.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="icon" href="favicon.png" type="image/x-icon">

    <!-- Fontawesome
    ================================================== -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <style>
        div.sticky {
            background: #1A1A1A;
            color: white;
            height: 30px;

            opacity: 0.5;
            text-align: left;
            width: 100%;
            /* hacemos que la cabecera ocupe el ancho completo de la página */
            left: 0;
            /* Posicionamos la cabecera al lado izquierdo */
            top: 0;
            /* Posicionamos la cabecera pegada arriba */
            position: fixed;
            /* Hacemos que la cabecera tenga una posición fija */
            z-index: 999;
        }

        @media only screen and (max-width: 400px) {
            .sticky {
                text-align: left;
            }
        }
    </style>

</head>

<body id="top">
    <div class="sticky">
    </div>
    <!-- header
    ================================================== -->
    <header class="s-header">

        <div class="header-logo">
            <a class="site-logo" href="http://www.venoot.com">
                <img src="/images/logo-venoot-web.svg" style="width: 200px;" alt="Homepage">
            </a>
        </div> <!-- end header-logo -->

        <nav class="header-nav">

            <a href="#0" class="header-nav__close" title="close"><span>Close</span></a>

            <div class="header-nav__content">
                <h3>Venoot.com</h3>

                <ul class="header-nav__list">
                    <li class="current"><a class="smoothscroll" href="#home" title="home"><?php echo $lang["home"]; ?></a></li>
                    <li><a class="smoothscroll" href="#about" title="about"><?php echo $lang["nosotros"]; ?></a></li>
                    <li><a class="smoothscroll" href="#services" title="services"><?php echo $lang["servicios"]; ?></a></li>
                    <li><a class="smoothscroll" href="#works" title="works"><?php echo $lang["plataforma"]; ?></a></li>
                    <li><a class="smoothscroll" href="#contact" title="contact"><?php echo $lang["contacto"]; ?></a></li>
                    <li><a href="{{ url('/login') }}" title="login"><?php echo $lang["loginuser"]; ?></a></li>
                    <li><a href="{{ url('/participant-user') }}" title="login"><?php echo $lang["loginparticipant"]; ?></a></li>
                    <span>Idioma/Language</span><br>

                    <a  href="{{ url('/static/es') }}" style="padding: 1px;width: 3rem;height: 3rem;line-height: initial;">ES</a>
                    <a   href="{{ url('/static/en') }}" style="padding: 1px;width: 3rem;height: 3rem;line-height: initial;">EN</a>

                </ul>

                <p><?php echo $lang["descripcion"]; ?></p>

                <ul class="header-nav__social">
                    <li>
                        <a href="#0"><i class="fab fa-linkedin"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-instagram"></i></a>
                    </li>

                </ul>

            </div> <!-- end header-nav__content -->

        </nav> <!-- end header-nav -->

        <a class="header-menu-toggle" href="#0">
            <span class="header-menu-icon"></span>
        </a>

    </header> <!-- end s-header -->


    <!-- home
    ================================================== -->
    <section id="home" class="s-home target-section" data-parallax="scroll" data-image-src="/images/hero-bg5.jpeg" data-natural-width=3000 data-natural-height=2000 data-position-y=top>

        <div class="shadow-overlay"></div>

        <div class="home-content">

            <div class="row home-content__main">
                <h1>
                    <?php echo $lang["titulo"]; ?>
                </h1>

                <p>
                    <?php echo $lang["bajada"]; ?>
                </p>
            </div> <!-- end home-content__main -->

        </div> <!-- end home-content -->

        <ul class="home-sidelinks">
            <li><a class="smoothscroll" href="#about"><?php echo $lang["nosotros"]; ?><span><?php echo $lang["qhacemos"]; ?></span></a></li>
            <li><a class="smoothscroll" href="#services"><?php echo $lang["servicios"]; ?><span><?php echo $lang["qteofrecemos"]; ?></span></a></li>
            <li><a class="smoothscroll" href="#contact"><?php echo $lang["contacto"]; ?><span><?php echo $lang["conversemos"]; ?></span></a></li>
        </ul> <!-- end home-sidelinks -->


        <!--<ul class="home-social">
            <li class="home-social-title">Conéctate</li>
            <li><a href="https://www.Venoot.com">
                <i class="fas fa-user-cog"></i>
                <span class="home-social-text">Productores</span>
            </a></li>
            <li><a href="https://www.Venoot.com">
                <i class="fas fa-user-tie"></i>
                <span class="home-social-text">Personal Staff</span>
            </a></li>
        </ul> <!-- end home-social -->

        <a href="#about" class="home-scroll smoothscroll">
            <span class="home-scroll__text">Down Page</span>
            <span class="home-scroll__icon"></span>
        </a> <!-- end home-scroll -->

    </section> <!-- end s-home -->


    <!-- about
    ================================================== -->
    <section id='about' class="s-about">
        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead"><?php echo $lang["qs"]; ?></h3>
                <h1 class="display-1"><?php echo $lang["bajadaqs"]; ?></h1>
            </div>
        </div> <!-- end section-header -->

        <div class="row" data-aos="fade-up">
            <div class="col-full">
                <p class="lead">
                    <?php echo $lang["descqs"]; ?>
                </p>
            </div>
        </div> <!-- end about-desc -->

        <div class="row">

            <div class="about-process process block-1-2 block-tab-full">

                <div class="process__vline-left"></div>
                <div class="process__vline-right"></div>

                <div class="col-block process__col" data-item="1" data-aos="fade-up">
                    <div class="process__text">
                        <h4><?php echo $lang["gestiona"]; ?></h4>

                        <p>
                            <?php echo $lang["bgestiona"]; ?>
                        </p>
                    </div>
                </div>
                <div class="col-block process__col" data-item="2" data-aos="fade-up">
                    <div class="process__text">
                        <h4><?php echo $lang["comunica"]; ?></h4>

                        <p>
                            <?php echo $lang["bcomunica"]; ?>
                        </p>
                    </div>
                </div>
                <div class="col-block process__col" data-item="3" data-aos="fade-up">
                    <div class="process__text">
                        <h4><?php echo $lang["seguimiento"]; ?></h4>

                        <p>
                            <?php echo $lang["bseguimiento"]; ?>
                        </p>
                    </div>
                </div>
                <div class="col-block process__col" data-item="4" data-aos="fade-up">
                    <div class="process__text">
                        <h4><?php echo $lang["analitica"]; ?></h4>

                        <p>
                            <?php echo $lang["banalitica"]; ?>
                        </p>
                    </div>
                </div>

            </div> <!-- end process -->

        </div> <!-- end about-stats -->

    </section> <!-- end s-about -->


    <!-- services
    ================================================== -->
    <section id='services' class="s-services light-gray">

        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead"><?php echo $lang["servicios"]; ?></h3>
                <h1 class="display-1"><?php echo $lang["bservicios"]; ?></h1>
            </div>
        </div> <!-- end section-header -->

        <div class="row" data-aos="fade-up">
            <div class="col-full">
                <p class="lead">
                    <?php echo $lang["mservicios"]; ?>
                </p>
            </div>
        </div> <!-- end about-desc -->

        <div class="row services-list block-1-3 block-m-1-2 block-tab-full">

            <div class="col-block service-item " data-aos="fade-up">
                <div class="service-icon service-icon--brand-identity">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s1"]; ?></h3>
                    <p><?php echo $lang["bs1"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon  service-icon--illustration">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s2"]; ?></h3>
                    <p><?php echo $lang["bs2"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon  service-icon--web-design">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s3"]; ?></h3>
                    <p><?php echo $lang["bs3"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--product-strategy">
                    <i class="fas fa-poll"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s4"]; ?></h3>
                    <p><?php echo $lang["bs4"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon  service-icon--ui-design">
                    <i class="fab fa-uikit"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s5"]; ?></h3>
                    <p><?php echo $lang["bs5"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fab fa-cc-amazon-pay"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s6"]; ?></h3>
                    <p><?php echo $lang["bs6"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="far fa-envelope-open"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s7"]; ?></h3>
                    <p><?php echo $lang["bs7"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s8"]; ?></h3>
                    <p><?php echo $lang["bs8"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s9"]; ?></h3>
                    <p><?php echo $lang["bs9"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s10"]; ?></h3>
                    <p><?php echo $lang["bs10"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fab fa-app-store-ios"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s11"]; ?></h3>
                    <p><?php echo $lang["bs11"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fab fa-wpforms"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s12"]; ?></h3>
                    <p><?php echo $lang["bs12"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fab fa-internet-explorer"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s13"]; ?></h3>
                    <p><?php echo $lang["bs13"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="far fa-eye"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s14"]; ?></h3>
                    <p><?php echo $lang["bs14"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-draw-polygon"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s15"]; ?></h3>
                    <p><?php echo $lang["bs15"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s16"]; ?></h3>
                    <p><?php echo $lang["bs16"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s17"]; ?></h3>
                    <p><?php echo $lang["bs17"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s18"]; ?></h3>
                    <p><?php echo $lang["bs18"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fab fa-google"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s19"]; ?></h3>
                    <p><?php echo $lang["bs19"]; ?>
                    </p>
                </div>
            </div>

            <div class="col-block service-item" data-aos="fade-up">
                <div class="service-icon service-icon--mobile-dev">
                    <i class="fas fa-share-square"></i>
                </div>
                <div class="service-text">
                    <h3 class="h4"><?php echo $lang["s20"]; ?></h3>
                    <p><?php echo $lang["bs20"]; ?>
                    </p>
                </div>
            </div>

        </div> <!-- end services-list -->

    </section> <!-- end s-services -->


    <!-- works
    ================================================== -->
    <section id='works' class="s-works">

        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead">Venoot.com</h3>
                <h1 class="display-1"><?php echo $lang["plataforma-venoot"]; ?></h1>
            </div>
        </div> <!-- end section-header -->

        <div class="row masonry-wrap">
            <div class="masonry">

                <div class="masonry__brick" data-aos="fade-up">
                    <div class="item-folio">

                        <div class="item-folio__thumb">
                            <a href="/images/portfolio/gallery/Concurso-innovacion-social.png" class="thumb-link" title="Salad" data-size="1050x500">
                                <img src="/images/portfolio/Concurso-innovacion-social.png" srcset="/images/portfolio/Concurso-innovacion-social.png" alt="">
                            </a>
                        </div>

                        <div class="item-folio__text">
                            <h3 class="item-folio__title">
                                Innovación Social
                            </h3>
                        </div>

                        <a href="https://www.Venoot.com" class="item-folio__project-link" title="Project link">
                            Ampliar Imagen
                        </a>

                        <span class="item-folio__caption">
                            <p>Concurso de innovación social, de la Universidad Santo Tomás</p>
                        </span>

                    </div> <!-- end item-folio -->
                </div> <!-- end masonry__brick -->

                <div class="masonry__brick" data-aos="fade-up">
                    <div class="item-folio">

                        <div class="item-folio__thumb">
                            <a href="/images/portfolio/gallery/david-lynch.png" class="thumb-link" title="Liberty" data-size="1050x500">
                                <img src="/images/portfolio/david-lynch.png" srcset="/images/portfolio/david-lynch.png" alt="">
                            </a>
                        </div>

                        <div class="item-folio__text">
                            <h3 class="item-folio__title">
                                David Lynch
                            </h3>
                        </div>

                        <a href="https://www.Venoot.com" class="item-folio__project-link" title="Project link">
                            Ampliar imagen
                        </a>

                        <span class="item-folio__caption">
                            <p>Cambiando Mentes, cambiando mundos.</p>
                        </span>

                    </div> <!-- end item-folio -->
                </div> <!-- end masonry__brick -->

                <!-- <div class="masonry__brick" data-aos="fade-up">
                    <div class="item-folio">

                        <div class="item-folio__thumb">
                            <a href="/images/portfolio/gallery/larrain.png" class="thumb-link" title="Liberty" data-size="750x900">
                                <img src="/images/portfolio/larrain.png"
                                     srcset="/images/portfolio/larrain.png" alt="">
                            </a>
                        </div>

                        <div class="item-folio__text">
                            <h3 class="item-folio__title">
                                LarrainVial
                            </h3>
                        </div>

                        <a href="https://www.Venoot.com" class="item-folio__project-link" title="Project link">
                            Ampliar imagen
                        </a>

                        <span class="item-folio__caption">
                            <p>Mas allá de las fronteras, email para descarga de código QR.</p>
                        </span>

                    </div> <!-- end item-folio -->
                <!-- </div> -->
                <!-- end masonry__brick -->

                <div class="masonry__brick" data-aos="fade-up">
                    <div class="item-folio">

                        <div class="item-folio__thumb">
                            <a href="/images/portfolio/gallery/ticketCapriotti.jpg" class="thumb-link" title="Paul Capriotti" data-size="850x900">
                                <img src="/images/portfolio/ticketCapriotti.jpg" srcset="/images/portfolio/ticketCapriotti.jpg 1x" alt="">
                            </a>
                        </div>

                        <div class="item-folio__text">
                            <h3 class="item-folio__title">
                                Paul Capriotti
                            </h3>
                        </div>

                        <a href="https://www.Venoot.com" class="item-folio__project-link" title="Project link">
                            Ampliar Imagen
                        </a>

                        <div class="item-folio__caption">
                            <p>Curso de la evaluación mediática y digital.</p>
                        </div>

                    </div> <!-- end item-folio -->
                </div> <!-- end masonry__brick -->

                <div class="masonry__brick" data-aos="fade-up">
                    <div class="item-folio">

                        <div class="item-folio__thumb">
                            <a href="/images/portfolio/gallery/festival-innovacion.jpg" class="thumb-link" title="Woodcraft" data-size="1050x500">
                                <img src="/images/portfolio/festival-innovacion.jpg" srcset="/images/portfolio/festival-innovacion.jpg" alt="">
                            </a>
                        </div>

                        <div class="item-folio__text">
                            <h3 class="item-folio__title">
                                Festival de Innovación
                            </h3>
                        </div>

                        <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                            Ampliar Imagen
                        </a>

                        <span class="item-folio__caption">
                            <p>Festival de Innovación en las Condes.</p>
                        </span>

                    </div> <!-- end item-folio -->
                </div> <!-- end masonry__brick -->

                <div class="masonry__brick" data-aos="fade-up">
                    <div class="item-folio">

                        <div class="item-folio__thumb">
                            <a href="/images/portfolio/gallery/taller-rey-lennon.jpg" class="thumb-link" title="Shutterbug" data-size="1050x500">
                                <img src="/images/portfolio/taller-rey-lennon.jpg" srcset="/images/portfolio/taller-rey-lennon.jpg" alt="">
                            </a>
                        </div>

                        <div class="item-folio__text">
                            <h3 class="item-folio__title">
                                Rey Lennon
                            </h3>
                        </div>

                        <a href="https://www.behance.net/" class="item-folio__project-link" title="Project link">
                            Ampliar Imagen
                        </a>

                        <span class="item-folio__caption">
                            <p>Nuevas metodologías para el desarrollo de estratégias comunicacionales corporativas.</p>
                        </span>

                    </div> <!-- end item-folio -->
                </div> <!-- end masonry__brick -->

            </div> <!-- end masonry -->
        </div> <!-- end masonry-wrap -->

    </section> <!-- end s-works -->
    <!-- Clientes
    ================================================== -->
    <section id='clientes' class="clientes">
        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead"><?php echo $lang["clientes"]; ?></h3>
            </div>
        </div> <!-- end section-header -->
        <div class="row section-header " data-aos="fade-up">
            <div class="col-block">
                <img src="/images/clientes/c1.png" class="logo">
                <img src="/images/clientes/c2.png" class="logo">
                <img src="/images/clientes/c3.png" class="logo">
                <img src="/images/clientes/c4.png" class="logo">
                <img src="/images/clientes/c5.png" class="logo">
                <img src="/images/clientes/c6.png" class="logo">
                <img src="/images/clientes/c7.png" class="logo">
                <img src="/images/clientes/c8.png" class="logo">
                <img src="/images/clientes/c9.png" class="logo">
                <img src="/images/clientes/c10.png" class="logo">
                <img src="/images/clientes/c11.png" class="logo">
                <img src="/images/clientes/c12.png" class="logo">
                <img src="/images/clientes/c13.png" class="logo">
            </div>
        </div> <!-- end section-header -->
    </section>
    <!-- stats
    ================================================== -->
    <section id='stats' class="s-stats">

        <div class="row stats block-1-4 block-m-1-2 block-mob-full" data-aos="fade-up">

            <div class="col-block stats__col ">
                <small><?php echo $lang["morethan"]; ?></small>
                <div class="stats__count">15229</div>
                <h5><?php echo $lang["enviados"]; ?></h5>
            </div>
            <div class="col-block stats__col">
                <small><?php echo $lang["morethan"]; ?></small>
                <div class="stats__count">2507</div>
                <h5><?php echo $lang["check"]; ?></h5>
            </div>
            <div class="col-block stats__col">
                <small><?php echo $lang["morethan"]; ?></small>
                <div class="stats__count">2800</div>
                <h5><?php echo $lang["usuarios"]; ?></h5>
            </div>
            <div class="col-block stats__col">
                <p>&nbsp;</p>
                <img src="/images/logo.png">
            </div> <!-- end contact-main -->

        </div> <!-- end row -->

        </div> <!-- end stats -->

    </section> <!-- end s-stats -->


    <!-- contact
    ================================================== -->
    <section id='contact' class="s-contact">

        <div class="row section-header">
            <div class="col-full">
                <h3 class="subhead subhead--light"><?php echo $lang["contacto"]; ?></h3>
                <h1 class="display-1 display-1--light"><?php echo $lang["bcontacto"]; ?></h1>
            </div>
        </div> <!-- end section-header -->
        <div class="row">
            <div class="col-full contact-main">
                <p>
                    <a href="mailto:contact@venoot.com" class="contact-email">contact@venoot.com</a>
                </p>
            </div> <!-- end contact-main -->

        </div> <!-- end row -->

        <div class="row">

            <div class="col-five tab-full contact-secondary">
                <h3 class="subhead subhead--light"><?php echo $lang["contacto"]; ?></h3>

                <!--<ul class="contact-social">
                    <li>
                        <a href="#0"><i class="fab fa-linkedin"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-instagram"></i></a>
                    </li>

                </ul> end contact-social -->

                <div class="contact-subscribe">
                    <form class="group mc-form" action="process-email.php" method="POST">
                        <input type="text" value="" name="nombre-full" class="name" id="name" placeholder="<?php echo $lang["nombre"]; ?>" pattern="[A-Za-z]{3,100}" required oninput="check_text(this);">
                        <input type="email" value="" name="email-full" class="email" id="mc-email" placeholder="<?php echo $lang["email"]; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required oninput="check_text(this);">
                        <label for="mc-email" class="subscribe-message"></label>
                        <br />
                        <button type="submit" name="subscribe" class="contact-secondary"><?php echo $lang["contactosend"]; ?></button>
                    </form>
                    <script type="text/javascript">
                        function check_text(input) {
                            if (input.validity.patternMismatch) {
                                input.setCustomValidity("<?php echo $lang["valido"]; ?>");
                            } else {
                                input.setCustomValidity("");
                            }
                        }
                    </script>
                </div> <!-- end contact-subscribe -->
            </div> <!-- end contact-secondary -->

        </div> <!-- end row -->

        <div class="row">
            <div class="col-full cl-copyright">
                <span>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;<script>
                        document.write(new Date().getFullYear());
                    </script> <?php echo $lang["derechos"]; ?>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></span>
            </div>
        </div>

        <div class="cl-go-top">
            <a class="smoothscroll" title="Volver arriba" href="#top"><i class="icon-arrow-up" aria-hidden="true"></i></a>
        </div>

    </section> <!-- end s-contact -->


    <!-- photoswipe background
    ================================================== -->
    <div aria-hidden="true" class="pswp" role="dialog" tabindex="-1">

        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">

            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button> <button class="pswp__button pswp__button--share" title="Share"></button> <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button> <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button> <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>

        </div>

    </div> <!-- end photoSwipe background -->


    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader">
        </div>
    </div>


    <!-- Java Script
    ================================================== -->
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/main_static.js"></script>

</body>

</html>
