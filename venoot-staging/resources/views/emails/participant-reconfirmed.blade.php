<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <!-- utf-8 works for most cases -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Forcing initial-scale shouldn't be necessary -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Use the latest (edge) version of IE rendering engine -->
        <title>Email Venoot</title>

        <!-- CSS Reset -->
        <style type="text/css">
            /* What it does: Remove spaces around the email design added by some email clients. */
            /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                height: 100% !important;
                width: 100% !important;
            }
            /* What it does: Stops email clients resizing small text. */
            * {
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }
            /* What it does: Forces Outlook.com to display emails full width. */
            .ExternalClass {
                width: 100%;
            }
            /* What is does: Centers email on Android 4.4 */
            div[style*="margin: 16px 0"] {
                margin: 0 !important;
            }
            /* What it does: Stops Outlook from adding extra spacing to tables. */
            table,
            td {
                mso-table-lspace: 0pt !important;
                mso-table-rspace: 0pt !important;
            }
            /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
            table {
                border-spacing: 0 !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                margin: 0 auto !important;
            }
            table table table {
                table-layout: auto;
            }
            /* What it does: Uses a better rendering method when resizing images in IE. */
            img {
                -ms-interpolation-mode: bicubic;
            }
            /* What it does: Overrides styles added when Yahoo's auto-senses a link. */
            .yshortcuts a {
                border-bottom: none !important;
            }
            /* What it does: Another work-around for iOS meddling in triggered links. */
            a[x-apple-data-detectors] {
                color: inherit !important;
            }
        </style>

        <!-- Progressive Enhancements -->
        <style type="text/css">
            /* What it does: Hover styles for buttons */
            .button-td,
            .button-a {
                transition: all 100ms ease-in;
            }
            .button-td:hover,
            .button-a:hover {
                background: #555555 !important;
                border-color: #555555 !important;
            }
        </style>
    </head>
    <body
        width="100%"
        height="100%"
        bgcolor="#e0e0e0"
        style="margin: 0;"
        yahoo="yahoo"
    >
        <table
            cellpadding="0"
            cellspacing="0"
            border="0"
            height="100%"
            width="100%"
            bgcolor="#e0e0e0"
            style="border-collapse:collapse;"
        >
            <tr>
                <td>
                    <center style="width: 100%;">
                        <!-- Visually Hidden Preheader Text : BEGIN -->
                        {{-- <div
                            style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;"
                        >
                            {{ $subject }}
                        </div> --}}
                        <!-- Visually Hidden Preheader Text : END -->

                        <div style="max-width: 600px;">
                            <!--[if (gte mso 9)|(IE)]>
                            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                            <tr>
                            <td>
                            <![endif]-->

                            <!-- Email Header : BEGIN -->
                            <table
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                align="center"
                                width="100%"
                                style="max-width: 600px;"
                            >
                                <tr>
                                    <td
                                        style="padding: 20px 0; text-align: center"
                                    >
                                        <img
                                            src="{{ Storage::url($event->logo_event) }}"
                                            width="200"
                                            height="50"
                                            alt="alt_text"
                                            border="0"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <!-- Email Header : END -->

                            <!-- Email Body : BEGIN -->
                            <table
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                align="center"
                                bgcolor="#ffffff"
                                width="100%"
                                style="max-width: 600px;"
                            >
                                <!-- Hero Image, Flush : BEGIN -->
                                <tr>
                                    <td class="full-width-image" align="center">
                                        <img
                                            src="{{ Storage::url($event->banner) }}"
                                            width="600"
                                            alt="alt_text"
                                            border="0"
                                            style="width: 100%; max-width: 600px; height: auto;"
                                        />
                                    </td>
                                </tr>
                                <!-- Hero Image, Flush : END -->

                                <!-- 1 Column Text : BEGIN -->
                                <tr>
                                    <td>
                                        <table
                                            cellspacing="0"
                                            cellpadding="0"
                                            border="0"
                                            width="100%"
                                        >
                                            <tr>
                                                <td
                                                    style="padding: 30px 0px 5px 0px; font-family: sans-serif; font-size: 24px; mso-height-rule: exactly; color: #555555; text-align: center"
                                                >
                                                {{ $event->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="padding: 30px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555; text-align: center"
                                                >
                                                    Estimado {{ $participant->data['name']}}{{ ' '.($participant->data['lastname']) }},<br />
                                                    te recordamos coordialmente que has sido invitado
                                                    al evento que se
                                                    celebrará el {{ $start_date->isoFormat('dddd, DD [de] MMMM [de] YYYY') }} a las {{ $start_time->isoFormat('HH:mm') }}
                                                    en {{ $event->location }}
                                                    <br /><br />
                                                    @if ($event->estimate->confirmation_form)
                                                    Si aun no has confirmado tu asistencia, por favor, hazlo presionando el
                                                    botón Confirmo Asistencia.
                                                    @elseif ($event->estimate->ticket_sale)
                                                    Si aun no has comprado entradas y estas interesado, visita nuestra pagina para hacerlo.
                                                    @endif
                                                    <br /><br />

                                                    <!-- Button : Begin -->

                                                    @if ($event->estimate->confirmation_form)
                                                    <table
                                                        cellspacing="0"
                                                        cellpadding="0"
                                                        border="0"
                                                        align="center"
                                                        style="margin: auto;"
                                                    >
                                                        <tr>
                                                            <td
                                                                style="border-radius: 3px; background: #222222; text-align: center;"
                                                                class="button-td"
                                                            >
                                                                <a
                                                                    href="{{ url("confirms/{$participation->uuid}") }}"
                                                                    style="background: #222222; border: 15px solid #222222; padding: 0 10px; color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;"
                                                                    class="button-a"
                                                                >
                                                                    <!--[if mso
                                                                        ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                    [endif]-->Confirmo
                                                                    Asistencia<!--[if mso
                                                                        ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                    [endif]-->
                                                                </a>
                                                            </td>
                                                            <td>&nbsp;</td>
                                                            <td
                                                                style="border-radius: 3px; background: #222222; text-align: center;"
                                                                class="button-td"
                                                            >
                                                                <a
                                                                    href="{{ url("unconfirms/{$participation->uuid}") }}"
                                                                    style="background: #222222; border: 15px solid #222222; padding: 0 10px;color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;"
                                                                    class="button-a"
                                                                >
                                                                    <!--[if mso
                                                                        ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                    [endif]-->No
                                                                    Confirmo<!--[if mso
                                                                        ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                    [endif]-->
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @elseif ($event->estimate->ticket_sale)
                                                    <table
                                                        cellspacing="0"
                                                        cellpadding="0"
                                                        border="0"
                                                        align="center"
                                                        style="margin: auto;"
                                                    >
                                                        <tr>
                                                            <td
                                                                style="border-radius: 3px; background: #222222; text-align: center;"
                                                                class="button-td"
                                                            >
                                                                <a
                                                                    href="{{ url("events/{$event->id}/landing") }}"
                                                                    style="background: #222222; border: 15px solid #222222; padding: 0 10px; color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;"
                                                                    class="button-a"
                                                                >
                                                                    <!--[if mso
                                                                        ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                    [endif]-->Ir al Evento<!--[if mso
                                                                        ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                    [endif]-->
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @endif

                                                    <!-- Button : END -->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- 1 Column Text : BEGIN -->

                                <!-- Two Even Columns : BEGIN -->
                                <tr>
                                    <td
                                        bgcolor="#ffffff"
                                        align="center"
                                        height="100%"
                                        valign="top"
                                        width="100%"
                                    >
                                        <!--[if mso]>
                        <table cellspacing="0" cellpadding="0" border="0" align="center" width="560">
                        <tr>
                        <td align="center" valign="top" width="560">
                        <![endif]-->

                                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                                    </td>
                                </tr>
                                <!-- Two Even Columns : END -->
                            </table>

                            <!-- Email Body : END -->

                            <!-- Email Footer : BEGIN -->
                            <table
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                align="center"
                                width="100%"
                                style="max-width: 680px;"
                            >
                                <tr>
                                    <td
                                        style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;"
                                    >
                                        <webversion
                                            style="color:#cccccc; text-decoration:underline; font-weight: bold;"
                                            >{{ url("events/{$event->id}/landing") }}</webversion
                                        >
                                        <br />
                                        <br />
                                        {{ $event->company->name }}<br />
                                        <span class="mobile-link--footer"
                                            >{{ $event->company->address }}</span
                                        >
                                        <br />
                                        <br />
                                        <unsubscribe style="color:#888888; text-decoration:underline;">
                                            <a href="{{ url("unsubscribe/{$participation->uuid}") }}">Eliminar de la lista</a>
                                        </unsubscribe>
                                    </td>
                                </tr>
                            </table>

                            <!-- Email Footer : END -->

                            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
                        </div>
                    </center>
                </td>
            </tr>
        </table>
    </body>
</html>
