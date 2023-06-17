<!DOCTYPE html>
<html>

<head>
    <title>Invitacion Brasil Formulario</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
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
        <div class="text-center font-bold mb-10 px-6">
            Rogamos completar el siguiente formulario para confirmar exitosamente su asistencia:
        </div>
        <form id="confirmation-form">
            <input type="hidden" name="event_id" value="{{ $event->id }}" />

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Nombre*</label>
                <input data-parsley-whitespace="trim"
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="text"
                    id="name" name="name" required />
            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Apellidos*</label>
                <input data-parsley-whitespace="trim"
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="text"
                    id="lastname" name="lastname" required />
            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Cargo*</label>
                <input data-parsley-whitespace="trim"
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="text"
                    id="job" name="job" required />
            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Empresa/Institución*</label>
                <input data-parsley-whitespace="trim"
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="text"
                    id="company" name="company" required />
            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-2">
                <label>Correo electrónico*</label>
                <input data-parsley-whitespace="trim"
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="email"
                    id="email" name="email" required />

            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Repetir Correo electrónico*</label>
                <input data-parsley-whitespace="trim" data-parsley-equalto="#email"
                    data-parsley-equalto-message="Ambos correos deben ser iguales."
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="email"
                    name="email" required>

            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Teléfono*</label>
                <input data-parsley-whitespace="trim"
                    data-parsley-pattern="^(\+\d{1,2}\s)?\(?\d{1}\)?[\s.-]?\d{4}[\s.-]?\d{4}$"
                    class="w-full h-8 outline-0 border border-gray-400 px-4 focus:border-secondary" type="text"
                    id="phone" name="phone" required />
            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Notas</label>
                <textarea data-parsley-whitespace="trim" class="w-full outline-0 border border-gray-400 px-4 focus:border-secondary"
                    name="notes" id="notes" rows="5"></textarea>
            </div>

            <div class="px-6 lg:px-8 xl:px-14 2xl:px-20 mb-8">
                <label>Adjunte su pase de movilidad habilitado*</label>
                <input class="filepond-pdf w-full" type="file" id="pass" name="pass"
                    placeholder="Pase Movilidad" required />
            </div>

            <div id="saveButton" class="flex flex-row px-6 lg:px-8 xl:px-14 2xl:px-20 mt-8">
                <div class="grow"></div>
                <button id="confirm-button" type="submit"
                    class="text-lg text-white font-semibold bg-secondary py-3 w-40 rounded-full">
                    Guardar
                </button>
            </div>

            <div id="saveMessage"
                class="hidden flex flex-row mx-6 lg:mx-8 xl:mx-14 2xl:mx-20 mt-8 text-lg text-white text-center font-semibold bg-secondary px-8 py-3 rounded-full mx-auto">
            </div>
        </form>
    </div>

    <div id="thanks"
        class="bg-white rounded-lg px-8 py-8 flex flex-col justify-center items-center gap-6 transition translate-x-[100vw] duration-0 top-0 max-h-0 overflow-hidden">
        <div class="text-3xl xl:text-4xl font-bold text-black">Registro recibido!</div>
        <div class="text-sm text-neutral-600 max-w-xs text-center">
            Hemos enviado un correo electrónico con la confirmación. Si no lo recibe dentro de los siguientes
            minutos por favor revisar su carpeta de SPAM.
        </div>
    </div>

    <!-- Load libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"
        integrity="sha256-pEdn/pJ2tyT37axbEIPkyUUfuG1yXR0+YV+h+jphem4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/i18n/es.js"
        integrity="sha256-dcJkxln8t7jxoFFA4jP3/ru8rFOlKpt478JM/wsMsgU=" crossorigin="anonymous"></script>

    <script>
        $(function() {
            //FilePond
            FilePond.setOptions({
                labelIdle: 'Arrastra y suelta tus archivos o <span class = "filepond--label-action"> Examinar <span>',
                labelInvalidField: 'El campo contiene archivos inválidos',
                labelFileWaitingForSize: 'Esperando tamaño',
                labelFileSizeNotAvailable: 'Tamaño no disponible',
                labelFileLoading: 'Cargando',
                labelFileLoadError: 'Error durante la carga',
                labelFileProcessing: 'Cargando',
                labelFileProcessingComplete: 'Carga completa',
                labelFileProcessingAborted: 'Carga cancelada',
                labelFileProcessingError: 'Error durante la carga',
                labelFileProcessingRevertError: 'Error durante la reversión',
                labelFileRemoveError: 'Error durante la eliminación',
                labelTapToCancel: 'toca para cancelar',
                labelTapToRetry: 'tocar para volver a intentar',
                labelTapToUndo: 'tocar para deshacer',
                labelButtonRemoveItem: 'Eliminar',
                labelButtonAbortItemLoad: 'Abortar',
                labelButtonRetryItemLoad: 'Reintentar',
                labelButtonAbortItemProcessing: 'Cancelar',
                labelButtonUndoItemProcessing: 'Deshacer',
                labelButtonRetryItemProcessing: 'Reintentar',
                labelButtonProcessItem: 'Cargar',
                labelMaxFileSizeExceeded: 'El archivo es demasiado grande',
                labelMaxFileSize: 'El tamaño máximo del archivo es {filesize}',
                labelMaxTotalFileSizeExceeded: 'Tamaño total máximo excedido',
                labelMaxTotalFileSize: 'El tamaño total máximo del archivo es {filesize}',
                labelFileTypeNotAllowed: 'Archivo de tipo no válido',
                fileValidateTypeLabelExpectedTypes: 'Espera {allButLastType} o {lastType}',
                imageValidateSizeLabelFormatError: 'Tipo de imagen no compatible',
                imageValidateSizeLabelImageSizeTooSmall: 'La imagen es demasiado pequeña',
                imageValidateSizeLabelImageSizeTooBig: 'La imagen es demasiado grande',
                imageValidateSizeLabelExpectedMinSize: 'El tamaño mínimo es {minWidth} × {minHeight}',
                imageValidateSizeLabelExpectedMaxSize: 'El tamaño máximo es {maxWidth} × {maxHeight}',
                imageValidateSizeLabelImageResolutionTooLow: 'La resolución es demasiado baja',
                imageValidateSizeLabelImageResolutionTooHigh: 'La resolución es demasiado alta',
                imageValidateSizeLabelExpectedMinResolution: 'La resolución mínima es {minResolution}',
                imageValidateSizeLabelExpectedMaxResolution: 'La resolución máxima es {maxResolution}',
            })
            FilePond.registerPlugin(FilePondPluginFileValidateType)
            const pdfElements = document.querySelectorAll('input.filepond-pdf')
            pdfElements.forEach((pdfElement) => {
                const pond = FilePond.create(pdfElement, {
                    acceptedFileTypes: ['application/pdf', 'image/*'],
                })
                pond.setOptions({
                    server: {
                        process: {
                            url: `${window.location.origin}/api/uploadFormImage`,
                        },
                        load: './public/image/',
                    },
                })
            })

            const form = $('#confirmation-form').parsley()

            // when the form is submitted
            var uuid = null
            $('#confirmation-form').on('submit', function(e) {

                // if the validator does not prevent form submit
                if (!e.isDefaultPrevented()) {
                    var url = '/api/participants/confirmFromForm'

                    // Disable Button
                    $('#confirm-button').prop('disabled', true)

                    // POST values in the background the the script URL
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $(this).serialize(),
                        success: function(data) {
                            // we recieve the type of the message: success x danger and apply it to the
                            var messageAlert = 'alert-' + data.type
                            var messageText = data.message
                            uuid = data.uuid

                            $('#saveMessage').html(`Su información ha sido recibida con éxito.<br>
                            Si no es redirigido a la página de confirmación por favor presione <a
                            href="/qr-brazil/${uuid}">aquí.</a>`)

                            $('#saveButton').hide()
                            $('#saveMessage').show()

                            window.location.href = `/qr-brazil/${uuid}`
                        },
                    })
                    return false
                } else {
                    console.log('Submit Prevented')
                }
            })

            $('#copy-link-button').click(function(e) {
                var dummy = document.createElement('textarea')
                document.body.appendChild(dummy)
                dummy.value = 'https://venoot-pro.work/venoot-chat?uuid=' + uuid
                dummy.select()
                document.execCommand('copy')
                document.body.removeChild(dummy)
            })
        })
    </script>
</body>

</html>
