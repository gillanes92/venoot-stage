<!doctype html>
<html>
    <head>
    <meta charset="UTF-8">
    <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Use the latest (edge) version of IE rendering engine -->
    <title>Ticket {{ $event->name }}</title>

    <!-- CSS Reset -->
    <style type="text/css">
/* What it does: Remove spaces around the email design added by some email clients. */
      /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
html,  body {
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
table,  td {
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
label {
	font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, "sans-serif";
}
a {
	color: #333;
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
		.heading-section-blanco .subheading{
				margin-top: 20px;
				margin-bottom: 20px !important;
				display: inline-block;
				font-size: 24px;
				font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, "sans-serif";
				text-transform: uppercase;
				letter-spacing: 2px;
				color: rgba(0,0,0,.4);
				position: relative;
		}

		.heading-section .subheading{
				margin-top: 20px;
				margin-bottom: 20px !important;
				display: inline-block;
				font-size: 24px;
				font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, "sans-serif";
				text-transform: uppercase;
				letter-spacing: 2px;
				color: rgba(255,255,255,.4);
				position: relative;
		}
		.heading-section .subheading::after{
				position: absolute;
				left: 0;
				right: 0;
				bottom: -10px;
				content: '';
				width: 100%;
				height: 3px;
				background: #FF0000;
				margin: 0 auto;
		}
		.heading-section-blanco .subheading::after{
				position: absolute;
				left: 0;
				right: 0;
				bottom: -10px;
				content: '';
				width: 100%;
				height: 3px;
				background: #FF0000;
				margin: 0 auto;
		}
        /* Media Queries */
        @media screen and (max-width : 600px ){

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
    <table bgcolor="#e0e0e0" cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" style="border-collapse:collapse;">
      <tr>
        <td><center style="width: 100%;">

            <!-- Visually Hidden Preheader Text : BEGIN -->
            <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;"> (Opcional) Este texto es el extracto que se visualiza en los emails </div>
            <!-- Visually Hidden Preheader Text : END -->

            <!-- Email Header : BEGIN -->
            <table align="center" width="600" class="email-container">
            <tr>
                <td style="padding: 20px 0; text-align: center"><img src="images/Image_200x50.png" width="200" height="50" alt="alt_text" title="Logo Empresa" border="0"></td>
              </tr>
          </table>
            <!-- Email Header : END -->

            <!-- Email Body : BEGIN -->
            <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="600" class="email-container">

            <!-- Hero Image, Flush : BEGIN -->
            <tr>
            <td class="full-width-image"><center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(400)->generate($uuid)) !!} " width="600" alt="CodigoQR" border="0" style="width: 100%; max-width: 300px; height: auto; margin: 5px;" title="CodigoQR"><br><label style="color: #000;">{{ $uuid }}</label></center></td>
              </tr>
            <!-- Hero Image, Flush : END -->

            <!-- 1 Column Text : BEGIN -->
            <tr> </tr>
            <!-- 1 Column Text : BEGIN -->

            <!-- Background Image with Text : BEGIN -->
            <tr>
                <td bgcolor="#222222" valign="middle" style="text-align: center; background-position: center center !important; background-size: cover !important;"><!--[if gte mso 9]>
                    <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:175px; background-position: center center !important;">
                    <v:fill type="tile" src="assets/Responsive/Image_600x230.png" color="#222222" />
                    <v:textbox inset="0,0,0,0">
                    <![endif]-->
                <div>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
                        <td valign="middle"><div class="heading-section"><span class="subheading">IMPORTANTE</span></div></td>
                      </tr>
                    <tr>
                        <td valign="middle" style="text-align: center; padding: 40px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;"> Este código es único e irrepetible, está asociado a la persona a la cual se envía este mensaje y será válido sólo para el día y la hora que indica este documento.</td>
                      </tr>
                  </table>
                  </div>

                <!--[if gte mso 9]>
                    </v:textbox>
                    </v:rect>
                    <![endif]--></td>
              </tr>
			<tr>
                <td bgcolor="#FFFFFF" valign="middle" style="text-align: center; background-position: center center !important; background-size: cover !important;"><!--[if gte mso 9]>
                    <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;height:175px; background-position: center center !important;">
                    <v:fill type="tile" color="#FFFFFF" />
                    <v:textbox inset="0,0,0,0">
                    <![endif]-->

                <div>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
                        <td valign="middle"><div class="heading-section-blanco"><span class="subheading">DATOS DEL EVENTO</span></div></td>
                    </tr>
					<tr>
                    <td valign="middle" align="center" style="text-align: center; padding: 20px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #666; text-transform: uppercase;">{{ $event->name }}</td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center" style="padding: 20px; font-family: sans-serif; font-size: 12px; mso-height-rule: exactly; line-height: 20px; color: #666;"><p><p><label>Ubicación: </label><b>Galería</b></p><p><label>{{ $event->start_date->format('d') }}{{ $event->start_date->day != $event->end_date->day ? ' a '.$event->end_date->format('d') : '' }} {{ $event->start_date->locale('es')->monthName }}{{ $event->start_date->month != $event->end_date->month ? ' a '.$event->end_date->locale('es')->monthName : '' }}</label><br><label>Lugar: {{ $event->location }}</label><br><label>Desde las {{ $event->start_time->format('H:i') }}Hrs.</label></p></td>

                    </tr>
                  </table>
                  </div>
				<div>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
                        <td colspan="2" valign="middle"><div class="heading-section-blanco"><span class="subheading">DATOS DEL COMPRADOR</span></div></td>
                    </tr>
					<tr>
                    <td colspan="2" valign="middle" align="center" style="text-align: center; padding-top: 20px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #666; text-transform: uppercase;">{{ $participant->data['name'] }} {{ $participant->data['lastname'] }}</td>
                    </tr>
                    <tr>
                    <td colspan="2" valign="middle" align="center" style="text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #666; text-transform: none;"><p><label>{{ $participant->data['email'] }}</label></p></td>
                    </tr>
                  </table>
                </div>

                <!--[if gte mso 9]>
                    </v:textbox>
                    </v:rect>
                    <![endif]--></td>
             </tr>
            <!-- Background Image with Text : END -->

            <!-- Two Even Columns : BEGIN -->
            <tr>
                <td align="center" valign="top" style="padding: 10px;">&nbsp;</td>
            </tr>
            <!-- Thumbnail Right, Text Left : END -->

          </table>
            <!-- Email Body : END -->

            <!-- Email Footer : BEGIN -->
            {{-- <table align="center" width="600" class="email-container">
            <tr>
                <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;"><webversion style="color:#888888; text-decoration:underline;"><a href="mailto:">Enviar a mi Email</a></webversion><br><webversion style="color:#888888; text-decoration:underline;"><a href="#">Descargar PDF del QR</a></webversion>
                <br>
                <br>
                Nombre de la empresa<br>
                <span class="mobile-link--footer">Dirección de la empresa que está en el sistema</span> <br>
                <br>
                </td>
              </tr>
          </table> --}}
            <!-- Email Footer : END -->

          </center></td>
      </tr>
    </table>
</body>
</html>
