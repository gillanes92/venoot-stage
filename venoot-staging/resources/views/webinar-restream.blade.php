<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $event->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">

    <!-- HTML Meta Tags -->
    <title>{{ $event->name }}</title>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#DA2F37',
                        secondary: '#505140',
                        terciary: '#0C0E0B',
                    },
                }
            }
        }
    </script>
</head>

<body class="is-preload overflow-x-hidden">
    <div class="flex flex-row w-full h-screen p-1 gap-1">
        <div class="grow border-primary border-2 rounded">
            <iframe
                title="Venoot Studio"
                src="https://studio.restream.io/"
                class="h-full w-full rounded">
            </iframe>
        </div>
        <div class="w-80 h-full">
            <div class="border-primary border-2 w-full h-full rounded flex justify-center items-center">
                <div class="text-4xl font-semibold text-center">
                    Herramientas Venoot
                </div>
            </div>            
        </div>
    </div>
</body>

</html>

