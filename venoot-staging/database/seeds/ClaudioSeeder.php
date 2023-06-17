<?php

use Illuminate\Database\Seeder;

class ClaudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();

        $css_events = [
            [559, '1', 6, 8, 'color:#FFFFFF;'],
            [560, '1', 6, 9, 'color:#FFFFFF;'],
            [561, '1', 6, 13, 'color:#FFFFFF;'],
            [562, '1', 6, 14, 'color:#F1383B;'],
            [563, '1', 6, 15, 'color:#FFFFFF;'],
            [564, '1', 6, 16, 'color:#11244D;'],
            [565, '1', 6, 17, 'background-color:#d627d6;'],
            [566, '1', 6, 18, 'color:white;background-color:red;'],
            [567, '1', 6, 19, 'background-color:darkred;'],
            [568, '1', 6, 20, 'font-size:36px;'],
            [569, '1', 6, 21, 'font-size:24px;color:#FFFFFF;'],
            [570, '1', 6, 22, 'background-color:#4A4A4A;'],
            [571, '1', 6, 23, 'background-color:#131313;'],
            [572, '1', 6, 24, 'background-color:red;'],
            [573, '1', 6, 25, 'background-color:darkred;'],
            [574, '1', 6, 26, 'color:white;'],
            [575, '1', 6, 27, 'background-color:red;'],
            [576, '1', 6, 28, 'background-color:darkred;'],
            [577, '1', 6, 29, 'background-color:red;color:#fff;'],
            [578, '1', 6, 30, 'background-color:#0e1b4d;color:#fff;'],
            [579, '1', 6, 31, 'font-size:16px;color:gray;font-weight:normal;'],
            [580, '1', 6, 32, 'font-size:18px;color:#fa0526;font-weight:bold;'],
            [581, '1', 6, 33, 'color:#C9C9C9;'],
            [582, '1', 6, 34, 'color:#FFFFFF;text-decoration:none;'],
            [583, '1', 6, 35, 'font-size:18px;color:white;font-weight:bold;font-style:italic;']
        ];

        $css_events = array_map(function ($css_events) use ($now) {
            return [
                'id_css' => $css_events[0],
                'id_template' => $css_events[1],
                'id_evento' => $css_events[2],
                'id_elemento' => $css_events[3],
                'propiedades' => $css_events[4],
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }, $css_events);

        \DB::table('css_events')->insert($css_events);

        $elementos = [
            [8, '1', 'Titulo', 'Titulo de Landing', '#intro', '#intro h1', 'color:#FFFFFF;'],
            [9, '1', 'Titulo', 'Titulo Destacado', '#intro', '#intro h1 span', 'color:#FFFFFF;'],
            [13, '1', 'Textos Intro', 'Propiedades del Texto', '#intro', '#intro p', 'color:#FFFFFF;'],
            [14, '1', 'Textos Intro', 'Propiedades del Link', '#intro', '#intro a', 'color:#F1383B;'],
            [15, '1', 'Textos Intro', 'Propiedades sobre Link', '#intro', '#intro a:hover', 'color:#FFFFFF;'],
            [16, '1', 'Titulo Secciones', '', '#venue', 'section div h2', 'color:#11244D;'],
            [17, '1', 'Barra Navegacion', 'Fondo', '#venue', '#header.header-scrolled', 'background-color:#131313;'],
            [18, '1', 'Boton TOP', 'Propiedades', '#', '.back-to-top', 'color:white;background-color:red;'],
            [19, '1', 'Boton TOP', 'Sobre Boton', '#', '.back-to-top:hover', 'background-color:darkred;'],
            [20, '1', 'Pie de Landing', 'Titulo', '#footer', '#footer .section-header h2', 'font-size:36px;'],
            [21, '1', 'Pie de Landing', 'Textos', '#', '#footer p', 'font-size:24px;color:#FFFFFF;'],
            [22, '1', 'Pie de Landing', 'Fondo', '#', '#footer .footer-top', 'background-color:#4A4A4A;'],
            [23, '1', 'Pie de Landing', 'Barra inferior', '#', '#footer', 'background-color:#131313;'],
            [24, '1', 'Formulario de Contacto', 'Color boton', '#contact', '#contact .form button[type=\"submit\"]', 'background-color:red;'],
            [25, '1', 'Formulario de Contacto', 'Sobre Boton', '#contact', '#contact .form button[type=\"submit\"]:hover', 'background-color:darkred;'],
            [26, '1', 'Seccion Confirmar', 'Textos', '#subscribe', '#subscribe .section-header h2, #subscribe p', 'color:white;'],
            [27, '1', 'Seccion Confirmar', 'Boton', '#subscribe', '#subscribe_button2', 'background-color:red;'],
            [28, '1', 'Seccion Confirmar', 'Sobre boton', '#subscribe', '#subscribe_button2:hover', 'background-color:darkred;'],
            [29, '1', 'Seccion Agenda', 'Fecha destacada', '#schedule', '#schedule .nav-tabs a.active', 'background-color:red;color:#fff;'],
            [30, '1', 'Seccion Agenda', 'Otras Fechas', '#schedule', '#schedule .nav-tabs a', 'background-color:#0e1b4d;color:#fff;'],
            [31, '1', 'Seccion Agenda', 'Hora', '#schedule', 'time', 'font-size:16px;color:gray;font-weight:normal;'],
            [32, '1', 'Seccion Agenda', 'Titulo de Charla', '#schedule', 'section div h4', 'font-size:18px;color:blue;font-weight:bold;'],
            [33, '1', 'Barra Navegacion', 'Link', '#', '#nav-menu-container a', 'color:#C9C9C9;'],
            [34, '1', 'Barra Navegacion', 'Sobre Link', '#', '#nav-menu-container a:hover', 'color:#FFFFFF;text-decoration:none;'],
            [35, '1', 'Lugar', '', '#venue ', '#venue .venue-info p', 'font-size:18px;color:white;font-weight:bold;font-style:italic;']
        ];

        $elementos = array_map(function ($elementos) use ($now) {
            return [
                'id_elemento' => $elementos[0],
                'id_template' => $elementos[1],
                'desc_elemento' => $elementos[2],
                'description' => $elementos[3],
                'tag' => $elementos[4],
                'identificador' => $elementos[5],
                'propiedades' => $elementos[6],
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }, $elementos);

        \DB::table('elementos')->insert($elementos);
    }
}
