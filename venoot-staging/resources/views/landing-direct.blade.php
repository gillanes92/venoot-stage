<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">

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
            background-image: linear-gradient(#0003, #0003),
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
            -webkit-text-fill-color: #FFF;
            -webkit-box-shadow: inherit;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        @if ($event->id == 140 || $event->id == 148)
                            primary: '#FF0032',
                            secondary: '#3B4A5C',
                            terciary: '#3B4A5C',
                        @elseif ($event->id == 137)
                            primary: '#ef0b50',
                            secondary: '#ef0b50',
                            terciary: '#3B4A5C',
                        @else
                            primary: '#1576BB',
                            secondary: '#0284C7',
                            terciary: '#073653',
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
    </style>
</head>

<body class="is-preload">
    <div class="text-white">

        {{-- STATIC TOP BAR --}}
        <div class="top-bar fixed bg-transparent transition-colors duration-500 ease-in-out w-full h-20 px-4 sm:px-6 md:px-12 lg:px-22 xl:px-28 flex flex-row z-20 justify-between ">
            <div class="my-2"><img class="max-h-16 object-contain" src="{{ Storage::url($event->logo_event) }}"></div>
            <div class="inline-flex flex-row gap-10 my-auto uppercase">
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
                    <div id="menu" class="w-40 absolute bg-terciary flex flex-col gap-6 right-2 transition-transform duration-500 origin-top scale-y-0 py-2 px-4 border-y border-secondary">
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
            <div class="flex flex-col xl:flex-row justify-center items-center gap-32 min-h-screen pt-20 xl:pt-0 pb-10">

                <div class="grow max-w-xl px-6 pt-6">
                    {{-- <div> 
                        <img class="max-h-20 md:max-h-24 lg:max-h-28 xl:max-h-36 mx-auto md:mx-0 object-contain" src="{{ Storage::url($event->logo_event) }}"></div> --}}
                    <div
                        class="mt-8 title text-3xl sm:text-4xl md:text-5xl text-center md:text-left mb-4 inline-flex flex-col gap-y-1">
                        <div>{{ $event->name }}</div>
                        @if ($event->id == 140)
                            <div class="flex flex-row flex-wrap">
                                <div class="flex flex-initial basis-full basis-1/2 p-1">
                                    <img
                                        src="{{ Storage::url('ZyeOzGP78YqvnFLfImP90aM545IsOhe7AmAYJnxU.png') }}"
                                    />
                                    <img
                                        src="{{ Storage::url('N3RQZD0Z6zWHJKIwys7xVM3FPj6NwbRIo9QgX2Vm.png') }}"
                                    />
                                </div>
                            </div>
                        @else
                        <div class="text-primary">{{ $event->landing->highlight }}</div>
                        @endif
                        <div>{{ $event->landing->second_title }}</div>
                    </div>
                    <div class="text-xl sm:text-2xl md:text-3xl text-center flex justify-center items-center"><span>{{
                            $event->start_date->format('d') }} de {{ $event->id == 138 ? $event->start_date->locale('en')->monthName : $event->start_date->locale('es')->monthName }} de
                            {{
                            $event->start_date->format('Y') }} {{ $event->start_time->format('H:i') }} HRS
                            @if ($event->id == 140)
                            (Hora de Chile)
                            @endif
                            </span><a
                            href="{{ $link->google() }}" class="ml-6"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-primary hover:text-secondary mb-1.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg></a><span class="text-sm text-primary">AGENDAR</span></span>
                    </div>
                </div>

                <div class="grow max-w-xl w-full px-6 mt-16">
                    @if ($event->estimate->confirmation_form)
                    <form id="confirmation-form" method="post" action="/api/participants/confirmFromForm"
                        class="bg-terciary/30 rounded-lg px-8 py-8 flex flex-col justify-center w-full gap-4">
                        <div class="text-3xl font-semibold text-primary text-center mb-4">
                            @if ($event->id == 137)
                            Ingresa
                            @elseif ($event->id == 138)
                            Join
                            @elseif($event->id == 140)
                            Regístrese
                            @else
                            Regístrate
                            @endif
                        </div>
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        @foreach($event->profile->database->fields as $field)
                        @if ($field['in_form'])
                        <div>

                            @if ($field['type'] == 'string')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                                @if ($field['key'] == 'rut')
                                <input
                                    class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60 rut"
                                    type="text" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control" data-parsley-pattern="[0-9]{1,10}-[K|k|0-9]" data-parsley-rut {{
                                    $field['required'] ? 'required' : '' }}>
                                @else
                                <input
                                    class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                    type="text" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control" {{
                                    $field['required'] ? 'required' : '' }}>
                                @endif
                            @elseif ($field['type'] == 'email')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                            <input
                                class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                type="email" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                class="form-control email" data-parsley-type="email" {{ $field['required'] ? 'required'
                                : '' }}>

                            @elseif ($field['type'] == 'number')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                            <input
                                class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                type="number" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control"
                                {{ $field['required'] ? 'required' : '' }}>

                            @elseif ($field['type'] == 'boolean')
                            <div class="flex flex-row gap-8">
                                <label class="block text-sm text-gray-200 grow">{{ $field['name'] }}</label>
                                <input
                                    class="w-full bg-transparent h-12 w-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                    type="checkbox" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control"
                                    {{ $field['required'] ? 'required' : '' }}>
                            </div>
                            @elseif ($field['type'] == 'date')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                            <input
                                class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                type="date" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control" {{
                                $field['required'] ? 'required' : '' }}>

                            @elseif ($field['type'] == 'datetime')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                            <input
                                class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                type="datetime-local" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                class="form-contro" {{ $field['required'] ? 'required' : '' }}>

                            @elseif ($field['type'] == 'image')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                            <input
                                class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="filepond-image">

                            @elseif ($field['type'] == 'pdf')
                            <label class="block text-sm text-gray-200">{{ $field['name'] }}</label>
                            <input
                                class="w-full bg-transparent h-12 outline-0 border-b border-gray-200/30 focus:border-blue-400/60"
                                type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="filepond-pdf">

                            @elseif ($field['type'] == 'choice')
                            <div class="flex flex-col">
                                <label class="block text-sm text-gray-200 mb-1">{{ $field['name'] }}</label>
                                <select id="{{ $field['key'] }}" name="{{ $field['key'] }}" class="form-control text-terciary overflow-hidden" {{
                                    $field['required'] ? 'required' : '' }}>
                                    @foreach ($field['choices'] as $choice)
                                    <option class="text-terciary">{{ $choice }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        @endif
                        @endforeach

                        <div class="mt-6">
                            <button type="submit"
                                class="text-lg font-semibold uppercase bg-secondary py-3 w-full rounded">
                                @if ($event->id == 137)
                                Ingresa
                                @elseif ($event->id == 138)
                                Join
                                @elseif($event->id == 140)
                                Regístrese
                                @else
                                Regístrate
                                @endif
                            </button>
                        </div>
                    </form>
                    @endif

                    {{-- SHOPPING CART --}}
                    @if ($event->estimate->ticket_sale)
                    <div id="venoot-shopping-cart" data-id={{ $event->id }} data-env={{ $env }}></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- WHERE WHEN --}}
        <div class="flex text-black">
            <div class="inline-flex flex-col md:flex-row justify-evenly items-center gap-y-6 py-28 w-full">
                <div class="inline-flex flex-row items-center gap-2 w-60 md:justify-center">
                    <div><i class="text-6xl text-primary lni lni-calendar"></i></div>
                    <div>
                        <div class="text-xl uppercase font-medium">{{ $event->landing->when_title }}</div>
                        <div class="text-base text-terciary">{{ $event->start_date->day }}{{ $event->start_date->day !=
                            $event->end_date->day ? '-'.$event->end_date->day : '' }} {{ $event->id == 138 ?
                            $event->start_date->locale('en')->monthName : $event->start_date->locale('es')->monthName }} {{ $event->start_date->year }}</div>
                    </div>
                </div>
                <div class="inline-flex flex-row items-center gap-2 w-60 md:justify-center">
                    <div><i class="text-6xl text-primary lni lni-timer"></i></div>
                    <div>
                        <div class="text-xl uppercase font-medium">{{ $event->landing->hour_title }}</div>
                        <div class="text-base text-terciary">{{ $event->start_time->format('H:i') }}</div>
                    </div>
                </div>
                <div class="inline-flex flex-row items-center gap-2 w-60 md:justify-center">
                    <div><i class="text-6xl text-primary lni lni-map-marker"></i></div>
                    <div>
                        <div class="text-xl uppercase font-medium">{{ $event->landing->where_title }}</div>
                        <div class="text-base text-terciary">{{ $event->location }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COUNTER --}}
        <div class="bg-terciary">
            <div class="text-2xl font-semibold text-center pt-20 pb-12">
                @if ($event->id == 138)
                Starts in:
                @else
                Comienza en:
                @endif
            </div>
            <div
                class="timer inline-flex flex-col lg:flex-row justify-center items-center gap-x-10 gap-y-6 pb-20 w-full">
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="weeks text-5xl font-semibold">00</div>
                    <div class="text-sm">
                        @if ($event->id == 138)
                        Weeks
                        @else
                        Semanas
                        @endif
                    </div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="days text-5xl font-semibold" data-days>00</div>
                    <div class="text-sm">
                        @if ($event->id == 138)
                        Days
                        @else
                        Días
                        @endif 
                    </div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="text-5xl font-semibold" data-hours>00</div>
                    <div class="text-sm">
                        @if ($event->id == 138)
                        Hours
                        @else
                        Horas
                        @endif 
                    </div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="text-5xl font-semibold" data-minutes>00</div>
                    <div class="text-sm">
                        @if ($event->id == 138)
                        Minutes
                        @else
                        Minutos
                        @endif
                    </div>
                </div>
                <div class="h-32 w-32 bg-primary flex flex-col justify-center items-center w-full">
                    <div class="text-5xl font-semibold" data-seconds>00</div>
                    <div class="text-sm">
                        @if ($event->id == 138)
                        Seconds
                        @else
                        Segundos
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        @if ($event->landing->has_description)
        <div id="about" class="px-4 md:px-10 lg:px-18 xl:px-40 pt-28 pb-10">
            <div class="text-4xl font-bold text-terciary uppercase pt-2.5 pb-5 mini-top-line">{{
                $event->landing->description_title }}</div>
            @if (strlen($event->description) > 400)
            <div class="text-base text-secondary sm:columns-2">
                {!! $event->description !!}
            </div>
            @else
            <div class="text-base text-secondary">
                {!! $event->description !!}
            </div>
            @endif
        </div>
        @endif

        {{-- SPEAKERS --}}
        @if ($event->landing->has_speakers && !$actors->isEmpty())
        <div id="speakers" class="py-28">
            <div class="px-4 md:px-10 lg:px-18 xl:px-40 pb-10">
                @if ($event->landing->show_speaker_title)
                <div class="text-4xl font-bold text-terciary uppercase pt-2.5 pb-5 mini-top-line">{{
                    $event->landing->speakers_title }}</div>
                @endif
            </div>

            @foreach ($event->actors->groupBy('category') as $key => $actors)
            {{-- <div class="">
                <h2>{{ $key }}</h2>
            </div> --}}
            {{-- <div class="flex justify-center"> --}}
                <div class="flex flex-row flex-wrap justify-center">
                    @foreach ($actors as $actor)
                    <div
                        id="{{ $actor->id }}" class="relative flex-initial shrink-0 basis-full sm:basis-1/2 lg:basis-1/3 xl:basis-1/4">
                        <div class="relative">
                            <img class="w-full hover:scale-110 transition-all duration-500 ease-in-out"
                                src="{{ Storage::url($actor->photo) }}" alt="{{ $actor->name }}">
                            <div class="absolute top-[80%] z-10">
                                <div class="bg-terciary px-8 py-2">
                                    <div class="text-primary font-semibold">{{ $actor->name }} {{ $actor->lastname }}</div>
                                    <div class="text-sm">{{ $actor->description }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{--
            </div> --}}
            @endforeach
        </div>
        @endif

        {{-- TODO: SHOP --}}

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
                            <div class="capitalize">{{ $event->id == 138 ? \Carbon\Carbon::parse($key)->locale('en')->monthName : \Carbon\Carbon::parse($key)->locale('es')->monthName }}</div>
                        </div>
                        <div class="flex flex-col grow text-center gap-16">
                            @foreach($activities->sortBy(function ($activity) {
                                return $activity->start_time.'#'. (20 - $activity->order);
                                }) as $activity)
                            <div>
                                <div class="text-base font-semibold mb-1">{{ $activity->name }}</div>
                                @if ($activity->start_time == $activity->end_time)
                                    <div class="text-sm font-semibold mb-1">{{ $activity->start_time }}</div>
                                @else
                                    <div class="text-sm font-semibold mb-1">{{ $activity->start_time }} - {{ $activity->end_time }}</div>
                                @endif
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
        <div id="supporters" class="text-terciary">
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
                            <img
                                src="{{ Storage::url($sponsor->logo) }}"
                            />
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
        <div id="contact" class="bg-terciary inline-flex flex-col-reverse lg:flex-row justify-center items-center w-full py-28 gap-x-20 gap-y-20 px-4 md:px-10 lg:px-18 xl:px-40">
            <div class="max-w-md w-full">
                <div class="w-28 h-28 lg:w-36 lg:h-36 mx-auto"><img src="{{ Storage::url($event->logo_event) }}"></div>
                @if ($event->id == 148)
                <div class="flex flex-row gap-4 justify-center">
                    <a href="https://www.tecnofarma.cl/terminos-y-condiciones/" target="_blank">Terminos y Condiciones</a><span>|</span><a href="https://www.tecnofarma.cl/politica-de-privacidad/" target="_blank">Politica de Privacidad</a>
                </div>
                @endif
                @if ($event->landing->has_ssnn)
                    <div class="inline-flex flex-row gap-4 text-white w-full justify-center text-4xl">
                        @if ($event->twitter)
                        <a href="{{ $event->twitter }}" class="w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"
                                ><i class="lni lni-twitter-filled"></i
                            ></a>
                        @endif
                        @if ($event->facebook)
                        <a href="{{ $event->facebook }}" class="w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"
                            ><i class="lni lni-facebook-filled"></i
                        ></a>
                        @endif
                        @if ($event->instagram)
                        <a href="{{ $event->instagram }}" class="pt-1 text-2xl w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"
                            ><i class="lni lni-instagram"></i
                        ></a>
                        @endif
                        @if ($event->google_plus)
                        <a href="{{ $event->google_plus }}" class="w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"
                            ><i class="lni lni-google-plus-filled"></i
                        ></a>
                        @endif
                        @if ($event->linkedin)
                        <a href="{{ $event->linkedin }}" class="pt-1 text-2xl w-10 h-10 border-2 rounded-full border-primary text-center hover:text-primary/80"
                            ><i class="lni lni-linkedin"></i
                        ></a>
                        @endif
                    </div>
                @endif
            </div>
            @if ($event->landing->has_contact)
            <div class="max-w-md w-full">
                <div class="text-lg font-bold text-white mini-top-line uppercase pt-2 mb-4">{{ $event->landing->contact_title }}</div>
                @if ($event->landing->show_contact_form)
                <div class="form">
                    <div id="errormessage"></div>
                    <form
                        id="contact-form"
                        class="flex flex-col gap-2 bg-gray-200 p-2 rounded"
                        method="post"
                        action="api/events/{{ $event->id }}/contact"
                    >
                        
                        <div>
                            <input
                                type="text"
                                name="name"
                                class="px-2 rounded w-full bg-terciary/20 h-12 outline-0 border border-secondary/30 focus:border-primary/60"
                                id="name"
                                @if ($event->id == 138)
                                placeholder="Name"
                                @else
                                placeholder="Nombre"
                                @endif
                                data-rule="minlen:4"
                                data-msg="Please enter at least 4 chars"
                            />
                            <div class="validation"></div>
                        </div>
                        <div>
                            <input
                                type="email"
                                class="px-2 rounded w-full bg-terciary/20 h-12 outline-0 border border-secondary/30 focus:border-primary/60"
                                name="email"
                                id="email"
                                placeholder="Email"
                                data-rule="email"
                                data-msg="Please enter a valid email"
                            />
                            <div class="validation"></div>
                        </div>
                    
                        <div>
                            <textarea
                                class="px-2 rounded w-full bg-terciary/20 h-12 outline-0 border border-secondary/30 focus:border-primary/60 min-h-[200px] resize-y"
                                name="body"
                                rows="5"
                                data-rule="required"
                                data-msg="Please write something for us"
                                @if ($event->id == 138)
                                placeholder="Message"
                                @else
                                placeholder="Mensaje"
                                @endif
                            ></textarea>
                            <div class="validation"></div>
                        </div>

                        <div class="text-center">
                            <button class="text-lg font-semibold uppercase bg-secondary py-3 w-full rounded" type="submit">
                                @if ($event->id == 138)
                                Send
                                @else
                                Enviar
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
                @endif
                @if ($event->landing->show_email)
                <div class="flex items-center mt-4">
                    <div class="text-xl text-primary"><a href="mailto:{{ $event->email }}">{{ $event->email }}</a></div>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif


        @if ($event->id == 148)
        <div class="w-full flex flex-row bg-terciary text-xs text-white px-2 pb-1">
            <div class="grow"></div>
            <div>XARO14CL0822</div>
        </div>
        @endif
    </div>

    {{-- V 1.2 --}}

    <!-- Scripts -->
    @if ($event->estimate->ticket_sale)
    <script src=/shopping-cart/js/app.js></script>
    @endif

    <!-- Load libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"
        integrity="sha256-pEdn/pJ2tyT37axbEIPkyUUfuG1yXR0+YV+h+jphem4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/i18n/es.js"
        integrity="sha256-dcJkxln8t7jxoFFA4jP3/ru8rFOlKpt478JM/wsMsgU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/timezz@7.0.0/dist/timezz.min.js"></script>

    <script>
        // RUT validation
        window.Parsley.addValidator('rut', {
            validateString: function(value) {
                // Despejar Puntos
                var valor = rut.value.replace('.','');
                // Despejar Guión
                valor = valor.replace('-','');

                // Aislar Cuerpo y Dígito Verificador
                cuerpo = valor.slice(0,-1)
                dv = valor.slice(-1).toUpperCase()

                // Formatear RUN
                rut.value = cuerpo + '-' + dv

                // Si no cumple con el mínimo ej. (n.nnn.nnn)
                if(cuerpo.length < 7) { return false }

                // Calcular Dígito Verificador
                suma = 0
                multiplo = 2

                // Para cada dígito del Cuerpo
                for(i=1;i<=cuerpo.length;i++) {
                
                    // Obtener su Producto con el Múltiplo Correspondiente
                    index = multiplo * valor.charAt(cuerpo.length - i)
                    
                    // Sumar al Contador General
                    suma = suma + index
                    
                    // Consolidar Múltiplo dentro del rango [2,7]
                    if(multiplo < 7) { multiplo = multiplo + 1 } else { multiplo = 2 }
                }

                // Calcular Dígito Verificador en base al Módulo 11
                dvEsperado = 11 - (suma % 11)

                // Casos Especiales (0 y K)
                dv = (dv == 'K')?10:dv
                dv = (dv == 0)?11:dv

                // Validar que el Cuerpo coincide con su Dígito Verificador
                if(dvEsperado != dv) { return false }

                return true
            },
            messages: {
                es: 'Este RUT no es válido.',
            }
        })
        $(function () {
            // Parsley
            $('#confirmation-form').parsley()
            
            // TOP BAR
            if (window.pageYOffset > 20 ){   
                $(".top-bar").addClass("scrolled")
            } else {
                $(".top-bar").removeClass("scrolled")
            }
            $(window).scroll(function() {   
                if (window.pageYOffset > 20 ){   
                    $(".top-bar").addClass("scrolled")
                } else {
                    $(".top-bar").removeClass("scrolled")
                }
            });

            $('#menu-button').click(function() {
                $('#menu').toggleClass('show-menu')
            })


            // Countdown
            timezz(document.querySelector(".timer"), {
                date: "{{ $event->start_date->format('Y-m-d') }} {{ $event->start_time->format('H:i') }}",
                // stopOnZero: true,
                update(event) {
                    document.querySelector(".timer").querySelector(".weeks").innerHTML = Math.floor(event.days / 7);
                    document.querySelector(".timer").querySelector(".days").innerHTML = event.days % 7;
                }
            });

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

            // Contact Form handling
                $('#contact-form').on('submit', function (e) {
                    if (!e.isDefaultPrevented()) {
                        var url = "/api/events/{{ $event->id }}/contact";
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

            // when the form is submitted
            $('#confirmation-form').on('submit', function (e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = "/api/participants/confirmFromForm";

                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize(),
                        success: function (data) {
                            window.location.replace(window.location.origin + "/venoot-chat?uuid=" + data.uuid);
                        }
                    });
                    return false;
                }
            })
        })
    </script>

</body>

</html>