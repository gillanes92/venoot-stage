const mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/main.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .stylus('resources/stylus/main.styl', 'public/css')
    .styles(
        [
            'resources/js/plugins/bootstrap/css/bootstrap.min.css',
            'resources/js/plugins/animate/animate.min.css',
            'resources/js/plugins/venobox/venobox.css',
            'resources/js/plugins/owlcarousel/assets/owl.carousel.min.css',
            'resources/css/style.css',
        ],
        'public/css/landing.css'
    )
    .styles(
        [
            'resources/js/plugins/bootstrap/css/bootstrap.min.css',
            'resources/js/plugins/animate/animate.min.css',
            'resources/js/plugins/venobox/venobox.css',
            'resources/js/plugins/owlcarousel/assets/owl.carousel.min.css',
        ],
        'public/css/static-landing.css'
    )
    .scripts(
        [
            'resources/js/plugins/jquery/jquery.min.js',
            'resources/js/plugins/jquery/jquery-migrate.min.js',
            'resources/js/plugins/bootstrap/js/bootstrap.bundle.min.js',
            'resources/js/plugins/easing/easing.min.js',
            'resources/js/plugins/superfish/hoverIntent.js',
            'resources/js/plugins/superfish/superfish.min.js',
            'resources/js/plugins/wow/wow.min.js',
            'resources/js/plugins/venobox/venobox.min.js',
            'resources/js/plugins/owlcarousel/owl.carousel.min.js',
            'resources/js/plugins/contactform/contactform.js',
            'resources/js/main.js',
        ],
        'public/js/landing.js'
    )
    // .styles(['resources/js/plugins/builderjs/builder.css'], 'public/builder/builder.css')
    // .scripts(['resources/js/plugins/builderjs/builder.js'], 'public/builder/builder.js')
    .options({
        postCss: [require('autoprefixer')],
    })
    .sourceMaps()
