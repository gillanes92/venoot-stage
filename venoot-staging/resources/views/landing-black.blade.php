<!DOCTYPE HTML>
<html>
<!-- BLACK -->

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
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

    @if ($event->banner)
        <style>
            .hero {
                background-image: linear-gradient(#000A, #000A),
                    url('{{ Storage::url($event->banner) }}');
                background-size: cover;
            }
        </style>
    @endif

    <style>
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
            box-shadow: inherit;
            -webkit-text-fill-color: #FFF;
            -webkit-box-shadow: inherit;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    sans: ['Lato', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', "Segoe UI",
                        'Roboto', "Helvetica Neue", 'Arial', "Noto Sans", 'sans-serif', "Apple Color Emoji",
                        "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"
                    ],
                    serif: ['ui-serif', 'Georgia', 'Cambria', "Times New Roman", 'Times', 'serif'],
                    josefin: ['Josefin Sans'],
                },
                extend: {
                    colors: {
                        @if ($event->id == 153)
                            primary: '#1576BB',
                            secondary: '#0284C7',
                            terciary: '#073653',
                        @else
                            primary: '#F50136',
                            secondary: '#F50136',
                            terciary: '#18181C',
                        @endif
                    },
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        .link-top-line {
            @apply relative absolute before:w-4 before:border-t-2 before:border-primary before:top-[-10px] before:left-1/2 before:-ml-2 before:transition before:scale-x-0;
        }

        .link-top-line.active {
            @apply before:scale-x-100;
        }

        .calendar-date.active {
            @apply bg-primary text-white border-0;
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
        <div
            class="top-bar fixed bg-transparent transition-colors duration-500 ease-in-out w-full h-20 px-4 lg:px-64 xl:px-80 flex flex-row z-20 justify-between">
            <div class="my-2"><img class="max-h-16 object-contain" src="{{ Storage::url($event->logo_event) }}">
            </div>
            <div class="inline-flex flex-row gap-10 my-auto uppercase text-sm">
                <div class="hidden lg:block"><a class="link-top-line active" href="#home">home</a></div>

                @if ($event->landing->has_description)
                    <div class="hidden lg:block"><a class="link-top-line"
                            href="#about">{{ $event->landing->description_title }}</a></div>
                @endif

                @if ($event->landing->has_speakers && !$actors->isEmpty())
                    <div class="hidden lg:block"><a class="link-top-line"
                            href="#speakers">{{ $event->landing->speakers_title }}</a></div>
                @endif

                @if ($event->landing->has_activities && !$event->activities->isEmpty())
                    <div class="hidden lg:block"><a class="link-top-line"
                            href="#schedule">{{ $event->landing->activities_title }}</a></div>
                @endif

                @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
                    <div class="hidden lg:block"><a class="link-top-line"
                            href="#supporters">{{ $event->landing->sponsors_title }}</a></div>
                @endif

                @if ($event->landing->has_contact)
                    <div class="hidden lg:block"><a class="link-top-line"
                            href="#contact">{{ $event->landing->contact_title }}</a></div>
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

                <div class="grow px-6 pt-6">
                    <div class="flex flex-col max-w-xl xl:max-w-6xl mx-auto">
                        <div
                            class="mt-8 title text-3xl sm:text-4xl md:text-5xl text-center xl:text-left mb-10 inline-flex flex-col gap-x-4 gap-y-1">
                            <div class="text-xl">{{ $event->landing->second_title }}</div>
                            <div class="uppercase font-bold">{{ $event->name }}</div>
                            <div class="text-primary text-2xl font-extrabold">{{ $event->landing->highlight }}</div>


                            <a href="{{ $link->google() }}"
                                class="uppercase text-sm flex justify-center xl:justify-start items-center bg-primary max-w-fit py-3 px-4 rounded mt-6">
                                <div>agregar al calendario</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="max-w-lg w-full px-6 mt-10">
                    @if ($event->estimate->confirmation_form)
                        <form id="confirmation-form" method="post" action="/api/participants/confirmFromForm"
                            class="bg-white rounded-lg px-8 py-12 flex flex-col justify-center w-full gap-8">
                            <div class="text-4xl font-josefin font-bold text-black">Regístrate</div>
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            @foreach ($event->profile->database->fields as $field)
                                @if ($event->id != 150 || !in_array($field['key'], ['name', 'lastname']))
                                @if ($field['in_form'])
                                    <div>
                                        @if ($field['type'] == 'string')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="text" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="form-control" {{ $field['required'] ? 'required' : '' }}
                                                placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'email')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="email" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="form-control email" data-parsley-type="email"
                                                {{ $field['required'] ? 'required' : '' }}
                                                placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'number')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="number" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="form-control" {{ $field['required'] ? 'required' : '' }}
                                                placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'boolean')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="checkbox" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="form-control" {{ $field['required'] ? 'required' : '' }}
                                                placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'date')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="date" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="form-control" {{ $field['required'] ? 'required' : '' }}
                                                placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'datetime')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="datetime-local" id="{{ $field['key'] }}"
                                                name="{{ $field['key'] }}" class="form-contro"
                                                {{ $field['required'] ? 'required' : '' }}
                                                placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'image')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="filepond-image" placeholder="{{ $field['name'] }}">
                                        @elseif ($field['type'] == 'pdf')
                                            <input
                                                class="w-full bg-gray-200 h-12 outline-0 border border-transparent px-4 focus:border-secondary"
                                                type="file" id="{{ $field['key'] }}" name="{{ $field['key'] }}"
                                                class="filepond-pdf" placeholder="{{ $field['name'] }}">
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
                                @endif
                            @endforeach

                            <div class="mt-6">
                                <button type="submit"
                                    class="text-lg font-semibold uppercase bg-secondary py-3 w-full">
                                    Regístrate
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- SHOPPING CART --}}
                    @if ($event->estimate->ticket_sale)
                        <div id="venoot-shopping-cart" data-id={{ $event->id }} data-env={{ $env }}>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- WHERE WHEN --}}
        <div class="flex text-white bg-primary">
            <div class="inline-flex flex-col md:flex-row justify-evenly items-center gap-x-20 gap-y-6 py-28 mx-auto">
                <div class="inline-flex flex-col items-center gap-2 w-60 md:justify-center">
                    <div class="text-sm uppercase font-bold">{{ $event->landing->where_title }}</div>
                    <div><i class="text-6xl lni lni-map"></i></div>
                    <div class="text-base">{{ $event->location }}</div>
                </div>

                <div class="inline-flex flex-col items-center gap-2 w-60 md:justify-center">
                    <div class="text-sm uppercase font-bold">{{ $event->landing->when_title }}</div>
                    <div><i class="text-6xl lni lni-calendar"></i></div>
                    <div class="text-base">
                        {{ $event->start_date->day }}{{ $event->start_date->day != $event->end_date->day ? '-' . $event->end_date->day : '' }}
                        {{ $event->start_date->locale('es')->monthName }} {{ $event->start_date->year }}</div>
                </div>

                <div class="inline-flex flex-col items-center gap-2 w-60 md:justify-center">
                    <div class="text-sm uppercase font-bold">{{ $event->landing->hour_title }}</div>
                    <div><i class="text-6xl lni lni-timer"></i></div>
                    <div class="text-base">{{ $event->start_time->format('H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        {{-- @if ($event->landing->has_description)
        <div id="about" class="px-4 md:px-10 lg:px-18 xl:px-40 py-28">
            <div class="text-4xl font-bold text-terciary uppercase pt-2.5 pb-5 mini-top-line">{{
                $event->landing->description_title }}</div>
            <div class="text-base text-secondary sm:columns-2">
                {!! $event->description !!}
            </div>
        </div>
        @endif --}}

        {{-- CALENDAR --}}
        @if ($event->landing->has_activities && !$activitiesByDate->isEmpty())
            <div id="schedule"
                class="flex flex-col px-4 md:px-10 lg:px-18 xl:px-96 pt-28 justify-center overflow-hidden">
                <div
                    class="inline-flex flex-col text-4xl font-josefin font-bold text-terciary uppercase items-center gap-6">
                    <div>{{ $event->landing->activities_title }}</div>
                    <div>
                        <div class="border-b border-primary w-12 mb-1 -ml-2"></div>
                        <div class="border-b border-primary w-12 ml-2"></div>
                    </div>
                </div>

                <div class="flex flex-row gap-10 w-full">
                    <div class="flex flex-col text-terciary basis-64">
                        @foreach ($activitiesByDate as $key => $activities)
                            <div id="date-{{ $loop->iteration }}"
                                class="calendar-date cursor-pointer p-5 border-b border-terciary/10 {{ $loop->first ? 'active' : '' }}">
                                <div class="text-base font-semibold uppercase mb-2">
                                    {{ \Carbon\Carbon::parse($key)->dayName }}
                                </div>
                                <div class="text-sm capitalize">{{ \Carbon\Carbon::parse($key)->format('d') }}
                                    {{ \Carbon\Carbon::parse($key)->monthName }}</div>
                            </div>
                        @endforeach
                    </div>

                    @foreach ($activitiesByDate as $key => $activities)
                        <div
                            class="flex flex-col grow shadow-lg calendar-details date-{{ $loop->iteration }} {{ $loop->first ? '' : 'hidden' }}">
                            @foreach ($activities->sortBy(function ($activity) {
        return $activity->start_time . '#' . (20 - $activity->order);
    }) as $activity)
                                <div id="calendar-details-{{ $loop->iteration }}"
                                    class="grow calendar-data-date flex flex-col px-5 py-3 border-b border-terciary/10">
                                    <div class="text-sm mb-1 text-neutral-400">{{ $activity->start_time }} -
                                        {{ $activity->end_time }}</div>
                                    <div class="font-josefin text-lg text-terciary font-bold mb-2">
                                        {{ $activity->name }}</div>
                                    <div
                                        class="inline-flex flex-col md:flex-row text-sm text-neutral-400 gap-x-4 gap-y-1">
                                        @if (!$activity->actors->isEmpty())
                                            @foreach ($activity->actors as $actor)
                                                <div>{{ $actor->name }} {{ $actor->lastname }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div
                                    class="flex flex-col calendar-data calendar-details-{{ $loop->iteration }} transition {{ $loop->first ? 'border-b border-terciary/10 scale-y-100 px-28 py-4' : 'scale-y-0 p-0 h-0' }} bg-neutral-100 text-sm">
                                    <div class="text-neutral-600">{!! $activity->description !!}</div>
                                    <div class="text-neutral-400"><span class="text-primary">Ubicacion:
                                        </span>{{ $activity->location }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- SPEAKERS --}}
        @if ($event->landing->has_speakers && !$actors->isEmpty())
            <div id="speakers"
                class="flex flex-col px-4 md:px-10 lg:px-18 xl:px-96 py-28 justify-center overflow-hidden">
                <div
                    class="inline-flex flex-col text-4xl font-josefin font-bold text-terciary uppercase items-center gap-6 mb-10">
                    <div>{{ $event->landing->speakers_title }}</div>
                    <div>
                        <div class="border-b border-primary w-12 mb-1 -ml-2"></div>
                        <div class="border-b border-primary w-12 ml-2"></div>
                    </div>
                </div>

                @foreach ($event->actors->groupBy('category') as $key => $actors)
                    <div class="flex flex-row flex-wrap justify-center">
                        @foreach ($actors as $actor)
                            <div
                                class="relative overflow-hidden flex-initial shrink-0 basis-full sm:basis-1/2 xl:basis-1/3">
                                <div class="relative overflow-hidden p-2 border border-neutral-200 shadow rounded">
                                    <img class="w-full" src="{{ Storage::url($actor->photo) }}"
                                        alt="{{ $actor->name }}">
                                    <div class="absolute bg-neutral-200 px-8 py-2 bottom-2 z-10 text-terciary">
                                        <div class="uppercase font-semibold">{{ $actor->name }}
                                            {{ $actor->lastname }}</div>
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

        {{-- MARCAS --}}
        @if ($event->landing->has_sponsors && !$event->sponsors->isEmpty())
            <div id="supporters"
                class="flex flex-col px-4 md:px-10 lg:px-18 xl:px-96 py-28 justify-center overflow-hidden">
                @if ($event->landing->show_sponsor_title)
                    <div
                        class="inline-flex flex-col text-4xl font-josefin font-bold text-terciary uppercase items-center gap-6 mb-10">
                        <div>{{ $event->landing->sponsors_title }}</div>
                        <div>
                            <div class="border-b border-primary w-12 mb-1 -ml-2"></div>
                            <div class="border-b border-primary w-12 ml-2"></div>
                        </div>
                    </div>
                @endif


                @foreach ($event->sponsors->groupBy('category') as $key => $sponsors)
                    <div
                        class="inline-flex flex-col text-2xl font-bold uppercase pt-2.5 pb-10 text-terciary items-center">
                        <div>{{ $key }}</div>
                        <div>
                            <div class="border-b border-primary w-12 mb-1 -ml-2"></div>
                            <div class="border-b border-primary w-12 ml-2"></div>
                        </div>
                    </div>

                    <div class="flex flex-row flex-wrap justify-center">
                        @foreach ($sponsors as $sponsor)
                            <div
                                class="flex justify-center flex-initial basis-full sm:basis-1/2 lg:basis-1/3 xl:basis-1/4 p-1 ">
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
        @endif

        {{-- CONTACT --}}
        @if ($event->landing->has_ssnn || $event->landing->has_contact)
            <div id="contact" class="pt-28 pb-10 px-4 md:px-10 lg:px-18 xl:px-40">
                <div
                    class="inline-flex flex-col text-4xl font-josefin font-bold text-terciary uppercase items-center gap-6 w-full">
                    <div>{{ $event->landing->contact_title }}</div>
                    <div>
                        <div class="border-b border-primary w-12 mb-1 -ml-2"></div>
                        <div class="border-b border-primary w-12 ml-2"></div>
                    </div>
                </div>
            </div>
            <div class="inline-flex flex-col-reverse lg:flex-row justify-center items-center w-full">
                <div class="max-w-md w-full">
                    <div class="w-28 h-28 lg:w-36 lg:h-36 mx-auto"><img
                            src="{{ Storage::url($event->logo_event) }}"></div>
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
                        @if ($event->landing->show_email)
                            <div class="flex items-center justify-center lg:justify-start">
                                <div class="text-xl text-primary"><a
                                        href="mailto:{{ $event->email }}">{{ $event->email }}</a></div>
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
                                        <button class="text-lg font-semibold uppercase bg-secondary py-3 w-full"
                                            type="submit">Enviar</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
    </div>
    @endif

    <div class="mb-28"></div>
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
        $(function() {
            // TOP BAR
            function setEqualHeight(columns) {
                var tallestcolumn = 0;
                columns.each(function() {
                    currentHeight = $(this).height();
                    if (currentHeight > tallestcolumn) {
                        tallestcolumn = currentHeight;
                    }
                });
                columns.height(tallestcolumn);
            }
            if (window.pageYOffset > 20) {
                $(".top-bar").addClass("scrolled")
            } else {
                $(".top-bar").removeClass("scrolled")
            }
            $(window).scroll(function() {
                if (window.pageYOffset > 20) {
                    $(".top-bar").addClass("scrolled")
                } else {
                    $(".top-bar").removeClass("scrolled")
                }
            });

            $('#menu-button').click(function() {
                $('#menu').toggleClass('show-menu')
            })

            $('.link-top-line').click(function() {
                $('.link-top-line.active').removeClass('active')
                $(this).addClass('active')
            })

            $('.calendar-date').click(function() {
                $('.calendar-date.active').removeClass('active')
                $(this).addClass('active')

                $('.calendar-details').addClass('hidden')
                $(`.calendar-details.${$(this).attr('id')}`).removeClass('hidden')

                $('.calendar-data').removeClass('border-b border-terciary/10 scale-y-100 px-28 py-4')
                $('.calendar-data').addClass('scale-y-0 p-0 h-0')
                $(`.calendar-data.calendar-details-1`).removeClass('scale-y-0 p-0 h-0')
                $(`.calendar-data.calendar-details-1`).addClass(
                    'border-b border-terciary/10 scale-y-100 px-28 py-4')

                setEqualHeight($('.calendar-details'))
            })

            $('.calendar-data-date').click(function() {
                $('.calendar-data').removeClass('border-b border-terciary/10 scale-y-100 px-28 py-4')
                $('.calendar-data').addClass('scale-y-0 p-0 h-0')
                $(`.calendar-data.${$(this).attr('id')}`).removeClass('scale-y-0 p-0 h-0')
                $(`.calendar-data.${$(this).attr('id')}`).addClass(
                    'border-b border-terciary/10 scale-y-100 px-28 py-4')

                setEqualHeight($('.calendar-details'))
            })

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
                                $('#confirmed-space').show();
                                $('#confirmation-space').hide();

                                // empty the form
                                $('#confirmation-form')[0].reset();
                                // hide modal
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
        })
    </script>

</body>

</html>
