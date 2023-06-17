<!DOCTYPE html>
<html>

<head>
    <title>Confirmación Brasil</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#124D7E',
                        secondary: '#3967BF',
                        terciary: '#3769C5',
                    },
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        .filepond--credits {
            display: none;
        }

        label {
            @apply block font-bold mb-1 ml-2;
        }

        .parsley-errors-list {
            @apply text-primary text-xs;
        }
    </style>
</head>

<body class="is-preload overflow-x-hidden">
    <div class="container mx-auto mb-10 lg:py-2 xl:py-6 2xl:py-10">
        <div class="bg-primary h-2 w-full"></div>
        <div class="w-full">
            <img src="/images/logo-brazil.jpg" class="mx-auto mt-10 2xl:mt-20 mb-10" />
        </div>

        <div class="text-lg font-bold text-center mb-10 px-6 lg:px-8 xl:px-14 2xl:px-20">
            Estimado/a {{ $participant->data['name'] }} {{ $participant->data['lastname'] }}
        </div>

        <div class="text-xl font-bold text-center mb-10 px-6 lg:px-8 xl:px-14 2xl:px-20">
            ¡Gracias! Hemos recibido su confirmación.
        </div>

        <div class="text-lg text-center mb-10 px-6 lg:px-8 xl:px-14 2xl:px-20">
            En breve recibirá también por correo este código QR, que comprueba que su confirmación ha sido exitosa y
            el cual deberá presentar en el sector acreditación para agilizar su ingreso.
        </div>

        <div class=>
            <img src="data:image/png;base64, {!! base64_encode(
                QrCode::format('png')->size(400)->generate($uuid),
            ) !!}" width="600" alt="CodigoQR" border="0"
                style="width: 100%; max-width: 300px;" title="CodigoQR" class="mx-auto" />
        </div>

        <div class="bg-primary h-2 w-full"></div>
    </div>
</body>

</html>
