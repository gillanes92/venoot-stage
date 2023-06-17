<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <!-- utf-8 works for most cases -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Forcing initial-scale shouldn't be necessary -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Use the latest (edge) version of IE rendering engine -->
        <title>EmailTemplate-Responsive</title>

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
                -ms-text-size-adjust: 100%;S
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

            /* Media Queries */
            @media screen and (max-width: 600px) {
                .email-container {
                    width: 100% !important;
                }

                /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
                .fluid,
                .fluid-centered {
                    max-width: 100% !important;
                    height: auto !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
                /* And center justify these ones. */
                .fluid-centered {
                    margin-left: auto !important;
                    margin-right: auto !important;
                }

                /* What it does: Forces table cells into full-width rows. */
                .stack-column,
                .stack-column-center {
                    display: block !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    direction: ltr !important;
                }
                /* And center justify these ones. */
                .stack-column-center {
                    text-align: center !important;
                }

                /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
                .center-on-narrow {
                    text-align: center !important;
                    display: block !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                    float: none !important;
                }
                table.center-on-narrow {
                    display: inline-block !important;
                }
            }
        </style>
    </head>
    <body bgcolor="#e0e0e0" width="100%" style="margin: 0;" yahoo="yahoo">
        <table
            bgcolor="#e0e0e0"
            cellpadding="0"
            cellspacing="0"
            border="0"
            height="100%"
            width="100%"
            style="border-collapse:collapse;"
        >
            <tr>
                <td>
                    <center style="width: 100%;">
                        <!-- Visually Hidden Preheader Text : BEGIN -->
                        <div
                            style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;"
                        >
                            (Opcional) Este texto es el extracto que se
                            visualiza en los emails
                        </div>
                        <!-- Visually Hidden Preheader Text : END -->

                        <!-- Email Header : BEGIN -->
                        <table
                            align="center"
                            width="600"
                            class="email-container"
                        >
                            <tr>
                                <td style="padding: 20px 0; text-align: center">
                                    <img
                                        src={{ Storage::url($event->logo_event) }}
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
                            width="600"
                            class="email-container"
                        >
                            <!-- Hero Image, Flush : BEGIN -->
                            <tr>
                                <td class="full-width-image">
                                    <img
                                        src={{ Storage::url($event->banner) }}
                                        width="600"
                                        alt="alt_text"
                                        border="0"
                                        align="center"
                                        style="width: 100%; max-width: 600px; height: auto;"
                                    />
                                </td>
                            </tr>
                            <!-- Hero Image, Flush : END -->

                            <!-- 1 Column Text : BEGIN -->
                            <tr>
                                <td
                                    style="padding: 40px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555;"
                                >
                                    Estimado {{ $participant->data['name']}}{{ ' '.($participant->data['lastname']) }} en el
                                    siguiente email podrás descargar los QRs
                                    asociados a tu acceso al evento, presiona
                                    sobre el botón para poder descargar este
                                    documento en formato PDF. <br />
                                    <br />
                                    <!-- Button : Begin -->

                                    <table
                                        cellspacing="0"
                                        cellpadding="0"
                                        border="0"
                                        align="center"
                                        style="margin: auto"
                                    >
                                        <tr>
                                            @foreach($tickets as $ticket)
                                                <td
                                                    style="border-radius: 3px; background: #222222; text-align: center;"
                                                    class="button-td"
                                                >
                                                    <a
                                                        href="{{ url("qr/bought-ticket/{$ticket->uuid}") }}"
                                                        style="background: #222222; border: 15px solid #222222; padding: 0 10px;color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold; margin-right: 5px;"
                                                        class="button-a"
                                                    >
                                                        <!--[if mso
                                                            ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                        [endif]-->Ticket #{{ $loop->iteration }}<!--[if mso
                                                            ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                        [endif]-->
                                                    </a>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>

                                    @if ($event->invitees > 0 && !$isInvitee)
                                        <br />
                                        <!-- Button : Begin -->

                                        <table
                                            cellspacing="0"
                                            cellpadding="0"
                                            border="0"
                                            align="center"
                                            style="margin: auto"
                                        >
                                            <tr>
                                                <td
                                                    style="border-radius: 3px; background: #222222; text-align: center;"
                                                    class="button-td"
                                                >
                                                    <a
                                                        href="{{ url('invitees\/').$participation->uuid }}"
                                                        style="background: #222222; border: 15px solid #222222; padding: 0 10px;color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;"
                                                        class="button-a"
                                                    >
                                                        <!--[if mso
                                                            ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                        [endif]-->CONFIRMAR CORRES DE INVITADOS<!--[if mso
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
                            <!-- 1 Column Text : BEGIN -->

                            <!-- Background Image with Text : BEGIN -->
                            <tr>
                                <td
                                    {{-- background={{ Storage::url($event->banner) }} --}}
                                    bgcolor="#222222"
                                    valign="middle"
                                    style="text-align: center; background-position: center center !important; background-size: cover !important;"
                                >
                                    <!--[if gte mso 9]>
                    <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:175px; background-position: center center !important;">
                    <v:fill type="tile" src="assets/Responsive/Image_600x230.png" color="#222222" />
                    <v:textbox inset="0,0,0,0">
                    <![endif]-->

                                    <div>
                                        <table
                                            align="center"
                                            border="0"
                                            cellpadding="0"
                                            cellspacing="0"
                                            width="100%"
                                        >
                                            <tr>
                                                <td
                                                    valign="middle"
                                                    style="text-align: center; padding: 40px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;"
                                                >
                                                    Este código es único e
                                                    irrepetible, está asociado a
                                                    la persona a la cual se
                                                    envía este mensaje y será
                                                    válido sólo para el día y la
                                                    hora que indica este
                                                    documento.
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!--[if gte mso 9]>
                    </v:textbox>
                    </v:rect>
                    <![endif]-->
                                </td>
                            </tr>
                            <!-- Background Image with Text : END -->

                            <!-- Two Even Columns : BEGIN -->
                            <tr>
                                <td
                                    align="center"
                                    valign="top"
                                    style="padding: 10px;"
                                >
                                    &nbsp;
                                </td>
                            </tr>
                            <!-- Two Even Columns : END -->

                            <!-- Three Even Columns : BEGIN -->
                            <tr></tr>
                            <!-- Three Even Columns : END -->

                            <!-- Thumbnail Left, Text Right : BEGIN -->
                            <tr>
                                <td
                                    dir="ltr"
                                    align="center"
                                    valign="top"
                                    width="100%"
                                    style="padding: 10px;"
                                >
                                    <table
                                        align="center"
                                        border="0"
                                        cellpadding="0"
                                        cellspacing="0"
                                        width="100%"
                                    >
                                        <tr>
                                            <td
                                                width="33.33%"
                                                class="stack-column-center"
                                            >
                                                <table
                                                    align="center"
                                                    border="0"
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                    width="100%"
                                                >
                                                    <tr>
                                                        <td
                                                            dir="ltr"
                                                            valign="top"
                                                            style="padding: 0 10px;"
                                                        >
                                                            <img
                                                                src={{ Storage::url($event->logo_event) }}
                                                                width="170"
                                                                height=""
                                                                alt="alt_text"
                                                                border="0"
                                                                class="center-on-narrow"
                                                            />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td
                                                width="66.66%"
                                                class="stack-column-center"
                                            >
                                                <table
                                                    align="center"
                                                    border="0"
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                    width="100%"
                                                >
                                                    <tr>
                                                        <td
                                                            dir="ltr"
                                                            valign="top"
                                                            style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555; padding: 10px; text-align: left;"
                                                            class="center-on-narrow"
                                                        >
                                                            <strong
                                                                style="color:#111111;"
                                                                >{{ $event->name }}</strong
                                                            >
                                                            <br />
                                                            <br />
                                                            {{ $event->description }} <br />
                                                            <br />
                                                            <!-- Button : Begin -->

                                                            <table
                                                                cellspacing="0"
                                                                cellpadding="0"
                                                                border="0"
                                                                class="center-on-narrow"
                                                                style="float:left;"
                                                            >
                                                                <tr>
                                                                    <td
                                                                        style="border-radius: 3px; background: #222222; text-align: center;"
                                                                        class="button-td"
                                                                    >
                                                                        <a
                                                                            href="{{ url('events/{$event->id}/landing') }}"
                                                                            style="background: #222222; border: 15px solid #222222; padding: 0 10px;color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;"
                                                                            class="button-a"
                                                                        >
                                                                            <!--[if mso
                                                                                ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                            [endif]-->Ir
                                                                            a
                                                                            Ver
                                                                            el
                                                                            Evento<!--[if mso
                                                                                ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                            [endif]-->
                                                                        </a>
                                                                    </td>

                                                                    @if ($participant_order->facto_link)
                                                                    <td
                                                                        style="border-radius: 3px; background: #222222; text-align: center;"
                                                                        class="button-td"
                                                                    >
                                                                        <a
                                                                            href="{{ $participant_order->facto_link }}"
                                                                            style="background: #222222; border: 15px solid #222222; padding: 0 10px;color: #ffffff; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;"
                                                                            class="button-a"
                                                                        >
                                                                            <!--[if mso
                                                                                ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                            [endif]-->Ir a la Boleta<!--[if mso
                                                                                ]>&nbsp;&nbsp;&nbsp;&nbsp;<!
                                                                            [endif]-->
                                                                        </a>
                                                                    </td>
                                                                    @endif
                                                                </tr>
                                                            </table>

                                                            <!-- Button : END -->
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- Thumbnail Left, Text Right : END -->

                            <!-- Thumbnail Right, Text Left : BEGIN -->
                            <tr>
                                <td
                                    dir="rtl"
                                    align="center"
                                    valign="top"
                                    width="100%"
                                    style="padding: 10px;"
                                >
                                    &nbsp;
                                </td>
                            </tr>
                            <!-- Thumbnail Right, Text Left : END -->
                        </table>

                        <!-- Email Body : END -->

                        <!-- Email Footer : BEGIN -->
                        <table
                            align="center"
                            width="600"
                            class="email-container"
                        >
                            <tr>
                                <td
                                    style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;"
                                >
                                    <webversion
                                        style="color:#cccccc; text-decoration:underline; font-weight: bold;"
                                        >Ver en página web</webversion
                                    >
                                    <br />
                                    <br />
                                    {{ $event->company->name }}<br />
                                    <span class="mobile-link--footer"
                                        >{{ $event->address }}</span
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
                    </center>
                </td>
            </tr>
        </table>
    </body>
</html>
