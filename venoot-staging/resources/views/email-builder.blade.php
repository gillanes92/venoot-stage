<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="keywords" />
        <meta content="" name="description" />
        <title>{{ $template->name }}</title>

        <!-- Favicons -->
        <link href="{{ url('/images/favicon.png') }}" rel="icon" />
        <link href="{{ url('/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800"
            rel="stylesheet"
        />

        <!-- Editor -->
        <link href="/builderjs/builder.css" rel="stylesheet" />
        <script type="text/javascript" src="/builderjs/builder.js"></script>
    </head>

    <body>
        <script>
            // List of available templates
            var templates = [
                // {
                //     name: 'Blank',
                //     url: '{{ URL::to('/') }}/templates/layouts/0_0_blank',
                //     thumbnail: '{{ URL::to('/') }}/templates/layouts/0_0_blank/thumb.png'
                // },

                {
                    name: 'Pricing Offer',
                    url: '{{ URL::to('/') }}/templates/layouts/0_1_pricing_offer',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/0_1_pricing_offer/thumb.png'
                },

                {
                    name: 'With Table',
                    url: '{{ URL::to('/') }}/templates/layouts/0_2_with_table',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/0_2_with_table/thumb.png'
                },

                {
                    name: 'Form Builder',
                    url: '{{ URL::to('/') }}/templates/layouts/0_3_form_builder',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/0_3_form_builder/thumb.png'
                },

                {
                    name: '1-2-1 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/1_2_1_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/1_2_1_column/thumb.png'
                },

                {
                    name: '1-2 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/1_2_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/1_2_column/thumb.png'
                },

                {
                    name: '1-3-1 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/1_3_1_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/1_3_1_column/thumb.png'
                },

                {
                    name: '1-3-2 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/1_3_2_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/1_3_2_column/thumb.png'
                },

                {
                    name: '1-3 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/1_3_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/1_3_column/thumb.png'
                },

                {
                    name: '1 Column',
                    url: '{{ URL::to('/') }}/templates/layouts/1_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/1_column/thumb.png'
                },

                {
                    name: '2-1-2 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/2_1_2_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/2_1_2_column/thumb.png'
                },

                {
                    name: '2-1 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/2_1_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/2_1_column/thumb.png'
                },

                {
                    name: '2 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/2_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/2_column/thumb.png'
                },

                {
                    name: '3-1-3 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/3_1_3_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/3_1_3_column/thumb.png'
                },

                {
                    name: '3 Columns',
                    url: '{{ URL::to('/') }}/templates/layouts/3_column',
                    thumbnail: '{{ URL::to('/') }}/templates/layouts/3_column/thumb.png'
                },
            ];


            var builder = new Editor({
                root: '/builderjs/',
                logo: '/images/icon-logo.svg',
                token: '{{ $token }}',
                uploadAssetMethod: 'PUT',
                saveMethod: 'PUT',

                templates,

                @if ($template->content)
                url: '{{ URL::to('/') }}/templates/{{ $template->id }}?token={{ $token }}',
                @endif

                uploadAssetUrl: '{{ URL::to('/') }}/api/uploadTemplateImage?token={{ $token }}',
                saveUrl: '{{ URL::to('/') }}/api/templates/{{ $template->id }}?token={{ $token }}',
                testUrl: '{{ URL::to('/') }}/api/templates/{{ $template->id }}/testEmail?token={{ $token }}',
                backUrl: '{{ URL::to('/') }}/templates',

                tags: [
                    {tag: '{URL-LANDING}', type: 'label'},
                    {tag: '{TITULO}', type: 'label'},
                    {tag: '{LOCACION}', type: 'label'},
                    {tag: '{NOMBRE}', type: 'label'},
                    {tag: '{APELLIDO}', type: 'label'},
                    {tag: '{QR-NUMERO}', type: 'label'},
                    {tag: '{QR-EVENTO}', type: 'label'},
                    {tag: '{CONFIRMAR-SI}', type: 'label'},
                    {tag: '{CONFIRMAR-NO}', type: 'label'},
                    {tag: '{INVITAR}', type: 'label'},
                ],
            });
            builder.init();
        </script>
    </body>
</html>
