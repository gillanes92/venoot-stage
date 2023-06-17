<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;800;&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet"> --}}
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

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

    @if($event->banner)
    <style>
        .hero {
            @if ($event->id != 151)
            background-image: linear-gradient(#0006, #0006),
            @else
            background-image: linear-gradient(#0004, #0004),
            @endif
            url('{{ Storage::url($event->banner) }}');
            background-size: cover;
        }
    </style>
    @endif

    <style>
        body {

            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        .title {
            font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
            border-bottom-width: 1px;
            -webkit-text-fill-color: #000;
            -webkit-box-shadow: inherit;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        @if ($event->id === 151)
                        primary: '#369EF5',
                        secondary: '#5CB72D',
                        terciary: '#0C273A',
                        @elseif ($event->id == 145)
                        primary: '#F7F700',
                        secondary: '#DA007F',
                        terciary: '#FFFFFF',
                        @elseif ($event->id == 153)
                        primary: '#1576BB',
                        secondary: '#0284C7',
                        terciary: '#073653',
                        @else
                        primary: '#DA2F37',
                        secondary: '#505140',
                        terciary: '#0C0E0B',
                        @endif
                    },
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        .mini-top-line {
            @apply relative before:w-10 before:absolute before:border-t-4 before:border-primary before:top-0 before:left-0;
        }

        .scrolled {
            @apply bg-terciary border-b border-secondary;
        }

        .show-menu {
            @apply scale-y-100;
        }

        .parsley-errors-list {
            @apply text-primary text-xs;
        }
    </style>
</head>

<body class="is-preload overflow-x-hidden">
    <div class="text-white">

        {{-- STATIC TOP BAR --}}
        <div
            class="top-bar fixed bg-transparent transition-colors duration-500 ease-in-out w-full h-20 px-4 sm:px-6 md:px-12 lg:px-22 xl:px-28 flex flex-row z-20 justify-between ">
            <div class="my-2"><img class="max-h-16 object-contain" src="{{ Storage::url($event->logo_event) }}"></div>
            <div class="inline-flex flex-row gap-10 my-auto uppercase text-sm">
                <div class="hidden lg:block"><a href="#home">home</a></div>

                @if ($event->landing->has_description)
                <div class="hidden lg:block"><a href="#about">{{ $event->landing->description_title }}</a></div>
                @endif

                @if ($event->landing->has_speakers && !$actors->isEmpty())
                <div class="hidden lg:block"><a href="#speakers">{{ $event->landing->speakers_title }}</a></div>
                @endif

                @if ($event->landing->has_activities && !$event->activities->isEmpty())
                <div class="hidden lg:block"><a href="#schedule">{{ $event->landing->activities_title }}</a></div>
                @endif

                @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
                <div class="hidden lg:block"><a href="#supporters">{{ $event->landing->sponsors_title }}</a></div>
                @endif

                @if ($event->landing->has_contact)
                <div class="hidden lg:block"><a href="#contact">{{ $event->landing->contact_title }}</a></div>
                @endif

                <div id="menu-button" class="relative block lg:hidden">
                    <i class="lni lni-menu text-xl"></i>
                    <div id="menu"
                        class="w-40 absolute bg-terciary flex flex-col gap-6 right-2 transition-transform duration-500 origin-top scale-y-0 py-2 px-4 border-y border-secondary">
                        <div><a href="#home">home</a></div>

                        @if ($event->landing->has_description)
                        <div><a href="#about">{{ $event->landing->description_title }}</a></div>
                        @endif

                        @if ($event->landing->has_speakers && !$actors->isEmpty())
                        <div><a href="#speakers">{{ $event->landing->speakers_title }}</a></div>
                        @endif

                        @if ($event->landing->has_activities && !$event->activities->isEmpty())
                        <div><a href="#schedule">{{ $event->landing->activities_title }}</a></div>
                        @endif

                        @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
                        <div><a href="#supporters">{{ $event->landing->sponsors_title }}</a></div>
                        @endif

                        @if ($event->landing->has_contact)
                        <div><a href="#contact">{{ $event->landing->contact_title }}</a></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- HERO --}}
        <div id="home" class="hero">
            <div
                class="flex flex-col xl:flex-row justify-between items-center min-w-screen min-h-screen pt-20 xl:pt-0 pb-10">

                <div class="grow px-6 pt-6 max-h-fit">
                    <div class="flex flex-col max-w-xl xl:max-w-3xl mx-auto">
                        <div
                            class="mt-8 title text-3xl sm:text-4xl md:text-5xl text-center xl:text-left inline-flex flex-col gap-y-1">
                            <div class="uppercase font-normal">{{ $event->name }}</div>
                            <div class="text-primary text-5xl lg:text-8xl font-extrabold">{{ $event->landing->highlight
                                }}</div>
                            <div class="uppercase font-normal">{{ $event->landing->second_title }}</div>

                            <div
                                class="uppercase text-sm sm:text-normal md:text-lg flex justify-center xl:justify-start items-center">
                                <span>{{
                                    $event->start_date->format('d') }} de {{ $event->start_date->locale('es')->monthName
                                    }}
                                    de
                                    {{
                                    $event->start_date->format('Y') }} {{ $event->start_time->format('H:i') }}
                                    HRS</span><a target="_blank" href="{{ $link->google() }}" class="ml-6"><svg
                                        xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 hover:text-blue-400 mb-1.5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg></a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative max-w-lg w-full px-6">
                    @if ($event->estimate->confirmation_form)
                    <form id="confirmation-form"
                        class="bg-white rounded-lg px-8 py-12 flex flex-col justify-center w-full gap-4 mt-10 xl:mt-20">
                        @if ($event->id != 150)
                        <div class="text-4xl font-bold text-black">Regístrate</div>
                        @endif
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        @foreach($event->profile->database->fields as $field)
                        @if ($event->id != 150 || ($field['key'] != 'name' && $field['key'] != 'lastname'))
                        @if ($field['in_form'])
                        <div>
                            @if ($field['type'] == 'string')

                            <input data-parsley-whitespace="trim"
                                class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600 w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                type="text" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{ $field['required']
                                ? 'required' : '' }} placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'email')
                            @if ($event->id == 150)
                            <label class="block font-bold text-sm text-gray-600 mb-2 text-center">{{ $field['name'] }}</label>
                            @endif
                            <div class="flex flex-col gap-4">
                                <input id="form-email-{{ $loop->index }}" data-parsley-whitespace="trim"
                                    class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600 w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                    type="email" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{ $field['required']
                                    ? 'required' : '' }} placeholder="{{ $field['name'] }}">

                                @if ($event->id != 150)
                                <input data-parsley-whitespace="trim" data-parsley-equalto="#form-email-{{ $loop->index }}"
                                    data-parsley-equalto-message="Ambos correos deben ser iguales."
                                    class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600 w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                    type="email" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{ $field['required']
                                    ? 'required' : '' }} placeholder="Confirmación {{ $field['name'] }}">
                                @endif
                            </div>

                            @elseif ($field['type'] == 'number')

                            <input
                                class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600  w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                type="number" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{ $field['required']
                                ? 'required' : '' }} placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'boolean')

                            <input
                                class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600  w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                type="checkbox" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{
                                $field['required'] ? 'required' : '' }} placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'date')

                            <input
                                class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600  w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                type="date" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{ $field['required']
                                ? 'required' : '' }} placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'datetime')

                            <input
                                class="placeholder:text-sm placeholder:text-neutral-400 text-neutral-600  w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                type="datetime-local" id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{
                                $field['required'] ? 'required' : '' }} placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'image')
                            <label class="block font-bold text-sm text-gray-600 mb-2 text-center">{{ $field['name'] }}</label>
                            <input class="filepond-image w-full" type="file" id="{{ $field['key'] }}"
                                name="{{ $field['key'] }}" placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'pdf')
                            <label class="block font-bold text-sm text-gray-600 mb-2 text-center">{{ $field['name'] }}</label>
                            <input class="filepond-pdf w-full" type="file" id="{{ $field['key'] }}"
                                name="{{ $field['key'] }}" placeholder="{{ $field['name'] }}">

                            @elseif ($field['type'] == 'choice')
                            <label class="block font-bold text-sm text-gray-600 mb-2 text-center">{{ $field['name'] }}</label>
                            <select
                                class="placeholder:text-sm text-neutral-700 w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                id="{{ $field['key'] }}" name="{{ $field['key'] }}" {{ $field['required'] ? 'required'
                                : '' }}>
                                @foreach ($field['choices'] as $choice)
                                <option>{{ $choice }}</option>
                                @endforeach
                            </select>
                            @endif
                            @endif
                        </div>
                        @endif
                        @endforeach

                        <div class="mt-6">
                            <button type="submit"
                                class="text-lg font-semibold uppercase bg-secondary py-3 w-full rounded-full">
                                @if ($event->id != 150 || $event->id == 2)
                                Regístrate
                                @else
                                Envíar
                                @endif
                            </button>
                        </div>
                    </form>

                    <div id="thanks"
                        class="bg-white rounded-lg px-8 py-8 flex flex-col justify-center items-center gap-6 transition translate-x-[100vw] duration-0 top-0 max-h-0 overflow-hidden">
                        <div class="text-3xl xl:text-4xl font-bold text-black">
                            Registro recibido!
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-primary animate-bounce"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-sm text-neutral-600 max-w-xs text-center">
                            Hemos enviado un correo electrónico con la confirmación. Si no lo recibe dentro de los siguientes
                            minutos por favor revisar su carpeta de SPAM.<br>
                            <br>
                            Te recomendamos agendar el evento!<br>
                            <a id="google-link" target="_blank" href={{ $link_qr->google() }} class="text-primary">Calendario Google</a><br>
                            <a id="ical-link" href={{ $link_qr->ics() }} class="text-primary">Calendario Local (ics)</a>
                        </div>
                    </div>

                    <div id="no-auth"
                        class="bg-white rounded-lg px-8 py-8 flex flex-col justify-center items-center gap-6 transition translate-x-[100vw] duration-0 top-0 max-h-0 overflow-hidden">
                        <div class="text-3xl xl:text-4xl font-bold text-black text-center">
                            Este correo electrónico no está autorizado!
                        </div>
                        <div>
                            <svg aria-hidden="true" class="h-20 w-20 text-primary animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    @endif

                    {{-- SHOPPING CART --}}
                    @if ($event->estimate->ticket_sale)
                    <div id="venoot-shopping-cart" data-id={{ $event->id }} data-env={{ $env }}></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- WHERE WHEN --}}
        @if ($event->id != 145 && $event->id != 150)
        <div class="flex text-black">
            <div class="inline-flex flex-col md:flex-row justify-evenly items-center gap-y-6 py-28 w-full">
                <div class="inline-flex flex-row items-center gap-2 w-60 md:justify-center">
                    <div><i class="text-6xl text-primary lni lni-calendar"></i></div>
                    <div>
                        <div class="text-xl uppercase font-medium">{{ $event->landing->when_title }}</div>
                        <div class="text-base text-terciary">{{ $event->start_date->day }}{{ $event->start_date->day !=
                            $event->end_date->day ? '-'.$event->end_date->day : '' }} {{
                            $event->start_date->locale('es')->monthName }} {{ $event->start_date->year }}</div>
                    </div>
                </div>
                <div class="inline-flex flex-row items-center gap-2 w-60 md:justify-center">
                    <div><i class="text-6xl text-primary lni lni-timer"></i></div>
                    <div>
                        <div class="text-xl uppercase font-medium">{{ $event->landing->hour_title }}</div>
                        <div class="text-base text-terciary">{{ $event->start_time->format('H:i') }}</div>
                    </div>
                </div>
                <div id="address" class="inline-flex flex-row items-center gap-2 w-60 md:justify-center cursor-pointer">
                    <div><i class="text-6xl text-primary lni lni-map-marker"></i></div>
                    <div>
                        <div class="text-xl uppercase font-medium">{{ $event->landing->where_title }}</div>
                        <div class="text-base text-terciary">{{ $event->location }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- COUNTER --}}
        @if ($event->id != 145 && $event->id != 150 && $event->id != 2)
        <div class="bg-terciary">
            <div class="text-2xl font-semibold text-center pt-20 pb-12">Comienza en:</div>
            <div
                class="timer inline-flex flex-col lg:flex-row justify-center items-center gap-x-10 gap-y-6 pb-20 w-full">
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="weeks text-5xl font-semibold">00</div>
                    <div class="text-sm">Semanas</div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="days text-5xl font-semibold" data-days>00</div>
                    <div class="text-sm">Días</div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="text-5xl font-semibold" data-hours>00</div>
                    <div class="text-sm">Horas</div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="text-5xl font-semibold" data-minutes>00</div>
                    <div class="text-sm">Minutos</div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="text-5xl font-semibold" data-seconds>00</div>
                    <div class="text-sm">Segundos</div>
                </div>
            </div>
        </div>
        @endif

        {{-- DESCRIPTION --}}
        @if ($event->landing->has_description)
        <div id="about" class="px-4 md:px-10 lg:px-18 xl:px-40 py-28">
            <div class="text-4xl font-bold text-terciary uppercase pt-2.5 pb-5 mini-top-line">{{
                $event->landing->description_title }}</div>
            <div class="text-base text-terciary sm:columns-2">
                {!! $event->description !!}
            </div>
        </div>
        @endif

        {{-- SPEAKERS --}}
        @if ($event->landing->has_speakers && !$actors->isEmpty())
        <div id="speakers" class="py-28">
            <div class="px-4 md:px-10 lg:px-18 xl:px-40 pb-10">
                @if ($event->landing->show_speaker_title)
                <div class="text-4xl font-bold text-white uppercase pt-2.5 pb-5 mini-top-line">{{
                    $event->landing->speakers_title }}</div>
                @endif
            </div>

            @foreach ($event->actors->groupBy('category') as $key => $actors)
            <div class="flex flex-row flex-wrap justify-center">
                @foreach ($actors as $actor)
                <div
                    class="relative overflow-hidden flex-initial shrink-0 basis-full sm:basis-1/2 lg:basis-1/3 xl:basis-1/4">
                    <div class="relative overflow-hidden">
                        <img class="w-full hover:scale-110 transition-all duration-500 ease-in-out"
                            src="{{ Storage::url($actor->photo) }}" alt="{{ $actor->name }}">
                        <div class="absolute bg-primary px-8 py-2 bottom-7 left-2 z-10">
                            <div class="font-semibold">{{ $actor->name }} {{ $actor->lastname }}</div>
                            <div class="text-sm">{{ $actor->organization }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endif

        {{-- TODO: SHOP --}}

        {{-- SPACE --}}
        @if ($event->landing->has_speakers && !$actors->isEmpty() && $event->landing->has_activities && !$activitiesByDate->isEmpty())
        <div class="mb-28"></div>
        @endif

        {{-- CALENDAR --}}
        @if ($event->landing->has_activities && !$activitiesByDate->isEmpty())
        <div id="schedule" class="px-4 md:px-10 lg:px-18 xl:px-40 pb-28">
            <div class="text-4xl font-bold text-terciary uppercase pt-2.5 pb-10 mini-top-line">
                <i class="text-secondary lni lni-calendar mr-2"></i>{{ $event->landing->activities_title }}
            </div>

            <div class="bg-terciary/20">
                <div class="h-6 bg-primary"></div>

                @foreach($activitiesByDate as $key => $activities)
                <div class="flex flex-col text-terciary">
                    <div class="inline-flex flex-row w-full px-2 sm:px-4 md:px-6 lg:px-8 xl:px-10 py-8">
                        <div class="text-center">
                            <div class="text-3xl font-semibold">{{ \Carbon\Carbon::parse($key)->format('d') }}</div>
                            <div class="capitalize">{{ \Carbon\Carbon::parse($key)->monthName }}</div>
                        </div>
                        <div class="flex flex-col grow text-center gap-16">
                            @foreach($activities->sortBy(function ($activity) {
                            return $activity->start_time.'#'. (20 - $activity->order);
                            }) as $activity)
                            <div>
                                <div class="text-base font-semibold mb-1">{{ $activity->name }}</div>
                                <div class="text-sm font-semibold mb-1">{{ $activity->start_time }} - {{
                                    $activity->end_time }}</div>
                                <div class="inline-flex flex-col md:flex-row text-base text-primary gap-x-6 gap-y-1">
                                    @if (!$activity->actors->isEmpty())
                                    @foreach ($activity->actors as $actor)
                                    <div>{{ $actor->name }} {{ $actor->lastname }}</div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- MARCAS --}}
        @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
        <div id="supporters" class="bg-terciary">
            <div class="px-4 md:px-10 lg:px-18 xl:px-40 py-28">
                @if ($event->landing->show_sponsor_title)
                <div class="text-4xl font-bold uppercase pt-2.5 pb-10 mini-top-line">
                    {{ $event->landing->sponsors_title }}
                </div>
                @endif


                @foreach ($event->sponsors->groupBy('category') as $key => $sponsors)
                <div class="text-2xl font-bold uppercase pt-2.5 pb-10 mini-top-line">
                    {{ $key }}
                </div>

                <div class="flex flex-row flex-wrap justify-center">
                    @foreach($sponsors as $sponsor)
                    <div class="flex justify-center flex-initial basis-full sm:basis-1/2 lg:basis-1/3 xl:basis-1/4 p-1 ">
                        @if ($sponsor->url)
                        <a href="{{ $sponsor->url }}" target="_blank">
                            @endif
                            <img src="{{ Storage::url($sponsor->logo) }}" />
                            @if ($sponsor->url)
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CONTACT --}}
        @if ($event->landing->has_ssnn || $event->landing->has_contact)
        <div id="contact"
            class="inline-flex flex-col-reverse lg:flex-row justify-center items-center w-full py-28 gap-x-20 gap-y-20 px-4 md:px-10 lg:px-18 xl:px-40">
            <div class="max-w-md w-full">
                <div class="w-28 h-28 lg:w-36 lg:h-36 mx-auto"><img src="{{ Storage::url($event->logo_event) }}"></div>
                @if ($event->landing->has_ssnn)
                <div class="inline-flex flex-row gap-4 text-primary w-full justify-center text-4xl">
                    @if ($event->twitter)
                    <a href="{{ $event->twitter }}"
                        class="w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"><i
                            class="lni lni-twitter-filled"></i></a>
                    @endif
                    @if ($event->facebook)
                    <a href="{{ $event->facebook }}"
                        class="w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"><i
                            class="lni lni-facebook-filled"></i></a>
                    @endif
                    @if ($event->instagram)
                    <a href="{{ $event->instagram }}"
                        class="pt-1 text-2xl w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"><i
                            class="lni lni-instagram"></i></a>
                    @endif
                    @if ($event->google_plus)
                    <a href="{{ $event->google_plus }}"
                        class="w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"><i
                            class="lni lni-google-plus-filled"></i></a>
                    @endif
                    @if ($event->linkedin)
                    <a href="{{ $event->linkedin }}"
                        class="pt-1 text-2xl w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"><i
                            class="lni lni-linkedin"></i></a>
                    @endif
                </div>
                @endif
            </div>
            @if ($event->landing->has_contact)
            <div class="flex flex-col gap-y-10 max-w-md w-full">
                <div class="text-lg font-bold text-terciary mini-top-line uppercase pt-2 ">{{
                    $event->landing->contact_title }}
                </div>
                @if ($event->landing->show_email)
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="text-xl text-primary"><a href="mailto:{{ $event->email }}">{{ $event->email }}</a></div>
                </div>
                @endif
                @if ($event->landing->show_contact_form)
                <div class="form">
                    <div id="errormessage"></div>
                    <form id="contact-form" class="flex flex-col gap-2" method="post"
                        action="api/events/{{ $event->id }}/contact">

                        <div>
                            <input type="text" name="name"
                                class="px-2 rounded w-full bg-terciary/20 h-12 outline-0 border border-secondary/30 focus:border-primary/60"
                                id="name" placeholder="Nombre" data-rule="minlen:4"
                                data-msg="Please enter at least 4 chars" />
                            <div class="validation"></div>
                        </div>
                        <div>
                            <input type="email"
                                class="px-2 rounded w-full bg-terciary/20 h-12 outline-0 border border-secondary/30 focus:border-primary/60"
                                name="email" id="email" placeholder="Email" data-rule="email"
                                data-msg="Please enter a valid email" />
                            <div class="validation"></div>
                        </div>

                        <div>
                            <textarea
                                class="px-2 rounded w-full bg-terciary/20 h-12 outline-0 border border-secondary/30 focus:border-primary/60 min-h-[200px] resize-y"
                                name="body" rows="5" data-rule="required" data-msg="Please write something for us"
                                placeholder="Mensaje"></textarea>
                            <div class="validation"></div>
                        </div>

                        <div class="text-center">
                            <button class="text-lg font-semibold uppercase bg-secondary py-3 w-full rounded"
                                type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/timezz@7.0.0/dist/timezz.min.js"></script>

    <script>
        $(function () {
            // TOP BAR
            if (window.pageYOffset > 20) {
                $(".top-bar").addClass("scrolled")
            } else {
                $(".top-bar").removeClass("scrolled")
            }
            $(window).scroll(function () {
                if (window.pageYOffset > 20) {
                    $(".top-bar").addClass("scrolled")
                } else {
                    $(".top-bar").removeClass("scrolled")
                }
            });

            $('#menu-button').click(function () {
                $('#menu').toggleClass('show-menu')
            })

            // Countdown
            if (document.querySelector('.timer') !== null) {
                timezz(document.querySelector(".timer"), {
                    date: "{{ $event->start_date->format('Y-m-d') }} {{ $event->start_time->format('H:i') }}",
                    // stopOnZero: true,
                    update(event) {
                        document.querySelector(".timer").querySelector(".weeks").innerHTML = Math.floor(event.days / 7);
                        document.querySelector(".timer").querySelector(".days").innerHTML = event.days % 7;
                    }
                });
            }

            // Map Link
            $('#address').click(() => {
                const userAgent = navigator.userAgent
                const address = '{{ $event->location }}'.trim().replace(/\s\s+/g, " ")
                if(/iPad|iPhone|iPod/i.test(userAgent)) {
                    window.open('http://maps.apple.com/?q=' + address);
                } else {
                    window.open('https://maps.google.com/maps?q=' + address);
                }
            })

            // Get user if it exists
            let qr_verificado = false
            const queryString = window.location.search
            const urlParams = new URLSearchParams(queryString)
            const qr = urlParams.get('qr')

            if (qr) {
                $('#video-secure-button').attr('href', window.location.protocol + '//' + window.location.host + '/venoot-chat?uuid=' + qr)

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
                    success: function (data) {
                        qr_verificado = true
                        for (let [key, value] of Object.entries(data.participant)) {
                            $("#" + key).attr("value", value)
                        }

                    }
                });
            }

            //FilePond
            FilePond.registerPlugin(FilePondPluginFileValidateType)
            const imageElements = document.querySelectorAll("input.filepond-image")
            imageElements.forEach((imageElement) => {
                const pond = FilePond.create(imageElement, {
                    acceptedFileTypes: ['image/*'],
                });
                pond.setOptions({
                    labelIdle: 'Arrastra y suelta tus archivos o <span class = "filepond--label-action"> Examinar <span>',
                    labelInvalidField: "El campo contiene archivos inválidos",
                    labelFileWaitingForSize: "Esperando tamaño",
                    labelFileSizeNotAvailable: "Tamaño no disponible",
                    labelFileLoading: "Cargando",
                    labelFileLoadError: "Error durante la carga",
                    labelFileProcessing: "Cargando",
                    labelFileProcessingComplete: "Carga completa",
                    labelFileProcessingAborted: "Carga cancelada",
                    labelFileProcessingError: "Error durante la carga",
                    labelFileProcessingRevertError: "Error durante la reversión",
                    labelFileRemoveError: "Error durante la eliminación",
                    labelTapToCancel: "toca para cancelar",
                    labelTapToRetry: "tocar para volver a intentar",
                    labelTapToUndo: "tocar para deshacer",
                    labelButtonRemoveItem: "Eliminar",
                    labelButtonAbortItemLoad: "Abortar",
                    labelButtonRetryItemLoad: "Reintentar",
                    labelButtonAbortItemProcessing: "Cancelar",
                    labelButtonUndoItemProcessing: "Deshacer",
                    labelButtonRetryItemProcessing: "Reintentar",
                    labelButtonProcessItem: "Cargar",
                    labelMaxFileSizeExceeded: "El archivo es demasiado grande",
                    labelMaxFileSize: "El tamaño máximo del archivo es {filesize}",
                    labelMaxTotalFileSizeExceeded: "Tamaño total máximo excedido",
                    labelMaxTotalFileSize: "El tamaño total máximo del archivo es {filesize}",
                    labelFileTypeNotAllowed: "Archivo de tipo no válido",
                    fileValidateTypeLabelExpectedTypes: "Espera {allButLastType} o {lastType}",
                    imageValidateSizeLabelFormatError: "Tipo de imagen no compatible",
                    imageValidateSizeLabelImageSizeTooSmall: "La imagen es demasiado pequeña",
                    imageValidateSizeLabelImageSizeTooBig: "La imagen es demasiado grande",
                    imageValidateSizeLabelExpectedMinSize: "El tamaño mínimo es {minWidth} × {minHeight}",
                    imageValidateSizeLabelExpectedMaxSize: "El tamaño máximo es {maxWidth} × {maxHeight}",
                    imageValidateSizeLabelImageResolutionTooLow: "La resolución es demasiado baja",
                    imageValidateSizeLabelImageResolutionTooHigh: "La resolución es demasiado alta",
                    imageValidateSizeLabelExpectedMinResolution: "La resolución mínima es {minResolution}",
                    imageValidateSizeLabelExpectedMaxResolution: "La resolución máxima es {maxResolution}",
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
                    labelIdle: 'Arrastra y suelta tus archivos o <span class = "filepond--label-action"> Examinar <span>',
                    labelInvalidField: "El campo contiene archivos inválidos",
                    labelFileWaitingForSize: "Esperando tamaño",
                    labelFileSizeNotAvailable: "Tamaño no disponible",
                    labelFileLoading: "Cargando",
                    labelFileLoadError: "Error durante la carga",
                    labelFileProcessing: "Cargando",
                    labelFileProcessingComplete: "Carga completa",
                    labelFileProcessingAborted: "Carga cancelada",
                    labelFileProcessingError: "Error durante la carga",
                    labelFileProcessingRevertError: "Error durante la reversión",
                    labelFileRemoveError: "Error durante la eliminación",
                    labelTapToCancel: "toca para cancelar",
                    labelTapToRetry: "tocar para volver a intentar",
                    labelTapToUndo: "tocar para deshacer",
                    labelButtonRemoveItem: "Eliminar",
                    labelButtonAbortItemLoad: "Abortar",
                    labelButtonRetryItemLoad: "Reintentar",
                    labelButtonAbortItemProcessing: "Cancelar",
                    labelButtonUndoItemProcessing: "Deshacer",
                    labelButtonRetryItemProcessing: "Reintentar",
                    labelButtonProcessItem: "Cargar",
                    labelMaxFileSizeExceeded: "El archivo es demasiado grande",
                    labelMaxFileSize: "El tamaño máximo del archivo es {filesize}",
                    labelMaxTotalFileSizeExceeded: "Tamaño total máximo excedido",
                    labelMaxTotalFileSize: "El tamaño total máximo del archivo es {filesize}",
                    labelFileTypeNotAllowed: "Archivo de tipo no válido",
                    fileValidateTypeLabelExpectedTypes: "Espera {allButLastType} o {lastType}",
                    imageValidateSizeLabelFormatError: "Tipo de imagen no compatible",
                    imageValidateSizeLabelImageSizeTooSmall: "La imagen es demasiado pequeña",
                    imageValidateSizeLabelImageSizeTooBig: "La imagen es demasiado grande",
                    imageValidateSizeLabelExpectedMinSize: "El tamaño mínimo es {minWidth} × {minHeight}",
                    imageValidateSizeLabelExpectedMaxSize: "El tamaño máximo es {maxWidth} × {maxHeight}",
                    imageValidateSizeLabelImageResolutionTooLow: "La resolución es demasiado baja",
                    imageValidateSizeLabelImageResolutionTooHigh: "La resolución es demasiado alta",
                    imageValidateSizeLabelExpectedMinResolution: "La resolución mínima es {minResolution}",
                    imageValidateSizeLabelExpectedMaxResolution: "La resolución máxima es {maxResolution}",
                    server: {
                        process: {
                            url: `${window.location.origin}/api/uploadFormImage`,
                        },
                        load: './public/image/',
                    }
                });
            })

            // Contact Form handling
            $('#contact-form').on('submit', function (e) {
                if (!e.isDefaultPrevented()) {
                    var url = "/api/events/{{ $event->id }}/contact";
                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function (data) {
                            alert("Mensaje Enviado!")
                        }
                    });
                    return false;
                }
            })

            const form = $('#confirmation-form').parsley()

            // when the form is submitted
            var uuid = null;
            $('#confirmation-form').on('submit', function (e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {

                    var url = "/api/participants/confirmFromForm";

                    // Remove box
                    $('#confirmation-form').addClass('transition translate-x-[100vw] duration-1000')

                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function (data) {
                            uuid = data.uuid;

                            $('#confirmation-form')[0].reset();
                            $('#confirmation-form').hide();

                             $('#google-link').attr("href", $('#google-link').attr('href').replace('%21%21replace%21%21', data.uuid));
                             $('#ical-link').attr("href", data.ics);

                            $('#thanks').addClass('transition translate-x-0 duration-1000')
                            $('#thanks').removeClass('max-h-0 overflow-hidden')
                        },

                        error: function (xhr, ajaxOptions, thrownError) {
                            if (xhr.status === 401) { 
                                $('#confirmation-form')[0].reset();
                                $('#confirmation-form').hide();

                                $('#no-auth').addClass('transition translate-x-0 duration-1000')
                                $('#no-auth').removeClass('max-h-0 overflow-hidden')
                            }
                        }
                    });
                    return false;
                }
            })

            $('#copy-link-button').click(function (e) {
                var dummy = document.createElement("textarea");
                document.body.appendChild(dummy);
                dummy.value = "https://venoot-pro.work/venoot-chat?uuid=" + uuid;
                dummy.select();
                document.execCommand("copy");
                document.body.removeChild(dummy);
            })
        })
    </script>

</body>

</html>
