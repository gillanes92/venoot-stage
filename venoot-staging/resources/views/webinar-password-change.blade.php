<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Cambio PAssword">
    <meta name="author" content="Venoot">
    <meta name="generator" content="AcelleSystemLayouts">
    <title>Cambio Password</title>

    <!-- Bootstrap core CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            content: [
                './**/*.{html,js}',
            ],
        }
    </script>
</head>

<body>
    <div class="w-full max-w-xs">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" id="password-form" method="post"
            action="/api/webinars/password-change">
            <input type="hidden" id="order_id" value="{{ $user->customer_id }}">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Contraseña
                </label>
                <input
                    class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    id="password" type="password" placeholder="******************">
                {{-- <p class="text-red-500 text-xs italic">Please choose a password.</p> --}}
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Repetir Contraseña
                </label>
                <input
                    class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    id="repeat_password" type="password" placeholder="******************">
                {{-- <p class="text-red-500 text-xs italic">Please choose a password.</p> --}}
            </div>
            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">Enviar</button>
            </div>
        </form>
        <p class="text-center text-gray-500 text-xs">
            &copy;2023 Venoot. Todos los derechos reservados.
        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <script>
        $(function() {

            $('#password-form').on('submit', function(e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = "/api/webinars/password-change";
                    var order_id = $('#order_id').val();
                    // POST values in the background the the script URL
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            order_id,
                            password: $('#password').val(),
                            repeat_password: $('#repeat_password').val(),
                        },
                        success: function(data) {
                            window.location = `/password-changed/${order_id}`
                        }
                    });
                    return false;
                }
            })
        })
    </script>
</body>

</html>
